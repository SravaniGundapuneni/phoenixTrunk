<?php
return array(
    'router' => array(
        'routes' => array(
            'linkRedirects-direct' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '[/:subsite]/linkRedirects[-:langCode][_:controller][/:action[/:itemId]]',
                    'constraints' => array(
                        'controller'     => '[a-zA-Z][a-zA-Z0-9]*',
                        'itemId'     => '[0-9][0-9]*',
                    ),                    
                    'defaults' => array(
                        '__NAMESPACE__' => 'LinkRedirects\Controller',
                        'controller' => 'Index',
                        'action' => 'index',
                        'keywords' => '',
                        'langCode' => '',
                    ),
                ),
            ),
            'linkRedirects-sockets' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '[/:subsite]/sockets/linkRedirects/:action',
                    'constraints' => array(
                        'controller'     => '[a-zA-Z][a-zA-Z0-9]*',
                        'itemId'     => '[0-9][0-9]*',
                    ),
                    'defaults' => array(
                        '__NAMESPACE__' => 'LinkRedirects\Controller',
                        'controller' => 'Sockets',
                        'action' => 'index',
                        'subsite' => ''
                    ),
                ),
            ),        
            //Legacy style path. 
            'linkRedirects-legacypath-toolbox' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '[/:subsite]/toolbox/tools/linkRedirects[_:action[&item]][_:itemId][-:langCode].html',
                    'defaults' => array(
                        '__NAMESPACE__' => 'LinkRedirects\Controller',
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
            'linkRedirects-toolbox' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '[/:subsite]/toolbox/tools/linkRedirects[-:langCode][_:controller][/:action[/:itemId]]',
                    'defaults' => array(
                        '__NAMESPACE__' => 'LinkRedirects\Controller',
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
            'LinkRedirects\Controller\Index' => 'LinkRedirects\Controller\IndexController',
            'LinkRedirects\Controller\Toolbox' => 'LinkRedirects\Controller\ToolboxController',
            'LinkRedirects\Controller\Sockets' => 'LinkRedirects\Controller\SocketsController'
        ),
    ),    
    'doctrine' => array(
        'driver' => array(
            //Configure the mapping driver for entities in Application module 
            'app_entities' => array(
                'class' => '\Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(realpath(__DIR__ . '/../src/LinkRedirects/Entity'))
            ),
            'admin_entities' => array(
                'class' => '\Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(realpath(__DIR__ . '/../src/LinkRedirects/Entity/Admin'))
            ),
            //Add configured driver to orm_default entity manager
            'orm_default' => array(
                'drivers' => array(
                     'LinkRedirects\Entity' => 'app_entities'
                )
            ),
            'orm_admin' => array(
                'class'   => 'Doctrine\ORM\Mapping\Driver\DriverChain',                
                'drivers' => array(
                     'LinkRedirects\Entity\Admin' => 'admin_entities'
                )
            ),
        ),
    ),
    'view_manager' => array (
       'template_path_stack' => array(
         __DIR__ . '/../view'
        ),
    ),         
);