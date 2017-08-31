<?php

/**
 * EventForm Class
 *
 * This extends Zend/Form and it creates a base form class for the Phoenix Room Model.
 *
 * @category        Toolbox
 * @package         EventRooms
 * @subpackage      Form
 * @copyright       Copyright (c) 2013 TravelClick, Inc (http://travelclick.com)
 * @license         All Rights Reserved
 * @since           File available since release 13.5.5
 * @author          Kevin Davis <kedavis@travelclick.com>
 * @filesource
*/

namespace PhoenixEvents\Form;

class EventsForm extends \ListModule\Form\Form
{
    public function __construct($sm=null)
    {
        $this->serviceManager = $sm;
                
        parent::__construct('phoenixEvents');
        $this->setAttribute('method', 'post');

        
    }
}
