<?php

/**
 * The file for the DynamicListModule Class for the Dynamic List Module
 *
 * @category    Toolbox
 * @package     DynamicListModule
 * @subpackage  Service
 * @copyright   Copyright (c) 2014 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 1.14
 * @since       File available since release 1.14
 * @author      A. Tate <atate@travelclick.com>
 * @filesource
 */

namespace DynamicListModule\Service;

use DynamicListModule\Model\ModuleItem;
use ListModule\Service\Lists;
use DynamicListModule\Model\Field;
use Phoenix\EventManager\Event as LanguageEvent;
use Zend\Stdlib\ArrayObject;
use Zend\Form\Element;
use Zend\Form\Form;

/**
 * The DynamicListModule Class for the Dynamic List Module
 *
 * @category    Toolbox
 * @package     DynamicListModule
 * @subpackage  Service
 * @copyright   Copyright (c) 2014 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 1.14
 * @since       File available since release 1.14
 * @author      A. Tate <atate@travelclick.com>
 */
class DynamicManager extends Lists
{

    protected $orderList = true;
    protected $allowCas = true;

    /**
     * The name of the entity used by the associated model
     * @var string
     */
    protected $entityName = ModuleItem::ENTITY_NAME;

    /**
     * The name of the model class associated with this service
     * @var string
     */
    protected $modelClass = "\DynamicListModule\Model\ModuleItem";

    /**
     * The current dynamic module's name
     * @var string
     */
    protected $moduleName = '';

    protected $fields;

    /**
     * setModuleName
     * 
     * @param string $moduleName
     */
    public function setModuleName($moduleName)
    {
        $this->moduleName = $moduleName;
    }

    /**
     * getModuleName
     * @return string
     */
    public function getModuleName()
    {
        return $this->moduleName;
    }

    public function createModel($entity = false, $languageTranslations = array())
    {
        $model = parent::createModel($entity, $languageTranslations);

        $model->setFields($this->getFields());

        return $model;
    }

    /**
     * getForm
     * 
     * @param  string $formName [description]
     * @param  mixed $sl
     * @return DynamicListModule\Form\ModuleItemForm
     */
    public function getForm($formName, $sl = null)
    {
        //Set the name of the form to retrieve
        $formName = '\DynamicListModule\Form\ModuleItemForm';

        //Retrieve and return the form
        $form = parent::getForm($formName, $sl);


        $findId = array();
        $options_total = array();
        $fields = $this->getFields();

        foreach ($fields as $key => $value) {
            if ($value->getType() !== 'select' && $value->getType() !== 'multiselect' && $value->getType() !== 'pageselect') {
                continue;
            }
            $valOptions = $this->getDefaultEntityManager()->getRepository('DynamicListModule\Entity\DynamicListModuleSelectValues')->findBy(array('field' => $value->getId()));
            if (count($valOptions) != 0) {
                foreach ($valOptions as $keyOptions => $valOptions) {
                    $options_total[$valOptions->getName()] = $valOptions->getName();
                }
                $form->get($value->getName())->setValueOptions($options_total);
            }
            
            // adding countries dropdown
            if ($value->getType() == 'countries'){
                 $form->get($value->getName())->setValueOptions($this->getCountriesOptions());
            } 
        }
        
        
        
        return $form;
    }
   

    /**
     * getFields
     *
     * Gets the fields associated with this module
     * 
     * @param  boolean $showInList [description]
     * @return mixed      
     */
    public function getFields($showInList = false)
    {
        if (!empty($this->fields)) {
            return $this->fields;
        }

        //Create the query builder instance
        $qbItems = $this->getDefaultEntityManager()->createQueryBuilder();

        //Build the query
        $qbItems->select('dlm', 'mf')
                ->from('Toolbox\Entity\Components', 'dlm')
                ->leftJoin('dlm.componentFields', 'mf')
                ->where('dlm.name = :moduleName')
                ->orderBy('mf.orderNumber')
                ->setParameter('moduleName', $this->moduleName);

        //Add the optional statement, if necessary
        if ($showInList) {
            $qbItems->andWhere('mf.showInList = 1');
        }

        //Run the Query
        $result = $qbItems->getQuery()->getResult();

        //Get the fields
        $this->fields = $result[0]->getComponentFields();

        //Return the fields collection
        return $this->fields;
    }

    public function getExportCsvString()
    {

        $fields = $this->getFields();
        $items = $this->module->getModuleItems();

        $csvArray = array();
        $fieldNames = array();

        foreach ($fields as $field) {
            $fieldNames[] = $field->getName();
        }
        $headerFields = $fieldNames;
        $headerFields[] = 'property';
        $headerFields[] = 'categoryId';
        $csvArray[] = $headerFields;

        foreach ($items as $item) {
            $itemModel = $this->createModel($item);
            $fieldsArray = array();
            foreach ($fieldNames as $fieldName) {
                $fieldsArray[] = $itemModel->getValue($fieldName);
            }
            $fieldsArray['property'] = $itemModel->getProperty()->getId();
            $fieldsArray['categoryId'] = $itemModel->getCategoryId();
            $csvArray[] = $fieldsArray;
        }
        return $this->arrayToCsvString($csvArray);
    }

    public function import($file)
    {
        $filename = $file['tmp_name'];
        $itemsArray = $this->csvToArray($filename);

        foreach ($itemsArray as $itemArray) {
            $this->save($this->createModel(), $itemArray);
        }
    }

    public function useCategories()
    {
        $module = $this->getModule();

        return $module->getCategories();
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
        $this->getTranslateFields();

        $maxOrderQuery = $this->getDefaultEntityManager()->createQueryBuilder();

        $maxOrderQuery->select('lm.orderNumber')
                ->from($this->entityName, 'lm')
                ->where('lm.component = :component')
                ->setParameter('component', $this->getModule()->getEntity())
                ->orderBy('lm.orderNumber', 'DESC')
                ->setMaxResults(1);

        try {
            $result = $maxOrderQuery->getQuery()->getSingleResult();

            return $result['orderNumber'];
        } catch (\Doctrine\ORM\NoResultException $e){
            return 0;
        }
    }

    /**
     * getItems
     * 
     * @return array
     */
    public function getItems($active = false, $showAll = false)
    {

        //Create the QueryBuilder instance
        $qbItems = $this->getDefaultEntityManager()->createQueryBuilder();

        //Build the Query
        $qbItems->select('dlm', 'mi')
                ->from('Toolbox\Entity\Components', 'dlm')
                ->leftJoin('dlm.moduleItems', 'mi')
                ->where('dlm.name = :moduleName')
                // ->andWhere(
                //         $qbItems->expr()->orx(
                //             'mi.id = lt.item',
                //             'lt.item is NULL'
                //         )
                //     )
                ->orderBy('mi.orderNumber','ASC')
                ->setParameter('moduleName', $this->moduleName);

        if ($active == true) {
            $qbItems->andWhere('mi.status = 1');
        }

        if ($this->getCurrentUser()->getId() && !$this->getCurrentUser()->isCorporate()) {
            $qbItems->andWhere('mi.property = :property')
                    ->setParameter('property', $this->getServiceManager()->get('currentProperty')->getEntity());
        }

        //Run the Query
        $result = $qbItems->getQuery()->getResult();

        $arrayItems = array();

        if (empty($result)) {
            return array();
        }

        //Get the items for the module
        $items = $result[0]->getModuleItems();

        return empty($items) ? $items : $this->getItemsTranslated($items);
    }

    public function getItemsTranslated($items)
    {
        $translatedItems = array();
        foreach ($items as $valItem) {
            $translatedItems[] = $this->createModel($valItem);
        }

        return $translatedItems;
    }

    /**
     * returns all items irrespective of what module they belong to.
     */
    public function getAllItems($modules)
    {
        $items = $this->getDefaultEntityManager()->getRepository('DynamicListModule\Entity\DynamicListModuleItems')->findBy(array('component' => $modules,'status' => 1,'property'=>array(22,10)));

        if (empty($items)) {
            return array();
        }

        return $this->getItemsTranslated($items);
    }

    public function getItemsBy($params = array(), $orderBy = array())
    {
        //Create the QueryBuilder instance
        $qbItems = $this->getDefaultEntityManager()->createQueryBuilder();

        $currentProperty = $this->getServiceManager()->get('currentProperty');

        //We don't want an error if the given module doesn't exist.
        if (!is_object($this->getModule())) {
            $this->getTranslateFields();
        }

        //Build the Query
        $qbItems->select('mi')
                ->from('Toolbox\Entity\ComponentItems', 'mi')
                ->where('mi.component = :module')
                ->setParameter('module', $this->getModule()->getEntity());

        foreach ($params as $keyParam => $valParam) {
            switch ($keyParam) {
                case 'property':
                    if ($valParam == true) {
                        $qbItems->andWhere($qbItems->expr()->orx('mi.allProperties = 1', 'mi.property = :property'))
                                ->setParameter('property', $currentProperty->getEntity());
                    }
                    break;
                default:
                    $qbItems->andWhere("mi.{$keyParam} = :{$keyParam}")
                            ->setParameter($keyParam, $valParam);
                    break;
            }
        }

        foreach ($orderBy as $keyOrder => $valOrder) {
            $qbItems->orderBy($keyOrder, $valOrder);
        }

        //Run the Query
        $result = $qbItems->getQuery()->getResult();

        if (empty($result)) {
            return array();
        }

        return $this->getItemsTranslated($result);
    }

    /**
     * getItemsArranged
     *
     * This takes a given queryBuilder object, runs the result, and formats it into a usable array
     * of items
     * 
     * @return array
     */
    public function getItemsArranged($items)
    {
        $itemsArray = array();

        //Create the models for each item
        //NOTE: This is necessary. Do not try to deal with the items collection by itself, unless
        //you really like dealing with Doctrine entities.
        foreach ($items as $valItem) {
            $itemsArray[] = $this->createModel($valItem);
        }

        //Return the items array
        return $itemsArray;
    }

    /**
     * getItemFormFields function
     *
     * @access public
     * @param mixed $itemFormClass
     * @return mixed $result
     */
    public function getItemFormFields($itemFormClass)
    {
        foreach ($this->getFields() as $valField) {
            //ITEM_STATUS_PUBLISHED = Active, ITEM_STATUS_ARCHIVED = read only
            if ($valField->getStatus() == Field::ITEM_STATUS_PUBLISHED || $valField->getStatus() == Field::ITEM_STATUS_ARCHIVED) {
                //Currently, only type, label, and readonly are configurable. As such, only simple form elements will be usable.
                $result[$valField->getName()] = array(
                    'type' => $this->getFieldFormType($valField->getType()),
                    'label' => $valField->getLabel(),
                    'readonly' => ($valField->getStatus() == Field::ITEM_STATUS_ARCHIVED) ? 1 : 0
                );
            }
        }

        $result = array_merge($result, parent::getItemFormFields($itemFormClass));

        return $result;
    }

    /**
     * getFieldFormType
     *
     * This is used to match the correct form field type with the correct module field type
     * 
     * @param  string $fieldType
     * @return string
     */
    public function getFieldFormType($fieldType)
    {
        switch ($fieldType) {
            case 'float':
                $type = 'text';
                break;

            // new code added here starts
            //case 'integer':
            //     $type = 'text'; 
            //     break;   
            // new code added here ends 
            case 'pageselect':
                $type = 'multiselect';
                break;
            default:
                $type = $fieldType;
                break;
        }

        return $type;
    }

    /**
     * getItemProperty
     * 
     * @return PhoenixProperties\Entity\PhoenixProperty
     */
    public function getItemProperty($propertyId)
    {
        $propertiesService = $this->getServiceManager()->get('phoenix-properties');

        $property = $propertiesService->getItem($propertyId);

        return $property;
    }

    /**
     * save
     *
     * This adds the module to the data set, if there isn't already a module.
     * 
     * @param  object $model
     * @param  array $data
     * @return void
     */
    public function save($model, $data, $approved = false, $moduleName = null)
    {
        $this->getTranslateFields();
        $module = $this->getModule();

        if (isset($data['propertyId'])) {
            $currentProperty = $this->getServiceManager()->get('currentProperty');
            switch ($data['propertyId']) {
                case 'all':
                    $propertyId = $currentProperty->getId();
                    $data['allProperties'] = true;
                    break;
                case 'currentId':
                    $propertyId = $currentProperty->getId();
                    $data['allProperties'] = false;
                    break;
                default:
                    $propertyId = $data['propertyId'];
                    $data['allProperties'] = false;
                    break;
            }

            if (!((!$approved) && $module && $module->getCasAllowed() && $module->getCasEnabled())) {
                $data['propertyId'] = $this->getItemProperty($propertyId);
            }
        }

        if (!$model) {
            $model = $this->createModel();
        }

        if (!$model->getEntity()) {


            if (($approved) || ( $module && $module->getCasAllowed() && $module->getCasEnabled())) {
                $moduleService = $this->getServiceManager()->get('phoenix-dynamiclistmodule');
                $module = $moduleService->getItemBy(array('name' => $moduleName));
                $data['module'] = $module;
            } elseif (!($module->getCasAllowed() && $module->getCasEnabled())) {
                $data['module'] = $this->getModule();
            }
        }

        return parent::save($model, $data, $approved);
    }

    /**
     * attachBindingCalls
     * 
     * @param  \Zend\Form\Form $itemForm
     * @param  array $parameterVars
     * @return void
     */
    public function attachBindingCalls($itemForm, $parameterVars)
    {
        //Set the module name to our dynamic module, instead of dynamicListModule
        //This way media attachments will work with dynamic modules
        //$parameterVars['module'] = $this->getModuleName();
        //Run the attachBindingCalls method
        parent::attachBindingCalls($itemForm, $parameterVars);
    }

    private function csvToArray($file)
    {
        $rows = array();
        $headers = array();
        if (file_exists($file) && is_readable($file)) {
            $handle = fopen($file, 'r');
            while (!feof($handle)) {
                $row = fgetcsv($handle, 10240);
                if (empty($headers))
                    $headers = $row;
                else if (is_array($row))
                    $rows[] = array_combine($headers, $row);
            }
            fclose($handle);
        } else {
            throw new Exception($file . ' doesn`t exist or is not readable.');
        }
        return $rows;
    }

    private function arrayToCsvString($array)
    {
        ob_start();

        $f = fopen('php://output', 'w');
        foreach ($array as $line) {
            fputcsv($f, $line);
        }
        fclose($f);
        return ob_get_clean();
    }

    public function exportTranslations(LanguageEvent $e)
    {
        $translations = $e->getParam('exportArray');

        $modulesService = $this->getServiceManager()->get('phoenix-dynamiclistmodule');

        $modules = $modulesService->getItems();

        $moduleEntities = array();

        foreach ($modules as $valModule) {
            $moduleEntities[] = $valModule->getEntity();
        }

        if (empty($moduleEntities)) {
            return;
        }

        $moduleItems = $this->getAllItems($moduleEntities);
   foreach ($moduleItems as $valItem) {
            $moduleName = $valItem->getComponent()->getName();
            if (!isset($translations[$moduleName])) {
                $translations[$moduleName] = new ArrayObject();
            }

            $itemArray = new ArrayObject();
            $itemArray['fields'] = new ArrayObject();

            $itemTranslations = $valItem->getTranslations();

            foreach ($valItem->getFields() as $valField) {
                if ($valField->getTranslate() == 1) {
                    $fieldArray = new ArrayObject();

                    $fieldArray['defaultLanguageValue'] = $valItem->getValue($valField->getName());
                    $fieldArray['translations'] = new ArrayObject();

                    if (isset($itemTranslations[$valField->getName()])) {
                        $fieldArray['translations'] = $itemTranslations[$valField->getName()];
                    }

                    $itemArray['fields'][$valField->getName()] = $fieldArray;
                }
            }

            $translations[$moduleName][$valItem->getId()] = $itemArray;
        }
    }

    public function importTranslations(LanguageEvent $e)
    {
        $this->getDefaultEntityManager()->clear();
        $importText = $e->getParam('importText');

        $languagesService = $this->getServiceManager()->get('phoenix-languages');

        $languages = $languagesService->getItems();

        $languagesArray = array();

        foreach ($languages as $valLanguage) {
            $languagesArray[$valLanguage->getCode()] = $valLanguage;
        }

        $this->doImportTranslations($languagesArray, $importText, null);
    }

    public function doImportTranslations($languagesArray, $importText, $module = null)
    {
        $modulesService = $this->getServiceManager()->get('phoenix-dynamiclistmodule');

        $modules = $modulesService->getItems();

        foreach ($modules as $valModule) {
            parent::doImportTranslations($languagesArray, $importText, $valModule, $detachEntities);
        }
    }

    public function insertTranslation($field, $module, $language, $item, $content)
    {
        $insertTranslation = new \Languages\Entity\LanguageTranslations();
        $insertTranslation->setField($field);
        $insertTranslation->setComponent($module);
        $insertTranslation->setLanguage($language->getEntity());
        $insertTranslation->setItem($item->getEntity());
        $insertTranslation->setContent($content);
        $insertTranslation->setCreatedUserId($this->getCurrentUser()->getId());
        $insertTranslation->setModifiedUserId($this->getCurrentUser()->getId());
        $insertTranslation->setCreated(new \DateTime());
        $insertTranslation->setModified(new \DateTime());
        $insertTranslation->setStatus(1);
        $this->getDefaultEntityManager()->persist($insertTranslation);
        $this->getDefaultEntityManager()->flush();
    }

    // new added code starts: saurabh shirgaonkar, 7 aug 2014, 12:15 noon 

    public function getPageSelectOptions()
    {
        //echo "I am in Pages Service's getHotelOption<br/>"; 
        $optionsTotal = array();
        //inject default property as Not Assigned 
        $hotels = $this->getDefaultEntityManager()->getRepository('Pages\Entity\Pages')->findBy(array('status' => 1));

        foreach ($hotels as $keyHotel => $valHotel) {

            $optionsTotal[$valHotel->getId()] = $valHotel->getDataSection() . '/' . str_replace("default", "", $valHotel->getPageKey());
        }
        $optionsUnique = array_unique(($optionsTotal));

        natcasesort($optionsUnique);
        $arr1 = array('' => 'Select Pages');
        //$arr1 = array('' => 'Not Assigned'); 
        $options = $arr1 + $optionsUnique;
        return $options;
    }

    /*
      public function getPageSelectOptions()
      {
      //echo "I am in Pages Service's getHotelOption<br/>";
      $optionsTotal = array();
      //inject default property as Select Pages
      $hotels = $this->getDefaultEntityManager()->getRepository('Pages\Entity\Pages')->findBy(array('status' => 1));

      foreach ($hotels as $keyHotel => $valHotel)
      {

      $result = $this->getDefaultEntityManager()->getRepository(‘DynamicListModule\Entity\DynamicListModulePages’)->findBy(array(‘pageId’ => $this->getpageId()));
      $users = $em->getRepository('MyProject\Domain\User')->findBy(array('age' => 20));
      }
      $optionsUnique = array_unique(($optionsTotal));

      natcasesort($optionsUnique);
      $arr1 = array('' => 'Select Pages');
      $options = $arr1 + $optionsUnique;
      return $options;
      }
     */
    // new added code ends : saurabh shirgaonkar, 7 aug 2014, 12:15 noon  
}
