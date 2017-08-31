<?php

return array(
    'router' => array(
        'routes' => array(
            'categories-toolbox' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '[/:subsite]/toolbox/tools/listModule_categories[-:langCode]/:moduleId[/:action[/:itemId]]',
                    'defaults' => array(
                        '__NAMESPACE__' => 'ListModule\Controller',
                        'controller' => 'Categorytoolbox',
                        'action' => 'index',
                        'keywords' => '',
                        'langCode' => '',
                        'subsite' => ''
                    ),
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9]*',
                        'controller' => '[a-zA-Z][a-zA-Z0-9]*',
                        'langCode' => '[a-z][a-z]*',
                        'itemId' => '[0-9][0-9]*',
                    )
                ),
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'ListModule\Controller\CategoryToolbox' => 'ListModule\Controller\CategoryToolboxController',
        ),
    ),
    'view_helpers' => array(
        'invokables' => array(
            'displayStatus' => 'ListModule\Helper\DisplayStatus',
            'displayYesNo' => 'ListModule\Helper\DisplayYesNo',
            'listUpdateStatusScript' => 'ListModule\Helper\ListUpdateStatusScript',
            'listUpdateStatusButtons' => 'ListModule\Helper\ListUpdateStatusButtons',
            'formElement' => 'ListModule\Form\View\Helper\FormElement'
        )
    ),
    'view_manager' => array(
        'template_map' => array(
            'list-module/layout/module' => __DIR__ . '/../view/layout/module.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view'
        ),
    ),
    'doctrine' => array(
        'driver' => array(
            'app_entities' => array(
                'class' => '\Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(realpath(__DIR__ . '/../src/ListModule/Entity'))
            ),
            'admin_entities' => array(
                'class' => '\Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(realpath(__DIR__ . '/../src/ListModule/Entity/Admin'))
            ),
            'orm_default' => array(
                'drivers' => array(
                    'ListModule\Entity' => 'app_entities'
                )
            ),
            'orm_admin' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\DriverChain',
                'drivers' => array(
                    'ListModule\Entity\Admin' => 'admin_entities'
                )
            ),
        ),
    ),
    'media-manager-sizes' => array(
        'featured-Destinations' => array(
            'FeaturedDestinations',
        ),
        'featured-Image' => array(
            'FeaturedImage',
            'FeaturedSpecialsLarge',
        ),
        'galleries' => array(
            'PhotoThumbs',
        ),
        'phoenixAddons' => array(
            'Submedium',
        ),
        'profiles' => array(
            'ExecutiveTeam',
        ),
        'property-Features' => array(
            'HotelCardThumbs',
        ),
        'venues' => array(
            'Subsmall',
        ),
    ),       
);
