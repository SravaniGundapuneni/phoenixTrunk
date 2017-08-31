<?php
namespace Languages;

use Phoenix\Module\Module as PhoenixModule;

use Toolbox\Phoenix;

use \Zend\Mvc\MvcEvent;

class Module extends PhoenixModule
{
    protected $moduleNamespace = __NAMESPACE__;
    protected $moduleDirectory = __DIR__;

    public function onBootstrap(MvcEvent $e)
    {
        $eventManager = $e->getApplication()->getEventManager();

        $eventManager->attach(MvcEvent::EVENT_ROUTE, array($this, 'initLanguages'), -750);
        $eventManager->attach(MvcEvent::EVENT_ROUTE, array($this, 'setDefaultLanguage'), -4);
        $eventManager->attach(MvcEvent::EVENT_ROUTE, array($this, 'checkForLanguageCode'), 5);
        //$eventManager->attach(MvcEvent::EVENT_ROUTE, array($this, 'convertDlms'), 15);
        $eventManager->attach(MvcEvent::EVENT_DISPATCH, array($this, 'addModuleMenu'), 10);
        $eventManager->attach(MvcEvent::EVENT_DISPATCH, array($this, 'setLayout'), 100);        

        /**
         * Module Migrations Support
         */
        $this->updateTableStructures($e);
    }

    public function setDefaultLanguage(MvcEvent $e)
    {
        $serviceManager = $e->getApplication()->getServiceManager();

        $languages = $serviceManager->get('phoenix-languages');

        $languages->getLanguageOptions();        
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

    public function convertDlms(MvcEvent $e)
    {
        set_time_limit(3000);


        $serviceManager = $e->getApplication()->getServiceManager();

        $config = $serviceManager->get('Config');

        $dontRunConvert = (empty($config['dlm_conversion'])) ? false : $config['dlm_conversion'];

        if (!$dontRunConvert) {
            return;
        }

        $entityManager = $serviceManager->get('doctrine.entitymanager.orm_default');

        $entityManager->getConnection()->executeUpdate("set names utf8;");

        $queryModules = "select * from dynamicListModules_old";

        $resultModules = $entityManager->getConnection()->query($queryModules)->fetchAll();

        foreach ($resultModules as $valResult) {
            $component = new \Toolbox\Entity\Components();

            // $component->setId($valResult['moduleId']);
            $component->setName($valResult['name']);
            $component->setDescription($valResult['description']);
            $component->setDynamic(1);
            $component->setCategories($valResult['categories']);
            $component->setcreatedUserId($valResult['userId']);
            $component->setCreated(new \DateTime($valResult['created']));
            $component->setModifiedUserId($valResult['userId']);
            $component->setModified(new \DateTime($valResult['modified']));
            $component->setStatus($valResult['status']);

            $entityManager->persist($component);

            $componentId = $component->getId();

            $oldComponentId = $valResult['moduleId'];

            $queryFields = "select * from dynamicListModule_fields_old where module = $oldComponentId";

            $resultFields = $entityManager->getConnection()->query($queryFields)->fetchAll();

            $newFields = array();

            foreach ($resultFields as $valField) {
                $field = new \Toolbox\Entity\ComponentFields();

                $field->setComponent($component);
                $field->setName($valField['name']);
                $field->setLabel($valField['displayName']);
                $field->setTranslate(1);
                $field->setType($valField['type']);
                $field->setShowInList($valField['showInList']);
                $field->setOrderNumber($valField['orderNumber']);
                $field->setCreatedUserId($valField['userId']);
                $field->setCreated(new \DateTime($valField['created']));
                $field->setModifiedUserId($valField['userId']);
                $field->setModified(new \DateTime($valField['modified']));
                $field->setStatus($valField['status']);

                $entityManager->persist($field);
                $entityManager->flush();

                $newFields[$valField['fieldId']] = $field;

                $querySelectValues = "select * from dynamicListModule_selectValues_old where field = {$valField['fieldId']}";

                $resultSelectValues = $entityManager->getConnection()->query($querySelectValues)->fetchAll();

                if (!empty($resultSelectValues)) {
                    foreach ($resultSelectValues as $valSelect) {
                        $selectValue = new \DynamicListModule\Entity\DynamicListModuleSelectValues();
                        $selectValue->setName($valSelect['name']);
                        $selectValue->setField($field->getId());

                        $entityManager->persist($selectValue);
                    }
                }
            }

            $queryItems = "select * from dynamicListModule_items_old where module = $oldComponentId";

            $resultItems = $entityManager->getConnection()->query($queryItems)->fetchAll();

            $orderNumber = 1;
            foreach ($resultItems as $valItem) {
                $item = new \DynamicListModule\Entity\DynamicListModuleItems();

                $item->setAllProperties($valItem['allProperties']);
                $item->setComponent($component);

                $parentModuleName = str_replace(' ','-',lcfirst($component->getName()));

                if (!empty($valItem['property'])) {
                    $property = $entityManager->getRepository('PhoenixProperties\Entity\PhoenixProperty')->findOneBy(array('id' => $valItem['property']));
                    $item->setProperty($property);
                }

                $item->setCategoryId($valItem['categoryId']);
                $field->setCreatedUserId($valItem['userId']);
                $field->setModifiedUserId($valItem['userId']);
                $item->setCreated(new \DateTime($valItem['created']));
                $item->setModified(new \DateTime($valItem['modified']));
                $item->setStatus($valItem['status']);
                $item->setOrderNumber($orderNumber);
                $orderNumber++;

                $entityManager->persist($item);
                $entityManager->flush();

                $queryItemFields = "select * from dynamicListModule_itemFields_old where item = {$valItem['itemId']}";

                $resultItemFields = $entityManager->getConnection()->query($queryItemFields)->fetchAll();

                foreach ($resultItemFields as $valItemField) {
                    $queryInsert = "insert into dynamicListModule_itemFields (item, field, value, modifiedUserId, createdUserId, created, modified, status) 
                                    values (
                                        {$item->getId()}, 
                                        {$newFields[$valItemField['field']]->getId()}, 
                                        :value,
                                        {$valItemField['userId']},
                                        {$valItemField['userId']},                                        
                                        '{$valItemField['created']}',
                                        '{$valItemField['modified']}',
                                        {$valItemField['status']})";
                    $entityManager->getConnection()->executeUpdate($queryInsert, array(':value' => $valItemField['value']));

                    // $itemField = new \DynamicListModule\Entity\DynamicListModuleItemFields();
                    // $itemField->setItem($item);
                    // $itemField->setField($newFields[$valItemField['field']]);
                    // $itemField->setValue($valItemField['value']);
                    // $itemField->setUserId($valItemField['userId']);
                    // $itemField->setCreated(new \DateTime($valItemField['created']));
                    // $itemField->setModified(new \DateTime($valItemField['modified']));
                    // $itemField->setStatus($valItemField['status']);

                    // $entityManager->persist($itemField);
                }

                $queryItemAttachments = "update mediaManagerFileAttachments set parentItemId = {$item->getId()} where parentItemId = {$valItem['itemId']} and parentModule = '{$parentModuleName}'";
                $entityManager->getConnection()->executeUpdate($queryItemAttachments);
            }
        }

        $entityManager->flush();          
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'phoenix-polytext' => function($sm) {
                    $service = new Service\Polytexts();
                    $service->setCurrentUser($sm->get('phoenix-users-current'));
                    $service->setDefaultEntityManager($sm->get('doctrine.entitymanager.orm_default'));
                    $service->setAdminEntityManager($sm->get('doctrine.entitymanager.orm_admin'));
                    $service->setEventManager($sm->get('phoenix-eventmanager'));
                    $service->setConfig($sm->get('MergedConfig'));
                    $service->setServiceManager($sm);
                    return $service;
                },
                'phoenix-languages' => function($sm) {
                    $service = new Service\Languages();
                    $service->setDefaultEntityManager($sm->get('doctrine.entitymanager.orm_default'));
                    $service->setAdminEntityManager($sm->get('doctrine.entitymanager.orm_admin'));
                    $service->setCurrentUser($sm->get('phoenix-users-current'));
                    $service->setEventManager($sm->get('phoenix-eventmanager'));
                    $service->setConfig($sm->get('MergedConfig'));
                    $service->setServiceManager($sm);
                    return $service;
                },
                'phoenix-languages-property' => function($sm) {
                    $service = new Service\PropertyLanguages();
                    $service->setDefaultEntityManager($sm->get('doctrine.entitymanager.orm_default'));
                    $service->setAdminEntityManager($sm->get('doctrine.entitymanager.orm_admin'));
                    $service->setCurrentUser($sm->get('phoenix-users-current'));
                    $service->setEventManager($sm->get('phoenix-eventmanager'));
                    $service->setConfig($sm->get('MergedConfig'));
                    $service->setServiceManager($sm);
                    return $service;
                },
                'phoenix-languages-mapper-xls' => function($sm) {
                    $mapper = new Mapper\Xls();
                    $mapper->setDefaultEntityManager($sm->get('doctrine.entitymanager.orm_default'));
                    $mapper->setAdminEntityManager($sm->get('doctrine.entitymanager.orm_admin'));
                    $mapper->setCurrentUser($sm->get('phoenix-users-current'));
                    $mapper->setEventManager($sm->get('phoenix-eventmanager'));
                    $mapper->setConfig($sm->get('MergedConfig'));
                    $mapper->setCurrentLanguage($sm->get('CurrentLanguage'));               
                    $mapper->setServiceManager($sm);
                    return $mapper;                    
                },
                //Placeholder so this is always available and won't crap out an early service.
                'currentLanguage' => function ($sm) {
                    $service = new Model\Language(array());
                    return $service;
                },
                'phoenix-filters-text' => function ($sm) {
                    $viewManager = $sm->get('view-manager');
                    $mergedConfig = $sm->get('MergedConfig');
                    $phoenixPolytext = $sm->get('phoenix-polytext');
                    $viewRenderer = $viewManager->getRenderer();

                    $service = new \Languages\Filter\Tag\Text(
                        $mergedConfig,
                        $phoenixPolytext,
                        $viewRenderer
                    );

                    return $service;
                },
            )
        );
    }

    /**
     * checkForLanguage
     *
     * Checks within the route to see if a language is set in the route. This only works for routes of the Phoenix format
     *
     * site.com/fr/rooms
     *
     * Routes using the format
     *
     * site.com/rooms-fr.html (or the alternative site.com/rooms-fr)
     *
     * Will be handled during the page Segment router.
     *
     * The bulk of the language setup is in initLanguages, but this will pull the language out of the route so it doesn't affect
     * the subsite calculation.
     * 
     * @param  MvcEvent $e
     * @return void
     */
    public function checkForLanguageCode($e)
    {
        //Check to see if a language code is included 
        $request = $e->getRequest();

        $serviceManager = $e->getApplication()->getServiceManager();

        $site = $serviceManager->get('phoenix-site-model');

        $basePath = $request->getUri()->getPath();

        $basePathParts = explode('/', $basePath);

        $defaultEntityManager = $serviceManager->get('doctrine.entitymanager.orm_default');
        $adminEntityManager = $serviceManager->get('doctrine.entitymanager.orm_admin');

        //We want to check all possible codes, whether or not they are valid on this site.
        $possibleCodesResult = $adminEntityManager->getRepository('Languages\Entity\Admin\Languages')->findAll();

        $possibleCodes = array();

        foreach ($possibleCodesResult as $valResult) {
            $possibleCodes[] = $valResult->getCode();
        }

        $mergedConfig = new \Config\Model\MergedConfig($serviceManager->get('Config'));

        $defaultLanguage = $defaultEntityManager->getRepository('Languages\Entity\Languages')->findOneByCode($mergedConfig->get('defaultLanguage', Phoenix::TOOLBOX_LANGUAGE_CODE));
        
        if (empty($defaultLanguage)) {
            $defaultLanguage = $defaultEntityManager->getRepository('Languages\Entity\Languages')->findOneByDefault(1);
        }

        if (!empty($defaultLanguage)) {
            $languageCode = $defaultLanguage->getCode();
        } else {
            $languageCode = 'en';
        }

        $addSlash = false;

        $arrayCount = count($basePathParts);

        $fromRoute = false;

        foreach ($basePathParts as $keyPart => $valPart) {
            if ($valPart == '') {
                unset($basePathParts[$keyPart]);
                continue;
            }

            if (in_array($valPart, $possibleCodes)) {
                $fromRoute = true;
                $languageCode = $valPart;

                if ($keyPart == $arrayCount - 1) {
                    $addSlash = true;
                }

                unset($basePathParts[$keyPart]);
                //We take the first language code encountered. All others are considered void and part of the regular uri path.
                break;
            }
        }

        // $site->setLangCodeFromRoute($fromRoute);
        $site->setLanguageCode($languageCode);

        if (count($basePathParts) > 1) {
            $newBasePath = '/' . implode('/', $basePathParts);
            if (substr($basePath, -1) == '/') {
                $newBasePath .= '/';
            }
        } elseif (!empty($basePathParts)) {
            $newBasePath = '/' . reset($basePathParts);

            if (substr($basePath, -1) == '/') {
                $newBasePath .= '/';
            }
        } else {
            $newBasePath = '/';
        }

        if ($addSlash && substr($newBasePath, -1) != '/') {
            $newBasePath .= '/';
        }

        $request->getUri()->setPath($newBasePath);
    }

    public function initLanguages($e)
    {
        $routeMatch = $e->getRouteMatch();
        $serviceManager = $e->getApplication()->getServiceManager();
        $mergedConfig = $serviceManager->get('MergedConfig');

        $languages = $serviceManager->get('phoenix-languages');

        $propertyLanguages = $serviceManager->get('phoenix-languages-property');

        $siteModel = $serviceManager->get('phoenix-site-model');

        $matchedRouteName = strtolower($routeMatch->getMatchedRouteName());

        //
        if (strpos($matchedRouteName, 'toolbox') !== false || strpos($matchedRouteName, 'sockets') !== false) {
            $languageCode = $mergedConfig->get(array('languages', 'toolboxDefaultCode'), Phoenix::TOOLBOX_LANGUAGE_CODE);
        } else {
            // if ($siteModel->getLanguageCodeFromRoute) {
                $languageCode = $siteModel->getLanguageCode();
            // }
        }

        $currentLanguage = $languages->getItemBy(array('code' => $languageCode, 'status' => 1));

        if (empty($currentLanguage)) {
            $currentLanguage = $languages->getItemBy(array('default' => 1));
        }

        if (empty($currentLanguage)) {
            return;
        }

        $currentLanguage->setConfig($mergedConfig);

        $serviceManager->setAllowOverride(true);
        $serviceManager->setService('currentLanguage', $currentLanguage);
        $serviceManager->setAllowOverride(false);

        $viewManager = $serviceManager->get('view-manager');
        //Check to see if we're coming here through the error track.
        //We need to use a different viewModel if so.
        if ($e->getError()) {
            $viewModel = $e->getResult();
        } else {
            $viewModel = $viewManager->getViewModel();
        }

        $viewModel->languageOptions = $languages->getLanguagesSelect();

        /**
         * For legacy pages, we'll let the legacy Languages handle things
         * This is not part of phoenix anylonger but lets keep the logic for now
         */
        if (strpos($routeMatch->getParam('controller'), 'Controller\Legacy') === false)
        {
            $polytext = $serviceManager->get('phoenix-polytext');
            $polytext->setConfig($mergedConfig);
            $polytext->init($routeMatch->getParam('langCode'));
        }
    }

    public function addModuleMenu($e)
    {
        $serviceManager = $e->getApplication()->getServiceManager();
        $routeMatch = $e->getRouteMatch();

        if ($routeMatch->getMatchedRouteName() == 'languages-toolbox' 
            || $routeMatch->getMatchedRouteName() == 'languages-properties-toolbox') {
            $viewManager = $serviceManager->get('view-manager');
            $viewModel = $viewManager->getViewModel();

            $viewModel->moduleMenuTemplate = 'layout/languages/module-menu';
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

        if (strpos($controller, 'Controller\PropertiesToolbox') > 0 ) {
            $e->getViewModel()->setTemplate('toolbox-layout');
        }
    }

    // public function getViewHelperConfig()
    // {
    //     return array(
    //         'factories' => array(
    //             'Translate' => function($sm) {
    //                 $helper = new \Languages\Helper\Translate();
    //                 return $helper;
    //             },
    //         )
    //     );
    // }    
}
