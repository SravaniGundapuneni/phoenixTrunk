<?php
/**
 * Rooms Model
 *
 * @category      Toolbox
 * @package       PhoenixRooms
 * @subpackage    Model
 * @copyright     Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license       All Rights Reserved
 * @version       Release 13.4
 * @since         File available since release 13.4
 * @author        Igor Sorokin <isorokin@travelclick.com>
 * @filesource
*/

namespace Blocks\Model;

/**
 * Block Class
 *
 * @extends \ListModule\Model\ListItem
 *
 */

class Block extends \ListModule\Model\ListItem
{
    const BLOCK_ENTITY_NAME = 'Blocks\Entity\Block';
    const BLOCK_LINK_ENTITY_NAME = 'Blocks\Entity\Blocks';

    public function __construct()
    {
        $this->entityClass = self::BLOCK_ENTITY_NAME;
        parent::__construct();
    }

    //==================================================================================================================
    /**
    * getBlocks
    *
    * Gets the propertyRooms and getProperty 
    *
    * @return array
    */
    protected function getBlocks()
    {
        return $this->getItemArray('propertyRooms', 'getProperty');
    }
}