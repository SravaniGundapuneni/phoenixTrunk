<?php
namespace DynamicListModule;

use Phoenix\Module\Module as PhoenixModule;

use Zend\Mvc\MvcEvent;

use Languages\EventManager\Event as LanguageEvent;

class Module extends PhoenixModule
{
    protected $moduleNamespace = __NAMESPACE__;
    protected $moduleDirectory = __DIR__;

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'phoenix-dynamiclistmodule' => function ($sm) {
                    $service = new Service\DynamicListModule();
                    $service->setCurrentUser($sm->get('phoenix-users-current'));
                    $service->setDefaultEntityManager($sm->get('doctrine.entitymanager.orm_default'));
                    $service->setAdminEntityManager($sm->get('doctrine.entitymanager.orm_admin'));
                    $service->setEventManager($sm->get('phoenix-eventmanager'));
                    $service->setConfig($sm->get('MergedConfig'));
                    $service->setServiceManager($sm);
                    $service->setCurrentLanguage($sm->get('CurrentLanguage'));
                    return $service;
                },
                'phoenix-dynamiclistmodule-install' => function ($sm) {
                    $service = new Service\Install();
                    $service->setCurrentUser($sm->get('phoenix-users-current'));
                    $service->setDefaultEntityManager($sm->get('doctrine.entitymanager.orm_default'));
                    $service->setAdminEntityManager($sm->get('doctrine.entitymanager.orm_admin'));
                    $service->setEventManager($sm->get('phoenix-eventmanager'));
                    $service->setConfig($sm->get('MergedConfig'));
                    $service->setServiceManager($sm);
                    $service->setCurrentLanguage($sm->get('CurrentLanguage'));
                    return $service;
                },                
                'phoenix-dynamiclistmodule-fields' => function ($sm) {
                    $service = new Service\Fields();
                    $service->setCurrentUser($sm->get('phoenix-users-current'));
                    $service->setDefaultEntityManager($sm->get('doctrine.entitymanager.orm_default'));
                    $service->setAdminEntityManager($sm->get('doctrine.entitymanager.orm_admin'));
                    $service->setEventManager($sm->get('phoenix-eventmanager'));
                    $service->setConfig($sm->get('MergedConfig'));
                    $service->setServiceManager($sm);
                    $service->setCurrentLanguage($sm->get('CurrentLanguage'));                    
                    return $service;
                },                
                'phoenix-dynamicmanager' => function ($sm) {
                    $service = new Service\DynamicManager();
                    $service->setCurrentUser($sm->get('phoenix-users-current'));
                    $service->setDefaultEntityManager($sm->get('doctrine.entitymanager.orm_default'));
                    $service->setAdminEntityManager($sm->get('doctrine.entitymanager.orm_admin'));
                    $service->setEventManager($sm->get('phoenix-eventmanager'));
                    $service->setConfig($sm->get('MergedConfig'));
                    $service->setServiceManager($sm);
                    $service->setCurrentLanguage($sm->get('CurrentLanguage'));

                    return $service;
                },                
                //This is version of the above factory that doesn't rely upon MergedConfig
                'phoenix-dynamiclistmodule-router' => function ($sm) {
                    $service = new Service\DynamicListModule();
                    //$service->setCurrentUser($sm->get('phoenix-users-current'));
                    $service->setDefaultEntityManager($sm->get('doctrine.entitymanager.orm_default'));
                    $service->setAdminEntityManager($sm->get('doctrine.entitymanager.orm_admin'));
                    $service->setEventManager($sm->get('phoenix-eventmanager'));
                    $service->setConfig(new \Config\Model\MergedConfig());
                    $service->setServiceManager($sm);
                    $service->setCurrentLanguage($sm->get('CurrentLanguage'));                    
                    return $service;
                },                 
            ),    
        );
    }

    public function getViewHelperConfig()
    {
        return array(
            'factories' => array(
                'GetModule' => function($sm) {
                    $serviceManager = $sm->getServiceLocator();
                    $moduleService = $serviceManager->get('phoenix-dynamiclistmodule');
                    $moduleManager = $serviceManager->get('phoenix-dynamicmanager');
                    $helper = new \DynamicListModule\Helper\GetModule($moduleService, $moduleManager);
                    return $helper;
                },                                
                'ModuleInformation' => function($sm) {
                    $serviceManager = $sm->getServiceLocator();
                    $moduleService = $serviceManager->get('phoenix-dynamicmanager');
                    $categoryService = $serviceManager->get('phoenix-listmodule-categories');
                    $helper = new \DynamicListModule\Helper\ModuleInformation($moduleService,$categoryService);
                    return $helper;
                },
                'ItemInformation' => function($sm) {
                    $serviceManager = $sm->getServiceLocator();
                    $moduleService = $serviceManager->get('phoenix-dynamicmanager');
                    $categoryService = $serviceManager->get('phoenix-listmodule-categories');
                    $helper = new \DynamicListModule\Helper\ItemInformation($moduleService,$categoryService,$serviceManager);
                    return $helper;
                },                
            )
        );
    }

    public function onBootstrap(MvcEvent $e)
    {
        //Get the Event Manager for the MVC Application
        $eventManager = $e->getApplication()->getEventManager();

        $eventManager->attach(\Zend\Mvc\MvcEvent::EVENT_DISPATCH, array($this, 'setLayout'), 100);
        $eventManager->attach(\Zend\Mvc\MvcEvent::EVENT_DISPATCH, array($this, 'buildMenu'), 101);
        $eventManager->attach(\Zend\Mvc\MvcEvent::EVENT_ROUTE, array($this, 'setTranslationsExport'), -1000);
        $eventManager->attach(\Zend\Mvc\MvcEvent::EVENT_ROUTE, array($this, 'checkRouteInstall'), -5);

        /**
         * Module Migrations Support
         */
        $this->updateTableStructures($e);
    }

    public function setTranslationsExport(MvcEvent $e)
    {
        $routeMatch = $e->getRouteMatch();

        //No need to run these if we're not doing an export.

        if ($routeMatch->getMatchedRouteName() != 'languages-toolbox' || ($routeMatch->getParam('action') != 'export' && $routeMatch->getParam('action') != 'import')) {
            return;
        }

        $dynamicManager = $e->getApplication()->getServiceManager()->get('phoenix-dynamicmanager');

        $eventManager = $dynamicManager->getEventManager();

        $eventManager->attach(LanguageEvent::EVENT_EXPORT, array($dynamicManager, 'exportTranslations'));
        $eventManager->attach(LanguageEvent::EVENT_IMPORT, array($dynamicManager, 'importTranslations'));

    }

    public function buildMenu(mvcEvent $e)
    {
        $serviceManager = $e->getApplication()->getServiceManager();

        $dynamicListService = $serviceManager->get('phoenix-dynamiclistmodule');

        $modules = $dynamicListService->getItems(true);

        $mergedConfig = $serviceManager->get('MergedConfig');

        $toolList = $mergedConfig->get(array('toolbox', 'toolList'), array());

        foreach ($modules as $valModule) {
            $name = $valModule->getName();
            $toolList[$name] = 'tools/' . str_replace(' ','-',lcfirst($name));
        }

        $mergedConfig->set('toolbox', array('toolList' => $toolList));
    }

    /**
     * setLayout
     *
     * Sets the layout to the toolbox layout if this is a toolbox request
     *
     * @param MvcEvent $e
     * @return void
     */
    public function setLayout(MvcEvent $e)
    {
        $routeMatch = $e->getRouteMatch();

        $controller = $routeMatch->getParam('controller');

        if (strpos($controller, 'Controller\ModuleToolbox') > 0 || strpos($controller, 'Controller\FieldsToolbox') > 0) {
            $e->getViewModel()->setTemplate('toolbox-layout');
        }
    }

    public function checkRouteInstall(MvcEvent $e)
    {
        $routeMatch = $e->getRouteMatch();

        if (empty($routeMatch)) {
            return;
        }

        $controller = $routeMatch->getParam('controller');
        $action = $routeMatch->getParam('action');

        $mergedConfig = $e->getApplication()->getServiceManager()->get('MergedConfig');

        $development = $mergedConfig->get('development');

        if (!$development && $controller == 'DynamicListModule\Controller\Toolbox' && $action == 'install') {
            return $this->triggerError($e, \Zend\Mvc\Application::ERROR_ROUTER_NO_MATCH, MvcEvent::EVENT_DISPATCH_ERROR);
        }
    }
}
