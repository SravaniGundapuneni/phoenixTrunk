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

use FlexibleForms\Model\Item;
use ListModule\Service\Lists;

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
class Items extends Lists
{
   
    /**
     * The name of the entity used by the associated model
     * @var string
     */        
    protected $entityName = Item::ENTITY_NAME;

    /**
     * The name of the model class associated with this service
     * @var string
     */        
    protected $modelClass = "\FlexibleForms\Model\Item";

    

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
     * getForm
     * 
     * @param  string $formName
     * @param  mixed $sl
     * @return FlexibleForms\Form\FieldForm
     */
    public function getForm($formName, $sl=null)
    {
     
        $formName = '\FlexibleForms\Form\ItemForm';

        return parent::getForm($formName, $sl);
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
}