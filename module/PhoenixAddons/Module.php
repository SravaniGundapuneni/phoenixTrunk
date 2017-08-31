<?php

namespace PhoenixAddons;

use Phoenix\Module\Module as PhoenixModule;
use Zend\Mvc\MvcEvent;

use Languages\EventManager\Event as LanguageEvent;

class Module extends PhoenixModule {

    protected $moduleNamespace = __NAMESPACE__;
    protected $moduleDirectory = __DIR__;

    public function onBootstrap(MvcEvent $e) {
            $this->installOrUpdateModule($e);
        $eventManager = $e->getApplication()->getEventManager();
        //Because of the use statement, you can just refer to it as MvcEvent without the full namespace
        // $eventManager->attach(MvcEvent::EVENT_DISPATCH, array($this, 'addModuleToToolbox'), 101);

        $eventManager->attach(\Zend\Mvc\MvcEvent::EVENT_ROUTE, array($this, 'setTranslationsExport'), -1000);

        /**
         * Module Migrations Support
         */
        $this->updateTableStructures($e);
    
    }

    public function setTranslationsExport(MvcEvent $e)
    {
        $routeMatch = $e->getRouteMatch();

        //No need to run these if we're not doing an export.

        if ($routeMatch->getMatchedRouteName() != 'languages-toolbox' || ($routeMatch->getParam('action') != 'export' && $routeMatch->getParam('action') != 'import')) {
            return;
        }

        $addOns = $e->getApplication()->getServiceManager()->get('phoenix-addons');

        $eventManager = $addOns->getEventManager();

        $eventManager->attach(LanguageEvent::EVENT_EXPORT, array($addOns, 'exportTranslations'));
        $eventManager->attach(LanguageEvent::EVENT_IMPORT, array($addOns, 'importTranslations'));

    }    

    public function getServiceConfig() {
        return array(
            'factories' => array(
                'phoenix-addons' => function ($sm) {
            $service = new Service\Addons();
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
            $toolList[] = array('key' => 'phoenixAddons');
            $mergedConfig->set(array('templateVars', 'toolList'), $toolList);
        }
    }

}
