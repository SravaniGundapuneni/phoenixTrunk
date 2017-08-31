<?php
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
        'FlexibleForms\Form\Form' => array(
        ),
    ),
    'itemFormFields' => array(
        'FlexibleForms\Form\Form' => array(
            'name'              => FormHelper::text('Name'),
            'label'             => FormHelper::text('Label'),
            'emailEnabled'        => FormHelper::checkbox('Send Email'),
            'description'       => FormHelper::textarea('Description', 0, 'description')
            
        ),
        'FlexibleForms\Form\FieldForm' => array(
            'name'              => FormHelper::text('Name'),
            'displayName'       => FormHelper::text('Label'),
            'type'              => FormHelper::select('Type'),
            'showInList'        => FormHelper::checkbox('Show in List'),
            'orderNumber'       => FormHelper::text('orderNumber'),
            
        ),
        'FlexibleForms\Form\EmailForm' => array(
            'departmentEmail'          => FormHelper::text('Email Id'),
            'property'          => FormHelper::select('Hotel Name'),
            'departmentId'      => FormHelper::select('Department Id'),
            'status'            => FormHelper::checkbox('Status')
        ),
        'FlexibleForms\Form\ModuleItemForm' => array(          
        ), 
    ),
    'fieldsBindParameters' => array(
        'FlexibleForms\Form\ModuleItemForm' => array(
        )
    ),
    'toggleEnabledFields' => array(
        'FlexibleForms\Form\Form' => array(
        ),
    ),
    'disabledFields' => array(
        'FlexibleForms\Form\Form' => array(
        ),
    ),
);