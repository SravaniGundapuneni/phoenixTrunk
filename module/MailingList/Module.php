<?php
namespace MailingList;


use Phoenix\Module\Module as PhoenixModule;
use Zend\Mvc\MvcEvent;
use Phoenix\EventManager\Event as PhoenixEvent;

class Module extends PhoenixModule
{
    protected $moduleNamespace = __NAMESPACE__;
    protected $moduleDirectory = __DIR__;
	
	 public function onBootstrap(MvcEvent $e)
    {
        $this->updateTableStructures($e);
        $this->installOrUpdateModule($e);
		$serviceManager = $e->getApplication()->getServiceManager();
        $phoenixEvents = $serviceManager->get('phoenix-eventmanager');
        $eventManager = $e->getApplication()->getEventManager();
		
		       
    }
	
	
    public function getServiceConfig()
    {
        return array(
			 'factories' => array(
                 
                'phoenix-mailinglist' => function($sm) {
                    $service = new Service\MailingLists();
                    $service->setDefaultEntityManager($sm->get('doctrine.entitymanager.orm_default'));
                    $service->setAdminEntityManager($sm->get('doctrine.entitymanager.orm_admin'));
                    $service->setEventManager($sm->get('phoenix-eventmanager'));                    
                    $service->setServiceManager($sm);
                    return $service;
                },
				                   
            )
		
		);
    }
}
