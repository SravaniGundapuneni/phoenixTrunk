<?php
/**
 * UserReviews Table Structures
 *
 * Users Table Structures for all tables it uses
 *
 * @category    Toolbox
 * @package     UserReviews
 * @subpackage  Tables
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.4.0
 * @since       File available since release 13.4.0
 * @author      <hnaik@travelclick.com>
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
	'eventcategory' => SchemaHelper::table(
        SchemaHelper::defaultScope(
            SchemaHelper::fields(
                SchemaHelper::primary('itemId'),
				SchemaHelper::varchar('title'),
				SchemaHelper::int('userId'),
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
    ),
	'calendarevent' => SchemaHelper::table(
        SchemaHelper::defaultScope(
            SchemaHelper::fields(
                SchemaHelper::primary('itemId'),
				SchemaHelper::varchar('title'),
                SchemaHelper::reference('item', 'componentItems', 'itemId'),                      
				SchemaHelper::reference('eventCategoryId', 'eventcategory', 'itemId'),
				SchemaHelper::varchar('highlights'),
				SchemaHelper::varchar('url'),
				SchemaHelper::varchar('dataSection'),
				SchemaHelper::int('userId'),
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
    ),
   'calendar' => SchemaHelper::table(
        SchemaHelper::defaultScope(
            SchemaHelper::fields(
                SchemaHelper::primary('itemId'),
				SchemaHelper::int('calendarEventId'),
				SchemaHelper::datetime('startDate'),
				SchemaHelper::datetime('endDate'),
               	SchemaHelper::int('userId'),
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
    ),
    
       
);