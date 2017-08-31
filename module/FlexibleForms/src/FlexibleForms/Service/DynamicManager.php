<?php

/**
 * The file for the FlexibleForms Class for the Dynamic List Module
 *
 * @category    Toolbox
 * @package     FlexibleForms
 * @subpackage  Service
 * @copyright   Copyright (c) 2014 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 1.14
 * @since       File available since release 1.14
 * @author      A. Tate <atate@travelclick.com>
 * @filesource
 */

namespace FlexibleForms\Service;

use FlexibleForms\Model\ModuleItem;
use ListModule\Service\Lists;
use FlexibleForms\Model\Field;
use Zend\Form\Element;

/**
 * The FlexibleForms Class for the Dynamic List Module
 *
 * @category    Toolbox
 * @package     FlexibleForms
 * @subpackage  Service
 * @copyright   Copyright (c) 2014 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 1.14
 * @since       File available since release 1.14
 * @author      A. Tate <atate@travelclick.com>
 */
class DynamicManager extends Lists
{

    /**
     * The name of the entity used by the associated model
     * @var string
     */
    protected $entityName = ModuleItem::ENTITY_NAME;

    /**
     * The name of the model class associated with this service
     * @var string
     */
    protected $modelClass = "\FlexibleForms\Model\ModuleItem";

    /**
     * The current dynamic module's name
     * @var string
     */
    protected $moduleName = '';

    /**
     * setModuleName
     * 
     * @param string $moduleName
     */
    public function setFormName($formName)
    {
        $this->formName = $formName;
    }

    /**
     * getFormName
     * @return string
     */
    public function getFormName()
    {
        return $this->formName;
    }

    /**
     * setModule
     * 
     * @param FlexibleForms\Model\Module $flexibleForm
     */
    public function setFlexibleForm($flexibleForm)
    {
        $this->flexibleForm = $flexibleForm;
    }

    /**
     * getModule
     * 
     * @return FlexibleForms\Model\Module
     */
    public function getFlexibleForm()
    {
        return $this->flexibleForm;
    }

    /**
     * getForm
     * 
     * @param  string $formName [description]
     * @param  mixed $sl
     * @return FlexibleForms\Form\ModuleItemForm
     */
    public function getForm($formName, $sl = null)
    {
        $currentProperty = $this->getServiceManager()->get('currentProperty');
        //Set the name of the form to retrieve
        $formName = '\FlexibleForms\Form\ModuleItemForm';
          $form = parent::getForm($formName, $sl);
        $fields = $this->getFields();

        foreach ($fields as $key => $value) {
            if ($value->getType() !== 'select' && $value->getType() !== 'multiselect' && $value->getType() !== 'pageselect') {
                continue;
            }
            $valOptions = $this->getDefaultEntityManager()->getRepository('FlexibleForms\Entity\FlexibleFormsSelectValues')->findBy(array('field' => $value->getId()));
            if (count($valOptions) != 0) {
                foreach ($valOptions as $keyOptions => $valOptions) {
                    $options_total[$valOptions->getName()] = $valOptions->getName();
                }
                $form->get($value->getName())->setValueOptions($options_total);
            }

            // adding countries dropdown
            if ($value->getType() == 'countries') {
                $form->get($value->getName())->setValueOptions($this->getCountriesOptions());
            }
        }
        //Retrieve and return the form
      
        $form->remove('action');
        $form->remove('id');
        // var_dump(get_class_methods($form));
        // die('FILE: ' . __FILE__ . ' LINE: ' . __LINE__);
        $form->add(array(
            'name' => 'formName',
            'type' => 'hidden',
        ));
        $form->get('formName')->setValue($this->getFormName());
        $form->add(array(
            'name' => 'flexFormSubmit',
            'type' => 'hidden',
        ));
        $form->get('flexFormSubmit')->setValue(true);
        $form->add(array(
            'name' => 'currentProperty',
            'type' => 'hidden',
        ));
        $form->get('currentProperty')->setValue($currentProperty->getId());
        $send = new Element\Submit('send');
        $send->setValue('Submit');
        $form->add($send);

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
    public function getFields($showInList = true)
    {
        //Create the query builder instance
        $qbItems = $this->getDefaultEntityManager()->createQueryBuilder();

        //Build the query
        $qbItems->select('dlm', 'mf')
                ->from('FlexibleForms\Entity\FlexibleForm', 'dlm')
                ->leftJoin('dlm.formFields', 'mf')
                ->where('dlm.name = :formName')
                ->orderBy('mf.orderNumber')
                ->setParameter('formName', $this->formName);

        //Add the optional statement, if necessary
        if ($showInList) {
            $qbItems->andWhere('mf.showInList = 1');
        }

        //Run the Query
        $result = $qbItems->getQuery()->getResult();

        //Get the fields
        $fields = $result[0]->getFormFields();

        //Return the fields collection
        return $fields;
    }

    public function useCategories()
    {
        $module = $this->getModule();

        return $module->getCategories();
    }

    /**
     * getItems
     * 
     * @return array
     */
    public function getItems()
    {
        //Create the QueryBuilder instance
        $qbItems = $this->getDefaultEntityManager()->createQueryBuilder();

        //Build the Query
        $qbItems->select('dlm')
                ->from('FlexibleForms\Entity\FlexibleForm', 'dlm')
                ->where('dlm.name = :moduleName')
                ->setParameter('moduleName', $this->moduleName);

        //Run the Query
        $result = $qbItems->getQuery()->getResult();

        $arrayItems = array();

        if (empty($result)) {
            return array();
        }

        //Get the items for the module
        $items = $result[0]->getModuleItems();

        return $this->getItemsArranged($items);
    }

    public function getItemsBy($params = array(), $orderBy = array())
    {
        //Create the QueryBuilder instance
        $qbItems = $this->getDefaultEntityManager()->createQueryBuilder();

        $currentProperty = $this->getServiceManager()->get('currentProperty');

        //Build the Query
        $qbItems->select('mi')
                ->from('FlexibleForms\Entity\FlexibleFormsItems', 'mi')
                ->where('mi.module = :module')
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

        return $this->getItemsArranged($result);
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
                    'label' => $valField->getDisplayName(),
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
    public function save($model, $data)
    {

        $this->getTranslateFields();
        $module = $this->getModule();
        if (!$model->getEntity()) {
            $data['form'] = $this->getFlexibleForm();
        }

        return parent::save($model, $data);
    }

    public function departEmails($depName, $currentPropertyId, $showInList = true)
    {

        switch ($depName):
            case 'Feedback/Customer Service':
                $depId = 1;
                break;
            case 'Billing Inquiries':
                $depId = 2;
                break;
            case 'Concierge':
                $depId = 3;
                break;
            case 'Reservations':
                $depId = 4;
                break;
            case 'Group Reservations':
                $depId = 5;
                break;
            case 'Meetings, Events and Weddings':
                $depId = 6;
                break;
        endswitch;
        $qbItems = $this->getDefaultEntityManager()->createQueryBuilder();

        //Build the query
        $qbItems->select('fle')
                ->from('FlexibleForms\Entity\FlexibleFormsDepartmentEmails', 'fle')
                ->where('fle.departmentId = :depId')
                ->andWhere('fle.property = :propertyId')
                ->setParameter('depId', $depId)
                ->setParameter('propertyId', $currentPropertyId);

        if ($showInList) {
            $qbItems->andWhere('fle.status = 1');
        }

        //Run the Query
        $result = $qbItems->getQuery()->getResult();
        if ($result) {
            return ($result[0]->getDepartmentEmail());
        }
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
        //Set the module name to our dynamic module, instead of flexibleForms
        //This way media attachments will work with dynamic modules
        $parameterVars['module'] = $this->getModuleName();

        //Run the attachBindingCalls method
        parent::attachBindingCalls($itemForm, $parameterVars);
    }

}
