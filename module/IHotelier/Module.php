<?php
namespace IHotelier;

use Phoenix\Module\Module as PhoenixModule;
use Zend\Mvc\MvcEvent;
use Languages\EventManager\Event as LanguageEvent;

class Module extends PhoenixModule
{
    protected $moduleNamespace = __NAMESPACE__;
    protected $moduleDirectory = __DIR__;

    public function onBootstrap(MvcEvent $e)
    {
        $this->updateTableStructures($e);
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'phoenix-ihotelier' => function ($sm) {
                    $service = new Service\IHotelier();
//                    $service->setCurrentUser($sm->get('phoenix-users-current'));
                    $service->setConfig($sm->get('MergedConfig'));
//                    $service->setCurrentLanguage($sm->get('currentLanguage'));
//                    $service->setLanguageService($sm->get('phoenix-languages'));
                    $service->setServiceManager($sm);
                    return $service;
                },
                'phoenix-ihotelier-settings' => function($sm) {
                    $service = new Service\Settings();
                    $service->setDefaultEntityManager($sm->get('doctrine.entitymanager.orm_default'));
                    $service->setAdminEntityManager($sm->get('doctrine.entityManager.orm_admin'));
                    $service->setServiceManager($sm);
                    if ($sm->has('mergedConfig')) {
                        $service->setConfig($sm->get('mergedConfig'));
                    }

                    return $service;
                }, 
                'phoenix-ihotelier-dailyrate' => function($sm) {
                    $service = new Service\DailyRate();
//                    $service->setConfig($sm->get('mergedConfig'));
                    $service->setServiceManager($sm);
                    return $service;
                },
            )
        );
    }
}
