<?php
/**
 * The file for the ContentFilter model class for the ContentFilter
 *
 * @category    Toolbox
 * @package     ContentFilter
 * @subpackage  Model
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 1.14
 * @since       File available since release 1.14
 * @author      A. Tate <atate@travelclick.com>
 * @filesource
 */

namespace ContentFilter\Model;

/**
 * This class extends from the base ListItem class in ListModule
 */
use \ListModule\Model\ListItem;

/**
 * 
 * @category    Toolbox
 * @package     ContentFilter
 * @subpackage  Model
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 1.14
 * @since       File available since release 1.14
 * @author      Saurabh Shirgaonkar <sshirgaonkar@travelclick.com>
 */

class PhoenixContentFilter extends ListItem
{	 
    /**
     * The name of the entity associated with this model
     */    
    const CONTENTFILTER_ENTITY_NAME = '\ContentFilter\Entity\PhoenixContentFilter';

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
        $this->entityClass = self::CONTENTFILTER_ENTITY_NAME;
       
        parent::__construct($config, $fields);
        
    }
}