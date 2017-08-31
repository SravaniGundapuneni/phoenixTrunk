<?php
/**
 * RateForm Class
 *
 * This extends Zend\Form and creates a base form class for the Phoenix Rate Model.
 *
 * @category        Toolbox
 * @package         PhoenixRates
 * @subpackage      Form
 * @copyright       Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license         All Rights Reserved
 * @version         Release: 13.4
 * @since           File available since release 13.4
 * @author          A. Tate <atate@travelclick.com>
 * @filesource
 */

namespace PhoenixRates\Form;
use Zend\Form\Element;

/**
 * RateForm Class
 *
 * This extends Zend\Form and creates a base form class for the Phoenix Rate Model.
 *
 * @category        Toolbox
 * @package         PhoenixRates
 * @subpackage      Form
 * @copyright       Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license         All Rights Reserved
 * @version         Release: 13.4
 * @since           File available since release 13.4
 * @author          A. Tate <atate@travelclick.com>
 */
class RateForm extends \ListModule\Form\Form
{
    /**
     * __construct
     *
     * The form class constructor
     *
     * This sets up all of the fields, which can then be modified to the needs of the code the form is being used in.
     * @param string $name
     */
    public function __construct()
    {
        // we want to ignore the name passed
        parent::__construct('phoenixRate');
        $this->setAttribute('method', 'post');
    }
}