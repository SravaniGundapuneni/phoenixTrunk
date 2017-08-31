<?php
namespace Integration\Helper;

use Zend\View\Helper\AbstractHelper; 

class GetPropertyAvailability extends AbstractHelper
{
    protected $phoenixProperties;
    protected $integrationManager;

    protected function getResutTemplate($propertyId, $params)
    {
        return array(
            'params' => $params,
            'id' => $propertyId,
            'code' => null,
            'name' => null,
            'availability' => false,
            'guarantee_type' => null,
            'information' => array(),
            'rooms' => array(),
            'rates' => array(),
            'room_rates' => array(),
            'daily_charges'  => array(),
            'cad'  => array(),
        );
    }

    protected function getParamsTemplate($params)
    {
        $result = false;

        if ((isset($params['start_date']) && strtotime($params['start_date']))
            && (isset($params['end_date']) && strtotime($params['end_date'])))
        {
            $result = array(
                'start_date'     => date('c', strtotime($params['start_date'])),
                'end_date'       => date('c', strtotime($params['end_date'])),
                'rooms'          => '1',
                'total_adults'   => isset($params['total_adults']) ? (int) $params['total_adults'] : 1,
                'total_children' => isset($params['total_children']) ?  (int) $params['total_children'] : 0,
                'summary_only'   => isset($params['summary_only']) ? (bool) $params['summary_only'] : true,
                'room_type'      => isset($params['room_type']) ? $params['room_type'] : null,
                'rate_code'      => isset($params['rate_code']) ? $params['rate_code'] : null,
                'promo_type'     => isset($params['promo_type']) ? $params['promo_type'] : null,
                'promo_code'     => isset($params['promo_code']) ? $params['promo_code'] : null,
                'iata_type'     => isset($params['iata_type']) ? $params['iata_type'] : null,
                'data_objects'   => isset($params['data_objects']) ? $params['data_objects'] : false,
                'currentLanguage'      => isset($params['currentLanguage']) ? $params['currentLanguage'] : 'en',
            );
        }

        return $result;
    }

    public function __invoke($propertyId, $params, $theLanguage)
    {
       
        $result = $this->getResutTemplate($propertyId, $params);

        if ($property = $this->phoenixProperties->getProperty($propertyId))
        {
            $result['id'] = $property->getId();
            $result['code'] = $property->getCode();
            $result['name'] = $property->getName();

            /**
             * get the hotel information
             */
            $result = array_replace_recursive($result,$this->view->getPropertyInformation($propertyId));

            if ( ! ($params = $this->getParamsTemplate($params)) )
            {
                $result['error'] = 'Please double check your parameters';
            }
            else
            {
                /**
                 * lets make sure that we set hotel_code
                 */
                $params['hotel_code'] = $result['code'];
                 
                // check if passing multiple promo codes.
                
                $multiplePromoCodeCheck = explode(',', $params['promo_code']);
                $multipleCount = count($multiplePromoCodeCheck) - 1;
                if(count($multiplePromoCodeCheck) > 1) {
                    foreach($multiplePromoCodeCheck as $key => $currentPromo) {
                        $params['promo_code'] = $currentPromo;
                            $availability = $this->integrationManager->getAvailability($params,$theLanguage);
                        if ( ! isset($availability['error']) AND count($availability['rooms']) > 0)
                        {
                            if($availability['general_availability'] == true && $key != $multipleCount) { 
                                continue; 
                                
                            }
                            $result['guarantee_type'] = $availability['guarantee_type'];

                               //     echo($currentLanguage["code"]); die();
                            //$filter['language'] = $language;

                            $property_rooms     = $this->view->getPropertyRooms($propertyId,$filter,$theLanguage);
                            $property_rates     = $this->view->getPropertyRates($propertyId,$filter,$theLanguage);
                            $property_addons    = $this->view->getPropertyAddons($propertyId,$filter,$theLanguage);

                            $result['rooms']    = isset($property_rooms['rooms']) ? $property_rooms['rooms'] : array();
                            $result['rates']    = isset($property_rates['rates']) ? $property_rates['rates'] : array();
                            $result['addons']   = isset($property_addons['addons']) ? $property_addons['addons'] : array();


                            /**
                             * Lets remove rooms not available
                             */
                            foreach ($result['rooms'] as $key => $room)
                            {
                                if ( isset($availability['rooms'][$room['code']]) )
                                {
                                    // get  current room code
                                    $availableRoom = $availability['rooms'][$room['code']];


                                    $result['rooms'][$key]['amenities']     = $availableRoom->amenities;

                                    foreach($result['rooms'][$key]['amenities'] as $amenity_key => $item)
                                    {
                                        $result['rooms'][$key]['amenities'][$amenity_key] = $item->toArray();

                                        if ( $params['data_objects'] )
                                        {
                                            $result['rooms'][$key]['amenities'][$amenity_key]['data_object'] = $item;
                                        }
                                    }

                                    if ( $params['data_objects'] )
                                    {
                                        $result['rooms'][$key]['data_object'] = $availableRoom;
                                    }
                                }
                                else
                                {
                                    unset($result['rooms'][$key]);
                                }
                            }

                            /**
                             * Lets remove rates not available
                             */
                            foreach ($result['rates'] as $key => $rate)
                            {
                                if ( isset($availability['rates'][$rate['code']]) )
                                {
                                    $result['rates'][$key]['addons'] = array();

                                    $availableRate = $availability['rates'][$rate['code']];

                                    $result['rates'][$key]['canceldate'] = $availableRate->canceldate;

                                    $packages = $this->integrationManager->getAvailablePackages($params, $rate['code']);

                                    foreach($result['addons'] as $addon_key => $addon)
                                    {
                                        if ( isset($packages[$addon['code']]) )
                                        {
                                            $availablePackage       = $packages[$addon['code']];
                                            $addon['currency']      = $availablePackage->currency;
                                            $addon['price']         = $availablePackage->price;
                                            $addon['tax']           = $availablePackage->tax;

                                            if ( $params['data_objects'] )
                                            {
                                                $addon['data_object']   = $availablePackage;
                                            }

                                            $result['rates'][$key]['addons'][] = $addon;
                                        }
                                    }

                                    if ( $params['data_objects'] )
                                    {
                                        $result['rates'][$key]['data_object'] = $availablePackage;
                                    }
                                }
                                else
                                {
                                    unset($result['rates'][$key]);
                                }
                            }

                            // check if this is a blockcode
                            if($params['promo_code'] != '' && count($result['rates']) == 0 && count($result['rooms']) > 0) {
                                // group/block code 
                                $buildcustomRate = 1;
                                    foreach ($availability['room_rates'] as $key => $item) {

                                        // get rate cancel date
                                       $theRateCancelDate =  $availability['rates']['']->canceldate;
                                       $result['rates'][$key]['addons'] = array();

                                    $availableRate = $item;
                                    $availableRate->rate_code = 'groupcode';

                                    $result['rates'][$key]['canceldate'] = $theRateCancelDate;
                                    $result['rates'][$key]['name'] = $availability['rates']['']->name;
                                    $result['rates'][$key]['code'] = $params['promo_code'];
                                    //$packages = $this->integrationManager->getAvailablePackagesBlocks($params, $rate['code']);
                                // create addons for the group code
                                    foreach($result['addons'] as $addon_key => $addon)
                                    {
                                        if ( isset($packages[$addon['code']]) )
                                        {
                                            $availablePackage       = $packages[$addon['code']];
                                            $addon['currency']      = $availablePackage->currency;
                                            $addon['price']         = $availablePackage->price;
                                            $addon['tax']           = $availablePackage->tax;

                                            if ( $params['data_objects'] )
                                            {
                                                $addon['data_object']   = $availablePackage;
                                            }

                                            $result['rates'][$key]['addons'][] = $addon;
                                        }
                                    }							

                                    }
                            }

                            /**
                             * Make sure we have rooms/rates
                             */
                            if ( $result['rooms'] && $result['rates'])
                            {
                                $result['availability'] = true;
                                $result['room_rates'] = $availability['room_rates'];
                                $result['daily_charges'] = $availability['daily_charges'];

                                foreach ($result['room_rates'] as $key => $item)
                                {
                                    $result['room_rates'][$key] = $item->toArray();

                                    if ( $params['data_objects'] )
                                    {
                                        $result['room_rates'][$key]['data_object'] = $item;
                                    }
                                }

                                foreach ($result['daily_charges'] as $key => $item)
                                {
                                    $result['daily_charges'][$key] = $item->toArray();

                                    if ( $params['data_objects'] )
                                    {
                                        $result['daily_charges'][$key]['data_object'] = $item;
                                    }
                                }
                            }
                            else
                            {
                               // $result['rooms'] = array();
                               // $result['rates'] = array();
                               // $result['room_rates'] = array();
                               // $result['daily_charges'] = array();
                            }

                            /**
                             * Unsetting the addons at this point since they dont need to be returned
                             */
                            unset($result['addons']);
                            break;
                            //
                        }
                        else
                        {
                            /**
                             * Parse the error message to return
                             */
                            continue;
                            $result['error'] = $availability['error'];
                            // $result['error'] = strstr($result['error'],PHP_EOL,true);
                            // $result['error'] = strip_tags(substr($result['error'],12));
                        }                            
                        
                    }
                } else {
                
                $availability = $this->integrationManager->getAvailability($params,$theLanguage);
                                if ( ! isset($availability['error']) )
                {
                    $result['guarantee_type'] = $availability['guarantee_type'];
                    
                       //     echo($currentLanguage["code"]); die();
                    //$filter['language'] = $language;

                    $property_rooms     = $this->view->getPropertyRooms($propertyId,$filter,$theLanguage);
                    $property_rates     = $this->view->getPropertyRates($propertyId,$filter,$theLanguage);
                    $property_addons    = $this->view->getPropertyAddons($propertyId,$filter,$theLanguage);

                    $result['rooms']    = isset($property_rooms['rooms']) ? $property_rooms['rooms'] : array();
                    $result['rates']    = isset($property_rates['rates']) ? $property_rates['rates'] : array();
                    $result['addons']   = isset($property_addons['addons']) ? $property_addons['addons'] : array();


                    /**
                     * Lets remove rooms not available
                     */
                    foreach ($result['rooms'] as $key => $room)
                    {
                        if ( isset($availability['rooms'][$room['code']]) )
                        {
                            // get  current room code
                            $availableRoom = $availability['rooms'][$room['code']];

                            
                            $result['rooms'][$key]['amenities']     = $availableRoom->amenities;

                            foreach($result['rooms'][$key]['amenities'] as $amenity_key => $item)
                            {
                                $result['rooms'][$key]['amenities'][$amenity_key] = $item->toArray();

                                if ( $params['data_objects'] )
                                {
                                    $result['rooms'][$key]['amenities'][$amenity_key]['data_object'] = $item;
                                }
                            }

                            if ( $params['data_objects'] )
                            {
                                $result['rooms'][$key]['data_object'] = $availableRoom;
                            }
                        }
                        else
                        {
                            unset($result['rooms'][$key]);
                        }
                    }
                    
                    /**
                     *  Get Full List of Packages For Availablility
                     */
                    $packages2 = $this->integrationManager->getAvailablePackages($params);
                    
                    
                    /**
                     * Lets remove rates not available
                     */
                    foreach ($result['rates'] as $key => $rate)
                    {
                        if ( isset($availability['rates'][$rate['code']]) )
                        {
                            $result['rates'][$key]['addons'] = array();

                            $availableRate = $availability['rates'][$rate['code']];
                            
                            $result['rates'][$key]['canceldate'] = $availableRate->canceldate;

                          //  $packages = $this->integrationManager->getAvailablePackages($params, $rate['code']);

                            foreach($result['addons'] as $addon_key => $addon)
                            {
                                if ( isset($packages2[$addon['code']]) )
                                {
                                    $availablePackage       = $packages2[$addon['code']];
                                    $addon['currency']      = $availablePackage->currency;
                                    $addon['price']         = $availablePackage->price;
                                    $addon['tax']           = $availablePackage->tax;

                                    if ( $params['data_objects'] )
                                    {
                                        $addon['data_object']   = $availablePackage;
                                    }

                                    $result['rates'][$key]['addons'][] = $addon;
                                }
                            }

                            if ( $params['data_objects'] )
                            {
                                $result['rates'][$key]['data_object'] = $availablePackage;
                            }
                        }
                        else
                        {
                            unset($result['rates'][$key]);
                        }
                    }
                    
                    // check if this is a blockcode
                    if($params['promo_code'] != '' && count($result['rates']) == 0 && count($result['rooms']) > 0) {
                        // group/block code 
                        $buildcustomRate = 1;
                            foreach ($availability['room_rates'] as $key => $item) {
                            
                                // get rate cancel date
                               $theRateCancelDate =  $availability['rates']['']->canceldate;
                               $result['rates'][$key]['addons'] = array();

                            $availableRate = $item;
                            $availableRate->rate_code = 'groupcode';
                            
                            $result['rates'][$key]['canceldate'] = $theRateCancelDate;
                            $result['rates'][$key]['name'] = $availability['rates']['']->name;
                            $result['rates'][$key]['code'] = $params['promo_code'];
                            $packages = $this->integrationManager->getAvailablePackagesBlocks($params, $rate['code']);
			// create addons for the group code
                            foreach($result['addons'] as $addon_key => $addon)
                            {
                                if ( isset($packages[$addon['code']]) )
                                {
                                    $availablePackage       = $packages[$addon['code']];
                                    $addon['currency']      = $availablePackage->currency;
                                    $addon['price']         = $availablePackage->price;
                                    $addon['tax']           = $availablePackage->tax;

                                    if ( $params['data_objects'] )
                                    {
                                        $addon['data_object']   = $availablePackage;
                                    }

                                    $result['rates'][$key]['addons'][] = $addon;
                                }
                            }							
							
                            }
                    }

                    /**
                     * Make sure we have rooms/rates
                     */
                    if ( $result['rooms'] && $result['rates'])
                    {
                        $result['availability'] = true;
                        $result['room_rates'] = $availability['room_rates'];
                        $result['daily_charges'] = $availability['daily_charges'];

                        foreach ($result['room_rates'] as $key => $item)
                        {
                            $result['room_rates'][$key] = $item->toArray();

                            if ( $params['data_objects'] )
                            {
                                $result['room_rates'][$key]['data_object'] = $item;
                            }
                        }

                        foreach ($result['daily_charges'] as $key => $item)
                        {
                            $result['daily_charges'][$key] = $item->toArray();

                            if ( $params['data_objects'] )
                            {
                                $result['daily_charges'][$key]['data_object'] = $item;
                            }
                        }
                    }
                    else
                    {
                       // $result['rooms'] = array();
                       // $result['rates'] = array();
                       // $result['room_rates'] = array();
                       // $result['daily_charges'] = array();
                    }

                    /**
                     * Unsetting the addons at this point since they dont need to be returned
                     */
                    unset($result['addons']);
                }
                else
                {
                    /**
                     * Parse the error message to return
                     */
                    $result['error'] = $availability['error'];
                    // $result['error'] = strstr($result['error'],PHP_EOL,true);
                    // $result['error'] = strip_tags(substr($result['error'],12));
                }
                }

            }
        }
        $serviceManager = $this->phoenixProperties->getServiceManager();
        $phoenixRates = $serviceManager->get('phoenix-rates');
        
        $currencyData = $phoenixRates->getCadCurrency();

       
        $result['cad'] = $currencyData;

        return $result;
    }

    public function __construct($phoenixProperties, $integrationManager)
    {
        $this->phoenixProperties = $phoenixProperties;
        $this->integrationManager = $integrationManager;
    }
}