<?php

return array(
    'name' => 'PhoenixEvents',
    'label' => 'Phoenix Events',
    'description' => 'List of Events',
    'fields' => array(
        'eventName' => array(
            'type' => 'text',
            'label' => 'Event Name',
            'translate' => true
        ),
        'eventDescription' => array(
            'type' => 'textarea',
            'label' => 'Event Description',
            'translate' => true
        ),
    )
);
