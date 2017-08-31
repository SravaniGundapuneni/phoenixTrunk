<?php
 /**
 * @package     Weather
 * @copyright   Copyright (c) 2015 TravelClick, Inc (http://www.travelclick.com) 
 * @license     All Rights Reserved
 * @version     Release: 3.14
 * @since       File available since release 3.14
 * @author      Jason Bowden <jbowden@travelclick.com> 
 * @filename    Module.php
 */

namespace Weather;
use Phoenix\Module\Module as PhoenixModule;
  
class Module extends PhoenixModule
{
    protected $moduleNamespace = __NAMESPACE__;
    protected $moduleDirectory = __DIR__;

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'phoenix-weather' => function ($sm) {
                    $service = new Service\Weather();
//                    $service->setCurrentUser($sm->get('phoenix-users-current'));
                    $service->setConfig($sm->get('MergedConfig'));
//                    $service->setCurrentLanguage($sm->get('currentLanguage'));
//                    $service->setLanguageService($sm->get('phoenix-languages'));
                    $service->setServiceManager($sm);
                    return $service;
                },
            )
        );
    }
}