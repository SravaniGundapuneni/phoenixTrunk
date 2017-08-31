<?php

/**
 * The file for the GetPropertyRooms Helper
 *
 * @category    Toolbox
 * @package     GetPropertyRooms
 * @subpackage  Helper
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.4
 * @since       File available since release 13.4
 * @author      A. Tate <atate@travelclick.com>
 * @filesource
 */

namespace Integration\Helper;

use Zend\View\Helper\AbstractHelper;

/**
 * The file for the GetPropertyRooms Helper
 *
 * @category    Toolbox
 * @package     GetPropertyRooms
 * @subpackage  Helper
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.4
 * @since       File available since release 13.4
 * @author      A. Tate <atate@travelclick.com>
 */

class GetPropertyRooms extends \ListModule\Helper\ItemInformation
{
    protected $phoenixProperties;
    protected $languages;

    /**
     *  getResultTemplate function
     * 
     * @access protected
     * @param mixed $propertyId
     * @return array $propertyId
     *
    */

    protected function getResutTemplate($propertyId)
    {
        return array(
            'id' => $propertyId,
            'code' => null,
            'name' => null,
            'rooms'=>array()
        );
    }


    /**
     *  getMainImage function
     * 
     * @access protected
     * @param mixed $item
     * @return mixed $result
     *
    */

    protected function getMainImage($item)
    {
        $result = null;

        if ( $mediaAttachmetns = $this->getMediaAttachments($item) )
        {
            $result = array_shift( $mediaAttachmetns );
        }

        return $result;
    }



    /**
     *  __invoke function
     * 
     * @access public
     * @param mixed $propertyId
     * @return mixed $result
     *
    */

    public function __invoke($propertyId, $filters = array(), $theLanguage)
    {

        $result = $this->getResutTemplate($propertyId);

        if ($property = $this->phoenixProperties->getProperty($propertyId))
        {
            // $integrationManager = $this->serviceManager->get('integration-manager');

            $result['id'] = $property->getId();
            $result['code'] = $property->getCode();
            $result['name'] = $property->getName();

            $serviceManager = $this->phoenixProperties->getServiceManager();

            $currentLanguage['code'] = $theLanguage;
            
            $phoenixRooms =  $serviceManager->get('phoenix-rooms');
            $filters['property'] = $property->getId();
            $filters['status'] = \ListModule\Model\ListItem::ITEM_STATUS_PUBLISHED;
            $rooms = array();

            $replacementOrderNumber = 1000000;
            $currentOrderNumber = 0;

            $roomItems = $phoenixRooms->getItemsBy($filters);

            foreach ($roomItems as $key => $item)
            {
                $itemOrderNumber = $item->getOrderNumber();
                $orderNumber = !empty($itemOrderNumber) || $itemOrderNumber === 0 ? $itemOrderNumber : $replacementOrderNumber;

                if (isset($rooms[$orderNumber])) {
                    $currentOrderNumber = $orderNumber + 1;
                } else {
                    $currentOrderNumber = $orderNumber;
                }


                $roomArray = $item->getArrayCopy();
                $currentRoomName = $roomArray['name'];
                if($theLanguage == 'fr') {
                    $currentRoomName = $item->getName_fr();
                    if($currentRoomName == '') {
                        $currentRoomName = $roomArray['name_en'];
                    }
                }
                $currentRoomDescription = $roomArray['description'];
                 if($theLanguage == 'fr') {
                    $currentRoomDescription = $item->getDescription_fr();
                    if($currentRoomDescription == '') {
                        $currentRoomDescription = $roomArray['description_en'];
                    }
                }

                $rooms[$currentOrderNumber] = array(
                    'id' => $roomArray['id'],
                    'code' => $roomArray['code'],
                    'name' => $currentRoomName,
                    'intro' => $currentRoomDescription,
                    'name_en' => $roomArray['name_en'],
                    'intro_en' => $roomArray['description_en'],
                    'occupancy' => $roomArray['maxOccupancy'],
                    'mainImage' => $this->getMainImage($item),
                    'virtualTour' => $roomArray['virtualTour'],
                    'description' => $roomArray['description'],
                    'attachments' => $this->getMediaAttachments($item),
                    'amenities' => array(),
                );

                $replacementOrderNumber++;
            }
            ksort($rooms);

            $result['rooms'] = $rooms;
        }

        return $result;
    }

    /**
     *  __construct function
     * 
     * @access public
     * @param  mixed $phoenixProperties
     *
    */

    public function __construct($phoenixProperties)
    {
        $this->phoenixProperties = $phoenixProperties;
    }
}