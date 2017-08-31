<?php
$a=1;
return array(
    'router' => array(
        'routes' => array(
            'phoenixSocialToolbar-direct' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '[/:subsite]/phoenixSocialToolbar[-:langCode][_:controller][/:action[/:itemId]]',
                    'constraints' => array(
                        'controller'     => '[a-zA-Z][a-zA-Z0-9]*',
                        'itemId'     => '[0-9][0-9]*',
                    ),                    
                    'defaults' => array(
                        '__NAMESPACE__' => 'PhoenixSocialToolbar\Controller',
                        'controller' => 'Index',
                        'action' => 'index',
                        'keywords' => '',
                        'langCode' => '',
                    ),
                ),
            ),
            'phoenixSocialToolbar-sockets' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '[/:subsite]/phoenixSocialToolbar/sockets', //[/:action[/:itemId]]',
                    'constraints' => array(
                        'controller'     => '[a-zA-Z][a-zA-Z0-9]*',
                        'itemId'     => '[0-9][0-9]*',                      
                    ),                    
                    'defaults' => array(
                        '__NAMESPACE__' => 'PhoenixSocialToolbar\Controller',
                        'controller' => 'Sockets',
                        'action' => 'index',
                        'keywords' => '',
                        'langCode' => '',
                        'subsite' => ''
                    ),
                ),
            ),            
            //Legacy style path. 
            'phoenixSocialToolbar-legacypath-toolbox' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '[/:subsite]/toolbox/tools/phoenixSocialToolbar[_:action[&item]][_:itemId][-:langCode].html',
                    'defaults' => array(
                        '__NAMESPACE__' => 'PhoenixSocialToolbar\Controller',
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
            'phoenixSocialToolbar-toolbox' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '[/:subsite]/toolbox/tools/phoenixSocialToolbar[-:langCode][_:controller][/:action[/:itemId]]',
                    'defaults' => array(
                        '__NAMESPACE__' => 'PhoenixSocialToolbar\Controller',
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
            'PhoenixSocialToolbar\Controller\Index' => 'PhoenixSocialToolbar\Controller\IndexController',
            'PhoenixSocialToolbar\Controller\Toolbox' => 'PhoenixSocialToolbar\Controller\ToolboxController',
            'PhoenixSocialToolbar\Controller\Sockets' => 'PhoenixSocialToolbar\Controller\SocketsController'
        ),
    ),    
    'doctrine' => array(
        'driver' => array(
            //Configure the mapping driver for entities in Application module 
            'app_entities' => array(
                'class' => '\Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(realpath(__DIR__ . '/../src/PhoenixSocialToolbar/Entity'))
            ),
            'admin_entities' => array(
                'class' => '\Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(realpath(__DIR__ . '/../src/PhoenixSocialToolbar/Entity/Admin'))
            ),
            //Add configured driver to orm_default entity manager
            'orm_default' => array(
                'drivers' => array(
                     'PhoenixSocialToolbar\Entity' => 'app_entities'
                )
            ),
            'orm_admin' => array(
                'class'   => 'Doctrine\ORM\Mapping\Driver\DriverChain',                
                'drivers' => array(
                     'PhoenixSocialToolbar\Entity\Admin' => 'admin_entities'
                )
            ),
        ),
    ), 
    'phoenix-filters' => array(
        'social' => 'phoenix-filters-social',
        ),
    'view_helpers' => array(
        'invokables' => array(
            'socialToolbar' => 'PhoenixSocialToolbar\Helper\GetSocialToolbarElement',            

           )
     ),
    'view_manager' => array(
            'display_not_found_reason' => true,
            'display_exceptions'       => true,
            'doc_type'                 => 'HTML5',
            'not_found_template'       => 'error/404',
            'exception_template'       => 'error/index',
            'template_map'  => array(
                'social-toolbar-item'     => __DIR__ . '/../view/social-toolbar/layout/layout.phtml',
                ),
            'template_path_stack' => array(
            __DIR__ . '/../view'
        ),
    ),
);