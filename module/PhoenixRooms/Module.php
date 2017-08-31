<?php

namespace PhoenixRooms;

use Phoenix\Module\Module as PhoenixModule;
use Zend\Mvc\MvcEvent;

use Languages\EventManager\Event as LanguageEvent;

class Module extends PhoenixModule {

    protected $moduleNamespace = __NAMESPACE__;
    protected $moduleDirectory = __DIR__;

    public function onBootstrap(MvcEvent $e) {
        $eventManager = $e->getApplication()->getEventManager();

        //Because of the use statement, you can just refer to it as MvcEvent without the full namespace
        // $eventManager->attach(MvcEvent::EVENT_DISPATCH, array($this, 'addModuleToToolbox'), 101);       

        $eventManager->attach(\Zend\Mvc\MvcEvent::EVENT_ROUTE, array($this, 'setTranslationsExport'), -1000);

        /**
         * Module Migrations Support
         */
        $this->updateTableStructures($e);
        $this->installOrUpdateModule($e);
    }

    public function setTranslationsExport(MvcEvent $e)
    {
        $routeMatch = $e->getRouteMatch();

        //No need to run these if we're not doing an export.

        if ($routeMatch->getMatchedRouteName() != 'languages-toolbox' || ($routeMatch->getParam('action') != 'export' && $routeMatch->getParam('action') != 'import')) {
            return;
        }

        $rooms = $e->getApplication()->getServiceManager()->get('phoenix-rooms');

        $eventManager = $rooms->getEventManager();

        $eventManager->attach(LanguageEvent::EVENT_EXPORT, array($rooms, 'exportTranslations'));
        $eventManager->attach(LanguageEvent::EVENT_IMPORT, array($rooms, 'importTranslations'));

    }    

    public function getServiceConfig() {
        return array(
            'factories' => array(
                'phoenix-rooms' => function ($sm) {
            $service = new Service\Rooms;
            $service->setCurrentUser($sm->get('phoenix-users-current'));
            $service->setDefaultEntityManager($sm->get('doctrine.entitymanager.orm_default'));
            $service->setAdminEntityManager($sm->get('doctrine.entitymanager.orm_admin'));
            $service->setEventManager($sm->get('phoenix-eventmanager'));
            $service->setCurrentLanguage($sm->get('currentLanguage'));
            $service->setLanguageService($sm->get('phoenix-languages'));
            $service->setConfig($sm->get('MergedConfig'));
            $service->setServiceManager($sm);
            return $service;
        },
            )
        );
    }

    public function addModuleToToolbox($e) {
        $serviceManager = $e->getApplication()->getServiceManager();
        $mergedConfig = $serviceManager->get('MergedConfig');

        $toolList = $mergedConfig->get(array('templateVars', 'toolList'));

        if (is_array($toolList)) {
            $toolList[] = array('key' => 'phoenixRooms');
            $mergedConfig->set(array('templateVars', 'toolList'), $toolList);
        }
    }

}
