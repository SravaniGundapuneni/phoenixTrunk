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
	'phpSample' => SchemaHelper::table(
		SchemaHelper::defaultScope(
			SchemaHelper::fields(
				SchemaHelper::primary('markerId'),
				SchemaHelper::reference('item', 'componentItems', 'itemId'),
				SchemaHelper::varchar('title', 255),
				SchemaHelper::varchar('latitude', 255),
				SchemaHelper::varchar('longitude', 255),
				SchemaHelper::text('description'),
				SchemaHelper::varchar('queryString', 255),
                SchemaHelper::int('createdUserId'),                
                SchemaHelper::int('modifiedUserId'),
				SchemaHelper::datetime('modified'),
				SchemaHelper::datetime('created'),
				SchemaHelper::int('status'),
				SchemaHelper::int('propertyId'),
                                SchemaHelper::int('category'),
                SchemaHelper::varchar('url', 255),
                SchemaHelper::int('orderNumber')
			),
			SchemaHelper::indexes(
				SchemaHelper::index('PRIMARY','markerId',true)
			)
		)
	),
        
);