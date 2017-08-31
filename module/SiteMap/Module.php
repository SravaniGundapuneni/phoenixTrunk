<?php
namespace SiteMap;

use Phoenix\Module\Module as PhoenixModule;
use Zend\Mvc\MvcEvent;

class Module extends PhoenixModule
{
    protected $moduleNamespace = __NAMESPACE__;
    protected $moduleDirectory = __DIR__;
    
    public function onBootstrap(MvcEvent $e)
    {
        //$eventManager->attach(\Zend\Mvc\MvcEvent::EVENT_ROUTE, array($this, 'setTranslationsExport'), -1000);

        /*/
         * Module Migrations Support
        /*/
        $this->updateTableStructures($e);
        $this->installOrUpdateModule($e);
    }
    
    public function getServiceConfig()
    {
        
        return array(
            'factories' => array(
                'phoenix-sitemap' => function ($sm) {
                    $service = new Service\SiteMaps;
                    $service->setCurrentUser($sm->get('phoenix-users-current'));
                    $service->setDefaultEntityManager($sm->get('doctrine.entitymanager.orm_default'));
                    $service->setAdminEntityManager($sm->get('doctrine.entitymanager.orm_admin'));
                    $service->setEventManager($sm->get('phoenix-eventmanager'));
                    $service->setConfig($sm->get('MergedConfig'));
                     $service->setCurrentLanguage($sm->get('currentLanguage'));
                    $service->setLanguageService($sm->get('phoenix-languages'));
                    $service->setServiceManager($sm);
                    return $service;
                }
            )
        );
    }
    
    public function getViewHelperConfig()
    {
        return array(
            'factories' => array(
                'GetSiteMap' => function($sm) {
                    $serviceManager = $sm->getServiceLocator();
                    $moduleService = $serviceManager->get('phoenix-sitemap');
                    $helper = new \SiteMap\Helper\GetSiteMap($moduleService);
                    return $helper;
                }
            )
        );
    }


    public function setTranslationsExport(MvcEvent $e)
    {
        $routeMatch = $e->getRouteMatch();

        //No need to run these if we're not doing an export.

        if ($routeMatch->getMatchedRouteName() != 'languages-toolbox' || ($routeMatch->getParam('action') != 'export' && $routeMatch->getParam('action') != 'import')) {
            return;
        }

        $rooms = $e->getApplication()->getServiceManager()->get('phoenix-sitemap');

        $eventManager = $rooms->getEventManager();

        $eventManager->attach(LanguageEvent::EVENT_EXPORT, array($rooms, 'exportTranslations'));
        $eventManager->attach(LanguageEvent::EVENT_IMPORT, array($rooms, 'importTranslations'));

    }        
}