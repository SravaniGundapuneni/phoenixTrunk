<?php
/**
 * The Skeleton model file
 *
 * This model holds the Skeleton array, and is used as an interface to access and change settings
 * in the Skeleton array.
 *
 * @category    Toolbox
 * @package     Config
 * @subpackage  Model
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.5
 * @since       File available since release 13.4
 * @author      A. Tate <atate@travelclick.com>
 * @filesource
 */
namespace DevTools\Model;

use ZFTool\Model\Skeleton as ZFToolSkeleton;

/**
 * The Skeleton model file
 *
 * This model holds the Skeleton array, and is used as an interface to access and change settings
 * in the Skeleton array.
 *
 * @category    Toolbox
 * @package     Config
 * @subpackage  Model
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.5
 * @since       File available since release 13.4
 * @author      A. Tate <atate@travelclick.com>
 */
class Skeleton extends ZFToolSkeleton
{
    /**
     * Return the Module.php content
     *
     * @param  string $name
     * @return string
     */
    public static function getModule($name)
    {
        return <<<EOD
<?php
namespace $name;

use Phoenix\Module\Module as PhoenixModule;

class Module extends PhoenixModule
{
    protected \$moduleNamespace = __NAMESPACE__;
    protected \$moduleDirectory = __DIR__;

    public function getServiceConfig()
    {
        return array();
    }
}

EOD;
    }    

    /**
     *
     * @param type $name
     * @return type
     */
    public static function getModuleConfig($name)
    {
        $routeName = lcfirst($name);
        return <<<EOD
<?php
return array(
    'router' => array(
        'routes' => array(
            '$routeName-direct' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '[/:subsite]/{$routeName}[-:langCode][_:controller][/:action[/:itemId]]',
                    'constraints' => array(
                        'controller'     => '[a-zA-Z][a-zA-Z0-9]*',
                        'itemId'     => '[0-9][0-9]*',
                    ),                    
                    'defaults' => array(
                        '__NAMESPACE__' => '$name\Controller',
                        'controller' => 'Index',
                        'action' => 'index',
                        'keywords' => '',
                        'langCode' => '',
                    ),
                ),
            ),
            '{$routeName}-sockets' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '[/:subsite]/{$routeName}/sockets', //[/:action[/:itemId]]',
                    'constraints' => array(
                        'controller'     => '[a-zA-Z][a-zA-Z0-9]*',
                        'itemId'     => '[0-9][0-9]*',                      
                    ),                    
                    'defaults' => array(
                        '__NAMESPACE__' => '$name\Controller',
                        'controller' => 'Sockets',
                        'action' => 'index',
                        'keywords' => '',
                        'langCode' => '',
                        'subsite' => ''
                    ),
                ),
            ),            
            //Legacy style path. 
            '{$routeName}-legacypath-toolbox' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '[/:subsite]/toolbox/tools/{$routeName}[_:action[&item]][_:itemId][-:langCode].html',
                    'defaults' => array(
                        '__NAMESPACE__' => '$name\Controller',
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
            '{$routeName}-toolbox' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '[/:subsite]/toolbox/tools/{$routeName}[-:langCode][_:controller][/:action[/:itemId]]',
                    'defaults' => array(
                        '__NAMESPACE__' => '$name\Controller',
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
            '$name\Controller\Index' => '$name\Controller\IndexController',
            '$name\Controller\Toolbox' => '$name\Controller\ToolboxController',
            '$name\Controller\Sockets' => '$name\Controller\SocketsController'
        ),
    ),    
    'doctrine' => array(
        'driver' => array(
            //Configure the mapping driver for entities in Application module 
            'app_entities' => array(
                'class' => '\Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(realpath(__DIR__ . '/../src/$name/Entity'))
            ),
            'admin_entities' => array(
                'class' => '\Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(realpath(__DIR__ . '/../src/$name/Entity/Admin'))
            ),
            //Add configured driver to orm_default entity manager
            'orm_default' => array(
                'drivers' => array(
                     '$name\Entity' => 'app_entities'
                )
            ),
            'orm_admin' => array(
                'class'   => 'Doctrine\ORM\Mapping\Driver\DriverChain',                
                'drivers' => array(
                     '$name\Entity\Admin' => 'admin_entities'
                )
            ),
        ),
    ),        
);
EOD;
    }
}