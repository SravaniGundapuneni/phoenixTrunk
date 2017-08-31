<?php
return array(
    'router' => array(
        'routes' => array(
            'pages-page' => array(
                'type' => 'Pages\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/[:subsite/][[:keywords],][:page][.html]',
                    'constraints' => array(
                        'page' => '[a-zA-Z][a-zA-Z0-9\-_]*',
                    ),
                    'defaults' => array(
                        '__NAMESPACE__' => 'Pages\Controller',
                        'controller' => 'Index',
                        'action' => 'index',
                        'page' => 'default',
                        'keywords' => '',
                        'langCode' => '',
                        'subsite' => '',
                    ),
                ),
            ),
            'pages-pagedirect' => array(
                'type' => 'Pages\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/[:page]/',
                    'constraints' => array(
                        'page' => '[a-zA-Z][a-zA-Z0-9\-_\/]*',
                    ),
                    'defaults' => array(
                        '__NAMESPACE__' => 'Pages\Controller',
                        'controller' => 'Index',
                        'action' => 'index',
                        'page' => 'default',
                        'keywords' => '',
                        'langCode' => '',
                        'subsite' => '',
                    ),
                ),
            ),                        
            'pages-sockets' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '[/:subsite]/sockets/pages/:action',
                    'constraints' => array(
                        'controller'     => '[a-zA-Z][a-zA-Z0-9]*',
                        'itemId'     => '[0-9][0-9]*',                      
                    ),                    
                    'defaults' => array(
                        '__NAMESPACE__' => 'Pages\Controller',
                        'controller' => 'Sockets',
                        'action' => 'edit-list',
                        'keywords' => '',
                        'langCode' => '',
                        'subsite' => ''
                    ),
                ),
            ),         
            //Legacy style path. 
            'pages-legacypath-toolbox' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '[/:subsite]/toolbox/tools/pages[_:action[&item]][_:itemId][-:langCode].html',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Pages\Controller',
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
            'pages-toolbox' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '[/:subsite]/toolbox/tools/pages[-:langCode][_:controller][/:action[/:itemId]]',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Pages\Controller',
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
            'Pages\Controller\Index' => 'Pages\Controller\IndexController',
            'Pages\Controller\Toolbox' => 'Pages\Controller\ToolboxController',
            'Pages\Controller\Sockets' => 'Pages\Controller\SocketsController'
        ),
    ),
    'view_manager' => array (
       'display_not_found_reason' => true,
       'display_execeptions'      => true,
       'doctype'                  => 'HTML5',
       'not_found_template'       => 'error/404',
       'exception_template'       => 'error/index',
       'template_map' => array(
          //to be filled in later.. 
          ),
       'template_path_stack' => array(
         __DIR__ . '/../view',
         __DIR__ . '/../view/pages/index',
         __DIR__ . '/../view/pages/templates',
        ),
    ),    
    'doctrine' => array(
        'driver' => array(
            //Configure the mapping driver for entities in Application module 
            'app_entities' => array(
                'class' => '\Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(realpath(__DIR__ . '/../src/Pages/Entity'))
            ),
            'admin_entities' => array(
                'class' => '\Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(realpath(__DIR__ . '/../src/Pages/Entity/Admin'))
            ),
            //Add configured driver to orm_default entity manager
            'orm_default' => array(
                'drivers' => array(
                     'Pages\Entity' => 'app_entities'
                )
            ),
            'orm_admin' => array(
                'class'   => 'Doctrine\ORM\Mapping\Driver\DriverChain',                
                'drivers' => array(
                     'Pages\Entity\Admin' => 'admin_entities'
                )
            ),
        ),
    ),        
);