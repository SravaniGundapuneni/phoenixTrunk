<?php

/**
 * Pages Module Fields
 *
 * Pages Module Fields for all module forms
 *
 * @category    Toolbox
 * @package     Pages
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
        'Pages\Form\PagesForm' => array(
            'pageType'=> FormHelper::select('Page Type',array('landingpage'=>'Landing','contentpage'=>'Content')), 
            'dataSection' => FormHelper::text('Data Section',1),
            'pageKey' => FormHelper::text('Page Url'),
            'startDate' => FormHelper::text('Start Date'),
            'autoExpire' => FormHelper::text('Auto Expire Date'),
            'eventName' => FormHelper::text('Event Name'),
            'additionalParams' => FormHelper::text('Additional Parameters'),
			'hotel' => FormHelper::select('Hotels', array()),
            'template' => FormHelper::select('Template', array(
                 )),
        ),
    ),
    'fieldsBindParameters' => array(
        'Pages\Form\PagesForm' => array(
            'mediaAttachments' => array(
                'class' => 'phoenix-attachedmediafiles',
                'parameters' => array('module', 'itemModel', 'currentProperty')
            )
        )
    ),
    'inputFilters' => array(
        'Pages\Model\Page' => array(
            'pageKey' => array(
                'name' => 'pageKey',
                'required' => true,
                'allowEmpty' => false,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name' => 'Regex',
                        'options' => array(
                            'pattern'  => '/^\w|-*$/i',
                            'messages' =>array(
                                \Zend\Validator\Regex::NOT_MATCH => 'Page URL cannot contain special characters',
                            )
                        ),
                    ),
                    array(
                        'name' => 'NotEmpty',
                        'options' => array(
                            'messages' =>array(
                                \Zend\Validator\NotEmpty::IS_EMPTY => 'Page URL is required and cannot be left blank',
                            )
                        ),
                    ),
                ),
            ),
            'startDate' => array(
                'name' => 'startDate',
                'required' => false,
                'allowEmpty' => true,
                'validators' => array(
                    array(
                        'name' => 'Regex',
                        'options' => array(
                            'pattern'  => '/^2[0-9]{3}-0[1-9]|1[012]-[012][1-9]|3[01]$/i',
                            'messages' =>array(
                                \Zend\Validator\Regex::NOT_MATCH => 'Please enter a date in YYYY-MM-DD format',
                            )
                        ),
                    ),
                ),
            ),
            'autoExpire' => array(
                'name' => 'autoExpire',
                'required' => false,
                'allowEmpty' => true,
                'validators' => array(
                    array(
                        'name' => 'Regex',
                        'options' => array(
                            'pattern'  => '/^2[0-9]{3}-0[1-9]|1[012]-[012][1-9]|3[01]$/i',
                            'messages' =>array(
                                \Zend\Validator\Regex::NOT_MATCH => 'Please enter a date in YYYY-MM-DD format',
                            )
                        ),
                    ),
                ),
            ),
        )
    ),
    'toggleEnabledFields' => array(
        'Pages\Form\PagesForm' => array(
            //'dataSection'
        ),
    ),
    'disabledFields' => array(
        'Pages\Form\PagesForm' => array(
        'dataSection'
        ),
    ),
);
