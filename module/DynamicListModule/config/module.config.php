<?php
return array(
    'userPermissions' => array(
        'DynamicListModule' => array(
            'Configure modules' => 'configureModules',
        )
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view'
        ),
    ),    
    'router' => array(
        'routes' => array(
            'dynamicListModule-moduleToolbox' => array(
                'type' => '\DynamicListModule\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '[/:subsite]/toolbox/tools/:module[-:langCode][_:controller][/:action[/:itemId]]',
                    'constraints' => array(
                        'module' => '[a-zA-Z][a-zA-Z0-9_-]+',
                        'moduleController'     => '[a-zA-Z][a-zA-Z0-9]*',
                        'itemId'     => '[0-9][0-9]*',
                    ),                    
                    'defaults' => array(
                        '__NAMESPACE__' => 'DynamicListModule\Controller',
                        'controller' => 'ModuleToolbox',
                        'action' => 'index',
                        'keywords' => '',
                        'langCode' => '',
                        'subsite' => ''
                    ),
                ),
            ),
            'dynamicListModule-fieldsToolbox' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '[/:subsite]/toolbox/tools/dynamicListModule_fields[-:langCode]/:moduleId[/:action[/:itemId]]',
                    'constraints' => array(
                        'itemId'     => '[0-9][0-9]*',
                        'moduleId'   => '[0-9][0-9]*'
                    ),                    
                    'defaults' => array(
                        '__NAMESPACE__' => 'DynamicListModule\Controller',
                        'controller' => 'FieldsToolbox',
                        'action' => 'index',
                        'keywords' => '',
                        'langCode' => '',
                    ),
                ),
            ),                 
            'dynamicListModule-direct' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '[/:subsite]/dynamicListModule[-:langCode][_:controller][/:action[/:itemId]]',
                    'constraints' => array(
                        'controller'     => '[a-zA-Z][a-zA-Z0-9]*',
                        'itemId'     => '[0-9][0-9]*',
                    ),                    
                    'defaults' => array(
                        '__NAMESPACE__' => 'DynamicListModule\Controller',
                        'controller' => 'Index',
                        'action' => 'index',
                        'keywords' => '',
                        'langCode' => '',
                    ),
                ),
            ),
            'dynamicListModule-sockets' => array(
                'type' => '\DynamicListModule\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '[/:subsite]/sockets/:module[/:action[/:itemId]]',
                    'constraints' => array(
                        'controller'     => '[a-zA-Z][a-zA-Z0-9]*',
                        'itemId'     => '[0-9][0-9]*',                      
                    ),                    
                    'defaults' => array(
                        '__NAMESPACE__' => 'DynamicListModule\Controller',
                        'controller' => 'Sockets',
                        'action' => 'index',
                        'keywords' => '',
                        'langCode' => '',
                        'subsite' => ''
                    ),
                ),
            ),  
            'dynamicListModule-admin-sockets' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '[/:subsite]/sockets/dynamicListModule[/:action[/:itemId]]',
                    'constraints' => array(
                        'controller'     => '[a-zA-Z][a-zA-Z0-9]*',
                        'itemId'     => '[0-9][0-9]*',                      
                    ),                    
                    'defaults' => array(
                        '__NAMESPACE__' => 'DynamicListModule\Controller',
                        'controller' => 'Sockets',
                        'action' => 'index',
                        'keywords' => '',
                        'langCode' => '',
                        'subsite' => ''
                    ),
                ),
            ),                        
            //Legacy style path. 
            'dynamicListModule-legacypath-toolbox' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '[/:subsite]/toolbox/tools/dynamicListModule[_:action[&item]][_:itemId][-:langCode].html',
                    'defaults' => array(
                        '__NAMESPACE__' => 'DynamicListModule\Controller',
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
            'dynamicListModule-toolbox' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '[/:subsite]/toolbox/tools/dynamicListModule[-:langCode][_:controller][/:action[/:itemId]]',
                    'defaults' => array(
                        '__NAMESPACE__' => 'DynamicListModule\Controller',
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
            'DynamicListModule\Controller\Index' => 'DynamicListModule\Controller\IndexController',
            'DynamicListModule\Controller\Toolbox' => 'DynamicListModule\Controller\ToolboxController',
            'DynamicListModule\Controller\Sockets' => 'DynamicListModule\Controller\SocketsController',
            'DynamicListModule\Controller\ModuleToolbox' => 'DynamicListModule\Controller\ModuleToolboxController',
            'DynamicListModule\Controller\FieldsToolbox' => 'DynamicListModule\Controller\FieldsToolboxController'            
        ),
    ),
    'controller_plugins' => array(
        'invokables' => array(
            'GetModuleRedirect' => 'DynamicListModule\Mvc\Controller\Plugin\GetModuleRedirect',
        )
    ),
    'doctrine' => array(
        'driver' => array(
            //Configure the mapping driver for entities in Application module 
            'app_entities' => array(
                'class' => '\Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(realpath(__DIR__ . '/../src/DynamicListModule/Entity'))
            ),
            'admin_entities' => array(
                'class' => '\Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(realpath(__DIR__ . '/../src/DynamicListModule/Entity/Admin'))
            ),
            //Add configured driver to orm_default entity manager
            'orm_default' => array(
                'drivers' => array(
                     'DynamicListModule\Entity' => 'app_entities'
                )
            ),
            'orm_admin' => array(
                'class'   => 'Doctrine\ORM\Mapping\Driver\DriverChain',                
                'drivers' => array(
                     'DynamicListModule\Entity\Admin' => 'admin_entities'
                )
            ),
        ),
    ),
    'media-manager-sizes' => array(
        'dynamicListModule' => array(
            'Submedium',
            'FeaturedSpecialsListView',
            'FeaturedSpecialsMini',
            'FeaturedSpecialsLarge',
            'FeaturedSpecials'
        ),
    ),       
);