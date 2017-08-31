<?php

// Phoenix pathing for specific widget.
return array(
	'router' => array(
            'routes' => array(
                'phoenixTemplates-toolbox' => array(
                    'type' => 'Segment',
                    'options' => array(
                        'route' => '[/:subsite]/toolbox/tools/phoenixTemplates[-:langCode][_:controller][/:action[/:itemId]]',
                        'defaults' => array(
                                                    '__NAMESPACE__' => 'PhoenixTemplates\Controller',
                                                    'controller' => 'PhoenixTemplates\Controller\Toolbox',
                                                    'action' => 'index',
                            'keywords' => '',
                            'langCode' => '',
                            'subsite' => ''
                            ),
                    ),
                ),
                'phoenixTemplates-test' => array(
                    'type' => 'Segment',
                    'options' => array(
                        'route' => '[/:subsite]/toolbox/tools/phoenixTemplates/test[-:langCode][_:controller][/:action[/:itemId]]',
                        'defaults' => array(
                                                    '__NAMESPACE__' => 'PhoenixTemplates\Controller',
                                                    'controller' => 'PhoenixTemplates\Controller\Test',
                                                    'action' => 'index',
                            'keywords' => '',
                            'langCode' => '',
                            'subsite' => ''
                            ),
                    ),
                ),
                
                'phoenixTemplates-template1' => array(
                    'type' => 'Segment',
                    'options' => array(
                        'route' => '[/:subsite]/toolbox/tools/phoenixTemplates/template1[-:langCode][_:controller][/:action[/:itemId]]',
                        'defaults' => array(
                                                    '__NAMESPACE__' => 'PhoenixTemplates\Controller',
                                                    'controller' => 'PhoenixTemplates\Controller\Template1',
                                                    'action' => 'index',
                            'keywords' => '',
                            'langCode' => '',
                            'subsite' => ''
                            ),
                    ),
                ),
                
                'phoenixTemplates-template2' => array(
                    'type' => 'Segment',
                    'options' => array(
                        'route' => '[/:subsite]/toolbox/tools/phoenixTemplates/template2[-:langCode][_:controller][/:action[/:itemId]]',
                        'defaults' => array(
                                                    '__NAMESPACE__' => 'PhoenixTemplates\Controller',
                                                    'controller' => 'PhoenixTemplates\Controller\Template2',
                                                    'action' => 'index',
                            'keywords' => '',
                            'langCode' => '',
                            'subsite' => ''
                            ),
                    ),
                ),
                
                'phoenixTemplates-template3' => array(
                    'type' => 'Segment',
                    'options' => array(
                        'route' => '[/:subsite]/toolbox/tools/phoenixTemplates/template3[-:langCode][_:controller][/:action[/:itemId]]',
                        'defaults' => array(
                                                    '__NAMESPACE__' => 'PhoenixTemplates\Controller',
                                                    'controller' => 'PhoenixTemplates\Controller\Template2',
                                                    'action' => 'index',
                            'keywords' => '',
                            'langCode' => '',
                            'subsite' => ''
                            ),
                    ),
                ),
                
                'phoenixTemplates-sockets' => array(
				'type' => 'Segment',
				'options' => array(
					'route' => '[/:subsite]/sockets/phoenixTemplates[/:action]', //[/:action[/:itemId]]',
					'constraints' => array(
						'controller'     => '[a-zA-Z][a-zA-Z0-9]*',
						'itemId'     => '[0-9][0-9]*',
					),
					'defaults' => array(
						'__NAMESPACE__' => 'PhoenixTemplates\Controller',
						'controller' => 'Sockets',
						'action' => 'index',
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
                            'PhoenixTemplates\Controller\Toolbox' => 'PhoenixTemplates\Controller\ToolboxController',
                            'PhoenixTemplates\Controller\Test' => 'PhoenixTemplates\Controller\TestController',
                            'PhoenixTemplates\Controller\Template1' => 'PhoenixTemplates\Controller\Template1Controller',
                            'PhoenixTemplates\Controller\Template2' => 'PhoenixTemplates\Controller\Template2Controller',
                            'PhoenixTemplates\Controller\Sockets' => 'PhoenixTemplates\Controller\SocketsController',
                    ),
            ),
            'view_manager' => array (
           'template_path_stack' => array(
                            __DIR__ . '/../view'
            ),
        ),  
         // Used for region builder, maybe obsolete at this point - TN.
	'defaults' => array(
		'widgetFolder' => 'PhoenixTemplates', // Widget folder name.
		'widgetName' => 'PhoenixTemplates', // One or two word name for widget.
		'widgetType' => 'Content', // Header, Content, Footer, All.
		'moduleConnection' => '', // Which, if any, modules .does the widget use.
	),
	'includes' => array(
		'js' => array( // any non-global includes, order by priority
			'1' => '',
			),
		'css' => array( // any non-global includes, order by priority
			'1' => '',
			),
		),
        // Used to create specific config options for the widgets. Similar functiontionality to the JW Test .xml config options.
        // When a widget is saved to a page the config selections will be written to the unique widget instance in the /templates/main subsite folder.
    	'config' => array(
            'inputs' => array(
                    'format' => array(
                        'type' => 'select',
                        'label' => 'Select Time Format',
                        'values' => array('24h format','12h format','Long format')
                        ),
                    'showSeconds' => array(
                        'type' => 'checkbox',
                        'label' => 'Select Time Format'
                        ),
                    ),
	),
); 