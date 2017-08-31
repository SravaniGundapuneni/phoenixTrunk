<?php
/**
 * The file for the ModuleItem model class for the FlexibleForms
 *
 * @category    Toolbox
 * @package     FlexibleForms
 * @subpackage  Model
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 1.14
 * @since       File available since release 1.14
 * @author      A. Tate <atate@travelclick.com>
 * @filesource
 */

namespace FlexibleForms\Model;

/**
 * This class extends from the base ListItem class in ListModule
 */
use \ListModule\Model\ListItem;

/**
 * The ModuleItem class for the FlexibleForms
 *
 * @category    Toolbox
 * @package     FlexibleForms
 * @subpackage  Model
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 1.14
 * @since       File available since release 1.14
 * @author      A. Tate <atate@travelclick.com>
 */

class ModuleItem extends ListItem
{
    const ENTITY_NAME = '\FlexibleForms\Entity\FlexibleFormsItems';

    /**
     * The name of the entity class associated with this model
     *
     * So, yeah, this is just a property version of the above constant. The reason for this so we keep
     * the functionality of the constant, while having the property available to be used for dynamically
     * loading the entity class, which for some reason you can't use constant values to do that.
     * 
     * @var string
     */
    protected $entityClass = self::ENTITY_NAME;

    public function setEntity($entity)
    {
        parent::setEntity($entity);

        $this->setValues();
    }

    /**
     * getParentModule
     *
     * returns the parent module, to be used for media attachments.
     * This was abstracted out into a method so dynamic modules can use media attachments
     * 
     * @return string
     */
    protected function getParentModule()
    {
        return ($this->getEntity()) ? $this->getEntity()->getModule()->getName() : 'flexibleForms';
    }

    protected function setValues()
    {
        $itemValues = $this->getEntity()->getItemFields();

        $valuesArray = array();

        foreach($itemValues as $valItemField) {
            switch ($valItemField->getField()->getType()) {
                case 'text':
                case 'textarea':
                    $value = $valItemField->getValue();
                    break;
                default:
                    $value = unserialize($valItemField->getValue());
                    break;
            }

            $valuesArray[$valItemField->getField()->getName()] = $value;
        }

        $this->values = $valuesArray;
    }

    public function getValue($field)
    {
        return (isset($this->values[$field])) ? $this->values[$field] : false;
    }

    public function exchangeArray($loadArray = array())
    {
        if (!$this->entity) {
            $this->entity = new $this->entityClass;

            if (isset($loadArray['form'])) {
                $this->entity->setForm($loadArray['form']->getEntity());
                unset($loadArray['form']);
            }
        }

        if (isset($loadArray['allProperties'])) {
            $this->entity->setAllProperties($loadArray['allProperties']);
            unset($loadArray['allProperties']);
        }

        if (isset($loadArray['property'])) {
            $this->entity->setProperty($loadArray['property']->getEntity());
            unset($loadArray['property']);
        }

        $this->values = $loadArray;     
    }

    protected function formatValueForSave($value, $type)
    {
        if (is_null($value)) {
            $value = '';
        }

        switch ($type) {
            case 'text':
            case 'textarea':
                break;
            case 'integer':
                $value = (int) $value;
                break;
            case 'float':
                $value = (float) $value;
                break;
            case 'checkbox':
            case 'radio':
                $value = ($value) ? true : false;
                break;
        }

        if ($type != 'text' && $type != 'textarea') {
            $value = serialize($value);
        }

        return $value;
    }

    protected function prepareForSave()
    {
        parent::prepareForSave();

        //Remove the old values
        if ($this->getEntity()->getId()) {
            $qbRemoveValues = $this->getDefaultEntityManager()->createQueryBuilder();

            $qbRemoveValues->delete('FlexibleForms\Entity\FlexibleFormsItemFields', 'dlmif')
                           ->where('dlmif.item = :item')
                           ->setParameter('item', $this->getEntity());

            $qbRemoveValues->getQuery()->execute();
        }
    }

    public function save()
    {
        parent::save();

        //Next, loop through fields and create entities for the values
        $fields = $this->getEntity()->getForm()->getFormFields();

        $fieldValueArray = array();

        foreach($fields as $valField) {
            $fieldValue = new \FlexibleForms\Entity\FlexibleFormsItemFields();
            $fieldValue->setStatus(1);
            $fieldValue->setUserId($this->getEntity()->getUserId());
            $fieldValue->setModified($this->getEntity()->getModified());
            $fieldValue->setCreated($this->getEntity()->getCreated());
            $fieldValue->setValue($this->formatValueForSave($this->getValue($valField->getName()), $valField->getType()));
            $fieldValue->setField($valField);
            $fieldValue->setItem($this->getEntity());
            $this->getDefaultEntityManager()->persist($fieldValue);
            $this->getDefaultEntityManager()->flush();
            $fieldValueArray[] = $fieldValue;
        }

        $valField->setFieldValues($fieldValueArray);
        $this->getEntity()->setItemFields($fieldValueArray);
        $this->getDefaultEntityManager()->flush();        
    }

    public function getArrayCopy($datesToString = false)
    {
        $returnArray = array();

        foreach ($this->values as $keyField => $value) {
            if (is_float($value)) {
                $value = number_format($value, 2);
            }
            $returnArray[$keyField] = $value;
        }
        $returnArray['categoryId'] = $this->getEntity()->getCategoryId();
        $returnArray['property'] = ($this->getEntity()->getAllProperties() == 1) ? 'all' : $this->getEntity()->getProperty()->getId();

        return $returnArray;
    }
}