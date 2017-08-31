<?php
/**
 * PhoenixRates Table Structures
 *
 * PhoenixRates Table Structures for all tables it uses
 *
 * @category    Toolbox
 * @package     HeroImages
 * @subpackage  Tables
 * @copyright   Copyright (c) 2014 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.4.0
 * @since       File available since release 13.5.0
 * @author      Daniel Yang <dyang@travelclick.com>
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
    'heroImages' => SchemaHelper::table(
        SchemaHelper::defaultScope(
            SchemaHelper::fields(
                SchemaHelper::primary('itemId'),
                SchemaHelper::reference('item', 'componentItems', 'itemId'),                
                SchemaHelper::varchar('title'),
                SchemaHelper::int('order'),
                SchemaHelper::int('propertyId'),
                SchemaHelper::varchar('module'),
                SchemaHelper::int('pageId'),
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
     'heroImages_attachments' => SchemaHelper::table(
        SchemaHelper::defaultScope(
            SchemaHelper::fields(
                SchemaHelper::primary('itemId'),
                SchemaHelper::text('text1'),
                    SchemaHelper::text('text2'),
                    SchemaHelper::text('text3'),
                    SchemaHelper::int('attachmentId'),
                    SchemaHelper::varchar('caption'),
                    SchemaHelper::varchar('url'),
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
    )
);