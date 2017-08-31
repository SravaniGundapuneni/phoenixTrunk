<?php

/**
 * The file for the SocialToolboxModule Class for the Dynamic List Module
 *
 * @category    Toolbox
 * @package     SocialToolboxModule
 * @subpackage  Service
 * @copyright   Copyright (c) 2014 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 1.14
 * @since       File available since release 1.14
 * @author      A. Tate <atate@travelclick.com>
 * @filesource
 */
 
 
namespace PhoenixToolbox;

use Phoenix\Module\Module as PhoenixModule;

class Module extends PhoenixModule
{
   protected $moduleNamespace=__NAMESPACE__;
   protected $moduleDirectory=__DIR__;
   
   public function getServiceConfig()
   {
   return array();
   }  

   public function getAutoloaderConfig()
   {
   
   return array('Zend\Loader\StandardAutoLoader'=>array(
          'namespaces' => array(__NAMESPACE__=>__DIR__.'/src/'.__NAMESPACE__,),
		  ),
		  );
   }
}

?>