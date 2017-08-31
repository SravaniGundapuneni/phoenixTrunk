<?php
return array(
    'name' => 'Calendar',
    'label' => 'Calendar',
    'description' => 'Display events on a calendar.',
    'fields' => array(
         'title' => array(
            'type' => 'text',
            'label' => 'Title',
            'translate' => true
        ),  
        'description' => array(
            'type' => 'textarea',
            'label' => 'Description',
            'translate' => true
        ), 
        'highlights' => array(
            'type' => 'textarea',
            'label' => 'Highlights',
            'translate' => true
        ),  
        
    ),
    
);