<?php
/**
 * Phoenix Properties Table Structures
 *
 * Phoenix Properties Table Structures for all tables it uses
 *
 * @category    Toolbox
 * @package     SocialToolbar
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
	'socialToolbar' => SchemaHelper::table(
		SchemaHelper::defaultScope(
			SchemaHelper::fields(
				SchemaHelper::primary('socialToolId'),
				SchemaHelper::int('toolbar_enabled'),
				SchemaHelper::int('layout'),
				SchemaHelper::int('color'),
				SchemaHelper::int('recommend_email'),
				SchemaHelper::int('imgTwitter'),
				SchemaHelper::int('imgGoogle'),
				SchemaHelper::int('imgFacebook'),
				SchemaHelper::varchar('imgFacebookTitle', 255),
				SchemaHelper::varchar('imgFacebookCaption', 255),
				SchemaHelper::varchar('imgFacebookDescription', 255),
				SchemaHelper::int('iconPreset'),
				SchemaHelper::int('toolbarLayout'),
				SchemaHelper::int('colorScheme'),
				SchemaHelper::varchar('gradientTop', 255),
				SchemaHelper::varchar('gradientBottom', 255),
				SchemaHelper::varchar('layoutFont', 255),
				SchemaHelper::varchar('layoutBorders', 255),
				SchemaHelper::int('rounded'),
				SchemaHelper::int('extended'),
				SchemaHelper::int('showLabel'),
				SchemaHelper::int('onlyIcons'),
				SchemaHelper::varchar('toolbarSize', 255),
				SchemaHelper::int('butTwitter'),
				SchemaHelper::int('butFacebook'),
				SchemaHelper::int('butPinterest'),
				SchemaHelper::int('butGoogle'),
				SchemaHelper::int('butEmail'),
				SchemaHelper::varchar('dataSection', 255),
				SchemaHelper::varchar('smFacebook', 255),
				SchemaHelper::int('smFacebookEnabled'),
				SchemaHelper::int('showStream'),
				SchemaHelper::int('showFaces'),
				SchemaHelper::tinyint('showStreamOrFaces'),
				SchemaHelper::int('shareTooltip'),
				SchemaHelper::varchar('smTwitter', 255),
				SchemaHelper::int('smTwitterEnabled'),
				SchemaHelper::varchar('smYouTube', 255),
				SchemaHelper::int('smYouTubeEnabled'),
				SchemaHelper::varchar('smTripAdivsor', 255),
				SchemaHelper::int('smTripAdivsorEnabled'),
				SchemaHelper::datetime('modified'),
				SchemaHelper::datetime('created'),
                SchemaHelper::int('createdUserId'),                
                SchemaHelper::int('modifiedUserId')
			),
			SchemaHelper::indexes(
				SchemaHelper::index('PRIMARY','socialToolId',true)
			)
		)
	)
);