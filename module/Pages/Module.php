<?php

namespace Pages;

use Phoenix\Module\Module as PhoenixModule;
use Zend\Mvc\MvcEvent;
use Phoenix\EventManager\Event as PhoenixEvent;
use Languages\EventManager\Event as LanguageEvent;

class Module extends PhoenixModule {

    protected $moduleNamespace = __NAMESPACE__;
    protected $moduleDirectory = __DIR__;

    public function onBootstrap(MvcEvent $e) {
        $serviceManager = $e->getApplication()->getServiceManager();
        $phoenixEvents = $serviceManager->get('phoenix-eventmanager');
        $eventManager = $e->getApplication()->getEventManager();
       // $eventManager->attach('route', array($this, 'checkAccess'));
        /**
         * Module Migrations Support
         */
        $this->updateTableStructures($e);
        $this->installOrUpdateModule($e);

        $pages = $serviceManager->get('phoenix-pages');
        $phoenixEvents->attach(PhoenixEvent::EVENT_CONFIGMERGE, array($pages, 'onConfigMerge'), -10000);
        $phoenixEvents->attach(\Zend\Mvc\MvcEvent::EVENT_ROUTE, array($pages, 'mergeConfigs'), -3);
        $eventManager->attach(\Zend\Mvc\MvcEvent::EVENT_DISPATCH, array($this, 'setupToolbox'), 3);
        // $eventManager->attach(\Zend\Mvc\MvcEvent::EVENT_ROUTE, array($this, 'setTranslationsExport'), -1000);
        $config = $serviceManager->get('Config');

        // //Added this so the conditional addition of categoris to the layout menu will be triggered.
        // //A. Tate 2013-10-29
        // $layoutModel = $serviceManager->get('listModule-layout');
        // $layoutModel->hasCategories = true;
    }

    public function setupToolbox(MvcEvent $e)
    {
        $routeMatch = $e->getRouteMatch();

        $serviceManager = $e->getApplication()->getServiceManager();

        //Get the Pages Service
        $service = $serviceManager->get("phoenix-pages");

        //Sets current user on service
        $service->setCurrentUser($serviceManager->get('phoenix-users-current')); 

        if ($routeMatch->getParam('controller') !== 'Pages\Controller\Toolbox' && ($routeMatch->getParam('action') == 'addItem' || $routeMatch->getParam('action') == 'editItem')) {
            return true;
        }
        
        //Get the Categories Service
        $categories = $serviceManager->get('listModule-categories');

        //Checks if Current Property Exists and creates an object if not.
        if ($serviceManager->has('currentProperty')) {
            $currentProperty = $serviceManager->get('currentProperty');
        } else {
            $currentProperty = new \PhoenixProperties\Entity\PhoenixProperty();

            $currentProperty->setId(0);
        }

        //Set the currentProperty for categories
        $categories->setProperty($currentProperty);

        $moduleName = 'Pages';

        //Set the module name for categories
        $categories->setModuleName($moduleName);

        //Add the categories service to our service
        $service->setCategories($categories);
    }

    public function attachLanguagesService(MvcEvent $e) {
        $serviceManager = $e->getApplication()->getServiceManager();
        $pages = $serviceManager->get('phoenix-pages');
        $pages->setCurrentLanguage($serviceManager->get('currentLanguage'));
        $pages->setLanguageService($serviceManager->get('phoenix-languages'));
    }

    public function setTranslationsExport(MvcEvent $e) {
        $routeMatch = $e->getRouteMatch();

        //No need to run these if we're not doing an export.

        if ($routeMatch->getMatchedRouteName() != 'languages-toolbox' || ($routeMatch->getParam('action') != 'export' && $routeMatch->getParam('action') != 'import')) {
            return;
        }

        $pages = $e->getApplication()->getServiceManager()->get('phoenix-pages');

        $phoenixEvents = $pages->getEventManager();

        // $phoenixEvents->attach(LanguageEvent::EVENT_EXPORT, array($pages, 'exportTranslations'));
        // $phoenixEvents->attach(LanguageEvent::EVENT_IMPORT, array($pages, 'importTranslations'));
    }

    public function getServiceConfig() {
        return array(
            'factories' => array(
                'phoenix-pages' => function ($sm) {
                    $service = new Service\Pages();
                    $service->setServiceManager($sm);
                    $service->setDefaultEntityManager($sm->get('doctrine.entitymanager.orm_default'));
                    $service->setAdminEntityManager($sm->get('doctrine.entitymanager.orm_admin'));
                    $service->setEventManager($sm->get('phoenix-eventmanager'));
                    //$service->setCurrentUser($svcLoc->get('phoenix-users-current'));
                    //$service->setConfig($sm->get('MergedConfig'));      
                    return $service;
                },
                'phoenix-Pages-service' => function ($sm) {
                    return $sm->get('phoenix-pages');
                },
            )
        );
    }

}
