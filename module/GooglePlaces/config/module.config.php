<?php
return array(
    'router' => array(
        'routes' => array(
/*
            'googlePlaces-direct' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '[/:subsite]/googlePlaces[-:langCode][_:controller][/:action[/:itemId]]',
                    'constraints' => array(
                        'controller'     => '[a-zA-Z][a-zA-Z0-9]*',
                        'itemId'     => '[0-9][0-9]*',
                    ),                    
                    'defaults' => array(
                        '__NAMESPACE__' => 'GooglePlaces\Controller',
                        'controller' => 'Index',
                        'action' => 'index',
                        'keywords' => '',
                        'langCode' => '',
                    ),
                ),
            ),
 * 
 */
            'googlePlaces-sockets' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '[/:subsite]/googlePlaces/sockets[/:action[/:itemId]]',
                    'constraints' => array(
                        'controller'     => '[a-zA-Z][a-zA-Z0-9]*',
                        'itemId'     => '[0-9][0-9]*',                      
                    ),                    
                    'defaults' => array(
                        '__NAMESPACE__' => 'GooglePlaces\Controller',
                        'controller' => 'Sockets',
                        'action' => 'index',
                        'keywords' => '',
                        'langCode' => '',
                        'subsite' => ''
                    ),
                ),
            ),            
            //Legacy style path. 
            'googlePlaces-legacypath-toolbox' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '[/:subsite]/toolbox/tools/googlePlaces[_:action[&item]][_:itemId][-:langCode].html',
                    'defaults' => array(
                        '__NAMESPACE__' => 'GooglePlaces\Controller',
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
            'googlePlaces-toolbox' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '[/:subsite]/toolbox/tools/googlePlaces[-:langCode][_:controller][/:action[/:itemId]]',
                    'defaults' => array(
                        '__NAMESPACE__' => 'GooglePlaces\Controller',
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
            'GooglePlaces\Controller\Index' => 'GooglePlaces\Controller\IndexController',
            'GooglePlaces\Controller\Toolbox' => 'GooglePlaces\Controller\ToolboxController',
            'GooglePlaces\Controller\Sockets' => 'GooglePlaces\Controller\SocketsController'
        ),
    ),    
    'view_manager' => array(
        'template_map' => array(
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
                'paths' => array(realpath(__DIR__ . '/../src/GooglePlaces/Entity'))
            ),
            'admin_entities' => array(
                'class' => '\Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(realpath(__DIR__ . '/../src/GooglePlaces/Entity/Admin'))
            ),
            //Add configured driver to orm_default entity manager
            'orm_default' => array(
                'drivers' => array(
                     'GooglePlaces\Entity' => 'app_entities'
                )
            ),
            'orm_admin' => array(
                'class'   => 'Doctrine\ORM\Mapping\Driver\DriverChain',                
                'drivers' => array(
                     'GooglePlaces\Entity\Admin' => 'admin_entities'
                )
            ),
        ),
    ),        
);