<?php
return array(
    'router' => array(
        'routes' => array(
            'phoenixEvents-direct' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '[/:subsite]/phoenixEvents[-:langCode][_:controller][/:action[/:itemId]]',
                    'constraints' => array(
                        'controller'     => '[a-zA-Z][a-zA-Z0-9]*',
                        'itemId'     => '[0-9][0-9]*',
                    ),                    
                    'defaults' => array(
                        '__NAMESPACE__' => 'PhoenixEvents\Controller',
                        'controller' => 'Index',
                        'action' => 'index',
                        'keywords' => '',
                        'langCode' => '',
                    ),
                ),
            ),
            'phoenixEvents-sockets' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '[/:subsite]/sockets/phoenixEvents[/:action]', //[/:action[/:itemId]]',
                    'constraints' => array(
                        'controller'     => '[a-zA-Z][a-zA-Z0-9]*',
                        'itemId'     => '[0-9][0-9]*',                      
                    ),                    
                    'defaults' => array(
                        '__NAMESPACE__' => 'PhoenixEvents\Controller',
                        'controller' => 'Sockets',
                        'action' => 'index',
                        'keywords' => '',
                        'langCode' => '',
                        'subsite' => ''
                    ),
                ),
            ),            
            //Legacy style path. 
            'phoenixEvents-legacypath-toolbox' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '[/:subsite]/toolbox/tools/phoenixEvents[_:action[&item]][_:itemId][-:langCode].html',
                    'defaults' => array(
                        '__NAMESPACE__' => 'PhoenixEvents\Controller',
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
            'phoenixEvents-toolbox' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '[/:subsite]/toolbox/tools/phoenixEvents[-:langCode][_:controller][/:action[/:itemId]]',
                    'defaults' => array(
                        '__NAMESPACE__' => 'PhoenixEvents\Controller',
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
    'controllers' => array(
        'invokables' => array(
            'PhoenixEvents\Controller\Index' => 'PhoenixEvents\Controller\IndexController',
            'PhoenixEvents\Controller\Toolbox' => 'PhoenixEvents\Controller\ToolboxController',
            'PhoenixEvents\Controller\Sockets' => 'PhoenixEvents\Controller\SocketsController'
        ),
    ),    
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
         'template_map' => array(
             'event-item'     => __DIR__ . '/../view/phoenix-events/layout/layout.phtml',
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
                'paths' => array(realpath(__DIR__ . '/../src/PhoenixEvents/Entity'))
            ),
            'admin_entities' => array(
                'class' => '\Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(realpath(__DIR__ . '/../src/PhoenixEvents/Entity/Admin'))
            ),
            //Add configured driver to orm_default entity manager
            'orm_default' => array(
                'drivers' => array(
                     'PhoenixEvents\Entity' => 'app_entities'
                )
            ),
            'orm_admin' => array(
                'class'   => 'Doctrine\ORM\Mapping\Driver\DriverChain',                
                'drivers' => array(
                     'PhoenixEvents\Entity\Admin' => 'admin_entities'
                )
            ),
        ),
    ),
    'view_helpers' => array(
        'invokables' => array(
            'getEventInformation' => 'PhoenixEvents\Helper\GetEventElement',
        )
    ),        
);