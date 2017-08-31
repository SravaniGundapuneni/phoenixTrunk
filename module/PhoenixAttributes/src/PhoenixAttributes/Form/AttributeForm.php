<?php
/**
 * AttributeForm Class
 *
 * This extends Zend\Form and creates a base form class for the Phoenix Attribute Model.
 *
 * @category        Toolbox
 * @package         PhoenixAttributes
 * @subpackage      Form
 * @copyright       Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license         All Rights Reserved
 * @version         Release: 13.4
 * @since           File available since release 13.4
 * @author          Grou <jrubio@travelclick.com>
 * @filesource
 */

namespace PhoenixAttributes\Form;

use Zend\InputFilter;
use Zend\Form\Element;
use Zend\Form\Form;

/**
 * AttributeForm Class
 *
 * This extends Zend\Form and creates a base form class for the Phoenix Attribute Model.
 *
 * @category        Toolbox
 * @package         PhoenixAttributes
 * @subpackage      Form
 * @copyright       Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license         All Rights Reserved
 * @version         Release: 13.4
 * @since           File available since release 13.4
 * @author          Grou <jrubio@travelclick.com>
 */
class AttributeForm extends \ListModule\Form\Form
{
    /**
     * __construct
     *
     * The form class constructor
     *
     * This sets up all of the fields, which can then be modified to the needs of the code the form is being used in.
     * @param string $name
     */
    public function __construct($name = null)
    {
        // we want to ignore the name passed
        parent::__construct('phoenixAttribute');
        $this->setAttribute('method', 'post');
    }
}