<?php

/**
 * This extends Zend\Form and creates a base form class for the FlexibleForms.
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
 * This extends Zend\Form and creates a base form class for the FlexibleForms.
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
class ModuleItemForm extends \ListModule\Form\Form {

    /**
     * __construct
     *
     * The form class constructor
     *
     * This sets up all of the fields, which can then be modified to the needs of the code the form is being used in.
     * @param string $name
     */
    public function __construct($name = null) {
        // we want to ignore the name passed
        parent::__construct('flexibleFormsItem');
        $this->setAttribute('method', 'post');
        $this->add(array(
            'name' => 'department',
            'type' => 'select',
            'options' => array(
                'label' => 'Contact a Specific Department*',
                'value_options' => array(
                        '- select -' => '- select -',
                    'Feedback/Customer Service' => 'Feedback/Customer Service',
                    'Billing Inquiries' => 'Billing Inquiries',
                    'Concierge' => 'Concierge',
                    'Reservations' => 'Reservations',
                    'Group Reservations' => 'Group Reservations',
                    'Meetings, Events and Weddings' => 'Meetings, Events and Weddings',
                ),
                'label_attributes' => array(
                    'class' => 'blocklabel'),
                '- select -' => '- select -',
                'Feedback/Customer Service' => 'Feedback/Customer Service',
                'Billing Inquiries' => 'Billing Inquiries',
                'Concierge' => 'Concierge',
                'Reservations' => 'Reservations',
                'Group Reservations' => 'Group Reservations',
                'Meetings, Events and Weddings' => 'Meetings, Events and Weddings',
            ),
            'attributes' => array(
                'class' => 'stdTextInput',
            )
        ));
        $this->add(array(
            'name' => 'title',
            'type' => 'select',
            'options' => array(
                'label' => 'Title',
                'value_options' => array(
                    '- select -' => '- select -',
                    'Mr.' => 'Mr.',
                    'Ms.' => 'Ms.',
                    'Dr.' => 'Dr.',
                    'Mrs.' => 'Mrs.'
                   
                ),
                'label_attributes' => array(
                    'class' => 'blocklabel'),
                '- select -' => '- select -',
               'Mr.' => 'Mr.',
                    'Ms.' => 'Ms.',
                    'Dr.' => 'Dr.',
                    'Mrs.' => 'Mrs.'
            ),
            'attributes' => array(
                'class' => 'stdTextInput',
            )
        ));
    }

}
