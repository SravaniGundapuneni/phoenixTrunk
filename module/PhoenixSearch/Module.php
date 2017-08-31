<?php
namespace PhoenixSearch;

use Phoenix\Module\Module as PhoenixModule;
use \Zend\Mvc\MvcEvent;

class Module extends PhoenixModule
{
    protected $moduleNamespace = __NAMESPACE__;
    protected $moduleDirectory = __DIR__;

    public function onBootstrap(MvcEvent $e)
    {
    }

    public function getViewHelperConfig()
    {
        return array(
            'factories' => array(
                'SiteSearch' => function($sm) {
                    $serviceManager = $sm->getServiceLocator();
                    $polytextService = $serviceManager->get('phoenix-polytext');
                    $helper = new Helper\SiteSearch($polytextService);
                    return $helper;
                },
            )
        );
    }
}