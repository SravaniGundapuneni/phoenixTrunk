<?php
/**
 * PhoenixAddons Table Structures
 *
 * PhoenixAddons Table Structures for all tables it uses
 *
 * @category    Toolbox
 * @package     PhoenixAddons
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
    'phoenixAddons' => SchemaHelper::table(
        SchemaHelper::defaultScope(
            SchemaHelper::fields(
                SchemaHelper::primary('addonId'),
                SchemaHelper::varchar('code'),
                SchemaHelper::varchar('name'),
                SchemaHelper::reference('property', 'phoenixProperties', 'propertyId'),
                SchemaHelper::reference('item', 'componentItems', 'itemId'),
                SchemaHelper::text('description'),
                SchemaHelper::decimal('price', 10, 2, 'YES', '0.00'),
                SchemaHelper::varchar('currency'),
                SchemaHelper::varchar('tax'),
                SchemaHelper::tinyint('featured'),
                SchemaHelper::text('owsData'),
                SchemaHelper::tinyInt('userModified'),
                //french
                SchemaHelper::varchar('name_fr'),
                SchemaHelper::varchar('description_fr'),
                // Last 4 coulumns (do not change)
                SchemaHelper::int('createdUserId'),
                SchemaHelper::int('modifiedUserId'),
                SchemaHelper::datetime('created'),
                SchemaHelper::datetime('modified'),
                SchemaHelper::int('status'),
                SchemaHelper::int('orderNumber')
            ),
            SchemaHelper::indexes(
                SchemaHelper::index('PRIMARY','addonId',true),
                SchemaHelper::index('PhoenixProperty','property')
            )
        )
    )
);

