<?php
use ModuleMigrations\StdLib\SchemaHelper;

return array(
    // 'dynamicListModules' => SchemaHelper::table(
    //     SchemaHelper::defaultScope(
    //         SchemaHelper::fields(
    //             SchemaHelper::primary('moduleId'),
    //             SchemaHelper::varchar('name', 255),
    //             SchemaHelper::text('description'),
    //             SchemaHelper::tinyint('categories'),
    //             SchemaHelper::int('userId'),
    //             SchemaHelper::datetime('modified'),
    //             SchemaHelper::datetime('created'),
    //             SchemaHelper::int('status')
    //         ),
    //         SchemaHelper::indexes(
    //             SchemaHelper::index('PRIMARY','moduleId',true)
    //         ),
    //         SchemaHelper::InnoDB()            
    //     )
    // ),
    // 'dynamicListModule_fields' => SchemaHelper::table(
    //     SchemaHelper::defaultScope(
    //         SchemaHelper::fields(
    //             SchemaHelper::primary('fieldId'),
    //             SchemaHelper::reference('module', 'modules', 'moduleId'),
    //             SchemaHelper::varchar('name', 255),
    //             SchemaHelper::varchar('displayName', 255),
    //             SchemaHelper::varchar('type', 255),
    //             SchemaHelper::int('showInList'),
    //             SchemaHelper::int('orderNumber'),
    //             // Last 4 coulumns (do not change)
    //             SchemaHelper::int('userId'),
    //             SchemaHelper::datetime('created'),
    //             SchemaHelper::datetime('modified'),
    //             SchemaHelper::int('status')
    //         ),
    //         SchemaHelper::indexes(
    //             SchemaHelper::index('PRIMARY','fieldId',true)
    //         ),
    //         SchemaHelper::InnoDB()            
    //     )
    // ),

	'dynamicListModule_pages' => SchemaHelper::table(
        SchemaHelper::defaultScope(
            SchemaHelper::fields(
			    SchemaHelper::primary('ipId'),
                SchemaHelper::int('itemId'),
				SchemaHelper::int('pageId')               
            ),
            SchemaHelper::indexes(
                SchemaHelper::index('PRIMARY','ipId', true)
            ),
            SchemaHelper::InnoDB()
        )
    ),

    // 'dynamicListModule_items' => SchemaHelper::table(
    //     SchemaHelper::defaultScope(
    //         SchemaHelper::fields(
    //             SchemaHelper::primary('itemId'),
    //             SchemaHelper::tinyint('allProperties'),
    //             SchemaHelper::reference('component', 'components', 'componentId'),
    //             SchemaHelper::reference('property', 'phoenixProperties', 'propertyId'),
    //             SchemaHelper::int('categoryId'),
    //             // Last 4 columns (do not change)
    //             SchemaHelper::int('userId'),
    //             SchemaHelper::datetime('created'),
    //             SchemaHelper::datetime('modified'),
    //             SchemaHelper::int('status')
    //         ),
    //         SchemaHelper::indexes(
    //             SchemaHelper::index('PRIMARY', 'itemId', true)
    //         ),
    //         SchemaHelper::InnoDB()
    //     )
    // ),

    'dynamicListModule_itemFields' => SchemaHelper::table(
        SchemaHelper::defaultScope(
            SchemaHelper::fields(
                SchemaHelper::primary('ifId'),
                SchemaHelper::reference('item', 'componentItems', 'itemId'),
                SchemaHelper::reference('field', 'componentFields', 'componentFieldId'),
                SchemaHelper::text('value'),
                // Last 4 coulumns (do not change)
                SchemaHelper::int('createdUserId'),                
                SchemaHelper::int('modifiedUserId'),
                SchemaHelper::datetime('created'),
                SchemaHelper::datetime('modified'),
                SchemaHelper::int('status')
            ),
            SchemaHelper::indexes(
                SchemaHelper::index('PRIMARY', 'ifId', true)
            ),
            SchemaHelper::InnoDB()
        )
    ), 
    'dynamicListModule_selectValues' => SchemaHelper::table(
        SchemaHelper::adminScope(
            SchemaHelper::fields(
                SchemaHelper::primary('valId'),
                SchemaHelper::text('name'),
                // Last 4 coulumns (do not change)
                SchemaHelper::int('field')
            ),
            SchemaHelper::indexes(
                SchemaHelper::index('PRIMARY', 'valId', true)
            ),
            SchemaHelper::InnoDB()
        ),
        SchemaHelper::defaultScope(
            SchemaHelper::fields(
                SchemaHelper::primary('valId'),
                SchemaHelper::text('name'),
                // Last 4 coulumns (do not change)
                SchemaHelper::int('field')
            ),
            SchemaHelper::indexes(
                SchemaHelper::index('PRIMARY', 'valId', true)
            ),
            SchemaHelper::InnoDB()
        )
    )
);