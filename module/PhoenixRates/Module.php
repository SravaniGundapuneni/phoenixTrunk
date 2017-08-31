<?php

namespace PhoenixRates;

use Phoenix\Module\Module as PhoenixModule;
use Zend\Mvc\MvcEvent;

use Languages\EventManager\Event as LanguageEvent;

class Module extends PhoenixModule {

    protected $moduleNamespace = __NAMESPACE__;
    protected $moduleDirectory = __DIR__;

    public function onBootstrap(MvcEvent $e) {
        $eventManager = $e->getApplication()->getEventManager();
        $serviceManager = $e->getApplication()->getServiceManager();
        //The priority is set to 101, so it will run before Toolbox\Phoenix::populateLayout()
        // $eventManager->attach(\Zend\Mvc\MvcEvent::EVENT_DISPATCH, array($this, 'addModuleToToolbox'), 101);

        $eventManager->attach(\Zend\Mvc\MvcEvent::EVENT_ROUTE, array($this, 'addPagesListener'), -1000);
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

        $rates = $e->getApplication()->getServiceManager()->get('phoenix-rates');

        $eventManager = $rates->getEventManager();

        $eventManager->attach(LanguageEvent::EVENT_EXPORT, array($rates, 'exportTranslations'));
        $eventManager->attach(LanguageEvent::EVENT_IMPORT, array($rates, 'importTranslations'));

    }          

    public function getServiceConfig() {
        return array(
            'factories' => array(
                'phoenix-rates' => function ($sm) {
            $service = new Service\Rates();
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
                'phoenix-membership' => function ($sm) {
            $service = new Service\Membership;
            $service->setDefaultEntityManager($sm->get('doctrine.entitymanager.orm_default'));
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
            $toolList[] = array('key' => 'phoenixRates');
            $mergedConfig->set(array('templateVars', 'toolList'), $toolList);
        }
    }

    public function addPagesListener($e) {
        $serviceManager = $e->getApplication()->getServiceManager();
        $phoenixEventManager = $serviceManager->get('phoenix-eventmanager');
        $ratesService = $serviceManager->get('phoenix-rates');
        $phoenixEventManager->attach(\Pages\EventManager\Event::EVENT_PAGE_EDITITEM, array($ratesService, 'onPageEditItem'));
        $phoenixEventManager->attach(\Pages\EventManager\Event::EVENT_PAGE_SAVE, array($ratesService, 'onPageSave'));
        $phoenixEventManager->attach(\Pages\EventManager\Event::EVENT_PAGE_DISPLAY, array($ratesService, 'onPageDisplay'));
        $phoenixEventManager->attach(\Pages\EventManager\Event::EVENT_PAGE_GETARRAYCOPY, array($ratesService, 'onPageGetArrayCopy'));
        $phoenixEventManager->attach(\Pages\EventManager\Event::EVENT_PAGE_GETINPUTFILTER, array($ratesService, 'onPageGetInputFilter'));
    }

}
