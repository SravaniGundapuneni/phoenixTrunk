<?php
namespace SiteMaps;

use Phoenix\Module\Module as PhoenixModule;
use Zend\Mvc\MvcEvent;

class Module extends PhoenixModule
{
    protected $moduleNamespace = __NAMESPACE__;
    protected $moduleDirectory = __DIR__;
    
    public function onBootstrap(MvcEvent $e)
    {
        /*/
         * Module Migrations Support
        /*/
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
                'phoenix-sitemapsection' => function($sm) {
                    $service = new Service\SiteMapSection();
                    $service->setDefaultEntityManager($sm->get('doctrine.entitymanager.orm_default'));
                    $service->setAdminEntityManager($sm->get('doctrine.entitymanager.orm_admin'));
                    $service->setEventManager($sm->get('phoenix-eventmanager'));                    
                    $service->setServiceManager($sm);
                    return $service;
                },
				 'phoenix-sitemappages' => function($sm) {
                    $service = new Service\SiteMapPages();
                    $service->setDefaultEntityManager($sm->get('doctrine.entitymanager.orm_default'));
                    $service->setAdminEntityManager($sm->get('doctrine.entitymanager.orm_admin'));
                    $service->setEventManager($sm->get('phoenix-eventmanager'));                    
                    $service->setServiceManager($sm);
                    return $service;
                },
				  'phoenix-sitemaps' => function($sm) {
                    $service = new Service\SiteMaps();
                    $service->setDefaultEntityManager($sm->get('doctrine.entitymanager.orm_default'));
                    $service->setAdminEntityManager($sm->get('doctrine.entitymanager.orm_admin'));
                    $service->setEventManager($sm->get('phoenix-eventmanager'));
                    $service->setCurrentUser($sm->get('phoenix-users-current'));
                    $service->setCurrentLanguage($sm->get('currentLanguage'));
                    $service->setLanguageService($sm->get('phoenix-languages'));                                 
                    $service->setServiceManager($sm);
                    return $service;
                },  
            )
        );
    }
    
    public function getViewHelperConfig()
    {
        return array(
            'factories' => array(
                'GetSiteMap' => function($sm) {
                    $serviceManager = $sm->getServiceLocator();
                    $moduleService = $serviceManager->get('phoenix-sitemaps');
                    $helper = new \SiteMaps\Helper\GetSiteMap($moduleService);
                    return $helper;
                }
            )
        );
    }
}