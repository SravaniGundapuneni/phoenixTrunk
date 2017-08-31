<?php
/**
 * Main Legacy Adapter
 *
 * The class that will handle backwards compatibility for the new architecture in regards to what class.Main.php
 * handled in condor
 *
 * @category    Phoenix
 * @package     Application
 * @subpackage  Legacy
 * @copyright   Copyright (c) 2013 TravelClick
 * @license     All Rights Reserved
 * @version     Release: 13.4
 * @since       File available since release 13.4
 * @author      Andrew C. Tate <atate@travelclick.com>
 * @filesource
 */

namespace Toolbox\Legacy;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Main Legacy Adapter Class
 *
 * The class that will handle backwards compatibility for the new architecture in regards to what class.Main.php
 * handled in condor.
 *
 * The idea is that instead of condor/modules/core/class.Main.php being instantiated 
 * at the beginning of the application, this class will be called. At first, this will do nothing other than pass arguments
 * on to the existing main class. Eventually, method by method, property by property, Main will no longer exist, except as a legacy interface.
 *
 * Phase I: Create Adapater, write 1:1 public methods, and basically use it as a complete passthrough to the Condor Main Object
 * Phase II: rewrite __construct for the Main Condor Object, getting all of its functionality (and any protected method called during it) working through the interface
 * Phase III: rewrite init, protected methods, and public methods to use ZF2, only switching to legacy code as necessary.
 * 
 * This is currently in Phase II of III of the legacy adapter writing process. This is the ugliest part, when a bunch of things that are rather ugly will appear in this class.
 * This is a WORK IN PROGRESS, and does not reflect what this will look like when it goes live. Even then it will still be bulkier than the rest of the application, for obvious reasons.
 * 
 * @category    Phoenix
 * @package     Application
 * @subpackage  Legacy
 * @copyright   Copyright (c) 2013 TravelClick
 * @license     All Rights Reserved
 * @version     Release: 13.4
 * @since       File available since release 13.4
 * @author      Andrew C. Tate <atate@travelclick.com>
 * @filesource
 */
class Main implements ServiceLocatorAwareInterface
{
    /**
     * The settings array. Known as iniSettings in Condor.
     * @var array
     */
    protected $settings = array();

    protected $request;

    protected $parameters = array('query' => array(),
                                  'post' => array(),
                                  'route' => array(),
                                 );

    protected $setFromSettings = array(
            'enableLanguageDomains' => 'domains',
            'environment' => 'server',
            'captureUrls' => 'urls',
            'homePageRedirection' => 'urls',
        );

    protected $setInitialMethods = array(
            'setTimezone',
            'initLibraries',
            'addPearToPath',
            'loadMainLibraryFiles',
            'cleanUpInput',
            'setErrorHandling'
        );

    protected $disableSaveToCache = false;

    /**
     * DON'T MODIFY THE ORDER OF THESE CALLS. THERE ARE DEPENDENCIES.
     * Well, until this is all refactored.
     *
     * @var array
     */    
    protected $strictOrderedMethods = array(
            'initMainObjects' => array(), 
            'redirectByIncomingLink' => array(), 
            'setEnvironmentFromRequest' => array(), 
            'locateLocalCxml' => array(),
            'redirectUrls' => array(),
            'startSession' => array(),
            'initializePolytext' => array(),
            'setLangCode' => array(),
            'setLocalTemplates' => array(),
            'setAuthProcess' => array(),
            'createConf' => array(),
            'setDebugFromLocalCxml' => array(),
            'canonicalizeUrl' => array(),
            'createLocalUser' => array(),
            'callEvent1' => array('onCondorDBOpen'),
            'disableCacheFromLocalCxml' => array(),
            'setPreventBadBehavior' => array(),
            'disableSitePublisherAdmin' => array(),
            'setCondorVersion' => array(),
            'preventCrossUserCaching' => array(),
            'checkForLoginAttempt' => array(),
            'disableCacheSitePublisher' => array(),
            'setEditMode' => array(),
            'setContentVersionMode' => array(),
            'setLargeFileBaseHref' => array(),
            'setCdnStrategy' => array(),
            'getCurrentDataSection' => array(),
            'setContentPaths' => array(),
            'setIHotelierLangId' => array(),
            'configurePage' => array(),
            'setCurrentModuleObject' => array(),
            'setEventListenerModuleObj' => array(),
            'callEvent2' => array('onCondorInit'),
            'setMediaManagerSizes' => array(),
            'setInitialized' => array(true),
            'initContentApproval' => array(),
            'applyDBPatches' => array(),
            'setInitialized' => array(true),
            'refreshCache' => array(),
            'checkToDisableSaveToCache' => array(),
            'syncDynamicContent' => array(),
            'setPathsInMain' => array(),          
        );
    
    protected $serviceLocator;

    public $moduleAdapter;

    /**
     * Various paths that are important to the legacy application
     * @var array
     */
    protected $paths = array();

    protected $allowOverride = false;

    protected $timer;

    protected $auth;

    public $DBQueryList = array();

    public $pathway = array();

    public $taskCXML = false;
    public $newrelic;

    public $redirectUrls = array();

    protected  $refreshCacheNotice;

    /**
     * The condor $main object. As this class is built, this will become less important, and will eventually be removed.
     * @var \Main (condor) $main
     * @deprecated
     */
    protected $main;

    protected $eventEnabledObjects = array();

    public function __get($property)
    {
        return $this->main->$property;
    }

    public function __set($property, $value)
    {
        $this->main->$property = $value;
    }

    public function __construct()
    {
        $this->timer = microtime(true);
    }

    public function setLegacyPathway($pathway)
    {
        $this->pathway = $pathway;
    }

    /**
     * createMainObject
     * 
     * Create the condor main object.
     * @param  array $settings 
     * @return void
     * @deprecated
     */
    public function initSettingsAndMainObject($settings)
    {
        $this->setSettings($settings);

        $this->main = new \Main($this->getSettings(), $this);
    }

    /**
     * Let's get this party started
     * @return void
     */
    public function init()
    {
        // print_r('<pre>');
        // print_r($this->paths);
        // print_r('</pre>');
        // print_r('<hr>');        
        $timerStart = microtime(true);
        //To Be phased out...Don't put new things in this object
        $this->conf = new \stdClass();

        //Set our time zone
        $this->setTimezone();        

        $this->main->paths = $this->getPaths();

        $this->setRequestedUrl();
        $this->setInitialMethods();
        $this->setFromSettings();
        $this->disableDbCache();
        $this->openDatabaseConnections();
        $beforeStrictLoadTime = (microtime(true) - $timerStart) / 1000;
        //echo "<hr><hr><font color=\"white\">BEFORE STRICT LOAD TIME: $beforeStrictLoadTime seconds</font><hr><hr>"; 
        $this->runStrictOrderedMethods();

        $this->setInitialized(true);

        $initLoadTime = (microtime(true) - $timerStart) / 1000;
        //echo "<hr><hr><font color=\"white\">INIT LOAD TIME: $initLoadTime seconds</font><hr><hr>";
        // print_r('<pre>');
        // print_r($this->paths);
        // print_r('</pre>');
        // die('FILE: ' . __FILE__ . ' LINE: ' . __LINE__);
    }

    public function setFromSetting($category, $setting)
    {
        $settingValue = $this->getSetting($category, $setting);

        $this->$setting = $settingValue;
    }

    //Setters
    /**
     * Set serviceManager instance
     *
     * @param  ServiceLocatorInterface $serviceLocator
     * @return void
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

    public function setStrictOrderedMethods($strictOrderedMethods)
    {
        if ($this->allowOverride === true) {
            $this->strictOrderedMethods = $strictOrderedMethods;
        }
    }

    public function setAllowOverride($allowOverride) {
        $this->allowOverride = $allowOverride;
    }

    /**
     * setSettings
     * 
     * Set the settings array
     * @param array $settings
     */
    public function setSettings(array $settings)
    {
        $this->settings = $settings;

        return $this;
    }

    public function setPaths(array $paths)
    {
        $this->paths = $paths;
    }

    public function setPath($label, $path)
    {
        $this->paths[$label] = $path;
    }

    public function setRequest($request)
    {
        $this->request = $request;

        return $this;
    }

    public function setParameters(array $parameters, $type = '')
    {
        if (!empty($type) && $type != '') {
            $this->parameters[$type] = $parameters;
        } else {
            $this->parameters = $parameters;
        }

        return $this;
    }

    public function setPhoenix(\Toolbox\Phoenix $phoenix)
    {
        $this->phoenix = $phoenix;
    }

    public function getPhoenix()
    {
        return $phoenix;
    }

    public function setResponse($response)
    {
        $this->response = $response;
    }

    public function setRequestedUrl()
    {
        $httpHost = $this->getRequest()->getServer()->get('HTTP_HOST');
        $requestUri = $this->getRequest()->getRequestUri();

        $this->requestedURL = $httpHost . $requestUri;

        if (mb_strpos($this->requestedURL, 'http://') === false) {
            $this->requestedURL = 'http://' . $this->requestedURL;
        }        
    }

    public function sessionExists()
    {
        //$this->session->exists();
        return \Session::exists();
    }

    public function setAuth($auth)
    {
        $this->auth = $auth;
    }

    public function getAuth()
    {
        return $this->auth;
    }

    /**
     * setMainObject
     *
     * Set the Main Object to be used by the adapter
     * 
     * @param \Main $mainObject
     */
    public function setMainObject(\Main $mainObject)
    {
        $this->main = $mainObject;
    }

    //Getters
    /**
     * Retrieve serviceManager instance
     *
     * @return ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    /**
     * getSettings
     * 
     * Returns the settings Array
     * @return array
     */
    public function getSettings()
    {
        return $this->settings;
    }

    public function getSetting($category, $setting = null, $default = null)
    {
        if (isset($this->settings[$category]) && !$setting) {
            return $this->settings[$category];
        }

        if (isset($this->settings[$category][$setting])) {
            return $this->settings[$category][$setting];
        }

        return $default;
    }

    public function settingMatches($category, $setting, $value = true)
    {
        return (isset($this->settings[$category][$setting]) && $this->settings[$category][$setting] == $value);
    }

    public function getPaths()
    {
        return $this->paths;
    }

    public function getPath($label)
    {
        return (isset($this->paths[$label])) ? $this->paths[$label] : '';
    }

    /**
     * getParameters
     *
     * Get an array of parameters, either the entire set, or one type
     * @param  string $type
     * @return array|boolean
     */
    public function getParameters($type = '')
    {
        if ($type != '' && isset($this->parameters[$type])) {
            return $this->parameters[$type];
        }

        return $this->parameters;
    }

    public function getModuleConfig($module)
    {
        return $this->moduleAdapter->getModuleConfig($module);
    }

    /**
     * getMainObject
     * 
     * Get the condor main object
     * @return \Main $main (condor object)
     * @deprecated
     */
    public function getMainObject()
    {
        return $this->main;
    }    

    public function getRequest()
    {
        return $this->request;
    }

    public function getResponse()
    {
        return $this->response;
    }


    public function setInitialized($initialized)
    {
        $this->initialized = $initialized;
    }

    /**
     * BEGIN LEGACY INTERFACE METHODS
     * 
     * The following methods are the public methods of the current main object
     */

    public function setMediaManagerSizes()
    {
        $this->mediaManagerSizes = $this->parseMediaManagerConfig();
    }

    /**
     * getCurrentPage
     * 
     * Gets the current Page
     * REFACTOR-TO LinkWriter
     * 
     * @return string
     */
    public function getCurrentPage()
    {
        return $this->links->constructLinkTagContent(
            $this->links->getCurrentPageParameters()
        );
    }

    /**
     * preprocessRequest
     *
     * REFACTOR-TO: LinkWriter
     * @return array
     */
    public function preprocessRequest()
    {
        return $this->main->preprocessRequest();
    }

    public function setEnvironmentFromRequest()
    {
        $routeParams = $this->preprocessRequest();

        //Commented this out because it is wreaking havoc with seoUrls.
        //This needs to be fixed seoURLs work AND it works using Phoenix
        //$routeParams = $this->getParameters('route');

        $this->currentPage = $routeParams['page'];

        $this->currentLocation = $routeParams['location'];
        
        if (isset($routeParams['item'])) {
            $this->currentItem = $routeParams['item'];
        }
        
        if (isset($routeParams['task'])) {
            $this->currentTask = $routeParams['task'];
        }
        if (isset($routeParams['pageAlias'])) {
            $this->currentPageAlias = $routeParams['pageAlias'];
        }

        /**
         * 13.1-dev Optimizations
         * Integrates the newrelic monitoring
         */
        $this->newrelic->custom_param('VERSION',CONDOR_VERSION);
        $this->newrelic->name_request($this->getCurrentPage());    
    }

    /**
     * callEvent
     * 
     * Fires events from either the module, or our localCXML configuration.
     * 
     * @param  string     $eventName
     * @param  mixed     $eventInfo
     * @return void
     */
    public function callEvent($eventName, $eventInfo = false)
    {
        //Call Objects
        $eventEnabledObjects = $this->getEventEnabledObjects();
        foreach ($eventEnabledObjects as $object) {
            if(method_exists($object, $eventName)) {
                $object->$eventName($eventInfo);
            }
        }

        $this->callLocalCxmlEvents($eventName);

        return true;
    }

    public function getEventEnabledObjects()
    {
        return (is_array($this->eventEnabledObjects)) ? $this->eventEnabledObjects : array();
    }

    public function addEventListener($listenerObject)
    {
        $this->eventEnabledObjects[] = $listenerObject;
    }

    /**
     * renderContent()
     * REFACTOR-TO: PAGE
     * @return string The Page Content
     */
    public function renderContent()
    {
        return $this->main->renderContent();
        //return $this->page->renderContent();
    }

    /**
     * refreshCache
     * REFACTOR-TO: CACHE
     * 
     * Refresh the cache
     * @param  string $dataSection [description]
     * @return void
     */
    public function refreshCache($dataSection = null)
    {
        $this->cache->refreshCache($dataSection, $this->useMemcache);

        $this->refreshCacheNotice = "Database Purged";
    }

    /**
     * getOutputFromBuffer
     * REFACTOR-TO: PAGE
     * @return string content from buffer
     */
    public function getOutputFromBuffer()
    {
        //Get any output that was echoed during the creation of the page
        return ob_get_clean();
    }

    /**
     * sendContent
     * REFACTOR-TO: PAGE
     * @return void
     */
    public function sendContent($content)
    {
        if(!$this->getDisableSaveToCache()) {
            $this->finalContent = $content;
            //Save the page to the cache
            $this->savePageToCache();
        }
        //Content is everything that was created and echoed

        echo trim($content);

        if(!$this->socketRequested()) {
            $this->outputPageTime();
        }
    }

    /**
     * close
     * 
     * Handles closing the session and database connection
     * once the request is finished
     * 
     * @return void
     */
    public function close()
    {
       $close = $this->main->close();

       $this->terminate();
    }

    /**
     * beginOutputBuffer
     * REFACTOR-TO: PAGE
     * @return void
     */
    public function beginOutputBuffer()
    {
        //Set up a mulitlingual cache of all of the output by the scripts
        mb_language('Neutral');
        mb_internal_encoding('UTF-8');
        mb_http_input('UTF-8');
        mb_detect_order('auto');
        mb_http_output('UTF-8');
        ob_start('mb_output_handler');

        //and let the browser know
        $this->setHeader("Content-type: text/html; charset=utf-8");
        $this->setHeader('Content-language:' . $this->text->getLanguage());
    }

    /**
     * getPathWithSlash
     *
     * @param  string $key
     * @return string
     * @deprecated
     */
    public function getPathWithSlash($key)
    {
        return $this->main->getPathWithSlash($key);
    }

    /**
     * disableEdgecast
     * 
     * Sets the disableEdgecast property to false
     * 
     * @return void
     */
    public function disableEdgecast()
    {
        $this->disableEdgecast = true;
    }

    /**
     * disableCache
     * 
     * Disables the cache, backwards compatibility
     * REFACTOR-TO: Cache
     * 
     * @return void
     */
    public function disableCache()
    {
        $this->disableServeFromCache();
        $this->disableSaveToCache();
    }

    /**
     * setDisableCache
     * 
     * REFACTOR-TO: Cache
     * 
     * @return void
     */
    public function setDisableCache($disableSwitch)
    {
        $this->disableCache = $disableSwitch;
    }

    public function getDisableCache()
    {
        return $this->disableCache;
    }

    /**
     * disableServeFromCache()
     *
     * REFACTOR-TO: Cache
     * 
     * Disables the cache and sends the proper headers back
     * to disable the browser caching
     * 
     * @return void
     */
    public function disableServeFromCache()
    {
        $this->disableCache = true;

        $this->setHeader("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
        $this->setHeader("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        $this->setHeader("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        $this->setHeader("Cache-Control: post-check=0, pre-check=0", false);
        $this->setHeader("Pragma: no-cache");
    }

    /**
     * disableSaveToCache()
     * 
     * Sets disableSaveToCache to true. Uses the setDisableSaveToCache method.
     * 
     * @return void
     */
    public function disableSaveToCache()
    {
        $this->setDisableSaveToCache(true);
    }

    /**
     * setDisableSaveToCache
     *
     * Created this so there would be a method available to enable save to cache as well as disable.
     * 
     * @param boolean $disableSwitch
     */
    public function setDisableSaveToCache($disableSwitch = true)
    {
        $this->disableSaveToCache = $disableSwitch;        
    }

    /**
     * getDisableSaveToCache
     *
     * Returns $this->disableSaveToCache;
     * 
     * @return boolean
     */
    public function getDisableSaveToCache()
    {
        return $this->disableSaveToCache;
    }    

    public function outputPageTime()
    {
        $pageTime = $this->getOutputPageTime();

        echo "<!--{$pageTime['nbQueries']} Queries - {$pageTime['secs']} seconds -->";
    }

    public function getOutputPageTime()
    {
        //Keep track of queries and time taken
        $nbQueries = count($this->DBQueryList);

        $secs = (microtime(true) - $this->timer ) / 1000;

        return array('nbQueries' => $nbQueries, 'secs' => $secs);
    }

    public function createModuleObject($module, $dataSectionOrCXML = false)
    {
        return $this->moduleAdapter->createModuleObject($module, $dataSectionOrCXML);
    }

    public function getModuleItems($params)
    {
        $moduleAdapter = $this->getServiceLocator()->get('phoenix-legacy-moduleadapter');

        return $moduleAdapter->getModuleItems($params);
    }

    public function printPageErrors()
    {
        $errors = $this->messageLog->getMessages();
        if($errors) {
            echo "
            <!-- Error List -->
            <style type=\"text/css\">
                #errors {
                    clear:both;
                    width:80%;
                    margin: 10px 10%;
                    background-color:#ddd;
                }
                #errors ol {
                    margin: 5px 0 5px 20px;
                    padding:5px;
                    font-family:arial, sans-serif;
                    font-size:11px;
                }
                #errors li {
                    list-style: decimal;
                    padding: 7px;
                }
                #errors li.odd {
                    background-color:#ccc;
                }
                #errors li.error {
                    color:red;
                }
                #errors li.warning {
                    color:orange;
                }
                #errors li.notice {
                    color:blue;
                }
                #errors p {
                    margin: 0;
                }
                #errors .highlights {
                    color: black;
                }
            </style>
            <div id=\"errors\">
                $errors
            </div>";
        }
    }

    //For restricted page info tagged to the end of the page
    public function printRestrictedPageInfo()
    {
        if (isset($this->parameters['query']['showCxml'])) {
            echo $this->getCxmlOutput();
            $this->terminate(42);
        } else {
            $output = $this->getDbQueryListOutput()
                    . $this->getVariableHelperOutput()
                    . $this->getFilterHistoryOutput()
                    . $this->getKeysOutput();

            echo $output;           
        }
    }

    //For non restricted page info tagged to the end of the page
    public function printPageInfo()
    {
        if($this->refreshCacheNotice !== false) {
            $notice = "Cache Notice: $this->refreshCacheNotice";
            echo "\n<!-- $notice --> \n\n";
            echo "
<script type=\"text/javascript\">
    console.warn('$notice');
</script>";
        }
    }



    /**
     * socketRequested
     * 
     * Determine if the current request is a socket request
     * 
     * @return void
     */
    public function socketRequested()
    {   
        $queryParams = $this->getParameters('query');
        return isset($queryParams['socketModule']);
    }

    /**
     * taskRequested
     * 
     * Determine if the current request is a task request
     * @return void
     */
    public function taskRequested()
    {
        $queryParams = $this->getParameters('query');
        return isset($queryParams['taskModule']);
    }

    /**
     * iniCheck()
     * 
     * Ini Configuration boolean matching
     * 
     * @param  strinmg $section
     * @param  string $key
     * @param  mixed $value
     * @return bool
     */
    public function iniCheck($section, $key, $value = true)
    {
        return isset($this->settings[$section][$key]) && $this->settings[$section][$key] == $value;
    }    

    public function toEdgecastHref($href)
    {
        if(mb_strpos($href, SITE_BASE_HREF) === false)
        {
            trigger_error("Unable to convert url to Edgecast Url: '$href'", E_USER_WARNING);
            return false;
        }
        else
        {
            return str_ireplace(SITE_BASE_HREF, $this->getPathWithSlash('edgecastBaseHREF'), $href);
        }
    }

    /**
     * canEditPage()
     * 
     * Really need to think of a more elegant way to do this...
     * -- a few years later ---
     * Now it is possible to use (string) $main->localCXML->areaType == admin
     * -- someday the below code should be updated to use areaType from the cxml  --
     * Will it be you that changes it? YES (ACT 04/29/2013)
     *
     * 
     * @return boolean
     */
    public function canEditPage()
    {
        if ($this->isAdminEditablePage() || $this->isWriteEditablePage()) {
            return true;
        }

        return false;
    }

    public function getTaskCxml()
    {
        return $this->taskCXML;
    } 

    protected function isAdminEditablePage()
    {
        $isEditablePage = $this->isEditablePage();

        return $this->getAuth()->canUserAdmin() && $isEditablePage;
    }

    protected function isEditablePage()
    {
        $taskCxml = $this->getTaskCxml();
        if (!$taskCxml) {
            return true;
        }

        if (!isset($this->taskCXML->inPageEdit) || (string) $this->taskCXML->inPageEdit != 'false') {
            return true;
        }

        return false;
    }

    protected function isWriteEditablePage()
    {
        return $this->getAuth()->canWrite() && !isset($this->taskCXML->template) && ((string) $this->localCXML->templateSet != (string) $this->localCXML->adminTemplateSet);
    }

    /**
     * getLogoutLink()
     * 
     * Gets the logout link to logout out of toolbox
     * 
     * @return string
     */
    public function getLogoutLink()
    {
        $isRestricted = $this->getAuth()->isRestricted();

        $logoutPage = ($isRestricted) ? 'default' : 'self';

        return $this->links->writeLinkTo(
            $logoutPage,
            array(
                'logout' => 1
            )
        );
    }

    public function handlePageNotFound()
    {
        return $this->main->handlePageNotFound();
    }

    public function redirectByIncomingLink()
    {
        $this->links->setLegacyDb($this->DB);
        $this->redirectUrls = $this->links->redirectByIncomingLink();
    }  

    /**
     * setHeader()
     * 
     * Sets the headers passed to it.
     * 
     * @param array $header
     */
    public function setHeader($header)
    {
        header($header);
    }

    /**
     * Apply DB patches
     */
    public function applyDBPatches()
    {
        // check if the dbpatches entry exists
        $dbPatches = $this->getSetting('dbpatches');

        if(!is_null($dbPatches)) { 
            // Saved the original moduleCXML because createModuleObject change it.
            $moduleCXMLoriginal = $this->moduleCXML;
            foreach($dbPatches as $moduleName => $enabled) {
                if($enabled){
                    $this->moduleAdapter->applyDbPatches($moduleName);
                }
            }
            // Recover the original moduleCXML
            $this->moduleCXML = $moduleCXMLoriginal;
        }
    }

    /**
     * toBool()
     * 
     * Returns true or false, useful for cxml configuration where character data is a string
     * 
     * @param  mixed $var
     * @return boolean
     */
    public function toBool($var)
    {
        return \Phoenix\StdLib\TypeHandling::toBool($var);
    }

    public function executeCommand($command)
    {
        return exec($command);
    }    

    /**
     * ini_get
     * 
     * Gets the configuration from iniSettings
     * 
     * @param  string $section
     * @param  string $key
     * @return mixed
     */
    public function ini_get($section, $key = null, $default = null)
    {
        return $this->getSetting($section, $key, $default);
    }

    /**
     * array_get_value()
     * 
     * Returns the value for a given array key
     * or the default parameters if false
     * 
     * @param  array $array
     * @param  string $key
     * @param  mixed $default
     * @return mixed
     */
    public function array_get_value($array,$key,$default)
    {
        return \Phoenix\StdLib\ArrayHelper::getValueFromArray($array, $key, $default);
    }

    /**
     * redirect()
     * 
     * Redirects to a given page
     * 
     * @param  string $page
     * @return void
     */
    public function redirect($pageUrl, $code = null)
    {
        $httpStatusCodes = array(
            '100' => "{$code} Continue",
            '101' => "{$code} Switching Protocols",
            '200' => "{$code} OK",
            '201' => "{$code} Created",
            '202' => "{$code} Accepted",
            '203' => "{$code} Non-Authoritative Information",
            '204' => "{$code} No Content",
            '205' => "{$code} Reset Content",
            '206' => "{$code} Partial Content",
            '300' => "{$code} Multiple Choices",
            '301' => "{$code} Moved Permanently",
            '302' => "{$code} Moved Temporarily",
            '303' => "{$code} See Other",
            '304' => "{$code} Not Modified",
            '305' => "{$code} Use Proxy",
            '400' => "{$code} Bad Request",
            '401' => "{$code} Unauthorized",
            '402' => "{$code} Payment Required",
            '403' => "{$code} Forbidden",
            '404' => "{$code} Not Found",
            '405' => "{$code} Method Not Allowed",
            '406' => "{$code} Not Acceptable",
            '407' => "{$code} Proxy Authentication Required",
            '408' => "{$code} Request Time-out",
            '409' => "{$code} Conflict",
            '410' => "{$code} Gone",
            '411' => "{$code} Length Required",
            '412' => "{$code} Precondition Failed",
            '413' => "{$code} Request Entity Too Large",
            '414' => "{$code} Request-URI Too Large",
            '415' => "{$code} Unsupported Media Type",
            '500' => "{$code} Internal Server Error",
            '501' => "{$code} Not Implemented",
            '502' => "{$code} Bad Gateway",
            '503' => "{$code} Service Unavailable",
            '504' => "{$code} Gateway Time-out",
            '505' => "{$code} HTTP Version not supported"
        );
        
        /**
         * Lets calculate our http response message
         */
        $httpStatusMessage = $this->array_get_value(
            $httpStatusCodes,
            $code,
            "{$code} Unknown http status code"
        );

        $this->setHeader("HTTP/1.1 {$httpStatusMessage}");
        $this->setHeader("Status: {$httpStatusMessage}");
        $this->setHeader("Redirected_by: Toolbox CMS");
        $this->setHeader("Location: {$pageUrl}");
        $this->terminate();
    }

    /**
     * terminate()
     * 
     * Abstraction to the global function exit so we can
     * unit tests certain methods that call exit()
     * 
     * @param  int $code
     * @return void
     */
    public function terminate($code = null)
    {
        return $this->main->terminate($code);
    }           

    /**
     * Protected functions
     */
    

    protected function setInitialMethods()
    {
        foreach ($this->getSetInitialMethods() as $valMethod) {
            $this->$valMethod();
        }        
    }

    protected function getSetInitialMethods()
    {
        return $this->setInitialMethods;
    }

    protected function setFromSettings()
    {
        $this->checkToEnableCache();
        $this->checkToEnableSitePublisher();
        $this->checkToEnableContentApproval();
        $this->checkToEnableCustomUrls();
        $this->enableMemcache();

        foreach ($this->setFromSettings as $keySetting => $valSetting) {
            $this->setFromSetting($valSetting, $keySetting);
        }        
    }

    protected function checkToEnableSitePublisher()
    {
        $this->enableSitePublisher = $this->settingMatches('edit', 'enableSitePublisher');
    }

    protected function checkToEnableContentApproval()
    {
        $this->contentApprovalRequired = $this->settingMatches('edit', 'requireContentApproval');
    }

    protected function checkToEnableCustomUrls()
    {
        $this->enableCustomUrls = $this->settingMatches('urls', 'enableCustomUrls');
    }

    protected function runStrictOrderedMethods()
    {
        foreach ($this->strictOrderedMethods as $keyMethod => $valMethod) {
            $this->runInitMethod($keyMethod, $valMethod);
        }        
    }

    protected function runInitMethod($keyMethod, $valMethod)
    {
        switch ($keyMethod) {
            case 'callEvent1':
            case 'callEvent2':
                $this->callEvent($valMethod[0]);
                break;
            case 'setInitialized':
                $this->$keyMethod($valMethod[0]);
                break;
            case 'refreshCache':
                $this->setRefreshCache();
                break;
            default:
                $this->$keyMethod();
                break;
        }
    }

    protected function setRefreshCache()
    {
        if (isset($this->parameters['query']['cache']) && $this->parameters['query']['cache'] == 'refresh' && !$this->socketRequested()) {
            $this->refreshCache = true;
            $this->refreshCache();                        
        }        
    }    

    /**
     * initMainObjects
     * 
     * Initialize main objects for the main class
     * 
     * @return void
     */
    protected function initMainObjects()
    {
        /**
         * Lets instantiate our main Objects
         */
        $this->OS = new \Phoenix\StdLib\OS();
        $this->page = $this->getServiceLocator()->get('phoenix-legacy-page', true);
        $this->setAuth($this->getServiceLocator()->get('phoenix-legacy-auth', true));
        $this->links = $this->getServiceLocator()->get('phoenix-legacy-linkwriter', true);
        $this->cache = $this->getServiceLocator()->get('phoenix-legacy-cache', true);
        $this->moduleAdapter = $this->getServiceLocator()->get('phoenix-legacy-moduleadapter', true);
        $this->contentAppearance = $this->getServiceLocator()->get('phoenix-legacy-contentappearancetracking', true);
        $this->newrelic = $this->_new('NewRelic');        
    }

    /**
     * _new()
     *
     * This is an abstraction so we can
     * mock and inject our test mocks
     * Added this back in for NewRelic, for now
     * 
     * @param  string  $class
     * @return object
     */
    protected function _new($class)
    {
        if (!class_exists($class)) return;
        if ($a = array_slice(func_get_args(),1))
        if (method_exists($class, '__construct'))
        if ($class = new \ReflectionClass($class))
        return $class->newInstanceArgs($a);

        return new $class();
    }    

    /**
     * setTimezone
     * 
     * Sets the default timezone
     * 
     * @param string $timezone
     * @return void
     */
    protected function setTimezone($timezone = 'Europe/Paris')
    {
        date_default_timezone_set($timezone);
    }

    /**
     * _initLibraries()
     * 
     * initialize our load libraries
     * 
     * @return void
     */
    protected function initLibraries()
    {
        $path = CONDOR_MODULES_DIR;

        $this->classLib = new Loader($path, array('prefix' => 'class.'));
        $this->functionLib = new Loader($path, array('prefix' => 'function.'));
        $this->filterLib = new Loader($path, array('prefix' => 'filter.'));;
        $this->exceptionLib = new Loader($path, array('prefix' => 'exception.'));
        $this->interfaceLib = new Loader($path, array('prefix' => 'interface.'));;
        $this->itemFormElementLib = new Loader($path, array('prefix' => 'itemFormElement.'));
    }

    /**
     * _addPearToPath()
     *
     * Adds our PEAR path to the system path
     *
     * 
     * @return void
     */
    protected function addPearToPath()
    {
        //Add the pear directory
        set_include_path(implode(array(
            get_include_path(),
            PATH_SEPARATOR,
            CONDOR_BASE_DIR,
            'PEAR'
        )));
    }

    /**
     * _loadMainLibraryFiles()
     * 
     * Load all of the basic libraries
     * 
     * @return void
     */
    protected function loadMainLibraryFiles()
    {
        $this->classLib->load('core', 'Base', __FILE__, __LINE__);
        $this->classLib->load('core', 'DataFilter', __FILE__, __LINE__);  
        $this->classLib->load('core', 'AppMessage',             __FILE__, __LINE__);
        $this->classLib->load('core',   'CondorDB',              __FILE__, __LINE__);
        $this->classLib->load('core',   'CondorDBObject',       __FILE__, __LINE__);
        $this->classLib->load('core',   'VersionedDBObjects', __FILE__, __LINE__);
        $this->classLib->load('core',   'Module',                  __FILE__, __LINE__);
        $this->classLib->load('core',   'ListModule',              __FILE__, __LINE__);
        $this->classLib->load('core',   'ListModuleItems',         __FILE__, __LINE__);
        $this->classLib->load('core',   'Auth',                 __FILE__, __LINE__);
        $this->classLib->load('text',   'PolyText',             __FILE__, __LINE__);
        $this->classLib->load('text',   'TextDbObject',            __FILE__, __LINE__);
        $this->classLib->load('core',     'Page',                   __FILE__, __LINE__);
        $this->classLib->load('core',     'CondorLinkWriter',     __FILE__, __LINE__);
        $this->classLib->load('users',  'User',                 __FILE__, __LINE__);
        $this->classLib->load('users',  'Session',                __FILE__, __LINE__);
        $this->classLib->load('cache',  'Itibiti',                __FILE__, __LINE__);  //Cache class
        $this->classLib->load('cache',  'CondorMemcache',       __FILE__, __LINE__);  //Memcache wrapper class
        $this->classLib->load('core',     'ClassBuilder',         __FILE__, __LINE__);
        $this->classLib->load('core',     'MobileDetect',         __FILE__, __LINE__);  //Simple class to detect mobile devices by user agent
        $this->classLib->load('core', 'ContentAppearanceTracking', __FILE__, __LINE__);  // Content appearance tracking

        $this->classLib->load('mediaManager',      'AttachMediaFiles',       __FILE__, __LINE__);
        $this->classLib->load('ihotelier',      'IhotelierData',       __FILE__, __LINE__);
        $this->classLib->load('localUsers',      'LocalUser',           __FILE__, __LINE__);
        $this->classLib->load('contentBlocks',  'ContentBlock',       __FILE__, __LINE__);
        $this->classLib->load('pages',         'Pages',                 __FILE__, __LINE__);
        $this->classLib->load('sitePublisher', 'SitePublisher',         __FILE__, __LINE__);
        $this->classLib->load('sitePublisher', 'PublishedPageRenderer', __FILE__, __LINE__);
        $this->classLib->load('contentApproval', 'ContentApproval', __FILE__, __LINE__);    
        $this->interfaceLib->load('contentApproval', 'ApprovalInterface', __FILE__, __LINE__);          
        $this->classLib->load('contentApproval', 'ModuleItem', __FILE__, __LINE__);
        $this->classLib->load('contentApproval', 'Model', __FILE__, __LINE__);
        
        $this->classLib->load('core', 'NewRelic', __FILE__, __LINE__);

        $this->functionLib->load('core', 'writeLinkTo',                    __FILE__, __LINE__);
        $this->functionLib->load('core', 'loadModuleFunction',            __FILE__, __LINE__);
        $this->functionLib->load('core', 'currentDataSection',            __FILE__, __LINE__);
        $this->functionLib->load('core', 'getDataSectionDirectory',     __FILE__, __LINE__);
        $this->functionLib->load('core', 'condorFileToHREF',             __FILE__, __LINE__);
        $this->functionLib->load('core', 'condorHREFToFile',             __FILE__, __LINE__);
        $this->functionLib->load('core', 'getLocalCXML',                 __FILE__, __LINE__);
        $this->functionLib->load('core', 'stripSlashesDeep',             __FILE__, __LINE__);
        $this->functionLib->load('core', 'replaceHashVars', __FILE__, __LINE__);
        $this->functionLib->load('core', 'createCurrentTaskMenu', __FILE__, __LINE__);
        $this->functionLib->load('ihotelier', 'ihotelierGetLanguageId', __FILE__, __LINE__);
        $this->functionLib->load('core', 'getSnippet', __FILE__, __LINE__);
        $this->functionLib->load('core', 'closeHtmlTags', __FILE__, __LINE__);
        $this->functionLib->load('core', 'getHtmlSnippet', __FILE__, __LINE__);
        $this->functionLib->load('core', 'slashomatic', __FILE__, __LINE__);
        $this->functionLib->load('google', 'websiteOptimizer', __FILE__, __LINE__);
        $this->functionLib->load('contentApproval', 'init', __FILE__, __LINE__);

        $this->exceptionLib->load('core', 'NoAuthorizationException', __FILE__, __LINE__);
        $this->exceptionLib->load('core', 'InvalidParameterException', __FILE__, __LINE__);
        $this->exceptionLib->load('core', 'MySQLException',  __FILE__, __LINE__);
        $this->exceptionLib->load('core', 'DBConnectionException',  __FILE__, __LINE__);
        $this->exceptionLib->load('core', 'FileNotFoundException', __FILE__, __LINE__);
        $this->exceptionLib->load('core', 'FubarException', __FILE__, __LINE__);
        $this->exceptionLib->load('core', 'ContentAppearanceSaveRejectedException', __FILE__, __LINE__);
    }

    protected function cleanUpInput()
    {
        $query = $this->getParameters('query');
        if(isset($query['socket']))
        {
            $parts = explode(':', $query['socket']);

            $query['socketModule'] = $parts[0];
            $_REQUEST['socketModule'] = $parts[0];
            $query['socketName']   = $parts[1];
            $_REQUEST['socketName']   = $parts[1];

            $this->parameters['query'] = $query;
        }
    }

    protected function setErrorHandling()
    {

        if(!$this->iniCheck('errors','use_php_error_handler',true)) {
            /* Set up the error handling */
            $today = date('Y_m_d');
            $this->messageLog = new \AppMessage(CONDOR_LOGS_DIR . $today . '.log');
            $this->messageLog->dieOnError = true;

            $this->functionLib->load('core', 'logPHPError',     __FILE__, __LINE__);

            //The logPHPError function will handle all errors and send them to the AppMessage Class
            set_error_handler('logPHPError');
        }

        //Make sure the value of display_errors is false to avoid problems
        $display_errors = ini_get('display_errors');

        if(strtolower(trim($display_errors)) == 'off' || $display_errors == 0)
        {
            ini_set('display_errors', false);
        } else {
            ini_set('display_errors', 1);
        }
    } 

    protected function enableMemcache()
    {
        if ($this->settingMatches('cache', 'enableMemcache')) {
            $memcache = new \CondorMemcache();
            $memcache->uniqueKey = $this->getSetting('cache','memcacheUniqueKey');
            $memcachePort = $this->getSetting('cache','memcachePort');
            $memcacheServer = $this->getSetting('cache','memcacheServer');
            if($memcache->connect($memcacheServer, $memcachePort)) {
                $this->useMemcache = true;
                $this->cache->setMemcache($memcache);
            }
        }
    }

    protected function disableDbCache()
    {
        /**
         * Disable the database page cache if needed
         */
        if(count($this->parameters['post']) > 0 || $this->socketRequested() || $this->sessionExists())
        {
            $this->disableServeFromCache();
            $this->disableSaveToCache();
        } else {
            /**
             * Which get vars do we allow to be able to cache
             */
            $cacheOkVars = array(
                'page',
                'langCode',
                'location',
                'item',
                'identifier',
                'task',
                'contentOnly'
            );

            foreach($this->parameters['query'] as $paramName => $value)
            {
                if(!in_array($paramName, $cacheOkVars))
                {
                    $this->disableServeFromCache();
                    $this->disableSaveToCache();
                }
            }
        }
    }    

    /**
     * openDatabaseConnections()
     * 
     * Open our database connections to admin/local databases
     * 
     * @return void
     */
    protected function openDatabaseConnections()
    {
        //Init the classes we will use to create the page
        $this->DB = $this->getServiceLocator()->get('phoenix-legacy-db');
        $this->adminDB = clone $this->DB;

        $this->DB->useLocalDB();
        $this->DB->open( __FILE__, __LINE__);

        $this->adminDB->useAdminDB();
        $this->adminDB->open( __FILE__, __LINE__);
    }

    protected function locateLocalCxml()
    {
        /**
         * Try to load the local CXML if page is an .html or .htm page
         */
        $this->localCXML = $this->getServiceLocator()->get('LocalCxml', true);

        //TEMPORARY FIX FOR customUrls
        if (!$this->localCXML && $this->getServiceLocator()->has('phoenix-legacy-cxml')) {
            $cxml = $this->getServiceLocator()->get('phoenix-legacy-cxml');

            $this->localCXML = $cxml->getLocalCxml($this->currentLocation, $this->currentPage, true, false);
        }

        $config = $this->getServiceLocator()->get('MergedConfig', true);

        $this->virtualBaseArea = $config->get('virtualBaseArea', false);

        /**
         * If local CXML cannot be found, else handle Page Not Found
         */
        if(!$this->localCXML && empty( $this->redirectUrls)){
            $this->handlePageNotFound();
        }        
    }

    protected function redirectUrls()
    {
        $redirectUrls = $this->redirectUrls;

        /**
         * Redirections are populated by redirectByIncomingLink and CondorLinkWriter::lookupURLinDictionary
         * If exist redirection by priority (1. LinkRedirects, 2. SeoUrls)
         */
        if(!empty($redirectUrls))
        {
            foreach ($redirectUrls as $redirectArray) {
                foreach ($redirectArray as $key => $value) {
                    $this->setHeader($value);
                }
            }
            
            $this->terminate();
        }        
    }

    protected function startSession()
    {
        /**
         * If there is a session or a form,
         * or a user is trying to login
         * then we start a session
         */
        if(\Session::exists())
        {
            $this->disableServeFromSitePublisher = true;
            $this->disableEdgecast();

            $this->perpetuateSession();

            if(isset($_GET['logout']))
            {
                $this->user->logout();
            }
        }        
    }

    protected function perpetuateSession()
    {
        //Start the session
        $this->session     = new \Session();

        //See if this is a user session and log in the current user
        if($this->session->get('users', 'userID'))
        {   
            $this->user  = new \User($this->session->get('users', 'userID'));
        }
    }

    /**
     * initializePolytext()
     * 
     * Initialize the polytext class
     *
     * @return void
     */
    protected function initializePolytext()
    {
        $this->text   = $this->getServiceLocator()->get('phoenix-legacy-polytext');
        $routeParams = $this->getParameters('route');
        $langCode = ($routeParams['langCode']) ? $routeParams['langCode'] : '';
        $this->text->init($langCode);
    }

    /**
     * setLangCode
     * 
     * Set the langCode for this page
     *
     * @return void
     */
    protected function setLangCode()
    {
        $this->page->setVar('langCode', $this->text->getLanguage());
    }

    /**
     * setLocalTemplates
     * 
     * if you use 'templatePath' in your _area.cxml,
     * remove 'localTemplatePath' from the ini file,
     * otherwise that will throw an error (in purpose, don't declare things twice)
     *
     * @return void
     */
    protected function setLocalTemplates()
    {
        if(isset($this->localCXML->templatePath))
        {
            $templatePath = (string) $this->localCXML->templatePath;
            define('CONDOR_LOCAL_TEMPLATES_DIR', SITE_BASE_DIR . $templatePath);
            define('CONDOR_LOCAL_TEMPLATES_HREF', SITE_BASE_HREF . $templatePath);
        }        
    }

    /**
     * setAuthProcess
     * 
     * Set if we default to admin auth or local user auth
     *
     * @return void
     */
    protected function setAuthProcess()
    {
        if(isset($this->localCXML->authProcess) && (string) $this->localCXML->authProcess == 'local') {
            $this->authProcess = 'local';
        }
    }

    /**
     * createConf
     * 
     * Create our default main configuration
     *
     * @deprecated
     * @return void
     */
    protected function createConf()
    {
        //Configure the mail settings
        $this->conf->mail = new \stdClass();
        $this->conf->mail->SMTP = new \stdClass();

        $smtpHost = $this->getSetting('mail', 'smtpHost');

        // If the cxml has a MAIL->SMTP section, set the conf object from the cxml
        if (isset($this->localCXML->mail->SMTP)) {
            $this->conf->mail->SMTP->host     = (string) $this->localCXML->mail->SMTP->host;
            $this->conf->mail->SMTP->user     = (string) $this->localCXML->mail->SMTP->user;
            $this->conf->mail->SMTP->password = (string) $this->localCXML->mail->SMTP->password;
            $this->conf->mail->defaultFromAddress = (string) $this->localCXML->mail->defaultFromAddress;
        } elseif ($smtpHost) {
            $this->conf->mail->SMTP->host = $smtpHost;
            $this->conf->mail->SMTP->user = $this->getSetting('mail', 'smtpUser');
            $this->conf->mail->SMTP->password = $this->getSetting('mail', 'smtpPassword');
            $this->conf->mail->defaultFromAddress = $this->getSetting('mail', 'defaultFromAddress');
        } else {
            $this->conf->mail->defaultFromAddress = $this->getSetting('mail', 'defaultFromAddress');
        }
    }

    protected function setDebugFromLocalCXML()
    {
        $this->showDebug = false;

        if(isset($this->localCXML->showDebug)) {
            $this->showDebug = $this->toBool($this->localCXML->showDebug);
        } elseif ($this->settingMatches('errors', 'display_errors')) {
            $this->showDebug = true;
        }
    }

    /**
     * canoncalizeUrl
     * 
     * Canonicalize current URL
     *
     * @return void
     */
    protected function canonicalizeURL()
    {   
        $redirectUrl = $this->links->canonicalizeUrl($this->requestedURL, $this);

        if ($redirectUrl == true) {
            /**
            * Lets call terminate instead of exit() directly
            */
            $this->terminate();            
        }
    }

    /**
     * Create a local user object
     */
    protected function createLocalUser()
    {
        if (\LocalUser::sessionExists()) {
            $this->localUser = new \LocalUser;
            $this->addEventListener($this->localUser);
        }
    }

    protected function disableCacheFromLocalCxml()
    {
        $useCache = (string) $this->localCXML->cache->useCache;
        if($useCache === 'false' || $useCache === 0) {
            $this->disableServeFromCache();
        }        
    }

    /**
     * setPreventBadBehavior
     * 
     * Prevent Bad Behavior (because people are mean)
     *
     * @return void
     */
    protected function setPreventBadBehavior()
    {
        if($this->settingMatches('security','enableBadBehavior') && !\Session::exists())
        {
            $this->preventBadBehavior();
        }
    }

    protected function preventBadBehavior()
    {
        require_once(CONDOR_MODULES_DIR . 'badBehavior/bad-behavior-condor.php');
    }

    /**
     * disableSitePublisherAdmin
     * 
     * If it's a back-end page, disable Site Publisher
     *
     * @return void
     */
    protected function disableSitePublisherAdmin()
    {
        if($this->localCXML->areaType == 'admin') {
            $this->disableServeFromSitePublisher = true;
        }
    }

    protected function setCondorVersion()
    {
        if (\Toolbox\Legacy\Auth::canAdmin()) {
            $this->setHeader('X-Condor-Version: '. CONDOR_VERSION);
        }   
    }

    /**
     * preventCrossUserCaching
     *
     * Prevent customization from one user being cached and shown to another
     *
     * @return void
     */
    protected function preventCrossUserCaching()
    {
        if (\LocalUser::sessionExists())
        {
            $this->disableServeFromCache();
            $this->disableSaveToCache();
            $this->disableServeFromSitePublisher = true;
        }        
    }

    protected function checkForLoginAttempt()
    {
        if (isset($this->parameters['post']['loginUsername'])) {
            $this->attemptUserLogin();
            $this->disableEdgecast();
        }        
    }

    /**
     * attemptUserLogin
     * 
     * Attempts to login a user
     * 
     * @return void
     */
    protected function attemptUserLogin()
    {
        //Delagate this to the user class
        $this->user = new \User();
        $this->user->login();
    }

    /**
     * disableCacheSitePublisher
     * 
     * Disable cache when using the site publisher,
     * unless serve from site publisher has been disabled...
     *
     * @return void
     */
    protected function disableCacheSitePublisher()
    {
        if ($this->enableSitePublisher && !$this->disableServeFromSitePublisher) {
            $this->disableCache();
        }

        if(!$this->disableCache) {
            $this->serveFromCache();
        }
    }

    protected function savePageToCache()
    {
        $this->functionLib->load('cache', 'savePageToCache', __FILE__, __LINE__);
        savePageToCache($this->finalContent);
    }

    /**
     * serveFromCache()
     * 
     * Serves a request from cache
     * 
     * @return void
     */
    protected function serveFromCache()
    {
        $this->functionLib->load('cache', 'serveFromCache', __FILE__, __LINE__);

        //Serve the page from the cache if it's available in the database
        serveFromCache();
    }

    protected function setEditMode()
    {
        if(isset($this->parameters['query']['mode']) && $this->parameters['query']['mode'] == 'edit' && \Toolbox\Legacy\Auth::canWrite()) {
            $this->mode = 'edit';
            $this->text->setMode('edit');
        } else {
            $this->mode = 'save';
        }
    }

    /**
     * setContentVersionMode
     * 
     * Get the content version to use when rendering elements
     *
     * @return void
     */
    protected function setContentVersionMode()
    {
        $this->contentVersionMode = $this->getContentVersionMode();
    }

    /**
     * getContentVersionMode()
     * 
     * Sets the version mode to use for rendering the page
     * 
     * @return integer
     */
    protected function getContentVersionMode()
    {
        $contentVersionMode = CONDOR_CONTENT_VERSION_MODE_PUBLISHED;        
        if ($this->contentApprovalRequired && \User::isLoggedIn()) {
            $contentVersionMode = CONDOR_CONTENT_VERSION_MODE_PENDING;
        }

        return $contentVersionMode;
    }    

    protected function setLargeFileBaseHref()
    {
        /**
         * Set the large file base href to use for big files,
         * There are no more occurrence of the variable cdnLargeFileBaseHREF anywhere
         */
        $largeFileBaseHREF = $this->getSetting('cdn', 'largeFileBaseHREF');
        if($largeFileBaseHREF) {
            $this->page->setVar('cdnLargeFileBaseHREF', $largeFileBaseHREF);
        } else {
            $this->page->setVar('cdnLargeFileBaseHREF', 'CDN_LARGE_FILE_HREF_MISSING');
        }  
    }

    protected function callLocalCxmlEvents($eventName)
    {
        //Call CXML events
        $events = $this->getLocalCxmlEvents();
        if($events) {
            foreach ($events->event as $event)
            {
                if((string) $event['name'] == $eventName)
                {
                    $this->runLocalEvent($event);
                }
            }
        }
    }

    protected function getLocalCxmlEvents()
    {
        if ($this->localCXML->events) {
            return $this->localCXML->events;
        }

        return false;
    }

    protected function runLocalEvent()
    {
        /**
         * Lets trim the event so we dont get
         * headers already sent errors any more
         */
        eval('?>' . trim( (string) $event ) . '<?php ');        
    }

    /**
     * REFACTOR-TO: CDN Adapter
     */
    protected function setCdnStrategy()
    {
        /* Identify the type of Strategy - Akamai */
        $this->identifyCdnStrategy();

        if(isset($this->parameters['query']['cdn']) && $this->parameters['query']['cdn']==0) {
            $this->cdnSupport=false;
        }

        //If old customer, then construct the guessable akamai URL - Akamai
        if(strcasecmp($this->cdnStrategy, 'proxy-resources') == 0) {
            $pattern='@([^/]+)/$@';
            preg_match($pattern, $this->getSetting('edgecast', 'baseHREF'), $matches);
            $this->paths['edgecastBaseHREF'] = 'http://'.$matches[1].'.www.travelclickhosting.com/';
            $this->paths['edgecastCondorBaseHREF'] = $this->paths['edgecastBaseHREF'] . 'condor/';

            define('CONDOR_EDGECAST_BASE_HREF', $this->paths['edgecastCondorBaseHREF']);
        }

        //old customer:    siteBaseImageHREF is set to siteBaseHREF - Akamai
        $edgecastEnable = $this->settingMatches('edgecast', 'enable');
        if (!$this->disableEdgecast && $edgecastEnable == 1 && (strcasecmp($this->cdnStrategy,'proxy-resources') == 0)) {
            $this->useEdgecast = true;
            define ('CONDOR_MODULES_HREF',            CONDOR_EDGECAST_BASE_HREF . 'modules/');
            define ('CONDOR_BASE_JS_HREF',            CONDOR_EDGECAST_BASE_HREF . 'js/');
            $this->siteBaseImageHREF = $this->paths['edgecastBaseHREF'];
        } elseif (strcasecmp($this->cdnStrategy,'proxy-all') == 0 && \Session::exists() && $this->cdnSupport === true) {
            //new customer + logged in:    siteBaseImageHREF is set to TC's Origin HREF - Akamai
            define ('CONDOR_MODULES_HREF',            CONDOR_BASE_HREF . 'modules/');
            define ('CONDOR_BASE_JS_HREF',            CONDOR_BASE_HREF . 'js/');
            $this->siteBaseImageHREF = CONDOR_ORIGIN_HREF;
        } else {
            //Customer with no caching - Akamai
            define ('CONDOR_MODULES_HREF',            CONDOR_BASE_HREF . 'modules/');
            define ('CONDOR_BASE_JS_HREF',            CONDOR_BASE_HREF . 'js/');
            $this->siteBaseImageHREF = $this->getSetting('paths', 'siteBaseHREF');
        }
        
        //Initialise constant based on the customer type - Akamai
        define('SITE_BASE_IMAGE_HREF',$this->siteBaseImageHREF);
    }

    /**
     * identifyCdnStrategy
     * 
     * This function identifies which type of CDN strategy the customer has."proxy-resource" is for old customers who
     * cache only JS,CSS and some Images."proxy-all" is for new customer, Where almost everything is cached as decided.
     * 
     * @return void
     */
    protected function identifyCdnStrategy()
    {
        $edgecastEnable = $this->settingMatches('edgecast', 'enable');
        $cdnEnable = $this->settingMatches('cdn', 'enable');

        if($edgecastEnable) {
            $this->cdnStrategy='proxy-resources';
            $this->cdnSupport=true;
        } elseif ($cdnEnable == 1) {
            $this->cdnStrategy='proxy-all';
            $this->cdnSupport=true;
        }
        define('CDN_STRATEGY_OPTION',$this->cdnStrategy);
    }

    /**
     * setCurrentDataSection
     * 
     * Get the currect datasection
     *
     * @return void
     */
    protected function getCurrentDataSection()
    {
        $this->currentDataSection = currentDataSection();        
    }

    /**
     * setContentPaths
     * 
     * Set Various content paths
     *
     * @return void
     */
    protected function setContentPaths()
    {
        //Set the base dynamic content paths
        $this->setDynamicContentPaths();

        //Set the template paths
        $this->setTemplatePaths();

        //Set the module theme paths
        $this->setModuleThemePaths();
    }

    protected function setDynamicContentPaths()
    {
        //Set the constant for the dynamic content dir
        $this->paths['dynamicContentDir'] = CONDOR_DYNAMIC_CONTENT_DIR;
        $this->paths['dynamicContentHREF'] = condorFileToHREF($this->paths['dynamicContentDir']);

        if($this->useEdgecast) {
            $this->paths['dynamicContentHREF'] =  $this->toEdgecastHREF($this->paths['dynamicContentHREF']);
        }

        if(strcasecmp($this->cdnStrategy,'proxy-all') == 0 && \Session::exists() && $this->cdnSupport === true) {
            //Swap the URL for new customer to TC's Origin HREF - Akamai
            $this->paths['dynamicContentHREF'] =  CONDOR_ORIGIN_HREF.'d/';
        }

        if(!defined('CONDOR_DYNAMIC_CONTENT_HREF')) {
            define('CONDOR_DYNAMIC_CONTENT_HREF', $this->paths['dynamicContentHREF']);
        }

        //Set the constant for the dataSection dir
        $this->paths['dataSectionDynamicContentDir'] = getDataSectionDirectory(). '/';
        define('DATA_SECTION_DYNAMIC_CONTENT_DIR',  $this->paths['dataSectionDynamicContentDir']) ;

        $this->paths['dataSectionDynamicContentHREF'] = condorFileToHREF($this->paths['dataSectionDynamicContentDir']);
        if($this->useEdgecast) {
            $this->paths['dataSectionDynamicContentHREF'] = $this->toEdgecastHREF($this->paths['dataSectionDynamicContentHREF']);
        }
        define('DATA_SECTION_DYNAMIC_CONTENT_HREF', $this->paths['dataSectionDynamicContentHREF']);
    }

    protected function setTemplatePaths()
    {
        //This is still in here as a fallback that coincides with the temporary fix regarding customUrls. Without this, bad things will happen.
        if (!isset($this->paths['templateBaseDir'])) {
            $templateSet = (string) $this->localCXML->templateSet;

            if(mb_strpos($templateSet, '<?') !== true) {
                $templateSet = trim($this->page->capture($templateSet));
            }

            //Set the base template dir
            if (defined('CONDOR_LOCAL_TEMPLATES_DIR') && is_dir(CONDOR_LOCAL_TEMPLATES_DIR . $templateSet)) {
                $this->paths['templateBaseDir'] = CONDOR_LOCAL_TEMPLATES_DIR . $templateSet . '/';
                $this->paths['templateBaseHREF'] = CONDOR_LOCAL_TEMPLATES_HREF . $templateSet;
            } elseif (is_dir(CONDOR_TEMPLATES_DIR . $templateSet)) {
                $this->paths['templateBaseDir'] = CONDOR_TEMPLATES_DIR . $templateSet . '/';
                $this->paths['templateBaseHREF'] =  CONDOR_TEMPLATES_HREF . $templateSet;
            } else {
                trigger_error('Template Set not found. Template Set:' . $templateSet, E_USER_ERROR);
            }
        }

        $this->page->templateBaseDir = $this->paths['templateBaseDir'];
        define('TEMPLATE_BASE_DIR', $this->paths['templateBaseDir']); //For symmetry with SITE_BASE_DIR, intended for use inside cxml and module config

        $this->page->setVar('templateBaseHREF', $this->paths['templateBaseHREF']);
        $this->page->setVar('templateBaseHREFNoEdge', $this->paths['templateBaseHREF']);

        if ($this->useEdgecast == true) {
            $this->page->setVar('templateBaseHREF', $this->toEdgecastHREF($this->paths['templateBaseHREF']));
        }

        //Set up the admin templates directory
        $this->paths['adminTemplateBaseDir'] = CONDOR_TEMPLATES_DIR  . (string) $this->localCXML->adminTemplateSet . '/';
        $this->paths['adminTemplateBaseHREF'] = CONDOR_TEMPLATES_HREF . (string) $this->localCXML->adminTemplateSet  ;

        $this->page->adminTemplateBaseDir = $this->paths['adminTemplateBaseDir'];
        $this->page->setVar('adminTemplateBaseHREF', $this->paths['adminTemplateBaseHREF'] );
    }


    protected function setModuleThemePaths()
    {
        if (defined('CONDOR_LOCAL_MODULE_THEME_DIR')) {
            $this->paths['moduleThemeBaseDir'] = CONDOR_LOCAL_MODULE_THEME_DIR;
        } else {
            $this->paths['moduleThemeBaseDir'] = CONDOR_LOCAL_TEMPLATES_DIR . 'moduleThemes/';
        }

        $this->page->moduleThemeBaseDir = $this->paths['moduleThemeBaseDir'];
    }

    /**
     * setIHotelierLangId
     * 
     * Set the ihotelierLangId to use through out the page 
     *
     * @return void
     */
    protected function setIHotelierLangId()
    {
        $this->page->setVar('ihotelierlangID', ihotelierGetLanguageId());
    }

    protected function configurePage()
    {
        $this->page->setVar('language',          $this->text->getLanguage());
        $this->page->setVar('dataSection',          $this->currentDataSection);

        $this->page->setVar('currentLocation',   slashomatic($this->currentLocation, SLASHOMATIC_REMOVE_SLASH));

        $this->page->setVar('currentHost', $this->getRequest()->getServer()->get('HTTP_HOST'));

        if($this->virtualBaseArea) {
            $this->page->setVar('virtualBaseArea',      $this->virtualBaseArea);
        }

        //Ask the page class to set the vars from the local cxml
        $this->page->setPageVarsFromLocalCXML();

        //So we can plug modules in through Phoenix, we'll set the toolList here, instead of through Cxml.
        //You can still use Cxml to add a module to the toolList, but it'll be handled here.
        $mergedConfig = $this->getServiceLocator()->get('MergedConfig');
        $this->page->setVar('toolList', $mergedConfig->get(array('templateVars', 'toolList'), array()));

        $headerContent = (string) $this->localCXML->addHeaderContent;

        if($headerContent) {
            $this->page->addHeaderContent($this->page->capture($headerContent));
        }
    }

    protected function setCurrentModuleObject()  
    {
        //Serve a socket
        if ($this->socketRequested()) {
            $this->currentModule = $_REQUEST['socketModule'];
            $this->currentModuleObj = $this->createModuleObject($this->currentModule);
        } elseif($this->taskRequested()) {
            if(isset($_GET['taskItem']) AND !empty($_GET['taskItem'])) {
                $this->currentItem = $_GET['taskItem'];
            }
            $this->currentModule = $_GET['taskModule'];
            $this->currentTask   = $_GET['taskName'];

            $this->currentModuleObj = $this->createModuleObject($this->currentModule);
        } elseif ((string) $this->localCXML->content['type'] == 'module') {
            //Allow the module content to come from another dataSection
            if(isset($this->localCXML->content['dataSection'])) {
                $dataSection = $this->localCXML->content['dataSection'];
            } else {
                $dataSection = currentDataSection();
            }

            $this->currentModule = (string) $this->localCXML->content->module;
            $this->currentModuleObj = $this->createModuleObject($this->currentModule, $dataSection );
        }

        if (is_object($this->currentModuleObj)) {
            $this->moduleConf = $this->currentModuleObj->getModuleConf();
            $this->page->setVar('currentModuleNameText',  $this->currentModuleObj->moduleNameText);
        }
    }

    protected function setEventListenerModuleObj()
    {
        $this->addEventListener($this->currentModuleObj);        
    }

    protected function checkToDisableSaveToCache()
    {
        if (\User::isLoggedIn()) {
            $this->disableSaveToCache();
        }
    }

    protected function syncDynamicContent()
    {
        if(isset($this->parameters['query']['mirrorSite'])) {
            include $this->paths['condorBaseDir'] . '/modules/core/inc.synchronizeDynamicContentFolder.php';
        }          
    }

    protected function setPathsInMain()
    {
        $this->main->paths = $this->paths;
    }

    protected function checkToEnableCache()
    {
        if ($this->settingMatches('cache', 'disablePageCache') || $this->settingMatches('cache', 'enablePageCache', false)) {
            $this->disableServeFromCache();
        }
    }

    protected function initContentApproval()
    {
        if ($this->contentApprovalRequired) {
            $this->addCssAndJsForCas();
        }
    }

    protected function addCssAndJsForCas()
    {
        /**
         * Fixes http://jira.ezyield.com/browse/TB-189
         * 
         * Regarding style.contentApproval.css
         * 
         * 1) should only be loaded on CAS-enabled sites
         * 2) should only be loaded when logged in to Toolbox
         */
        if ($this->user && $this->user->isLoggedIn()) {
            $this->page->addStyleSheet(CONDOR_MODULES_HREF . 'contentApproval/style.contentApproval.css');
            $this->page->addJSFile(CONDOR_MODULES_HREF . "contentApproval/ModuleItemApproval.js");            
        }        
    }

    protected function parseMediaManagerConfig()
    {
        $xmlConfig = $this->getModuleConfig('mediaManager');
        
        $sizes = array();

        foreach($xmlConfig->images->children() as $size) {

            $type = (string)$size->type;
            $width = (string)$size->width;
            $height = (string)$size->height;

            $sizes[$size->getName()] = array(
                'type' => $type,
                'width' => $width,
                'height' => $height,
                'folder' => CONDOR_THUMBS_FOLDER_PREFIX."_{$width}_{$height}_{$type}",
            );

        }
        
        return $sizes;
    }

    protected function getDbQueryListOutput()
    {
        $returnValue = '';
        if((isset($this->parameters['query']['DBQueryList']) and $this->canShowDbQueryList())){
            foreach ($this->DBQueryList as $count => $query){
                $returnValue .="<ul style=\"position:relative; z-index:10000; color:black; background-color:white; padding:20px;border:1px solid #ccc;margin:10px auto;width:760px;overflow:auto;\">
                        <li>Query #: "  . $count . "</li>
                        <li>SQL:  <em style=\"font-size:11px\">" . $query['sql'] . "</em></li>
                        <li>FILE: " . $query['file'] . "</li>
                        <li>LINE:  " . $query['line'] . "</li>
                        <li>DB:  " . $query['db'] . "</li>
                        <li>TIME:  " . $query['time'] . "</li>
                      </ul>";
            }
        }

        return $returnValue;
    }

    protected function canShowDBQueryList()
    {
        return ($this->auth->canUserAdmin() || (string) $this->localCXML->showDebug);
    }

    protected function getCxmlOutput()
    {
        ob_clean();
        $this->setHeader('Content-Type: text/xml');
        return $this->localCXML->asXML();    
    }

    protected function getVariableHelperOutput()
    {
        $returnValue = '';

        if (isset($this->parameters['query']['variableHelper'])){
            $this->functionLib->load('core', 'createVariableTable', __FILE__, __LINE__);
            $returnValue = createVariableTable();
        }

        return $returnValue;
    }

    protected function getFilterHistoryOutput()
    {
        $returnValue = '';
        if (isset($this->parameters['query']['filterHistory'])){
            $returnValue = "<div style=\"background-color:#eee;color:#222;margin:20px;padding:20px;clear:both;\">"
                         . "<h1>Filter History</h1>"
                         . $this->page->getFilterHistory()
                         . "</div>";
        }

        return $returnValue;
    }

    protected function getKeysOutput()
    {
        $returnValue = "\n<!-- Keys "
                     . $this->localCXML->areaKey . ' ' .  $this->localCXML->pageKey
                     . "--> \n\n";
        return $returnValue;     
    }    
}