<?php
namespace LinkRedirects;

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
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'phoenix-linkredirects' => function ($sm) {
                    //echo "instantiating service-mapmarkers<br/>";
                    $service = new Service\LinkRedirects();
                    //echo "initd service map markers<br/>";
                    $service->setCurrentUser($sm->get('phoenix-users-current'));
                    $service->setDefaultEntityManager($sm->get('doctrine.entitymanager.orm_default'));
                    $service->setAdminEntityManager($sm->get('doctrine.entitymanager.orm_admin'));
                    $service->setEventManager($sm->get('phoenix-eventmanager'));
                    $service->setConfig($sm->get('MergedConfig'));
                    return $service;
                }
            )
        );
    }
}
