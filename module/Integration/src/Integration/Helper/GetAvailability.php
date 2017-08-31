<?php

/**
 * The file for the GetAvailability Helper
 *
 * @category    Toolbox
 * @package     GetAvailability
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
 * The file for the GetAvailability Helper
 *
 * @category    Toolbox
 * @package     GetAvailability
 * @subpackage  Helper
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.4
 * @since       File available since release 13.4
 * @author      A. Tate <atate@travelclick.com>
 */

class GetAvailability extends AbstractHelper
{
    protected $phoenixProperties;
    protected $integrationManager;

    /**
     * Get the result template
     *
     * @param  array $params
     * @return array
     */
    protected function getResultTemplate($params)
    {
        return array(
            'params' => $params,
            'availability' => array()
        );
    }

    /**
     * Make sure we validate the params
     *
     * @param  array $params
     * @return array
     */
    protected function getParamsTemplate($params)
    {
        $result = false;

        if ((isset($params['start_date']) && strtotime($params['start_date']))
            && (isset($params['end_date']) && strtotime($params['end_date'])))
        {
            $result = array(
                'start_date'    => date('c', strtotime($params['start_date'])),
                'end_date'  => date('c', strtotime($params['end_date'])),
                'rooms'     => isset($params['rooms']) ? $params['rooms'] : 1,
                'total_adults'   => isset($params['total_adults']) ? $params['total_adults'] : 1,
                'total_children' => isset($params['total_children']) ? $params['total_children'] : 0,
                'guests'    => isset($params['guests']) ? $params['guests'] : 1,

            );
        }

        return $result;
    }

    /**
     * Get availability for all properties
     *
     * @param  array $params
     * @return array
     */
    public function __invoke($params,$theLanguage)
    {
        $result = $this->getResultTemplate($params);

        /**
         * Lets make sure the params is valid
         */
        if ( ! ($params = $this->getParamsTemplate($params)) )
        {
            $result['error'] = 'Please double check your parameters';
        }
        else
        {
            $properties = $this->view->getProperties();

            foreach ($properties as $key => $property)
            {
                $result['availability'][$property['id']] = $this->view->getPropertyAvailability($property['id'],$params,$theLanguage);

                /**
                 * Lets cleanup our availability object
                 */
                unset($result['availability'][$property['id']]['params']);
                unset($result['availability'][$property['id']]['rooms']);
                unset($result['availability'][$property['id']]['room_rates']);
                unset($result['availability'][$property['id']]['daily_charges']);
            }
        }

        return $result;
    }

    /**
     *  __invokeRegionalAvailabilityLogic function
     *
     * @access public
     * @param  mixed $params
     * @return mixed $result
     *
     */
    public function __invokeRegionalAvailabilityLogic($params)
    {
        $result = $this->getResutTemplate($params);

        /**
         * Lets make sure the params is valid
         */
        if ( ! ($params = $this->getParamsTemplate($params)) )
        {
            $result['error'] = 'Please double check your parameters';
        }
        else
        {
            $properties = $this->view->getProperties();

            if ( ! count( $params['hotel_codes'] ) )
            foreach ($properties as $key => $property)
            {
                $params['hotel_codes'][] = $property['code'];
            }

            if ( $regionalAvailability = $this->integrationManager->getRegionalAvailability($params) )
            {
                unset($params['hotel_codes'], $params['guests']);

                foreach ($regionalAvailability as $key => $property)
                {
                    if ($property->status_code == 'OPEN')
                    {
                        $availability = $this->view->getPropertyAvailability( $property->code, $params );
                        $result['availability'][$availability['id']] = $availability;
                        unset($result['availability'][$availability['id']]['information']);
                        unset($result['availability'][$availability['id']]['params']);
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