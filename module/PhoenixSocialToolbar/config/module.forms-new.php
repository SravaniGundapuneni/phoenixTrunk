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
        'PhoenixSocialToolbar\Form\Form' => array(
           
        ),
    ),
    'itemFormFields' => array(
        'PhoenixSocialToolbar\Form\Form' => array(
            'toolbarLayout'          => FormHelper::select('Toolbar Layout'),
            'toolbarColorStyles'         => FormHelper::select('Toolbar Color Styles'),
            'customColorGradient1'     => FormHelper::text('Gradient Color Picker 1'),
            'customColorGradient2'           => FormHelper::date('Gradient Color Picker 2'),
            'toolbarTextColor'       => FormHelper::text('Text Color'),
            'toolbarTextStyle'       => FormHelper::select('Text Style'),
            'toolbarSharingTooltip'       => FormHelper::checkbox('Enable Tooltip for Sharing Widget'),
            'toolbarIconSets'        => FormHelper::select('Enable Tooltip for Sharing Widget'),
            'toolbarShowLabels'             => FormHelper::checkbox('Show'),
            'status'             => FormHelper::checkbox('Toolbar Enabled'),
           
        ),
        'PhoenixSocialToolbar\Form\SocialSitesForm' => array(
            'siteName'          => FormHelper::select('Toolbar Layout'),
            'siteUserAccount'         => FormHelper::select('Toolbar Color Styles'),
            'siteUserPassword'     => FormHelper::text('Gradient Color Picker 1'),
            'customColorGradient2'           => FormHelper::date('Gradient Color Picker 2'),
            'toolbarTextColor'       => FormHelper::text('Text Color'),
            'toolbarTextStyle'       => FormHelper::select('Text Style'),
            'toolbarSharingTooltip'       => FormHelper::checkbox('Enable Tooltip for Sharing Widget'),
            'toolbarIconSets'        => FormHelper::select('Enable Tooltip for Sharing Widget'),
            'status'             => FormHelper::checkbox('Toolbar Enabled'),
           
        ),
          'PhoenixSocialToolbar\Form\SocialSitesForm' => array(
            'siteName'          => FormHelper::select('Toolbar Layout'),
            'siteUserAccount'         => FormHelper::select('Toolbar Color Styles'),
            'siteUserPassword'     => FormHelper::text('Gradient Color Picker 1'),
            'customColorGradient2'           => FormHelper::date('Gradient Color Picker 2'),
            'toolbarTextColor'       => FormHelper::text('Text Color'),
            'toolbarTextStyle'       => FormHelper::select('Text Style'),
            'toolbarSharingTooltip'       => FormHelper::checkbox('Enable Tooltip for Sharing Widget'),
            'toolbarIconSets'        => FormHelper::select('Enable Tooltip for Sharing Widget'),
            'status'             => FormHelper::checkbox('Toolbar Enabled'),
           
        ),
        'PhoenixSocialToolbar\Form\ModuleItemForm' => array(          
        ), 
    ),
    'fieldsBindParameters' => array(
        'PhoenixSocialToolbar\Form\ModuleItemForm' => array(
        )
    ),
    'toggleEnabledFields' => array(
        'PhoenixSocialToolbar\Form\Form' => array(
        ),
    ),
    'disabledFields' => array(
        'PhoenixSocialToolbar\Form\Form' => array(
        ),
    ),
);