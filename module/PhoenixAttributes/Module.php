<?php
/**
 * PhoenixAttributes Module
 *
 * The Module Class for the Phoenix Attributes module
 *
 * Phoenix Attributes is one of five related modules, all with the Phoenix prefix to
 * separate them from existing modules (if necessary), so Production developers won't
 * accidentally try to work with the wrong module.
 *
 * @category    Toolbox
 * @package     PhoenixAttributes
 * @subpackage  Controllers
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.4
 * @since       File available since release 13.4
 * @author      Grou <jrubio@travelclick.com>
 * @filesource
 */
namespace PhoenixAttributes;

use Phoenix\Module\Module as PhoenixModule;

use Zend\Mvc\MvcEvent;

/**
 * PhoenixAttributes Module
 *
 * The Module Class for the Phoenix Attributes module
 *
 * Phoenix Attributes is one of five related modules, all with the Phoenix prefix to
 * separate them from existing modules (if necessary), so Production developers won't
 * accidentally try to work with the wrong module.
 *
 * @category    Toolbox
 * @package     PhoenixAttributes
 * @subpackage  Controllers
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.4
 * @since       File available since release 13.4
 * @author      Grou <jrubio@travelclick.com>
 */
class Module extends PhoenixModule
{
    protected $moduleNamespace = __NAMESPACE__;
    protected $moduleDirectory = __DIR__;

    public function onBootstrap(MvcEvent $e)
    {
        $eventManager = $e->getApplication()->getEventManager();
        //The priority is set to 101, so it will run before Toolbox\Phoenix::populateLayout()
        // $eventManager->attach(\Zend\Mvc\MvcEvent::EVENT_DISPATCH, array($this, 'addModuleToToolbox'), 101);

        /**
         * Module Migrations Support
         */
        $this->updateTableStructures($e);
    }

    /**
     * getServiceConfig
     *
     * Sets up the service factories for this module. Other things can be set in this array,
     * but this is all we are currently using it for.
     *
     * @return array The array of service factories
     */
    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'phoenix-attributes' => function ($sm) {
                    $service = new Service\Attributes();
                    $service->setCurrentUser($sm->get('phoenix-users-current'));
                    $service->setDefaultEntityManager($sm->get('doctrine.entitymanager.orm_default'));
                    $service->setAdminEntityManager($sm->get('doctrine.entitymanager.orm_admin'));
                    $service->setEventManager($sm->get('phoenix-eventmanager'));
                    $service->setConfig($sm->get('MergedConfig'));
                    $service->setServiceManager($sm);
                    return $service;
                },
            )
        );
    }

    public function addModuleToToolbox($e)
    {
        $serviceManager = $e->getApplication()->getServiceManager();
        $mergedConfig = $serviceManager->get('MergedConfig');
        //$AttributesService = $serviceManager->get('phoenix-attributes');
        $toolList = $mergedConfig->get(array('templateVars', 'toolList'));
        if (is_array($toolList)) {
            $toolList[] = array('key' => 'phoenixAttributes');
            $mergedConfig->set(array('templateVars', 'toolList'), $toolList);
        }
    }
}
