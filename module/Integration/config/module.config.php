<?php
return array(
    'router' => array(
        'routes' => array(
        //     'integration-legacypath-toolbox' => array(
        //         'type' => 'Segment',
        //         'options' => array(
        //             'route' => '[/:subsite]/toolbox/tools/integration[_:action[&item]][_:itemId][-:langCode].html',
        //             'defaults' => array(
        //                 '__NAMESPACE__' => 'Integration\Controller',
        //                 'controller' => 'Toolbox',
        //                 'action' => 'index',
        //                 'keywords' => '',
        //                 'langCode' => '',
        //                 'subsite' => ''
        //             ),
        //             'constraints' => array(
        //                 'action' => '[a-zA-Z][a-zA-Z0-9]*',
        //                 'task'     => '[a-zA-Z][a-zA-Z0-9]*',
        //                 'itemId'     => '[0-9][0-9]*',
        //             ),
        //         ),
        //     ),
        //     'integration-toolbox' => array(
        //         'type' => 'Segment',
        //         'options' => array(
        //             'route' => '[/:subsite]/toolbox/tools/integration[-:langCode][_:controller][/:action[/:itemId]]',
        //             'defaults' => array(
        //                 '__NAMESPACE__' => 'Integration\Controller',
        //                 'controller' => 'Toolbox',
        //                 'action' => 'index',
        //                 'keywords' => '',
        //                 'langCode' => '',
        //                 'subsite' => ''
        //                 ),
        //             'constraints' => array(
        //                 'action' => '[a-zA-Z][a-zA-Z0-9]*',
        //                 'controller' => '[a-zA-Z][a-zA-Z0-9]*',
        //                 'langCode' => '[a-z][a-z]*',
        //                 'itemId'     => '[0-9][0-9]*',
        //             )
        //         ),
        //     ),
        ),
    ),
    'doctrine' => array(
        'driver' => array(
            //Configure the mapping driver for entities in Application module
            'app_entities' => array(
                'class' => '\Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(realpath(__DIR__ . '/../src/Integration/Entity'))
            ),
            'admin_entities' => array(
                'class' => '\Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(realpath(__DIR__ . '/../src/Integration/Entity/Admin'))
            ),
            //Add configured driver to orm_default entity manager
            'orm_default' => array(
                'drivers' => array(
                     'Integration\Entity' => 'app_entities'
                )
            ),
            'orm_admin' => array(
                'class'   => 'Doctrine\ORM\Mapping\Driver\DriverChain',
                'drivers' => array(
                     'Integration\Entity\Admin' => 'admin_entities'
                )
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Integration\Controller\Toolbox' => 'Integration\Controller\ToolboxController',
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
         ),
        'template_path_stack' => array(
            __DIR__ . '/../view'
        ),
    ),
);