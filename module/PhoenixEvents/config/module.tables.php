<?php
/**
 * Phoenix Event Table Structures
 *
 * Phoenix Event Table Structures for all tables it uses
 *
 * @category    Toolbox
 * @package     ImageSwitch
 * @subpackage  Tables
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.5.5
 * @since       File available since release 13.5.5
 * @author      Kevin Davis <kedavis@travelclick.com>
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
	'phoenixEvents' => SchemaHelper::table(
		 SchemaHelper::defaultScope(
			SchemaHelper::fields(
				 SchemaHelper::primary('eventId'),
				 //SchemaHelper::reference('item', 'componentItems', 'itemId'),				 
				 SchemaHelper::varchar('eventName',255),
				 SchemaHelper::varchar('eventStart',20),
				 SchemaHelper::varchar('eventStartTime',20),
				 SchemaHelper::varchar('eventEnd',20),
				 SchemaHelper::varchar('eventEndTime',20),
                                SchemaHelper::int('propertyId'),
				 SchemaHelper::text('eventDescription'),
				 SchemaHelper::int('showMainImage'),
				 SchemaHelper::int('createdUserId'),
				 SchemaHelper::int('modifiedUserId'),
				 SchemaHelper::datetime('created'),
				 SchemaHelper::datetime('modified'),
				 SchemaHelper::int('status'),
                                SchemaHelper::int('orderNumber')
			),
			 SchemaHelper::indexes(
				SchemaHelper::index('PRIMARY','eventId',true)
			)
		)
	)
);
