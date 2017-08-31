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
 *     ModuleHelper::image($label, $readonly = false, $class = false)
 *
 * As we add support for more methods we will update this file
 */
return array(
    'itemFormFields' => array(
        'HeroImages\Form\Form' => array(
            'title' => FormHelper::text('Title'),
            'pageId' => FormHelper::select('Page name'),
            'propertyId'       => FormHelper::select('Hotel'),
            'mediaAttachments' => FormHelper::mediaAttachments('Attach Media'),
        ),
        'HeroImages\Form\AttachmentForm' => array(
            'text1' => FormHelper::textarea('Text 1'),
            'text2' => FormHelper::textarea('Text 2'),
            'text3' => FormHelper::textarea('Text 3'),
            'caption' => FormHelper::text('Image Caption'),
            'url' => FormHelper::text('Url'),
        ),
    ),
    'fieldsBindParameters' => array(
        'HeroImages\Form\Form' => array(
            'mediaAttachments' => array(
                'class' => 'phoenix-attachedmediafiles',
                'parameters' => array('module', 'itemModel', 'currentProperty')
            )
        )
    ),
    'toggleEnabledFields' => array(
        'HeroImages\Form\Form' => array(
        ),
    ),
    'disabledFields' => array(
        'HeroImages\Form\Form' => array(
        ),
    ),
);
