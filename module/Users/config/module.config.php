<?php
return array(
    'router' => array(
        'routes' => array(
            'users-direct' => array(
                'type' => 'Segment',
                'options' => array(
                    //'route'    => '[:subsite[/[[:keywords],][[[:controller]_[[:action]_[[[:itemId]-]]]]]]]',
                    'route' => '[/:subsite]/users[-:langCode][_:controller][/:action[/:itemId]]',
                    'constraints' => array(
                        'controller'     => '[a-zA-Z][a-zA-Z0-9]*',
                        'itemId'     => '[0-9][0-9]*',
                    ),
                    'defaults' => array(
                        '__NAMESPACE__' => 'Users\Controller',
                        'controller' => 'Index',
                        'action' => 'index',
                        'keywords' => '',
                        'langCode' => '',
                    ),
                ),
            ),
            'users-sockets' => array(
                'type' => 'Segment',
                'options' => array(
                    //'route'    => '[:subsite[/[[:keywords],][[[:controller]_[[:action]_[[[:itemId]-]]]]]]]',
                    'route' => '[/:subsite]/users/sockets', //[/:action[/:itemId]]',
                    'constraints' => array(
                        'controller'     => '[a-zA-Z][a-zA-Z0-9]*',
                        'itemId'     => '[0-9][0-9]*',
                    ),
                    'defaults' => array(
                        '__NAMESPACE__' => 'Users\Controller',
                        'controller' => 'Sockets',
                        'action' => 'index',
                        'keywords' => '',
                        'langCode' => '',
                        'subsite' => ''
                    ),
                ),
            ),
            //Legacy style path.
            'users-legacypath-toolbox' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '[/:subsite]/toolbox/tools/users[_:action][_:itemId][-:langCode].html',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Users\Controller',
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
            //Legacy style path.
            'users-groups-legacypath-toolbox' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '[/:subsite]/toolbox/tools/users-groups[_:action][_:itemId][-:langCode].html',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Users\Controller',
                        'controller' => 'Groups',
                        'action' => 'index',
                        'keywords' => '',
                        'langCode' => '',
                        'subsite' => '',
                    ),
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9]*',
                        'task'     => '[a-zA-Z][a-zA-Z0-9]*',
                        'itemId'     => '[0-9][0-9]*',
                    ),
                ),
            ),
            'users-groups-toolbox' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '[/:subsite]/toolbox/tools/users_groups[/:action[/:itemId]]',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Users\Controller',
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
            'users-toolbox' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '[/:subsite]/toolbox/tools/users[-:langCode][_:controller][/:action[/:itemId]]',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Users\Controller',
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
        ),
    ),
    //This will tell Phoenix not to look for cxml for this controller, or for specific actions
    'cxmlIgnore' => array(
        'Users\Controller\Groups' => true,
        'Users\Controller\Permissions' => true
    ),
    'doctrine' => array(
        'driver' => array(
            //Configure the mapping driver for entities in Application module
            'app_entities' => array(
                'class' => '\Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(realpath(__DIR__ . '/../src/Users/Entity'))
            ),
            'admin_entities' => array(
                'class' => '\Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(realpath(__DIR__ . '/../src/Users/Entity/Admin'))
            ),
            //Add configured driver to orm_default entity manager
            'orm_default' => array(
                'drivers' => array(
                     'Users\Entity' => 'app_entities'
                )
            ),
            'orm_admin' => array(
                'class'   => 'Doctrine\ORM\Mapping\Driver\DriverChain',
                'drivers' => array(
                     'Users\Entity\Admin' => 'admin_entities'
                )
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Users\Controller\Index' => 'Users\Controller\IndexController',
            'Users\Controller\Toolbox' => 'Users\Controller\ToolboxController',
            'Users\Controller\Groups' => 'Users\Controller\GroupsController',
            'Users\Controller\Sockets' => 'Users\Controller\SocketsController'
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
             'users/layout/module'        => __DIR__ . '/../view/layout/module.phtml',
             //'error/404'               => __DIR__ . '/../view/error/404.phtml',
             //'error/index'             => __DIR__ . '/../view/error/index.phtml',
         ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
            getcwd() . '/module/ListModule/view/list-module/toolbox',
        ),
    ),
    'session' => array(
        'sessionExpire' => 1086400,
    ),
    'userPermissions' => array(
        'Users' => array(
            'Manage Site Users' => 'siteUsers',
            'Manage Global Users' => 'globalUsers'
        )
    )    
);