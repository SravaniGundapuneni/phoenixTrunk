<?php
/**
 * PhoenixRates Module Fields
 *
 * PhoenixRates Module Fields for all module forms
 *
 * @category    Toolbox
 * @package     PhoenixRates
 * @subpackage  Forms
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.4.0
 * @since       File available since release 13.4.0
 * @author      Jose A Duarte <jduarte@travelclick.com>
 * @filesource
 */

use \ListModule\StdLib\FormHelper;

/**
 * Here is the list of supported methods
 *
 *   Fields
 *
 *     ModuleHelper::text($name, $label, $options = array())
 *     ModuleHelper::textarea($label, $readonly = false, $class = false)
 *     ModuleHelper::checkbox($label, $readonly = false, $class = false)
 *     ModuleHelper::select($label, $options = null, $readonly = false, $class = false)
 *     ModuleHelper::multiselect($label, $options = null, $readonly = false, $class = false)
 *     ModuleHelper::date($label, $min = null, $max = null, $readonly = false, $class = false)
 *     ModuleHelper::attachment($label, $readonly = false, $class = false)
 *     ModuleHelper::image($label, $readonly = false, $class = false)
 *
 * As we add support for more methods we will update this file
 */
return array(
    'itemFormFields' => array(
        'PhoenixRates\Form\RateForm' => array(
            'code'              => FormHelper::text('Code'),
            'name'              => FormHelper::text('Name'),
            //'hotelName'         => FormHelper::text('Hotel'),
            //'rateTypeCategory'  => FormHelper::text('Rate Type Category', 1),
            'isMember'          => FormHelper::checkbox('Member'),
            'membership'        => FormHelper::multiselect('Membership Level(s)'),
            'description'       => FormHelper::textarea('Description', 0, 'description'),
            'category'          => FormHelper::select('Categories'),
            //'policy'            => FormHelper::textarea('Policy'),
            'price'             => FormHelper::text('Price'),
            'bookingLink'       => FormHelper::text('Booking Link', 1),
            'startDate'         => FormHelper::text('Start Date'),
            'autoExpiry'        => FormHelper::text('Auto Expire Date'),
            //'isCorporate'       => FormHelper::text('Is Brand', 1),
            //'featured'          => FormHelper::checkbox('Feature on Property Home Page'),
            //'brandFeatured'     => FormHelper::checkbox('Feature on Brand Home Page'),
            //'specialOffers'     => FormHelper::checkbox('Feature on Special Offers Page'),
            //'corporateFeatured' =>  FormHelper::select('Brand Feature Order', array('1' => 'First','2' => 'Second','3' => 'Third','4' => 'Fourth')),
            'mediaAttachments'  => FormHelper::mediaAttachments('Attach Media'),
        ),
    ),
    'fieldsBindParameters' => array(
        'PhoenixRates\Form\RateForm' => array(
            'mediaAttachments' => array(
                'class' => 'phoenix-attachedmediafiles',
                'parameters' => array('module', 'itemModel', 'currentProperty')
            )
        )
    ),
    'inputFilters' => array(
        'PhoenixRates\Model\Rate' => array(
            'code' => array(
                'name'       => 'code',
                'required'   => true,
                'allowEmpty' => false,
            ),
            'name' => array(
                'name'       => 'name',
                'required'   => true,
                'allowEmpty' => false,
            ),
            'membership' => array(
                'name' => 'membership',
                'required' => false,
            ),
            'category' => array(
                'name' => 'category',
                'required' => true,
                'allowEmpty' => false,
                'validators' => array(
                    array(
                        'name' => 'Regex',
                        'options' => array(
                            'pattern'  => '/^[a-zA-Z1-9][a-zA-Z0-9]*$/i',
                            'messages' =>array(
                                \Zend\Validator\Regex::NOT_MATCH => 'Category Field is Empty, Please Select a Category',
                            )
                        ),
                    ),
                ),
            ),
            'price' => array(
                'name'       => 'price',
                'required'   => true,
                'allowEmpty' => false,
                'validators' => array(
                    array(
                        'name' => 'GreaterThan',
                        'options' => array(
                            'min'  => 0,
                            'messages' =>array(
                                \Zend\Validator\GreaterThan::NOT_GREATER => 'Price must be greater than zero',
                            )
                        ),
                    ),
                ),
            ),
            'startDate' => array(
                'name'       => 'startDate',
                'required'   => true,
                'allowEmpty' => false,
                'validators' => array(
                    array(
                        'name' => 'Regex',
                        'options' => array(
                            'pattern'  => '/^2[0-9]{3}-0[1-9]|1[012]-[012][1-9]|3[01]$/i',
                            'messages' =>array(
                                \Zend\Validator\Regex::NOT_MATCH => 'Please enter a date in YYYY-MM-DD format',
                            )
                        ),
                    ),
                ),
            ),
            'autoExpiry' => array(
                'name'       => 'autoExpiry',
                'required'   => true,
                'allowEmpty' => false,
                'validators' => array(
                    array(
                        'name' => 'Regex',
                        'options' => array(
                            'pattern'  => '/^2[0-9]{3}-0[1-9]|1[012]-[012][1-9]|3[01]$/i',
                            'messages' =>array(
                                \Zend\Validator\Regex::NOT_MATCH => 'Please enter a date in YYYY-MM-DD format',
                            )
                        ),
                    ),
                ),
            ),
            'bookingLink' => array(
                'name' => 'bookingLink',
                'required' => false,
                'allowEmpty' => false,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'Hostname',
                        'options' => array(
                            'allow'       => \Zend\Validator\Hostname::ALLOW_URI || \Zend\Validator\Hostname::ALLOW_DNS, // Allow these hostnames
                            'useIdnCheck' => true,  // Check IDN domains
                            'useTldCheck' => true,  // Check TLD elements
                            'ipValidator' => null,  // IP validator to use
                            'messages'=>array(
                                \Zend\Validator\Hostname::INVALID_HOSTNAME => 'Please Use a Valid URL',
                            )
                        ),
                    ),
                ),
            ),
        )
    ),
    'toggleEnabledFields' => array(
        'PhoenixRates\Form\RateForm' => array(
            'price',
            'category',
            'bookingLink',
            // 'startDate',
            // 'autoExpiry'
        ),
    ),
    'disabledFields' => array(
        'PhoenixRates\Form\RateForm' => array(
            //'price',
            //'category',
            'isMember',
            'membership',
            'bookingLink',
            // 'startDate',
            // 'autoExpiry'
        ),
    ),
);