<?php
namespace GoogleAnalytics;


use Phoenix\Module\Module as PhoenixModule;

class Module extends PhoenixModule
{
    protected $moduleNamespace = __NAMESPACE__;
    protected $moduleDirectory = __DIR__;

    public function onBootstrap($e)
    {
        /**
         * Module Migrations Support
         */
   

        $this->installOrUpdateModule($e);
        $this->updateTableStructures($e);

        $eventManager = $e->getApplication()->getEventManager();
        // $eventManager->attach( \Zend\Mvc\MvcEvent::EVENT_DISPATCH, array($this, 'testTranslation'), -1000);

    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'phoenix-googleanalytics' => function ($sm) {
                    $service = new Service\GoogleAnalytics();
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

    // public function testTranslation($e)
    // {
    //     $serviceManager = $e->getApplication()->getServiceManager();

    //     $googleAnalytics = $serviceManager->get('phoenix-googleanalytics');

    //     $items = $googleAnalytics->getItems();

    //     // foreach ($items as $valItem) {
    //     //     $itemArray = $valItem->getArrayCopy();

    //     //     var_dump($itemArray);

    //     //     echo 'Map Marker Title: ' . $itemArray['title'] . '<br>';
    //     // }
    //     // die('DIE CALLED AT LINE: ' . __LINE__ . ' in FILE: ' . __FILE__);
    // }
}
