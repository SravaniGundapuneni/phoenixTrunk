<?php
return array(
    'router' => array(
        'routes' => array(
            'languages-sockets' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '[/:subsite]/sockets/languages/:action',
                    'constraints' => array(
                        'controller'     => '[a-zA-Z][a-zA-Z0-9]*',
                        'itemId'     => '[0-9][0-9]*',
                    ),
                    'defaults' => array(
                        '__NAMESPACE__' => 'Languages\Controller',
                        'controller' => 'Sockets',
                        'action' => 'index',
                        'subsite' => ''
                    ),
                ),
            ),
            'languages-toolbox' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '[/:subsite]/toolbox/tools/languages[-:langCode][_:controller][/:action[/:itemId]]',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Languages\Controller',
                        'controller' => 'Toolbox',
                        'action' => 'index',
                        'keywords' => '',
                        'langCode' => '',
                        'subsite' => ''
                        ),
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9]*',
                        'controller' => '[a-zA-Z][a-zA-Z0-9]*',
                        'langCode' => '[a-z][a-z]*',
                        'itemId' => '[0-9][0-9]*',
                    )
                ),
            ),
            'languages-properties-toolbox' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '[/:subsite]/toolbox/tools/languages_properties[/:action[/:itemId]]',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Languages\Controller',
                        'controller' => 'PropertiesToolbox',
                        'action' => 'index',
                        'keywords' => '',
                        'langCode' => '',
                        'subsite' => ''
                        ),
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9]*',
                        'controller' => '[a-zA-Z][a-zA-Z0-9]*',
                        'langCode' => '[a-z][a-z]*',
                        'itemId' => '[0-9][0-9]*',
                    )
                ),
            ),            
        ),
    ),
    'doctrine' => array(
        'driver' => array(
            //Configure the mapping driver for entities in Application module
            'app_entities' => array(
                'class' => '\Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(realpath(__DIR__ . '/../src/Languages/Entity'))
            ),
            'admin_entities' => array(
                'class' => '\Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(realpath(__DIR__ . '/../src/Languages/Entity/Admin'))
            ),
            //Add configured driver to orm_default entity manager
            'orm_default' => array(
                'drivers' => array(
                     'Languages\Entity' => 'app_entities'
                )
            ),
            'orm_admin' => array(
                'class'   => 'Doctrine\ORM\Mapping\Driver\DriverChain',
                'drivers' => array(
                     'Languages\Entity\Admin' => 'admin_entities'
                )
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Languages\Controller\Sockets' => 'Languages\Controller\SocketsController',
            'Languages\Controller\Toolbox' => 'Languages\Controller\ToolboxController',
            'Languages\Controller\PropertiesToolbox' => 'Languages\Controller\PropertiesToolboxController',
        ),
    ),
    'view_helpers' => array(
        'invokables' => array(
            'polytextElement' => 'Languages\Helper\PolytextElement',
        )
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'polytext-element'     => __DIR__ . '/../view/phoenix-polytext/element.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view'
        ),
    ),
    'phoenix-filters' => array(
        'text' => 'phoenix-filters-text',
     ),
);