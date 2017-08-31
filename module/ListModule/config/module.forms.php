<?php
/**
 * ListModule Module Fields
 *
 * ListModule Module Fields for all module forms
 *
 * @category    Toolbox
 * @package     ListModule
 * @subpackage  Forms
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
 *
 */
return array(
    'itemFormStatues' => array(
        'ListModule\Form\Form' => array(
        ),
    ),
    'itemFormFields' => array(
        'ListModule\Form\Form' => array(
        ),
    ),
    'toggleEnabledFields' => array(
        'ListModule\Form\Form' => array(
        ),
    ),
    'disabledFields' => array(
        'ListModule\Form\Form' => array(
        ),
    )
);