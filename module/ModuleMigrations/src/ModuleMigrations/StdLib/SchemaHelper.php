<?php
/**
 * Collection of static schema helper methods
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
 * @filesource
 */

namespace ModuleMigrations\StdLib;

/**
 * Collection of static schema helper methods
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
class SchemaHelper
{
    public static function table()
    {
        $table = array();

        $args = func_get_args();
        foreach ($args as $scope)
        {
            $table[$scope['name']] = array();
            $table[$scope['name']]['fields'] = $scope['fields'];
            $table[$scope['name']]['indexes'] = $scope['indexes'];
            $table[$scope['name']]['engine'] = $scope['engine'];
            $table[$scope['name']]['autoIncrement'] = $scope['autoIncrement'];
        }

        return $table;
    }

    protected static function scope($name, $fields, $indexes, $engine, $autoIncrement = 1)
    {
        return array(
            'name'      => $name,
            'fields'    => $fields,
            'indexes'   => $indexes,
            'engine'    => $engine,
            'autoIncrement' => $autoIncrement
        );
    }

    public static function MyISAM() { return 'MyISAM'; }
    public static function InnoDB() { return 'InnoDB'; }

    public static function defaultScope($fields, $indexes = array(), $engine = 'MyISAM', $autoIncrement = 1)
    {
        return self::scope(
            'default',
            $fields,
            $indexes,
            $engine,
            $autoIncrement
        );
    }

    public static function adminScope($fields, $indexes = array(), $engine = 'MyISAM', $autoIncrement = 1)
    {
        return self::scope(
            'admin',
            $fields,
            $indexes,
            $engine,
            $autoIncrement
        );
    }

    public static function fields()
    {
        return func_get_args();
    }

    public static function indexes()
    {
        return func_get_args();
    }

    protected static function field($name, $type, $null = false, $default = null, $extra = null)
    {
        return array(
            'Field' => $name,
            'Type' => $type,
            'Null' => $null,
            'Default' => $default,
            'Extra' => $extra,
        );
    }

    public static function primary($name)
    {
        return self::field($name, "int(11)", 'NO', null, 'auto_increment');
    }

    public static function reference($name, $table, $key = 'id', $cascade = true, $null = 'NO')
    {
        $cascade = $cascade ? "ON DELETE CASCADE ON UPDATE CASCADE" : '';

        return self::field(
            $name,
            "int(11)",
            $null,
            null,
            ", FOREIGN KEY (`$name`) REFERENCES $table(`$key`) $cascade"
        );
    }

    public static function decimal($name, $length, $decimal, $null = 'YES', $default = null, $extra = null)
    {
        return self::field($name, "decimal({$length},{$decimal})", $null, $default, $extra);
    }

    public static function int($name, $null = 'YES', $default = null, $extra = '')
    {
        return self::field($name, "int(11)", $null, $default, $extra);
    }

    public static function tinyint($name, $null = 'YES', $default = null, $extra = '')
    {
        return self::field($name, "tinyint(1)", $null, $default, $extra);
    }

    public static function varchar($name, $length = 255, $null = 'YES', $default = null, $extra = '')
    {
        return self::field($name, "varchar({$length})", $null, $default, $extra);
    }

    public static function text($name, $null = 'YES', $default = null, $extra = '')
    {
        return self::field($name, "text", $null, $default, $extra);
    }

    public static function longtext($name, $null = 'YES', $default = null, $extra = '')
    {
        return self::field($name, "longtext", $null, $default, $extra);
    }

    public static function datetime($name, $null = 'YES', $default = null, $extra = '')
    {
        return self::field($name, "datetime", $null, $default, $extra);
    }

    public static function timestamp($name, $null = 'YES', $default = null, $extra = '')
    {
        return self::field($name, "timestamp", $null, $default, $extra);
    }

    public static function blob($name, $null = 'YES', $default = null, $extra = '')
    {
        return self::field($name, "blob", $null, $default, $extra);
    }

    public static function unique($name, $key = null)
    {
        return self::index($name, $key, true, 'UNIQUE');
    }

    public static function fulltext($name, $key = null)
    {
        return self::index($name, $key, false, 'FULLTEXT');
    }

    public static function index($name, $key = null, $unique = null, $type = 'BTREE')
    {
        return array(
            'Non_unique' => $unique ? '0' : '1',
            'Key_name' => $name,
            'Column_name' => $key ? $key : $name,
            'Index_type' => $type
        );
    }
}
