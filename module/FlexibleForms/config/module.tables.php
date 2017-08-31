<?php
use ModuleMigrations\StdLib\SchemaHelper;

return array(
    'flexibleForm' => SchemaHelper::table(
        SchemaHelper::defaultScope(
            SchemaHelper::fields(
                SchemaHelper::primary('formId'),
                SchemaHelper::varchar('name', 255),
                SchemaHelper::varchar('label', 255),
                SchemaHelper::text('description'),
                SchemaHelper::int('createdUserId'),                
                SchemaHelper::int('modifiedUserId'),
                SchemaHelper::datetime('modified'),
                SchemaHelper::datetime('created'),
                SchemaHelper::int('status'),
                     SchemaHelper::int('emailEnabled')
            ),
            SchemaHelper::indexes(
                SchemaHelper::index('PRIMARY','formId',true)
            ),
            SchemaHelper::InnoDB()            
        )
    ),
    'flexibleForms_fields' => SchemaHelper::table(
        SchemaHelper::defaultScope(
            SchemaHelper::fields(
                SchemaHelper::primary('fieldId'),
                SchemaHelper::reference('form', 'flexibleForm', 'formId'),
                SchemaHelper::varchar('name', 255),
                SchemaHelper::varchar('displayName', 255),
                SchemaHelper::varchar('type', 255),
                SchemaHelper::int('showInList'),
                SchemaHelper::int('orderNumber'),
                // Last 4 coulumns (do not change)
                SchemaHelper::int('createdUserId'),                
                SchemaHelper::int('modifiedUserId'),
                SchemaHelper::datetime('created'),
                SchemaHelper::datetime('modified'),
                SchemaHelper::int('status')
            ),
            SchemaHelper::indexes(
                SchemaHelper::index('PRIMARY','fieldId',true)
            ),
            SchemaHelper::InnoDB()            
        )
    ),
    'flexibleForms_items' => SchemaHelper::table(
        SchemaHelper::defaultScope(
            SchemaHelper::fields(
                SchemaHelper::primary('itemId'),
                SchemaHelper::reference('form', 'flexibleForm', 'formId'),
                // Last 4 columns (do not change)
                SchemaHelper::int('createdUserId'),                
                SchemaHelper::int('modifiedUserId'),
                SchemaHelper::datetime('created'),
                SchemaHelper::datetime('modified'),
                SchemaHelper::int('status')
            ),
            SchemaHelper::indexes(
                SchemaHelper::index('PRIMARY', 'itemId', true)
            ),
            SchemaHelper::InnoDB()
        )
    ),
    'flexibleForms_itemFields' => SchemaHelper::table(
        SchemaHelper::defaultScope(
            SchemaHelper::fields(
                SchemaHelper::primary('ifId'),
                SchemaHelper::reference('item', 'flexibleForms_items', 'itemId'),
                SchemaHelper::reference('field', 'flexibleForms_fields', 'fieldId'),
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
    'flexibleForms_depEmails' => SchemaHelper::table(
        SchemaHelper::defaultScope(
            SchemaHelper::fields(
                SchemaHelper::primary('emailId'),
                SchemaHelper::text('departmentEmail'),
                SchemaHelper::int('property'),
                SchemaHelper::int('departmentId'),
                SchemaHelper::int('status')
            ),
            SchemaHelper::indexes(
                SchemaHelper::index('PRIMARY', 'emailId', true)
            ),
            SchemaHelper::InnoDB()
        )
    ), 
     'flexibleForms_attachments' => SchemaHelper::table(
        SchemaHelper::defaultScope(
            SchemaHelper::fields(
                SchemaHelper::primary('attachmentId'),
                SchemaHelper::text('attachmentName'),
                SchemaHelper::int('fieldId')
            ),
            SchemaHelper::indexes(
                SchemaHelper::index('PRIMARY', 'attachmentId', true)
            ),
            SchemaHelper::InnoDB()
        )
    ), 
);