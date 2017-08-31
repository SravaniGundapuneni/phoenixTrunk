<?php
/**
 * PhoenixRates Table Structures
 *
 * PhoenixRates Table Structures for all tables it uses
 *
 * @category    Toolbox
 * @package     PhoenixRates
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
	'phoenixRates' => SchemaHelper::table(
		SchemaHelper::defaultScope(
			SchemaHelper::fields(
				SchemaHelper::primary('rateId'),
				SchemaHelper::varchar('code'),
				SchemaHelper::varchar('name'),
                SchemaHelper::reference('property', 'phoenixProperties', 'propertyId'),
                SchemaHelper::reference('item', 'componentItems', 'itemId'),                
				SchemaHelper::text('description'),
				SchemaHelper::int('category'),
				SchemaHelper::text('policy'),
                                SchemaHelper::text('terms'),
				SchemaHelper::decimal('price', 10, 2, 'YES', '0.00'),
				SchemaHelper::varchar('bookingLink'),
				SchemaHelper::datetime('startDate'),
				SchemaHelper::datetime('autoExpiry'),
				SchemaHelper::tinyint('categories'),
				SchemaHelper::tinyint('showMainImage'),
				SchemaHelper::tinyint('featured'),
                                SchemaHelper::tinyint('brandFeatured'),
                                SchemaHelper::tinyint('specialOffers'),
                                SchemaHelper::tinyint('corporateFeatured'),
				SchemaHelper::varchar('membership'),
				SchemaHelper::varchar('rateTypeCategory'),
                                SchemaHelper::tinyint('isMember'),
                                SchemaHelper::text('owsData'),
                                SchemaHelper::tinyInt('userModified'),
                                
                                // french
                                SchemaHelper::varchar('name_fr'),
                                SchemaHelper::text('description_fr'),
                                SchemaHelper::text('terms_fr'),
                // Last 4 coulumns (do not change)
				SchemaHelper::int('createdUserId'),
                SchemaHelper::int('modifiedUserId'),
				SchemaHelper::datetime('created'),
				SchemaHelper::datetime('modified'),
				SchemaHelper::int('status'),
                                SchemaHelper::int('orderNumber')
			),
			SchemaHelper::indexes(
				SchemaHelper::index('PRIMARY','rateId',true)
			),
            SchemaHelper::InnoDB()
		)
	),
	'pageRates' => SchemaHelper::table(
		SchemaHelper::defaultScope(
			SchemaHelper::fields(
				SchemaHelper::primary('prId'),
				SchemaHelper::int('pageId'),
				SchemaHelper::int('rateId'),
				SchemaHelper::int('orderNumber')
			),
			SchemaHelper::indexes(
				SchemaHelper::index('PRIMARY','prId',true)
			)
		)
	),
        'basecurrency' => SchemaHelper::table(
		SchemaHelper::adminScope(
			SchemaHelper::fields(
				SchemaHelper::primary('id'),
				SchemaHelper::varchar('currency'),
				SchemaHelper::varchar('dollarequiv')
			),
			SchemaHelper::indexes(
				SchemaHelper::index('PRIMARY','id',true)
			)
		)
                )
);