<?php
/**
 * PhoenixProperties Table Structures
 *
 * PhoenixProperties Table Structures for all tables it uses
 *
 * @category    Toolbox
 * @package     PhoenixProperties
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
    'phoenixProperties' => SchemaHelper::table(
        SchemaHelper::defaultScope(
            SchemaHelper::fields(
                SchemaHelper::primary('propertyId'),
                SchemaHelper::varchar('code'),
                SchemaHelper::varchar('name'),
                SchemaHelper::text('description'),
                SchemaHelper::varchar('url', 200),
                SchemaHelper::varchar('address', 100),
                SchemaHelper::varchar('suiteApt', 100),
                SchemaHelper::varchar('city', 100),
                SchemaHelper::varchar('state', 100),
                SchemaHelper::varchar('zip', 20),
                SchemaHelper::varchar('country'),
                SchemaHelper::varchar('tollfreeNumber', 22),
                SchemaHelper::varchar('phoneNumber', 22),
                SchemaHelper::varchar('faxNumber', 22),
                SchemaHelper::varchar('email'),
                SchemaHelper::varchar('group'),
                SchemaHelper::varchar('latitude', 20),
                SchemaHelper::varchar('longitude', 20),
                SchemaHelper::int('labelX'),
                SchemaHelper::int('labelY'),
                SchemaHelper::varchar('photo'),
                SchemaHelper::varchar('tempFormat',1),
                SchemaHelper::text('history'),
                SchemaHelper::text('policy'),
                SchemaHelper::tinyint('categories'),
                SchemaHelper::tinyint('showMainImage'),
                SchemaHelper::tinyint('isCorporate'),
                SchemaHelper::text('owsData'),
                SchemaHelper::text('twitter'),
                SchemaHelper::text('instagram'),
				SchemaHelper::text('facebook'),
                SchemaHelper::text('sitePath'),
                SchemaHelper::tinyInt('userModified'),
                    
                // French
                SchemaHelper::varchar('name_fr'),
                SchemaHelper::text('description_fr'),   
                SchemaHelper::varchar('address_fr', 100),
                SchemaHelper::varchar('city_fr', 100),
                SchemaHelper::varchar('state_fr', 100),   
                    
                // Last 4 coulumns (do not change)
                SchemaHelper::int('createdUserId'),                
                SchemaHelper::int('modifiedUserId'),
                SchemaHelper::datetime('created'),
                SchemaHelper::datetime('modified'),
                SchemaHelper::int('status'),
                SchemaHelper::int('orderNumber')
            ),
            SchemaHelper::indexes(
                SchemaHelper::index('PRIMARY','propertyId',true)
            ),
            SchemaHelper::InnoDB()
        )
    )
);