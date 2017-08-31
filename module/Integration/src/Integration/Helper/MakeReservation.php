<?php

/**
 * The file for the MakeReservation Helper
 *
 * @category    Toolbox
 * @package     MakeReservation
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
 * The file for the MakeReservation Helper
 *
 * @category    Toolbox
 * @package     MakeReservation
 * @subpackage  Helper
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.4
 * @since       File available since release 13.4
 * @author      A. Tate <atate@travelclick.com>
 */

class MakeReservation extends AbstractHelper
{
    protected $phoenixProperties;
    protected $integrationManager;

    /**
     *  getResultTemplate function
     * 
     * @access protected
     * @param mixed $propertyId
     * @param mixed $params
     * @param mixed $behalf
     * @return array $propertyId
     *
    */

    protected function getResutTemplate($propertyId, $params, $behalf)
    {
        /**
         * Lets merge the params and behalf arrays
         */
        $params = array_merge($params, $behalf);

        return array(
            'params' => $params,
            'id' => $propertyId,
            'code' => null,
            'name' => null,
            'reservation' => array(),
            'confirmation_number' => null,
        );
    }

    /**
     *  getAddonsTemplate function
     * 
     * @access protected
     * @param mixed $addons
     * @return mixed $result
     *
    */

    protected function getAddonsTemplate($addons)
    {
        $result = array();

        foreach ($addons as $key => $addon)
        {
            $result[] = array(
                'addon_code' => isset($addon['addon_code']) ? $addon['addon_code'] : null,
                'quantity'   => isset($addon['quantity']) ? $addon['quantity'] : null,
            );
        }

        return $result;
    }


    /**
     *  getRoomsTemplate function
     * 
     * @access protected
     * @param mixed $rooms
     * @return mixed $result
     *
    */

    protected function getRoomsTemplate($rooms)
    {
        $result = array();

        foreach ($rooms as $key => $room)
        {
            $result[] = array(
                'units'       => isset($room['units']) ? $room['units'] : 1,
                'adults'      => isset($room['adults']) ? $room['adults'] : 1,
                'children'    => isset($room['children']) ? $room['children'] : 0,
                'room_code'   => isset($room['room_code']) ? $room['room_code'] : null,
                'rate_code'   => isset($room['rate_code']) ? $room['rate_code'] : null,
                'addons'      => $this->getAddonsTemplate($room['addons'])
            );
        }

        return $result;
    }

    /**
     *  getPackagesTemplate function
     * 
     * @access protected
     * @param mixed $rooms
     * @return mixed $result
     *
    */

    protected function getPackagesTemplate($rooms)
    {
        $result = array();

        foreach ($rooms as $key => $room)
        {
            $result[$key] = array();

            foreach ($room['addons'] as $addon)
            {
                $addon_code = $addon['addon_code'];
                $result[$key][$addon_code] = $addon['quantity'];
            }
        }

        return $result;
    }


    /**
     *  getParamsTemplate function
     * 
     * @access protected
     * @param mixed $rooms
     * @param mixed $behalf
     * @return mixed $result
     *
    */

    protected function getParamsTemplate($params, $behalf)
    {
        $result = false;

        if ((isset($params['start_date']) && strtotime($params['start_date']))
            && (isset($params['end_date']) && strtotime($params['end_date']))
            && isset($params['reservation_rooms'])
            && isset($params['person_id'])
            && isset($params['guarantee_type'])
            && isset($params['card_type'])
            && isset($params['card_number'])
            && isset($params['card_name'])
            && isset($params['card_year'])
            && isset($params['card_month']))
        {
            $parans['reservation_rooms'] = $this->getRoomsTemplate($params['reservation_rooms']);
            $parans['packages'] = $this->getPackagesTemplate($params['reservation_rooms']);

            $behalf = array(
                'enabled'       => isset($behalf['enabled']) ? $behalf['enabled'] : false,
                'title'         => isset($behalf['title']) ? $behalf['title'] : null,
                'first_name'    => isset($behalf['first_name']) ? $behalf['first_name'] : null,
                'last_name'     => isset($behalf['last_name']) ? $behalf['last_name'] : null,
                'email'         => isset($behalf['email']) ? $behalf['email'] : null,
                'phone'         => array(
                    'country_code'  => isset($behalf['phone']['country_code']) ? $behalf['phone']['country_code'] : null,
                    'area_code' => isset($behalf['phone']['area_code']) ? $behalf['phone']['area_code'] : null,
                    'number'    => isset($behalf['phone']['number']) ? $behalf['phone']['number'] : null,
                ),
            );

            $result = array(
                'start_date'            => $params['start_date'],
                'end_date'              => $params['end_date'],
                'reservation_rooms'     => $params['reservation_rooms'],
                'packages'              => $params['packages'],
                'person_id'             => $params['person_id'],
                'special_request'       => $params['special_request'],
                'guest_info'       => $params['guest_info'],
                'guarantee_type'        => $params['guarantee_type'],
                'iata_code'             => $params['iata_type'],
                'promo_type'            => $params['promo_type'],
                'promo_code'            => $params['promo_code'],
                'card_type'             => $params['card_type'],
                'card_number'           => $params['card_number'],
                'card_name'             => $params['card_name'],
                'card_year'             => $params['card_year'],
                'card_month'            => $params['card_month'],
                'behalf'                => $behalf,
            );
        }

        return $result;
    }

    /**
     *  __invoke function
     * 
     * @access public
     * @param mixed $rooms
     * @param array $behalf
     * @param mixed $dump_params
     * @return mixed $result
     *
    */

    public function __invoke($propertyId, $params, $behalf = array(), $dump_params = false)
    {
        $result = $this->getResutTemplate($propertyId, $params, $behalf);

        if ($property = $this->phoenixProperties->getProperty($propertyId))
        {
            $result['id'] = $property->getId();
            $result['code'] = $property->getCode();
            $result['name'] = $property->getName();

            if ( ! ($params = $this->getParamsTemplate($params, $behalf)) )
            {
                $result['error'] = 'Please double check your parameters';
            }
            else
            {
                /**
                 * Temporary to test the params templates
                 */
                if ( $dump_params ) return $params;

                /**
                 * lets make sure that we set hotel_code
                 */
                $params['hotel_code'] = $result['code'];

                foreach ($params['reservation_rooms'] as $key => $room)
                {
                    $params['leg']                  = $key;
                    $params['rooms']                = $room['units'];
                    $params['total_adults']         = $room['adults'];
                    $params['total_children']       = $room['children'];
                    $params['room_code']            = $room['room_code'];
                    $params['rate_code']            = $room['rate_code'];

                    /**
                     * for multiple rooms we need to pass the confirmation_number
                     */
                    $params['confirmation_number'] = $result['confirmation_number'];
                   

                    $reservation = $this->integrationManager->createBooking( $params );

                    if ( is_object($reservation) && ! is_array($reservation) )
                    {
                        /**
                         * Lets set the confirmaion number here
                         */
                        $confirmation_number = $reservation->confirmation_number;
                        $result['confirmation_number'] = $confirmation_number;
                        $result['reservation'][$key] = $reservation;
                    }
                    else
                    {
                        /**
                         * Parse the error message to return
                         */
                        $result['error'][$key] = $reservation['error'];
                        if(strpos($reservation['error'],'INVALID_RATE_CODE' ) !== false ) {
                            $params['promo_type'] = 'corporate-id';	
                            $reservation = $this->integrationManager->createBooking( $params );
                            
                            if ( is_object($reservation) && ! is_array($reservation) )
                            {
                                /**
                                 * Lets set the confirmaion number here
                                 */
                                $confirmation_number = $reservation->confirmation_number;
                                $result['confirmation_number'] = $confirmation_number;
                                $result['reservation'][$key] = $reservation;
                            }                            
                            
                            
                        }
                        // $result['error'] = strstr($result['error'],PHP_EOL,true);
                        // $result['error'] = strip_tags(substr($result['error'],12));
                    }
                }
            }
        }

        return $result;
    }

     /**
     *  __construct function
     * 
     * @access public
     * @param  mixed $phoenixProperties
     * @param  mixed $integrationManager
     *
    */

    public function __construct($phoenixProperties, $integrationManager)
    {
        $this->phoenixProperties = $phoenixProperties;
        $this->integrationManager = $integrationManager;
    }
}