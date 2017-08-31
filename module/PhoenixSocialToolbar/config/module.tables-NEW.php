<?php
/*
 * Phoenix Properties Table Structures for all tables it uses
 *
 * @category    Phoenix Toolbox
 * @package     SocialToolbar
 * @subpackage  Tables
 * @copyright   Copyright (c) 2014 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: Alpha 1.0 - UPDATED 5/6/2014
 * @since       File available since release 13.4.0
 * @filesource
 */

use \ModuleMigrations\StdLib\SchemaHelper;

return array(
	'phoenixSocialToolbarSettings' => SchemaHelper::table(
		SchemaHelper::defaultScope(
			SchemaHelper::fields(
				SchemaHelper::primary('socialToolbarId'),
				SchemaHelper::int('userId'),
				SchemaHelper::datetime('created'),
                SchemaHelper::datetime('modified'),
				SchemaHelper::int('status'),
				SchemaHelper::int('order'),
                SchemaHelper::varchar('toolbarLayout', 1000),
                SchemaHelper::varchar('toolbarWidth', 1000),
                SchemaHelper::varchar('toolbarColorStyles', 1000),
                SchemaHelper::varchar('customColorGradient1', 1000),
                SchemaHelper::varchar('customColorGradient2', 1000),
                SchemaHelper::varchar('toolbarTextColor', 1000),
                SchemaHelper::varchar('toolbarTextStyle', 1000),
                SchemaHelper::int('toolbarSharingTooltip'),
                SchemaHelper::int('toolbarShowLabels'),
                SchemaHelper::varchar('toolbarIconSets', 1000),
                SchemaHelper::int('userId'),
                SchemaHelper::varchar('toolbarCustomIconPath', 1000)
			),
			SchemaHelper::indexes(
				SchemaHelper::index('PRIMARY','socialToolbarID',true)
			),
            SchemaHelper::InnoDB()  
		)
	)


	'phoenixSocialToolbarSites' => SchemaHelper::table(
		SchemaHelper::defaultScope(
			SchemaHelper::fields(
				SchemaHelper::primary('socialToolbarSiteId'),
				SchemaHelper::int('userId'),
				SchemaHelper::datetime('created'),
                SchemaHelper::datetime('modified'),
				SchemaHelper::int('status'),
				SchemaHelper::int('order'),
				SchemaHelper::int('userId'),
                SchemaHelper::varchar('siteUserAccount', 255),
                SchemaHelper::varchar('siteUserPassword', 255),
                SchemaHelper::varchar('apiKey', 1000),
                SchemaHelper::varchar('apiPassword', 1000),
                SchemaHelper::varchar('siteUrl', 255),
                SchemaHelper::varchar('siteName', 255)
			),
			SchemaHelper::indexes(
				SchemaHelper::index('PRIMARY','socialToolbarSiteId',true)
			),
			  SchemaHelper::InnoDB()   
		)
	),
	'phoenixSocialToolbarShare' => SchemaHelper::table(
		SchemaHelper::defaultScope(
			SchemaHelper::fields(
				SchemaHelper::int('userId'),
				SchemaHelper::datetime('created'),
	            SchemaHelper::datetime('modified'),
				SchemaHelper::int('status'),
				SchemaHelper::int('order'),
				SchemaHelper::int('userId'),
				SchemaHelper::varchar('siteName', 1000)
			),
			SchemaHelper::indexes(
				SchemaHelper::index('PRIMARY','socialToolbarShareId',true)
			),
            SchemaHelper::InnoDB()  
        )    
	),
);