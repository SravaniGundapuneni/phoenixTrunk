<?php
return array(
  'router' => array(
      'routes' => array(
         'phoenixRooms-direct' => array(
          'type' => 'Segment',
          'options' => array(
             'route' => '[/:subsite]/phoenixRooms[-:langCode][_:controller][/:action[/:itemId]]',
             'constraints' => array(
                 'controller'   => '[a-zA-Z][a-zA-Z0-9]*',
                 'itemId'       => '[0-9][0-9]',
                ),
              'defaults' => array(
                   '__NAMESPACE__' => 'PhoenixRooms\Controller',
                   'controller' => 'Index',
                   'action' => 'index',

                ),
            ),
          ),

         'phoenixRooms-sockets' => array(
            'type' => 'Segment',
            'options' => array (
              'route' => '[/:subsite]/sockets/phoenixRooms[/:action]',
              'constraints' => array (
                 'controller' => '[a-zA-Z][a-z[a-zA-Z0-9*',
                 'itemId' => '[0-9][0-9]*',
                  ),
                'defaults' => array(
                    '__NAMESPACE__' => 'PhoenixRooms\Controller',
                    'controller' => 'Sockets',
                    'action' => 'index',
                    'keywords' => '',
                    'langCode' => '',
                    'subsite' => ''
                  ),
              ),
          ),
            //Legacy style path. 
            //Not sure what was causing the comma error, but some info was missing from your array
            'phoenixRooms-legacypath-toolbox' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '[/:subsite]/toolbox/tools/phoenixRooms[_:action[&item]][_:itemId][-:langCode].html',
                    'defaults' => array(
                        '__NAMESPACE__' => 'PhoenixRooms\Controller',
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
          'phoenixRooms-toolbox' => array(
              'type' => 'Segment',
              'options' =>  array(
                'route' => '[/:subsite]/toolbox/tools/phoenixRooms[-:langCode][_:controller][/:action[/:itemId]]',
                'defaults' => array(
                  '__NAMESPACE__' => 'PhoenixRooms\Controller',
                  'controller' => 'Toolbox',
                  'action' => 'index',
                  'keywords' => '',
                  'langCode' => '',
                  'subsite' => '',
                  ),
                'constraints' => array(
                   'action' => '[a-zA-Z][a-zA-Z0-9]*',
                   'controller' => '[a-zA-Z][a-zA-Z0-9]*',
                   'langCode' => '[a-z][a-z]*',
                   'itemId' => '[0-9][0-9]*',
                 )
              ),
          ),

          'phoenixRooms-toolbox-user-properties' => array(
            'type' => 'Segment',
            'options' => array(
              'route' => '[/:subsite]/toolbox/tools/phoenixRooms[-:langCode]/userProperties[/:itemId]',
              'defaults' => array (
                '__NAMESPACE__' => 'PhoenixRooms\Controller',
                'controller' => 'Toolbox',
                'action' => 'userProperties',
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
 'cxmlIgnore' => array(
     'PhoenixRooms\Controller\Toolbox' => true,
     'PhoenixRooms\Controller\Sockets' => true
  ),
  'controllers' => array(
     'invokables' => array(
      'PhoenixRooms\Controller\Index' => 'PhoenixRooms\Controller\IndexController',
      'PhoenixRooms\Controller\Sockets' => 'PhoenixRooms\Controller\SocketsController',
      'PhoenixRooms\Controller\Toolbox' => 'PhoenixRooms\Controller\ToolboxController',
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
       __DIR__ . '/../view'
      ),
    ),
 'doctrine' => array(
    'driver' => array(
       'app_entities' => array(
         'class' => '\Doctrine\ORM\Mapping\Driver\AnnotationDriver',
         'cache' => 'array',
         'paths' => array(realpath(__DIR__ . '/../src/PhoenixRooms/Entity'))

        ),
       'admin_entities' => array(
         'class' => '\Doctrine\ORM\Mapping\Driver\AnnotationDriver',
         'cache' => 'array',
         'paths' => array(realpath(__DIR__. '/../src/PhoenixRooms/Entity/Admin'))
        ),
       'orm_default' => array(
         'drivers' => array(
            'PhoenixRooms\Entity' => 'app_entities'
          )
        ),
        'orm_admin' => array(
          'class' => 'Doctrine\ORM\Mapping\Driver\DriverChain',
          'drivers' => array(
             'PhoenxRooms\Entity\Admin' => 'admin_entities'
            )

          ),
         ),
     ),

 'toggleEnabledFields' => array(
  'PhoenixRooms\Form\RoomForm' => array(
    'roomPrice',
    'roomname')
  ),

 'disabledFields' => array(
   'PhoenixRooms\Form\RoomForm' => array(
    'roomPrice',
    'roomname')
  ),
  'media-manager-sizes' => array(
    'phoenixRooms' => array(
      'FeaturedSpecialsListView',
      'phoenixRooms'
    ),
  ),       
);
//Removed closing tag, as this page is all PHP.