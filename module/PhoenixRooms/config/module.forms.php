<?php
/**
 * PhoenixRooms Module Fields
 *
 * PhoenixRooms Module Fields for all module forms
 *
 * @category    Toolbox
 * @package     PhoenixRooms
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
        'PhoenixRooms\Form\RoomForm' => array(
            //'code'             => FormHelper::text('Code'),
            'name'             => FormHelper::text('Name'),
            //'hotelName'        => FormHelper::text('Hotel', 1),
            'description'      => FormHelper::textarea('Description', 0, 'description'),
            'features'         => FormHelper::textarea('Features', 0, 'features'),
            //'bedType'          => FormHelper::text('Bed Type'),
            //'maxOccupancy'     => FormHelper::text('Maximum Occupancy'),
            //'virtualTour'      => FormHelper::text('Virtual Tour'),
            //'isCorporate'      => FormHelper::checkbox('Brand Only'),
            //'featured'         => FormHelper::checkbox('Featured'),
            'price'            => FormHelper::text('Price'),
            'bookingLink'      => FormHelper::text('Booking Link'),
            'category'         => FormHelper::select('Categories'),
            'mediaAttachments' => FormHelper::mediaAttachments('Attach Media'),
            //'policy'           => FormHelper::textarea('Policy'),
        ),
    ),
    'fieldsBindParameters' => array(
        'PhoenixRooms\Form\RoomForm' => array(
            'mediaAttachments' => array(
                'class' => 'phoenix-attachedmediafiles',
                'parameters' => array('module', 'itemModel', 'currentProperty')
            )
        )
    ),
  'toggleEnabledFields' => array(
        'PhoenixRooms\Form\RoomForm' => array(
            'bedType',
        ), 
    ),
    'disabledFields' => array(
        'PhoenixRooms\Form\RoomForm' => array(
            
        ),
    ),
    'inputFilters' => array(
        'PhoenixRooms\Model\Room' => array(
            
			/*'code' => array(
                'name' => 'code',
                'required' => true,
                'allowEmpty' => false,
            ), */
            'name' => array(
                'name' => 'name',
                'required' => true,
                'allowEmpty' => false,
            ),
         /* 'maxOccupancy' => array(
                'name' => 'maxOccupancy',
                'required' => true,
                'allowEmpty' => false,
            ), */
            'price' => array(
                'name' => 'price',
                'required' => true,
                'allowEmpty' => false,
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
        ),
    ),
);