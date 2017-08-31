<?php
/** 
 * SeoMetaText Module Fields
 *
 * SeoMetaText Module Fields for all module forms
 *
 * @category    Toolbox
 * @package     SeoMetaText
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
        'SeoMetaText\Form\SeoMetaTextForm' => array(
            'metaH1'                     => FormHelper::text('Meta H1 Tag'),
            'metaTitle'                => FormHelper::text('Meta Title'),
            'metaCanonical'                => FormHelper::text('Meta Canonical'),
            'metaDescription'                  => FormHelper::textarea('Meta Description'),
            'pageId'                       => FormHelper::select('Page'),  
            'countries'                       => FormHelper::select('Countries'), 
            ),
        ),  
 );