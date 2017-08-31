<?php
use ModuleMigrations\StdLib\SchemaHelper;

return array(
    'pages' => SchemaHelper::table(
        SchemaHelper::defaultScope(
            SchemaHelper::fields(
            SchemaHelper::varchar('pageType'),
                SchemaHelper::primary('pageId'),
                SchemaHelper::varchar('dataSection', 255),
                SchemaHelper::varchar('pageKey', 50),
                SchemaHelper::reference('item', 'componentItems', 'itemId'),                
                SchemaHelper::varchar('template', 100),
                SchemaHelper::text('blocks'),
                SchemaHelper::int('categoryId'),
                SchemaHelper::datetime('startDate'),
                SchemaHelper::datetime('autoExpire'),
                SchemaHelper::varchar('eventName', 255),    
                SchemaHelper::varchar('additionalParams', 255),
                SchemaHelper::text('pageHeading'),
                // Last 4 coulumns (do not change)
                SchemaHelper::int('createdUserId'),                
                SchemaHelper::int('modifiedUserId'),
                SchemaHelper::datetime('created'),
                SchemaHelper::datetime('modified'),
                SchemaHelper::int('status'),
	        SchemaHelper::text('blocks2')
            ),
            SchemaHelper::indexes(
                SchemaHelper::index('PRIMARY','pageId',true)
            )
        )
    ),
    'page_properties' => SchemaHelper::table(
        SchemaHelper::defaultScope(
            SchemaHelper::fields(
                SchemaHelper::primary('ppId'),
                SchemaHelper::int('pageId'),
                SchemaHelper::int('propertyId')
            ),
            SchemaHelper::indexes(
                SchemaHelper::index('PRIMARY','ppId',true)
            )
        )
    ),
    'page_blocks' => SchemaHelper::table(
        SchemaHelper::defaultScope(
            SchemaHelper::fields(
                SchemaHelper::primary('pbId'),
                SchemaHelper::int('pageId'),
                SchemaHelper::int('blockId')
            ),
            SchemaHelper::indexes(
                SchemaHelper::index('PRIMARY','pbId',true)
            )
        )
    )
);