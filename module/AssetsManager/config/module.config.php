<?php
return array(
    'assetsManager' => array (
        'name' => 'Assetics',
        'version' => '5.3',
        'asseticsEnvironment' => 'development',   
        'asseticsJsMinificationEnabled' => 'true',
        'asseticsCssMinificationEnabled' => 'true',
        'asseticsBuildOnRequestEnabled' => 'true',   
        'asseticsDebugModeEnabled' => 'true',
        'asseticsCacheModeEnabled' => 'true'  
    ), 
    'router' => array(
        'routes' => array(
            'assetsManager-direct' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '[/:subsite]/assetsManager[-:langCode][_:controller][/:action[/:itemId]]',
                    'constraints' => array(
                        'controller'     => '[a-zA-Z][a-zA-Z0-9]*',
                        'itemId'     => '[0-9][0-9]*',
                    ),                    
                    'defaults' => array(
                        '__NAMESPACE__' => 'AssetsManager\Controller',
                        'controller' => 'Index',
                        'action' => 'index',
                        'keywords' => '',
                        'langCode' => '',
                    ),
                ),
            ),
            'assetsManager-sockets' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '[/:subsite]/assetsManager/sockets', //[/:action[/:itemId]]',
                    'constraints' => array(
                        'controller'     => '[a-zA-Z][a-zA-Z0-9]*',
                        'itemId'     => '[0-9][0-9]*',                      
                    ),                    
                    'defaults' => array(
                        '__NAMESPACE__' => 'AssetsManager\Controller',
                        'controller' => 'Sockets',
                        'action' => 'index',
                        'keywords' => '',
                        'langCode' => '',
                        'subsite' => ''
                    ),
                ),
            ),            
            //Legacy style path. 
            'assetsManager-legacypath-toolbox' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '[/:subsite]/toolbox/tools/assetsManager[_:action[&item]][_:itemId][-:langCode].html',
                    'defaults' => array(
                        '__NAMESPACE__' => 'AssetsManager\Controller',
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
            'assetsManager-toolbox' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '[/:subsite]/toolbox/tools/assetsManager[-:langCode][_:controller][/:action[/:itemId]]',
                    'defaults' => array(
                        '__NAMESPACE__' => 'AssetsManager\Controller',
                        'controller' => 'Toolbox',
                        'action' => 'assetic',
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
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view'
        ),
    ),    
    'controllers' => array(
        'invokables' => array(
            'AssetsManager\Controller\Index' => 'AssetsManager\Controller\IndexController',
            'AssetsManager\Controller\Toolbox' => 'AssetsManager\Controller\ToolboxController',
            'AssetsManager\Controller\Sockets' => 'AssetsManager\Controller\SocketsController',
        ),
    ),    
    'doctrine' => array(
        'driver' => array(
            //Configure the mapping driver for entities in Application module 
            'app_entities' => array(
                'class' => '\Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(realpath(__DIR__ . '/../src/AssetsManager/Entity'))
            ),
            'admin_entities' => array(
                'class' => '\Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(realpath(__DIR__ . '/../src/AssetsManager/Entity/Admin'))
            ),
            //Add configured driver to orm_default entity manager
            'orm_default' => array(
                'drivers' => array(
                     'AssetsManager\Entity' => 'app_entities'
                )
            ),
            'orm_admin' => array(
                'class'   => 'Doctrine\ORM\Mapping\Driver\DriverChain',                
                'drivers' => array(
                     'AssetsManager\Entity\Admin' => 'admin_entities'
                )
            ),
        ),
    ), 
    'assetic_configuration' => array(
        // Use on development environment
        'debug' => true,
        'buildOnRequest' => true,
        'cacheEnabled'      => false,
        // This is optional flag, by default set to `true`.
        // In debug mode this allows you to combine all assets to one file.
        'combine' => true,
        //where the combined files are published to
        //'baseUrl'           => SITE_PATH,
        'webPath'           => SITE_PATH . '/scripts',
        'basePath'          => '/scripts',

        'default' => array(
            'assets' => array(
                //'@main',
                '@region_builder',
                '@head_js',
                '@inline_js'
            ),
            'options' => array(
                'mixin' => true,
            ),
        ),
        'modules' => array(
            'phoenixsite' => array(
                //where the src files are located
                # module root path for yout css and js files
                'root_path' => SITE_PATH . '/templates/main',
                # collection of assets
                'collections' => array(
                    /*'main' => array(
                        'assets' => array(
                            //'css/*.css',
                            //'scss/main.scss',
                        ),
                        'filters' => array(),
                        'options' => array(),
                    ),*/
                    'region_builder' => array(
                        //compiles SASS sheets from region builder
                        'assets' => array(
                            'scss/*.scss',
                            'scss/pages/*.scss'
                        ),
                        'filters' => array(
                            'ScssphpFilter' => array('name' => 'Assetic\Filter\ScssphpFilter'),
                             ),   
                        'options' => array(
                          //  'output' => 'main.css',
                            ),
                    ),
                    'head_js' => array(
                        'assets' => array(
                            'js/header/*.js',
                        ),
                        'filters' => array(
                            //'?JSMinFilter' => array('name'=> 'Assetic\Filter\JSMinFilter'
                            //),
                        ),
                    ),
                    'inline_js' => array(
                        'assets' => array(
                            'js/inline/*.js',
                        ),
                        'filters' => array(
                            //'?JSMinFilter' => array('name'=> 'Assetic\Filter\JSMinFilter'
                            //),
                        ),
                    ),
                    'base_images' => array(
                        'assets'=> array(
                            'images/*.png',
                        ),
                        'options' => array(
                            'move_raw' => true,
                        )
                    ),
               ),
            ),
        ),
   ),
);
                                       
                                        