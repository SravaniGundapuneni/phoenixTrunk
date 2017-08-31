<?php

namespace Toolbox;

class Phoenix
{

    const LEGACY_CONTROLLER = 'Toolbox\Controller\Legacy';
    const TOOLBOX_LANGUAGE_CODE = 'en';

    protected $requestedUrl;

    public function init($e)
    {
        $routeMatch = $e->getRouteMatch();

        // print_r('<pre>');
        // print_r($routeMatch);
        // print_r('</pre>');
        // die;
        //Do setup for all 
        if (!$this->initAll($e)) {
            $e->stopPropagation();
            return false;
        }

        $initMethod = 'initPhoenix';

        //We don't need to run this if we aren't using a legacy controller.
        if ($routeMatch->getParam('controller') == static::LEGACY_CONTROLLER) {
            $initMethod = 'initLegacy';
        }

        if (!$this->$initMethod($e)) {
            $e->stopPropagation();
            return false;
        }

        return true;
    }

    /**
     * initAll
     *
     * This will handle initizlization of the overall application, both legacy and Phoenix.
     * 
     * @param  MvcEvent $e 
     * @return boolean
     */
    public function initAll($e)
    {
        $this->initErrorHandling($e);

        return true;
    }

    /**
     * initLegacy
     *
     * This runs the initial setup for the Main Adapter for the legacy application.
     * @param  MvcEvent $e
     * @return boolean
     */
    public function initLegacy($e)
    {
        $serviceManager = $e->getApplication()->getServiceManager();
        $configManager = $serviceManager->get('phoenix-configmanager');
        $mergedConfig = $serviceManager->get('MergedConfig');
        $appSettings = $serviceManager->get('ApplicationConfig');
        $config = $serviceManager->get('Config');

        $iniSettings = $appSettings['iniSettings'];

        $getParams = $e->getRequest()->getQuery()->toArray();
        $postParams = $e->getRequest()->getPost()->toArray();
        $routeParams = $e->getRouteMatch()->getParams();

        $condor = new \Toolbox\Legacy\Condor();
        $condor->setMergedConfig($configManager);

        if (!isset($getParams['preRenderPage']) || !$getParams['preRenderPage']) {
            $condor->setHrefConstants($iniSettings);
        }

        $condor->setHostMap($iniSettings);

        if (isset($getParams['preRenderPage']) && $getParams['preRenderPage']) {
            $condor->loadSitePublisher($iniSettings);
            return false;
        }

        $condor->loadRequiredFiles($iniSettings);

        $main = $serviceManager->get('phoenix-legacy-main');

        $main->initSettingsAndMainObject($iniSettings);
        $main->setPaths($mergedConfig->get('paths', $iniSettings['paths']));
        $main->setLegacyPathway($config['legacyPathway']);
        $main->setRequest($e->getRequest());
        $main->setParameters(array('query' => $getParams, 'post' => $postParams, 'route' => $routeParams));

        if (isset($getParams['edgecast']) && $getParams['edgecast'] == 0) {
            $main->disableEdgecast();
        }

        $main->setPhoenix($this);
        $main->init();
        \Toolbox\Legacy\MainLoader::setInstance($main);
        return true;
    }

    /**
     * This runs the initialization related solely to Phoenix
     * @param  MvcEvent $e 
     * @return boolean
     */
    public function initPhoenix($e)
    {
        return true;
    }

    /**
     * setRequestedUrl
     *
     * Puts together the requestedUrl property, used in determining redirects.
     * @param Zend\Http\Request
     */
    public function setRequestedUrl($request)
    {
        $httpHost = $request->getServer()->get('HTTP_HOST');
        $requestUri = $request->getRequestUri();

        $this->requestedUrl = $httpHost . $requestUri;

        if (mb_strpos($this->requestedUrl, 'http://') === false) {
            $this->requestedUrl = 'http://' . $this->requestedUrl;
        }
    }

    /**
     * getRequestedUrl
     * 
     * Get the Requested Url
     * @return string
     */
    public function getRequestedUrl()
    {
        return $this->requestedUrl;
    }

    public function initErrorHandling($e)
    {
        $serviceManager = $e->getApplication()->getServiceManager();
        $settings = $serviceManager->get('MergedConfig')->getMergedConfig();

        if (!isset($settings['errors']['use_php_error_handler']) || !$settings['errors']['use_php_error_handler']) {
            $errorHandler = $serviceManager->get('phoenix-errorhandler');
            set_error_handler(array($errorHandler, 'logError'));
        }
    }

    public function setPaths($e)
    {
        $serviceManager = $e->getApplication()->getServiceManager();

        $routeMatch = $e->getRouteMatch();

        $mergedConfig = $serviceManager->get('MergedConfig');

        $toolboxIncludeUrl = $mergedConfig->get(array('paths', 'toolboxIncludeUrl'), '');

        if (!$toolboxIncludeUrl) {
            $mergedConfig->set(array('paths', 'toolboxIncludeUrl'), '/phoenix/');
        } else {
            //Make sure ToolboxIncludeUrl ALWAYS includes a '/' at the end.
            if (substr($toolboxIncludeUrl, -1) !== '/') {
                $mergedConfig->set(array('paths', 'toolboxIncludeUrl'), $toolboxIncludeUrl . '/');
            }
        }
    }

    protected function setDynamicPaths($paths)
    {
        // $paths['foo'] = 'bar';

        return $paths;
    }

    public function populateLayout($e)
    {
        //Legacy doesn't know what to do with this stuff. Let's not let it try.
        if ($e->getParam('isLegacy')) {
            return true;
        }
        $serviceManager = $e->getApplication()->getServiceManager();

        $viewManager = $serviceManager->get('view-manager');
     
        $mergedConfig = $serviceManager->get('MergedConfig');

        //Check to see if we're coming here through the error track.
        //We need to use a different viewModel if so.
        if ($e->getError()) {
            $viewModel = $e->getResult();
        } else {
            $viewModel = $viewManager->getViewModel();
        }

        $routeMatch = $e->getRouteMatch();

        if ($routeMatch instanceof \Zend\Mvc\Router\RouteMatch) {
            $viewModel->langCode = $routeMatch->getParam('langCode', 'en');
            $viewModel->routePage = $routeMatch->getParam('page', 'default');
            $subsite = $routeMatch->getParam('subsite', '');
        } else {
            $siteModel = $serviceManager->get('phoenix-sitemodel');
            $subsite = $siteModel->getSubsite();
        }

        $currentUser = $serviceManager->get('phoenix-users-current');

        $viewModel->currentAction = $e->getParam('action');

        $currentItemId = $e->getParam('itemId', 0);


        $viewModel->mediaRoot = $mergedConfig->get('mediaRoot');

        if ($currentItemId > 0) {
            $viewModel->currentItemId = $currentItemId;
        }

        $requestUri = $e->getRequest()->getUri();

        $viewModel->useHttps = ($requestUri->getScheme() == 'https') ? true : false;

        $viewModel->runtimeVariables = array();

        $isDeveloper = false;
        $hasUser = false;
        // print_r('<pre>');
        // print_r($e->getRouteMatch());
        // print_r('</pre>');
        // die('FILE: ' . __FILE__ . ' LINE: ' . __LINE__);
        if ($currentUser->getUserEntity()) {
            $hasUser = true;
            $viewModel->currentUser = array(
                'userId' => $currentUser->getUserEntity()->getId(),
                'username' => $currentUser->getUserEntity()->getUsername(),
                'givenName' => $currentUser->getUserEntity()->getGivenName(),
                'email' => $currentUser->getUserEntity()->getEmail(),
                'scope' => $currentUser->getUserEntity()->getScope()
            );

            if ($currentUser->getUserEntity()->getType() == 2) {
                $isDeveloper = true;
            }
            // current user for pagetype[content/landing] to pass the isDevelper() method
            $viewModel->currentUserObject= $currentUser;
            
            
        }

        $subsitePath = '/';

        $virtualBaseArea = $mergedConfig->get('virtualBaseArea', 'toolbox/');
        $toolboxRootRoute = 'home/toolbox-root';
        if ($subsite) {
            $subsitePath .= $subsite . '/';
            $virtualBaseArea = substr($subsite . '/' . $virtualBaseArea, 1);
            $toolboxRootRoute = 'home/toolbox-root-subsite';
            $subsiteRoute = substr($subsite, 1);
        } else {
            $subsiteRoute = null;
        }

        //This is a version of subsite that is usable for building routes.
        $viewModel->subsiteRoute = $subsiteRoute;

        $viewModel->toolboxRootRoute = $toolboxRootRoute;
        $viewModel->subsite = $subsite;

        $routeSubsitePath = '';
        if (!empty($routeMatch)) {
            $routeSubsitePath = $routeMatch->getParam('subsite', '');
        }

        $viewModel->subsiteToolboxPath = $mergedConfig->get('subsiteToolboxPath', $routeSubsitePath);

        //This is different from toolboxIncludeUrl, as this is for links within Toolbox, as opposed to a path to include images, css, js, etc...
        $viewModel->toolboxHomeUrl = $e->getRequest()->getBaseUrl() . '/' . $virtualBaseArea;

        $viewModel->virtualBaseArea = $virtualBaseArea;

        $templateVars = $mergedConfig->get('templateVars', array());

        //@TODO Make these customizable on a site by site basis
//        $templateVars['generalToolList'] = array(
//           'Site Content' => '',
//           // 'Publish' => '',
//           // 'Web Forms' => '',
//           // 'Training Information' => ''
//        );

        $acl = $serviceManager->get('phoenix-users-acl');

        $toolList = $mergedConfig->get(array('toolbox', 'toolList'), array());

        $templateVars['dlmToolList'] = array_merge(array(
            'MailingList'=>'tools/mailingList',
            'Map Markers' => 'tools/mapMarkers',
            'PhoenixReview'=>'tools/phoenixReview',
        ), $toolList);

        ksort($templateVars['dlmToolList']);

        /**
         * @todo  This needs to be dynamic and based upon what modules are actually loaded.
         */        
        $templateVars['toolList'] = array(
            'Addons' => 'tools/phoenixAddons',
            'Calendar'=>'tools/calendar',
            //'Events' => 'tools/phoenixEvents',
            'Hero Images' => 'tools/heroImages',
            'Media Manager' => 'tools/mediaManager',
            'Pages' => 'tools/pages',
            'Rates' => 'tools/phoenixRates',
            'Rooms' => 'tools/phoenixRooms',
            'Web Forms' => 'tools/flexibleForms',
			'Content Filter' => 'tools/contentFilter',
            // 'Content Blocks' => 'tools/blocks',
            // 'Downloads' => '',
            // 'Guest Reviews' => '',
            // 'Ihotelier Rooms' => '',
            // 'Landing Pages' => '',
            // 'Mailing List' => '',
            // 'Packages' => '',
            // 'Social Toolbar' => 'tools/phoenixSocialToolbar',         
            //'Attributes' => 'tools/phoenixAttributes',
            //'Mvc Test'=>'tools/mvcTest',
            
                // 'Featured Rates' => '',
                // 'News Articles' => '',
                // 'Calendar Node' => '',
                // 'Calendar' => '',
                // 'Wedding Builder' => '',
                // 'Social Toolbar' => '',
                // 'Tagged Content' => '',
                );

        if ($currentUser->getId() && !$acl->isUserAllowed($currentUser, null, 'canAdmin')) {
            $templateVars['toolList'] = array('MediaManager' => 'tools/mediaManager',
                'Events' => 'tools/phoenixEvents',
                'Pages' => 'tools/pages');
        }

        $isAdmin = false;
        if ($hasUser) {
            if ($acl->isUserAllowed($currentUser, null, 'canAdmin')) {
                $templateVars['adminToolList'] = array();

                $manageUsers = false;

                if (!$currentUser->isSuperAdmin()) {
                    foreach ($currentUser->getGroups() as $valGroup) {
                        if ($valGroup->getName() == 'manageUsers') {
                            $manageUsers = true;
                        }
                    }
                } else {
                    $manageUsers = true;
                }

                if ($manageUsers) {
                    $templateVars['adminToolList']['Users'] = 'tools/users';
                }
                $templateVars['adminToolList'] = array_merge($templateVars['adminToolList'], array(
                    //'Dynamic List Modules' => 'tools/dynamicListModule',
                    //'Sitemap' => 'tools/siteMap',
                    // 'Links redirects' => '',
                    // 'Custom URLs' => '',
                    // 'Deployer' => '',
                    // 'Google Analytics' => '',
                    // 'Text and translations' => '',
                    'Content Approval System' => 'tools/contentApproval',
                    'Google Analytics' => 'tools/googleAnalytics',
                    'Hotels' => 'tools/phoenixProperties',
                    'iHotelier' => 'tools/IHotelier',
                    //'Integration' => 'tools/integration',
                    'Link Redirects' => 'tools/linkRedirects',
                    'Manage languages' => 'tools/languages',
                    'Refresh Cache' => 'sockets/pageCache/refresh',
                    'SEO File Upload' => 'tools/seoFileUpload',
                    'SEO Meta Text' => 'tools/seoMetaText',
                ));
                $isAdmin = true;
            }

            $templateVars['devToolList'] = array(
                'Footer Navigation' => 'tools/footer', // dev tool
                'Layout Editor' => 'tools/layoutEditor', // dev tool
                'Navigations' => 'tools/navigations', // dev tool
                'Phoenix Sitemap'=>'tools/siteMaps', // dev tool
                'Phoenix Templates' =>  'tools/phoenixTemplates', // dev tool
                'Style' => 'tools/style', // dev tool
                'Template Builder' => 'tools/templateBuilder', // dev tool
            );
        }

        $uri = $e->getRequest()->getUri();

        $externalSiteRoot = $uri->getScheme() . '://' . $uri->getHost() . '/';

        $viewModel->externalSiteRoot = $externalSiteRoot;

        $currentLanguageArray = $serviceManager->get('currentLanguage');
        $currentLanguage = array();
        $currentLanguage['code'] = $currentLanguageArray->getCode();
        $templateVars['currentLanguage'] = $currentLanguage;

// Header Menu
        $currentProperty = $serviceManager->get('currentProperty');
        if ($currentProperty->getId() == 22 || $currentProperty->getId() == 10) {
            $headerComponents = $serviceManager->get('phoenix-navigations');
            $mainmenu = 'mainmenu';
            $mainMenu = $headerComponents->getNavigationComponent($mainmenu);


            if (isset($_SESSION['yfuser'])) {
                if ($currentLanguage['code'] == 'fr') {
                    unset($mainMenu[$mainmenu][17]['submenu'][2]);
                    $pushArray = array(
                        'key' => 'account',
                        'name' => 'Tableau de bord',
                        'url' => 'youfirst/account/fr',
                    );
                    array_push($mainMenu[$mainmenu][17]['submenu'], $pushArray);
                } else {
                    unset($mainMenu[$mainmenu][6]['submenu'][2]);
                    $pushArray = array(
                        'key' => 'account',
                        'name' => 'Dashboard',
                        'url' => 'youfirst/account',
                    );
                    array_push($mainMenu[$mainmenu][6]['submenu'], $pushArray);
                }
            }
            $templateVars[$mainmenu] = $mainMenu[$mainmenu];
        }





        // Google Analytics code added by Sravani Gundapuneni

        $googleAnalytics = $serviceManager->get('phoenix-googleAnalytics');
        $templateVars['googleAnalytics'] = $googleAnalytics->checkGoogleAnalytics();

        // Google Analytics code End  
        $viewModel->isAdmin = $isAdmin;
        $viewModel->isDeveloper = $isDeveloper;

        if ($isDeveloper) {
            $templateVars['adminToolList']['Dynamic List Modules'] = 'tools/dynamicListModule';
            //$templateVars['adminToolList']['Flexible Forms'] = 'tools/flexibleForms';
        }

        $toolListArrays = array('toolList', 'generalToolList', 'adminToolList');

        foreach ($templateVars as $keyVar => $valVar) {
            if (in_array($keyVar, $toolListArrays)) {
                // $listCount = count($valVar);
                // $lastKey = $listCount - 1;
                // $valVar[$lastKey]['last'] = true;
            }

            if (in_array($keyVar, array('siteroot', 'tmpltroot')) && substr($valVar, -1) !== '/') {
                $valVar = $valVar . '/';
            }
            $viewModel->$keyVar = $valVar;
        }

        $viewModel->showCas = $mergedConfig->get(array('edit', 'requireContentApproval'));

        $paths = $mergedConfig->get('paths');

        foreach ($paths as $keyPath => $valPath) {
            $viewModel->$keyPath = $valPath;
        }

        $viewModel->page = $mergedConfig->get('page', '');
        $viewModel->pageKey = $mergedConfig->get('pageKey', '');
        $viewModel->pageName = $mergedConfig->get('pageName', '');

        $viewModel->response = $e->getResponse();

//        echo '<pre>';
//        print_r($templateVars);
//        echo '</pre>';
        // print_r('<pre>');
        // print_r($mergedConfig->get('phoenix-filters'));
        // print_r('</pre>');
        // die('FILE: ' . __FILE__ . ' LINE: ' . __LINE__);
    }

    public function checkForLegacy($e)
    {
        $routeMatch = $e->getRouteMatch();
        // print_r('<pre>');
        // print_r($routeMatch);
        // print_r('</pre>');
        // die('FILE: ' . __FILE__ . ' LINE: ' . __LINE__);

        $controller = $routeMatch->getParam('controller');

        $isLegacy = strpos($controller, 'Controller\Legacy') !== false;
        $e->setParam('isLegacy', $isLegacy);
    }

    /**
     * checkForCxmlIgnore
     *
     * Checks to see if the matched controller and action should be ignored when loading Cxml.
     *
     * Not intended for legacy pages, which should all have corresponding cxml.
     * 
     * @param  \Zend\Mvc\MvcEvent $e
     * @return void
     */
    public function checkForCxmlIgnore($e)
    {
        $cxmlIgnore = false;

        //Get Route Match Object and Config array
        $routeMatch = $e->getRouteMatch();
        $config = $e->getApplication()->getServiceManager()->get('Config');

        //Get the ignore Cxml array from the module config array.
        $cxmlIgnoreArray = $config['cxmlIgnore'];

        //Get the controller and action
        $controller = $routeMatch->getParam('controller');
        $action = $routeMatch->getParam('action');

        //Check to see if the controller and action should be ignored when looking for Cxml
        if (in_array($controller, array_keys($cxmlIgnoreArray))) {
            if (is_array($cxmlIgnoreArray[$controller])) {
                //If the action is found, ignore the Cxml. Otherwise, there should be a corresponding Cxml page for the action.
                if (in_array($action, $cxmlIgnoreArray[$controller])) {
                    $cxmlIgnore = true;
                }
            }
            //All actions in controller should be ignored when looking for Cxml.
            $cxmlIgnore = true;
        }
        //Controller not found, assume that there is a corresponding Cxml page to be found
        $e->setParam('cxmlIgnore', $cxmlIgnore);
    }

}
