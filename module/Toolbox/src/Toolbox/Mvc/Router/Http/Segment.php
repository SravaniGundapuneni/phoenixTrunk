<?php
/**
 * Custom Segment Router for Toolbox. 
 *
 * This was originally in the library, but was moved after I noticed code that was specific to the Toolbox implementation of Phoenix
 * A. Tate 06/21/2013
 *
 * While the Zend Segment Router does a pretty decent job of routing the URLS, we need to add a couple parts of
 * functionality:
 *
 * 1) We need to take custom urls and link redirects and get their base route 
 *
 * 2) We need to look a site's config (and its subsite, if necessary) and see if they have routing config set up for the given page.
 *    If not, the router check will return null, and move to the next in the tree.
 * 3) Toolbox is a reserved subsite, and as such should not fall under these rules. As such, we have a check to return null anytime toolbox is the subsite.
 *    This is necessary because of the way routing is handled. We need Application to be the last module checked, but the other module routers were picking 
 *    up toolbox as a subsite, before the base Application routing could do its thing.
 * 
 * @category    Phoenix
 * @package     Mvc
 * @subpackage  Router
 * @copyright   Copyright (c) 2013 TravelClick
 * @license     All Rights Reserved
 * @version     Release: TBD
 * @since       File available since release TBD
 * @author      Andrew C. Tate <atate@travelclick.com>
 * @filesource
 */
namespace Toolbox\Mvc\Router\Http;

use \Zend\Mvc\Router\Http\Segment as ZendSegment;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

use Zend\Stdlib\RequestInterface as Request;

class Segment extends ZendSegment implements ServiceLocatorAwareInterface
{
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

    public function match(Request $request, $pathOffset = null)
    {
        $routeMatch = parent::match($request, $pathOffset);

        if (empty($routeMatch)) 
        {
            return null;            
        }

        if ($this->checkForToolbox($routeMatch)) {
            return null;
        }

        $applicationConfig = $this->routePluginManager->getServiceLocator()->get('ApplicationConfig');

        $pageConfig = new \Config\Model\PageConfig($routeMatch->getParam('controller'), $routeMatch->getParam('subsite'));

        $this->routePluginManager->getServiceLocator()->setService('PageConfig', $pageConfig);

        if (in_array($pageConfig->getModule(), $applicationConfig['modules'])) {
            return $this->setRouteMatch($pageConfig, $routeMatch);
        }

        return null;
    }

    public function checkForToolbox($routeMatch)
    {
        $subsite = $routeMatch->getParam('subsite');

        return ($subsite == 'toolbox') ? true : false;
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

    protected function setRouteMatch($pageConfig, $routeMatch)
    {
        $namespaceName = ucfirst($pageConfig->getModule()) . '\Controller';
        $routeMatch->setParam('__NAMESPACE__', $namespaceName);

        $routeMatch->setParam('controller', 'Index');

        $pageController = $pageConfig->getController();
        $pageAction = $pageConfig->getAction();
        
        if ($pageController) {
            $routeMatch->setParam('controller', $pageController);
        }

        if ($pageAction) {
            $routeMatch->setParam('action', $pageAction);
        }

        return $routeMatch;
    }
}