<?php
return array(
    'name' => 'SeoMetaText',
    'label' => 'SeoMetaText',
    'description' => 'List of SeoMetaText to be placed upon a map for a site.',
    'fields' => array(
        'metaH1' => array(
            'type' => 'text',
            'label' => 'Meta H1',
            'translate' => true
        ),
        'metaTitle' => array(
            'type' => 'text',
            'label' => 'Meta Title',
            'translate' => true
        ),
          'metaDescription' => array(
            'type' => 'textarea',
            'label' => 'Meta Description',
            'translate' => true
        ),
                
    )
);