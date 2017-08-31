<?php

/**
 * The SeoMetaText Service
 *
 * @category    Toolbox
 * @package     SeoMetaText
 * @subpackage  Service
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.6
 * @since       File available since release 13.6
 * @author      A. Tate <atate@travelclick.com>
 * @filesource
 */

namespace Toolbox\Service;

use Toolbox\Model\Components;
use Pages\EventManager\Event as PagesEvent;

class Modules extends \ListModule\Service\Lists {

    /**
     * __construct
     *
     * Construct our Map Markers service
     *
     * @return void
     */
    public function __construct() {

        $this->entityName = Components::ENTITY_NAME;
        $this->modelClass = "\Toolbox\Model\Components";
    }


}
