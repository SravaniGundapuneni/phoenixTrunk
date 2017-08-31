<?php
/**
 * PhoenixAttributes Module Fields
 *
 * PhoenixAttributes Module Fields for all module forms
 *
 * @category    Toolbox
 * @package     PhoenixAttributes
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
 *     ModuleHelper::polytext($label, $readonly = false, $class = false)
 *     ModuleHelper::image($label, $readonly = false, $class = false)
 *
 * As we add support for more methods we will update this file
 */
return array(
    'itemFormFields' => array(
        'PhoenixAttributes\Form\AttributeForm' => array(
            'code'          => FormHelper::text('Code', 1),
            'name'          => FormHelper::text('Name'),
            'hotelName'     => FormHelper::text('Hotel', 1),
            'description'   => FormHelper::textarea('Description', 0, 'description'),
            'isCorporate'   => FormHelper::text('Is Brand', 1),
            'featured'      => FormHelper::checkbox('Featured'),
            'mediaAttachments'  => FormHelper::mediaAttachments('Attach Media'),
        ),
    ),
    'fieldsBindParameters' => array(
        'PhoenixAttributes\Form\AttributeForm' => array(
            'mediaAttachments' => array(
                'class' => 'phoenix-attachedmediafiles',
                'parameters' => array('module', 'itemModel', 'currentProperty')
            )
        )
    ),
    'toggleEnabledFields' => array(
        'PhoenixAttributes\Form\AttributeForm' => array(
        ),
    ),
    'disabledFields' => array(
        'PhoenixAttributes\Form\AttributeForm' => array(
        ),
    ),
);