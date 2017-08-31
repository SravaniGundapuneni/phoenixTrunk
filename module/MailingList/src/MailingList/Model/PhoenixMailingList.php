<?php
/**
 * The file for the MailingList model class for the MailingList
 *
 * @category    Toolbox
 * @package     MailingList
 * @subpackage  Model
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 1.14
 * @since       File available since release 1.14
 * @author      A. Tate <atate@travelclick.com>
 * @filesource
 */

namespace MailingList\Model;

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

class PhoenixMailingList extends ListItem
{
	 
    /**
     * The name of the entity associated with this model
     */    
    const MAILINGLIST_ENTITY_NAME = '\MailingList\Entity\PhoenixMailingList';

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
        $this->entityClass = self::MAILINGLIST_ENTITY_NAME;
       
        parent::__construct($config, $fields);
        
    }
    
    public function exchangeArray($loadArray)
    {
        if (!$this->getEntity() instanceof \MailingList\Entity\MailingList) {
            $this->entity = new \MailingList\Entity\PhoenixMailingList();
        }
        
        foreach ($loadArray as $key => $value) {          
            
            $setMethod = 'set' . ucfirst($key);
        
            if (is_callable(array($this->getEntity(), $setMethod))) {
                $this->getEntity()->$setMethod($value);
            }
        }
    }
    public function save()
    {
        return parent::save();
    }
    
}