<?php
/**
 * File for GetModuleRedirect Controller Plugin
 *
 * @category    Toolbox
 * @package     DynamicListModule
 * @subpackage  Plugins
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 1.14
 * @since       File available since release 1.14
 * @author      A. Tate <atate@travelclick.com>
 * @filesource
 */

namespace DynamicListModule\Mvc\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;

/**
 * GetModuleRedirect Controller Plugin
 *
 * @category    Toolbox
 * @package     DynamicListModule
 * @subpackage  Plugins
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 1.14
 * @since       File available since release 1.14
 * @author      A. Tate <atate@travelclick.com>
 */
class GetModuleRedirect extends AbstractPlugin
{
    /**
     * __invoke
     * 
     * @param  string $module
     * @param  string $routeSuffix
     * @param  array  $parameters
     * @return string
     */
    public function __invoke($module, $routeSuffix = 'toolbox', $parameters = array())
    {
        //Retrieves the loaded controller object
        $controller = $this->getController();

        //Get the MergedConfig object
        $config = $controller->getServiceLocator()->get('MergedConfig');

        //Get the modules list from the config
        $modules = $config->get(array('modules'), array());

        //Check to see if module is static or not
        if (array_search($module, $modules)) {
            //Module is static module
            $route = $module . '-' . $routeSuffix;
        } else {
            //Module doesn't exist, assume dynamic module
            //The module might also not exist, but that should be handled by the router.
            $route = 'dynamicListModule-module' . ucfirst($routeSuffix);
            $parameters['module'] = str_replace(' ','-',lcfirst($module));
        }

        //Build the url
        $url = $controller->url()->fromRoute($route, $parameters);

        //Return the url
        return $url;
    }
}