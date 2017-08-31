<?php
use ModuleMigrations\StdLib\SchemaHelper;

return array(
    'blocks' => SchemaHelper::table(
        SchemaHelper::defaultScope(
            SchemaHelper::fields(
                SchemaHelper::primary('id'),
                SchemaHelper::varchar('name', 100),
                SchemaHelper::int('page'),
                SchemaHelper::text('content'),

                SchemaHelper::int('createdUserId'),                
                SchemaHelper::int('modifiedUserId'),
                SchemaHelper::datetime('created'),
                SchemaHelper::datetime('modified'),
                SchemaHelper::int('status')
            ),
            SchemaHelper::indexes(
                SchemaHelper::index('PRIMARY','id',true)
            )
        )
    )
);