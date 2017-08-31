<?php
return array(
    'router' => array(
        'routes' => array(
            'phoenixAttributes-direct' => array(
                'type' => 'Segment',
                'options' => array(
                    //'route'    => '[:subsite[/[[:keywords],][[[:controller]_[[:action]_[[[:itemId]-]]]]]]]',
                    'route' => '[/:subsite]/phoenixAttributes[-:langCode][_:controller][/:action[/:itemId]]',
                    'constraints' => array(
                        'controller'     => '[a-zA-Z][a-zA-Z0-9]*',
                        'itemId'     => '[0-9][0-9]*',
                    ),                    
                    'defaults' => array(
                        '__NAMESPACE__' => 'PhoenixAttributes\Controller',
                        'controller' => 'Index',
                        'action' => 'index',
                        'keywords' => '',
                        'langCode' => '',
                    ),
                ),
            ),
            'phoenixAttributes-sockets' => array(
                'type' => 'Segment',
                'options' => array(
                    //'route'    => '[:subsite[/[[:keywords],][[[:controller]_[[:action]_[[[:itemId]-]]]]]]]',
                    'route' => '[/:subsite]/sockets/phoenixAttributes/:action', //[/:action[/:itemId]]',
                    'constraints' => array(
                        'controller'     => '[a-zA-Z][a-zA-Z0-9]*',
                        'itemId'     => '[0-9][0-9]*',                      
                    ),                    
                    'defaults' => array(
                        '__NAMESPACE__' => 'PhoenixAttributes\Controller',
                        'controller' => 'Sockets',
                        'action' => 'index',
                        'keywords' => '',
                        'langCode' => '',
                        'subsite' => ''
                    ),
                ),
            ),
            //Legacy style path. 
            'phoenixAttributes-legacypath-toolbox' => array(
                'type' => 'Segment',
                'options' => array(
                    //'route'    => '[:subsite[/[[:keywords],][[[:controller]_[[:action]_[[[:itemId]-]]]]]]]',
                    'route' => '[/:subsite]/toolbox/tools/phoenixAttributes[_:action[&item]][_:itemId][-:langCode].html',
                    'defaults' => array(
                        '__NAMESPACE__' => 'PhoenixAttributes\Controller',
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
            'phoenixAttributes-toolbox' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '[/:subsite]/toolbox/tools/phoenixAttributes[-:langCode][_:controller][/:action[/:itemId]]',
                    'defaults' => array(
                        '__NAMESPACE__' => 'PhoenixAttributes\Controller',
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
        'PhoenixAttributes\Controller\Toolbox' => true,
        'PhoenixAttributes\Controller\Sockets' => true
    ),
    'controllers' => array(
        'invokables' => array(
            'PhoenixAttributes\Controller\Index' => 'PhoenixAttributes\Controller\IndexController',
            'PhoenixAttributes\Controller\Sockets' => 'PhoenixAttributes\Controller\SocketsController',
            'PhoenixAttributes\Controller\Toolbox' => 'PhoenixAttributes\Controller\ToolboxController',
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
             //'users/layout/module'        => __DIR__ . '/../view/layout/module.phtml',
             //'users/index'         => __DIR__ . '/../view/users/index/index.phtml'
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
                'paths' => array(realpath(__DIR__ . '/../src/PhoenixAttributes/Entity'))
            ),
            'admin_entities' => array(
                'class' => '\Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(realpath(__DIR__ . '/../src/PhoenixAttributes/Entity/Admin'))
            ),
            //Add configured driver to orm_default entity manager
            'orm_default' => array(
                'drivers' => array(
                     'PhoenixAttributes\Entity' => 'app_entities'
                )
            ),
            'orm_admin' => array(
                'class'   => 'Doctrine\ORM\Mapping\Driver\DriverChain',                
                'drivers' => array(
                     'PhoenixAttributes\Entity\Admin' => 'admin_entities'
                )
            ),
        ),
    ),
);