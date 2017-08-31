<?php
namespace SeoFileUpload;

use Phoenix\Module\Module as PhoenixModule;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\ModuleManager\ModuleManager as ModuleManager;

class Module extends PhoenixModule
{
    protected $moduleNamespace = __NAMESPACE__;
    protected $moduleDirectory = __DIR__;

    public function onBootstrap(MvcEvent $e)
    {
        $serviceManager = $e->getApplication()->getServiceManager();
        
        //Get the Event Manager for the MVC Application
        $eventManager = $e->getApplication()->getEventManager();

        $eventManager->attach(\Zend\Mvc\MvcEvent::EVENT_DISPATCH, array($this, 'setUserBasePaths'), 100);

        $serviceManager->get('viewhelpermanager')->setFactory("GetMediaImageElement",
            function ($serviceManager) use ($e) {
                return new \SeoFileUpload\Helper\GetMediaImageElement($serviceManager);
            });
        /**
         * Module Migrations Support
         */
        $this->updateTableStructures($e);
    }

    public function getServiceConfig()
    {
        return array(
        	'factories' => array(
                'phoenix-seofileupload' => function($sm) {
                	$service = new Service\SeoFileUpload;
                	$service->setDefaultEntityManager($sm->get('doctrine.entitymanager.orm_default'));
                	$service->setAdminEntityManager($sm->get('doctrine.entityManager.orm_admin'));
                    return $service;
                },

        	)
        );
    }

    public function setUserBasePaths($e)
    {
        $serviceManager = $e->getApplication()->getServiceManager();

        $mergedConfig         = $serviceManager->get('mergedConfig');
        $seoFileUploadService  = $serviceManager->get('phoenix-seofileupload');
        $corporateProperty    = $serviceManager->get('corporateProperty');
        $currentProperty      = $serviceManager->get('currentProperty');
        $currentUser          = $serviceManager->get('phoenix-users-current');

        $mediaRoot = $mergedConfig->get('mediaRoot');

        $seoFileUploadService->setConfig($mergedConfig);

        $seoFileUploadService->setUserBasePaths(
            $currentProperty,
            $corporateProperty,
            $currentUser,
            $mediaRoot
        );
    }
}
