<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Toolbox;

use Phoenix\Module\Module as PhoenixModule;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\ModuleManager\ModuleManager as ModuleManager;

class Module extends PhoenixModule
{
    const TOOLBOX_ROUTE_ROOT_KEY = 'home/toolbox-root';
    const TOOLBOX_SUBSITE_ROUTE_ROOT_KEY = 'home/toolbox-root-subsite';

    const DEFAULT_TIMEZONE = 'America/New_York';

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

    /**
     * init
     *
     * Module init Method. This is ran when the module is loaded, so anything that needs to be done before the bootstrap
     * (such as attaching event callbacks to the Boostrap event) should be done here.
     * @param  ModuleManager $moduleManager
     * @return void
     */
    public function init(ModuleManager $moduleManager)
    {
    }

    /**
     * onBootstrap
     *
     * The default onBootstrap event for the module. This is triggered without having to attach it like other callbacks.
     * @param  MvcEvent $e
     * @return void
     */
    public function onBootstrap(MvcEvent $e)
    {
        //Start the session
        session_start();

        //$this->backupDlms($e);

        $httpServerHeaders = $e->getRequest()->getServer()->toArray();
        $userAgentHeaders = $e->getRequest()->getHeaders()->toString();

        //Set the Mobile Detect Service
        $mobileDetect = new \Phoenix\StdLib\MobileDetect($httpServerHeaders, null);

        //Gets the Service Manager from the Application
        $serviceManager = $e->getApplication()->getServiceManager();

        $defaultEntityManager = $serviceManager->get('doctrine.entitymanager.orm_default');

        $config = $serviceManager->get('Config');

        if (empty($config['debug'])) {
            $defaultEntityManager->getConnection()->getConfiguration()->setSQLLogger(null);          
        } else {
            $defaultEntityManager->getConnection()->getConfiguration()->setSQLLogger(new \Doctrine\DBAL\Logging\DebugStack());          
        }



        $serviceManager->setService('MobileDetect', $mobileDetect);

        //Add to NewRelic Request
        $newRelic = $serviceManager->get('phoenix-newrelic');

        $request = $e->getRequest();

        /**
         * Module Migrations Support
         */
        $this->updateTableStructures($e);
        
        $applicationConfig = $serviceManager->get('ApplicationConfig');

        $siteRoot = '';

        if (!empty($config['templateVars']['siteroot'])) {
            $siteRoot = $config['templateVars']['siteroot'];
        }

        if ($siteRoot) {
            // if (!empty($config['assetic_configuration']['webPath'])) {
            //     $newWebPath = $siteRoot . $config['assetic_configuration']['webPath'];
            //     $config['assetic_configuration']['webPath'] = $newWebPath;
            // }

            // if (!empty($config['assetic_configuration']['basePath'])) {
            //     $newBasePath = $siteRoot . $config['assetic_configuration']['basePath'];
            //     $config['assetic_configuration']['basePath'] = $newBasePath;
            // }

            $serviceManager->setAllowOverride(true);
            $serviceManager->setService('Config', $config);
            $serviceManager->setAllowOverride(false);

            $configNew = $serviceManager->get('Config');
        }

        if (!empty($config['phoenixVersion'])) {
            define('PHOENIX_VERSION', $config['phoenixVersion']);

            if (!empty($config['phoenixVersionDate'])) {
                define('PHOENIX_VERSION_DATE', $config['phoenixVersionDate']);
            }
        } else {
            define('PHOENIX_VERSION', 'Phoenix Development');
            define('PHOENIX_VERSION_DATE', date('Y-m-d'));
        }


        $defaultTimezone = (isset($applicationConfig['defaultTimezone'])) ? $applicationConfig['defaultTimezone'] : static::DEFAULT_TIMEZONE;

        //This is here to make our servers don't trigger errors for this if it isn't set in the php.ini.
        date_default_timezone_set($defaultTimezone);

        //Get the Event Manager for the MVC Application
        $eventManager = $e->getApplication()->getEventManager();

        //This sets the layout, depending on if it is a Backend or Frontend route.
        $eventManager->attach(\Zend\Mvc\MvcEvent::EVENT_DISPATCH, array($this, 'setLayout'), 100);
        $eventManager->attach(\Zend\Mvc\MvcEvent::EVENT_DISPATCH, array($this, 'disableLayout'), -10000);
        //$eventManager->attach(\Zend\Mvc\MvcEvent::EVENT_DISPATCH, array($this, 'updateTableStructures'), 1000);
        $eventManager->attach(\Zend\Mvc\MvcEvent::EVENT_FINISH, array($this, 'onFinish'), -100);

        //ALL LISTENERS AND METHODS BELOW HERE ARE ASSUMED COMPATIBLE WITH CONDOR, AND THUS NOT AS YET USABLE BY PHOENIXTNG

        // //Set the DocType for the Renderer by default to XHTML1_TRANSITIONAL
        // $viewManager = $serviceManager->get('view-manager');
        // $viewManager->getRenderer()->doctype()->setDoctype('XHTML1_TRANSITIONAL');

        //Instantiate the Phoenix Object, which handles a lot of the setup of Phoenix
        $phoenix = $serviceManager->get('phoenix');

        // //Attach callbacks to the Route MvcEvent.
        // //REMOVE BEFORE LIVE: This callback is temporary, and should be removed before 13.4 goes live.
        //$eventManager->attach(\Zend\Mvc\MvcEvent::EVENT_ROUTE, array($this, 'updateDatabase'), 10000);

        // //This will check if the request is for the legacy Toolbox, and set a parameter for the event if so. This parameter is used
        // //by other callbacks and methods to determine if they need to run.
        // $eventManager->attach(\Zend\Mvc\MvcEvent::EVENT_ROUTE, array($phoenix, 'checkForLegacy'), -300);

        // //This will set a url that will be used for socket calls from Phoenix pages, at least until sockets are handled through Phoenix.
        // $eventManager->attach(\Zend\Mvc\MvcEvent::EVENT_ROUTE, array($this, 'handleLegacySockets'), -400);

        //Sets additional values for the $paths array, and defines some path constants used by the application.
        $eventManager->attach(\Zend\Mvc\MvcEvent::EVENT_ROUTE, array($phoenix, 'setPaths'), -701);

        // //Sets the event's cxmlIgnore parameter, used by multiple callbacks
        // $eventManager->attach(\Zend\Mvc\MvcEvent::EVENT_ROUTE, array($phoenix, 'checkForCxmlIgnore'), -550);

        // // //For use with the LegacyController, this will handle
        // // $eventManager->attach(\Zend\Mvc\MvcEvent::EVENT_ROUTE, array($this, 'preprocessRequest'), -575);

        // //Retrieve the LocalCxml and prepare it for use in the application.
        // $eventManager->attach( \Zend\Mvc\MvcEvent::EVENT_ROUTE, array($this, 'getLocalCxml'), -600);

        // //This runs after localCxml has been retrieved and the Config has been merged. It adds additional paths retrieved from the Merged Config.
        // $eventManager->attach(\Zend\Mvc\MvcEvent::EVENT_ROUTE, array($this, 'addPagePaths'), -999);

        // //In case something slips through Phoenix and the Legacy Routing, default it to legacy. Perhaps Condor will find something for it, or at least use its 404 page.
        // $eventManager->attach( \Zend\Mvc\MvcEvent::EVENT_ROUTE, array($this, 'legacyFallback'), -1000 );

        // //Normalize DataSections, making the dataSection value in the MergedConfig a string and get the root DataSection, if necessary.
        // $eventManager->attach(\Zend\Mvc\MvcEvent::EVENT_ROUTE, array($this, 'normalizeDataSections'), -1000);

        // //Attach Callbacks to the Dispatch MvcEvent
        // //Now that we have done all of the setup and found our route, let's initialize Phoenix
        // $eventManager->attach( \Zend\Mvc\MvcEvent::EVENT_DISPATCH, array($this, 'preDispatchInit'), 100);

        // //Attaches the parseTags Filter to the FilterChain.
        $eventManager->attach(\Zend\Mvc\MvcEvent::EVENT_DISPATCH, array($this, 'attachParseTags'), 100);

        // //Adds several needed variables to the layout.
        $eventManager->attach(\Zend\Mvc\MvcEvent::EVENT_DISPATCH, array($phoenix, 'populateLayout'), 100);
        
         $eventManager->attach('render', array($this, 'setLayoutTitle'));
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

   public function backupDlms(MvcEvent $e)
    {

        $serviceManager = $e->getApplication()->getServiceManager();

        $config = $serviceManager->get('Config');

        $dontRunConvert = (empty($config['dlm_conversion'])) ? false : $config['dlm_conversion'];

        if (!$dontRunConvert) {
            return;
        }

        $entityManager = $serviceManager->get('doctrine.entitymanager.orm_default');

        $queryDeleteModules = "DROP TABLE IF EXISTS dynamicListModules;";
        $queryDeleteModuleFields = 'DROP TABLE IF EXISTS dynamicListModule_fields';
        $queryDeleteModuleItemFields = 'DROP TABLE IF EXISTS dynamicListModule_itemFields';
        $queryDeleteModuleItems = 'DROP TABLE IF EXISTS dynamicListModule_items';        
        $queryDeleteSelectValues = 'DROP TABLE IF EXISTS dynamicListModule_selectValues';

        $entityManager->getConnection()->query($queryDeleteModuleItemFields);
        $entityManager->getConnection()->query($queryDeleteModuleItems);
        $entityManager->getConnection()->query($queryDeleteModuleFields);
        $entityManager->getConnection()->query($queryDeleteModules);
        $entityManager->getConnection()->query($queryDeleteSelectValues);


        $renameModules = "RENAME TABLE `dynamicListModules_old` TO `dynamicListModules` ;";
        $renameModuleFields = "RENAME TABLE `dynamicListModule_fields_old` TO `dynamicListModule_fields` ;";
        $renameModuleItems = "RENAME TABLE `dynamicListModule_items_old` TO `dynamicListModule_items` ;";
        $renameModuleItemFields = "RENAME TABLE `dynamicListModule_itemFields_old` TO `dynamicListModule_itemFields` ;";        
        $renameModuleSelectValues = "RENAME TABLE `dynamicListModule_selectValues_old` TO `dynamicListModule_selectValues` ;";

        $entityManager->getConnection()->query($renameModules);
        $entityManager->getConnection()->query($renameModuleFields);
        $entityManager->getConnection()->query($renameModuleItems);
        $entityManager->getConnection()->query($renameModuleItemFields);
        $entityManager->getConnection()->query($renameModuleSelectValues);

        $modulesOldQuery = "CREATE TABLE IF NOT EXISTS `dynamicListModules_old` (
                          `moduleId` int(11) NOT NULL AUTO_INCREMENT,
                          `name` varchar(255) DEFAULT NULL,
                          `description` text,
                          `userId` int(11) DEFAULT NULL,
                          `modified` datetime DEFAULT NULL,
                          `created` datetime DEFAULT NULL,
                          `status` int(11) DEFAULT NULL,
                          `categories` tinyint(1) DEFAULT NULL,
                          PRIMARY KEY (`moduleId`)
                        ) ENGINE=InnoDB  DEFAULT CHARSET=utf8;";

        $entityManager->getConnection()->query($modulesOldQuery);

        $insertModulesOld = "INSERT INTO dynamicListModules_old SELECT * from dynamicListModules";

        $entityManager->getConnection()->query($insertModulesOld);

        $moduleFieldsOldQuery = "CREATE TABLE IF NOT EXISTS `dynamicListModule_fields_old` (
          `fieldId` int(11) NOT NULL AUTO_INCREMENT,
          `module` int(11) NOT NULL,
          `name` varchar(255) DEFAULT NULL,
          `type` varchar(255) DEFAULT NULL,
          `showInList` int(11) DEFAULT NULL,
          `orderNumber` int(11) DEFAULT NULL,
          `userId` int(11) DEFAULT NULL,
          `created` datetime DEFAULT NULL,
          `modified` datetime DEFAULT NULL,
          `status` int(11) DEFAULT NULL,
          `displayName` varchar(255) DEFAULT NULL,
          PRIMARY KEY (`fieldId`),
          KEY `module` (`module`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=utf8;";

        $entityManager->getConnection()->query($moduleFieldsOldQuery);

        $insertModuleFieldsOld = "INSERT INTO dynamicListModule_fields_old SELECT * from dynamicListModule_fields";

        $entityManager->getConnection()->query($insertModuleFieldsOld);

        $moduleItemsOldQuery = "CREATE TABLE IF NOT EXISTS `dynamicListModule_items_old` (
                              `itemId` int(11) NOT NULL AUTO_INCREMENT,
                              `allProperties` tinyint(1) DEFAULT NULL,
                              `module` int(11) NOT NULL,
                              `property` int(11) NOT NULL,
                              `categoryId` int(11) DEFAULT NULL,
                              `userId` int(11) DEFAULT NULL,
                              `created` datetime DEFAULT NULL,
                              `modified` datetime DEFAULT NULL,
                              `status` int(11) DEFAULT NULL,
                              `propertyId` int(11) DEFAULT NULL,
                              PRIMARY KEY (`itemId`),
                              KEY `module` (`module`),
                              KEY `property` (`property`)
                            ) ENGINE=InnoDB  DEFAULT CHARSET=utf8;";

        $entityManager->getConnection()->query($moduleItemsOldQuery);       

        $insertModuleItemsOld = "INSERT INTO dynamicListModule_items_old SELECT * from dynamicListModule_items";

        $entityManager->getConnection()->query($insertModuleItemsOld);

        $moduleItemFieldsOldQuery = "CREATE TABLE IF NOT EXISTS `dynamicListModule_itemFields_old` (
                                  `ifId` int(11) NOT NULL AUTO_INCREMENT,
                                  `item` int(11) NOT NULL,
                                  `field` int(11) NOT NULL,
                                  `value` text,
                                  `userId` int(11) DEFAULT NULL,
                                  `created` datetime DEFAULT NULL,
                                  `modified` datetime DEFAULT NULL,
                                  `status` int(11) DEFAULT NULL,
                                  PRIMARY KEY (`ifId`),
                                  KEY `item` (`item`),
                                  KEY `field` (`field`)
                                ) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
                                ";

        $entityManager->getConnection()->query($moduleItemFieldsOldQuery);

        $insertModuleItemFieldsOld = "INSERT INTO dynamicListModule_itemFields_old SELECT * from dynamicListModule_itemFields";

        $entityManager->getConnection()->query($insertModuleItemFieldsOld);

        $selectValuesOld = "CREATE TABLE IF NOT EXISTS `dynamicListModule_selectValues_old` (
                              `valId` int(11) NOT NULL AUTO_INCREMENT,
                              `name` text,
                              `field` int(11) DEFAULT NULL,
                              PRIMARY KEY (`valId`)
                            ) ENGINE=InnoDB  DEFAULT CHARSET=utf8;";

        $entityManager->getConnection()->query($selectValuesOld);

        $insertSelectValuesOld = "INSERT INTO dynamicListModule_selectValues_old SELECT * from dynamicListModule_selectValues";

        $entityManager->getConnection()->query($insertSelectValuesOld);        

        $queryDeleteModules = "DROP TABLE IF EXISTS dynamicListModules;";
        $queryDeleteModuleFields = 'DROP TABLE IF EXISTS dynamicListModule_fields';
        $queryDeleteModuleItemFields = 'DROP TABLE IF EXISTS dynamicListModule_itemFields';
        $queryDeleteModuleItems = 'DROP TABLE IF EXISTS dynamicListModule_items';        
        $queryDeleteSelectValues = 'DROP TABLE IF EXISTS dynamicListModule_selectValues';

        $entityManager->getConnection()->query($queryDeleteModuleItemFields);
        $entityManager->getConnection()->query($queryDeleteModuleItems);
        $entityManager->getConnection()->query($queryDeleteSelectValues);
        $entityManager->getConnection()->query($queryDeleteModuleFields);
        $entityManager->getConnection()->query($queryDeleteModules);
    }

    /**
     * getServiceConfig
     *
     * Sets up the service factories for this module. Other things can be set in this array,
     * but this is all we are currently using it for.
     *
     * @return array The array of service factories
     */
    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                //The Legacy Main Adapter
                // 'phoenix-legacy-main' => function($sm) {
                //     $service = new \Toolbox\Legacy\Main();
                //     \Toolbox\Legacy\MainLoader::setInstance($service);
                //     return \Toolbox\Legacy\MainLoader::getInstance();
                // },
                // //The Legacy Page Adapter
                // 'phoenix-legacy-page' => function($sm) {
                //     $service = new \Toolbox\Legacy\Page();
                //     $service->setNewDb($sm->get('doctrine.entitymanager.orm_default'));
                //     $service->setNewAdminDb($sm->get('doctrine.entitymanager.orm_admin'));
                //     return $service;
                // },
                // //The Legacy LinkWriter Adapter
                // 'phoenix-legacy-linkwriter' => function($sm) {
                //     $service = new \Toolbox\Legacy\LinkWriter();
                //     $service->setNewDb($sm->get('doctrine.entitymanager.orm_default'));
                //     $service->setNewAdminDb($sm->get('doctrine.entitymanager.orm_admin'));
                //     return $service;
                // },
                // //The Legacy ContentAppearanceTracking Adapter
                // 'phoenix-legacy-contentappearancetracking' => function($sm) {
                //     $service = new \Toolbox\Legacy\ContentAppearanceTracking();
                //     $service->setNewDb($sm->get('doctrine.entitymanager.orm_default'));
                //     $service->setNewAdminDb($sm->get('doctrine.entitymanager.orm_admin'));
                //     return $service;
                // },
                //A wrapper for the Doctrine Default Entity Manager.
                //This might be removed, as it's only called in one place that currently isn't being used
                'phoenix-database' => function ($sm) {
                    $service = new \Phoenix\Module\Database();
                    $service->setEntityManager($sm->get('doctrine.entitymanager.orm_default'));
                    return $service;
                },
                //A wrapper for the Doctrine Default Entity Manager.
                //This might be removed, as it's only called in one place that currently isn't being used
                'phoenix-admindatabase' => function ($sm) {
                    $service = new \Phoenix\Module\Database();
                    $service->setEntityManager($sm->get('doctrine.entitymanager.orm_admin'));
                    return $service;
                },
                // //The Legacy DB Adapter
                // 'phoenix-legacy-db' => function($sm) {
                //     $service = new \Toolbox\Legacy\Db();
                //     return $service;
                // },
                // //The Legacy Cxml Adapter
                // 'phoenix-legacy-cxml' => function ($sm) {
                //     $service = new \Toolbox\Legacy\Cxml();
                //     $service->setServiceManager($sm);
                //     return $service;
                // },
                //The Legacy Module Adapter
                // 'phoenix-legacy-moduleadapter' => function ($sm) {
                //     $service = new \Toolbox\Legacy\ModuleAdapter();
                //     return $service;
                // },
                // //The Legacy Cache Adapter
                // 'phoenix-legacy-cache' => function ($sm) {
                //     $service = new \Toolbox\Legacy\Cache();
                //     $service->setNewDb($sm->get('doctrine.entitymanager.orm_default'));
                //     $service->setNewAdminDb($sm->get('doctrine.entitymanager.orm_admin'));
                //     return $service;
                // },
                // //The Legacy Auth Adapter
                // 'phoenix-legacy-auth' => function ($sm) {
                //     $service = new \Toolbox\Legacy\Auth();
                //     $service->setNewDb($sm->get('doctrine.entitymanager.orm_default'));
                //     $service->setNewAdminDb($sm->get('doctrine.entitymanager.orm_admin'));
                //     $service->setAclService($sm->get('phoenix-users-acl'));
                //     return $service;
                // },
                // The NewRelic Performance Tool Class
                'phoenix-newrelic' => function ($sm) {
                    $service = new \Phoenix\Performance\NewRelic();
                    return $service;
                },                
                //The Phoenix EventManager
                'phoenix-eventmanager' => function ($sm) {
                    $service = new \Phoenix\EventManager\EventManager();
                    $service->setEventClass('\Phoenix\EventManager\Event');
                    return $service;
                },
                //The Phoenix ErrorHandler
                'phoenix-errorhandler' => function ($sm) {
                    $service = new \Toolbox\Service\ErrorHandler($sm->get('MergedConfig'));
                    return $service;
                },
                //The Content Appearance Tracking
                'phoenix-contentappearance' => function ($sm) {
                    $service = new Service\ContentAppearance();
                    $service->setDefaultEntityManager($sm->get('doctrine.entitymanager.orm_default'));
                    $service->setAdminEntityManager($sm->get('doctrine.entitymanager.orm_admin'));
                    $service->setEventManager($sm->get('phoenix-eventmanager'));
                    $service->setConfig($sm->get('MergedConfig'));
                    $service->setServiceManager($sm);
                    return $service;
                },
                //The ParseTags Filter
                'phoenix-filters-parsetags' => function($sm) {
                    $service = new \Toolbox\Filter\ParseTags($sm->get('MergedConfig'), $sm);
                    return $service;
                },
                //The Page Filter
                'phoenix-filters-page' => function ($sm) {
                    $service = new \Toolbox\Filter\Tag\Page($sm->get('MergedConfig'));
                    return $service;
                },
                //The LINK Filter
                'phoenix-filters-link' => function ($sm) {
                    $service = new \Toolbox\Filter\Tag\Link($sm->get('MergedConfig'));
                    return $service;
                },
                'phoenix' => function($sm) {
                    $service = new Phoenix();
                    return $service;
                },
                'phoenix-service-urls' => function($sm) {
                    $service = new \Toolbox\Service\Urls();
                    $service->setDefaultEntityManager($sm->get('doctrine.entitymanager.orm_default'));
                    $service->setAdminEntityManager($sm->get('doctrine.entitymanager.orm_admin'));
                    $service->setEventManager($sm->get('phoenix-eventmanager'));
                },
                'DoctrineORMModule\Form\Annotation\AnnotationBuilder' => function(Zend\ServiceManager\ServiceLocatorInterface $sl) {
                    return new DoctrineORMModule\Form\Annotation\AnnotationBuilder($sl->get('doctrine.entitymanager.orm_admin'));
                },
                'phoenix-modules' => function ($sm) {
                    $service = new Service\Modules();
                    $service->setCurrentUser($sm->get('phoenix-users-current'));
                    $service->setDefaultEntityManager($sm->get('doctrine.entitymanager.orm_default'));
                    $service->setAdminEntityManager($sm->get('doctrine.entitymanager.orm_admin'));
                    $service->setEventManager($sm->get('phoenix-eventmanager'));
                    $service->setConfig($sm->get('MergedConfig'));
                    $service->setServiceManager($sm);
                    return $service;
                },                        
            )
        );
    }

    /**
     * getViewHelperConfig
     *
     * Lets the view models pull params from the POST/GET
     *
     * @return object
     */
    public function getViewHelperConfig()
    {
        return array(
            'factories' => array(
                'Params' => function ($sm) {
                    $serviceManager = $sm->getServiceLocator();
                    $request = $serviceManager->get('Request');
                    $helper = new \Toolbox\Helper\Params($request);
                    return $helper;
                },
            )
        );
    }

    /**
     * REMOVE BEFORE LIVE:
     * THIS IS JUST A QUICK AND DIRTY WAY TO UPDATE THE DATABASE WITH THE PERMISSIONS AND GROUPS TABLES
     * WE NEED A REAL SOLUTION BEFORE 13.4 CAN LAUNCH!
     * @return void
     */
    public function updateDatabase($e)
    {
        $serviceManager = $e->getApplication()->getServiceManager();
        $defaultEntityManager = $serviceManager->get('doctrine.entitymanager.orm_default');
        $adminEntityManager = $serviceManager->get('doctrine.entitymanager.orm_admin');

        $permissionsSql = "
            CREATE TABLE IF NOT EXISTS `permissions` (
              `permissionId` int(11) NOT NULL AUTO_INCREMENT,
              `name` varchar(255) NOT NULL,
              `groupId` int(11) NOT NULL DEFAULT '0',
              `authLevel` varchar(50) NOT NULL,
              `created` datetime NOT NULL,
              `modified` datetime NOT NULL,
              PRIMARY KEY (`permissionId`)
            ) ENGINE=MyISAM  DEFAULT CHARSET=utf8";

        $permissionsUpdateSql = "
            ALTER TABLE  `permissions`
                ADD  `groupId` INT( 11 ) NOT NULL DEFAULT  '0' AFTER  `name` ,
                ADD  `authLevel` VARCHAR(50) NOT NULL AFTER  `groupId`,
                ADD `area` VARCHAR(50) NOT NULL AFTER `authLevel`;";

        $permissionsUpdateSql2 = "
            ALTER TABLE  `permissions`
                ADD `area` VARCHAR(50) NOT NULL AFTER `authLevel`;";

        $groupsSql = "
            CREATE TABLE IF NOT EXISTS `groups` (
                `groupId` int(11) NOT NULL AUTO_INCREMENT,
                `name` varchar(255) NOT NULL,
                `created` datetime NOT NULL,
                `modified` datetime NOT NULL,
                PRIMARY KEY (`groupId`)
            ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;";

        $usersGroupsSql = "
            CREATE TABLE IF NOT EXISTS `users_groups` (
                `ugId` int(11) NOT NULL AUTO_INCREMENT,
                `userId` int(11) NOT NULL,
                `groupId` int(11) NOT NULL,
                PRIMARY KEY (`ugId`)
            ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0 ;";

        $defaultEntityManager->getConnection()->executeUpdate($permissionsSql);
        $adminEntityManager->getConnection()->executeUpdate($groupsSql);
        $adminEntityManager->getConnection()->executeUpdate($usersGroupsSql);

        $permissionsCheck = $defaultEntityManager->getConnection()->fetchAll('desc permissions');

        $runPermsQuery1 = true;
        $runPermsQuery2 = true;
        if (!is_null($permissionsCheck)) {
            foreach ($permissionsCheck as $valCheck) {
                if ('groupId' == $valCheck['Field'] || 'authLevel' == $valCheck['Field']) {
                    $runPermsQuery1 = false;
                    continue;
                }
                if ('area' == $valCheck['Field']) {
                    $runPermsQuery2 = false;
                    continue;
                }
            }
        }

        if ($runPermsQuery1) {
            $defaultEntityManager->getConnection()->executeUpdate($permissionsUpdateSql);
        }

        if ($runPermsQuery2) {
            $defaultEntityManager->getConnection()->executeUpdate($permissionsUpdateSql2);
        }
    }

    /**
     * setDatabaseConnFromIniSettings
     *
     * Pulls the database connection settings from iniSettings
     * and puts them into the Doctrine connection settings array
     *
     * @param \Zend\ServiceManager\ServiceManager $serviceManager
     *
     */
    public function setDatabaseConnFromIniSettings($serviceManager)
    {
        //Extract the iniSettings from the ApplicationConfig array
        $appSettings = $serviceManager->get('ApplicationConfig');
        $iniSettings = $appSettings['iniSettings'];

        //Get the Db settings arrays
        $siteDb = $iniSettings['database'];
        $adminDb = $iniSettings['adminDatabase'];

        //Get the Merged Module config array
        $config = $serviceManager->get('Config');

        //Get the doctrine connection arrays, or set them to empty arrays if they aren't set.
        $siteConn = (isset($config['doctrine']['connection']['orm_default'])) ? $config['doctrine']['connection']['orm_default'] : array();
        $adminConn = (isset($config['doctrine']['connection']['orm_admin'])) ? $config['doctrine']['connection']['orm_admin'] : array();

        $serviceManager->setAllowOverride(true);

        //Insert the db connection settings into the doctrine arrays
        $config['doctrine']['connection']['orm_default'] = $this->setDbParamsFromIni($siteConn, $siteDb);
        $config['doctrine']['connection']['orm_admin'] = $this->setDbParamsFromIni($adminConn, $adminDb);

        //Save the updated values to the Config array
        $serviceManager->setService('Config', $config);
        $serviceManager->setAllowOverride(false);
    }

    /**
     * legacyFallback
     *
     * If we've reached this point, and Phoenix has not found a controller,let's throw it to
     * the Legacy Controller.
     *
     * @param  MvcEvent $e
     * @return void
     */
    public function legacyFallback(MvcEvent $e)
    {
        $routeMatch       = $e->getRouteMatch();
        $controllerName   = $routeMatch->getParam('controller', 'not-found');
        $application      = $e->getApplication();
        $events           = $application->getEventManager();
        $controllerLoader = $application->getServiceManager()->get('ControllerLoader');

        if (!$controllerLoader->has($controllerName)) {
            $routeMatch->setParam('controller', 'Toolbox\Controller\Legacy');
        }
    }

    /**
     * getLocalCxml
     *
     * Retrieves the LocalCxml and prepares it for use by Phoenix
     * This uses a legacy Adapter inside of Phoenix, but LocalCxml is (hopefully) a legacy concept.
     * Even if it is going to be with us for a long time.
     *
     * @param  MvcEvent $e
     * @return void
     */
    public function getLocalCxml(MvcEvent $e)
    {
        //Get the Route Match Object
        $routeMatch = $e->getRouteMatch();

        //Get isLegacy
        $isLegacy = $e->getParam('isLegacy');

        // REMOVE BEFORE LIVE:
        // print_r('<pre>');
        // print_r($routeMatch);
        // print_r('</pre>');
        // die('FILE: ' . __FILE__ . ' LINE: ' . __LINE__);

        //Set the location value, which is used by the LocalCxml Adapter
        $location = $routeMatch->getParam('location', $routeMatch->getParam('subsite'));

        //If this is a toolbox action, set the location to toolbox
        if (strpos($routeMatch->getParam('controller'), 'Controller\Toolbox')) {
            if ($location) {
                $location .= '/toolbox/tools';
            } else {
                $location .= 'toolbox/tools';
            }
        }

        //Retrieve the page parameter
        $page = $routeMatch->getParam('page', $routeMatch->getParam('action'));

        //Artificially create the page parameter if this is not a Legacy Page
        if (!$e->getParam('isLegacy')) {
            $controllerArray = explode('\\', $routeMatch->getParam('controller'));
            $page = lcfirst($controllerArray[0]);
        }

        //Get the Service Manager
        $serviceManager = $e->getApplication()->getServiceManager();

        //Get the Legacy Cxml Adapter
        $cxml = $serviceManager->get('phoenix-legacy-cxml');

        // print_r('<pre>');
        // print_r($e->getRouteMatch());
        // print_r('</pre>');
        // die('FILE: ' . __FILE__ . ' LINE: ' . __LINE__);

        //Get the Phoenix Object
        $phoenix = $serviceManager->get('phoenix');

        //Retrieve and parse the LocalCxml
        $localCxml = $cxml->getLocalCxml($location, $page, true, $e->getParam('cxmlIgnore'));

        //Add the LocalCxml to our Service Manager
        $serviceManager->setService('LocalCxml', $localCxml);

        //Get the config array
        //Moved this down here AFTER the localCxml is run. Otherwise, this breaks Toolbox
        //@todo Begin mergingconfig BEFORE localCxml (and merge as we go), so we are using one object
        //instead of multiple arrays.
        //A. Tate 06/24/2013
        $config = $serviceManager->get('Config');

        //Set the LegacyPathway setting in the Config array.
        $serviceManager->setAllowOverride(true);
        $config['legacyPathway'] = $cxml->getPathway();
        $serviceManager->setService('Config', $config);
        $serviceManager->setAllowOverride(false);
    }

    /**
     * preDispatchInit
     *
     * Run the Phoenix preDispatch initialization
     *
     * @param  MvcEvent $e
     * @return boolean
     */
    public function preDispatchInit(MvcEvent $e)
    {
        $phoenix = new Phoenix();

        return $phoenix->init($e);
    }

    public function normalizeDataSections(MvcEvent $e)
    {
        $mergedConfig = $e->getApplication()->getServiceManager()->get('MergedConfig');

        $rootDataSection = '';
        $dataSection = '';

        $dataSectionConfig = $mergedConfig->get('dataSection', '');

        if ($dataSectionConfig && is_array($dataSectionConfig)) {
            if (isset($dataSectionConfig['value'])) {
                $dataSection = $dataSectionConfig['value'];
            }
        }

        $legacyPathwayAreas = $mergedConfig->get(array('legacyPathway', 'areas'), array());

        foreach ($legacyPathwayAreas as $valArea) {
            if ($valArea['xml']->areaKey == 'root') {
                $rootDataSection = (string) $valArea['xml']->dataSection;
                break;
            } else {
                continue;
            }
        }

        if ($dataSection) {
            $mergedConfig->set('dataSection', $dataSection);
        }

        if ($rootDataSection) {
            $mergedConfig->set('rootDataSection', $rootDataSection);
        }
    }

    /**
     * setLayout
     *
     * Sets the layout to the toolbox layout if this is a toolbox request
     *
     * @param MvcEvent $e
     * @return void
     */
    public function setLayout(MvcEvent $e)
    {   
        $routeMatch = $e->getRouteMatch();

        $controller = $routeMatch->getParam('controller');

        if (strpos($controller, 'Controller\Toolbox') > 0) {
            $e->getViewModel()->setTemplate('toolbox-layout');
        }
    }

    public function disableLayout(MvcEvent $e)
    {
        // $result = $e->getResult();

        // if ($result instanceof \Zend\View\Model\ViewModel) {
        //     var_dump($result);
        //     die('FILE: ' . __FILE__ . ' LINE: ' . __LINE__);
        // }
    }

    /**
     * addPagePaths
     *
     * This adds additional paths to our paths array. Primarily used by Legacy requests,
     * but also may be applicable to Phoenix
     *
     * @param MvcEvent $e
     */
    public function addPagePaths(MvcEvent $e)
    {
        //Get the Service Manager
        $serviceManager = $e->getApplication()->getServiceManager();

        //Get the Config Manager and our MergedConfig
        $configManager = $serviceManager->get('phoenix-configmanager');
        $mergedConfigModel = $serviceManager->get('MergedConfig');
        $mergedConfig = $mergedConfigModel->getMergedConfig();

        // print_r('<pre>');
        // print_r($serviceManager->get(''));
        // print_r('</pre>');
        // die('FILE: ' . __FILE__ . ' LINE: ' . __LINE__);

        //Get our current Paths array and initialize our $pagePaths array
        $paths = $mergedConfig['paths'];
        $pagePaths = array();

        //Set our default template sets
        $adminTemplateSet = 'toolbox';

        $defaultTemplateSet = 'main';
        $defaultAdminTemplateSet = 'toolbox';

        $templateSet = $mergedConfigModel->get('templateSet', $defaultTemplateSet);
        $adminTemplateSet = $mergedConfigModel->get('adminTemplateSet', $defaultAdminTemplateSet);

        if (!is_string($templateSet)) {
            $templateSet = $defaultTemplateSet;
            $mergedConfigModel->set('templateSet', $templateSet);
        }

        if (!is_string($adminTemplateSet)) {
            $adminTemplateSet = $defaultAdminTemplateSet;
            $mergedConfigModel->set('adminTemplateSet', $templateSet);
        }

        //Define the local templates directory and HREF constants
        if (!defined('CONDOR_LOCAL_TEMPLATES_DIR')) {
            define('CONDOR_LOCAL_TEMPLATES_DIR', SITE_PATH . 'templates/');
            define('CONDOR_LOCAL_TEMPLATES_HREF', $paths['siteBaseHREF'] . 'templates/');
        }

        //Allow the all templates to be somewhere outside the condor directory if needed
        if (!defined('CONDOR_TEMPLATES_DIR'))
        {
            define('CONDOR_TEMPLATES_DIR', $paths['condorBaseDir'] . 'templates/');
            define('CONDOR_TEMPLATES_HREF', $paths['condorBaseHREF'] . 'templates/');
        }

        //Set the base template dir
        if (defined('CONDOR_LOCAL_TEMPLATES_DIR') && is_dir(CONDOR_LOCAL_TEMPLATES_DIR . $templateSet)) {
            $paths['templateBaseDir'] = CONDOR_LOCAL_TEMPLATES_DIR . $templateSet . '/';
            $paths['templateBaseHREF'] = CONDOR_LOCAL_TEMPLATES_HREF . $templateSet;
        } elseif (is_dir(CONDOR_TEMPLATES_DIR . $templateSet)) {
            $paths['templateBaseDir'] = CONDOR_TEMPLATES_DIR . $templateSet . '/';
            $paths['templateBaseHREF'] =  CONDOR_TEMPLATES_HREF . $templateSet;
        } elseif (!$e->getParam('isLegacy')) {
            trigger_error('Template Set not found. Template Set:' . $templateSet, E_USER_ERROR);
        }

        if (isset($paths['templateBaseHREF'])) {
          $paths['templateBaseHREFNoEdge'] = $paths['templateBaseHREF'];
        }

        //@todo: add code to set edgecast HREF

        //Set up the admin templates directory
        $paths['adminTemplateBaseDir'] = CONDOR_TEMPLATES_DIR  . $adminTemplateSet . '/';
        $paths['adminTemplateBaseHREF'] = CONDOR_TEMPLATES_HREF . $adminTemplateSet;

        $mergedConfigModel->set('paths', $paths);
        $mergedConfigModel->set('adminTemplateBaseHREF', $paths['adminTemplateBaseHREF']);
    }

    /**
     * attachParseTags
     *
     * Attach the ParseTags filter to the ViewRenderer's filterChain
     *
     * @param  MvcEvent $e
     * @return void
     */
    public function attachParseTags(MvcEvent $e)
    {
        $serviceManager = $e->getApplication()->getServiceManager();

        $viewManager    = $serviceManager->get('view-manager');
        $filterChain    = $viewManager->getRenderer()->getFilterChain();
        $filterObject   = $serviceManager->get('phoenix-filters-parsetags');

        $filterChain->attach(array($filterObject, 'filter'));

        // data collector for parsing 'data-src' attributes from the page
        // and collect data accordingly [IS 2013-11-22]
        $filterChain->attach(array(new \Toolbox\Filter\DataCollector($serviceManager), 'collectData'));
    }

    /**
     * handleLegacySockets
     *
     * This creates a Legacy URL that allows sockets to work when called from Phoenix pages
     * @param  MvcEvent $e
     * @return void
     */
    public function handleLegacySockets(MvcEvent $e)
    {
        //Get the Service Manager
        $serviceManager = $e->getApplication()->getServiceManager();

        //Get the Request
        $request = $e->getRequest();

        //Get the ViewManager and ViewModel
        //Note that the ViewModel is the one for the overall layout, and not the action or module.
        $viewManager = $serviceManager->get('view-manager');
        $viewModel = $viewManager->getViewModel();

        //Get the Route Match Object
        $routeMatch = $e->getRouteMatch();

        //Retrieve the Subsite Parameter
        $subsite = $routeMatch->getParam('subsite');
        $subsiteWithPath = '/';

        //Let's make sure we have all the right slashes
        if ($subsite) {
            $subsiteWithPath .= $subsite . '/';
        }

        //Get the Uri so we can build this url
        $uri = $request->getUri();

        //Build the URL and attach it to the ViewModel
        $viewModel->phoenixSocketUrl = $uri->getScheme() . '://' . $uri->getHost() . $request->getBaseUrl() . $subsiteWithPath . 'toolbox/default-en.html';
    }

    /**
     * setDbParamsFromIni
     *
     * Insert the db connection settings into the given doctrineArray
     * If the iniArray doesn't have a value, we'll just keep the doctrineArray default
     *
     * @param array $doctrineArray
     * @param array $iniArray
     *
     * @return array
     */
    protected function setDbParamsFromIni($doctrineArray, $iniArray)
    {
        //Set the db host
        if (isset($iniArray['host']) && $iniArray['host'] != '') {
            $doctrineArray['params']['host'] = $iniArray['host'];
        }

        //Set the port
        if (isset($iniArray['port']) && $iniArray['port'] != '') {
            $doctrineArray['params']['port'] = $iniArray['port'];
        }

        //Set the user
        if (isset($iniArray['user']) && $iniArray['user'] != '') {
            $doctrineArray['params']['user'] = $iniArray['user'];
        }

        //Set the password
        if (isset($iniArray['pass']) && $iniArray['pass'] != '') {
            $doctrineArray['params']['password'] = $iniArray['pass'];
        } else {
            $doctrineArray['params']['password'] = '';
        }

        //Set the db name
        if (isset($iniArray['name']) && $iniArray['name'] != '') {
            $doctrineArray['params']['dbname'] = $iniArray['name'];
        }

        return $doctrineArray;
    }

    /**
     * setInitialIniSettings
     *
     * Uses the iniSettings and does some inital setup with them.
     *
     * @param \Zend\ServiceManager\ServiceManager $serviceManager
     */
    protected function setInitialIniSettings($serviceManager)
    {
        $serviceManager->setAllowOverride(true);
        $legacyCondor = new Legacy\Condor();
        $appSettings = $serviceManager->get('ApplicationConfig');
        $newAppSettings = $legacyCondor->setIniSettings($appSettings);
        $serviceManager->setService('ApplicationConfig', $newAppSettings);
        $serviceManager->setAllowOverride(false);
    }

    /**
     * OnFinish event
     *
     * Currently using it for the saving of content appearance
     * tho this needs to be moved to the Pages module
     *
     * @param  MvcEvent $e
     * @return void
     */
    public function onFinish($e)
    {
        $serviceManager = $e->getApplication()->getServiceManager();

        $defaultManager = $serviceManager->get('doctrine.entitymanager.orm_default');

        $config = $serviceManager->get('MergedConfig');

        if ($config->get('debug', false)) {
           ob_start();
            echo '<h3>Queries</h3>';
            foreach($defaultManager->getConnection()->getConfiguration()->getSQLLogger()->queries as $keyQuery => $valQuery) {
                $queryNumber = $keyQuery;
                echo '<b>Query #' . $queryNumber . '</b><br>';
                $fontColor = 'black';
                if ($valQuery['executionMS'] > 1000) {
                    echo '<h1>SLOW QUERY ALERT</h1>';
                    $fontColor = 'red';
                    echo '<script>alert("SLOW QUERY ALERT");</script>';
                }
                echo "<font color=\"$fontColor\">";
                echo 'Time to Execute (ms): ' . $valQuery['executionMS'] . '<br>';
                echo $valQuery['sql'] . '<br>';
                echo '</font>';
                echo '<hr>';
            }
            echo '<hr><hr>';
           $queryLog = ob_get_contents();
           ob_end_clean();

           $e->getResponse()->setContent($e->getResponse()->getContent() . $queryLog);
        }

        $defaultManager->getConnection()->close();
        $defaultManager->close();

        $adminManager = $serviceManager->get('doctrine.entitymanager.orm_admin');

        $adminManager->getConnection()->close();
        $adminManager->close();

        /**
         * Only if this is a page call, temporary
         */
        if ( $serviceManager->has('currentPage') )
        {
            $currentPage = $serviceManager->get('currentPage');
            //$contentAppearanceService = $serviceManager->get('phoenix-contentappearance');
            //$contentAppearanceService->save($currentPage);
        }
    }
    
     public function setLayoutTitle($e)
    {
        $matches    = $e->getRouteMatch();

        if (empty($matches)) {
            return true;
        }

        $action     = ucfirst($matches->getParam('action'));
        $controller = substr($matches->getParam('controller'),0,strpos($matches->getParam('controller'),'\\'));
        $module     = __NAMESPACE__;

        // Getting the view helper manager from the application service manager
        $viewHelperManager = $e->getApplication()->getServiceManager()->get('viewHelperManager');

        // Getting the headTitle helper from the view helper manager
        $headTitleHelper   = $viewHelperManager->get('headTitle');

        // Setting a separator string for segments
        $headTitleHelper->setSeparator(' - ');

        // Setting the action, controller, module and site name as title segments
        $headTitleHelper->append($action);
        $headTitleHelper->append($controller);
        $headTitleHelper->append($module);
    }
}
