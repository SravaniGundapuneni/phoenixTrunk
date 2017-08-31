<?php
/**
 * Custom Segment Router for Toolbox. 
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
namespace Toolbox\Legacy;

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

    /**
     * Run the segment match on the requested uri and apply any legacy changes required.
     *
     * This will change the controller to use Legacy, and will set the necessary legacy parameters,
     * such as page, task, itemId, location, and pageAlias
     * 
     * @param  Request $request    [description]
     * @param  int     $pathOffset [description]
     * @return null|RouteMatch $routeMatch
     */
    public function match(Request $request, $pathOffset = null)
    {
        $routeMatch = parent::match($request, $pathOffset);

        if (empty($routeMatch)) 
        {
            return null;            
        }

        $routeMatch->setParam('page', $routeMatch->getParam('controller'));
        $routeMatch->setParam('controller', 'Legacy');
        if ($routeMatch->getParam('action') != 'index') {
            $action = (int) $routeMatch->getParam('action');
            if ($action > 0) {
                $paramName = 'itemId';
            } else {
                $paramName = 'task';
            }
            $routeMatch->setParam($paramName, $routeMatch->getParam('action'));
            $routeMatch->setParam('action', 'index');                
        }
        if ($routeMatch->getParam('subsite') != '') {
            $routeMatch->setParam('location', $routeMatch->getParam('subsite') . '/');
        }

        $routeMatch->setParam('pageAlias', $routeMatch->getParam('keywords'));

        return $routeMatch;
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