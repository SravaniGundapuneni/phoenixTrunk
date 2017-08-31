<?php
/**
 * The file for the Calendar model class for the Calendar
 *
 * @category    Toolbox
 * @package     Calendar
 * @subpackage  Model
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 1.14
 * @since       File available since release 1.14
 * @author      A. Tate <atate@travelclick.com>
 * @filesource
 */

namespace Calendar\Model;

/**
 * This class extends from the base ListItem class in ListModule
 */
use \ListModule\Model\ListItem;
use Calendar\Entity\EventCategory as EveCategory;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

/**
 * The MailingL:ist class for the MailingList
 *
 * @category    Toolbox
 * @package     MailingList
 * @subpackage  Model
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 1.14
 * @since       File available since release 1.14
 * @author      H.Naik<hnaik@travelclick.com>
 */

class PhoenixEventCategory extends ListItem
{
	 
    /**
     * The name of the entity associated with this model
     */    
    const EVENT_CATEGORY_ENTITY_NAME = '\Calendar\Entity\EventCategory';
	protected $eventCatEntity;
    /**
     * The name of the entity class associated with this model
     *
     * So, yeah, this is just a property version of the above constant. The reason for this so we keep
     * the functionality of the constant, while having the property available to be used for dynamically
     * loading the entity class, which for some reason you can't use constant values to do that.
     * 
     * @var string
     */
    //protected $entityClass = self::REVIEWS_ENTITY_NAME;
	
	 public function __construct($config = array(), $fields = array())
    {
        $this->entityClass = self::EVENT_CATEGORY_ENTITY_NAME;
       
        parent::__construct($config, $fields);
        
    }
	
	
	public function getInputFilter() {

        //echo "function called input filter";
		$inputFilter = new InputFilter();
		
		$factory = new InputFactory();
		
        $inputFilter->add($factory->createInput(array(
                    'name' => 'title',
					'required' => true,
					'allowEmpty' => false,
					'validators' => array(
                        array(
                            'name' => 'NotEmpty',
                            'options' => array(
                                
								'messages'=>array(
									\Zend\Validator\NotEmpty::IS_EMPTY => 'Category field is empty, Please Enter Event Category',
								)
                            ),
                        ),
						array(
									'name'    => 'StringLength',
									'options' => array(
										'encoding' => 'UTF-8',
										'min'      => 1,
										'max'      => 8,
										'messages'=>array(
												\Zend\Validator\StringLength::TOO_LONG => 'Category can not be more than 8 characters long.',
												\Zend\Validator\StringLength::TOO_SHORT => 'Category is required.', 
												
											),
										),
									),
									 array(
											'name' => 'Regex',
											'options' => array(
												'pattern' => '/^[\w.-]*$/i',
												'messages' => array(
													'regexInvalid' => 'Category title cannot contain special characters.',
												),
											),
									),
                       
                    ),	
					
					
        ))); 
		
		 
		$this->inputFilter = $inputFilter;
		
		//var_dump($this->inputFilter);
        //exit;
        return $this->inputFilter;
		}
}