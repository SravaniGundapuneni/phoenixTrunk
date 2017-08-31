<?php
/**
 * The PhpSample Service
 *
 * @category    Toolbox
 * @package     PhpSample
 * @subpackage  Service
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.6
 * @since       File available since release 13.6
 * @author      A. Tate <atate@travelclick.com>
 * @filesource
 */
namespace PhpSample\Service;

use PhpSample\Model\PhpSampler;
use Pages\EventManager\Event as PagesEvent;
use ListModule\Service\UnifiedLists as BaseLists;

class PhpSample extends BaseLists
{
    protected $orderList = true;

    /**
     * __construct
     *
     * Construct our Map Markers service
     *
     * @return void
     */
    public function __construct()
    {
        $this->entityName = PhpSample::PHPSAMPLE_ENTITY_NAME;
        $this->modelClass = "\PhpSample\Model\PhpSampler";
        $this->dataItem = PhpSample::PHPSAMPLE_DATA_ENTITY;
    }

    
    public function getCategoryOptions()
    {
        $option = $this->getCategoriesArray(array('status'=>'1'));
        if ($this->currentUser->getIsCorporate()) {
             $property = new \Zend\Form\Element\Hidden('category');
            $property->setValue('Null');
        } else {
            $property = new \Zend\Form\Element\Select('category');
            $property->setLabel('Categories');
            $property->setLabelAttributes(array('class' => 'blockLabel'));
            $property->setAttribute('class', 'stdTextInput');
            $property->setValueOptions($option);
        }
        
        return $option;
    }     
    
    public function sampleOutput(){
        
        $items = $this->defaultEntityManager->getConnection()->fetchAll("select
             email from site_users");
        
        return $items;
        
    }
    
    /**
     * getPropertyIdOptions
     *
     * @todo  Remove this from this service class, so the PhoenixPropeties module will be properly decoupled from Pages.
     * 
     * @return array
     */
    public function getPropertyIdOptions ()
    {
         //echo "I am in Pages Service's getHotelOption<br/>";
        $options = array();
         //inject default property as Not Assigned
        $options[0] = 'Not Assigned';
        $hotels = $this->getDefaultEntityManager()->getRepository('PhoenixProperties\Entity\PhoenixProperty')->findBy(array('status' => 1));

        foreach ($hotels as $keyHotel => $valHotel) {
            $options[$valHotel->getId()] = $valHotel->getName();
        }

         if ($this->currentUser->getIsCorporate()) {
             $property = new \Zend\Form\Element\Hidden('propertyId');
            $property->setValue('Null');
        } else {
            $property = new \Zend\Form\Element\Select('propertyId');
            $property->setLabel('Hotel');
            $property->setLabelAttributes(array('class' => 'blockLabel'));
            $property->setAttribute('class', 'stdTextInput');
            $property->setValueOptions($options);
        }
       
        return $options;
    }
    
    
    
    
    public function setPropertyField($fieldKey = 'propertyId', $itemForm)
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

            $property = new \Zend\Form\Element\Select('property');
            $property->setLabel('Hotel');
            $property->setLabelAttributes(array('class' => 'blockLabel'));
            $property->setAttribute('class', 'stdTextInput');
            $property->setValueOptions($optionsList);
        } else {
            $property = new \Zend\Form\Element\Hidden('property');
            $property->setValue($currentProperty->getId());
        }

        $itemForm->add($property);
    }
    
}