<?php
/**
 * The file for the DynamicListModule Class for the Dynamic List Module
 *
 * @category    Toolbox
 * @package     DynamicListModule
 * @subpackage  Service
 * @copyright   Copyright (c) 2014 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 1.14
 * @since       File available since release 1.14
 * @author      A. Tate <atate@travelclick.com>
 * @filesource
 */

namespace DynamicListModule\Service;

use DynamicListModule\Model\Module;
use ListModule\Service\Lists;

/**
 * The DynamicListModule Class for the Dynamic List Module
 *
 * @category    Toolbox
 * @package     DynamicListModule
 * @subpackage  Service
 * @copyright   Copyright (c) 2014 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 1.14
 * @since       File available since release 1.14
 * @author      A. Tate <atate@travelclick.com>
 */
class DynamicListModule extends Lists
{
    /**
     * The name of the entity used by the associated model
     * @var string
     */
    protected $entityName = MODULE::ENTITY_NAME;

    /**
     * The name of the model class associated with this service
     * @var string
     */
    protected $modelClass = "\DynamicListModule\Model\Module";

    public function getItems($active = false, $showAll = false)
    {
        $findByArray = array('dynamic' => 1);

        if ($active) {
            $findByArray['status'] = 1;
        }

        return $this->getItemsBy($findByArray, $this->orderBy);
    } 
}