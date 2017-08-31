<?php
return array(
    'router' => array(
        'routes' => array(
            'phoenixProperties-direct' => array(
                'type' => 'Segment',
                'options' => array(
                    //'route'    => '[:subsite[/[[:keywords],][[[:controller]_[[:action]_[[[:itemId]-]]]]]]]',
                    'route' => '[/:subsite]/phoenixProperties[-:langCode][_:controller][/:action[/:itemId]]',
                    'constraints' => array(
                        'controller'     => '[a-zA-Z][a-zA-Z0-9]*',
                        'itemId'     => '[0-9][0-9]*',
                    ),
                    'defaults' => array(
                        '__NAMESPACE__' => 'PhoenixProperties\Controller',
                        'controller' => 'Index',
                        'action' => 'index',
                        'keywords' => '',
                        'langCode' => '',
                    ),
                ),
            ),
            'phoenixProperties-sockets' => array(
                'type' => 'Segment',
                'options' => array(
                    //'route'    => '[:subsite[/[[:keywords],][[[:controller]_[[:action]_[[[:itemId]-]]]]]]]',
                    'route' => '[/:subsite]/sockets/phoenixProperties[/:action]', //[/:action[/:itemId]]',
                    'constraints' => array(
                        'controller'     => '[a-zA-Z][a-zA-Z0-9]*',
                        'itemId'     => '[0-9][0-9]*',
                    ),
                    'defaults' => array(
                        '__NAMESPACE__' => 'PhoenixProperties\Controller',
                        'controller' => 'Sockets',
                        'action' => 'index',
                        'keywords' => '',
                        'langCode' => '',
                        'subsite' => ''
                    ),
                ),
            ),
            //Legacy style path.
            'phoenixProperties-legacypath-toolbox' => array(
                'type' => 'Segment',
                'options' => array(
                    //'route'    => '[:subsite[/[[:keywords],][[[:controller]_[[:action]_[[[:itemId]-]]]]]]]',
                    'route' => '[/:subsite]/toolbox/tools/phoenixProperties[_:action[&item]][_:itemId][-:langCode].html',
                    'defaults' => array(
                        '__NAMESPACE__' => 'PhoenixProperties\Controller',
                        'controller' => 'Toolbox',
                        'action' => 'index',
                        'keywords' => '',
                        'langCode' => '',
                        'subsite' => ''
                    ),
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9]*',
                        'task'     => '[a-zA-Z][a-zA-Z0-9]*',
                        'itemId'     => '[0-9][0-9]*',
                    ),
                ),
            ),
            'phoenixProperties-toolbox' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '[/:subsite]/toolbox/tools/phoenixProperties[-:langCode][_:controller][/:action[/:itemId]]',
                    'defaults' => array(
                        '__NAMESPACE__' => 'PhoenixProperties\Controller',
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
                        'itemId'     => '[0-9][0-9]*',
                    )
                ),
            ),
            'phoenixProperties-toolbox-user-properties' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '[/:subsite]/toolbox/tools/phoenixProperties[-:langCode]/userProperties[/:itemId]',
                    'defaults' => array(
                        '__NAMESPACE__' => 'PhoenixProperties\Controller',
                        'controller' => 'Toolbox',
                        'action' => 'userProperties',
                        'keywords' => '',
                        'langCode' => '',
                        'subsite' => ''
                        ),
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9]*',
                        'controller' => '[a-zA-Z][a-zA-Z0-9]*',
                        'langCode' => '[a-z][a-z]*',
                        'itemId'     => '[0-9][0-9]*',
                    )
                ),
            ),
        ),
    ),
    //This will tell Phoenix not to look for cxml for this controller, or for specific actions
    'cxmlIgnore' => array(
        'PhoenixProperties\Controller\Toolbox' => true,
        'PhoenixProperties\Controller\Sockets' => true
    ),
    'controllers' => array(
        'invokables' => array(
            'PhoenixProperties\Controller\Index' => 'PhoenixProperties\Controller\IndexController',
            'PhoenixProperties\Controller\Sockets' => 'PhoenixProperties\Controller\SocketsController',
            'PhoenixProperties\Controller\Toolbox' => 'PhoenixProperties\Controller\ToolboxController',
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
         'template_map' => array(
             //'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
             // 'users/layout/module'        => __DIR__ . '/../view/layout/module.phtml',
             // 'users/index'         => __DIR__ . '/../view/users/index/index.phtml'
             //'error/404'               => __DIR__ . '/../view/error/404.phtml',
             //'error/index'             => __DIR__ . '/../view/error/index.phtml',
         ),
        'template_path_stack' => array(
            __DIR__ . '/../view'
        ),
    ),
    'doctrine' => array(
        'driver' => array(
            //Configure the mapping driver for entities in Application module
            'app_entities' => array(
                'class' => '\Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(realpath(__DIR__ . '/../src/PhoenixProperties/Entity'))
            ),
            'admin_entities' => array(
                'class' => '\Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(realpath(__DIR__ . '/../src/PhoenixProperties/Entity/Admin'))
            ),
            //Add configured driver to orm_default entity manager
            'orm_default' => array(
                'drivers' => array(
                     'PhoenixProperties\Entity' => 'app_entities'
                )
            ),
            'orm_admin' => array(
                'class'   => 'Doctrine\ORM\Mapping\Driver\DriverChain',
                'drivers' => array(
                     'PhoenixProperties\Entity\Admin' => 'admin_entities'
                )
            ),
        ),
    ),
    'media-manager-sizes' => array(
        'phoenixProperties' => array(
            'MarkerInfo',
            'Submedium',
            'FeaturedSpecialsListView',
            'confirmationPage' 
        ),
    ),       
);