<?php
/**
 * The file for the Module model class for the DynamicListModule
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

namespace DynamicListModule\Model;

/**
 * This class extends from the base ListItem class in ListModule
 */
use \ListModule\Model\ListItem;

/**
 * The Module class for the DynamicListModule
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

class Module extends ListItem
{
    /**
     * The name of the entity associated with this model
     */    
    const ENTITY_NAME = '\Toolbox\Entity\Components';

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

    public function exchangeArray($loadArray = array())
    {
        parent::exchangeArray($loadArray);
        $this->entity->setDynamic(1);
    }
}