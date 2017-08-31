<?php
namespace Integration;

use Phoenix\Module\Module as PhoenixModule;
use Zend\Mvc\MvcEvent;
use Phoenix\EventManager\Event as PhoenixEvent;

class Module extends PhoenixModule
{
    protected $moduleNamespace = __NAMESPACE__;
    protected $moduleDirectory = __DIR__;

    public function onBootstrap(MvcEvent $e)
    {
        $serviceManager = $e->getApplication()->getServiceManager();
        $viewModel = new \Zend\View\Model\ViewModel();
        $viewModel->setTemplate('layout/module');
        $viewModel->setVariable('moduleName',"Integration");
        $serviceManager->setService('integration-layout', $viewModel);

        /**
         * lets load the NXC autoloader
         */
        require_once( 'library/OWS/autoload.php' );
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'integration-manager' => function($sm) {
                    $service = new Service\IntegrationManager($currentUser);
                    $service->setPhoenixProperties($sm->get('phoenix-properties'));
                    $service->setPhoenixAttributes($sm->get('phoenix-attributes'));
                    $service->setPhoenixRooms($sm->get('phoenix-rooms'));
                    $service->setPhoenixAddons($sm->get('phoenix-addons'));
                    $service->setPhoenixRates($sm->get('phoenix-rates'));
                    $service->setDefaultEntityManager($sm->get('doctrine.entitymanager.orm_default'));
                    return $service;
                },
                'phoenix-property-information' => function($sm) {
                    $service = new Service\PropertyInformation;  
                    $service->setPhoenixProperties($sm->get('phoenix-properties'));
                    return $service;
                }
            )
        );
    }

    public function getViewHelperConfig()
    {
        return array(
            'factories' => array(
                'GetLocations' => function($sm) {
                    $serviceManager = $sm->getServiceLocator();
                    $phoenixProperties = $serviceManager->get('phoenix-properties');
                    $helper = new \Integration \Helper\GetLocations($phoenixProperties);
                    return $helper;
                },
                'GetAvailability' => function($sm) {
                    $serviceManager = $sm->getServiceLocator();
                    $phoenixProperties = $serviceManager->get('phoenix-properties');
                    $integrationManager = $serviceManager->get('integration-manager');
                    $helper = new \Integration \Helper\GetAvailability($phoenixProperties, $integrationManager);
                    return $helper;
                },
                'GetProperties' => function($sm) {
                    $serviceManager = $sm->getServiceLocator();
                    $phoenixProperties = $serviceManager->get('phoenix-properties');
                    $helper = new \Integration \Helper\GetProperties($phoenixProperties);
                    return $helper;
                },
                'GetPropertyAddons' => function($sm) {
                    $serviceManager = $sm->getServiceLocator();
                    $phoenixProperties = $serviceManager->get('phoenix-properties');
                    $helper = new \Integration \Helper\GetPropertyAddons($phoenixProperties);
                    return $helper;
                },
                'GetPropertyAddonId' => function($sm) {
                    $serviceManager = $sm->getServiceLocator();
                    $phoenixProperties = $serviceManager->get('phoenix-properties');
                    $helper = new \Integration \Helper\GetPropertyAddonId($serviceManager->get('doctrine.entitymanager.orm_default'));
                    return $helper;
                },                
                'GetPropertyAttributes' => function($sm) {
                    $serviceManager = $sm->getServiceLocator();
                    $phoenixProperties = $serviceManager->get('phoenix-properties');
                    $helper = new \Integration \Helper\GetPropertyAttributes($phoenixProperties);
                    return $helper;
                },
                'GetPropertyAvailability' => function($sm) {
                    $serviceManager = $sm->getServiceLocator();
                    $phoenixProperties = $serviceManager->get('phoenix-properties');
                    $integrationManager = $serviceManager->get('integration-manager');
                    $helper = new \Integration \Helper\GetPropertyAvailability($phoenixProperties, $integrationManager);
                    return $helper;
                },
                'GetPropertyInformation' => function($sm) {
                    $serviceManager = $sm->getServiceLocator();
                    $phoenixProperties = $serviceManager->get('phoenix-properties');
                    $helper = new \Integration \Helper\GetPropertyInformation($phoenixProperties);
                    return $helper;
                },
                'GetPropertyRooms' => function($sm) {
                    $serviceManager = $sm->getServiceLocator();
                    $phoenixProperties = $serviceManager->get('phoenix-properties');
                    $helper = new \Integration \Helper\GetPropertyRooms($phoenixProperties);
                    return $helper;
                },
                'GetPropertyRates' => function($sm) {
                    $serviceManager = $sm->getServiceLocator();
                    $phoenixProperties = $serviceManager->get('phoenix-properties');
                    $helper = new \Integration \Helper\GetPropertyRates($phoenixProperties);
                    return $helper;
                },        
                        
                'MakeReservation' => function($sm) {
                    $serviceManager = $sm->getServiceLocator();
                    $phoenixProperties = $serviceManager->get('phoenix-properties');
                    $integrationManager = $serviceManager->get('integration-manager');
                    $helper = new \Integration \Helper\MakeReservation($phoenixProperties, $integrationManager);
                    return $helper;
                },
                'ModifyReservation' => function($sm) {
                    $serviceManager = $sm->getServiceLocator();
                    $phoenixProperties = $serviceManager->get('phoenix-properties');
                    $integrationManager = $serviceManager->get('integration-manager');
                    $helper = new \Integration \Helper\ModifyReservation($phoenixProperties, $integrationManager);
                    return $helper;
                },
                'GetPropertyWithRateCode' => function($sm) {
                    $serviceManager = $sm->getServiceLocator();
                    $phoenixProperties = $serviceManager->get('phoenix-properties');
                    $integrationManager = $serviceManager->get('integration-manager');
                    $helper = new \Integration \Helper\GetPropertyWithRateCode($phoenixProperties, $integrationManager);
                    return $helper;
                },
            )
        );
    }
}
