<?php
return array(
    'router' => array(
        'routes' => array(
            'calendar-direct' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '[/:subsite]/calendar[-:langCode][_:controller][/:action[/:itemId]]',
                    'constraints' => array(
                        'controller'     => '[a-zA-Z][a-zA-Z0-9]*',
                        'itemId'     => '[0-9][0-9]*',
                    ),                    
                    'defaults' => array(
                        '__NAMESPACE__' => 'Calendar\Controller',
                        'controller' => 'Index',
                        'action' => 'index',
                        'keywords' => '',
                        'langCode' => '',
                    ),
                ),
            ),
			'calendar-sockets' => array(
				'type' => 'Segment',
				'options' => array(
					'route' => '[/:subsite]/sockets/calendar[/:action]', //[/:action[/:itemId]]',
					'constraints' => array(
						'controller'     => '[a-zA-Z][a-zA-Z0-9]*',
						'itemId'     => '[0-9][0-9]*',
					),
					'defaults' => array(
						'__NAMESPACE__' => 'Calendar\Controller',
						'controller' => 'Sockets',
						'action' => 'index',
						'keywords' => '',
						'langCode' => '',
						'subsite' => ''
					),
				),
			),            
            //Legacy style path. 
            'calendar-legacypath-toolbox' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '[/:subsite]/toolbox/tools/calendar[_:action[&item]][_:itemId][-:langCode].html',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Calendar\Controller',
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
            'calendar-toolbox' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '[/:subsite]/toolbox/tools/calendar[-:langCode][_:controller][/:action[/:itemId]]',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Calendar\Controller',
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
            'Calendar\Controller\Index' => 'Calendar\Controller\IndexController',
            'Calendar\Controller\Toolbox' => 'Calendar\Controller\ToolboxController',
            'Calendar\Controller\Sockets' => 'Calendar\Controller\SocketsController'
        ),
    ),
	'view_manager'=>array(
			'display_not_found_reason'=>true,
			'display_exception'=>true,
			'doctype'=>'HTML5',
			'not_found_template'=>'error/404',
			'exeception_template'=>'error/index',
			'template_map'=>array(
				//'layout/layout'=>__DIR__.'/../view/layout/layout.phtml',
				'calendar/layout/module'=>__DIR__.'/../view/layout/module.phtml',
				//'error/404'=>__DIR__.'/../view/error/404.phtml',
				//'error/index'=>__DIR__.'/../view/error/index.phtml'
		),
		'template_path_stack' => array(
            __DIR__ . '/../view',
            getcwd() . '/module/ListModule/view/list-module/toolbox',
        ),
	),
    'doctrine' => array(
        'driver' => array(
            //Configure the mapping driver for entities in Application module 
            'app_entities' => array(
                'class' => '\Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(realpath(__DIR__ . '/../src/Calendar/Entity'))
            ),
            /*'admin_entities' => array(
                'class' => '\Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(realpath(__DIR__ . '/../src/Calendar/Entity/Admin'))
            ),*/
            //Add configured driver to orm_default entity manager
            'orm_default' => array(
                'drivers' => array(
                     'Calendar\Entity' => 'app_entities'
                )
            ),
            /*'orm_admin' => array(
                'class'   => 'Doctrine\ORM\Mapping\Driver\DriverChain',                
                'drivers' => array(
                     'PhoenixReview\Entity\Admin' => 'admin_entities'
                )
            ),*/
        ),
    ), 
	
);