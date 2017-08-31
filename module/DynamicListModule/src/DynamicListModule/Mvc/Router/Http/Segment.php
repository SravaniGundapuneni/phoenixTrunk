<?php
/**
 * File for the Custom Router for DynamicListModule
 *
 * @category    Toolbox
 * @package     DynamicListModule
 * @subpackage  Router
 * @copyright   Copyright (c) 2014 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 1.14
 * @since       File available since release 1.14
 * @author      A. Tate <atate@travelclick.com>
 * @filesource
 */

namespace DynamicListModule\Mvc\Router\Http;

use \Zend\Mvc\Router\Http\Segment as ZendSegment;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

use Zend\Stdlib\RequestInterface as Request;

/**
 * Custom Router for DynamicListModule
 *
 * @category    Toolbox
 * @package     DynamicListModule
 * @subpackage  Router
 * @copyright   Copyright (c) 2014 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 1.14
 * @since       File available since release 1.14
 * @author      A. Tate <atate@travelclick.com>
 */
class Segment extends ZendSegment implements ServiceLocatorAwareInterface
{
    /**
     * Holds the Service Manager
     * @var mixed
     */
    protected $routePluginManager = null;    

    /**
     * Set service locator
     *
     * @param ServiceLocatorInterface $serviceLocator
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->routePluginManager = $serviceLocator;
    }

    /**
     * Checks to see if our request matches the router
     * 
     * @param  Request $request
     * @param  mixed  $pathOffset
     * @return mixed
     */
    public function match(Request $request, $pathOffset = null)
    {
        //Run the parent match method
        $routeMatch = parent::match($request, $pathOffset);

        //If the parent didn't find a match, no need to continue
        if (empty($routeMatch)) 
        {
            return null;            
        }

        //Get the module name from the route
        $moduleName = str_replace('-',' ',ucfirst($routeMatch->getParam('module')));
        //Get the Dynamid List Module service instance for use before MergedConfig
        $dynamicListModule = $this->routePluginManager->getServiceLocator()->get('phoenix-dynamiclistmodule-router');

        //Check to see if our module name is actually a dynamic module
        $isModuleResult = $dynamicListModule->getItemBy(array('name' => $moduleName, 'dynamic' => 1));

        if ($isModuleResult) {
            //Set the moduleName parameter on the RouteMatch object and return
            $routeMatch->setParam('moduleName', $moduleName);
            return $routeMatch;
        }
    }

    /**
     * Get service locator
     *
     * @return ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->routePluginManager;
    }      
}