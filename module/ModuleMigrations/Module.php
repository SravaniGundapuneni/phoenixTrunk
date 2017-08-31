<?php
/**
 * Migrations Module
 *
 * The Module Class for the Migrations module
 *
 * Migrations module assists other modules with automatic db migrations
 *
 * @category    Toolbox
 * @package     Migrations
 * @subpackage  Controllers
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.4
 * @since       File available since release 13.4
 * @author      A. Tate <atate@travelclick.com>
 * @filesource
 */
namespace ModuleMigrations;

use Phoenix\Module\Module as PhoenixModule;

/**
 * Migrations Module
 *
 * The Module Class for the Migrations module
 *
 * Migrations module assists other modules with automatic db migrations
 *
 * @category    Toolbox
 * @package     Migrations
 * @subpackage  Controllers
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.4
 * @since       File available since release 13.4
 * @author      A. Tate <atate@travelclick.com>
 */
class Module extends PhoenixModule
{
    protected $moduleNamespace = __NAMESPACE__;
    protected $moduleDirectory = __DIR__;

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'phoenix-migrations' => function($sm) {
                    $service = new Service\ModuleMigrations();
                    $service->setDefaultEntityManager($sm->get('doctrine.entitymanager.orm_default'));
                    $service->setAdminEntityManager($sm->get('doctrine.entitymanager.orm_admin'));
                    $service->setEventManager($sm->get('phoenix-eventmanager'));
                    return $service;
                },
            )
        );
    }
}