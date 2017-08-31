<?php
/**
 * PhoenixAttributes Table Structures
 *
 * PhoenixAttributes Table Structures for all tables it uses
 *
 * @category    Toolbox
 * @package     PhoenixAttributes
 * @subpackage  Tables
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.4.0
 * @since       File available since release 13.4.0
 * @author      Jose A Duarte <jduarte@travelclick.com>
 * @filesource
 */

use \ModuleMigrations\StdLib\SchemaHelper;

/**
 * Here is the list of supported methods
 *
 *   Fields
 * 
 *     SchemaHelper::primary($name)
 *     SchemaHelper::reference($name, $table, $key, $cascade = true)
 *     SchemaHelper::decimal($name, $length, $decimal, $null = 'YES', $default = null, $extra = null)
 *     SchemaHelper::int($name, $null = 'YES', $default = null, $extra = '')
 *     SchemaHelper::tinyint($name, $null = 'YES', $default = null, $extra = '')
 *     SchemaHelper::varchar($name, $length = 255, $null = 'YES', $default = null, $extra = '')
 *     SchemaHelper::text($name, $null = 'YES', $default = null, $extra = '')
 *     SchemaHelper::datetime($name, $null = 'YES', $default = null, $extra = '')
 *
 *   Indexes
 *
 *     SchemaHelper::index($name, $key = null, $unique = false)
 *
 * As we add support for more methods we will update this file
 *  
 */
return array(
    'phoenixAttributes' => SchemaHelper::table(
        SchemaHelper::defaultScope(
            SchemaHelper::fields(
                SchemaHelper::primary('attributeId'),
                SchemaHelper::varchar('code'),
                SchemaHelper::varchar('name'),
                SchemaHelper::varchar('property'),
                SchemaHelper::text('description'),
                SchemaHelper::tinyint('categories'),
                SchemaHelper::tinyint('showMainImage'),
                SchemaHelper::tinyint('featured'),

                // Last 4 coulumns (do not change)
                SchemaHelper::int('createdUserId'),                
                SchemaHelper::int('modifiedUserId'),
                SchemaHelper::datetime('created'),
                SchemaHelper::datetime('modified'),
                SchemaHelper::int('status')
            ),
            SchemaHelper::indexes(
                SchemaHelper::index('PRIMARY','attributeId',true)
            )
        )
    )
);