<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'DevTools\Controller\Create'      => 'DevTools\Controller\CreateController',
        ),
    ),

    'console' => array(
        'router' => array(
            'routes' => array(
                'devtools-create-module' => array(
                    'options' => array(
                        'route'    => 'create module <name> [<path>]',
                        'defaults' => array(
                            'controller' => 'DevTools\Controller\Create',
                            'action'     => 'module',
                        ),
                    ),
                ),
            ),
        ),
    ),
);
