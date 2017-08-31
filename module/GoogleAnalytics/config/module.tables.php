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
 * @author      Sravani Gundapuneni <sgundapuneni@travelclick.com>
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
	'googleAnalytics' => SchemaHelper::table(
		SchemaHelper::defaultScope(
			SchemaHelper::fields(
				SchemaHelper::primary('googleAnalyticId'),
				SchemaHelper::varchar('title', 255),
				SchemaHelper::varchar('gaAccount', 255),
                                SchemaHelper::tinyint('eventTracking'),
                                SchemaHelper::tinyint('crossTracking'),
                                SchemaHelper::tinyint('remarketing'),
                                SchemaHelper::tinyint('anonynize'),
                                SchemaHelper::varchar('keyValue', 255),
				SchemaHelper::varchar('domain', 64),
                                SchemaHelper::varchar('crossDomain', 64),
                                SchemaHelper::varchar('bookingUrl', 128),
                                SchemaHelper::varchar('pageNames', 255),
				SchemaHelper::datetime('modified'),
				SchemaHelper::datetime('created'),
				SchemaHelper::int('status')
				
			),
			SchemaHelper::indexes(
				SchemaHelper::index('PRIMARY','googleAnalyticId',true)
			)
		)
	)
);