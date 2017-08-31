<?php

// Phoenix pathing for specific widget.
return array(
    'router' => array(
        'routes' => array(
            'templateBuilder-toolbox' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '[/:subsite]/toolbox/tools/templateBuilder[-:langCode][_:controller][/:action[/:itemId]]',
                    'defaults' => array(
                        '__NAMESPACE__' => 'TemplateBuilder\Controller',
                        'controller' => 'TemplateBuilder\Controller\Toolbox',
                        'action' => 'index',
                        'keywords' => '',
                        'langCode' => '',
                        'subsite' => ''
                    ),
                ),
            ),
            'templateBuilder-sockets' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '[/:subsite]/sockets/templateBuilder[/:action]', //[/:action[/:itemId]]',
                        'constraints' => array(
                        'controller'  => '[a-zA-Z][a-zA-Z0-9]*',
                        'itemId'      => '[0-9][0-9]*',
                    ),
                    'defaults' => array(
                        '__NAMESPACE__' => 'TemplateBuilder\Controller',
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
            'TemplateBuilder\Controller\Toolbox' => 'TemplateBuilder\Controller\ToolboxController',
            'TemplateBuilder\Controller\Sockets' => 'TemplateBuilder\Controller\SocketsController',
        ),
    ),
    'view_helpers' => array(
        'invokables' => array(
            'getTemplateFileImage' => 'TemplateBuilder\Helper\GetTemplateFileImage',
            'getTemplateFiles' => 'TemplateBuilder\Helper\GetTemplateFiles',
            'getTemplatePageNumber' => 'TemplateBuilder\Helper\GetTemplatePageNumber',
        )
    ),
    'view_manager' => array (
        'template_path_stack' => array(
            __DIR__ . '/../view'
        ),
    )
); 