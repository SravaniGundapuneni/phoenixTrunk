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
        'PhoenixReview\Form\PhoenixReviewForm' => array(
        ),
    ),
    'itemFormFields' => array(
        'PhoenixReview\Form\PhoenixReviewForm' => array(
			'title'              => FormHelper::text('Title'),
			'content'            =>FormHelper::textarea('Content'),
            'name'              => FormHelper::text('Name'),
            'emailId'             => FormHelper::text('Email'),
            'emailEnabled'        => FormHelper::checkbox('Send Email'),
           
            
        ),
        'PhoenixReview\Form\FieldForm' => array(
            'name'              => FormHelper::text('Name'),
            'displayName'       => FormHelper::text('Label'),
            'type'              => FormHelper::select('Type'),
            'showInList'        => FormHelper::checkbox('Show in List'),
            'orderNumber'       => FormHelper::text('orderNumber')
        ),
        //'PhoenixReview\Form\ModuleItemForm' => array(          
        //), 
    ),
    /*'fieldsBindParameters' => array(
        'PhoenixReview\Form\ModuleItemForm' => array(
        )
    ), */
    'toggleEnabledFields' => array(
        'PhoenixReview\Form\PhoenixReviewForm' => array(
        ),
    ),
    'disabledFields' => array(
        'PhoenixReview\Form\PhoenixReviewForm' => array(
        ),
    ),
);