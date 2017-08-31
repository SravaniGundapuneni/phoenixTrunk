<?php
namespace Users;

use Phoenix\Module\Module as PhoenixModule;
use Zend\Mvc\MvcEvent;
use Phoenix\EventManager\Event as PhoenixEvent;
class
 Module extends PhoenixModule
{
    protected $moduleNamespace = __NAMESPACE__;
    protected $moduleDirectory = __DIR__;

    public function onBootstrap(MvcEvent $e)
    {
        $serviceManager = $e->getApplication()->getServiceManager();
        $eventManager = $e->getApplication()->getEventManager();
        $phoenixEvents = $serviceManager->get('phoenix-eventmanager');
        $users = $serviceManager->get('phoenix-users');

        $sessions = $serviceManager->get('phoenix-users-sessions');
        $acl = $serviceManager->get('phoenix-users-acl');
        // $phoenixEvents->attach(PhoenixEvent::EVENT_CONFIGMERGE, array($users, 'onConfigMerge'), 100);
        $phoenixEvents->attach(PhoenixEvent::EVENT_CONFIGMERGE, array($acl, 'onConfigMerge'), -10000);
        $phoenixEvents->attach(PhoenixEvent::EVENT_NEEDS_LOGIN, array($this, 'showLoginForm'), 101);
          
        $phoenixEvents->attach(PhoenixEvent::EVENT_NEEDS_LOGIN, array($this, 'onNeedsLogin'), 100);
        // $phoenixEvents->attach(\Users\EventManager\Event::EVENT_LOAD_CURRENTUSER, array($acl, 'onLoadCurrentUser'), 100);
        // $phoenixEvents->attach(\Users\EventManager\Event::EVENT_GET_USER, array($acl, 'onGetUser'), 100);
        $eventManager->attach(MvcEvent::EVENT_ROUTE, array($sessions, 'start'), -1000);
        $eventManager->attach(MvcEvent::EVENT_ROUTE, array($acl, 'setupBaseAcl'), -1000);
        $eventManager->attach(MvcEvent::EVENT_ROUTE, array($this, 'checkForLogout'), -1001);
        $eventManager->attach(MvcEvent::EVENT_ROUTE, array($this, 'restrictedPage'), -1002);
        // $eventManager->attach(MvcEvent::EVENT_ROUTE, array($acl, 'setupUserAcl'), -1005);
        $eventManager->attach(MvcEvent::EVENT_FINISH, array($this, 'onFinish'), 100);
 
        $viewModel = new \Zend\View\Model\ViewModel();
        $viewModel->setTemplate('users/layout/module');
        $viewModel->setVariable('title',"Users");

        $serviceManager->setService('users-layout', $viewModel);

        /**
         * Module Migrations Support
         */
        $this->updateTableStructures($e);          
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

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'phoenix-users-acl' => function($sm) {
                    $service = new Service\Acl();
                    $service->setDefaultEntityManager($sm->get('doctrine.entitymanager.orm_default'));
                    $service->setAdminEntityManager($sm->get('doctrine.entitymanager.orm_admin'));
                    $service->setEventManager($sm->get('phoenix-eventmanager'));
                    $service->setServiceManager($sm);
                    return $service;
                },
                'phoenix-users-groups' => function($sm) {
                    $service = new Service\Groups();
                    $service->setDefaultEntityManager($sm->get('doctrine.entitymanager.orm_default'));
                    $service->setAdminEntityManager($sm->get('doctrine.entitymanager.orm_admin'));
                    $service->setEventManager($sm->get('phoenix-eventmanager'));
                    $service->setServiceManager($sm);

                    //This check is in case this is called before MergedConfig is created.
                    if ($sm->has('MergedConfig')) {
                        $service->setConfig($sm->get('MergedConfig'));
                    }
                    return $service;
                },    
                'phoenix-users-permissions' => function($sm) {
                    $service = new Service\Permissions();
                    $service->setDefaultEntityManager($sm->get('doctrine.entitymanager.orm_default'));
                    $service->setAdminEntityManager($sm->get('doctrine.entitymanager.orm_admin'));
                    $service->setEventManager($sm->get('phoenix-eventmanager'));
                    
                    //This check is in case this is called before MergedConfig is created.
                    if ($sm->has('MergedConfig')) {
                        $service->setConfig($sm->get('MergedConfig'));
                    }
                    return $service;
                },                                
                'phoenix-users-sessions' => function($sm) {
                    $service = new Service\Sessions();
                    $service->setDefaultEntityManager($sm->get('doctrine.entitymanager.orm_default'));
                    $service->setAdminEntityManager($sm->get('doctrine.entitymanager.orm_admin'));
                    return $service;
                },                
                'phoenix-users' => function($sm) {
                    $service = new Service\Users();
                    $service->setDefaultEntityManager($sm->get('doctrine.entitymanager.orm_default'));
                    $service->setAdminEntityManager($sm->get('doctrine.entitymanager.orm_admin'));
                    $service->setEventManager($sm->get('phoenix-eventmanager'));                    
                    $service->setServiceManager($sm);
                    return $service;
                },
                'phoenix-users-alerts' => function($sm) {
                    $service = new Service\Alerts();
                    if ($sm->has('MergedConfig')) {
                        $service->setConfig($sm->get('MergedConfig'));
                    }
                    $service->setDefaultEntityManager($sm->get('doctrine.entitymanager.orm_default'));
                    $service->setAdminEntityManager($sm->get('doctrine.entitymanager.orm_admin'));
                    $service->setSessions($sm->get('phoenix-users-sessions'));

                    return $service;
                },
                'phoenix-users-current' => function($sm) {
                    $service = new Model\User($sm->get('MergedConfig'));
                    $service->setDefaultEntityManager($sm->get('doctrine.entitymanager.orm_default'));
                    $service->setAdminEntityManager($sm->get('doctrine.entitymanager.orm_admin'));
                    $service->setEventManager($sm->get('phoenix-eventmanager'));
                    $service->setAlerts($sm->get('phoenix-users-alerts'));
                    return $service;
                }
            )
        );
    }

    public function onViewResponse($e)
    {
        // $result = 
        // die('FILE: ' . __FILE__ . ' LINE: ' . __LINE__);
    }

    public function checkForLogout($e)
    {
        $request = $e->getRequest();
        $serviceManager = $e->getApplication()->getServiceManager();
        $sessions = $serviceManager->get('phoenix-users-sessions');

        $routeMatch = $e->getRouteMatch();


        $logout = $request->getQuery()->get('logout');

        if ($logout){
            if ($sessions->read()) {
                $sessions->destroy();
            }

            $routeMatch->setParam('controller', 'Users\Controller\Index');
            $routeMatch->setParam('action', 'logout');
        }
    }

    public function restrictedPage($e)
    {
        $serviceManager = $e->getApplication()->getServiceManager();

        $phoenixEvents = $serviceManager->get('phoenix-eventmanager');
        $routeMatch = $e->getRouteMatch();

        if (strpos($routeMatch->getParam('controller'), 'Controller\Toolbox') !== false) {
            $sessions = $serviceManager->get('phoenix-users-sessions');
            $loggedIn = $sessions->isLoggedIn();

            if (!$loggedIn) {
               $needsLogin = $phoenixEvents->trigger(\Phoenix\EventManager\Event::EVENT_NEEDS_LOGIN, '\Phoenix\EventManager\Event', array('MvcEvent' => $e));
                if ($needsLogin->stopped()) {
                    $response = $needsLogin->last();

                    return $response;
                }
            }
        }

    }

    public function showLoginForm($e)
    {
        $mvcEvent = $e->getParam('MvcEvent');
        $serviceManager = $mvcEvent->getApplication()->getServiceManager();
        $routeMatch = $mvcEvent->getRouteMatch();

        $acceptedRoutes = array(\Toolbox\Module::TOOLBOX_ROUTE_ROOT_KEY,
                                \Toolbox\Module::TOOLBOX_SUBSITE_ROUTE_ROOT_KEY);


        if (in_array($routeMatch->getMatchedRouteName(), $acceptedRoutes)) {
            $routeMatch->setParam('controller', 'Users\Controller\Toolbox');
            $routeMatch->setParam('action', 'login');
            
            $e->stopPropagation();
        }        

        return true;
    }

    public function onNeedsLogin($e)
    {
        $mvcEvent = $e->getParam('MvcEvent');
        $serviceManager = $mvcEvent->getApplication()->getServiceManager();
        $routeMatch = $mvcEvent->getRouteMatch();
        //$subsite = $routeMatch->getParam('subsite');
        $config = $serviceManager->get('MergedConfig');

        $siteModel = $serviceManager->get('phoenix-site-model');

        $subsite = $siteModel->getSubsite();
        $routeMatch->setParam('subsite', $subsite);
        //$subsitePath = '/';

        $urlRoot = $config->get(array('templateVars', 'siteroot'), $mvcEvent->getRequest()->getBaseUrl() . '/');

        if ($subsite) {
            if (substr($urlRoot, -1) == '/') {
                $urlRoot = substr($urlRoot, 0, -1);
            }

            $urlRoot .= $subsite . '/';
        }

        $target =  $urlRoot . 'toolbox/';
        
        $this->doRedirect($mvcEvent, $target);
        
        return false;
    }

    public function onFinish($e)
    {
        $serviceManager = $e->getApplication()->getServiceManager();
        $ipAddress = $ipAddress = $e->getRequest()->getServer()->get('REMOTE_ADDR');

        $sessions = $serviceManager->get('phoenix-users-sessions');
        $sessions->close($ipAddress);
    }
}
