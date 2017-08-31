<?php
namespace PhoenixReview;


use Phoenix\Module\Module as PhoenixModule;
use Zend\Mvc\MvcEvent;
use Phoenix\EventManager\Event as PhoenixEvent;

class Module extends PhoenixModule
{
    protected $moduleNamespace = __NAMESPACE__;
    protected $moduleDirectory = __DIR__;
	
	 public function onBootstrap(MvcEvent $e)
    {
        $serviceManager = $e->getApplication()->getServiceManager();
        $eventManager = $e->getApplication()->getEventManager();
        $phoenixEvents = $serviceManager->get('phoenix-eventmanager');
        //$review = $serviceManager->get('phoenix-phoenixreview');

        
 
        $viewModel = new \Zend\View\Model\ViewModel();
        $viewModel->setTemplate('PhoenixReview/layout/module');
        $viewModel->setVariable('title',"Reviews");

        $serviceManager->setService('phoenixReview-layout', $viewModel);
		
		
        /**
         * Module Migrations Support
         */
        $this->updateTableStructures($e);          
    }
	
	
    public function getServiceConfig()
    {
        return array(
			 'factories' => array(
                 
                'phoenix-review' => function($sm) {
                    $service = new Service\Reviews();
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
