<?php
/**
 * File for the Custom Router for FlexibleForms
 *
 * @category    Toolbox
 * @package     FlexibleForms
 * @subpackage  Router
 * @copyright   Copyright (c) 2014 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 1.14
 * @since       File available since release 1.14
 * @author      A. Tate <atate@travelclick.com>
 * @filesource
 */

namespace FlexibleForms\Mvc\Router\Http;

use \Zend\Mvc\Router\Http\Segment as ZendSegment;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

use Zend\Stdlib\RequestInterface as Request;

/**
 * Custom Router for FlexibleForms
 *
 * @category    Toolbox
 * @package     FlexibleForms
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
        $formName = ucfirst($routeMatch->getParam('form'));

        //Get the Dynamid List Module service instance for use before MergedConfig
        $flexibleForms = $this->routePluginManager->getServiceLocator()->get('phoenix-flexibleforms-router');

        //Check to see if our module name is actually a dynamic module
        $isFormResult = $flexibleForms->getItemBy(array('name' => $formName));

        if ($isFormResult) {
            //Set the moduleName parameter on the RouteMatch object and return
            $routeMatch->setParam('formName', $moduleName);
            
            return $routeMatch;
        }

        return null;
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