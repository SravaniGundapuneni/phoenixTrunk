<?php

/**
 * RoomForm Class
 *
 * This extends Zend/Form and it creates a base form class for the Phoenix Room Model.
 *
 * @category        Toolbox
 * @package         PhoenixRooms
 * @subpackage      Form
 * @copyright       Copyright (c) 2013 TravelClick, Inc (http://travelclick.com)
 * @license         All Rights Reserved
 * @since           File available since release 13.4
 * @author          Kevin Davis <kedavis@travelclick.com>
 * @filesource
*/

namespace PhoenixRooms\Form;

class RoomForm extends \ListModule\Form\Form
{
    public function __construct($name = null)
    {
        // we want to ignore the name passed
        parent::__construct('phoenixRoom');
        $this->setAttribute('method', 'post');
    }
}