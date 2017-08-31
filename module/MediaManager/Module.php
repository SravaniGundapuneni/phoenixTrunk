<?php
namespace MediaManager;

use Phoenix\Module\Module as PhoenixModule;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\ModuleManager\ModuleManager as ModuleManager;

class Module extends PhoenixModule {

    protected $moduleNamespace = __NAMESPACE__;
    protected $moduleDirectory = __DIR__;

    public function onBootstrap(MvcEvent $e) {
        $serviceManager = $e->getApplication()->getServiceManager();
        $eventManager = $e->getApplication()->getEventManager();

        $eventManager->attach(\Zend\Mvc\MvcEvent::EVENT_DISPATCH, array($this, 'setUserBasePaths'), 100);

        $serviceManager->get('viewhelpermanager')->setFactory("GetImageElement", function ($serviceManager) use ($e) {
            return new \MediaManager\Helper\GetImageElement($serviceManager);
        });

        $sharedManager = $eventManager->getSharedManager();
        $sharedManager->attach('Zend\Mvc\Controller\AbstractActionController', 'dispatch', function($e) use ($serviceManager) {
            $controller = $e->getTarget();
            $controller->getEventManager()->attachAggregate($serviceManager->get('MediaManagerListener'));
        }, 2);
        
        /**
         * Module Migrations Support
         */
        $this->updateTableStructures($e);
    }

    public function getServiceConfig() {
        return array(
            'factories' => array(
                'phoenix-mediamanager' => function($sm) {            
                    $service = new Service\MediaManager;
                    $service->setDefaultEntityManager($sm->get('doctrine.entitymanager.orm_default'));
                    $service->setCurrentUser($sm->get('phoenix-users-current'));
                    $service->setAdminEntityManager($sm->get('doctrine.entityManager.orm_admin'));
                    $service->setCurrentLanguage($sm->get('currentLanguage'));  
                    $service->setLanguageService($sm->get('phoenix-languages'));
                    $service->setServiceManager($sm);
                    return $service;
                },
                'phoenix-imagemanipulation' => function($sm) {
                    $service = new Service\ImageManipulation;
                    $service->setDefaultEntityManager($sm->get('doctrine.entitymanager.orm_default'));
                    $service->setAdminEntityManager($sm->get('doctrine.entityManager.orm_admin'));

                    if ($sm->has('mergedConfig')) {
                        $service->setConfig($sm->get('mergedConfig'));
                        $imageLibrary = Service\ImageManipulation::LIBRARY_IMAGE_MAGICK;
                        $service->setImageLibrary($service->getConfig()->get('imageLib', $imageLibrary));
                    }

                    return $service;
                },
                'phoenix-attachedmediafiles' => function($sm) {
                    $service = new Service\AttachedMediaFiles;
                    $service->setDefaultEntityManager($sm->get('doctrine.entitymanager.orm_default'));
                    $service->setAdminEntityManager($sm->get('doctrine.entityManager.orm_admin'));
                    $service->setCurrentUser($sm->get('phoenix-users-current'));
                    $service->setMediaManager($sm->get('phoenix-mediamanager'));
                    $service->setCurrentLanguage($sm->get('currentLanguage'));  
                    $service->setLanguageService($sm->get('phoenix-languages'));
                    $service->setServiceManager($sm);
                    if ($sm->has('mergedConfig')) {
                        $service->setConfig($sm->get('mergedConfig'));
                    }
               
                    return $service;
                },
                'phoenix-mediamanagerfiles' => function($sm) {
                    $service = new Service\MediaManagerFiles;
                    $service->setDefaultEntityManager($sm->get('doctrine.entitymanager.orm_default'));
                    $service->setAdminEntityManager($sm->get('doctrine.entityManager.orm_admin'));
                    $service->setCurrentUser($sm->get('phoenix-users-current'));
                    $service->setMediaManager($sm->get('phoenix-mediamanager'));
                    $service->setCurrentLanguage($sm->get('currentLanguage'));  
                    $service->setLanguageService($sm->get('phoenix-languages'));
                    $service->setServiceManager($sm);
                    if ($sm->has('mergedConfig')) {
                        $service->setConfig($sm->get('mergedConfig'));
                    }
                   
                    return $service;
                },
                'phoenix-mediamanager-image' => function($sm) {
                    $service = new Service\MediaManagerImages;
                    $service->setDefaultEntityManager($sm->get('doctrine.entitymanager.orm_default'));
                    $service->setAdminEntityManager($sm->get('doctrine.entityManager.orm_admin'));
                    $service->setCurrentLanguage($sm->get('currentLanguage'));  
                    $service->setLanguageService($sm->get('phoenix-languages'));
                    $service->setServiceManager($sm);
                    if ($sm->has('mergedConfig')) {
                        $service->setConfig($sm->get('mergedConfig'));
                    }
                    
                    return $service;
                },
                'phoenix-mediamanager-opengraph' => function($sm) {
                    $service = new Service\MediaManagerOpenGraph;
                    $service->setDefaultEntityManager($sm->get('doctrine.entitymanager.orm_default'));
                    $service->setAdminEntityManager($sm->get('doctrine.entityManager.orm_admin'));
                    $service->setServiceManager($sm);
                    if ($sm->has('mergedConfig')) {
                        $service->setConfig($sm->get('mergedConfig'));
                    }

                    return $service;
                },
                'phoenix-mediamanager-schemadotorg' => function($sm) {
                    $service = new Service\MediaManagerSchemaDotOrg;
                    $service->setDefaultEntityManager($sm->get('doctrine.entitymanager.orm_default'));
                    $service->setAdminEntityManager($sm->get('doctrine.entityManager.orm_admin'));
                    $service->setServiceManager($sm);
                    if ($sm->has('mergedConfig')) {
                        $service->setConfig($sm->get('mergedConfig'));
                    }

                    return $service;
                },
                'phoenix-mediamanager-permissions' => function($sm) {
                    $service = new Service\MediaManagerPermissions;
                    $service->setServiceManager($sm);
                    if ($sm->has('mergedConfig')) {
                        $service->setConfig($sm->get('mergedConfig'));
                    }
                    $service->setMediaManager($sm->get('phoenix-mediamanager'));
                    $service->setCurrentUser($sm->get('phoenix-users-current'));
                    return $service;
                },
                'phoenix-mediamanager-responsive-image' => function($sm) {
                    $service = new Service\MediaManagerResponsiveImage;
                    $service->setServiceManager($sm);
                    if ($sm->has('mergedConfig')) {
                        $service->setConfig($sm->get('mergedConfig'));
                    }
                    $service->setMediaManager($sm->get('phoenix-mediamanager'));
                    $service->setCurrentUser($sm->get('phoenix-users-current'));
                    return $service;
                },
                'phoenix-filters-image' => function ($sm) {
                    $viewManager = $sm->get('view-manager');
                    $mergedConfig = $sm->get('MergedConfig');
                    $imageSwitches = $sm->get('phoenix-mediamanager-image');
                    $viewRenderer = $viewManager->getRenderer();

                    $service = new \MediaManager\Filter\Tag\Image(
                        $mergedConfig,
                        $imageSwitches,
                        $viewRenderer
                    );

                    return $service;
                },
                'phoenix-mediamanager-crop' => function($sm) {
                    $service = new Service\MediaManagerCrop;
                    $service->setServiceManager($sm);
                    if ($sm->has('mergedConfig')) {
                        $service->setConfig($sm->get('mergedConfig'));
                    }
                    return $service;
                },
            )
        );
    }

    public function setUserBasePaths($e) {
        $serviceManager = $e->getApplication()->getServiceManager();

        $mergedConfig = $serviceManager->get('mergedConfig');
        $mediaManagerService = $serviceManager->get('phoenix-mediamanager');
        $corporateProperty = $serviceManager->get('corporateProperty');
        $currentProperty = $serviceManager->get('currentProperty');
        $currentUser = $serviceManager->get('phoenix-users-current');

        $mediaRoot = $mergedConfig->get('mediaRoot', 'd');

        $mediaManagerService->setConfig($mergedConfig);

        $mediaManagerService->setUserBasePaths(
                $currentProperty, $corporateProperty, $currentUser, $mediaRoot
        );
    }

    public function getViewHelperConfig()
    {
        return array(
            'factories' => array(
                'isReadOnly' => function($pluginManager) {
                    $viewHelper = new View\Helper\IsReadOnly();
                    $viewHelper->setServiceLocator($pluginManager->getServiceLocator());
                    return $viewHelper;
                }
            )
        ); 
    }

    // public function initMediaManager($e)
    // {
    //  $routeMatch = $e->getRouteMatch();
    //  $serviceManager = $e->getApplication()-getServiceManager();
    //  $mergedConfig = $serviceManager->get('MergdedConfig');
    // }
}
