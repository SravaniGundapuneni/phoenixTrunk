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
//use \ListModule\Model\ListItem;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use ListModule\Model\UnifiedListItem;
use Zend\Validator\Hostname;

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

class PhoenixCalendarEvent extends UnifiedListItem
{
	 
    /**
     * The name of the entity associated with this model
     */    
    const CALENDAR_EVENT_ENTITY_NAME = 'Calendar\Entity\CalendarItems';
    const CALENDAR_DATA_ENTITY = 'calendarData';
    protected $dataEntityClass = 'Calendar\Entity\CalendarEvent';
	private $calendarEventEntity;
	protected $inputFilter;
    /**
     * The name of the entity class associated with this model
     *
     * So, yeah, this is just a property version of the above constant. The reason for this so we keep
     * the functionality of the constant, while having the property available to be used for dynamically
     * loading the entity class, which for some reason you can't use constant values to do that.
     * 
     * @var string
     */
    //protected $entityClass = self::CALENDAR_EVENT_ENTITY_NAME;
	
	public function __construct($config = array(), $fields = array())
    {
        $this->entityClass = self::CALENDAR_EVENT_ENTITY_NAME;
        $this->dataEntity = self::CALENDAR_DATA_ENTITY;  

        parent::__construct($config, $fields);
    }
}