<?php
return array(
    'name' => 'MapMarkers',
    'label' => 'Map Markers',
    'description' => 'List of markers to be placed upon a map for a site.',
    'fields' => array(
        'title' => array(
            'type' => 'text',
            'label' => 'Title',
            'translate' => true
        ),
        'description' => array(
            'type' => 'textarea',
            'label' => 'Content',
            'translate' => true
        )        
    )
);