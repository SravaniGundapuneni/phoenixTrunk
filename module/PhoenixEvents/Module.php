<?php

namespace PhoenixEvents;

use Phoenix\Module\Module as PhoenixModule;
use Zend\Mvc\MvcEvent;

use Languages\EventManager\Event as LanguageEvent;

class Module extends PhoenixModule {

    protected $moduleNamespace = __NAMESPACE__;
    protected $moduleDirectory = __DIR__;

    public function onBootstrap(MvcEvent $e) {
        $eventManager = $e->getApplication()->getEventManager();
        $serviceManager = $e->getApplication()->getServiceManager();

        $eventManager->attach(\Zend\Mvc\MvcEvent::EVENT_ROUTE, array($this, 'setTranslationsExport'), -1000);

        $serviceManager->get('viewhelpermanager')->setFactory("GetEventElement", function ($viewHelperManager) use ($serviceManager) {
            $phoenixEvents = $serviceManager->get('phoenix-events');
            return new \PhoenixEvents\Helper\GetEventElement($phoenixEvents);
        }
        );

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

        $events = $e->getApplication()->getServiceManager()->get('phoenix-events');

        $eventManager = $events->getEventManager();

        $eventManager->attach(LanguageEvent::EVENT_EXPORT, array($events, 'exportTranslations'));
        $eventManager->attach(LanguageEvent::EVENT_IMPORT, array($events, 'importTranslations'));

    }

    public function getServiceConfig() {
        return array(
            'factories' => array(
                'phoenix-events' => function ($sm) {
            $service = new Service\Events();
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

    public function phoenixEventsSwtich($e) {
        $routeMatch = $e->getRouteMatch();
        $serviceManager = $e->getApplication - getServiceManager();
        $mergedConfig = $serviceManager->get('MergedConfig');

        if (strpos($routeMatch->getParam('controller'), 'Controller\Legacy') === false) {
            $phoenixEvents = $serviceManager->get('phoenix-events');
            $phoenixEvents->setConfig($mergedConfig);
            $phoenixEvents->init($routeMatch->getParam('langCode'));
        }
    }

}
