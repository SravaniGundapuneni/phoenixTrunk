<?php

 /**
 * @package     AssetsManager
 * @copyright   Copyright (c) 2014 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 1.14
 * @since       File available since release 1.14
 * @author      Saurabh Shirgaonkar <sshirgaonkar@travelclick.com>
 * @filename    Module.php
 */

namespace AssetsManager;
use Phoenix\Module\Module as PhoenixModule;
  
    class Module extends PhoenixModule
    {
        protected $moduleNamespace=__NAMESPACE__;
	    protected $moduleDirectory=__DIR__;
   
	    public function getAutoloaderConfig()
	    {
	  	    return array(
		        'Zend\Loader\StandardAutoloader' => array(
				'namespaces'=>array( __NAMESPACE__=>__DIR__.'/src/'.__NAMESPACE__,
			    ),
			  ),
		    );						
	    }

	    public function getServiceConfig() {
				
			return array(
				'factories' => array(
				'assets' => function ($sm) {
				$service = new Service\AssetsManager();
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