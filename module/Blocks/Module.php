<?php

namespace Blocks;
use \Zend\Mvc\MvcEvent;

class Module extends \Phoenix\Module\Module
{
    protected $moduleNamespace = __NAMESPACE__;
    protected $moduleDirectory = __DIR__;

    public function onBootstrap(MvcEvent $e)
    {
        /**
         * Module Migrations Support
         */
        $this->updateTableStructures($e);
    }

    public function getServiceConfig()
    {
        return array('factories' => array(
            'phoenix-blocks' => function($sm) {
                $service = new Service\Blocks();
//                $service->setCurrentUser($sm->get('phoenix-users-current'));
                $service->setDefaultEntityManager($sm->get('doctrine.entitymanager.orm_default'));
                $service->setAdminEntityManager($sm->get('doctrine.entitymanager.orm_admin'));
                $service->setEventManager($sm->get('phoenix-eventmanager'));
                $service->setConfig($sm->get('MergedConfig'));
                return $service;
            }
        ));
    }
}
