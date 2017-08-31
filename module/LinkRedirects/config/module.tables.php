<?php
/**
 * Image Switch Table Structures
 *
 * Image Switch Table Structures for all tables it uses
 *
 * @category    Toolbox
 * @package     ImageSwitch
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
	 'linkRedirects' => SchemaHelper::table(
        SchemaHelper::defaultScope(
            SchemaHelper::fields(
                SchemaHelper::primary('itemId'),
               
             
                SchemaHelper::varchar('incomingUrl'),
                     SchemaHelper::varchar('redirectUrl'),
               
                SchemaHelper::int('response'),
                    
                // Last 4 coulumns (do not change)
                SchemaHelper::int('createdUserId'),                
                SchemaHelper::int('modifiedUserId'),
                SchemaHelper::datetime('created'),
                SchemaHelper::datetime('modified'),
                SchemaHelper::int('status')
            ),
            SchemaHelper::indexes(
                SchemaHelper::index('PRIMARY','itemId',true)
            ),
            SchemaHelper::InnoDB()
        )
    ),
    'linkRedirectsError' => SchemaHelper::table(
        SchemaHelper::defaultScope(
            SchemaHelper::fields(
                SchemaHelper::primary('itemId'),
               
               
                SchemaHelper::int('hits'),
                    SchemaHelper::varchar('url'),
                // Last 4 coulumns (do not change)
                SchemaHelper::int('createdUserId'),                
                SchemaHelper::int('modifiedUserId'),
                SchemaHelper::datetime('created'),
                SchemaHelper::datetime('modified'),
                SchemaHelper::int('status'),
                       SchemaHelper::int('order')
            ),
            SchemaHelper::indexes(
                SchemaHelper::index('PRIMARY','itemId',true)
            ),
            SchemaHelper::InnoDB()
        )
    )
);