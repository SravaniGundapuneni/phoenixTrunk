<?php
namespace GooglePlaces;

class Module extends \Phoenix\Module\Module
{
    protected $moduleNamespace = __NAMESPACE__;
    protected $moduleDirectory = __DIR__;
/*
    public function getServiceConfig()
    {
        return array('factories' => array(
            'GoogleMapContent' => function($sm) {
                $service = new Service\GoogleMapContent;
                $service->setDefaultEntityManager($sm->get('doctrine.entitymanager.orm_default'));
                $service->setAdminEntityManager($sm->get('doctrine.entitymanager.orm_admin'));
                $service->setEventManager($sm->get('phoenix-eventmanager'));
                $service->setConfig($sm->get('MergedConfig'));
                return $service;
            }
        ));
    }
 * 
 */
}
