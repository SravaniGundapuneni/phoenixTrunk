<?php
/**
 * This extends Zend\Form and creates a base form class for the FlexibleForms Field.
 *
 * @category    Toolbox
 * @package     FlexibleForms
 * @subpackage  Form
 * @copyright   Copyright (c) 2014 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 1.14
 * @since       File available since release 1.14
 * @author      A. Tate <atate@travelclick.com>
 * @filesource
 */

namespace FlexibleForms\Form;

use Zend\InputFilter;
use Zend\Form\Element;

/**
 * This extends Zend\Form and creates a base form class for the FlexibleForms Field.
 *
 * @category    Toolbox
 * @package     FlexibleForms
 * @subpackage  Form
 * @copyright   Copyright (c) 2014 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 1.14
 * @since       File available since release 1.14
 * @author      A. Tate <atate@travelclick.com> 
 */
class FieldForm extends \ListModule\Form\Form
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
        parent::__construct('flexibleFormsItems');
        $this->setAttribute('method', 'post');
    }
}