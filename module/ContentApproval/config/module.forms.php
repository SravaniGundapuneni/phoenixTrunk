<?php

/**
 * PhoenixRates Module Fields
 *
 * PhoenixRates Module Fields for all module forms
 *
 * @category    Toolbox
 * @package     HeroImages
 * @subpackage  Forms
 * @copyright   Copyright (c) 2014 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.4.0
 * @since       File available since release 13.5.0
 * @author      Daniel Yang <dyang@travelclick.com>
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
        'ContentApproval\Form\Form' => array(
            'approvalAction' => FormHelper::text('Action'),
        'data' => FormHelper::textarea('Data'),
        'originalData' => FormHelper::textarea('Original Data')
        ),
         'ContentApproval\Form\WorkflowForm' => array(
            'userGroup'       => FormHelper::select('User Group'),
        ),
       
    ),
    'fieldsBindParameters' => array(
        'ContentApproval\Form\Form' => array(
            'mediaAttachments' => array(
                'class' => 'phoenix-attachedmediafiles',
                'parameters' => array('module', 'itemModel', 'currentProperty')
            )
        )
    ),
    'toggleEnabledFields' => array(
        'ContentApproval\Form\Form' => array(
        ),
    ),
    'disabledFields' => array(
        'ContentApproval\Form\Form' => array(
        ),
    ),
);
