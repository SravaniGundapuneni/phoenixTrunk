<?php
/**
 * Polytext Model
 *
 * @category    Toolbox
 * @package     Languages
 * @subpackage  Model
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.5.5
 * @since       File available since release 13.5.5
 * @author      Jose A. Duarte <jduarte@travelclick.com>
 * @filesource
 */

namespace Languages\Model;

class Polytext extends \ListModule\Model\ListItem
{
    const ENTITY_NAME = 'Languages\Entity\Polytext';

    public function __construct()
    {
        $this->entityClass = self::ENTITY_NAME;
        parent::__construct();
    }
}
