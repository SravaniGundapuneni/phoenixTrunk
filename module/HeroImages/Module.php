<?php

namespace HeroImages;

use Phoenix\Module\Module as PhoenixModule;
use Zend\Mvc\MvcEvent;
use Languages\EventManager\Event as LanguageEvent;

class Module extends PhoenixModule
{

    protected $moduleNamespace = __NAMESPACE__;
    protected $moduleDirectory = __DIR__;

    public function onBootstrap(MvcEvent $e)
    {
        $eventManager = $e->getApplication()->getEventManager();
        $eventManager->attach(\Zend\Mvc\MvcEvent::EVENT_DISPATCH, array($this, 'setLayout'), 100);

        $eventManager->attach(\Zend\Mvc\MvcEvent::EVENT_ROUTE, array($this, 'setTranslationsExport'), -1000);

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

        $heroImage = $e->getApplication()->getServiceManager()->get('phoenix-heroimages');

        $eventManager = $heroImage->getEventManager();

        $eventManager->attach(LanguageEvent::EVENT_EXPORT, array($heroImage, 'exportTranslations'));
        $eventManager->attach(LanguageEvent::EVENT_IMPORT, array($heroImage, 'importTranslations'));

    }

    public function getServiceConfig()
    {

        return array(
            'factories' => array(
                'phoenix-heroimages' => function ($sm) {
            $service = new Service\HeroImage();
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
                'phoenix-heroimageAttachments' => function ($sm) {
            $service = new Service\Attachment();
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
                'GetHeroImages' => function($sm) {
                    $serviceManager = $sm->getServiceLocator();
                    $phoenixProperties = $serviceManager->get('phoenix-properties');
                    $helper = new Helper\GetHeroImages($phoenixProperties);
                    return $helper;
                },
                'GetHeroImagePage' => function($sm) {
                    $serviceManager = $sm->getServiceLocator();
                    $helper = new Helper\GetHeroImagePage($serviceManager->get('phoenix-pages'));
                    return $helper;
                }           
            )
        );
    }

    public function setLayout(MvcEvent $e)
    {
        $routeMatch = $e->getRouteMatch();

        $controller = $routeMatch->getParam('controller');

        if (strpos($controller, 'Controller\ModuleToolbox') > 0 || strpos($controller, 'Controller\AttachmentToolbox') > 0) {
            $e->getViewModel()->setTemplate('toolbox-layout');
        }
    }

}
