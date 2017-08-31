<?php
use ModuleMigrations\StdLib\SchemaHelper;

return array(
    'cache' => SchemaHelper::table(
        SchemaHelper::defaultScope(
            SchemaHelper::fields(
                SchemaHelper::primary('id'),
                SchemaHelper::varchar('device', 35),
                SchemaHelper::varchar('url'),
                SchemaHelper::longtext('content'),
                SchemaHelper::datetime('created'),
                SchemaHelper::tinyint('current'),
                SchemaHelper::varchar('langCode', 2)
            ),
            SchemaHelper::indexes(
                SchemaHelper::index('PRIMARY','id',true)
            )
        )
    )
);