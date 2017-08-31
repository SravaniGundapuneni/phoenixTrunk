<?php
/** 
 * PhoenixEvents Module Fields
 *
 * PhoenixEvents Module Fields for all module forms
 *
 * @category    Toolbox
 * @package     PhoenixRates
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
        'PhoenixEvents\Form\EventsForm' => array(
            'eventName'          => FormHelper::text('Event Name'),
            'eventStart'         => FormHelper::text('Event Start Date'),
            'eventStartTime'     => FormHelper::text('Event Start Time'),
            'eventEnd'           => FormHelper::text('Event End Date'),
            'eventEndTime'       => FormHelper::text('Event End Time'),
            'propertyId'       => FormHelper::select('Hotel'),
            'eventDescription'   => FormHelper::textarea('Event Description', 0, 'description'),
            'status'             => FormHelper::checkbox('Event Status'),
            'mediaAttachments'  => FormHelper::mediaAttachments('Attach Media'),
        ),
    ),
    'fieldsBindParameters' => array(
        'PhoenixEvents\Form\EventsForm' => array(
            'mediaAttachments' => array(
                'class' => 'phoenix-attachedmediafiles',
                'parameters' => array('module', 'itemModel', 'currentProperty')
            )
        )
    ),
    'toggleEnabledFields' => array(
        'PhoenixEvents\Form\EventsForm' => array(
            'status'
        ),
    ),
    'disabledFields' => array(
        'PhoenixEvents\Form\EventsForm' => array(
            'status'
        ),
    ),    
); 