<?php
/**
 * PhoenixRates Module Fields
 *
 * PhoenixRates Module Fields for all module forms
 *
 * @category    Toolbox
 * @package     SiteMap
 * @subpackage  Forms
 * @copyright   Copyright (c) 2014 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.4.0
 * @since       File available since release 13.5.0
 * @author      Alex Kotsores <akotsores@travelclick.com>
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
        'SiteMap\Form\Form' => array(
            'title'             => FormHelper::text('Title'),
            'page'              => FormHelper::text('Page'),
            'pageKey'           => FormHelper::text('Page Key'),
            'areaKey'           => FormHelper::text('Area Key'),
            'propertyId'       => FormHelper::select('Hotel'),
        ),
    ),
    'fieldsBindParameters' => array(
        'SiteMap\Form\Form' => array(
            'mediaAttachments' => array(
                'class' => 'phoenix-attachedmediafiles',
                'parameters' => array('module', 'itemModel', 'currentProperty')
            )
        )
    ),
    'toggleEnabledFields' => array(
        'SiteMap\Form\Form' => array(
            
        ),
    ),
    'disabledFields' => array(
        'SiteMap\Form\Form' => array(
            
        ),
    ),
);