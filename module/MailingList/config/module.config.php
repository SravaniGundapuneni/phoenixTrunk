<?php
return array(
    'router' => array(
        'routes' => array(
            'mailingList-direct' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '[/:subsite]/mailingList[-:langCode][_:controller][/:action[/:itemId]]',
                    'constraints' => array(
                        'controller'     => '[a-zA-Z][a-zA-Z0-9]*',
                        'itemId'     => '[0-9][0-9]*',
                    ),                    
                    'defaults' => array(
                        '__NAMESPACE__' => 'MailingList\Controller',
                        'controller' => 'Index',
                        'action' => 'index',
                        'keywords' => '',
                        'langCode' => '',
                    ),
                ),
            ),
            'mailingList-sockets' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '[/:subsite]/sockets/mailingList[/:action[/:itemId]]',
                    'constraints' => array(
                        'controller'     => '[a-zA-Z][a-zA-Z0-9]*',
                        'itemId'     => '[0-9][0-9]*',                      
                    ),                    
                    'defaults' => array(
                        '__NAMESPACE__' => 'MailingList\Controller',
                        'controller' => 'Sockets',
                        'action' => 'index',
                        'keywords' => '',
                        'langCode' => '',
                        'subsite' => ''
                    ),
                ),
            ),             
            //Legacy style path. 
            'mailingList-legacypath-toolbox' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '[/:subsite]/toolbox/tools/mailingList[_:action[&item]][_:itemId][-:langCode].html',
                    'defaults' => array(
                        '__NAMESPACE__' => 'MailingList\Controller',
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
            'mailingList-toolbox' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '[/:subsite]/toolbox/tools/mailingList[-:langCode][_:controller][/:action[/:itemId]]',
                    'defaults' => array(
                        '__NAMESPACE__' => 'MailingList\Controller',
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
            'MailingList\Controller\Index' => 'MailingList\Controller\IndexController',
            'MailingList\Controller\Toolbox' => 'MailingList\Controller\ToolboxController',
            'MailingList\Controller\Sockets' => 'MailingList\Controller\SocketsController'
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
				'mailingList/layout/module'=>__DIR__.'/../view/layout/module.phtml',
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
                'paths' => array(realpath(__DIR__ . '/../src/MailingList/Entity'))
            ),
            /*'admin_entities' => array(
                'class' => '\Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(realpath(__DIR__ . '/../src/MailingList/Entity/Admin'))
            ),*/
            //Add configured driver to orm_default entity manager
            'orm_default' => array(
                'drivers' => array(
                     'MailingList\Entity' => 'app_entities'
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