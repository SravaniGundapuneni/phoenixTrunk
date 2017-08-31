<?php

return array(
    'cache-type' => 'phoenix',
    'router' => array(
        'routes' => array(
             'pageCache-toolbox' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '[/:subsite]/toolbox/tools/pageCache[-:langCode][_:controller][/:action[/:itemId]]',
                    'defaults' => array(
                        '__NAMESPACE__' => 'PageCache\Controller',
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
            'pageCache-sockets' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '[/:subsite]/sockets/pageCache/:action',
                    'constraints' => array(
                        'controller'     => '[a-zA-Z][a-zA-Z0-9]*',
                        'itemId'     => '[0-9][0-9]*',                      
                    ),                    
                    'defaults' => array(
                        '__NAMESPACE__' => 'PageCache\Controller',
                        'controller' => 'Sockets',
                        'action' => 'edit-list',
                        'keywords' => '',
                        'langCode' => '',
                        'subsite' => ''
                    ),
                ),
            ),                         
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'PageCache\Controller\Sockets' => 'PageCache\Controller\SocketsController',
            'PageCache\Controller\Toolbox' => 'PageCache\Controller\ToolboxController',
        ),
    ), 
    'view_manager' => array (
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
                'paths' => array(realpath(__DIR__ . '/../src/PageCache/Entity'))
            ),
            'admin_entities' => array(
                'class' => '\Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(realpath(__DIR__ . '/../src/PageCache/Entity/Admin'))
            ),
            //Add configured driver to orm_default entity manager
            'orm_default' => array(
                'drivers' => array(
                    'PageCache\Entity' => 'app_entities'
                )
            ),
            'orm_admin' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\DriverChain',
                'drivers' => array(
                    'PageCache\Entity\Admin' => 'admin_entities'
                )
            ),
        ),
    ),
);
