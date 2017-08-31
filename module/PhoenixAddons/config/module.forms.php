<?php
/**
 * PhoenixAddons Module Fields
 *
 * PhoenixAddons Module Fields for all module forms
 *
 * @category    Toolbox
 * @package     PhoenixAddons
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
        'PhoenixAddons\Form\AddonForm' => array(
            'code'              => FormHelper::text('Code', 1),
            'name'              => FormHelper::text('Name'),
            'hotelName'         => FormHelper::text('Hotel', 1),
            'description'       => FormHelper::textarea('Description', 0, 'description'),
            'price'             => FormHelper::text('Price', 1),
            'startDate'         => FormHelper::date('Start Date'),
            'autoExpiry'        => FormHelper::date('Auto Expire Date'),
          //  'isCorporate'       => FormHelper::text('Is Corporate', 1),
            'featured'          => FormHelper::checkbox('Featured'),
            'mediaAttachments'  => FormHelper::mediaAttachments('Attach Media'),
        ),
    ),
    'fieldsBindParameters' => array(
        'PhoenixAddons\Form\AddonForm' => array(
            'mediaAttachments' => array(
                'class' => 'phoenix-attachedmediafiles',
                'parameters' => array('module', 'itemModel', 'currentProperty')
            )
        )
    ),
    'toggleEnabledFields' => array(
        'PhoenixAddons\Form\AddonForm' => array(
            'price',
            'bookingLink',
            'startDate',
            'autoExpiry',
        ),
    ),
    'disabledFields' => array(
        'PhoenixAddons\Form\AddonForm' => array(
            'price',
            'bookingLink',
            'startDate',
            'autoExpiry',
        ),
    ),
);