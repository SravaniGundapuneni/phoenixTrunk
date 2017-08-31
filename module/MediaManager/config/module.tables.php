<?php
/**
 * Media Manager Table Structures
 *
 * Media Manager Table Structures for all tables it uses
 *
 * @category    Toolbox
 * @package     MediaManager
 * @subpackage  Tables
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.5.0
 * @since       File available since release 13.5.0
 * @author      Andrew Tate <atate@travelclick.com>
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
		'mediaManager_images' => SchemaHelper::table(
		SchemaHelper::defaultScope(
			SchemaHelper::fields(
				SchemaHelper::primary('imageId'),
				SchemaHelper::int('fileId'),
				SchemaHelper::int('createdUserId'),                
				SchemaHelper::int('modifiedUserId'),
				SchemaHelper::datetime('modified'),
				SchemaHelper::datetime('created'),
				SchemaHelper::int('status'),
				SchemaHelper::varchar('dataSection', 255),
								SchemaHelper::varchar('altText', 255),
				SchemaHelper::int('imageWidth'),
				SchemaHelper::int('imageHeight'),
				SchemaHelper::int('socialSharing')
			),
			SchemaHelper::indexes(
				SchemaHelper::index('PRIMARY','imageId',true)
			),
						SchemaHelper::InnoDB()	
		)
	),
	'mediaManagerFiles' => SchemaHelper::table(
		SchemaHelper::defaultScope(
			SchemaHelper::fields(
				SchemaHelper::primary('fileId'),
				SchemaHelper::varchar('name', 255),
				SchemaHelper::varchar('path', 255),
				SchemaHelper::varchar('type', 255),
				SchemaHelper::varchar('origName', 255),
				SchemaHelper::int('createdUserId'),                
				SchemaHelper::int('modifiedUserId'),
				SchemaHelper::datetime('modified'),
				SchemaHelper::datetime('created'),
				SchemaHelper::int('status'),
				SchemaHelper::int('propertyId')
			),
			SchemaHelper::indexes(
				SchemaHelper::index('PRIMARY','fileId',true)
			),
			SchemaHelper::InnoDB()			
		)
	),
	'mediaManagerFileAttachments' => SchemaHelper::table(
		SchemaHelper::defaultScope(
			SchemaHelper::fields(
				SchemaHelper::primary('attachId'),
				SchemaHelper::reference('file', 'mediaManagerFiles', 'fileId'),
				SchemaHelper::varchar('langCode', 3),
				SchemaHelper::varchar('parentModule', 255),
				SchemaHelper::int('parentItemId'),
				SchemaHelper::int('orderNumber'),
				SchemaHelper::int('propertyId'),
				SchemaHelper::varchar('altText', 255),

				// Last 4 coulumns (do not change)
				SchemaHelper::int('createdUserId'),                
				SchemaHelper::int('modifiedUserId'),
				SchemaHelper::datetime('created'),
				SchemaHelper::datetime('modified'),
				SchemaHelper::int('status')
			),
			SchemaHelper::indexes(
				SchemaHelper::index('PRIMARY','attachId',true)
			),
			SchemaHelper::InnoDB()			
		)
	),
	'open_graph' => SchemaHelper::table(
		SchemaHelper::defaultScope(
			SchemaHelper::fields(
				SchemaHelper::primary('opengraphid'),
				SchemaHelper::reference('fileid', 'mediaManagerFiles', 'fileId'),
				SchemaHelper::varchar('ogproperty', 255),
				SchemaHelper::varchar('content', 255)
			),
			SchemaHelper::indexes(
				SchemaHelper::index('PRIMARY','opengraphid', true)
			),
			SchemaHelper::InnoDB()			
		)
	),
	'schema_dot_org_images' => SchemaHelper::table(
		SchemaHelper::defaultScope(
			SchemaHelper::fields(
				SchemaHelper::primary('schemadotorgid'),
				SchemaHelper::reference('fileid', 'mediaManagerFiles', 'fileId'),
				SchemaHelper::varchar('itemprop', 255)
			),
			SchemaHelper::indexes(
				SchemaHelper::index('PRIMARY','schemadotorgid', true)
			),
			SchemaHelper::InnoDB()			
		)
	),
);