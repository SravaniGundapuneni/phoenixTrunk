<?php
use ModuleMigrations\StdLib\SchemaHelper;

return array(
   
 'phoenixSiteComponents' => SchemaHelper::table(
        SchemaHelper::defaultScope(
            SchemaHelper::fields(
                SchemaHelper::primary('sitecomponentId'),
                SchemaHelper::text('name'),
                SchemaHelper::text('label'),
                SchemaHelper::reference('item', 'componentItems', 'itemId'),                 
                SchemaHelper::reference('componentFields', 'componentFields', 'componentFieldId'),
                      SchemaHelper::reference('component', 'components', 'componentId'),
                SchemaHelper::reference('property', 'phoenixProperties', 'propertyId'),
               
                // Last 4 columns (do not change)
                SchemaHelper::int('createdUserId'),                
                SchemaHelper::int('modifiedUserId'),
                SchemaHelper::datetime('created'),
                SchemaHelper::datetime('modified'),
                SchemaHelper::int('status'),
                     SchemaHelper::int('translationId')
            ),
            SchemaHelper::indexes(
                SchemaHelper::index('PRIMARY', 'sitecomponentId', true)
            ),
            SchemaHelper::InnoDB()
        )
    ),
    
);