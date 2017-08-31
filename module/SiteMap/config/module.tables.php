<?php
/**
 * PhoenixRates Table Structures
 *
 * PhoenixRates Table Structures for all tables it uses
 *
 * @category    Toolbox
 * @package     SiteMap
 * @subpackage  Tables
 * @copyright   Copyright (c) 2014 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.4.0
 * @since       File available since release 13.5.0
 * @author      Alex Kotsores <akotsores@travelclick.com>
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
    'siteMap' => SchemaHelper::table(
        SchemaHelper::defaultScope(
            SchemaHelper::fields(
                SchemaHelper::primary('itemId'),
                SchemaHelper::varchar('title'),
                SchemaHelper::varchar('dataSection'),
                SchemaHelper::int('order'),
                SchemaHelper::varchar('page'),
                SchemaHelper::varchar('pageKey'),
                SchemaHelper::varchar('areaKey'),
                SchemaHelper::int('propertyId'),
                SchemaHelper::varchar('module'),
                SchemaHelper::varchar('friendlyType'),
                SchemaHelper::int('dynamicPage'),
                SchemaHelper::int('moduleItemId'),
                SchemaHelper::varchar('moduleItemTitle'),
                    
                // Last 4 coulumns (do not change)
                SchemaHelper::int('createdUserId'),                
                SchemaHelper::int('modifiedUserId'),
                SchemaHelper::datetime('created'),
                SchemaHelper::datetime('modified'),
                SchemaHelper::int('status'),
                SchemaHelper::int('orderNumber')
            ),
            SchemaHelper::indexes(
                SchemaHelper::index('PRIMARY','itemId',true)
            ),
            SchemaHelper::InnoDB()
        )
    )
);