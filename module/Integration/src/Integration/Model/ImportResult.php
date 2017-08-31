<?php

/**
 * The file for the ImportResult Model
 *
 * @category    Toolbox
 * @package     ImportResult
 * @subpackage  Model
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.4
 * @since       File available since release 13.4
 * @author      A. Tate <atate@travelclick.com>
 * @filesource
 */

namespace Integration\Model;

/**
 * The file for the ImportResult Model
 *
 * @category    Toolbox
 * @package     ImportResult
 * @subpackage  Model
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.4
 * @since       File available since release 13.4
 * @author      A. Tate <atate@travelclick.com>
 */

class ImportResult
{
    private $attributes = array(
        'code'        => null,
        'language'    => null,
        'name'        => null,
        'status'      => null,
        'status_desc' => null,
        'data_object' => null,
        'children'    => array(),
        'duration'    => 0
    );

    private $callbackAttributes = array(
        'status_text'  => 'getStatusText',
        'status_class' => 'getStatusCSSClass'
    );

    private static $cache = array();

    const STATUS_CREATED = 1;
    const STATUS_SKIPPED = 2;
    const STATUS_UPDATED = 3;
    const STATUS_OVERWRITE = 4;

    /**
     *  __construct function
     * 
     * @access public
     * @param  array $attributes
     *
    */

    public function __construct( array $attributes = null )
    {
        if( is_array( $attributes ) )
        {
            $this->attributes = array_merge( $this->attributes, $attributes );
        }
    }

    /**
     *  __isset function
     * 
     * @access public
     * @param  array $name
     * @return array $this->attributes[ $name ]
     *
    */
 
    public function __isset( $name )
    {
        return isset( $this->attributes[ $name ] );
    }

     /**
     *  __get function
     * 
     * @access public
     * @param  array $name
     * @return array $value
     *
    */

    public function __get( $name )
    {
        $value = null;

        if( isset( $this->callbackAttributes[ $name ] ) )
        {
            $value = call_user_func( array( $this, $this->callbackAttributes[ $name ] ) );
        }
        elseif( isset( $this->data_object->$name ) )
        {
            $value = $this->data_object->$name;
        }
        elseif( isset( $this->attributes[ $name ] ) )
        {
            $value = $this->attributes[ $name ];
        }

        return $value;
    }

    /**
     *  __set function
     * 
     * @access public
     * @param  array $name
     * @param  array $value
     *
    */

    public function __set( $name, $value )
    {
        $this->attributes[ $name ] = $value;
    }

    /**
     *  get function
     * 
     * @access public
     * @param  array $name
     * @param  array $default
     * @return array $default
     *
    */

    public function get($name, $default = null)
    {
        return isset( $this->data_object->$name ) ?  $this->data_object->$name : $default;
    }

    /**
     *  getStatusText function
     * 
     * @access public
     * @return array $status
     *
    */

    public function getStatusText()
    {
        $status = null;

        switch( $this->status )
        {
            case self::STATUS_CREATED: $status = 'Created'; break;
            case self::STATUS_SKIPPED: $status = 'Skipped'; break;
            case self::STATUS_UPDATED: $status = 'Pending'; break;
            case self::STATUS_OVERWRITE: $status = 'Overwrite'; break;
        }

        return $status;
    }

    /**
     *  getStatusCSSSlass function
     * 
     * @access public
     * @return array $class
     *
    */

    public function getStatusCSSClass()
    {
        $class = null;

        switch( $this->status )
        {
            case self::STATUS_CREATED: $class = 'success'; break;
            case self::STATUS_SKIPPED: $class = 'warning';   break;
            case self::STATUS_UPDATED: $class = 'info';    break;
            case self::STATUS_OVERWRITE: $class = 'info';    break;
        }

        return $class;
    }

    /**
     *  setStatus function
     * 
     * @access public
     * @param  array $status
     * @param  array $description
     *
    */

    public function setStatus( $status, $description = null )
    {
        $this->status = $status;

        if( $description !== null ) {
            $this->status_desc = $description;
        }

        if( isset( $this->start_time ) ) {
            $this->duration = time() - $this->start_time;
        }
    }
}