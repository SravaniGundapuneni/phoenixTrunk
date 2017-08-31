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
        'UserReviews\Form\UserReviewsForm' => array(
        ),
    ),
    'itemFormFields' => array(
        'UserReviews\Form\UserReviewsForm' => array(
			'title'              => FormHelper::text('Title'),
			'content'            =>FormHelper::textarea('Content', 0, 'content'),
            'name'              => FormHelper::text('Name'),
            'emailId'             => FormHelper::text('Email'),
            'emailEnabled'        => FormHelper::checkbox('Send Email'),
           
            
        ),
        'UserReviews\Form\FieldForm' => array(
            'name'              => FormHelper::text('Name'),
            'displayName'       => FormHelper::text('Label'),
            'type'              => FormHelper::select('Type'),
            'showInList'        => FormHelper::checkbox('Show in List'),
            'orderNumber'       => FormHelper::text('orderNumber')
        ),
        //'UserReviews\Form\ModuleItemForm' => array(          
        //), 
    ),
    /*'fieldsBindParameters' => array(
        'UserReviews\Form\ModuleItemForm' => array(
        )
    ), */
    'toggleEnabledFields' => array(
        'UserReviews\Form\UserReviewsForm' => array(
        ),
    ),
    'disabledFields' => array(
        'UserReviews\Form\UserReviewsForm' => array(
        ),
    ),
);