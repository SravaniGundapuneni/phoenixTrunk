<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

return array(
    'auto-layout' => true,    
    'router' => array(
        'routes' => array(
            'root-sockets' => array(
                'type' => 'Segment',
                'may_terminate' => true,
                'options' => array(
                    'route' => '/sockets/:socket',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Toolbox\Controller',
                        'controller' => 'Sockets',
                        'action' => 'index',
                        'socket' => '',
                    ),
                    'constraints' => array(
                        'socket' => '(.)+'
                    ),
                ),
            ),
            'root-subsite' => array(
                'type' => 'Segment',
                'may_terminate' => true,
                'options' => array(
                    'route' => '/:subsite/',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Toolbox\Controller',
                        'controller' => 'Index',
                        'action' => 'index',
                        'subsite' => '',
                        'langCode' => '',
                    ),
                ),
            ),
             'toolbox-sockets' => array(
                'type' => 'Segment',
                'options' => array (
                  'route' => '[/:subsite]/sockets/toolbox[/:action]',
                  'constraints' => array (
                     'controllr' => '[a-zA-Z][a-z[a-zA-Z0-9*',
                     'itemId' => '[0-9][0-9]*',
                      ),
                    'defaults' => array(
                        '__NAMESPACE__' => 'Toolbox\Controller',
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
    //         'home' => array(
    //             'type' => 'Zend\Mvc\Router\Http\Literal',
    //             'options' => array(
    //                 'route'    => '/',
    //                 'defaults' => array(
    //                     'controller' => 'Toolbox\Controller\Index',
    //                     'action'     => 'index',
    //                     'page'       => 'default',
    //                     'location'   => ''
    //                 ),
    //             ),
    //             'may_terminate' => 'true',
    //             'child_routes' => array(
    //                 'root-sockets' => array(
    //                     'type' => 'Segment',
    //                     'may_terminate' => true,
    //                     'options' => array(
    //                         'route' => 'sockets/:socket',
    //                         'defaults' => array(
    //                             '__NAMESPACE__' => 'Toolbox\Controller',
    //                             'controller' => 'Sockets',
    //                             'action' => 'index',
    //                             'socket' => '',
    //                         ),
    //                         'constraints' => array(
    //                             'socket' => '(.)+'
    //                         ),
    //                     ),
    //                 ),
    //                 'root-subsite' => array(
    //                     'type' => 'Segment',
    //                     'may_terminate' => true,
    //                     'options' => array(
    //                         'route' => ':subsite/',
    //                         'defaults' => array(
    //                             '__NAMESPACE__' => 'Toolbox\Controller',
    //                             'controller' => 'Index',
    //                             'action' => 'index',
    //                             'subsite' => '',
    //                             'langCode' => '',
    //                         ),
    //                     ),
    //                 ),
    //                 'toolbox-root-subsite-noslash' => array(
    //                     'type' => 'Segment',
    //                     'may_terminate' => true,
    //                     'options' => array(
    //                         'route' => ':subsite/toolbox',
    //                         'defaults' => array(
    //                             '__NAMESPACE__' => 'Toolbox\Controller',
    //                             'controller' => 'Toolbox',
    //                             'action' => 'index',
    //                             'subsite' => '',
    //                             'location' => 'toolbox',
    //                             'langCode' => '',
    //                         ),
    //                     ),
    //                 ),                    
    //                 'toolbox-root-subsite' => array(
    //                     'type' => 'Segment',
    //                     'may_terminate' => true,
    //                     'options' => array(
    //                         'route' => ':subsite/toolbox/',
    //                         'defaults' => array(
    //                             '__NAMESPACE__' => 'Toolbox\Controller',
    //                             'controller' => 'Toolbox',
    //                             'action' => 'index',
    //                             'subsite' => '',
    //                             'location' => 'toolbox',
    //                             'langCode' => '',
    //                         ),
    //                     ),
    //                 ),
    //                 'toolbox-root-noslash' => array(
    //                     'type' => 'Literal',
    //                     'may_terminate' => true,
    //                     'options' => array(
    //                         'route' => 'toolbox',
    //                         'defaults' => array(
    //                             '__NAMESPACE__' => 'Toolbox\Controller',
    //                             'controller' => 'Toolbox',
    //                             'action' => 'index',
    //                             'langCode' => '',
    //                         ),
    //                     ),
    //                 ),
    //                 'toolbox-root' => array(
    //                     'type' => 'Literal',
    //                     'may_terminate' => true,
    //                     'options' => array(
    //                         'route' => 'toolbox/',
    //                         'defaults' => array(
    //                             '__NAMESPACE__' => 'Toolbox\Controller',
    //                             'controller' => 'Toolbox',
    //                             'action' => 'index',
    //                             'langCode' => '',
    //                         ),
    //                     ),
    //                 ),
    //             ),
    //         ),
    //     ),
    // ),
    'service_manager' => array(
        'factories' => array(
            'translator' => 'Zend\I18n\Translator\TranslatorServiceFactory',
            'doctrine.driver.orm_default' => new DoctrineModule\Service\DriverFactory('orm_default'),
            'doctrine.connection.orm_default' => new DoctrineORMModule\Service\DBALConnectionFactory('orm_default'),
            'doctrine.configuration.orm_default' => new DoctrineORMModule\Service\ConfigurationFactory('orm_default'),
            'doctrine.entitymanager.orm_default' => new DoctrineORMModule\Service\EntityManagerFactory('orm_default'),
            'doctrine.entity_resolver.orm_default' => new DoctrineORMModule\Service\EntityResolverFactory('orm_default'),
            'doctrine.authenticationadapter.orm_default' => new DoctrineModule\Service\Authentication\AdapterFactory('orm_default'),
            'doctrine.authenticationstorage.orm_default' => new DoctrineModule\Service\Authentication\StorageFactory('orm_default'),
            'doctrine.authenticationservice.orm_default' => new DoctrineModule\Service\Authentication\AuthenticationServiceFactory('orm_default'),
            'doctrine.sql_logger_collector.orm_default' => new DoctrineORMModule\Service\SQLLoggerCollectorFactory('orm_default'),
            'doctrine.eventmanager.orm_default' => new DoctrineModule\Service\EventManagerFactory('orm_default'),

            // 'doctrine.mapping_collector.orm_default' => function (Zend\ServiceManager\ServiceLocatorInterface $sl) {
            //     return new DoctrineORMModule\Collector\MappingCollector($sl->get('doctrine.entitymanager.orm_default')->getMetadataFactory(), 'orm_default_mappings');
            // },

            'doctrine.driver.orm_admin' => new DoctrineModule\Service\DriverFactory('orm_admin'),
            'doctrine.connection.orm_admin' => new DoctrineORMModule\Service\DBALConnectionFactory('orm_admin'),
            'doctrine.configuration.orm_admin' => new DoctrineORMModule\Service\ConfigurationFactory('orm_admin'),
            'doctrine.entitymanager.orm_admin' => new DoctrineORMModule\Service\EntityManagerFactory('orm_admin'),
            'doctrine.entity_resolver.orm_admin' => new DoctrineORMModule\Service\EntityResolverFactory('orm_admin'),
            'doctrine.authenticationadapter.orm_admin' => new DoctrineModule\Service\Authentication\AdapterFactory('orm_admin'),
            'doctrine.authenticationstorage.orm_admin' => new DoctrineModule\Service\Authentication\StorageFactory('orm_admin'),
            'doctrine.authenticationservice.orm_admin' => new DoctrineModule\Service\Authentication\AuthenticationServiceFactory('orm_admin'),
            'doctrine.sql_logger_collector.orm_admin' => new DoctrineORMModule\Service\SQLLoggerCollectorFactory('orm_admin'),
            'doctrine.eventmanager.orm_admin' => new DoctrineModule\Service\EventManagerFactory('orm_admin'),

            // 'doctrine.mapping_collector.orm_admin' => function (Zend\ServiceManager\ServiceLocatorInterface $sl) {
            //     return new DoctrineORMModule\Collector\MappingCollector($sl->get('doctrine.entitymanager.orm_default')->getMetadataFactory(), 'orm_admin_mappings');
            // },
        ),
    ),
    'translator' => array(
        'locale' => 'en_US',
        'translation_file_patterns' => array(
            array(
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Toolbox\Controller\Index' => 'Toolbox\Controller\IndexController',
            'Toolbox\Controller\Legacy' => 'Toolbox\Controller\LegacyController',
            'Toolbox\Controller\Default' => 'Toolbox\Controller\IndexController',
            'Toolbox\Controller\Sockets' => 'Toolbox\Controller\SocketsController',
            'Toolbox\Controller\Toolbox' => 'Toolbox\Controller\ToolboxController',
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'toolbox-menu'         => __DIR__ . '/../view/layout/toolbox/menu.phtml',
            'toolbox-menu-item'    => __DIR__ . '/../view/layout/toolbox/menu-item.phtml',
            'mediaManager'         => __DIR__ . '/../../MediaManager/view/media-manager/toolbox/partials/mediamanager.phtml',
            'successNotification'  => __DIR__ . '/../view/layout/toolbox/handlebars/success.phtml',
            'errorNotification'    => __DIR__ . '/../view/layout/toolbox/handlebars/error.phtml',
            'toolbox-layout'       => __DIR__ . '/../view/layout/toolbox/layout-foundation.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
             SITE_PATH."/templates/main",
        ),
        'strategies' => array(
            'ViewJsonStrategy',
        ),
    ),
    'view_helpers' => array(
        'invokables' => array(
            'camelCaseToWords'      => 'Toolbox\Helper\CamelCaseToWords',
            'toolboxToolUrl'        => 'Toolbox\Helper\ToolboxToolUrl',
            'toolIconPath'          => 'Toolbox\Helper\ToolIconPath',
            'menuItem'              => 'Toolbox\Helper\MenuItem',
            'simpleMenu'            => 'Phoenix\Helper\SimpleMenu',
            'getDropdownNavigation' => 'Toolbox\Helper\GetDropdownNavigation',
            'formOrderedSelect'     => 'Phoenix\Form\View\Helper\FormOrderedSelect',
            'encrypt'               => 'Toolbox\Helper\Encrypt',
            'decrypt'               => 'Toolbox\Helper\Decrypt'
        )
    ),
    'doctrine' => array(
        'driver' => array(
            //Configure the mapping driver for entities in Application module
            'app_entities' => array(
                'class' => '\Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(realpath(__DIR__ . '/../src/Toolbox/Entity'))
            ),
            'admin_entities' => array(
                'class' => '\Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(realpath(__DIR__ . '/../src/Toolbox/Entity/Admin'))
            ),
            //Add configured driver to orm_default entity manager
            'orm_default' => array(
                'drivers' => array(
                     'Toolbox\Entity' => 'app_entities'
                )
            ),
            'orm_admin' => array(
                'class'   => 'Doctrine\ORM\Mapping\Driver\DriverChain',
                'drivers' => array(
                     'Toolbox\Entity\Admin' => 'admin_entities'
                )
            ),
            ),
        'configuration' => array(
            'orm_default' => array(
                // metadata cache instance to use. The retrieved service name will
                // be `doctrine.cache.$thisSetting`
                'metadata_cache'    => 'array',
                

                // DQL queries parsing cache instance to use. The retrieved service
                // name will be `doctrine.cache.$thisSetting`
                'query_cache'       => 'array',

                // ResultSet cache to use.  The retrieved service name will be
                // `doctrine.cache.$thisSetting`
                'result_cache'      => 'array',

                // Mapping driver instance to use. Change this only if you don't want
                // to use the default chained driver. The retrieved service name will
                // be `doctrine.driver.$thisSetting`
                'driver'            => 'orm_default',

                // Generate proxies automatically (turn off for production)
                'generate_proxies'  => true,

                // directory where proxies will be stored. By default, this is in
                // the `data` directory of your application
                'proxy_dir'         => 'data/DoctrineORMModule/Proxy',

                // namespace for generated proxy classes
                'proxy_namespace'   => 'DoctrineORMModule\Proxy',

                // SQL filters.
                'filters'           => array()
                ),
            // Configuration for service `doctrine.configuration.orm_admin` service
            'orm_admin' => array(
                // metadata cache instance to use. The retrieved service name will
                // be `doctrine.cache.$thisSetting`
                'metadata_cache'    => 'array',

                // DQL queries parsing cache instance to use. The retrieved service
                // name will be `doctrine.cache.$thisSetting`
                'query_cache'       => 'array',

                // ResultSet cache to use.  The retrieved service name will be
                // `doctrine.cache.$thisSetting`
                'result_cache'      => 'array',

                // Mapping driver instance to use. Change this only if you don't want
                // to use the default chained driver. The retrieved service name will
                // be `doctrine.driver.$thisSetting`
                'driver'            => 'orm_admin',

                // Generate proxies automatically (turn off for production)
                'generate_proxies'  => true,

                // directory where proxies will be stored. By default, this is in
                // the `data` directory of your application
                'proxy_dir'         => 'data/DoctrineORMModule/Proxy',

                // namespace for generated proxy classes
                'proxy_namespace'   => 'DoctrineORMModule\Proxy',

                // SQL filters.
                'filters'           => array()
            )
        ),


        // Entity Manager instantiation settings
        'entitymanager' => array(
            // configuration for the `doctrine.entitymanager.orm_admin` service
            'orm_admin' => array(
                // connection instance to use. The retrieved service name will
                // be `doctrine.connection.$thisSetting`
                'connection'    => 'orm_admin',

                // configuration instance to use. The retrieved service name will
                // be `doctrine.configuration.$thisSetting`
                'configuration' => 'orm_admin'
            )
        ),


        'eventmanager' => array(
            // configuration for the `doctrine.eventmanager.orm_admin` service
            'orm_admin' => array()
        ),


        // collector SQL logger, used when ZendDeveloperTools and its toolbar are active
        'sql_logger_collector' => array(
            // configuration for the `doctrine.sql_logger_collector.orm_admin` service
            'orm_admin' => array(),
        ),


        // entity resolver configuration, allows mapping associations to interfaces
        'entity_resolver' => array(
            // configuration for the `doctrine.entity_resolver.orm_admin` service
            'orm_admin' => array()
        ),


        // authentication service configuration
        'authentication' => array(
            // configuration for the `doctrine.authentication.orm_admin` authentication service
            'orm_admin' => array(
                // name of the object manager to use. By default, the EntityManager is used
                'objectManager' => 'doctrine.entitymanager.orm_admin'
            ),
        )
         ),
    'phoenix-filters' => array(
        'page' => 'phoenix-filters-page',
        'link' => 'phoenix-filters-link',
        ),
    'userPermissions' => array(
        'Core' => array(
            'Site wide' => 'allSite',
        )
    )
);
