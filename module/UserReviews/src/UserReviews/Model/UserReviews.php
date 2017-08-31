<?php
/**
 * The file for the UserReviews model class for the UserReviews
 *
 * @category    Toolbox
 * @package     UserReviews
 * @subpackage  Model
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 1.14
 * @since       File available since release 1.14
 * @author      A. Tate <atate@travelclick.com>
 * @filesource
 */

namespace UserReviews\Model;

/**
 * This class extends from the base ListItem class in ListModule
 */
use \ListModule\Model\ListItem;

/**
 * The UserReviews class for the UserReviews
 *
 * @category    Toolbox
 * @package     UserReviews
 * @subpackage  Model
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 1.14
 * @since       File available since release 1.14
 * @author      H.Naik<hnaik@travelclick.com>
 */

class UserReviews extends ListItem
{
	 
    /**
     * The name of the entity associated with this model
     */    
    const REVIEWS_ENTITY_NAME = '\UserReviews\Entity\UserReviews';

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
        $this->entityClass = self::REVIEWS_ENTITY_NAME;
       
        parent::__construct($config, $fields);
        
    }
}