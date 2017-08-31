<?php

/**
 * The file for the Fields Class for the Dynamic List Module
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

use FlexibleForms\Model\Field;
use DynamicListModule\Service\Fields as BaseFields;

/**
 * The Fields Class for the Dynamic List Module
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
class Fields extends BaseFields
{

    /**
     * The name of the entity used by the associated model
     * @var string
     */
    protected $entityName = Field::ENTITY_NAME;

    /**
     * The name of the model class associated with this service
     * @var string
     */
    protected $modelClass = "\FlexibleForms\Model\Field";

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
        'select' => 'Select',
        'checkbox' => 'Checkbox',
        'date' => 'Date',
        'dropdownlist' => 'Dropdown List',
        'fileupload' => 'File Upload',
        'countries' => 'Countries'
    );

    /**
     * The default orderBy array for this service
     * @var array
     */
    protected $orderBy = array('orderNumber' => 'ASC', 'name' => 'ASC');
    protected $flexibleForm;

    /**
     * getFlexibleForm
     * 
     * Gets the module associated with this service
     * @return FlexibleForms\Model\Module
     */
    public function getFlexibleForm()
    {
        return $this->flexibleForm;
    }

    /**
     * setFlexibleForm
     *
     * Sets the module associated with this service
     * @param FlexibleForms\Model\Module $module
     */
    public function setFlexibleForm($flexibleForm)
    {
        $this->flexibleForm = $flexibleForm;
    }

    /**
     * setTypeOptions
     *
     * Sets the typeOptions option array on the given form field element
     * @param Zend\Form\Element\Select $typeField
     */
    public function setTypeOptions($typeField)
    {
        $typeField->setValueOptions($this->typeOptions);
    }

    public function addAdditionalData($data)
    {
        //echo "I am the service field";
        //Inject the module model into the data
        $data['form'] = $this->getFlexibleForm()->getEntity();

        return $data;
    }

    public function getItems($active = false, $showAll = false)
    {
        $items = array();
        if (!($results = $this->getItemsResult($this->getDefaultEntityManager(), $this->entityName, $active))) {
            return array();
        }

        foreach ($results as $valItem) {
            if ($this->getFlexibleForm() == $valItem->getForm()->getId()) {
                $items[] = $this->createModel($valItem);
            }
        }

        return $items;
    }

// Code for Select Options
    public function array_key_exists_wildcard($array, $search, $return = '')
    {
        $search = str_replace('\*', '.*?', preg_quote($search, '/'));
        $result = preg_grep('/^' . $search . '$/i', array_keys($array));
        if ($return == 'key-value')
            return array_intersect_key($array, array_flip($result));
        return $result;
    }

// Save or Update select Values
    public function getSelectValues($data)
    {

        $postValues = get_object_vars($data);

        $search = 'selectValue*';

        $arraySelect = $this->array_key_exists_wildcard($postValues, $search, 'key-value');

        $searchUpdate = 'updateValue*';

        $arraySelectUpdate = $this->array_key_exists_wildcard($postValues, $searchUpdate, 'key-value');

        $countArray = count($arraySelect);
        $countArrayUpdate = count($arraySelectUpdate);

        foreach ($arraySelectUpdate as $key => $value) {

            $strippedKey = substr($key, 11);
            $updateQuery = $this->getDefaultEntityManager()->createQueryBuilder();
            $updateQuery->update('DynamicListModule\Entity\DynamicListModuleSelectValues', 'dlms')
                    ->set('dlms.name', ':fieldname')
                    ->where('dlms.id = :key')
                    ->setParameter('fieldname', $value)
                    ->setParameter('key', $strippedKey);

            $updateQuery->getQuery()->execute();
        }

        for ($i = 1; $i <= $countArray; $i++) {
            if ($postValues['selectValue' . $i] != '') {
                $fieldValue = new \DynamicListModule\Entity\DynamicListModuleSelectValues();
                $fieldValue->setName($postValues['selectValue' . $i]);
                $fieldValue->setField($postValues['id']);
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

        return $options;
    }

    // Display Attachments
    public function getAttachments($fieldIdVal)
    {
        //$fieldName = $this->getFieldName($fieldIdVal);

        $options = array();
        //inject default property as Not Assigned
        $selectVal = $this->getDefaultEntityManager()->getRepository('FlexibleForms\Entity\FlexibleFormsAttachments')->findBy(array('fieldId' => $fieldIdVal));

        foreach ($selectVal as $keySelect => $valSelect) {
            $options[$valSelect->getAttachmentId()] = $valSelect->getAttachmentName();
        }

        return $options;
    }

    public function saveFile($attachmentData, $itemId)
    {
        $fileTmpLoc = $attachmentData['image-file']['tmp_name'];
        $pathAndName = str_replace("\\", "/", PHOENIX_PATH) . "/module/FlexibleForms/view/images/" . $attachmentData['image-file']['name'];

        $moveResult = move_uploaded_file($fileTmpLoc, $pathAndName);

        if ($moveResult == true) {
            $fieldValue = new \FlexibleForms\Entity\FlexibleFormsAttachments();
            $fieldValue->setAttachmentName($attachmentData['image-file']['name']);
            $fieldValue->setFieldId($itemId);

            $this->getDefaultEntityManager()->persist($fieldValue);
            $this->getDefaultEntityManager()->flush();
            //echo "File has been moved from ";
        } else {
            //echo "ERROR: File not moved correctly";
        }
    }

}
