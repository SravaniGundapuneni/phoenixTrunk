<?php
/**
 * Rooms Model
 *
 * @category        Toolbox
 * @package         PhoenixRooms
 * @subpackage      Model
 * @copyright       Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license         All Rights Reserved
 * @version         Release 13.4
 * @since           File available since release 13.4
 * @author          Kevin Davis <kedavis@travelclick.com>
 * @filesource
 */

namespace PhoenixRooms\Model;

use ListModule\Model\UnifiedListItem;

class Room extends UnifiedListItem
{
    const ENTITY_NAME = 'PhoenixRooms\Entity\PhoenixRoomsItems';
    const ROOM_DATA_ENTITY = 'phoenixRoomsData';
    protected $dataEntityClass = 'PhoenixRooms\Entity\PhoenixRoom';      

    public function __construct($config = array(), $fields = array())
    {
        $this->entityClass = self::ENTITY_NAME;
        $this->dataEntity = self::ROOM_DATA_ENTITY;        
        parent::__construct($config, $fields);
    }

    /**
     * Pass in the extra data to forms
     * @param  boolean $datesToString
     * @return array
     */
    public function getArrayCopy($datesToString = false)
    {
        $arrayCopy = parent::getArrayCopy($datesToString);
        $arrayCopy['hotelName'] = $this->getProperty()->getName();
        return $arrayCopy;
    }

    public function isCorporate($displayYesNo=false)
    {
        $isCorporate = $this->getProperty()->getIsCorporate();
        if ($displayYesNo) $isCorporate = $this->getYesNo($isCorporate);
        return $isCorporate;
    }
}