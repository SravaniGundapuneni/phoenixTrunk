<?php

/**
 * The file for the Fields Class for the Dynamic List Module
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

use DynamicListModule\Model\Field;
use ListModule\Service\Lists;
use ListModule\Model\ListItem;

/**
 * The Fields Class for the Dynamic List Module
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
class Fields extends Lists {

    /**
     * The name of the entity used by the associated model
     * @var string
     */
    protected $entityName = Field::ENTITY_NAME;

    /**
     * The name of the model class associated with this service
     * @var string
     */
    protected $modelClass = "\DynamicListModule\Model\Field";

    /**
     * The list of module field options that are available.
     * This is not a final list.
     * 
     * @var array
     */
    protected $typeOptions = array('text' => 'Text',
        'textarea' => 'Textarea',
        'integer' => 'Integer',
        'float' => 'Float',
        'checkbox' => 'Checkbox',
        'select' => 'Select',
        'multiselect' => 'Multi Select',
       'pageselect' => 'Page Select',       
        'date' => 'Date',
          'countries' => 'Countries'
    );
    
    
     /**
     * The default orderBy array for this service
     * @var array
     */
    protected $orderBy = array('orderNumber' => 'ASC', 'name' => 'ASC');

    /**
     * getModule
     * 
     * Gets the module associated with this service
     * @return DynamicListModule\Model\Module
     */
    public function getModule() {

        return $this->module;
    }

    /**
     * setModule
     *
     * Sets the module associated with this service
     * @param DynamicListModule\Model\Module $module
     */
    public function setModule($module) {
        $this->module = $module;
    }

    /**
     * setTypeOptions
     *
     * Sets the typeOptions option array on the given form field element
     * @param Zend\Form\Element\Select $typeField
     */
    public function setTypeOptions($typeField) {

        
        $typeField->setValueOptions($this->typeOptions);
    }

    public function addAdditionalData($data) {
        //echo "I am the service field";
        //Inject the module model into the data
        $data['component'] = $this->getModule()->getEntity();

        return $data;
    }

    /**
     * save
     * 
     * @param  DynamicListModule\Model\Field $model
     * @param  array $data
     * @return void
     */
    public function save($model, $data) {
       

        $selectValues = $this->getSelectValues($data);
        $data = $this->addAdditionalData($data);

        //Do the save

        parent::save($model, $data);
    }

// Code for Select Options
    public function array_key_exists_wildcard($array, $search, $return = '') {
        $search = str_replace('\*', '.*?', preg_quote($search, '/'));
        $result = preg_grep('/^' . $search . '$/i', array_keys($array));
        if ($return == 'key-value')
            return array_intersect_key($array, array_flip($result));
        return $result;
    }

// Save or Update select Values
    public function getSelectValues($data) {
        
        var_dump("get select values function called..");
        
        $postValues = get_object_vars($data);

        $search = 'selectValue*';

        $arraySelect = $this->array_key_exists_wildcard($postValues, $search, 'key-value');
         // print_r($arraySelect);

        $searchUpdate = 'updateValue*';

        $arraySelectUpdate = $this->array_key_exists_wildcard($postValues, $searchUpdate, 'key-value');
        // print_r($arraySelectUpdate);

        $countArray = count($arraySelect);
        $countArrayUpdate = count($arraySelectUpdate);

        foreach ($arraySelectUpdate as $key => $value) {
          
            $strippedKey = substr($key, 11);
            // echo $strippedKey.'=>'.$value.'<br/>'; 
            $updateQuery = $this->getDefaultEntityManager()->createQueryBuilder();
            $updateQuery->update('DynamicListModule\Entity\DynamicListModuleSelectValues', 'dlms')
                    ->set('dlms.name', ':fieldname')
                    ->where('dlms.id = :key')
                    ->setParameter('fieldname', $value)
                    ->setParameter('key', $strippedKey);

            $updateQuery->getQuery()->execute();
        
        }

        for ($i = 1; $i <= $countArray; $i++) {
            if($postValues['selectValue' . $i]!=''){
                $fieldValue = new \DynamicListModule\Entity\DynamicListModuleSelectValues();
                $fieldValue->setName($postValues['selectValue' . $i]);
                $fieldValue->setField($postValues['id']);
                var_dump($fieldValue);
                $this->getDefaultEntityManager()->persist($fieldValue);
                $this->getDefaultEntityManager()->flush();
            }
        }
    }

    // Display Select Values
    public function getSelectValueOptions($fieldIdVal) 
    {
        //$fieldName = $this->getFieldName($fieldIdVal);
        
            $options = array();
        //inject default property as Not Assigned
        $selectVal = $this->getDefaultEntityManager()->getRepository('DynamicListModule\Entity\DynamicListModuleSelectValues')->findBy(array('field' => $fieldIdVal));

        foreach ($selectVal as $keySelect => $valSelect) {
            $options[$valSelect->getId()] = $valSelect->getName();
        }
        
        //var_dump($options[$valSelect->getId()]);
        
        //var_dump($options);
        
        return $options;
    }

    public function getItems($active = false, $showAll = false) 
    {
        
        $items = array();
        if (!($results = $this->getItemsResult($this->getDefaultEntityManager(), $this->entityName, $active))) 
        {
            return array();
        }

        foreach ($results as $valItem) {
            //echo $valItem->getModule()->getId();exit;
            if ($this->getModule() == $valItem->getComponent()->getId()) {
                $items[] = $this->createModel($valItem);
            }
        }
        
        //exit();
        return $items;
    }
    

    /**
     * draft
     * @param  array $items
     * @return void
     */
    public function draft($items, $approved = false)
    {
        $this->updateItemsStatuses($this->entityName, 'p', ListItem::ITEM_STATUS_DRAFT, $items);
    }

    /**
     * archive
     * @param  array $items
     * @return void
     */
    public function archive($items, $approved = false)
    {
        $this->updateItemsStatuses($this->entityName, 'p', ListItem::ITEM_STATUS_ARCHIVED, $items);
    }

    /**
     * publish
     * @param  array $items
     * @return void
     */
    public function publish($items, $approved = false)
    {
        $this->updateItemsStatuses($this->entityName, 'p', ListItem::ITEM_STATUS_PUBLISHED, $items);
    }

    /**
     * trash
     * @param  array $items
     * @return void
     */
    public function trash($items, $approved = false)
    {
        $this->updateItemsStatuses($this->entityName, 'p', ListItem::ITEM_STATUS_TRASHED, $items);
    }   
    
    
    

    // Get fieldname from field Id
//    public function getFieldName($fieldIdVal) {
//        //Create the QueryBuilder instance
//        $fieldName = $this->getDefaultEntityManager()->createQueryBuilder();
//        //Build the Query
//        $fieldName->select('dlmf')
//                ->from('DynamicListModule\Entity\DynamicListModuleFields', 'dlmf')
//                ->where('dlmf.id = :field')
//                ->setParameter('field', $fieldIdVal);
//        //Run the Query
//        $result = $fieldName->getQuery()->getResult();
//        $fieldArray = array();
//        foreach ($result as $valResult) {
//            $fieldArray[] = array(
//                'name' => $valResult->getName(),
//            );
//        }
//        return $fieldArray[0]['name'];
//    }


        // SDS new code added starts ON 13 AUG 2014, 11:39AM
        /*
        public function save($model, $data)
        {
            $selectValues = $this->getMultiSelectValues($data);
            $data = $this->addAdditionalData($data);
            parent::save($model,$data);         
        }         

*/

        
        // SDS new code added starts ON 13 AUG 2014, 11:39AM   















}
