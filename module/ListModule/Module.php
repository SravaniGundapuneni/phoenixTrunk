<?php
namespace ListModule;

use Phoenix\Module\Module as PhoenixModule;

use Zend\Mvc\MvcEvent;

class Module extends PhoenixModule
{
    protected $moduleNamespace = __NAMESPACE__;
    protected $moduleDirectory = __DIR__;

    /**
     * onBootstrap event
     *
     * This was added to make sure the module layout is set so it can be used by ListModules
     * A. Tate 09/05/13
     * @param  MvcEvent $e
     * @return void
     */
    public function onBootstrap(MvcEvent $e)
    {
        $viewModel = new \Zend\View\Model\ViewModel();
        $viewModel->setTemplate('list-module/layout/module');
        $serviceManager = $e->getApplication()->getServiceManager();
        $serviceManager->setService('listModule-layout', $viewModel);

        $eventManager = $e->getApplication()->getEventManager();
        $eventManager->attach(\Zend\Mvc\MvcEvent::EVENT_DISPATCH, array($this, 'setLayout'), 100);
        $eventManager->attach(\Zend\Mvc\MvcEvent::EVENT_DISPATCH, array($this, 'checkForEmptyTrash'), -100);
        /**
         * Module Migrations Support
         */
        $this->updateTableStructures($e);
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'listModule-categories' => function ($sm) {
                    $service = new Service\Categories();
                    $service->setCurrentUser($sm->get('phoenix-users-current'));
                    $service->setDefaultEntityManager($sm->get('doctrine.entitymanager.orm_default'));
                    $service->setAdminEntityManager($sm->get('doctrine.entitymanager.orm_admin'));
                    $service->setEventManager($sm->get('phoenix-eventmanager'));
                    $service->setConfig($sm->get('MergedConfig'));
                    $service->setServiceManager($sm);
                    return $service;
                },
                    'phoenix-listmodule-categories' => function ($sm) {
                    $service = new Service\Categories();
                    $service->setCurrentUser($sm->get('phoenix-users-current'));
                    $service->setDefaultEntityManager($sm->get('doctrine.entitymanager.orm_default'));
                    $service->setAdminEntityManager($sm->get('doctrine.entitymanager.orm_admin'));
                    $service->setEventManager($sm->get('phoenix-eventmanager'));
                    $service->setConfig($sm->get('MergedConfig'));
                    $service->setServiceManager($sm);
                    $service->setCurrentLanguage($sm->get('currentLanguage'));                    
                    return $service;
                },
            )
        );
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
    
    public function setLayout(MvcEvent $e)
    {
        $routeMatch = $e->getRouteMatch();

        $controller = $routeMatch->getParam('controller');

        if (strpos($controller, 'Controller\ModuleToolbox') > 0 || strpos($controller, 'Controller\Categorytoolbox') > 0) {
            $e->getViewModel()->setTemplate('toolbox-layout');
        }
    }

    public function checkForEmptyTrash(MvcEvent $e)
    {
        $serviceManager = $e->getApplication()->getServiceManager();
        $currentUser = $serviceManager->get('phoenix-users-current');
        $acl = $serviceManager->get('phoenix-users-acl');

        $e->getViewModel()->emptyTrash = false;

        $socketsRoute = $e->getViewModel()->socketsRoute;

        $disableTrash = array('toolbox-sockets', 'contentApproval-sockets', 'listModule-sockets');

        if (!in_array($socketsRoute, $disableTrash) && $acl->isUserAllowed($currentUser, null, \Users\Service\Acl::PERMISSIONS_RESOURCE_ADMIN)) {
            $e->getViewModel()->emptyTrash = true;

            foreach ($e->getViewModel()->getChildren() as $valChild) {
                $valChild->emptyTrash = true;
            }
        }        
    }
}
