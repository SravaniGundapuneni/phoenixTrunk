<?php
/**
 * The file for the ModuleItem model class for the DynamicListModule
 *
 * @category    Toolbox
 * @package     DynamicListModule
 * @subpackage  Model
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 1.14
 * @since       File available since release 1.14
 * @author      A. Tate <atate@travelclick.com>
 * @filesource
 */

// changes made to this file on 13 Aug 2013, 1:11 pm  
 
 
namespace DynamicListModule\Model;

/**
 * This class extends from the base ListItem class in ListModule
 */
use \ListModule\Model\ListItem;

/**
 * The ModuleItem class for the DynamicListModule
 *
 * @category    Toolbox
 * @package     DynamicListModule
 * @subpackage  Model
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 1.14
 * @since       File available since release 1.14
 * @author      A. Tate <atate@travelclick.com>
 */

class ModuleItem extends ListItem
{
    const ENTITY_NAME = '\DynamicListModule\Entity\DynamicListModuleItems';

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

        if (is_object($entity->getComponent())) {
            $this->setFields($entity->getComponent()->getComponentFields());
        }

        $this->setLanguageTranslations($entity->getLanguageTranslations());

        $this->setValues();
    }

    public function getItem()
    {
        return $this->getEntity();
    } 

    public function setComponent($component)
    {
        $this->getEntity()->setComponent($component);
    }

    public function getComponent()
    {
        return $this->getEntity()->getComponent();
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
        return ($this->getEntity()) ? lcfirst(str_replace(' ', '-', $this->getEntity()->getComponent()->getName())) : 'dynamicListModule';
    }
	
	 /**
     * getPageSelect
     *
     * returns the parent module, to be used for loading pages 
     * 
     * @return array
     */
    public function getPageSelect() 
    { 
        $properties = array(); 
        $propertyIds = $this->getDefaultEntityManager()->getRepository('Pages\Entity\Pages')->findBy(array('pageId' => $this->getId()));
        //print_r($propertyIds);		
        
		// for loop starts 
		foreach ($propertyIds as $valProperty) 
		{ 
            $property = $this->getDefaultEntityManager()->getRepository('Pages\Entity\Pages')->findOneBy(array('id' => $valProperty->getPageId())); 
            
			// if loop starts
			if (!empty($property)) 
			{ 
                $properties[] = $property; 
            }
            // if loop ends
			
        }
        // for loop ends 		
      	   		
		return $properties; 
    }
	// function getPageSelect ends.
	
		
	
	 /**
	 * setValues
	 *
	 * Determines the field type and processes the values to display on the form for every field  
	 * 
	 */
    protected function setValues()
    {
        $itemValues = $this->getEntity()->getItemFields();
        $valuesArray = array();
        foreach($itemValues as $valItemField) 
		{ 
		    $fieldEntity = $valItemField->getField();			
			
            switch ($fieldEntity->getType()) 
			{
						 
            case 'text':
			           
			case 'textarea':
                    $value = $valItemField->getValue();
                    break;
		
			case 'pageselect':
			
		    /*OLD METHOD STARTS*/ /*COMMENTED FOR TESTING*/	
			//$value = unserialize($valItemField->getValue());
			//break;
            /*OLD METHOD ENDS*/			
			
			//NEW METHOD IN PLACE STARTS 20 OCT 2014, 1:21 PM 
			//print_r($valItemField->getPageId());
		    // NEW METHOD IN PLACE STARTS 20 OCT 2014, 1:21 PM 
			$value1 = $valItemField->getitemId();
			print_r("Item Id :");
			print_r($this->getId());
			print_r(" &nbsp; ");
			/*
			//echo "<br>";
			//print_r("Page Id:");
			//$value = $valItemField->getPageId();			
			//print_r($value);
			//echo "<br>";
			//$result=array();
			//$result = $this->getDefaultEntityManager()->getRepository(‘DynamicListModule\Entity\DynamicListModulePages’)->findBy(array(‘itemId’ => $this->getId()));  
				   
			    foreach($result as &$currentvalue)
				{			
				print_r("Page Id: ");                                                                                                                                    
				print_r($currentvalue);
                $value=$valItemField->getItemsByPage();				
                echo '<br/>';
                echo '<br/>';	
				}
			    */			
			break;
		                 
           case 'multiselect':
		         $value = unserialize($valItemField->getValue());
				 $value1 = $valItemField->getitemId();
			     print_r("Item Id :");
			     print_r($this->getId());
			     print_r(" &nbsp; ");
				//$result = $this->getDefaultEntityManager()->getRepository(‘DynamicListModule\Entity\DynamicListModulePages’)->findBy(array(‘itemId’ =>$this->getId()));             
				//print_r($result);
				break;			
					
            default:$value = unserialize($valItemField->getValue());
                 break;
            }
            $valuesArray[$fieldEntity->getName()] = $value;
        }
        $this->values = $valuesArray;
    }

    public function getValue($field)
    {
        return (isset($this->values[$field])) ? $this->values[$field] : false;
    }

    public function getInputFilter()
    {
        $inputFilter = parent::getInputFilter();

        $fields = $this->getFields();

        foreach ($fields as $keyField => $valField) {
            if ($valField->getRequired() == 1) {
                $inputFilter->add(
                    $this->inputFactory->createInput(
                        array(
                            'name' => $valField->getName(),
                            'required' => true,
                            'allowEmpty' => false,
                            'validators' => array(
                                array(
                                    'name' => 'NotEmpty',
                                    'options' => array(
                                        'messages'=>array(
                                            \Zend\Validator\NotEmpty::IS_EMPTY => "{$valField->getLabel()} cannot be empty.",
                                        )
                                    )
                                )
                            ),
                        )
                    )
                );                 
            }
        }

        return $inputFilter;
    }

    public function exchangeArray($loadArray = array())
    {

        $translations = $this->getTranslations();
        $languages = $this->getLanguages();
        $defaultLanguage = $languages->getDefaultLanguage();
        $defaultCode = $defaultLanguage->getCode();
        $loadArray = $this->exchangeArrayTranslations($loadArray);

        if (!$this->entity) 
		{
            $this->entity = new $this->entityClass;
            if (isset($loadArray['module'])) 
			{
                $this->entity->setComponent($loadArray['module']->getEntity());
                unset($loadArray['module']);
                unset($loadArray['component']);
            }
        }

        if (isset($loadArray['categoryId'])) 
		{
            $this->entity->setCategoryId($loadArray['categoryId']);
            unset($loadArray['categoryId']);
        }

        if (isset($loadArray['allProperties'])) {
            $this->entity->setAllProperties($loadArray['allProperties']);
            unset($loadArray['allProperties']);
        }

        if (isset($loadArray['propertyId'])) {
            $this->entity->setProperty($loadArray['propertyId']->getEntity());
            unset($loadArray['propertyId']);
            unset($loadArray['property']);
        }

        $this->values = $loadArray;
    }
	
	protected function getPageSelectValues($pageid)
	{
		$properties = array(); 
        $propertyIds = $this->getDefaultEntityManager()->getRepository('Pages\Entity\Pages')->findBy(array('pageId' => $this->getId()));
        //print_r($propertyIds);		
        
		// for loop starts 
		foreach ($propertyIds as $valProperty) 
		{ 
            $property = $this->getDefaultEntityManager()->getRepository('Pages\Entity\Pages')->findOneBy(array('id' => $valProperty->getPageId())); 
            
			// if loop starts
			if (!empty($property)) 
			{ 
                $properties[] = $property; 
            }
            // if loop ends
			
        }
        // for loop ends 		
      	  
	   
		return $properties;
		
	}

    protected function formatValueForSave($value, $type)
    {
        if (is_null($value)) 
		{
            $value = '';
        }

        switch ($type) 
		{
            case 'text':
			
            case 'textarea':
                break;
				
            case 'integer':
                $value = (int) $value;
                break;
				
            case 'float':
                $value = (float) $value;
                break;
				
		   case 'pageselect':
			    $value = $value; 		    
                break;

	        case 'multiselect':
			     $value = $value; 		    
                 break;			
				
            case 'checkbox':
						 
			
            case 'radio':
                $value = ($value) ? true : false;
                break;
        }

        if ($type != 'text' && $type != 'textarea') 
		{
		   // if($type != 'multiselect')
		   //	 {		    
            $value = serialize($value);
		   //    }
		   // else
		   // {		    
           // $value = serialize($value);
			// sds temp added code starts 13 aug 2014, 11:06am
			// print_r($value);
			// echo "These are the multi select values";
			// die();
			
			// sds temp code added here ends 13 aug 2014, 11:06am 
			
            // }
			
        }

        return $value;
    }
	
	
	
	
	
	
	
	// new code added here for multiselect starts SDS 13 aug 2014, 12:12pm
    
	protected function prepareForSave()
	{
	   parent::prepareForSave();
	   
	 //   if ($this->getEntity()->getpageId())
	 //   {
	 //   $qbRemoveValues=$this->getDefaultEntityManager()->createQueryBuilder();
	
  //      $qbRemoveValues->delete('DynamicListModule\Entity\DynamicListModulePagesItemFields', 'dlmif')
  //                          ->where('dlmif.item = :item')
  //                          ->setParameter('item', $this->getEntity());

  //           $qbRemoveValues->getQuery()->execute();
		// }

        //Remove the old values
        if ($this->getEntity()->getId()) {
            $qbRemoveValues = $this->getDefaultEntityManager()->createQueryBuilder();

            $qbRemoveValues->delete('DynamicListModule\Entity\DynamicListModuleItemFields', 'dlmif')
                           ->where('dlmif.item = :item')
                           ->setParameter('item', $this->getEntity());

            $qbRemoveValues->getQuery()->execute();
        }        
    }
   // new code added here for multiselect ends SDS 	13 aug 2014, 12:12pm
/*	
	
	

    protected function prepareForSave()
    {
        parent::prepareForSave();

        //Remove the old values
        if ($this->getEntity()->getId()) {
            $qbRemoveValues = $this->getDefaultEntityManager()->createQueryBuilder();

            $qbRemoveValues->delete('DynamicListModule\Entity\DynamicListModuleItemFields', 'dlmif')
                           ->where('dlmif.item = :item')
                           ->setParameter('item', $this->getEntity());

            $qbRemoveValues->getQuery()->execute();
        }
    }
	
	*/

    //  target are for changes starts saurabh shirgaonkar @ 7 Aug 2014, 11:56 am
 
	public function save()
    {
        parent::save();

        //Next, loop through fields and create entities for the values
        $fields = $this->getEntity()->getComponent()->getComponentFields();
        $fieldValueArray = array();
		
        foreach($fields as $valField) 
		{
            $fieldValue = new \DynamicListModule\Entity\DynamicListModuleItemFields();
            $fieldValue->setStatus(1);
            $fieldValue->setUserId($this->getEntity()->getUserId());
            $fieldValue->setModified($this->getEntity()->getModified());
            $fieldValue->setCreated($this->getEntity()->getCreated());
			
			if($valField->getType()=="multiselect")
			{			
    			$result=$this->savePageSelect($pages,$itemid);
			} else {				
                $fieldValue->setValue($this->formatValueForSave($this->getValue($valField->getName()), $valField->getType()));
			}
			
			
			//print_r($fieldEntity->getType());
			//$fieldEntity2 = $valItemField->getId();
			//print_r($fieldEntity->getId());			
			//$fieldEntity3 = $valItemField->getName();
			//print_r($fieldEntity->getName());
									
			//print_r("i am before switch.");
			//print_r($fieldEntity);
			//die();
			
			//if($this->getValue($valField->getName()=='pageSelect')
			//{
			 //this is the loop			
			//}			
			
			// if or switch....check for page select  
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
	
	
	public function savePageSelect($pages,$itemid)
    { 	  
	    foreach($pages as $valField) 
		{
            $fieldValue = new \DynamicListModule\Entity\DynamicListModulePages();             
            //$fieldValue->setItemId($this->getEntity()->getitemId());
            //$fieldValue->setPageId($this->getEntity()->getpageId());
            $fieldValue->setItemId($itemid);
            $fieldValue->setPageId($valField); 			
            $this->getDefaultEntityManager()->persist($fieldValue);
            $this->getDefaultEntityManager()->flush();          
        }   
    } 
	
	// target area for changes ends saurabh shirgaonkar @ 7 Aug 2014, 11:56 am
	
	// target area for changes ends saurabh shirgaonkar @ 7 Aug 2014, 11:56 am

    public function getArrayCopy($datesToString = false)
    {
        $returnArray = array();

        foreach ($this->values as $keyField => $value) {
            if (is_float($value)) {
                $value = number_format($value, 2);
            }

            $languageOptions = $this->getLanguages()->getLanguageOptions();

            $returnArray[$keyField] = $value;

            foreach (array_keys($languageOptions) as $valLanguage) {
                if ($valLanguage == $this->getLanguages()->getDefaultLanguage()->getCode()) {
                    $translateValue = $value;
                } else {
                    $translatedValue = $this->getTranslation($keyField, $valLanguage);
                    $translateValue = ($translatedValue) ? $translatedValue : $value;
                }

                $returnArray[$keyField . '_' . $valLanguage] = $translateValue;
            }

            if ($this->getLanguages()->getDefaultLanguage()->getCode() != $this->getCurrentLanguage()->getCode()) {
                $returnArray[$keyField] = $returnArray[$keyField . '_' . $this->getCurrentLanguage()->getCode()];
            }
        }
        $returnArray['id'] = $this->getEntity()->getId();
        $returnArray['categoryId'] = $this->getEntity()->getCategoryId();
        $returnArray['propertyId'] = ($this->getEntity()->getAllProperties() == 1) ? 'all' : $this->getEntity()->getProperty()->getId();

        $currentProperty = $this->getServiceManager()->get('currentProperty');
        $corporateProperty = $this->getServiceManager()->get('corporateProperty');

        if ($currentProperty != $corporateProperty && $returnArray['property'] == $currentProperty->getEntity()->getId() && $this->getCurrentUser()->isCorporate() && $returnArray['property'] != 'all') {
            $returnArray['propertyId'] = 'currentId';
        }

        return $returnArray;
    }
}




// new code added starts 12 Aug 2014, 4:08 pm

 
/*  
// ... 
  public function getItemsByPage()
    {
        //parent::save();

        //Next, loop through fields and create entities for the values
        $fields = $this->getEntity()->getComponent()->getComponentFields();

        $fieldValueArray = array();

        foreach($fields as $valField) 
		{
            $fieldValue = new \DynamicListModule\Entity\DynamicListModuleItemFields();
            $fieldValue->setStatus(1);
            $fieldValue->setidId($this->getEntity()->getipId());
            $fieldValue->setitemId($this->getEntity()->getitemId());
            $fieldValue->setpageId($this->getEntity()->getpageId());
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

	*/
  // new code added ends 12 Aug 2014, 4:08 pm  