<?php

// Phoenix pathing for specific widget.
return array(
	'router' => array(
            'routes' => array(
                'layoutEditor-toolbox' => array(
                    'type' => 'Segment',
                    'options' => array(
                        'route' => '[/:subsite]/toolbox/tools/layoutEditor[-:langCode][_:controller][/:action[/:itemId]]',
                        'defaults' => array(
                                                    '__NAMESPACE__' => 'LayoutEditor\Controller',
                                                    'controller' => 'LayoutEditor\Controller\Toolbox',
                                                    'action' => 'index',
                            'keywords' => '',
                            'langCode' => '',
                            'subsite' => ''
                            ),
                    ),
                ),
                'layoutEditor-sockets' => array(
				'type' => 'Segment',
				'options' => array(
					'route' => '[/:subsite]/sockets/layoutEditor[/:action]', //[/:action[/:itemId]]',
					'constraints' => array(
						'controller'     => '[a-zA-Z][a-zA-Z0-9]*',
						'itemId'     => '[0-9][0-9]*',
					),
					'defaults' => array(
						'__NAMESPACE__' => 'LayoutEditor\Controller',
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
                            'LayoutEditor\Controller\Toolbox' => 'LayoutEditor\Controller\ToolboxController',
                            'LayoutEditor\Controller\Sockets' => 'LayoutEditor\Controller\SocketsController',
                    ),
            ),
            'view_manager' => array (
           'template_path_stack' => array(
                            __DIR__ . '/../view'
            ),
        ),  
         // Used for region builder, maybe obsolete at this point - TN.
	'defaults' => array(
		'widgetFolder' => 'Templates', // Widget folder name.
		'widgetName' => 'Templates', // One or two word name for widget.
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