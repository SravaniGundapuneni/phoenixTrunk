<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace phpsample;
use Phoenix\Module\Module as PhoenixModule;
use Zend\Mvc\MvcEvent;

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

        $this->installOrUpdateModule($e);

        $eventManager = $e->getApplication()->getEventManager();
        $eventManager->attach( \Zend\Mvc\MvcEvent::EVENT_DISPATCH, array($this, 'testUnifiedItems'), -1000);

    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'phoenix-phpsample' => function ($sm) {
                    $service = new Service\PhpSample();
                    $service->setCurrentUser($sm->get('phoenix-users-current'));
                    $service->setDefaultEntityManager($sm->get('doctrine.entitymanager.orm_default'));
                    $service->setAdminEntityManager($sm->get('doctrine.entitymanager.orm_admin'));
                    $service->setEventManager($sm->get('phoenix-eventmanager'));
                    $service->setConfig($sm->get('MergedConfig'));
                    $service->setCurrentLanguage($sm->get('currentLanguage'));
                    $service->setLanguageService($sm->get('phoenix-languages'));
                    $service->setServiceManager($sm);
                    return $service;
                },
            )
        );
    }
    
     public function testUnifiedItems($e)
    {
        $serviceManager = $e->getApplication()->getServiceManager();

        $phpSample = $serviceManager->get('phoenix-phpsample');
    }

}


?>
