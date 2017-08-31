<?php

//return();

return array(
    'userPermissions' => array(
        'UserReviews' => array(
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
            'userReviews-fieldsToolbox' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '[/:subsite]/toolbox/tools/userReviews_fields[-:langCode]/:formId[/:action[/:itemId]]',
                    'constraints' => array(
                        'itemId'     => '[0-9][0-9]*',
                        'formId'   => '[0-9][0-9]*'
                    ),                    
                    'defaults' => array(
                        '__NAMESPACE__' => 'UserReviews\Controller',
                        'controller' => 'FieldsToolbox',
                        'action' => 'index',
                        'keywords' => '',
                        'langCode' => '',
                        'subsite' => '',
                    ),
                ),
            ),   
              
                
            'userReviews-direct' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '[/:subsite]/userReviews[-:langCode][_:controller][/:action[/:itemId]]',
                    'constraints' => array(
                        'controller'     => '[a-zA-Z][a-zA-Z0-9]*',
                        'itemId'     => '[0-9][0-9]*',
                    ),                    
                    'defaults' => array(
                        '__NAMESPACE__' => 'UserReviews\Controller',
                        'controller' => 'Index',
                        'action' => 'index',
                        'keywords' => '',
                        'langCode' => '',
                        'subsite' => '',
                    ),
                ),
            ),
            'userReviews-sockets' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '[/:subsite]/sockets/userReviews[/:action[/:itemId]]',
                    'constraints' => array(
                        'controller'     => '[a-zA-Z][a-zA-Z0-9]*',
                        'itemId'     => '[0-9][0-9]*',                      
                    ),                    
                    'defaults' => array(
                        '__NAMESPACE__' => 'UserReviews\Controller',
                        'controller' => 'Sockets',
                        'action' => 'index',
                        'keywords' => '',
                        'langCode' => '',
                        'subsite' => ''
                    ),
                ),
            ),            
            //Legacy style path. 
            'userReviews-legacypath-toolbox' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '[/:subsite]/toolbox/tools/userReviews[_:action[&item]][_:itemId][-:langCode].html',
                    'defaults' => array(
                        '__NAMESPACE__' => 'UserReviews\Controller',
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
            'userReviews-toolbox' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '[/:subsite]/toolbox/tools/userReviews[-:langCode][_:controller][/:action[/:itemId]]',
                    'defaults' => array(
                        '__NAMESPACE__' => 'UserReviews\Controller',
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
           /* 'userReviews-viewToolbox' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '[/:subsite]/toolbox/tools/userReview_view[/:action[/:itemId]]',
                    'constraints' => array(
                        'itemId'     => '[0-9][0-9]*',
                        'formId'   => '[0-9][0-9]*'
                    ),                    
                    'defaults' => array(
                        '__NAMESPACE__' => 'UserReviews\Controller',
                        'controller' => 'ViewToolbox',
                        'action' => 'index',
                        'keywords' => '',
                        'langCode' => '',
                        'subsite' => '',
                    ),
                ),
            ),   */         
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'UserReviews\Controller\Index' => 'UserReviews\Controller\IndexController',
            'UserReviews\Controller\Toolbox' => 'UserReviews\Controller\ToolboxController',
            'UserReviews\Controller\Sockets' => 'UserReviews\Controller\SocketsController',
            'UserReviews\Controller\FormToolbox' => 'UserReviews\Controller\FormToolboxController',
            //'UserReviews\Controller\ViewToolbox' => 'UserReviews\Controller\ViewToolboxController',
            //'UserReviews\Controller\FieldsToolbox' => 'UserReviews\Controller\FieldsToolboxController',
            //'UserReviews\Controller\ItemsToolbox' => 'UserReviews\Controller\ItemsToolboxController',
           
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
             'error/404'               => __DIR__ . '/../view/error/404.phtml',
            // 'error/index'             => __DIR__ . '/../view/error/index.phtml',
         ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
            getcwd() . '/module/ListModule/view/list-module/toolbox',
        ),
    ),
    'controller_plugins' => array(
        'invokables' => array(
            //'GetModuleRedirect' => 'UserReviews\Mvc\Controller\Plugin\GetModuleRedirect',
        )
    ),
    'doctrine' => array(
        'driver' => array(
            //Configure the mapping driver for entities in Application module 
            'app_entities' => array(
                'class' => '\Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(realpath(__DIR__ . '/../src/UserReviews/Entity'))
            ),
          'admin_entities' => array(
                'class' => '\Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(realpath(__DIR__ . '/../src/UserReviews/Entity/Admin'))
            ), 
            //Add configured driver to orm_default entity manager
            'orm_default' => array(
                'drivers' => array(
                     'UserReviews\Entity' => 'app_entities'
                )
            ),
          'orm_admin' => array(
                'class'   => 'Doctrine\ORM\Mapping\Driver\DriverChain',                
                'drivers' => array(
                     'UserReviews\Entity\Admin' => 'admin_entities'
                )
            ),
        ),
    ),        
);