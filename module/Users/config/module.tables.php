<?php
/**
 * Users Table Structures
 *
 * Users Table Structures for all tables it uses
 *
 * @category    Toolbox
 * @package     Users
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
    'groups' => SchemaHelper::table(
        SchemaHelper::adminScope(
            SchemaHelper::fields(
                SchemaHelper::primary('groupId'),
                SchemaHelper::varchar('name', 255, 'NO')
            ),
            SchemaHelper::indexes(
                SchemaHelper::index('PRIMARY','groupId',true)
            ),
            SchemaHelper::InnoDB()
        )
    ),
    'users' => SchemaHelper::table(
        SchemaHelper::adminScope(
            SchemaHelper::fields(
                SchemaHelper::primary('userID'),
                SchemaHelper::varchar('username', 32, 'NO', ""),
                SchemaHelper::varchar('password', 255, 'NO', ""),
                SchemaHelper::varchar('givenName', 255, 'NO', ""),
                SchemaHelper::varchar('surnames', 255, 'NO', ""),
                SchemaHelper::varchar('email', 255, 'NO', ""),
                SchemaHelper::varchar('resetPassKey', 32, 'YES', ""),
                SchemaHelper::datetime('resetPassExpires'),
                SchemaHelper::tinyint('isCorporate'),
                SchemaHelper::int('type', 'NO', "0")
            ),
            SchemaHelper::indexes(
                SchemaHelper::index('PRIMARY','userId',true)
            ),
            SchemaHelper::InnoDB()
        )
    ),
    'users_groups' => SchemaHelper::table(
        SchemaHelper::adminScope(
            SchemaHelper::fields(
                SchemaHelper::primary('ugId'),
                SchemaHelper::reference('userId', 'users', 'userId'),
                SchemaHelper::reference('groupId', 'groups', 'groupId')
            ),
            SchemaHelper::indexes(
                SchemaHelper::index('PRIMARY','ugId',true)
            ),
            SchemaHelper::InnoDB()
        )
    ),
    'site_groups' => SchemaHelper::table(
        SchemaHelper::defaultScope(
            SchemaHelper::fields(
                SchemaHelper::primary('groupId'),
                SchemaHelper::varchar('name', 255, 'NO'),
                SchemaHelper::datetime('created'),
                SchemaHelper::datetime('modified')                
            ),
            SchemaHelper::indexes(
                SchemaHelper::index('PRIMARY','groupId',true)
            ),
            SchemaHelper::InnoDB()
        )
    ),
    'site_users' => SchemaHelper::table(
        SchemaHelper::defaultScope(
            SchemaHelper::fields(
                SchemaHelper::primary('userID'),
                SchemaHelper::varchar('username', 32, 'NO', ""),
                SchemaHelper::varchar('password', 255, 'NO', ""),
                SchemaHelper::varchar('givenName', 255, 'NO', ""),
                SchemaHelper::varchar('surnames', 255, 'NO', ""),
                SchemaHelper::varchar('email', 255, 'NO', ""),
                SchemaHelper::varchar('resetPassKey', 32, 'NO', ""),
                SchemaHelper::datetime('resetPassExpires'),
                SchemaHelper::tinyint('isCorporate'),
                SchemaHelper::int('type', 'NO', "0"),
                SchemaHelper::datetime('created'),
                SchemaHelper::datetime('modified')                
            ),
            SchemaHelper::indexes(
                SchemaHelper::index('PRIMARY','userId',true)
            ),
            SchemaHelper::InnoDB(),
            100001
        )
    ),
    'site_users_groups' => SchemaHelper::table(
        SchemaHelper::defaultScope(
            SchemaHelper::fields(
                SchemaHelper::primary('ugId'),
                SchemaHelper::reference('userId', 'site_users', 'userId'),
                SchemaHelper::reference('groupId', 'site_groups', 'groupId')
            ),
            SchemaHelper::indexes(
                SchemaHelper::index('PRIMARY','ugId',true)
            ),
            SchemaHelper::InnoDB()
        )
    ),
    'sessions' => SchemaHelper::table(
        SchemaHelper::adminScope(
            SchemaHelper::fields(
                /**
                 * @todo  Make changes so these fields adhere to Phoenix field naming standards.
                 */
                SchemaHelper::primary('recordID'),
                SchemaHelper::int('userID'),
                SchemaHelper::varchar('sessID'),
                SchemaHelper::varchar('ipAddress', 100),
                SchemaHelper::datetime('created'),
                SchemaHelper::datetime('expire'),
                SchemaHelper::blob('data', 'NO')
            ),
            SchemaHelper::indexes(
                SchemaHelper::index('PRIMARY','recordID',true)
            ),
            SchemaHelper::InnoDB()
        )
    ),
    'permissions' => SchemaHelper::table(
        SchemaHelper::defaultScope(
            SchemaHelper::fields(
                SchemaHelper::primary('permissionId'),
                SchemaHelper::varchar('name', 255),
                SchemaHelper::int('groupId'),
                SchemaHelper::varchar('authLevel'),
                SchemaHelper::varchar('area'),
                SchemaHelper::datetime('created'),
                SchemaHelper::datetime('modified')
            ),
            SchemaHelper::indexes(
                SchemaHelper::index('PRIMARY','permissionId',true)
            )
        )
    ),
    'user_property' => SchemaHelper::table(
        SchemaHelper::defaultScope(
            SchemaHelper::fields(
                SchemaHelper::primary('upId'),
                SchemaHelper::int('userId'),
                SchemaHelper::int('propertyId'),
                SchemaHelper::varchar('baseAccessLevel')
            ),
            SchemaHelper::indexes(
                SchemaHelper::index('PRIMARY', 'upId', true)
            )
        )
    )
);