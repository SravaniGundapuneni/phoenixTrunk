<?php

/**
 * GoogleAnalytics Module Fields
 *
 * GoogleAnalytics Module Fields for all module forms
 *
 * @category    Toolbox
 * @package     GoogleAnalytics
 * @subpackage  Forms
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.5.5
 * @since       File available since release 13.5.5
 * @author      Sravani Gundapuneni <sgundapuneni@travelclick.com>
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
        'GoogleAnalytics\Form\GoogleAnalyticForm' => array(
            'title' => FormHelper::text('Title'),
              // 'gapServer' => FormHelper::checkbox('Use GAP Server'),
            'gaAccount' => FormHelper::text('GA Account Number'),
            'eventTracking' => FormHelper::checkbox('Enable Event Tracking'),
            'crossTracking' => FormHelper::checkbox('Enable Cross Tracking'),
            'domain' => FormHelper::text('Top Domain Name'),
            'crossDomain' => FormHelper::text('Cross Domain'),
            'pageNames' => FormHelper::select('Page Names'),
            'remarketing' => FormHelper::checkbox('DoubleClick Remarketing'),
            'anonynize' => FormHelper::checkbox('Anonymize'),
            'keyValue' => FormHelper::text('Key'),
        ),
    ),
);
