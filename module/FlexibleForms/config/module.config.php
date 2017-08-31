<?php
return array(
    'userPermissions' => array(
        'FlexibleForms' => array(
            'Configure forms' => 'configureForms',
        )
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view'
        ),
    ),    
    'router' => array(
        'routes' => array(
            // 'flexibleForms-formToolbox' => array(
            //     'type' => '\FlexibleForms\Mvc\Router\Http\Segment',
            //     'options' => array(
            //         'route' => '[/:subsite]/toolbox/tools/:form[-:langCode][_:controller][/:action[/:itemId]]',
            //         'constraints' => array(
            //             'form' => '[a-zA-Z][a-zA-Z0-9\-_]*',
            //             'moduleController'     => '[a-zA-Z][a-zA-Z0-9]*',
            //             'itemId'     => '[0-9][0-9]*',
            //         ),                    
            //         'defaults' => array(
            //             '__NAMESPACE__' => 'FlexibleForms\Controller',
            //             'controller' => 'FormToolbox',
            //             'action' => 'index',
            //             'keywords' => '',
            //             'langCode' => '',
            //         ),
            //     ),
            // ),            
            'flexibleForms-fieldsToolbox' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '[/:subsite]/toolbox/tools/flexibleForms_fields[-:langCode]/:formId[/:action[/:itemId]]',
                    'constraints' => array(
                        'itemId'     => '[0-9][0-9]*',
                        'formId'   => '[0-9][0-9]*'
                    ),                    
                    'defaults' => array(
                        '__NAMESPACE__' => 'FlexibleForms\Controller',
                        'controller' => 'FieldsToolbox',
                        'action' => 'index',
                        'keywords' => '',
                        'langCode' => '',
                        'subsite' => '',
                    ),
                ),
            ),   
            
             'flexibleForms-itemsToolbox' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '[/:subsite]/toolbox/tools/flexibleForms_items[-:langCode]/:formId[/:action[/:itemId]]',
                    'constraints' => array(
                        'itemId'     => '[0-9][0-9]*',
                        'formId'   => '[0-9][0-9]*'
                    ),                    
                    'defaults' => array(
                        '__NAMESPACE__' => 'FlexibleForms\Controller',
                        'controller' => 'ItemsToolbox',
                        'action' => 'index',
                        'keywords' => '',
                        'langCode' => '',
                        'subsite' => '',
                    ),
                ),
            ), 
                
            'flexibleForms-direct' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '[/:subsite]/flexibleForms[-:langCode][_:controller][/:action[/:itemId]]',
                    'constraints' => array(
                        'controller'     => '[a-zA-Z][a-zA-Z0-9]*',
                        'itemId'     => '[0-9][0-9]*',
                    ),                    
                    'defaults' => array(
                        '__NAMESPACE__' => 'FlexibleForms\Controller',
                        'controller' => 'Index',
                        'action' => 'index',
                        'keywords' => '',
                        'langCode' => '',
                        'subsite' => '',
                    ),
                ),
            ),
            'flexibleForms-sockets' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '[/:subsite]/sockets/flexibleForms[/:action[/:itemId]]',
                    'constraints' => array(
                        'controller'     => '[a-zA-Z][a-zA-Z0-9]*',
                        'itemId'     => '[0-9][0-9]*',                      
                    ),                    
                    'defaults' => array(
                        '__NAMESPACE__' => 'FlexibleForms\Controller',
                        'controller' => 'Sockets',
                        'action' => 'index',
                        'keywords' => '',
                        'langCode' => '',
                        'subsite' => ''
                    ),
                ),
            ),            
            //Legacy style path. 
            'flexibleForms-legacypath-toolbox' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '[/:subsite]/toolbox/tools/flexibleForms[_:action[&item]][_:itemId][-:langCode].html',
                    'defaults' => array(
                        '__NAMESPACE__' => 'FlexibleForms\Controller',
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
            'flexibleForms-toolbox' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '[/:subsite]/toolbox/tools/flexibleForms[-:langCode][_:controller][/:action[/:itemId]]',
                    'defaults' => array(
                        '__NAMESPACE__' => 'FlexibleForms\Controller',
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
            'flexibleForms-viewToolbox' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '[/:subsite]/toolbox/tools/flexibleForms_view[/:action[/:itemId]]',
                    'constraints' => array(
                        'itemId'     => '[0-9][0-9]*',
                        'formId'   => '[0-9][0-9]*'
                    ),                    
                    'defaults' => array(
                        '__NAMESPACE__' => 'FlexibleForms\Controller',
                        'controller' => 'ViewToolbox',
                        'action' => 'index',
                        'keywords' => '',
                        'langCode' => '',
                        'subsite' => '',
                    ),
                ),
            ),            
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'FlexibleForms\Controller\Index' => 'FlexibleForms\Controller\IndexController',
            'FlexibleForms\Controller\Toolbox' => 'FlexibleForms\Controller\ToolboxController',
            'FlexibleForms\Controller\Sockets' => 'FlexibleForms\Controller\SocketsController',
            'FlexibleForms\Controller\FormToolbox' => 'FlexibleForms\Controller\FormToolboxController',
            'FlexibleForms\Controller\ViewToolbox' => 'FlexibleForms\Controller\ViewToolboxController',
            'FlexibleForms\Controller\FieldsToolbox' => 'FlexibleForms\Controller\FieldsToolboxController',
            'FlexibleForms\Controller\ItemsToolbox' => 'FlexibleForms\Controller\ItemsToolboxController',
           
        ),
    ),
    'controller_plugins' => array(
        'invokables' => array(
            //'GetModuleRedirect' => 'FlexibleForms\Mvc\Controller\Plugin\GetModuleRedirect',
        )
    ),
    'doctrine' => array(
        'driver' => array(
            //Configure the mapping driver for entities in Application module 
            'app_entities' => array(
                'class' => '\Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(realpath(__DIR__ . '/../src/FlexibleForms/Entity'))
            ),
            'admin_entities' => array(
                'class' => '\Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(realpath(__DIR__ . '/../src/FlexibleForms/Entity/Admin'))
            ),
            //Add configured driver to orm_default entity manager
            'orm_default' => array(
                'drivers' => array(
                     'FlexibleForms\Entity' => 'app_entities'
                )
            ),
            'orm_admin' => array(
                'class'   => 'Doctrine\ORM\Mapping\Driver\DriverChain',                
                'drivers' => array(
                     'FlexibleForms\Entity\Admin' => 'admin_entities'
                )
            ),
        ),
    ),        
);