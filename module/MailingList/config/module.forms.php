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
        'MailingList\Form\Form' => array(
        ),
    ),
    'itemFormFields' => array(
        'MailingList\Form\Form' => array(
			'title'        =>FormHelper::select('Title',array('Mr'=>'Mr','Miss'=>'Miss','Mrs'=>'Mrs')),
			'firstName'              =>FormHelper::text('FirstName'),
			'lastName'              =>FormHelper::text('LastName'),
			'email'              =>FormHelper::text('Email'),
			'countryCode'        =>FormHelper::select('CountryCode',array('CA'=>'Canada','US'=>'United States of America','MX'=>'Mexico')),
            'subscribe'              =>FormHelper::checkbox('Subscribe'),
         
        ),
      
        //'PhoenixReview\Form\ModuleItemForm' => array(          
        //), 
    ),
    /*'fieldsBindParameters' => array(
        'PhoenixReview\Form\ModuleItemForm' => array(
        )
    ), */
    'toggleEnabledFields' => array(
        'MailingList\Form\Form' => array(
        ),
    ),
    'disabledFields' => array(
        'MailingList\Form\Form' => array(
        ),
    ),
);