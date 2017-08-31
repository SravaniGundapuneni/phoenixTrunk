<?php
namespace Designer;

use Phoenix\Module\Module as PhoenixModule;

class Module extends PhoenixModule
{
    protected $moduleNamespace = __NAMESPACE__;
    protected $moduleDirectory = __DIR__;

    public function getServiceConfig()
    {
        return array('factories' => array(
            'designer' => function($sm) {
                $service = new Service\Content;
                $service->setDefaultEntityManager($sm->get('doctrine.entitymanager.orm_default'));
                $service->setAdminEntityManager($sm->get('doctrine.entitymanager.orm_admin'));
                $service->setEventManager($sm->get('phoenix-eventmanager'));
                $service->setConfig($sm->get('MergedConfig'));
                return $service;
            }
        ));
    }
}
