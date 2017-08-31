<?php
namespace Calendar;


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
		$viewModel = new \Zend\View\Model\ViewModel();
       // $serviceManager->setService('event-layout', $viewModel);
		
		       
    }
	
	
    public function getServiceConfig()
    {
        return array(
			 'factories' => array(
                 
                'phoenix-calendar' => function($sm) {
                    $service = new Service\Calendar();
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
				'phoenix-calendarevent' => function($sm) {
                    $service = new Service\CalendarEvent();
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
				'phoenix-eventcategory' => function($sm) {
                    $service = new Service\EventCategory();
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
}
