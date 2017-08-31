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

class PhoenixCalendar extends ListItem
{
	 
    /**
     * The name of the entity associated with this model
     */    
    const CALENDAR_ENTITY_NAME = '\Calendar\Entity\Calendar';
    protected $calendarEntity;
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
        $this->entityClass = self::CALENDAR_ENTITY_NAME;
       
        parent::__construct($config, $fields);
        
    }
    
    public function exchangeArray($loadArray)
    {
        if (!$this->getEntity() instanceof \Calendar\Entity\Calendar) {
            $this->entity = new \Calendar\Entity\Calendar();
        }
        
        $dateArrays = array('startDate', 'endDate');

        foreach ($loadArray as $key => $value) {
           
            if (in_array($key, $dateArrays) && is_string($value)) {
                $value = ($value) ? new \DateTime($value) : null;
            }
            
            $setMethod = 'set' . ucfirst($key);
        
            if (is_callable(array($this->getEntity(), $setMethod))) {
                $this->getEntity()->$setMethod($value);
            }
        }
    }
}