<?php

/**
 * PhoenixProperties Module
 *
 * The Module Class for the Phoenix Properties module
 *
 * Phoenix Properties is one of five related modules, all with the Phoenix prefix to
 * separate them from existing modules (if necessary), so Production developers won't
 * accidentally try to work with the wrong module.
 *
 * @category    Toolbox
 * @package     PhoenixProperties
 * @subpackage  Controllers
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.4
 * @since       File available since release 13.4
 * @author      A. Tate <atate@travelclick.com>
 * @filesource
 */

namespace PhoenixProperties;

use Phoenix\Module\Module as PhoenixModule;
use Zend\Mvc\MvcEvent;

use Languages\EventManager\Event as LanguageEvent;

/**
 * PhoenixProperties Module
 *
 * The Module Class for the Phoenix Properties module
 *
 * Phoenix Properties is one of five related modules, all with the Phoenix prefix to
 * separate them from existing modules (if necessary), so Production developers won't
 * accidentally try to work with the wrong module.
 *
 * @category    Toolbox
 * @package     PhoenixProperties
 * @subpackage  Controllers
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.4
 * @since       File available since release 13.4
 * @author      A. Tate <atate@travelclick.com>
 */
class Module extends PhoenixModule {

    /**
     * Thd module's namespace. This should not be changed.
     * It has to be included in every Module class so the various methods (getConfig, getServiceConfig, etc..)
     * declared in PhoenixModule will have the proper namespace and directory.
     *
     * @var string
     */
    protected $moduleNamespace = __NAMESPACE__;

    /**
     * Thd module's directory. This should not be changed.
     * It has to be included in every Module class so the various methods (getConfig, getServiceConfig, etc..)
     * declared in PhoenixModule will have the proper namespace and directory.
     *
     * @var string
     */
    protected $moduleDirectory = __DIR__;

    public function onBootstrap(MvcEvent $e) {
        $eventManager = $e->getApplication()->getEventManager();
        $serviceManager = $e->getApplication()->getServiceManager();
        $phoenixEventManager = $serviceManager->get('phoenix-eventmanager');

        /**
         * Commenting out for commit, as this is not yet ready for committing.
         */
        // $phoenixEvents = $serviceManager->get('phoenix-eventmanager');
        // $phoenixEvents->attach('setIdentifierProperty', array($this, 'onSetIdentifier'));
        $eventManager->attach(\Zend\Mvc\MvcEvent::EVENT_DISPATCH, array($this, 'populateLayout'), 100);
        $eventManager->attach(\Zend\Mvc\MvcEvent::EVENT_RENDER, array($this, 'populateLayoutErrors'), 12);

        $eventManager->attach(\Zend\Mvc\MvcEvent::EVENT_ROUTE, array($this, 'setTranslationsExport'), -1000);

        /**
         * Module Migrations Support
         */
        $this->updateTableStructures($e);
        $this->installOrUpdateModule($e);
    }

    /**
     * getAutoloaderConfig
     *
     * Sets up the Autoloader config for the module and returns it to the Application
     *
     * @return array
     */
    public function getAutoloaderConfig()
    {
        $autoloaderArray = parent::getAutoloaderConfig();
        return array_merge(array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),), $autoloaderArray); 
    }  

    /**
     * getServiceConfig
     *
     * Sets up the service factories for this module. Other things can be set in this array,
     * but this is all we are currently using it for.
     *
     * @return array The array of service factories
     */
    public function getServiceConfig() {
        return array(
            'factories' => array(
                'phoenix-properties' => function ($sm) {
            $service = new Service\Properties();
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
                'map-properties' => function($sm) {
            $service = new Service\GoogleMapContent;
            $service->setDefaultEntityManager($sm->get('doctrine.entitymanager.orm_default'));
            $service->setAdminEntityManager($sm->get('doctrine.entitymanager.orm_admin'));
            $service->setEventManager($sm->get('phoenix-eventmanager'));
            $service->setConfig($sm->get('MergedConfig'));
             $service->setCurrentLanguage($sm->get('currentLanguage'));
            $service->setLanguageService($sm->get('phoenix-languages'));
            return $service;
        },
                'phoenix-users-current' => function($sm) {
            $service = new Model\PropertyUser($sm->get('MergedConfig'));
            $service->setDefaultEntityManager($sm->get('doctrine.entitymanager.orm_default'));
            $service->setAdminEntityManager($sm->get('doctrine.entitymanager.orm_admin'));
            
            $service->setEventManager($sm->get('phoenix-eventmanager'));
            $service->setAlerts($sm->get('phoenix-users-alerts'));

            return $service;
        },
                'phoenix-user-login-redirect' => function ($sm) {
            $service = new Service\UserLoginRedirect();
            $service->setDefaultEntityManager($sm->get('doctrine.entitymanager.orm_default'));
            $service->setAdminEntityManager($sm->get('doctrine.entitymanager.orm_admin'));
            $service->setServiceManager($sm);

            return $service;
        }
            )
        );
    }

    public function populateLayoutErrors($e) {
        if ($e->getError() && !$e->getParam('ignoreTemplateSetup', false)) {
            $this->populateLayout($e);
        }
    }

    public function populateLayout($e) {
        $serviceManager = $e->getApplication()->getServiceManager();
        $config = $serviceManager->get('MergedConfig');

        $viewManager = $serviceManager->get('view-manager');
        $viewHelperManager = $serviceManager->get('viewhelpermanager');

        $viewModel = ($e->getError()) ? $e->getResult() : $viewManager->getViewModel();

        $propertyInformation = $viewHelperManager->get('getPropertyInformation');

        $currentProperty = $serviceManager->get('currentProperty');
        $corporateProperty = $serviceManager->get('corporateProperty');

        $currentPropertyArray = $propertyInformation($currentProperty->getId());

        // var_dump($e->getRouteMatch());
        // die('FILE: ' . __FILE__ . ' LINE: ' . __LINE__);

        if ($currentProperty === $corporateProperty) {
            $corporatePropertyArray = $currentPropertyArray;
            $viewModel->isCorporateProperty = true;
        } else {
            $corporatePropertyArray = $propertyInformation($corporateProperty->getId());
            $viewModel->isCorporateProperty = false;
        }

        $viewModel->currentProperty = $currentPropertyArray;
        $viewModel->corporateProperty = $corporatePropertyArray;
    }

    public function addModuleToToolbox($e) {
        $serviceManager = $e->getApplication()->getServiceManager();
        $mergedConfig = $serviceManager->get('MergedConfig');
        $phoenixEvents = $serviceManager->get('phoenix-eventmanager');
        $propertiesService = $serviceManager->get('phoenix-properties');

        $toolList = $mergedConfig->get(array('templateVars', 'toolList'));
        if (is_array($toolList)) {
            $toolList[] = array('key' => 'phoenixProperties');
            $mergedConfig->set(array('templateVars', 'toolList'), $toolList);
        }

        //Attach User Events for this module
        $phoenixEvents->attach(\Users\EventManager\Event::EVENT_USER_OPTIONS, array($propertiesService, 'onUserOptions'), 100);
    }

    public function initPhoenixProperties($e) {
        $routeMatch = $e->getRouteMatch();
        $serviceManager = $e->getApplication() - getServiceManager();
        $mergedConfig = $serviceManager->get('MergdedConfig');
    }


    public function setTranslationsExport(MvcEvent $e)
    {
        $routeMatch = $e->getRouteMatch();

        //No need to run these if we're not doing an export.

        if ($routeMatch->getMatchedRouteName() != 'languages-toolbox' || ($routeMatch->getParam('action') != 'export' && $routeMatch->getParam('action') != 'import')) {
            return;
        }

        $properties = $e->getApplication()->getServiceManager()->get('phoenix-properties');

        $eventManager = $properties->getEventManager();

        $eventManager->attach(LanguageEvent::EVENT_EXPORT, array($properties, 'exportTranslations'));
        $eventManager->attach(LanguageEvent::EVENT_IMPORT, array($properties, 'importTranslations'));

    }        
}
