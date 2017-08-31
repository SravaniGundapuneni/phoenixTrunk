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
        'DynamicListModule\Form\Form' => array(
        ),
    ),
    'itemFormFields' => array(
        'DynamicListModule\Form\Form' => array(
            'name'              => FormHelper::text('Name'),
            'description'       => FormHelper::textarea('Description', 0, 'description'),
            'categories'        => FormHelper::checkbox('Use Categories')
        ),
        'DynamicListModule\Form\FieldForm' => array(
            'name'              => FormHelper::text('Name'),
            'label'             => FormHelper::text('Label'),
            'type'              => FormHelper::select('Type'),
            'required'          => FormHelper::checkbox('Required'),
            'translate'         => FormHelper::checkbox('Translate'),
            'showInList'        => FormHelper::checkbox('Show in List'),
            'orderNumber'       => FormHelper::text('orderNumber'),
            
        ),
        'DynamicListModule\Form\ModuleItemForm' => array(
            'propertyId' => FormHelper::propertySelect('propertyId'),
            'categoryId' => FormHelper::category('Category'),
            'mediaAttachments'  => FormHelper::mediaAttachments('Attach Media'),            
        ), 
    ),
    'fieldsBindParameters' => array(
        'DynamicListModule\Form\ModuleItemForm' => array(
            'mediaAttachments' => array(
                'class' => 'phoenix-attachedmediafiles',
                'parameters' => array('module', 'itemModel', 'currentProperty')
            )            
        )
    ),
    'toggleEnabledFields' => array(
        'DynamicListModule\Form\Form' => array(
        ),
    ),
    'disabledFields' => array(
        'DynamicListModule\Form\Form' => array(
        ),
    ),
);