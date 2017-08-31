<?php
/**
 * Module File 
 *
 * This file contains the information about module configuration and 
 * location of the classes belonging to the module to be auto loaded.
 *
 * @modulename  ContentFilter
 * @copyright   Copyright (c) 2014 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.5.5
 * @since       File available since release 13.5.5
 * @author      S Shirgaonkar <sshirgaonkar@travelclick.com>
 * @filesource
 */ 
namespace ContentFilter;

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
                 
                'phoenix-contentfilter' => function($sm) {
                    $service = new Service\ContentFilter();
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
