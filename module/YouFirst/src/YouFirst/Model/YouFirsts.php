<?php
/**
 * YouFirst Model
 * 
 * @category      Toolbox
 * @package       YouFirsts
 * @subpackage    Model
 * @copyright     Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license       All Rights Reserved
 * @version       Release 13.5
 * @since         File available since release 13.5
 * @author        Kevin Davis <kedavis@travelclick.com>
 * @filesource
*/

namespace YouFirst\Model;

/**
 * YouFirst Model
 * 
 * @category      Toolbox
 * @package       YouFirsts
 * @subpackage    Model
 * @copyright     Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license       All Rights Reserved
 * @version       Release 13.5
 * @since         File available since release 13.5
 * @author        Kevin Davis <kedavis@travelclick.com>
*/

use ListModule\Model\ListItem;

 class YouFirsts
 {
   public function exchangeArray($loadArray = array())
   {
     return $this->loadFromArray($loadArray);
   }
   
   public function getArrayCopy()
   { 
     return $this->toArray();
   }
   
   public function edit()
   {
     
   
   }
   
   public function save()
   {
   
   
   }
 
 
 }




