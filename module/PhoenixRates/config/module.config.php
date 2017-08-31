<?php
return array(
    'router' => array(
        'routes' => array(
            'phoenixRates-direct' => array(
                'type' => 'Segment',
                'options' => array(
                    //'route'    => '[:subsite[/[[:keywords],][[[:controller]_[[:action]_[[[:itemId]-]]]]]]]',
                    //'route' => '[/:subsite]/phoenixRates[-:langCode][_:controller][/:action[/:itemId]]',
                    'route' => '[/:subsite]/rateDetails[/:itemId]',
                    'constraints' => array(
                        'itemId'     => '[0-9][0-9]*',
                    ),                    
                    'defaults' => array(
                        '__NAMESPACE__' => 'PhoenixRates\Controller',
                        'controller' => 'Index',
                        'action' => 'viewItem',
                        'keywords' => '',
                        'langCode' => '',
                    ),
                ),
            ),
            'phoenixRates-sockets' => array(
                'type' => 'Segment',
                'options' => array(
                    //'route'    => '[:subsite[/[[:keywords],][[[:controller]_[[:action]_[[[:itemId]-]]]]]]]',
                    'route' => '[/:subsite]/sockets/phoenixRates[/:action]', //[/:action[/:itemId]]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9]*',
                        'controller'     => '[a-zA-Z][a-zA-Z0-9]*',
                        'itemId'     => '[0-9][0-9]*',                      
                    ),                    
                    'defaults' => array(
                        '__NAMESPACE__' => 'PhoenixRates\Controller',
                        'controller' => 'Sockets',
                        'action' => 'index',
                        'keywords' => '',
                        'langCode' => '',
                        'subsite' => ''
                    ),
                ),
            ),
            //Legacy style path. 
            'phoenixRates-legacypath-toolbox' => array(
                'type' => 'Segment',
                'options' => array(
                    //'route'    => '[:subsite[/[[:keywords],][[[:controller]_[[:action]_[[[:itemId]-]]]]]]]',
                    'route' => '[/:subsite]/toolbox/tools/phoenixRates[_:action[&item]][_:itemId][-:langCode].html',
                    'defaults' => array(
                        '__NAMESPACE__' => 'PhoenixRates\Controller',
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
            'phoenixRates-toolbox' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '[/:subsite]/toolbox/tools/phoenixRates[-:langCode][_:controller][/:action[/:itemId]]',
                    'defaults' => array(
                        '__NAMESPACE__' => 'PhoenixRates\Controller',
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
    //This will tell Phoenix not to look for cxml for this controller, or for specific actions
    'cxmlIgnore' => array(
        'PhoenixRates\Controller\Toolbox' => true,
        'PhoenixRates\Controller\Sockets' => true
    ),
    'controllers' => array(
        'invokables' => array(
            'PhoenixRates\Controller\Index' => 'PhoenixRates\Controller\IndexController',
            'PhoenixRates\Controller\Sockets' => 'PhoenixRates\Controller\SocketsController',
            'PhoenixRates\Controller\Toolbox' => 'PhoenixRates\Controller\ToolboxController',
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
             // 'users/layout/module'        => __DIR__ . '/../view/layout/module.phtml',
             // 'users/index'         => __DIR__ . '/../view/users/index/index.phtml'
             //'error/404'               => __DIR__ . '/../view/error/404.phtml',
             //'error/index'             => __DIR__ . '/../view/error/index.phtml',
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
                'paths' => array(realpath(__DIR__ . '/../src/PhoenixRates/Entity'))
            ),
            'admin_entities' => array(
                'class' => '\Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(realpath(__DIR__ . '/../src/PhoenixRates/Entity/Admin'))
            ),
            //Add configured driver to orm_default entity manager
            'orm_default' => array(
                'drivers' => array(
                     'PhoenixRates\Entity' => 'app_entities'
                )
            ),
            'orm_admin' => array(
                'class'   => 'Doctrine\ORM\Mapping\Driver\DriverChain',                
                'drivers' => array(
                     'PhoenixRates\Entity\Admin' => 'admin_entities'
                )
            ),
        ),
    ),
    'toggleEnabledFields' => array(
        'PhoenixRates\Form\RateForm' => array(
            'price'
        )
    ),
    'disabledFields' => array(
        'PhoenixRates\Form\RateForm' => array(
            'price'
        )
    ),
    'media-manager-sizes' => array(
        'phoenixRates' => array(
            'Submedium',
            'FeaturedSpecialsListView',
            'FeaturedSpecialsMini',
            'FeaturedSpecialsLarge',
            'FeaturedSpecials'
        ),
    ),       
    'responsive-sizes' => array(
        'phoenixRates' => array(
            'Small' => 'Submedium',
            'Medium' => 'FeaturedSpecials',
            'Large' => 'FeaturedSpecialsLarge',
        ),
    ),
);