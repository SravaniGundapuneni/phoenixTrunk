<?php
/**
 * UserReviews Table Structures
 *
 * Users Table Structures for all tables it uses
 *
 * @category    Toolbox
 * @package     UserReviews
 * @subpackage  Tables
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.4.0
 * @since       File available since release 13.4.0
 * @author      <hnaik@travelclick.com>
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
   'mailinglist' => SchemaHelper::table(
        SchemaHelper::defaultScope(
            SchemaHelper::fields(
                SchemaHelper::primary('itemId'),
                SchemaHelper::varchar('title', 255),
                SchemaHelper::varchar('email', 255),
                SchemaHelper::varchar('firstName', 255),
				SchemaHelper::varchar('lastName', 255),
				SchemaHelper::varchar('countryCode', 255),
				SchemaHelper::varchar('hash',255),
				SchemaHelper::varchar('dataSection', 255),
                SchemaHelper::int('subscribe'),
				SchemaHelper::int('userId'),
                SchemaHelper::datetime('created'),
				SchemaHelper::datetime('modified'),
				SchemaHelper::int('status'),
                SchemaHelper::int('emailConfirmed')
            ),
            SchemaHelper::indexes(
                SchemaHelper::index('PRIMARY','itemId',true)
            ),
            SchemaHelper::InnoDB()
        )
    ),
    
      
);