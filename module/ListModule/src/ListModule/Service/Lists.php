<?php

/**
 * The Lists Service File
 *
 * This file contains the Lists Class, which is the base class for all modules that are 
 * built off of this module. 
 *
 * This should be the base for all of our listModules. It SHOULD NOT become a catch all for 
 * our modules and become unwieldy. This exists as a way to minimize duplicated code, of which the CRUD
 * of list modules tend to have a lot.
 *
 * @category    Toolbox
 * @package     ListModule
 * @subpackage  Service
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.4
 * @since       File available since release 13.4
 * @author      A. Tate <atate@travelclick.com>
 * @filesource
 */

namespace ListModule\Service;

use Phoenix\StdLib\ArrayHelper;
use \ListModule\Model\ListItem;
use Zend\Mail\Message;
use Phoenix\EventManager\Event as LanguageEvent;
use Zend\Stdlib\ArrayObject;
use Doctrine\ORM\ORMInvalidArgumentException;

/**
 * The Lists Service Class
 *
 * This should be the base for all of our listModules. It SHOULD NOT become a catch all for 
 * our modules and become unwieldy. This exists as a way to minimize duplicated code, of which the CRUD
 * of list modules tend to have a lot.
 *
 * @category    Toolbox
 * @package     ListModule
 * @subpackage  Service
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.4
 * @since       File available since release 13.4
 * @author      A. Tate <atate@travelclick.com>
 */
abstract class Lists extends \Phoenix\Service\ServiceAbstract
{

    protected $allowCas = false;
    protected $entityName;
    protected $modelClass;
    protected $translateFields;
    protected $languagesEntity = 'Languages\Entity\LanguageTranslations';
    protected $module;
    protected $orderBy = array();
    protected $detachEntities = array();
    protected $orderList = false;
    protected $dontDetach = false;

    /**
     * The Current Logedin User
     * @var Users\Model\User
     */
    protected $currentUser;

    /**
     * setCurrentUser
     *
     * Setter for $this->currentUser
     *
     * @access public
     * @param Users\Model\User $currentUser
     */
    public function setCurrentUser($currentUser)
    {
        $this->currentUser = $currentUser;
    }

    /**
     * getCurrentUser
     * 
     * @return Users\Model\User $currentUser;
     */
    public function getCurrentUser()
    {
        return $this->currentUser;
    }

    /**
     * getItemFormOptions function
     *
     * Setter for $this->currentUser
     *
     * @access public
     * @param mixed $currentUser
     * @param mixed $options
     * @param mixed $mergedConfig
     * @return mixed $result
     */
    public function getItemFormOptions($itemForm, $options, $mergedConfig)
    {
        $config = array('toolbox', 'ListModule', 'editItemOptions');

        $result = $options = array_intersect($options, $mergedConfig->get($config, $options));

        if ($mergedConfig = $this->getConfig()) {
            $itemFormClass = get_class($itemForm);

            $moduleNamespace = current(explode('\\', $itemFormClass));
            $moduleFields = $mergedConfig->get(array('moduleFields', $moduleNamespace), array());
            $result = \Phoenix\StdLib\ArrayHelper::getValueFromArray($moduleFields, 'itemFormOptions');
            $result = \Phoenix\StdLib\ArrayHelper::getValueFromArray($result, $itemFormClass, array());

            $result = count($result) ? array_intersect($options, $result) : $options;
        }

        return $result;
    }

    /**
     * getFieldsBindParameters function
     *
     * Setter for $this->currentUser
     *
     * @access public
     * @param mixed $itemFormClass
     * @return mixed $result
     */
    public function getFieldsBindParameters($itemFormClass)
    {
        $result = array();

        if ($mergedConfig = $this->getConfig()) {
            $moduleNamespace = current(explode('\\', $itemFormClass));
            $moduleFields = $mergedConfig->get(array('moduleFields', $moduleNamespace), array());
            $result = \Phoenix\StdLib\ArrayHelper::getValueFromArray($moduleFields, 'fieldsBindParameters');
            $result = \Phoenix\StdLib\ArrayHelper::getValueFromArray($result, $itemFormClass, array());
        }

        return $result;
    }

    public function getOrderList()
    {
        return $this->orderList;
    }

    public function setOrderList($orderList)
    {
        $this->orderList = ($orderList) ? true : false;
    }

    public function reorderItems($itemsList)
    {
        $updateQuery = $this->getDefaultEntityManager()->createQueryBuilder();

        $updateQuery->update($this->entityName, 'lm')
                ->set('lm.orderNumber', ":orderNumber")
                ->where('lm.id = :itemId');

        foreach ($itemsList as $keyList => $valList) {
            $updateQuery->setParameter('orderNumber', $valList)
                    ->setParameter('itemId', $keyList);
            $updateQuery->getQuery()->execute();
        }
    }

    public function getMaxOrderNumber()
    {
        $maxOrderQuery = $this->getDefaultEntityManager()->createQueryBuilder();

        $maxOrderQuery->select('lm.orderNumber')
                ->from($this->entityName, 'lm')
                ->orderBy('lm.orderNumber', 'DESC')
                ->setMaxResults(1);



        try {
            $result = $maxOrderQuery->getQuery()->getSingleResult();

            return $result['orderNumber'];
        } catch (\Doctrine\ORM\NoResultException $e) {
            return 0;
        }
    }

    public function emptyTrash()
    {
        $qbDelete = $this->getDefaultEntityManager()->createQueryBuilder();

        $qbDelete->delete($this->entityName, 'lm')
                ->where('lm.status = :status')
                ->setParameter('status', ListItem::ITEM_STATUS_TRASHED);

        $qbDelete->getQuery()->execute();
    }

    /**
     * getItemFormFields function
     *
     * Setter for $this->currentUser
     *
     * @access public
     * @param mixed $itemFormClass
     * @return mixed $result
     */
    public function getItemFormFields($itemFormClass)
    {
        $result = array();

        if ($mergedConfig = $this->getConfig()) {
            $moduleNamespace = current(explode('\\', $itemFormClass));
            $moduleFields = $mergedConfig->get(array('moduleFields', $moduleNamespace), array());
            $result = \Phoenix\StdLib\ArrayHelper::getValueFromArray($moduleFields, 'itemFormFields');
            $result = \Phoenix\StdLib\ArrayHelper::getValueFromArray($result, $itemFormClass, array());
        }
        return $result;
    }

    /**
     * getModuleName
     *
     * Returns the name of the module
     * 
     * @return string
     */
    public function getModuleName()
    {
        return current(explode('\\', get_class($this)));
    }

    public function setLanguageService($languageService)
    {
        $this->languageService = $languageService;
    }

    public function getLanguageService()
    {
        return $this->languageService;
    }

    /**
     * setModule
     * 
     * @param DynamicListModule\Model\Module $module
     */
    public function setModule($module)
    {
        $this->module = $module;
    }

    /**
     * getModule
     * 
     * @codeCoverageIgnore
     * 
     * @return DynamicListModule\Model\Module
     */
    public function getModule()
    {
        return $this->module;
    }

    /**
     * getCategoriesArray
     *
     * Get an array of the categories for this module, in an array for use in select boxes.
     * @return array
     */
    public function getCategoriesArray()
    {
        $moduleName = $this->getModuleName();

        $result = $this->getCategoriesBy(array('status' => ListItem::ITEM_STATUS_PUBLISHED, 'module' => $moduleName));

        $categoriesArray = array();

        if (is_array($result)) {
            foreach ($result as $valCategory) {
                $categoriesArray[$valCategory->getId()] = $valCategory->getName();
            }
        }

        return $categoriesArray;
    }

    public function getCategoriesBy($parameters = array())
    {
        return $this->getDefaultEntityManager()->getRepository('ListModule\Entity\Categories')->findBy($parameters);
    }

    /**
     * setPropertyField
     *
     * @todo  Decouple from core module
     * 
     * @param \Zend\Form\Form $itemForm
     */
    public function setPropertyField($fieldKey = 'property', $itemForm)
    {
        //We don't want to proceed if serviceManager is not on this service.
        if (!is_object($this->getServiceManager())) {
            return array();
        }

        //Get the current and corporate property
        $currentProperty = $this->getServiceManager()->get('currentProperty');
        $corporateProperty = $this->getServiceManager()->get('corporateProperty');

        //We don't want to use this if we're not using properties as the site strategy
        if (is_object($currentProperty)) {
            $currentId = $currentProperty->getId();
        } else {
            return array();
        }

        if (is_object($corporateProperty)) {
            $corporateId = $corporateProperty->getId();
        } else {
            return array();
        }

        //Check to see if the user isCorporate, so they can set the property themselves
        if ($this->currentUser->getIsCorporate()) {
            $optionsList = array(
                'all' => 'All Properties',
                $corporateId => "Brand Site Only ({$corporateProperty->getName()})",
            );

            if ($currentId != $corporateId) {
                $optionsList['currentId'] = "Current Hotel ({$currentProperty->getName()})";
            }

            $propertyService = $this->getServiceManager()->get('phoenix-properties');

            $properties = $propertyService->getItems();

            foreach ($properties as $valProperty) {
                if ($valProperty->getId() != $corporateProperty->getId() && $valProperty->getId() != $currentProperty->getId()) {
                    $optionsList[$valProperty->getId()] = $valProperty->getName();
                }
            }

            $property = new \Zend\Form\Element\Select($fieldKey);
            $property->setLabel('Hotel');
            $property->setLabelAttributes(array('class' => 'blockLabel'));
            $property->setAttribute('class', 'stdTextInput');
            $property->setValueOptions($optionsList);
        } else {
            $property = new \Zend\Form\Element\Hidden($fieldKey);
            $property->setValue($currentProperty->getId());
        }

        $itemForm->add($property);
    }

    public function getCategoryBy($params = array())
    {
        $result = $this->getDefaultEntityManager()
                        ->getRepository('ListModule\Entity\Categories')->findOneBy($params);

        return $result;
    }

    /**
     * setCategoryField
     * 
     * @param \Zend\Form\Form $itemForm
     */
    public function setCategoryField($fieldKey = 'category', $itemForm)
    {
        if ($this->useCategories()) {
            $categories = $this->getCategoriesArray();
            $category = new \Zend\Form\Element\Select($fieldKey);
            $category->setLabel('Category');
            $category->setLabelAttributes(array('class' => 'blockLabel'));
            $category->setAttribute('class', 'stdTextInput');
            $category->setValueOptions($categories);
        } else {
            $category = new \Zend\Form\Element\Hidden('category');
        }

        $itemForm->add($category);
    }

    /**
     * getToggleEnabledFields
     * @param  string $itemFormClass
     * @return array
     */
    public function getToggleEnabledFields($itemFormClass)
    {
        $result = array();

        if ($mergedConfig = $this->getConfig()) {
            $moduleNamespace = current(explode('\\', $itemFormClass));
            $moduleFields = $mergedConfig->get(array('moduleFields', $moduleNamespace), array());
            $result = \Phoenix\StdLib\ArrayHelper::getValueFromArray($moduleFields, 'toggleEnabledFields');
            $result = \Phoenix\StdLib\ArrayHelper::getValueFromArray($result, $itemFormClass, array());
        }

        return $result;
    }

    /**
     * getDisabledFields
     * @param  string $itemFormClass
     * @return array
     */
    public function getDisabledFields($itemFormClass)
    {
        $result = array();

        if ($mergedConfig = $this->getConfig()) {
            $moduleNamespace = current(explode('\\', $itemFormClass));
            $moduleFields = $mergedConfig->get(array('moduleFields', $moduleNamespace), array());
            $result = \Phoenix\StdLib\ArrayHelper::getValueFromArray($moduleFields, 'disabledFields');
            $result = \Phoenix\StdLib\ArrayHelper::getValueFromArray($result, $itemFormClass, array());
        }

        return $result;
    }

    /**
     * attachBindingCalls
     * @param  object $itemForm
     * @param  array $parameterVars
     * @return void
     */
    public function attachBindingCalls($itemForm, $parameterVars)
    {
        $parameterVars['module'] = lcfirst($parameterVars['module']);

        $itemFormClass = get_class($itemForm);

        $fieldsBindParameters = $this->getFieldsBindParameters($itemFormClass);

        foreach ($fieldsBindParameters as $keyBinder => $valBinder) {
            if (!$this->serviceManager->has($valBinder['class'])) {
                continue;
            }

            $binderService = $this->serviceManager->get($valBinder['class']);
            $binderService->setBindParameters($valBinder['parameters'], $parameterVars);
            $itemForm->attachBinder($keyBinder, $binderService);
        }
    }

    public function getCategoryOptions()
    {
        $option = $this->getCategoriesArray();

        $categories = new \Zend\Form\Element\Select('category');
        $categories->setLabel('Categories');
        $categories->setLabelAttributes(array('class' => 'blockLabel'));
        $categories->setAttribute('class', 'stdTextInput');
        $categories->setValueOptions($option);

        return $option;
    }

    /**
     * getItemFormSelectOptions
     * @param  object $itemForm
     * @param  string $fieldName
     * @return array
     */
    public function getItemFormSelectOptions($itemForm, $fieldName)
    {
        //echo "function getItemFormSelectOptions called for select ";
        //echo "item name:==".$itemForm;
        //echo "fieldname:===";
        //var_dump($itemForm);
        $result = array();

        $itemFormFieldOptionsGetter = "get" . ucfirst($fieldName) . "Options";

        /**
         * @todo  Do this better. Our form classes should not be used to retrieve options from the db. Perhaps tie this
         *        in to the binding mechanism. As a temporary workaround I added a check to see if the getter was found on
         *        on the service. A. Tate <atate@travelclick.com|actate81@gmail.com> 01/15/2014
         */
        if (is_callable(array($itemForm, $itemFormFieldOptionsGetter))) {
            $result = $itemForm->$itemFormFieldOptionsGetter();
        } elseif (is_callable(array($this, $itemFormFieldOptionsGetter))) {
            $result = $this->$itemFormFieldOptionsGetter();
        }
        //$result=array('hi','hello');
        return $result;
    }

    /**
     * setItemFormFields
     * @param object $itemForm
     */
    public function setItemFormFields($itemForm)
    {
        $itemFormClass = get_class($itemForm);

        /**
         * Lets get the itemFormFields from the module.properties.php file
         */
        $itemFormFields = $this->getItemFormFields($itemFormClass);

        /**
         * Dynamically add fields to the form
         */
        foreach ($itemFormFields as $key => $field) {
            /**
             * Common fields
             */
            $type = isset($field['type']) ? $field['type'] : null;
            $label = isset($field['label']) ? $field['label'] : null;
            $readonly = isset($field['readonly']) ? $field['readonly'] : false;
            $class = isset($field['class']) ? $field['class'] : null;
            $options = isset($field['options']) ? $field['options'] : array();

            /**
             * for select fields
             */
            $options = isset($field['options']) ? $field['options'] : array();
            $multiple = isset($field['multiple']) ? $field['multiple'] : false;

            /**
             * for date fields
             */
            $min = isset($field['min']) ? $field['min'] : null;
            $max = isset($field['max']) ? $field['max'] : null;


            switch ($type) {
                case 'text':
                case 'integer':
                    $type = 'text';
                case 'textarea':
                case 'checkbox':

                    $itemForm->add($itemForm->$type($key, $label, $readonly, $class));
                    break;
                /**
                 * These were identical, so no reason to duplicate code. Also added some error trapping
                 * so options set in form config can be used.
                 * A. Tate <atate@travelclick.com|actate81@gmail.com> 01/15/2014
                 */
                case 'select':
                case 'pageselect':
                    $retrievedOptions = $this->getItemFormSelectOptions($itemForm, $key);
                    $options = (!empty($retrievedOptions)) ? $retrievedOptions : $options;
                    $itemForm->add($itemForm->$type($key, $label, $options, $readonly, $class));
                    break;
                case 'multiselect':
                    $retrievedOptions = $this->getItemFormSelectOptions($itemForm, $key);
                    $options = (!empty($retrievedOptions)) ? $retrievedOptions : $options;
                    $itemForm->add($itemForm->$type($key, $label, $options, $readonly, $class));
                    break;
                case 'date':
                    $itemForm->add($itemForm->$type($key, $label, $min, $max, $readonly, $class));
                    break;
                case 'category':
                    $this->setCategoryField($key, $itemForm);
                    break;
                case 'propertySelect':
                    $this->setPropertyField($key, $itemForm);
                    break;
                case 'mediaAttachments':
                    $itemForm->add($itemForm->$type($key, $label, $readonly, $class));
                    break;
                default:
                    throw new \Exception("The form field type [{$type}] is not supported");
                    break;
            }
        }

        $this->removeDisabledFields($itemForm);
        //var_dump($itemForm);
        return $itemForm;
    }

    /**
     * removeDisabledFields
     * @param  object $itemForm
     * @return void
     */
    public function removeDisabledFields($itemForm)
    {
        $itemFormClass = get_class($itemForm);

        $toggleEnabledFields = $this->getToggleEnabledFields($itemFormClass);
        $disabledFields = $this->getDisabledFields($itemFormClass);

        foreach ($toggleEnabledFields as $valField) {
            if (in_array($valField, $disabledFields)) {
                $itemForm->remove($valField);
            }
        }
    }

    /**
     * getForm
     * @param  string $formName
     * @param  ServiceManager $sl
     * @return object $form
     */
    public function getForm($formName, $sl = null)
    {
        $form = parent::getForm($formName, $sl);
        $form = $this->setItemFormFields($form);

        $languageService = $this->getServiceManager()->get('phoenix-languages');

        $languages = $languageService->getLanguageOptions();

        $currentLanguage = $this->getCurrentLanguage();

        if (empty($currentLanguage)) {
            $this->setCurrentLanguage($this->getServiceManager()->get('currentLanguage'));
        }

        $fields = is_object($this->getModule()) ? $this->getModule()->getComponentFields() : array();


        if (count($languages) > 1) {
            foreach ($fields as $valField) {
                if ($valField->getTranslate() == 1) {
                    foreach (array_keys($languages) as $valLanguage) {
                        $elementName = $valField->getName() . '_' . $valLanguage;

                        $form->add(array('name' => $elementName, 'type' => 'Hidden'));
                    }
                }
            }

            $form->add(array('name' => 'currentLanguage', 'type' => 'Hidden'));

            $form->get('currentLanguage')->setValue($this->getCurrentLanguage()->getCode());
        }

        return $form;
    }

    /**
     * updateItemStatuses
     * @param  object $entity
     * @param  string $prefix
     * @param  int $status
     * @param  array $items
     * @return void
     */
    public function updateItemsStatuses($entity, $prefix, $status, $items)
    {
        $em = $this->getDefaultEntityManager();
        $qbTrash = $em->createQueryBuilder();

        $qbTrash->update($entity, $prefix)
                ->set("$prefix.status", $status)
                ->where($qbTrash->expr()->in("{$prefix}.id", $items));

        $qbTrash->getQuery()->execute();
    }

    /**
     * getItemsArray
     * @return array
     */
    public function getItemsArray()
    {
        $itemsArray = array();
        $itemObjects = $this->getItems();
        foreach ($itemObjects as $valItem) {
            $itemsArray[] = $valItem->toArray();
        }

        return $itemsArray;
    }

    /**
     * This is a dummy to be overwriten in extending classes that need to modify the query
     * @param type $qb
     */
    protected function modifyQuery(&$qb)
    {
        
    }

    /**
     * getItemsResult
     *
     * Returns an array of items from the repository
     *
     * @param  object $entityManager
     * @param  string $entityName
     * @return array
     */
    protected function getItemsResult($entityManager, $entityName, $active = false, $showAll = false)
    {
        $orderBy = $this->orderBy;

        if ($this->getOrderList()) {
            $orderBy = array_merge(array('orderNumber' => 'ASC'), $orderBy);
        }

        if ($active) {
            return $entityManager->getRepository($entityName)->findBy(array('status' => 1), $orderBy);
        }

        return $entityManager->getRepository($entityName)->findBy(array(), $orderBy);
    }

    // public function getCacheName($method, $nameParts = array()) {
    //     $name = str_replace('\\', '-', get_class($this));
    //     $name . '-' . $method . implode('-', $nameParts);
    // }

    /**
     * getItems
     *
     * Get the items array
     *
     * @return array
     */
    public function getItems($active = false, $showAll = false)
    {
        //This will load the module if necessary
        $this->getTranslateFields();

        $items = array();

        if (!($resultsRaw = $this->getItemsResult($this->getDefaultEntityManager(), $this->entityName, $active, $showAll))) {
            return array();
        }

        $results = $this->getItemTranslations($resultsRaw);

        foreach ($results as $valItem) {

            $items[] = $this->createModel($valItem['item'], $valItem['languageTranslations']);
        }

        //This is a necessary step so wierd stuff doesn't go down.
        foreach ($this->detachEntities as $valEntity) {
            $valEntity->__setInitialized(false);
            $this->getDefaultEntityManager()->detach($valEntity);
        }

        return $items;
    }

    public function getItemTranslations($items = array())
    {
        $itemsArray = $this->organizeTransItems($items);

        $itemIds = array_keys($itemsArray);

        $query = $this->getTransQuery($itemIds);

        $query->setHint(\Doctrine\ORM\Query::HINT_INCLUDE_META_COLUMNS, true);
        $languageTranslations = $query->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY); //$this->getDefaultEntityManager()->getRepository($this->languagesEntity)->findBy(array('component' => $this->getModule(), 'item' => $itemIds));

        if (!empty($languageTranslations)) {
            foreach ($languageTranslations as $valTranslation) {
                $itemId = (int) $valTranslation['itemId'];

                if (!isset($itemsArray[$itemId])) {
                    continue;
                }

                $itemsArray[$itemId]['languageTranslations'][] = $valTranslation;
            }
        }

        return $itemsArray;
    }

    protected function getTransQuery($itemIds)
    {
        $qbTrans = $this->getDefaultEntityManager()->createQueryBuilder();

        $qbTrans->select('IDENTITY(lt.item) as itemId, lt.content, ltf.name as fieldName, ltl.code as langCode')
                ->from($this->languagesEntity, 'lt')
                ->join('lt.field', 'ltf')
                ->join('lt.language', 'ltl');

        $this->buildTransQuery($qbTrans);

        $qbTrans->andWhere($qbTrans->expr()->in('lt.item', ':itemIds'))
                ->setParameter('itemIds', $itemIds);

        $query = $qbTrans->getQuery();

        return $query;
    }

    protected function buildTransQuery($qbTrans)
    {
        if ($this->getModule() instanceof \DynamicListModule\Model\Module || $this->getModule() instanceof \FlexibleForms\Model\Module) {
            $module = $this->getModule()->getEntity();
        } else {
            $module = $this->getModule();
        }

        $qbTrans->where('lt.component = :component')
                ->setParameter('component', $module);
    }

    protected function organizeTransItems($items = array())
    {
        $itemsArray = array();

        foreach ($items as $valItem) {
            $itemsArray[$valItem->getId()]['item'] = $valItem;
            $itemsArray[$valItem->getId()]['languageTranslations'] = array();
        }

        return $itemsArray;
    }

    public function getItemsByIdArray($idArray)
    {
        //This will load the module if necessary
        $this->getTranslateFields();

        $itemsArray = array();
        foreach ($idArray as $valId) {
            $item = $this->getItem($valId);

            if ($item) {
                $itemsArray[] = $item;
            }
        }

        return $itemsArray;
    }

    /**
     * getItem
     * @param  int $id
     * @return mixed
     */
    public function getItem($id)
    {
        $this->getModuleName();
        //This will load the module if necessary
        $this->getTranslateFields();

        $module = $this->getModule();
        $entity = $this->getDefaultEntityManager()->getRepository($this->entityName)->findOneBy(array('id' => $id));

        if (empty($entity)) {
            return false;
        }

        if (!is_object($module)) {
            return $this->createModel($entity, array());
        } elseif (!$module instanceof \Phoenix\Module\Entity\EntityAbstract) {
            $module = $module->getEntity();
        }

        $dummyItem = new \DynamicListModule\Entity\DynamicListModuleItems();
        $dummyItem->setId($entity->getId());

        $component = $this->getModule();

        $languageTranslations = $this->getItemTranslations(array($entity));

        return $this->createModel($entity, $languageTranslations[$entity->getId()]['languageTranslations']);
    }

    /**
     * getItemBy
     * @param  array  $filters
     * @return mixed
     */
    public function getItemBy($filters = array())
    {
        //This will load the module if necessary
        $this->getTranslateFields();

        if (in_array('component', array_keys($filters))) {
            $testEntity = new $this->entityName();

            if (!in_array('component', array_keys($testEntity->getNames()))) {
                unset($filters['component']);
            }
        }


        $entity = $this->getDefaultEntityManager()->getRepository($this->entityName)->findOneBy($filters);

        if (empty($entity)) {
            return false;
        }

        $result = $this->getItemTranslations(array($entity));

        $item = current($result);

        if ($this->dontDetach == false) {
            //This is a necessary step so wierd stuff doesn't go down.
            foreach ($this->detachEntities as $valEntity) {
                $valEntity->__setInitialized(false);
                $this->getDefaultEntityManager()->detach($valEntity);
            }
        }

        return $this->createModel($item['item'], $item['languageTranslations']);
    }

    /**
     * getItemsBy
     * @param  array  $filters
     * @return mixed
     */
    public function getItemsBy($filters = array(), $orderBy = array())
    {
        //This will load the module if necessary
        $this->getTranslateFields();

        $result = $this->getDefaultEntityManager()->getRepository($this->entityName)->findBy($filters, $orderBy);

        if (empty($result)) {
            return array();
        }

        $result = $this->getItemTranslations($result);

        $items = array();

        foreach ($result as $valItem) {
            $items[] = $this->createModel($valItem['item'], $valItem['languageTranslations']);
        }


        //This is a necessary step so wierd stuff doesn't go down.
        foreach ($this->detachEntities as $valEntity) {
            $valEntity->__setInitialized(false);
            $this->getDefaultEntityManager()->detach($valEntity);
        }
        return $items;
    }

    public function getTranslateFields()
    {
        if (empty($this->translateFields)) {
            $module = $this->getModule();

            if (empty($module)) {
                $module = $this->getDefaultEntityManager()->getRepository('Toolbox\Entity\Components')->findOneBy(array('name' => $this->getModuleName()));
                $this->setModule($module);
            } elseif (is_int($module)) {
                $module = $this->getDefaultEntityManager()->getRepository('Toolbox\Entity\Components')->findOneBy(array('id' => $module));
            }

            if (!empty($module)) {
                $fields = $module->getComponentFields();

                $translateFields = array();

                if (!empty($fields)) {
                    foreach ($fields as $valField) {
                        if ($valField->getTranslate() == 1) {
                            $translateFields[] = array($valField->getName(), $valField->getType());
                        }
                    }
                }

                $this->translateFields = $translateFields;
            }
        }

        return $this->translateFields;
    }

    /**
     * createModel
     * @param  boolean $entity
     * @return object $model
     */
    public function createModel($entity = false, $languageTranslations = array())
    {
        $memoryLimit = (int) ini_get('memory_limit');

        //THIS NEEDS TO BE REMOVED FROM HERE BEFORE LOEWS GOES LIVE. WE SHOULDN'T BE MESSING WITH MEMORY_LIMIT IN MODELS

        if ($memoryLimit < 150) {
            ini_set('memory_limit', '150M');
        }

        $modelClass = $this->modelClass;

        $moduleFields = is_object($this->getModule()) ? $this->getModule()->getComponentFields() : array();

        $model = new $modelClass($this->getConfig(), $moduleFields);
        $model->setDefaultEntityManager($this->getDefaultEntityManager());
        $model->setAdminEntityManager($this->getAdminEntityManager());
        $model->setServiceManager($this->serviceManager);
        $model->setLanguageTranslations($languageTranslations);

        if (is_object($this->serviceManager) && $this->serviceManager->has('MergedConfig')) {
            $model->setLanguages($this->serviceManager->get('phoenix-languages'));
        }
        $model->setCurrentUser($this->currentUser);
        $model->setCurrentLanguage($this->getCurrentLanguage());

        if ($entity) {
            $model->setEntity($entity);
        }

        return $model;
    }

    public function createCasEntry($action, $data = null, $itemId = null, $originalData = null)
    {
        if ($data && (!is_array($data))) {
            $data = $data->toArray();
        }
        $casItem = new \ContentApproval\Entity\ContentApproval;
        if ($data) {
            $casItem->setData(serialize($data));
        }

        if (isset($this->casServiceName)) {
            $serviceName = $this->casServiceName;
        } else {
            $serviceName = $this->getModuleName();
        }
        $casItem->setItemTable($serviceName);
        $casItem->setApprovalAction($action);
        $casItem->setItemId($itemId);
        $casItem->setCreatedUserId($this->getCurrentUser()->getId());
        $casItem->setModifiedUserId($this->getCurrentUser()->getId());
        $casItem->setApproved(0);
        $casItem->setCreated(new \DateTime);
        $casItem->setModified(new \DateTime);
        if ($originalData) {
            $casItem->setOriginalData(serialize($originalData));
        }

        $em = $this->getDefaultEntityManager();
        $em->persist($casItem);
        $em->flush();

        $workflows = $em->getRepository('\ContentApproval\Entity\ContentApprovalWorkflows')->findAll();

        foreach ($workflows as $workflow) {
            $approvalItem = new \ContentApproval\Entity\ContentApprovalApprovals;
            $approvalItem->setWorkflow($workflow);
            $approvalItem->setItem($casItem);
            $approvalItem->setApproved(0);
            $approvalItem->setStatus(1);
            $em->persist($approvalItem);
            $em->flush();

            $siteroot = $this->getConfig()->get(array('templateVars', 'siteroot'));

            //send email notification
            $users = $this->getAdminEntityManager()->getRepository('\Users\Entity\Admin\UsersGroups')->findBy(array('groupId' => $workflow->getUserGroup()));
            foreach ($users as $userFromGroup) {
                $user = $this->getAdminEntityManager()->getRepository('\Users\Entity\Admin\Users')->find($userFromGroup->getUserId());

                $email = $user->getEmail();

                if (!empty($email)) {

                    $mail = new \Zend\Mail\Message();
                    $mail->setBody($siteroot . 'toolbox/tools/contentApproval/editItem/' . $casItem->getId()); // will generate our code html from email-template.phtml  
                    $mail->setFrom('contact@travelclick.com', 'Contact');
                    $mail->setTo($user->getEmail(), $user->getGivenName());

                    $mail->setSubject('A new change is waiting for your approval - Change ID #' . $casItem->getId());

                    $transport = new \Zend\Mail\Transport\Smtp();
                    $transport->send($mail);
                }
            }
        }
    }

    /**
     * save
     * @param  object $model [description]
     * @param  array $data  [description]
     * @return void
     */
    public function save($model, $data, $approved = false, $moduleName = null)
    {
        $this->getTranslateFields();
        $module = $this->getModule();

        if ((!is_object($model) || !$model->getId()) && $this->orderList) {
            $data['orderNumber'] = $this->getMaxOrderNumber() + 1;
        }

        if ((!$approved) && $module && $module->getCasAllowed() && $module->getCasEnabled()) {
            if (!$model->getId()) {
                $this->createCasEntry('save', $data);
            } elseif ($this->hasChanges($model, $data)) {
                $this->createCasEntry('update', $data, $model->getId(), $model->toArray());
            }
        } else {
            if (!isset($data['component'])) {
                $data['component'] = $this->getModule();
            }

            if (!$model) {
                $model = $this->createModel();
            }

            $model->loadFromArray($data);
            $model->save();
            return $model->getId();
        }
    }

    /**
     * draft
     * @param  array $items
     * @return void
     */
    public function draft($items, $approved = false)
    {
        $this->getTranslateFields();
        $module = $this->getModule();

        if ((!$approved) && $module && $module && $module->getCasAllowed() && $module->getCasEnabled()) {
            foreach ($items as $item) {
                if ($this->getItem($item)->getStatus() != ListItem::ITEM_STATUS_DRAFT) {
                    $this->createCasEntry('draft', array('status' => ListItem::ITEM_STATUS_DRAFT), $item, array('status' => $this->getItem($item)->getStatus()));
                }
            }
        } else {
            $this->updateItemsStatuses($this->entityName, 'p', ListItem::ITEM_STATUS_DRAFT, $items);
        }
    }

    /**
     * archive
     * @param  array $items
     * @return void
     */
    public function archive($items, $approved = false)
    {
        $this->getTranslateFields();
        $module = $this->getModule();

        if ((!$approved) && $module->getCasAllowed() && $module->getCasEnabled()) {
            foreach ($items as $item) {
                if ($this->getItem($item)->getStatus() != ListItem::ITEM_STATUS_ARCHIVED) {
                    $this->createCasEntry('archive', array('status' => ListItem::ITEM_STATUS_ARCHIVED), $item, array('status' => $this->getItem($item)->getStatus()));
                }
            }
        } else {
            $this->updateItemsStatuses($this->entityName, 'p', ListItem::ITEM_STATUS_ARCHIVED, $items);
        }
    }

    /**
     * publish
     * @param  array $items
     * @return void
     */
    public function publish($items, $approved = false)
    {
        $this->getTranslateFields();
        $module = $this->getModule();

        if ((!$approved) && $module && $module->getCasAllowed() && $module->getCasEnabled()) {
            foreach ($items as $item) {
                if ($this->getItem($item) && ($this->getItem($item)->getStatus() != ListItem::ITEM_STATUS_PUBLISHED)) {
                    $this->createCasEntry('publish', array('status' => ListItem::ITEM_STATUS_PUBLISHED), $item, array('status' => $this->getItem($item)->getStatus()));
                }
            }
        } else {
            $this->updateItemsStatuses($this->entityName, 'p', ListItem::ITEM_STATUS_PUBLISHED, $items);
        }
    }

    /**
     * trash
     * @param  array $items
     * @return void
     */
    public function trash($items, $approved = false)
    {
        $this->getTranslateFields();
        $module = $this->getModule();

        if ((!$approved) && !empty($module) && $module->getCasAllowed() && $module->getCasEnabled()) {
            foreach ($items as $item) {
                if ($this->getItem($item)->getStatus() != ListItem::ITEM_STATUS_TRASHED) {
                    $this->createCasEntry('trash', array('status' => ListItem::ITEM_STATUS_TRASHED), $item, array('status' => $this->getItem($item)->getStatus()));
                }
            }
        } else {
            $this->updateItemsStatuses($this->entityName, 'p', ListItem::ITEM_STATUS_TRASHED, $items);
        }
    }

    // Method is for getting results on various queries that might be implemented in extended classes
    // Outside calls have to be done in a form of "getSomething()"
    // internal implementation of 'filler' methods should be "Something($query, $arg)" in order not to conflict with this method
    public function __call($name, $args)
    {
        // Check if a 'getter' was called
        if (strpos($name, 'get') !== 0
                // ...and the required method exists on current object
                || !method_exists($this, $fun = substr($name, 3))) {
            return null;
        }
        // Create the query object
        $qry = $this->getDefaultEntityManager()->createQueryBuilder();
        // Call the query builder method in the extended object to actually fill the query object
        $this->$fun($qry, $args[0]);
        // return the query results
        return $qry->getQuery()->getResult();
    }

    public function hasChanges($entityModel, $data)
    {
        unset($data['action']);
        $entity = $entityModel->getEntity();

        if (empty($entity)) {
            return true;
        }

        foreach ($data as $key => $value) {
            $getAttribute = 'get' . ucfirst($key);
            if (($entity->$getAttribute()) != $value) {
                return true;
            }
        }
        return false;
    }

    public function strPosArray($val1, $val2)
    {
        if (!is_array($val2))
            $val2 = array($val2);
        foreach ($val2 as $what) {
            if (($pos = strpos($val1, $what)) !== false)
                return $pos;
        }
        return false;
    }

    public function exportTranslations(LanguageEvent $e)
    {
        $translations = $e->getParam('exportArray');

        $this->getTranslateFields();

        $module = $this->getModule();

        $moduleItems = $this->getItems();

        if (!is_object($module)) {
            var_dump($this);
            die('DIE CALLED AT LINE: ' . __LINE__ . ' in FILE: ' . __FILE__);
        }

        $moduleName = $module->getName();

        foreach ($moduleItems as $valItem) {
            if (!isset($translations[$moduleName])) {
                $translations[$moduleName] = new ArrayObject();
            }

            $itemArray = new ArrayObject();
            $itemArray['fields'] = new ArrayObject();

            switch ($moduleName):
                case 'PhoenixProperties':
                    $propertyIdFilter = $valItem->getId();
                    break;
                case 'PhoenixEvents':
                    $propertyIdFilter = $valItem->getPropertyId();
                    break;
                case 'HeroImages':
                    $propertyIdFilter = $valItem->getPropertyId();
                    break;
                case 'PhoenixRates':
                    $propertyIdFilter = $valItem->getProperty()->getId();
                    break;
                case 'PhoenixRooms':
                    $propertyIdFilter = $valItem->getProperty()->getId();
                    break;
                case 'PhoenixAddons':
                    $propertyIdFilter = $valItem->getProperty()->getId();
                    break;
                default:
                    $propertyIdFilter = '';
                    break;
            endswitch;
            $filterArrayPages = array('/youfirst', '/montreal-hotel', '/reservations', '/discover-loews', '/meetings', '/destinations', '/special-offers');
            $filterArraySeoMetaText = array('Brand', 'Vogue');


            if ($propertyIdFilter == 22 || $propertyIdFilter == 10 || $propertyIdFilter == '') {

                $itemTranslations = $valItem->getTranslations();
                if ($moduleName == 'Pages') {
                    if ($this->strPosArray($valItem->getDataSection(), $filterArrayPages) == 'true' || $valItem->getDataSection() == '') {
                        foreach ($module->getComponentFields() as $valField) {

                            if ($valField->getTranslate() == 1) {
                                $fieldArray = new ArrayObject();

                                $fieldName = 'get' . ucfirst($valField->getName());
                                $fieldArray['defaultLanguageValue'] = $valItem->$fieldName();
                                $fieldArray['translations'] = new ArrayObject();

                                if (isset($itemTranslations[$valField->getName()])) {
                                    $fieldArray['translations'] = $itemTranslations[$valField->getName()];
                                }

                                $itemArray['fields'][$valField->getName()] = $fieldArray;
                            }
                        }
                    }
                } else if ($moduleName == 'SeoMetaText') {
                    if ($this->strPosArray($valItem->getMetaH1(), $filterArraySeoMetaText) == 'true') {
                        foreach ($module->getComponentFields() as $valField) {

                            if ($valField->getTranslate() == 1) {
                                $fieldArray = new ArrayObject();

                                $fieldName = 'get' . ucfirst($valField->getName());
                                $fieldArray['defaultLanguageValue'] = $valItem->$fieldName();
                                $fieldArray['translations'] = new ArrayObject();

                                if (isset($itemTranslations[$valField->getName()])) {
                                    $fieldArray['translations'] = $itemTranslations[$valField->getName()];
                                }

                                $itemArray['fields'][$valField->getName()] = $fieldArray;
                            }
                        }
                    }
                } else {
                    foreach ($module->getComponentFields() as $valField) {

                        if ($valField->getTranslate() == 1) {
                            $fieldArray = new ArrayObject();

                            $fieldName = 'get' . ucfirst($valField->getName());
                            $fieldArray['defaultLanguageValue'] = $valItem->$fieldName();
                            $fieldArray['translations'] = new ArrayObject();

                            if (isset($itemTranslations[$valField->getName()])) {
                                $fieldArray['translations'] = $itemTranslations[$valField->getName()];
                            }

                            $itemArray['fields'][$valField->getName()] = $fieldArray;
                        }
                    }
                }

                $translations[$moduleName][$valItem->getId()] = $itemArray;
            }
        }
    }

    public function importTranslations(LanguageEvent $e)
    {
        $importText = $e->getParam('importText');

        $languagesService = $this->getServiceManager()->get('phoenix-languages');

        $languages = $languagesService->getItems();

        $languagesArray = array();

        $this->getTranslateFields();

        $module = $this->getModule();

        foreach ($languages as $valLanguage) {
            $languagesArray[$valLanguage->getCode()] = $valLanguage;
        }

        $this->doImportTranslations($languagesArray, $importText, $module);
    }

    public function doImportTranslations($languagesArray, $importText, $module = null)
    {
        $this->dontDetach = true;
        if (!$this->getCurrentUser() instanceof \Users\Model\Users) {
            $this->setCurrentUser($this->getServiceManager()->get('phoenix-users-current'));
        }

        if (isset($importText[$module->getName()])) {
            foreach ($importText[$module->getName()] as $keyItem => $valItem) {
                $moduleEntity = ($module instanceof \Phoenix\Module\Entity\EntityAbstract) ? $module : $module->getEntity();

                $filters = array('id' => $keyItem, 'component' => $moduleEntity);

                $item = $this->getItemBy($filters);

                if (empty($item)) {
                    continue;
                }

                $itemTranslations = $item->getLanguageTranslations();

                $translationsArray = array();

                foreach ($itemTranslations as $valItemTranslation) {
                    $translationsArray[$valItemTranslation->getField()->getName()][$valItemTranslation->getLanguage()->getCode()] = $valItemTranslation;
                }
                echo 'Begin Module ' . $module->getName() . ' Import of item #' . $keyItem . '<hr><hr>';
                foreach ($module->getComponentFields() as $valField) {
                    if ($valField->getTranslate() == 1) {
                        $importTranslations = $valItem['fields'][$valField->getName()]['translations'];

                        if (is_array($importTranslations) || $importTranslations instanceof ArrayObject) {
                            foreach ($importTranslations as $keyTranslation => $valTranslation) {
                                if (isset($translationsArray[$valField->getName()][$keyTranslation]) && $translationsArray[$valField->getName()][$keyTranslation]->getField() == $valField) {
                                    $updateTranslation = $translationsArray[$valField->getName()][$keyTranslation];
                                    $updateTranslation->setContent($valTranslation);
                                    $updateTranslation->setModified(new \DateTime());
                                    $updateTranslation->setModifiedUserId($this->getCurrentUser()->getId());

                                    $this->defaultEntityManager->getConnection()->executeUpdate('update languages_translations set item = ?, content = ?, modified = ?, modifiedUserId = ? where translationId = ?', array($item->getId(), $valTranslation, $updateTranslation->getModified()->format('Y-m-d H:i:s'), $updateTranslation->getModifiedUserId(), $updateTranslation->getId()));

                                    //$this->getDefaultEntityManager()->persist($updateTranslation);
                                    // try {
                                    //$this->getDefaultEntityManager()->flush();
                                    // } catch (ORMInvalidArgumentException $e) {
                                    //     //This is why we're going to a unified items table, so this crap can be excised.
                                    // }
                                    //echo 'Module ' . $module->getName() . '::Item #' . $item->getId() . ' Translation for ' . $valField->getName() . '::' . $keyTranslation . ' Updated.<br>';
                                } else {
                                    $this->insertTranslation($valField, $moduleEntity, $languagesArray[$keyTranslation], $item, $valTranslation);
                                    //echo 'Module ' . $module->getName() . '::Item #' . $item->getId() . ' Translation for ' . $valField->getName() . '::' . $keyTranslation . ' Inserted.<br>';
                                }
                            }
                        }
                    }
                }
            }
        }
        $this->dontDetach = false;
    }

    public function insertTranslation($field, $module, $language, $item, $content)
    {
        $insertTranslation = new $this->languagesEntity();
        $insertTranslation->setField($field);
        $insertTranslation->setComponent($module);
        $insertTranslation->setLanguage($language->getEntity());
        $insertTranslation->setContent($content);
        $insertTranslation->setCreatedUserId($this->getCurrentUser()->getId());
        $insertTranslation->setModifiedUserId($this->getCurrentUser()->getId());
        $insertTranslation->setCreated(new \DateTime());
        $insertTranslation->setModified(new \DateTime());
        $insertTranslation->setStatus(1);
        $this->getDefaultEntityManager()->persist($insertTranslation);
        $this->getDefaultEntityManager()->flush();

        $this->defaultEntityManager->getConnection()->executeUpdate('update languages_translations set item = ? where translationId = ?', array($item->getId(), $insertTranslation->getId()));
    }

    public function getCountriesOptions()
    {
        $options = array();
        $jsonString = file_get_contents(str_replace("\\", "/", PHOENIX_PATH) . "/module/ListModule/view/layout/js/countries.json");
        $jsonArray = json_decode($jsonString, true);
        foreach ($jsonArray as $keyArray => $valArray) {
            $options[$keyArray] = $valArray;
        }
        return $options;
    }

}
