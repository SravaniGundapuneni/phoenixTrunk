<?php

namespace PhoenixSite;

use Phoenix\Module\Module as PhoenixModule;
use Users\EventManager\Event as UserEvent;
use Zend\Mvc\MvcEvent;
use Zend\Stdlib\ArrayUtils;
use Languages\EventManager\Event as LanguageEvent;

class Module extends PhoenixModule
{

    protected $moduleNamespace = __NAMESPACE__;
    protected $moduleDirectory = __DIR__;

    public function onBootstrap(MvcEvent $e)
    {
        //Get the Event Manager for the MVC Application
        $eventManager = $e->getApplication()->getEventManager();

        //Get the Service Manager
        $serviceManager = $e->getApplication()->getServiceManager();

        //We need to check the Request to see if we have a subsite that is more than one level deep.
        $this->checkForLongSubsites($e);

        $config = $serviceManager->get('Config', array());

        $this->attachCustomEvents($eventManager, $config);

        //$eventManager->attach(\Zend\Mvc\MvcEvent::EVENT_ROUTE, array($this, 'addAdditionalLevelsToSubsite'), -1);

        $eventManager->attach(\Zend\Mvc\MvcEvent::EVENT_ROUTE, array($this, 'getSubsite'), 2);

        $eventManager->attach(\Zend\Mvc\MvcEvent::EVENT_ROUTE, array($this, 'checkHttps'), -8);

        $eventManager->attach(\Zend\Mvc\MvcEvent::EVENT_ROUTE, array($this, 'routeSubsite'), -2);

        $eventManager->attach(\Zend\Mvc\MvcEvent::EVENT_RENDER, array($this, 'populateLayoutError'), 10);

        $eventManager->attach(MvcEvent::EVENT_DISPATCH_ERROR, array($this, 'setErrorTemplates'), 14);
        $eventManager->attach(MvcEvent::EVENT_RENDER_ERROR, array($this, 'setErrorTemplates'), 14);

        $eventManager->attach(MvcEvent::EVENT_DISPATCH_ERROR, array($this, 'logError'), 13);
        $eventManager->attach(MvcEvent::EVENT_RENDER_ERROR, array($this, 'logError'), 13);
        //Checks to see if a subsite is present, and if so, load its config
        //$eventManager->attach(\Zend\Mvc\MvcEvent::EVENT_ROUTE, array($this, 'checkForSubsite'), -2);
        //$eventManager->attach(\Zend\Mvc\MvcEvent::EVENT_ROUTE, array($this, 'checkForLanguage'), 5);

        $eventManager->attach(\Zend\Mvc\MvcEvent::EVENT_ROUTE, array($this, 'checkForDefaultPage'), -3);

        $eventManager->attach(\Zend\Mvc\MvcEvent::EVENT_ROUTE, array($this, 'setPropertyInfo'), -3);

        $eventManager->attach(\Zend\Mvc\MvcEvent::EVENT_RENDER, array($this, 'setPropertyInfoErrors'), 20);
        $eventManager->attach(\Zend\Mvc\MvcEvent::EVENT_ROUTE, array($this, 'setTranslationsExport'), -1000);
        $eventManager->attach(\Zend\Mvc\MvcEvent::EVENT_ROUTE, array($this, 'dynamicPath404'), -1);
        $this->updateTableStructures($e);
    }

    public function mobileRedirect(MvcEvent $e)
    {
        $serviceManager = $e->getApplication()->getServiceManager();
        $mobileDetect = $serviceManager->get('MobileDetect');

        $config = new \Config\Model\MergedConfig($serviceManager->get('Config'));

        if ($mobileDetect->isMobile() && !$mobileDetect->isIpad()) {
        $request = $e->getRequest();
            $mobisite = $request->getQuery('mobisite', '');

            if ($mobisite == 'false') {
                setcookie("mobisite", "false");
            } elseif ($request->getCookie()->mobisite != 'false') {
                return $this->doRedirect($e, $config->get('mobileRedirectUrl', $config->get(array('templateVars', 'siteroot', '/?mobisite=false'))));
            }
        }
    }

    /**
     * getAutoloaderConfig
     *
     * Sets up the Autoloader config for the module and returns it to the Application
     *
     * @return array
     */
    public function getAutoloaderConfig()
    {
        $autoloaderArray = parent::getAutoloaderConfig();
        return array_merge(array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),), $autoloaderArray); 
    }    

    public function checkHttps(MvcEvent $e)
    {
        $serviceManager = $e->getApplication()->getServiceManager();

        $configManager = $serviceManager->get('phoenix-configmanager');

        $modulesConfig = new \Config\Model\MergedConfig($configManager->getRawConfig('modulesConfig'));

        $enableSsl = $modulesConfig->get('enableSsl', false);

        $useHttps = false;

        if ($enableSsl) {
            $scheme = $e->getRequest()->getUri()->getScheme();
            $host = $e->getRequest()->getUri()->getHost();
            $query = $e->getRequest()->getUri()->getQuery();
            $path = $e->getRequest()->getUri()->getPath();

            if ($scheme != 'https') {
                if (strpos($path, '/toolbox') !== false) {
                    $useHttps = true;
                } else {
                    $useSsl = $modulesConfig->get('useSsl', false);

                    if ($useSsl) {
                        $useHttps = true;

                        $siteRoot = $modulesConfig->get(array('templateVars', 'siteroot'));

                        $newSiteRoot = str_replace('http://', 'https://', $siteRoot);
                        $modulesConfig->set(array('templateVars', 'siteroot'), $newSiteRoot);
                    }
                }

                if ($useHttps) {
                    $url = 'https://' . $host . $path;

                    if (!empty($query)) {
                        $url .= '?' . $query;
                    }

                    return $this->doRedirect($e, $url);  
                }
            }
       }
    }

    public function setTranslationsExport(MvcEvent $e)
    {
        $routeMatch = $e->getRouteMatch();

        //No need to run these if we're not doing an export.

        if ($routeMatch->getMatchedRouteName() != 'languages-toolbox' || ($routeMatch->getParam('action') != 'export' && $routeMatch->getParam('action') != 'import')) {
            return;
        }

        $phoenixSite = $e->getApplication()->getServiceManager()->get('phoenix-sitecomponent');

        $eventManager = $phoenixSite->getEventManager();

        $eventManager->attach(LanguageEvent::EVENT_EXPORT, array($phoenixSite, 'exportTranslations'));
        $eventManager->attach(LanguageEvent::EVENT_IMPORT, array($phoenixSite, 'importTranslations'));
    }

    public function setErrorTemplates(MvcEvent $e)
    {
        if (!$e->getError()) {
            return;
        }
        $serviceManager = $e->getApplication()->getServiceManager();
        $viewManager = $serviceManager->get('view_manager');
        $mergedConfig = $serviceManager->get('MergedConfig');

        $exceptionStrategy = $viewManager->getExceptionStrategy();
        $routeNotFoundStrategy = $viewManager->getRouteNotFoundStrategy();

        $displayExceptions = $exceptionStrategy->displayExceptions();

        if ($displayExceptions) {
            $exceptionStrategy->setExceptionTemplate($mergedConfig->get(array('error_handling', 'exception_template'), 'error/index'));
        }

        $displayRouteNotFoundReason = $routeNotFoundStrategy->displayNotFoundReason();

        if ($displayRouteNotFoundReason) {
            $routeNotFoundStrategy->setNotFoundTemplate($mergedConfig->get(array('error_handling', 'not_found_template'), 'error/404'));
        }
    }

    public function attachCustomEvents($eventManager, $config, $eventType = 'mvc')
    {
        $configObj = new \Config\Model\MergedConfig($config);

        $mvcEvents = $configObj->get(array('customEvents', $eventType), array());

        foreach ($mvcEvents as $valEvent) {
            //Don't want to run early events again
            if ($valEvent['event'] == 'early') {
                continue;
            }
            
            $newListener = new $valEvent['listener'];
            $priority = (isset($valEvent['priority'])) ? $valEvent['priority'] : 1;
            $eventManager->attach($valEvent['event'], array($newListener, $valEvent['method']), $priority);
        }
    }

    public function dynamicPath404(MvcEvent $e)
    {
        $path = $e->getRequest()->getUri()->getPath();

        if ($path == '/d' || $path == '/d/') {
            $e->setError(\Zend\Mvc\Application::ERROR_ROUTER_NO_MATCH);

            $results = $e->getApplication()->getEventManager()->trigger(MvcEvent::EVENT_DISPATCH_ERROR, $e);
            if (count($results)) {
                $return  = $results->last();
            } else {
                $return = $e->getParams();
            }
            
            return $return;            
        }
    }

    public function getSubsite(MvcEvent $e)
    {
        $serviceManager = $e->getApplication()->getServiceManager();

        $request = $e->getRequest();
        $path = $request->getUri()->getPath();


        $configManager = $serviceManager->get('phoenix-configmanager');
        $modulesConfig = $configManager->getRawConfig('modulesConfig');

        if (!$modulesConfig) {
            $modulesConfig = $serviceManager->get('Config', array());
        }

        $config = $modulesConfig;

        //Remove rootPath from the path
        $rootPath = (isset($modulesConfig['rootPath'])) ? $modulesConfig['rootPath'] : 'localhost';

        if ($rootPath) {
            $freshPath = str_replace('/' . $rootPath, '', $path);
        } else {
            $freshPath = $path;
        }

        $pathParts = \Phoenix\StdLib\FileHandling::parsePath($freshPath);

        $pathBase = SITE_PATH;
        $subsite = '';

        $templatePathResolver = $serviceManager->get('Zend\View\Resolver\TemplatePathStack');

        foreach ($pathParts as $valPart) {
            if ($valPart == '') {
                continue;
            }

            $pathBase .= '/' . $valPart;

            //Check to see if this is a directory
            if (file_exists($pathBase) && is_dir($pathBase)) {
                $subsite .= '/' . $valPart;
                $subsiteConfig = $this->getSiteConfig($pathBase);
                $config = $configManager->mergeModuleConfigs($config, $subsiteConfig);

                $this->addSubsiteTemplatePaths($templatePathResolver, $subsiteConfig);
            } else {
                break;
            }
        }

        $configManager->saveConfig('modulesConfig', $config);

        $site = $serviceManager->get('phoenix-site-model');

        $site->setSubsite($subsite);

        //This will set the device type.
        $site->setDeviceType();
    }

    public function routeSubsite(MvcEvent $e)
    {
        $routeMatch = $e->getRouteMatch();
        $serviceManager = $e->getApplication()->getServiceManager();
        $site = $serviceManager->get('phoenix-site-model');

        $subsite = $site->getSubsite();

        if ($subsite != $routeMatch->getParam('subsite')) {
            $routeMatch->setParam('subsite', $subsite);
        }
    }

    public function checkForDefaultPage(MvcEvent $e)
    {

        /**
         * @todo This is crap and needs to be refactored so that these routes run through the page router for default. Default shouldn't be
         * treated any differently than a normal page.
         */
        $routeMatch = $e->getRouteMatch();

        $routeName = $routeMatch->getMatchedRouteName();

        if ($routeName == 'home' || $routeName == 'root-subsite') {
            $serviceManager = $e->getApplication()->getServiceManager();
            $pagesService = $serviceManager->get('phoenix-pages');
            $configManager = $serviceManager->get('phoenix-configmanager');
            $modulesConfig = $configManager->getRawConfig('modulesConfig');
            $mergedConfig = $serviceManager->get('mergedConfig');

            if (!$modulesConfig) {
                $modulesConfig = $serviceManager->get('Config', array());
            }

            $page = $pagesService->loadPage('default', $routeMatch->getParam('subsite', ''));

            if (!is_null($page)) {
                $serviceManager->setService('currentPage', $page);
                if ($page->getPageConfig() instanceof \Config\Model\MergedConfig) {
                    $config = $configManager->mergeModuleConfigs($modulesConfig, $page->getPageConfig()->getMergedConfig());
                    $templateVars = $config['templateVars'];
                    $mergedConfig->set('templateVars', $config['templateVars']);
                }

                $routeMatch->setParam('controller', 'Pages\Controller\Index');
                $routeMatch->setParam('action', 'index');
            }
        }
        // elseif ($routeName == 'pages-page' && $routeMatch->getParam('page', '') == 'default') {
        //     //We need to route this through Toolbox' index controller for now.
        //     $routeMatch->setParam('controller', 'Toolbox\Controller\Index');
        //     $routeMatch->setParam('action', 'index');
        // }
    }

    public function setPropertyInfoErrors(MvcEvent $e)
    {
        if ($e->getError() && !$e->getParam('ignoreTemplateSetup', false)) {
            $serviceManager = $e->getApplication()->getServiceManager();

            if (!($serviceManager->has('corporateProperty') && $serviceManager->has('currentProperty'))) {
                $this->setPropertyInfo($e);
            }
        }
    }

    public function setPropertyInfo(MvcEvent $e)
    {
        //Set Current Property
        $serviceManager = $e->getApplication()->getServiceManager();
        $eventManager = $serviceManager->get('phoenix-eventmanager');
        $configManager = $serviceManager->get('phoenix-configmanager');
        $modulesConfig = $configManager->getRawConfig('modulesConfig', true);

        $phoenixProperties = new \PhoenixProperties\Service\Properties();
        $phoenixProperties->setDefaultEntityManager($serviceManager->get('doctrine.entitymanager.orm_default'));
        $phoenixProperties->setAdminEntityManager($serviceManager->get('doctrine.entityManager.orm_admin'));
        $phoenixProperties->setServiceManager($serviceManager);

        $eventManager->attach(UserEvent::EVENT_USER_LOGIN, array($phoenixProperties, 'onUserLogin'), 1000);
        $eventManager->attach(\Phoenix\EventManager\Event::EVENT_SESSION_CREATE, array($phoenixProperties, 'onSessionCreate'), -100);
        $eventManager->attach(UserEvent::EVENT_GET_USER, array($phoenixProperties, 'onGetUser'), 1000);

        $propertyCode = $modulesConfig->get('propertyCode', '');

        $currentProperty = $phoenixProperties->getProperty($propertyCode);
        $corporateProperty = null;

        // var_dump($e->getRouteMatch());
        // die('FILE: ' . __FILE__ . ' LINE: ' . __LINE__);
        //@TODO this is clunky. It needs to work in a less terrible way.
        if (!is_null($currentProperty)) {
            //@TODO add error trapping for no property returned
            if ($currentProperty && $currentProperty->getIsCorporate() == 1) {
                $corporateProperty = $currentProperty;
            } else {
                $corporateProperty = $phoenixProperties->getItemBy(array('isCorporate' => 1));
            }
        }

        if (is_null($corporateProperty)) {
            $corporateProperty = $phoenixProperties->getItemBy(array('isCorporate' => 1));
        }

        if (is_null($currentProperty)) {
            $currentProperty = $corporateProperty;
        }

        $serviceManager->setService('corporateProperty', $corporateProperty);
        $serviceManager->setService('currentProperty', $currentProperty);
    }

    public function checkForLongSubsites(MvcEvent $e) 
    {
        $request = $e->getRequest();

        $baseUrl = $request->getBaseUrl();

        $path = $request->getUri()->getPath();
        $host = $request->getUri()->getHost();

        $trimEnd = strrchr($path, '/');

        $replaceEnd = ($trimEnd == '/') ? '' : $trimEnd;

        // Link Redirects Code Start Sravani Gundapuneni
        $serviceManager = $e->getApplication()->getServiceManager();
        $eventManager = $serviceManager->get('phoenix-eventmanager');
        $configManager = $serviceManager->get('phoenix-configmanager');
        $modulesConfig = $configManager->getRawConfig('modulesConfig', true);
        //MOVED TO EARLY PHOENIX
        // Link Redirects Code
        //$linkRedirects = new \LinkRedirects\Service\LinkRedirects();
        //$linkRedirects->setDefaultEntityManager($serviceManager->get('doctrine.entitymanager.orm_default'));
        //$getRedirectUrl = $linkRedirects->getRedirectUrl('http://' . $host . $path);

        // $linkRedirectArray = array();
        // foreach ($getRedirectUrl as $valResult) {
        //     $linkRedirectArray[] = array(
        //         'incomingUrl' => $valResult->getIncomingUrl(),
        //         'redirectUrl' => $valResult->getRedirectUrl(),
        //         'response' => $valResult->getResponse(),
        //     );
        // }

        // if (!empty($linkRedirectArray)) {
        //     $resirectUrl = $linkRedirectArray[0]['redirectUrl'];

        //     $checkUrl = $linkRedirectArray[0]['incomingUrl'];
        //     $responseCode = $linkRedirectArray[0]['response'];

        //     $response = $e->getResponse();
        //     $response->getHeaders()->addHeaderLine('Location', $resirectUrl);
        //     $response->setStatusCode($responseCode);
        //     $response->sendHeaders();
        //     return $response;
        // }
        if ($baseUrl) {
            $pathTrimmed = str_replace($baseUrl . '/', '', $path);
          
            
        } else {

            $pathTrimmed = substr($path, 1);
        }

        if ($trimEnd == '/') {

            $pathTrimmed = substr($pathTrimmed, 0, -1);
        } else {

            $pathTrimmed = str_replace($trimEnd, '', $pathTrimmed);
        }
        //$pathParts = explode('/', );
        //This is a root site, so we don't need to continue
        if (!$pathTrimmed || strpos($pathTrimmed, '/') === false) {
            return true;
        }

        $pathParts = explode('/', $pathTrimmed);

        //Special routing is in place for Toolbox, so we don't need to worry about that.
        if ($pathParts[0] == 'toolbox' || $pathParts[0] == 'sockets') {
            return true;
        }

        //Also check to see if toolbox is part of the path later in the path.
        $lastPathPart = count($pathParts) - 1;

        foreach ($pathParts as $keyPart => $valPart) {
            //Special routing is in place for toolbox and sockets.
            if ($valPart == 'toolbox' || $valPart == 'sockets') {
                //We don't want the previous result counted, if these are hit.
                if (!empty($baseUrlAddon)) {
                    array_pop($baseUrlAddon);
                }
                break;
            }

            if ($keyPart < $lastPathPart) {
                $baseUrlAddon[] = $valPart;
            }
        }

        if (!empty($baseUrlAddon)) {
            $baseUrlAddonString = implode('/', $baseUrlAddon);

            $e->setParam('baseUrlAddonString', $baseUrlAddonString);
            $request->setBaseUrl($baseUrl . '/' . $baseUrlAddonString);
        }
    }

    public function addAdditionalLevelsToSubsite(MvcEvent $e)
    {
        $routeMatch = $e->getRouteMatch();

        $baseUrlAddonString = $e->getParam('baseUrlAddonString', '');

        if ($baseUrlAddonString) {
            $subsite = $routeMatch->getParam('subsite', '');

            if ($subsite) {
                $routeMatch->setParam('subsite', $baseUrlAddonString . '/' . $subsite);
            }
        }
    }

    public function getConfig()
    {
        $moduleConfig = parent::getConfig();

        $siteConfig = $this->getSiteConfig(SITE_PATH);

        if (isset($siteConfig['router']) && is_array($siteConfig['router'])) {
            $moduleConfig['router'] = array_merge_recursive($moduleConfig['router'], $siteConfig['router']);
            unset($siteConfig['router']);
        }

        return array_merge($moduleConfig, $siteConfig);
    }

    public function checkForSubsite(MvcEvent $e)
    {
        $routeMatch = $e->getRouteMatch();

        $subsite = $routeMatch->getParam('subsite', '');

        if (!$subsite) {
            return true;
        }

        $serviceManager = $e->getApplication()->getServiceManager();

        $sitePath = SITE_PATH . '/' . $subsite;

        $subsiteConfig = $this->getSiteConfig($sitePath);

        $configManager = $serviceManager->get('phoenix-configmanager');
        $modulesConfig = $configManager->getRawConfig('modulesConfig');

        if (!$modulesConfig) {
            $modulesConfig = $serviceManager->get('Config', array());
        }
        $config = $configManager->mergeModuleConfigs($modulesConfig, $subsiteConfig);
        $configManager->saveConfig('modulesConfig', $config);

        $templatePathResolver = $serviceManager->get('Zend\View\Resolver\TemplatePathStack');

        $this->addSubsiteTemplatePaths($templatePathResolver, $subsiteConfig);
        // var_dump($config['view_manager']);
        // die('FILE: ' . __FILE__ . ' LINE: ' . __LINE__);
    }

    protected function addSubsiteTemplatePaths($templatePathResolver, $subsiteConfig)
    {
        $templateConfig = new \Config\Model\MergedConfig($subsiteConfig);

        $templatePathStack = $templateConfig->get(array('view_manager', 'template_path_stack'), array());

        foreach ($templatePathStack as $valPath) {
            $templatePathResolver->addPath($valPath);
        }
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'phoenix-site-model' => function($sm) {
            $model = new Model\Site(array());
            $model->setDefaultEntityManager($sm->get('doctrine.entitymanager.orm_default'));
            $model->setAdminEntityManager($sm->get('doctrine.entityManager.orm_admin'));
            $model->setServiceManager($sm);
            return $model;
        },
                'phoenix-sitecomponent' => function ($sm) {

            $service = new Service\Components();
            //echo "initd service map markers<br/>";
            $service->setCurrentUser($sm->get('phoenix-users-current'));
            $service->setDefaultEntityManager($sm->get('doctrine.entitymanager.orm_default'));
            $service->setAdminEntityManager($sm->get('doctrine.entitymanager.orm_admin'));
            $service->setEventManager($sm->get('phoenix-eventmanager'));
            $service->setCurrentLanguage($sm->get('currentLanguage'));
            $service->setLanguagesService($sm->get('phoenix-languages'));
            $service->setServiceManager($sm);
            $service->setConfig($sm->get('MergedConfig'));
            return $service;
        },
            )
        );
    }

    public function getViewHelperConfig()
    {
        return array(
            'factories' => array(
                'PhoenixSites' => function($sm) {
            $serviceManager = $sm->getServiceLocator();

            $formService = $serviceManager->get('phoenix-sitecomponent');
            $languageComponents = $serviceManager->get('phoenix-modules');

            $helper = new \PhoenixSite\Helper\PhoenixSites($formService, $languageComponents);
            return $helper;
        },
            )
        );
    }

    protected function getSiteConfig($sitePath)
    {
        $siteConfig = array();

        //Load the site's config, if it exists
        if (file_exists($sitePath . '/config/autoload/global.php')) {
            $siteConfig = require $sitePath . '/config/autoload/global.php';
        }

        //Load the site's local config, if it exists, and merge it to the standard site config
        if (file_exists($sitePath . '/config/site.local.php')) {
            $localConfig = require $sitePath . '/config/site.local.php';
            if ($this->checkTemplateVars($siteConfig, $localConfig)) {
                $siteTemplateVars = array_merge($siteConfig['templateVars'], $localConfig['templateVars']);
                unset($localConfig['templateVars']);
                $siteConfig['templateVars'] = $siteTemplateVars;
            }

            if (isset($localConfig['view_manager'])) {
                $viewManagerConfig = array_merge($siteConfig['view_manager'], $localConfig['view_manager']);
                unset($localConfig['view_manager']);
                $viewManagerConfig['view_manager'] = $viewManagerConfig;
            }
            $siteConfig = array_merge($siteConfig, $localConfig);
        }

        return $siteConfig;
    }

    public function checkTemplateVars($siteConfig, $localConfig)
    {
        if (isset($siteConfig['templateVars']) && (is_array($siteConfig['templateVars']))) {
            if (isset($localConfig['templateVars']) && is_array($localConfig['templateVars'])) {
                return true;
            }
        }

        return false;
    }

    public function populateLayoutError($e)
    {
        $serviceManager = $e->getApplication()->getServiceManager();

        if ($e->getError() && !$e->getParam('ignoreTemplateSetup', false)) {
            $phoenix = $serviceManager->get('phoenix');

            $phoenix->populateLayout($e);
        }
    }

    /**
     * logError
     *
     * This is just a temporary method until we can implement a more robust logging system.
     * Something is better than nothing here.
     * 
     * @param  MvcEvent $e
     * @return void
     */
    public function logError(MvcEvent $e)
    {
        $request = $e->getRequest();
        $serviceManager = $e->getApplication()->getServiceManager();
        $eventManager = $serviceManager->get('phoenix-eventmanager');
        $configManager = $serviceManager->get('phoenix-configmanager');
        $modulesConfig = $configManager->getRawConfig('modulesConfig', true);
        $linkRedirectsService = $serviceManager->get('phoenix-linkRedirects');

        $path = $request->getUri()->getPath();

        if ($e->getError()) {
            $type = $e->getError();

            switch ($type) {
                case 'error-exception':
                    $message = $e->getParam('exception');
                    break;
                case 'error-controller-cannot-dispatch':
                    $message = 'The requested controller #Controller# was unable to dispatch the request.';
                    break;
                case 'error-controller-not-found':
                    $message = 'The requested controller #Controller# could not be mapped to an existing controller class.';
                    break;
                case 'error-controller-invalid':
                    $message = 'The requested controller #Controller# was not dispatchable.';
                    break;
                case 'error-router-no-match':
                    $message = 'The requested URL could not be matched by routing.';
                    //We only want link redirect to be triggered for real 404 errors.
                    $setLinkRedirects = $linkRedirectsService->setLinkRedirects($request);
                    break;
                default:
                    $message = 'We cannot determine at this time why an error was generated.';
                    break;
            }

            $routeMatch = $e->getRouteMatch();

            if ($routeMatch) {
                $controller = $routeMatch->getParam('controller', '');

                if ($controller) {
                    $message = str_replace("#Controller#", $controller, $message);
                }
            }

            $logMessage = "Type: $type Path: $path Message: $message";
            error_log($logMessage);
        }
    }

}
