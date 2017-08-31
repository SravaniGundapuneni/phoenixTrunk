<?php
/** 
 * LinkRedirects Module Fields
 *
 * LinkRedirects Module Fields for all module forms
 *
 * @category    Toolbox
 * @package     LinkRedirects
 * @subpackage  Forms
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.5.5
 * @since       File available since release 13.5.5
 * @author      Kevin Davis <kedavis@travelclick.com>
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
       'LinkRedirects\Form\LinkRedirectsForm' => array(
           'incomingUrl' => FormHelper::text('Incoming Url'),
           'redirectUrl' => FormHelper::text('Redirecting Url'),
           'response' => FormHelper::select('Header Response Type',array(
           '301' => '301 Moved Permanently',
                    '302' => '302 Found',
                    '303' => '303 See Other',
                    '307' => '307 Moved Temporarily'
            
            )),
            
            ),
 ),
     );