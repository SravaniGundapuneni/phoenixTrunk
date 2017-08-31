<?php
namespace YouFirst;

use Phoenix\Module\Module as PhoenixModule;
use Zend\Mvc\MvcEvent;

class Module extends PhoenixModule
{
    protected $moduleNamespace = __NAMESPACE__;
    protected $moduleDirectory = __DIR__;
	
	public function onBootstrap (MvcEvent $e)
	{
	  $eventManager = $e->getApplication()->getEventManager();	  
	  //$eventManager->attach(MvcEvent::EVENT_DISPATCH, array($this, 'addModuleToToolbox'), 101);
	}

    public function getServiceConfig()
    {
        return array(
		'factories' => array(
		 'youfirst' => function($sm) {
		   $service = new Service\YouFirst();
		   $service->setEventManager($sm->get('youfirst-eventmanager'));
		   $service->setConfig($sm->get('MergedConfig'));
		   return $service;
		 },
		 
		 )
		);
    }
}
