<?php
/**
 * Languages Table Structures
 *
 * Languages Table Structures for all tables it uses
 *
 * @category    Toolbox
 * @package     Languages
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
    'languages' => SchemaHelper::table(
        SchemaHelper::defaultScope(
            SchemaHelper::fields(
                SchemaHelper::primary('languageId'),
                SchemaHelper::varchar('code', 4),
                SchemaHelper::varchar('name', 48),
                SchemaHelper::varchar('englishName', 255),
                SchemaHelper::tinyint('default'),

                // Last 5 columns (do not change)
                SchemaHelper::int('createdUserId'),
                SchemaHelper::datetime('created'),
                SchemaHelper::int('modifiedUserId'),
                SchemaHelper::datetime('modified'),
                SchemaHelper::int('status')                
            ),
            SchemaHelper::indexes(
                SchemaHelper::index('PRIMARY','languageId',true)                
            ),
            SchemaHelper::InnoDB()
        ),
        SchemaHelper::adminScope(
            SchemaHelper::fields(
                SchemaHelper::primary('langID'),
                SchemaHelper::varchar('code', 4),
                SchemaHelper::varchar('name', 48),
                SchemaHelper::varchar('nameEnglish', 255),

                // Last 5 columns (do not change)
                SchemaHelper::int('createdUserId', 11, true),
                SchemaHelper::datetime('created'),
                SchemaHelper::int('modifiedUserId', 11, true),
                SchemaHelper::datetime('modified'),
                SchemaHelper::int('status')                
            ),
            SchemaHelper::indexes(
                SchemaHelper::index('PRIMARY','languageId',true)                
            )
        )
    ),
    'languages_translations' => SchemaHelper::table(
        SchemaHelper::defaultScope(
            SchemaHelper::fields(
                SchemaHelper::primary('translationId'),
                SchemaHelper::reference('component', 'components', 'componentId'),
                SchemaHelper::reference('field', 'componentFields', 'componentFieldId'),
                SchemaHelper::reference('language', 'languages', 'languageId'),
                SchemaHelper::int('item'),
                SchemaHelper::text('content'),

                // Last 5 columns (do not change)
                SchemaHelper::int('createdUserId'),
                SchemaHelper::datetime('created'),
                SchemaHelper::int('modifiedUserId'),
                SchemaHelper::datetime('modified'),
                SchemaHelper::int('status')                
            ),
            SchemaHelper::indexes(
                SchemaHelper::index('PRIMARY','translationId',true)                
            ),
            SchemaHelper::InnoDB()              
        )        
    ),
    'property_languages' => SchemaHelper::table(
        SchemaHelper::defaultScope(
            SchemaHelper::fields(
                SchemaHelper::primary('plId'),
                SchemaHelper::int('property'),
                SchemaHelper::int('languageId'),
                SchemaHelper::tinyint('default')
            ),
            SchemaHelper::indexes(
                SchemaHelper::index('PRIMARY','plId',true)                
            ),
            SchemaHelper::InnoDB()
        )
    ),
    'polytext' => SchemaHelper::table(
        SchemaHelper::defaultScope(
            SchemaHelper::fields(
                SchemaHelper::primary('textId'),
                SchemaHelper::varchar('type'),
                SchemaHelper::varchar('area'),
                SchemaHelper::varchar('name'),
                SchemaHelper::varchar('lang', 10),
                SchemaHelper::text('text'),

                // Last 4 coulumns (do not change)
                SchemaHelper::int('userId'),
                SchemaHelper::datetime('created'),
                SchemaHelper::datetime('modified'),
                SchemaHelper::int('status')
            ),
            SchemaHelper::indexes(
                SchemaHelper::index('type'),
                SchemaHelper::index('lang'),
                SchemaHelper::index('area'),
                SchemaHelper::fulltext('text'),
                SchemaHelper::index('status')
            )
        ),
        SchemaHelper::adminScope(
            SchemaHelper::fields(
                SchemaHelper::primary('textId'),
                SchemaHelper::varchar('type'),
                SchemaHelper::varchar('area'),
                SchemaHelper::varchar('name'),
                SchemaHelper::varchar('lang', 10),
                SchemaHelper::text('text'),

                // Last 4 coulumns (do not change)
                SchemaHelper::int('userId'),
                SchemaHelper::datetime('created'),
                SchemaHelper::datetime('modified'),
                SchemaHelper::int('status')
            ),
            SchemaHelper::indexes(
                SchemaHelper::index('type'),
                SchemaHelper::index('lang'),
                SchemaHelper::index('area'),
                SchemaHelper::fulltext('text'),
                SchemaHelper::index('status')
            )
        )
    )
);