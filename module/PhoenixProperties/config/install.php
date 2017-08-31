<?php
return array(
    'name' => 'PhoenixProperties',
    'label' => 'Phoenix Properties',
    'description' => 'List of Phoenix Properties to be placed upon a map for a site.',
    'fields' => array(
         'name' => array(
            'type' => 'text',
            'label' => 'Name',
            'translate' => true
        ),
        'description' => array(
            'type' => 'textarea',
            'label' => 'Description',
            'translate' => true
        ),
        'history' => array(
            'type' => 'textarea',
            'label' => 'History',
            'translate' => true
        ),
        'policy' => array(
            'type' => 'textarea',
            'label' => 'Policy',
            'translate' => true
        ),
    )
);