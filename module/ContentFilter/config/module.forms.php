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
 *     As we add support for more methods we will update this file
 */
return array(
    'itemFormOptions' => array(
        'ContentFilter\Form\Form' => array(
        ),
    ),
    'itemFormFields' => array(
        /*'ContentFilter\Form\Form' => array(
		    'hotel'        =>FormHelper::select('Hotels'),			
			'roomName'     =>FormHelper::select('Name',array('Standard Room'=>'Standard Room','Superior Room'=>'Superior Room','Poolside Room'=>'Poolside Room')),
			'bedType'      =>FormHelper::select('No of Beds',array('1'=>'1','2'=>'2','3'=>'3','4'=>'4','5'=>'5')),
			'maxOccupancy' =>FormHelper::select('Max Occupancy',array('1'=>'1','2'=>'2','3'=>'3','4'=>'4','5'=>'5')),
			'roomCode'     =>FormHelper::select('Codes',array('STD2Q'=>'STD2Q','PLS2Q'=>'PLS2Q','CFS2Q'=>'CFS2Q','TFS2Q'=>'TFS2Q','LUXD2'=>'LUXD2')),			         
        ), */      
    ),
  
    'toggleEnabledFields' => array(
        'ContentFilter\Form\Form' => array(
        ),
    ),
    'disabledFields' => array(
        'ContentFilter\Form\Form' => array(
        ),
    ),
);