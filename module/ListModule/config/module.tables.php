<?php
use ModuleMigrations\StdLib\SchemaHelper;

return array(
    'categories' => SchemaHelper::table(
        SchemaHelper::defaultScope(
            SchemaHelper::fields(
                SchemaHelper::primary('categoryId'),
                SchemaHelper::int('propertyId'),
                SchemaHelper::varchar('name', 255),
                SchemaHelper::varchar('module', 255),
                SchemaHelper::tinyint('allProperties'),
                SchemaHelper::int('status'),
                SchemaHelper::int('createdUserId'),                
                SchemaHelper::int('modifiedUserId'),
                SchemaHelper::datetime('modified'),
                SchemaHelper::datetime('created')
            ),
            SchemaHelper::indexes(
                SchemaHelper::index('PRIMARY','categoryId',true)
            )
        )
    )
);