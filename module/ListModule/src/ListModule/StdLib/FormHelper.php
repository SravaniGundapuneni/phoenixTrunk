<?php
/**
 * Collection of static module helper methods
 *
 * This is a good place to put methods that we use in Toolbox for dealing with modules.
 *
 * @category    ModuleMigrations
 * @package     StdLib
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.ezyield.com)
 * @license     All Rights Reserved
 * @version     Release: 13.4
 * @since       File available since release 13.4
 * @author      Jose A. Duarte <jduarte@travelclick.com>
 * @filesource
 */

namespace ListModule\StdLib;

/**
 * Collection of static module helper methods
 *
 * This is a good place to put methods that we use in Toolbox for dealing with schemas.
 *
 * @category    ModuleMigrations
 * @package     StdLib
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.ezyield.com)
 * @license     All Rights Reserved
 * @version     Release: 13.4
 * @since       File available since release 13.4
 * @author      Jose A. Duarte <jduarte@travelclick.com>
 */
class FormHelper
{
    protected static function field($type, $label, $readonly, $class, $options = array())
    {
        /**
         * lets get the template and merge the extra options
         */
        return array_merge($options, array(
            'type' => $type,
            'label' => $label,
            'readonly' => $readonly,
            'class' => $class,
        ));
    }

    public static function text($label, $readonly = false, $class = false)
    {
        return self::field('text', $label, $readonly, $class);
    }

    public static function textarea($label, $readonly = false, $class = false)
    {
        return self::field('textarea', $label, $readonly, $class);
    }

    public static function checkbox($label, $readonly = false, $class = false)
    {
        return self::field('checkbox', $label, $readonly, $class);
    }

    public static function select($label, $options = null, $readonly = false, $class = false)
    {
        return self::field('select', $label, $readonly, $class, array('options'=>$options));
    }

    public static function multiselect($label, $options = null, $readonly = false, $class = false)
    {
        return self::field('multiselect', $label, $readonly, $class, array('multiple' => true, 'options' => $options));
    }

    public static function mediaAttachments($label, $readonly = true, $class = false)
    {
        return self::field('mediaAttachments', $label, $readonly, $class);
    }

    /**
     * propertySelect
     *
     * @todo  Decouple this non-core module functionality from the core functionality.
     * @param  string  $label
     * @param  boolean $readonly
     * @param  boolean $class
     * @return array
     */
    public static function propertySelect($label, $readonly = true, $class = false)
    {
        return self::field('propertySelect', $label, $readonly, $class);
    }

    public static function category($label, $readonly = true, $class = false)
    {
        return self::field('category', $label, $readonly, $class);
    }

    public static function date($label, $min = null, $max = null, $readonly = false, $class = false)
    {
        return self::field('date', $label, $readonly, $class, array('min' => $min,'max' => $max));
    }

    /**
     * @deprecated
     */
    public static function polytext($label, $readonly = false, $class = false)
    {
        return self::field('polytext', $label, $readonly, $class);
    }

    public static function password($label, $readonly = false, $class = false)
    {
        return self::field('password', $label, $readonly, $class);
    }

    public static function image($label, $readonly = false, $class = false)
    {
        return self::field('image', $label, $readonly, $class);
    }
}