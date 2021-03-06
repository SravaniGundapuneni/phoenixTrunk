<?php
namespace Footer;

use Phoenix\Module\Module as PhoenixModule;
use Zend\Mvc\MvcEvent;

use Languages\EventManager\Event as LanguageEvent;

class Module extends PhoenixModule
{
    protected $moduleNamespace = __NAMESPACE__;
    protected $moduleDirectory = __DIR__;

    public function onBootstrap($e)
    {
        $eventManager = $e->getApplication()->getEventManager();

        /**
         * Module Migrations Support
         */
        $this->updateTableStructures($e);    
        $this->installOrUpdateModule($e);

        $eventManager->attach(\Zend\Mvc\MvcEvent::EVENT_ROUTE, array($this, 'setTranslationsExport'), -1000);
    }

    public function setTranslationsExport(MvcEvent $e)
    {
        $routeMatch = $e->getRouteMatch();

        //No need to run these if we're not doing an export.

        if ($routeMatch->getMatchedRouteName() != 'languages-toolbox' || ($routeMatch->getParam('action') != 'export' && $routeMatch->getParam('action') != 'import')) {
            return;
        }

        $footer = $e->getApplication()->getServiceManager()->get('phoenix-footer');

        $eventManager = $footer->getEventManager();

        $eventManager->attach(LanguageEvent::EVENT_EXPORT, array($footer, 'exportTranslations'));
        $eventManager->attach(LanguageEvent::EVENT_IMPORT, array($footer, 'importTranslations'));

    }    

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'phoenix-footer' => function ($sm) {
                    //echo "instantiating service-mapmarkers<br/>";
                    $service = new Service\FooterMenus();
                    //echo "initd service map markers<br/>";
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
}
