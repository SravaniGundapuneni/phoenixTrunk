<?php
/**
 * The file for the PhoenixReview model class for the PhoenixReview
 *
 * @category    Toolbox
 * @package     PhoenixReview
 * @subpackage  Model
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 1.14
 * @since       File available since release 1.14
 * @author      A. Tate <atate@travelclick.com>
 * @filesource
 */

namespace PhoenixReview\Model;

/**
 * This class extends from the base ListItem class in ListModule
 */
use \ListModule\Model\ListItem;

/**
 * The PhoenixReview class for the PhoenixReview
 *
 * @category    Toolbox
 * @package     PhoenixReview
 * @subpackage  Model
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 1.14
 * @since       File available since release 1.14
 * @author      H.Naik<hnaik@travelclick.com>
 */

class PhoenixReview extends ListItem
{
	 
    /**
     * The name of the entity associated with this model
     */    
    const REVIEWS_ENTITY_NAME = '\PhoenixReview\Entity\PhoenixReview';

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