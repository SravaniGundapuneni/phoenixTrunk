<?php
/**
 * PhoenixProperties Module Fields
 *
 * PhoenixProperties Module Fields for all module fields
 *
 * @category    Toolbox
 * @package     PhoenixProperties
 * @subpackage  Tables
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.4.0
 * @since       File available since release 13.4.0
 * @author      Jose A Duarte <jduarte@travelclick.com>
 * @filesource
 */

use \ListModule\Model\ListItem;
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
    'itemFormOptions' => array(
        'PhoenixProperties\Form\PropertyForm' => array(
        ),
    ),
    'itemFormFields' => array(
        'PhoenixProperties\Form\PropertyForm' => array(
            'code'             => FormHelper::text('Code', 1),
            'name'             => FormHelper::text('Name'),
            'description'      => FormHelper::textarea('Description', 0, 'description'),
            'address'          => FormHelper::text('Address'),
            'suiteApt'         => FormHelper::text('Suite/Apt'),
            'city'             => FormHelper::text('City'),
            'state'            => FormHelper::text('State'),
            'zip'              => FormHelper::text('Zip'),
            'country'          => FormHelper::select('Country', null),
            'url'              => FormHelper::text('Url'),
            'tollfreeNumber'   => FormHelper::text('Tollfree Number'),
            'phoneNumber'      => FormHelper::text('Phone Number'),
            'faxNumber'        => FormHelper::text('Fax Number'),
            'email'            => FormHelper::text('Email'),
            'latitude'         => FormHelper::text('Latitude'),
            'longitude'        => FormHelper::text('Longitude'),
            'labelX'           => FormHelper::text('Label Horizontal Offset'),
            'labelY'           => FormHelper::text('Label Vertical Offset'),
            'tempFormat'       => FormHelper::select('Temperature Format', array('F'=>'Fahrenheit','C'=>'Celsius')),
            'history'          => FormHelper::textarea('History', 0, 'history'),
            'policy'           => FormHelper::textarea('Policy', 0, 'policy'),
            'groupCode'        => FormHelper::text('Group Code'),
            'facebook'         => FormHelper::text('Facebook'),
            'twitter'          => FormHelper::text('Twitter'),
            'instagram'        => FormHelper::text('Instagram'),
            'sitePath'         => FormHelper::text('Site Path'),
           // 'isCorporate'       => FormHelper::checkbox('Is Brand'),
            'mediaAttachments' => FormHelper::mediaAttachments('Attach Media'),
        ),
    ),
    'fieldsBindParameters' => array(
        'PhoenixProperties\Form\PropertyForm' => array(
            'mediaAttachments' => array(
                'class' => 'phoenix-attachedmediafiles',
                'parameters' => array('module', 'itemModel', 'currentProperty')
            )
        )
    ),
    'toggleEnabledFields' => array(
        'PhoenixProperties\Form\PropertyForm' => array(
            'groupCode',
            'url'
        ),
    ),
    'disabledFields' => array(
        'PhoenixProperties\Form\PropertyForm' => array(
            'groupCode',
            'url'
        ),
    ),
);

