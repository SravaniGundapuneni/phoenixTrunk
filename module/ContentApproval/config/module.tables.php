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
    'contentApproval_approvals' => SchemaHelper::table(
        SchemaHelper::defaultScope(
            SchemaHelper::fields(
                SchemaHelper::primary('id'),
                    SchemaHelper::int('workflow'),
                    SchemaHelper::int('item'),
                    SchemaHelper::tinyint('approved'),
                    SchemaHelper::tinyint('status')
            ),
            SchemaHelper::indexes(
                SchemaHelper::index('PRIMARY','id',true)
            )
        )
    ),
    'contentApproval_workflow' => SchemaHelper::table(
        SchemaHelper::defaultScope(
            SchemaHelper::fields(
                SchemaHelper::primary('id'),
                    SchemaHelper::int('userGroup'),
                    SchemaHelper::tinyint('status')
            ),
            SchemaHelper::indexes(
                SchemaHelper::index('PRIMARY','id',true)
            )
        )
    ),
    'contentApproval_Settings' => SchemaHelper::table(
        SchemaHelper::defaultScope(
            SchemaHelper::fields(
                SchemaHelper::primary('id'),
                SchemaHelper::int('enabled')
            ),
            SchemaHelper::indexes(
                SchemaHelper::index('PRIMARY','id',true)
            )
        )
    ),
    'contentApproval' => SchemaHelper::table(
        SchemaHelper::defaultScope(
            SchemaHelper::fields(
                SchemaHelper::primary('id'),
                SchemaHelper::datetime('created'),
                SchemaHelper::datetime('modified'),
                SchemaHelper::varchar('itemTable'),
                SchemaHelper::int('itemId'),
                SchemaHelper::int('createdUserId'),                
                SchemaHelper::int('modifiedUserId'),
                SchemaHelper::varchar('approvalAction'),
                    SchemaHelper::text('data'),
                    SchemaHelper::text('originalData'),
                    SchemaHelper::tinyint('approved'),
                SchemaHelper::tinyint('status')
            ),
            SchemaHelper::indexes(
                SchemaHelper::index('PRIMARY','id',true)
            )
        )
    ),
);