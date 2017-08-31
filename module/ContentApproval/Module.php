<?php
namespace ContentApproval;

use Phoenix\Module\Module as PhoenixModule;

class Module extends PhoenixModule
{
    protected $moduleNamespace = __NAMESPACE__;
    protected $moduleDirectory = __DIR__;

    public function onBootstrap($e)
    {
        /**
         * Module Migrations Support
         */
        $this->updateTableStructures($e);     
       $eventManager = $e->getApplication()->getEventManager();
        $eventManager->attach(\Zend\Mvc\MvcEvent::EVENT_DISPATCH, array($this, 'setLayout'), 100);
    }
    
    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'phoenix-contentapproval' => function ($sm) {
                    $service = new Service\ContentApproval();
                    $service->sm = $sm;
                    $service->setDefaultEntityManager($sm->get('doctrine.entitymanager.orm_default'));
                    $service->setAdminEntityManager($sm->get('doctrine.entitymanager.orm_admin'));
                    $service->setEventManager($sm->get('phoenix-eventmanager'));
                    $service->setConfig($sm->get('MergedConfig'));
                    $service->setCurrentLanguage($sm->get('currentLanguage'));
                    $service->setLanguageService($sm->get('phoenix-languages'));
                    $service->setCurrentUser($sm->get('phoenix-users-current'));
                    $service->setServiceManager($sm);
                    return $service;
                },
                        'phoenix-approvals' => function ($sm) {
                    $service = new Service\Approval();
                    $service->setDefaultEntityManager($sm->get('doctrine.entitymanager.orm_default'));
                    $service->setAdminEntityManager($sm->get('doctrine.entitymanager.orm_admin'));
                    $service->setEventManager($sm->get('phoenix-eventmanager'));
                    $service->setCurrentLanguage($sm->get('currentLanguage'));
                    $service->setLanguageService($sm->get('phoenix-languages'));
                    $service->setConfig($sm->get('MergedConfig'));
                    $service->setServiceManager($sm);
                    return $service;
                },
                        'phoenix-workflow' => function ($sm) {
                    $service = new Service\Workflow();
                    $service->setDefaultEntityManager($sm->get('doctrine.entitymanager.orm_default'));
                    $service->setAdminEntityManager($sm->get('doctrine.entitymanager.orm_admin'));
                    $service->setEventManager($sm->get('phoenix-eventmanager'));
                    $service->setCurrentLanguage($sm->get('currentLanguage'));
                    $service->setLanguageService($sm->get('phoenix-languages'));
                    $service->setConfig($sm->get('MergedConfig'));
                    $service->setServiceManager($sm);
                    return $service;
                },
            )
         );
    }
        public function getViewHelperConfig()
    {
        return array(
            'factories' => array(
                'GetCasInfo' => function($sm) {
                    $serviceManager = $sm->getServiceLocator();
                    $helper = new Helper\GetCasInfo($serviceManager);
                    return $helper;
                },            
            )
        );
    }
    public function setLayout( $e)
    {
        $routeMatch = $e->getRouteMatch();

        $controller = $routeMatch->getParam('controller');
        
        if (strpos($controller, 'Controller\Toolbox') > 0) {        
            $e->getViewModel()->setTemplate('toolbox-layout');
        }
    }
}
