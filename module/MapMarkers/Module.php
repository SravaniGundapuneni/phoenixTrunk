<?php
namespace MapMarkers;


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
        $this->updateTableStructures($e);  

        $this->installOrUpdateModule($e);

        $eventManager = $e->getApplication()->getEventManager();
        $eventManager->attach( \Zend\Mvc\MvcEvent::EVENT_DISPATCH, array($this, 'testUnifiedItems'), -1000);

    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'phoenix-mapmarkers' => function ($sm) {
                    $service = new Service\MapMarkers();
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

    public function testUnifiedItems($e)
    {
        $serviceManager = $e->getApplication()->getServiceManager();

        $mapMarkers = $serviceManager->get('phoenix-mapmarkers');

        // echo 'Test GetItems<br>';
        // $items = $mapMarkers->getItems();

        // echo ($items[0]->getTitle());
        // echo '<hr>';

        // $id = 3;

        // echo 'Test GetItem<br>';
        // $mapMarker = $mapMarkers->getItem($id);

        // echo ($mapMarker->getTitle());
        // echo '<hr>';

        // echo 'Test GetItemBy ItemData Field<br>';
        // $mapMarker = $mapMarkers->getItemBy(array('title' => 'This is a test map marker'));

        // echo ($mapMarker->getTitle());
        // echo '<hr>';

        // echo 'Test GetItemBy Item Field<br>';
        // $phoenixProperties = $serviceManager->get('phoenix-properties');

        // $property = $phoenixProperties->getItem(90);

        // $mapMarker = $mapMarkers->getItemBy(array('property' => $property->getEntity()));

        // echo ($mapMarker->getTitle());
        // echo '<hr>';

        // echo 'Test GetItemsBy ItemData Field<br>';
        // $mapMarker = $mapMarkers->getItemsBy(array('title' => 'This is a test map marker'));

        // echo ($mapMarker[0]->getTitle());
        // echo '<hr>';

        // echo 'Test GetItemsBy Item Field<br>';
        // $phoenixProperties = $serviceManager->get('phoenix-properties');

        // $property = $phoenixProperties->getItem(90);

        // $mapMarker = $mapMarkers->getItemsBy(array('property' => $property->getEntity()));

        // echo ($mapMarker[0]->getTitle());
        // echo '<hr>';        


        // die('DIE CALLED AT LINE: ' . __LINE__ . ' in FILE: ' . __FILE__);

        // foreach ($items as $valItem) {
        //     $itemArray = $valItem->getArrayCopy();

        //     var_dump($itemArray);

        //     echo 'Map Marker Title: ' . $itemArray['title'] . '<br>';
        // }
        // die('DIE CALLED AT LINE: ' . __LINE__ . ' in FILE: ' . __FILE__);
    }
}
