<?php
return array(
    //This is the default siteKey. Unless you have a really good reason, this shouldn't be changed for the main site of a group
    //Conversely, each sub site should have this set in their site.config.php file.
    'siteKey' => 'root',
    'mediaRoot' => 'd/',
    'router' => array(
        'routes' => array(
             
            'home' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        'controller' => 'Toolbox\Controller\Index',
                        'action'     => 'index',
                        'page'       => 'default',
                        'location'   => ''
                    ),
                ),
                'may_terminate' => 'true',
                'child_routes' => array(                   
                    'toolbox-root-subsite-noslash' => array(
                        'type' => 'Segment',
                        'may_terminate' => true,
                        'options' => array(
                            'route' => ':subsite/toolbox',
                            'defaults' => array(
                                '__NAMESPACE__' => 'Toolbox\Controller',
                                'controller' => 'Toolbox',
                                'action' => 'index',
                                'subsite' => '',
                                'location' => 'toolbox',
                                'langCode' => '',
                            ),
                        ),
                    ),   
                    
                     
                    'toolbox-root-subsite' => array(
                        'type' => 'Segment',
                        'may_terminate' => true,
                        'options' => array(
                            'route' => ':subsite/toolbox/',
                            'defaults' => array(
                                '__NAMESPACE__' => 'Toolbox\Controller',
                                'controller' => 'Toolbox',
                                'action' => 'index',
                                'subsite' => '',
                                'location' => 'toolbox',
                                'langCode' => '',
                            ),
                        ),
                    ),
                    'toolbox-root-noslash' => array(
                        'type' => 'Literal',
                        'may_terminate' => true,
                        'options' => array(
                            'route' => 'toolbox',
                            'defaults' => array(
                                '__NAMESPACE__' => 'Toolbox\Controller',
                                'controller' => 'Toolbox',
                                'action' => 'index',
                                'langCode' => '',
                            ),
                        ),
                    ),
                    'toolbox-root' => array(
                        'type' => 'Literal',
                        'may_terminate' => true,
                        'options' => array(
                            'route' => 'toolbox/',
                            'defaults' => array(
                                '__NAMESPACE__' => 'Toolbox\Controller',
                                'controller' => 'Toolbox',
                                'action' => 'index',
                                'langCode' => '',
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
      
    'doctrine' => array(
  
        'driver' => array(
            //Configure the mapping driver for entities in Application module 
            'app_entities' => array(
                'class' => '\Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(realpath(__DIR__ . '/../src/PhoenixSite/Entity'))
            ),
            'admin_entities' => array(
                'class' => '\Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(realpath(__DIR__ . '/../src/PhoenixSite/Entity/Admin'))
            ),
            //Add configured driver to orm_default entity manager
            'orm_default' => array(
                'drivers' => array(
                     'PhoenixSite\Entity' => 'app_entities'
                )
            ),
            'orm_admin' => array(
                'class'   => 'Doctrine\ORM\Mapping\Driver\DriverChain',                
                'drivers' => array(
                     'PhoenixSite\Entity\Admin' => 'admin_entities'
                )
            ),
        ),
    ),       
);