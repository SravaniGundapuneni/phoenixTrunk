<?php
namespace PhoenixSocialToolbar;

use Phoenix\Module\Module as PhoenixModule;
use \Zend\Mvc\MvcEvent;

class Module extends PhoenixModule
{
    protected $moduleNamespace = __NAMESPACE__;
    protected $moduleDirectory = __DIR__;

    public function onBootstrap(MvcEvent $e)
    {
        $eventManager = $e->getApplication()->getEventManager();
        $serviceManager = $e->getApplication()->getServiceManager();

        $serviceManager->get('viewhelpermanager')->setFactory("GetSocialToolbarElement",
           function ($viewHelperManager) use ($serviceManager) {
             $socialToolbar = $serviceManager->get('phoenix-socialtoolbar');
             return new \PhoenixSocialToolbar\Helper\GetSocialToolbarElement($socialToolbar);
           }

        );

        /**
         * Module Migrations Support
         */
        $this->updateTableStructures($e);  
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'phoenix-socialtoolbar' => function($sm) {
                    $service = new Service\PhoenixSocialToolbars;
                    $service->setDefaultEntityManager($sm->get('doctrine.entitymanager.orm_default'));
                    $service->setAdminEntityManager($sm->get('doctrine.entityManager.orm_admin'));
                    $service->setEventManager($sm->get('phoenix-eventmanager'));
                    //$service->setConfig($sm->get('MergedConfig'));
                    return $service;
                },
         
               
                )
            );

    }

    public function socialToolbarSwitch($e)
    {
        $routeMatch = $e->getRouteMatch();
        $serviceManager = $e->getApplication()->getServiceManager();
        $mergedConfig = $serviceManager->get('MergedConfig');

         if (strpos($routeMatch->getParam('controller'), 'Controller\Legacy') === false) {
            $socialToolbar = $serviceManager->get('phoenix-socialtoolbar');
            $socialToolbar->setConfig($mergedConfig);
            $socialToolbar->init($routeMatch->getParam('langCode'));
        }


    }
}
