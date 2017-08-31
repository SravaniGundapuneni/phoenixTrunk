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
        'SiteMaps\Form\Form' => array(
        ),
    ),
    'itemFormFields' => array(
        'SiteMaps\Form\Form' => array(
			//'dataSectionId'        =>FormHelper::select('DataSection'),
			'pageKey'              =>FormHelper::text('PageKey',array('readOnly'=>true)),
            'visible'              =>FormHelper::checkbox('Show'),
            
           
            
        ),
      
        //'PhoenixReview\Form\ModuleItemForm' => array(          
        //), 
    ),
    /*'fieldsBindParameters' => array(
        'PhoenixReview\Form\ModuleItemForm' => array(
        )
    ), */
    'toggleEnabledFields' => array(
        'SiteMaps\Form\Form' => array(
        ),
    ),
    'disabledFields' => array(
        'SiteMaps\Form\Form' => array(
        ),
    ),
);