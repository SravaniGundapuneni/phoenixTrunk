<?php
/**
 * PhoenixRooms Table Structures
 *
 * PhoenixRooms Table Structures for all tables it uses
 *
 * @category    Toolbox
 * @package     PhoenixRooms
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
	'phoenixRooms' => SchemaHelper::table(
		SchemaHelper::defaultScope(
			SchemaHelper::fields(
				SchemaHelper::primary('roomId'),
				SchemaHelper::varchar('code'),
				SchemaHelper::varchar('name'),
				SchemaHelper::reference('property', 'phoenixProperties', 'propertyId'),
                SchemaHelper::reference('item', 'componentItems', 'itemId'),                
				SchemaHelper::text('description'),
				SchemaHelper::text('features'),
				SchemaHelper::varchar('bedType', 30),
				SchemaHelper::int('maxOccupancy'),
				SchemaHelper::int('category'),
				SchemaHelper::tinyint('showMainImage'),
				SchemaHelper::tinyint('featured'),
                SchemaHelper::varchar('bookingLink'),
                SchemaHelper::decimal('price', 10, 2, 'YES', '0.00'),
                SchemaHelper::varchar('virtualTour'),                                
                SchemaHelper::tinyint('isCorporate'),
                SchemaHelper::text('owsData'),
                SchemaHelper::tinyInt('userModified'),
                // french
                SchemaHelper::varchar('name_fr'),
                SchemaHelper::text('description_fr'),
                // Last 4 coulumns (do not change)
                SchemaHelper::int('createdUserId'),                
                SchemaHelper::int('modifiedUserId'),
				SchemaHelper::datetime('created'),
				SchemaHelper::datetime('modified'),
				SchemaHelper::int('status'),
                SchemaHelper::int('orderNumber')
			),
			SchemaHelper::indexes(
				SchemaHelper::index('PRIMARY','roomId',true),
				SchemaHelper::index('PhoenixProperty', 'property')
			),
            SchemaHelper::InnoDB()
		)
	)
);