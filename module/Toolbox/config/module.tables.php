<?php
/**
 * Toolbox Table Structures
 *
 * Toolbox Table Structures for all tables it uses
 *
 * @category    Toolbox
 * @package     Toolbox
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
    'components' => SchemaHelper::table(
        SchemaHelper::adminScope(
            SchemaHelper::fields(
                SchemaHelper::primary('componentId'),
                SchemaHelper::varchar('name', 255),
                SchemaHelper::varchar('label', 255),
                SchemaHelper::text('description'),
                SchemaHelper::tinyint('dynamic'),
                SchemaHelper::tinyint('categories'),
                SchemaHelper::tinyint('casEnabled'),
                SchemaHelper::tinyint('casAllowed'), 
                // Last 4 coulumns (do not change)
                SchemaHelper::int('createdUserId'),
                SchemaHelper::datetime('created'),
                SchemaHelper::int('modifiedUserId'),
                SchemaHelper::datetime('modified'),
                SchemaHelper::int('status')
            ),
            SchemaHelper::indexes(
                SchemaHelper::index('PRIMARY','moduleId',true)
            ),
            SchemaHelper::InnoDB()            
        ),
        SchemaHelper::defaultScope(
            SchemaHelper::fields(
                SchemaHelper::primary('componentId'),
                SchemaHelper::varchar('name', 255),
                SchemaHelper::varchar('label', 255),
                SchemaHelper::text('description'),
                SchemaHelper::tinyint('dynamic'),                
                SchemaHelper::tinyint('categories'),
                SchemaHelper::tinyint('casEnabled'),
                SchemaHelper::tinyint('casAllowed'),    
                SchemaHelper::int('adminRepoId'),
                
                // Last 4 coulumns (do not change)
                SchemaHelper::int('createdUserId'),
                SchemaHelper::datetime('created'),
                SchemaHelper::int('modifiedUserId'),
                SchemaHelper::datetime('modified'),
                SchemaHelper::int('status')
            ),
            SchemaHelper::indexes(
                SchemaHelper::index('PRIMARY','componentId',true)
            ),
            SchemaHelper::InnoDB()            
        )        
    ),
    'componentFields' => SchemaHelper::table(
        SchemaHelper::adminScope(
            SchemaHelper::fields(
                SchemaHelper::primary('componentFieldId'),
                SchemaHelper::reference('component', 'components', 'componentId'),
                SchemaHelper::varchar('name', 255),
                SchemaHelper::varchar('label', 255),
                SchemaHelper::varchar('type', 255),
                SchemaHelper::int('showInList'),
                SchemaHelper::int('orderNumber'),
                SchemaHelper::int('translate'),
                SchemaHelper::int('required'),
                // Last 4 coulumns (do not change)
                SchemaHelper::int('createdUserId'),
                SchemaHelper::datetime('created'),
                SchemaHelper::int('modifiedUserId'),
                SchemaHelper::datetime('modified'),
                SchemaHelper::int('status')
            ),
            SchemaHelper::indexes(
                SchemaHelper::index('PRIMARY','moduleId',true)
            ),
            SchemaHelper::InnoDB()            
        ),
        SchemaHelper::defaultScope(
            SchemaHelper::fields(
                SchemaHelper::primary('componentFieldId'),
                SchemaHelper::reference('component', 'components', 'componentId'),
                SchemaHelper::varchar('name', 255),
                SchemaHelper::varchar('label', 255),
                SchemaHelper::int('translate'),
                SchemaHelper::varchar('type', 255),
                SchemaHelper::int('showInList'),
                SchemaHelper::int('orderNumber'), 
                SchemaHelper::int('required'),               
                // Last 4 coulumns (do not change)
                SchemaHelper::int('createdUserId'),
                SchemaHelper::datetime('created'),
                SchemaHelper::int('modifiedUserId'),
                SchemaHelper::datetime('modified'),
                SchemaHelper::int('status')
            ),
            SchemaHelper::indexes(
                SchemaHelper::index('PRIMARY','moduleId',true)
            ),
            SchemaHelper::InnoDB()            
        )        
    ),
    'componentItems' => SchemaHelper::table(
        SchemaHelper::defaultScope(
            SchemaHelper::fields(
                SchemaHelper::primary('itemId'),
                SchemaHelper::tinyint('allProperties'),
                SchemaHelper::reference('component', 'components', 'componentId'),
                SchemaHelper::reference('property', 'phoenixProperties', 'propertyId', true, 'YES'),
                SchemaHelper::int('categoryId'),
                // Last 4 columns (do not change)
                SchemaHelper::int('createdUserId'),
                SchemaHelper::datetime('created'),
                SchemaHelper::int('modifiedUserId'),
                SchemaHelper::datetime('modified'),
                SchemaHelper::int('status'),
                SchemaHelper::int('orderNumber')
            ),
            SchemaHelper::indexes(
                SchemaHelper::index('PRIMARY', 'itemId', true)
            ),
            SchemaHelper::InnoDB()
        )
    ),
    'contentAppearance' => SchemaHelper::table(
        SchemaHelper::defaultScope(
            SchemaHelper::fields(
                SchemaHelper::primary('appearanceId'),
                SchemaHelper::varchar('contentType', 30),
                SchemaHelper::varchar('contentKey', 50),
                SchemaHelper::varchar('page'),
                SchemaHelper::text('configuration'),

                // Last 4 coulumns (do not change)
                SchemaHelper::int('createdUserId'),                
                SchemaHelper::int('modifiedUserId'),
                SchemaHelper::datetime('created'),
                SchemaHelper::datetime('modified'),
                SchemaHelper::int('status')
            ),
            SchemaHelper::indexes(
                SchemaHelper::index('PRIMARY','appearanceId',true),
                SchemaHelper::index('contentType','contentType'),
                SchemaHelper::index('contentKey','contentKey'),
                SchemaHelper::index('appearancePage','page')
            )
        )
    )
);