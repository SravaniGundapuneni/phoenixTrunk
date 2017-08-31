<?php
/**
 * Media Manager Table Structures
 *
 * Media Manager Table Structures for all tables it uses
 *
 * @category    Toolbox
 * @package     ImageSwitch
 * @subpackage  Tables
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.5.0
 * @since       File available since release 13.5.0
 * @author      Andrew Tate <atate@travelclick.com>
 * @filesource
 */

use \ModuleMigrations\StdLib\SchemaHelper;

return array(
	'seoFileUploadFiles' => SchemaHelper::table(
		SchemaHelper::defaultScope(
			SchemaHelper::fields(
				SchemaHelper::primary('fileId'),
				SchemaHelper::varchar('name', 255),
				SchemaHelper::varchar('path', 255),
				SchemaHelper::varchar('type', 255),
				SchemaHelper::varchar('origName', 255),
                SchemaHelper::int('createdUserId'),                
                SchemaHelper::int('modifiedUserId'),
                SchemaHelper::datetime('created'),
				SchemaHelper::datetime('modified')
				
				
			),
			SchemaHelper::indexes(
				SchemaHelper::index('PRIMARY','fileId',true)
			),
			SchemaHelper::InnoDB()			
		)
	)	
);