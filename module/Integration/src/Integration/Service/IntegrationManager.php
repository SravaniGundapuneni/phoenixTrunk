<?php

/**
 * The file for the IntegrationManager Service
 *
 * @category    Toolbox
 * @package     IntegrationManager
 * @subpackage  Service
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.4
 * @since       File available since release 13.4
 * @author      A. Tate <atate@travelclick.com>
 * @filesource
 */

namespace Integration\Service;

use Phoenix\Service\ServiceAbstract;
use Phoenix\EventManager\Event as PhoenixEvent;
use Integration\Model\ImportResult;

/**
 * The file for the IntegrationManager Service
 *
 * @category    Toolbox
 * @package     IntegrationManager
 * @subpackage  Service
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.4
 * @since       File available since release 13.4
 * @author      A. Tate <atate@travelclick.com>
 */
class IntegrationManager extends ServiceAbstract
{

    protected $eventManager;
    protected static $phoenixProperties;
    protected static $phoenixAttributes;
    protected static $phoenixAddons;
    protected static $phoenixRooms;
    protected static $phoenixRates;

    /**
     *  setPhoenixProperties function
     * 
     * @access public
     * @param  mixed $phoenixProperties
     *
     */
    public function setPhoenixProperties($phoenixProperties)
    {
        self::$phoenixProperties = $phoenixProperties;
    }

    /**
     *  setPhoenixAttributes function
     * 
     * @access public
     * @param  mixed $phoenixAttributes
     *
     */
    public function setPhoenixAttributes($phoenixAttributes)
    {
        self::$phoenixAttributes = $phoenixAttributes;
    }

    /**
     *  setPhoenixRooms function
     * 
     * @access public
     * @param  mixed $phoenixRooms
     *
     */
    public function setPhoenixRooms($phoenixRooms)
    {
        self::$phoenixRooms = $phoenixRooms;
    }

    /**
     *  setPhoenixAddons function
     * 
     * @access public
     * @param  mixed $phoenixAddons
     *
     */
    public function setPhoenixAddons($phoenixAddons)
    {
        self::$phoenixAddons = $phoenixAddons;
    }

    /**
     *  setPhoenixRates function
     * 
     * @access public
     * @param  mixed $phoenixRates
     *
     */
    public function setPhoenixRates($phoenixRates)
    {
        self::$phoenixRates = $phoenixRates;
    }

    /**
     *  getHotels function
     * 
     * @access public
     * @return  mixed \owsWebServiceInformation::getInstance()->getHotelsInfo()
     *
     */
    public function getHotels()
    {
        return \owsWebServiceInformation::getInstance()->getHotelsInfo();

        // return array(
        //     'PR1' => (object) array('code' => 'PR1', 'name' => 'Property One', 'description' => 'This is the ultimate hotel property')
        // );
    }

    /**
     *  getLanguages function
     * 
     * @access public
     * @return  mixed \owsWebServiceInformation::getInstance()->getLanguages()
     *
     */
    public function getLanguages()
    {

         return array(
             'E' => 'English',
             'FR' => 'French'
         );
    }

    /**
     *  getProperties function
     * 
     * @access public
     * @return  mixed $properties
     *
     */
    public function getProperties()
    {
        $properties = array();

        $siteProperties = self::$phoenixProperties->getItemsArray();

        foreach ($siteProperties as $propertyArrayKey => $property) {
            if (isset($property['code']) && isset($property['name']))
                $properties[$property['code']] = $property['name'];
        }

        return $properties;
    }

    public function getHotelsByRateCode($rateCode)
    {

        $result = $this->defaultEntityManager->getConnection()->fetchAll("
                SELECT * FROM phoenixRates pt WHERE pt.code = '$rateCode'"
        );
        $hotelList = array();
        foreach ($result as $individualRate) {
            if (!in_array($individualRate['property'], $hotelList))
                $hotelList[] = $individualRate['property'];
        }


        return $hotelList;
    }
    
    public function getCurrency($amount = 0, $fromCurrency = 'USD', $toCurrency = 'USD') {


         $result = $this->adminEntityManager->getConnection()->fetchAll("
                SELECT dollarequiv FROM basecurrency pt WHERE pt.currency = '$toCurrency'"
        );       
        
        
        
        $currentConversion = $result[0]['dollarequiv'];
        $convertedAmount = $amount * $currentConversion;
        return number_format($convertedAmount, 2, '.', '');

       
    }
    
    public function getMultiRate($rateCode, $theHotel)
    {

        $result = $this->defaultEntityManager->getConnection()->fetchAll("
                SELECT * FROM phoenixRates pt WHERE pt.code LIKE '%$rateCode%'"
        );
        $rateList = array();
        foreach ($result as $individualRate) {

                $rateList[] = $individualRate['code'];
        }


        return $rateList;
    }


    /**
     *  getAvailablePackages function
     * 
     * @access public
     * @param  mixed $params
     * @param  mixed $rate_code
     * @return  mixed $result
     *
     */
    public function getAvailablePackages($params, $rate_code)
    {
        $result = array();

        $params['rate_code'] = $rate_code;

        try {
            $result = \owsWebServiceAvailability::getInstance()->fetchAvailablePackages($params);
        } catch (\Exception $e) {
            $msg = $e->getMessage();
           // $result = array();
            if( strpos( $e->getMessage(), 'INVALID' ) !== false ) {
                $params['promo_type'] = 'group-code';
                $params['rate_code'] = '';
                $result = $this->getAvailablePackagesBlocks($params,$rate_code);               
            }


        }

        return $result;
    }

    public function getAvailablePackagesBlocks($params, $rate_code)
    {
        $result = array();

     //   $params['rate_code'] = $rate_code;

        try {
            $result = \owsWebServiceAvailability::getInstance()->fetchAvailablePackages($params);
        } catch (\Exception $e) {

            $result = array();

        }

        return $result;
    }    
    
    /**
     *  getAvailability function
     * 
     * @access public
     * @param  mixed $params
     * @return  mixed $result
     *
     */
    public function getAvailability($params)
    {
        $result = array();

        try {
            $result = \owsWebServiceAvailability::getInstance()->availability($params);
        } catch (\Exception $e) {
            $result = array(
                'rooms' => array(),
                'rates' => array(),
                'room_rates' => array(),
                'dialy_charges' => array(),
                'guarantee_type' => null,
                'general_availability' => array(),
                'error' => $e->getMessage()
            );
        }

        return $result;
    }

    /**
     *  getRegionalAvailability function
     * 
     * @access public
     * @param  mixed $params
     * @return  mixed $result
     *
     */
    public function getRegionalAvailability($params)
    {
        $result = array();

        try {
            $result = \owsWebServiceAvailability::getInstance()->regionalAvailability($params);
        } catch (\Exception $e) {
            $result = array(
                'rooms' => array(),
                'rates' => array(),
                'room_rates' => array(),
                'error' => $e->getMessage()
            );
        }

        return $result;
    }

    /**
     *  createBooking function
     * 
     * @access public
     * @param  mixed $params
     * @return  mixed $result
     *
     */
    public function createBooking($params)
    {
        $result = array();

        try {
            $result = \owsWebServiceReservation::getInstance()->createBooking($params);
        } catch (\Exception $e) {
            $result = array(
                'error' => $e->getMessage()
            );
        }

        return $result;
    }

    public function modifyBooking($params)
    {
        $result = array();

        try {
            $result = \owsWebServiceReservation::getInstance()->modifyBooking($params);
        } catch (\Exception $e) {
            $result = array(
                'error' => $e->getMessage()
            );
        }

        return $result;
    }

    /**
     *  getHotelInfo function
     * 
     * @access public
     * @static
     * @param  mixed $params
     * @param  mixed $language
     * @return  mixed $result
     *
     */
    public static function getHotelInfo($code, $language = null)
    {
        $result = false;

        try {
            $result = \owsWebServiceInformation::getInstance()->getHotelInfo($code, $language);
        } catch (\Exception $e) {
            $result = false;
        }

        return $result;
    }

    /**
     *  getHotelInfo function
     * 
     * @access public
     * @static
     * @param  mixed $code
     * @param  mixed $language
     * @return  mixed $result
     *
     */
    public static function getHotelAddons($code, $language = null)
    {
        $result = array();

        try {
            $result = \owsWebServiceInformation::getInstance()->getHotelAddons($code, $language);
        } catch (\Exception $e) {
            $result = array();
        }

        return $result;
    }

    /**
     *  getHotelRooms function
     * 
     * @access public
     * @static
     * @param  mixed $code
     * @param  mixed $language
     * @return  mixed $result
     *
     */
    public static function getHotelRooms($code, $language = null)
    {
        $result = array();

        try {
            $result = \owsWebServiceInformation::getInstance()->getHotelRoomTypes($code, $language);
        } catch (\Exception $e) {
            $result = array();
        }

        return $result;
    }

    /**
     *  getHotelRates function
     * 
     * @access public
     * @static
     * @param  mixed $code
     * @param  mixed $language
     * @return  mixed $result
     *
     */
    public static function getHotelRates($code, $language = null)
    {
        $result = array();

        try {
            $result = \owsWebServiceInformation::getInstance()->getHotelRates($code, $language);

            foreach ($result as $rateIndex => $rate) {
                $result[$rateIndex] = \owsWebServiceInformation::getInstance()->getRate($code, $rateIndex, $language);
            }
        } catch (\Exception $e) {
            $result = array();
        }

        return $result;
    }

    /**
     *  isHotelAlreadyImported function
     * 
     * @access public
     * @static
     * @param  mixed $code
     * @param  mixed $language
     * @return  mixed self::$phoenixProperties->getProperty($code, $language)
     *
     */
    public static function isHotelAlreadyImported($code, $language)
    {
        return self::$phoenixProperties->getProperty($code, $language);
    }

    /**
     *  isHotelAddonAlreadyImported function
     * 
     * @access public
     * @static
     * @param  mixed $code
     * @param  mixed $language
     * @return  mixed self::$phoenixAddons->getAddon(array('code'=>$code, 'property'=>$hotelId))
     *
     */
    public static function isHotelAddonAlreadyImported($hotelId, $code, $language)
    {
        return self::$phoenixAddons->getAddon(array('code' => $code, 'property' => $hotelId));
    }

    /**
     *  isHotelAddonAlreadyImported function
     * 
     * @access public
     * @static
     * @param  mixed $hotelId
     * @param  mixed $code
     * @param  mixed $language
     * @return  mixed self::$phoenixAddons->getAddon(array('code'=>$code, 'property'=>$hotelId))
     *
     */
    public static function isHotelRoomAlreadyImported($hotelId, $code, $language)
    {
        return self::$phoenixRooms->getRoom(array('code' => $code, 'property' => $hotelId));
    }

    /**
     *  isHotelRateAlreadyImported function
     * 
     * @access public
     * @static
     * @param  mixed $hotelId
     * @param  mixed $code
     * @param  mixed $language
     * @return mixed self::$phoenixRates->getRate(array('code'=>$code, 'property'=>$hotelId))
     *
     */
    public static function isHotelRateAlreadyImported($hotelId, $code, $language)
    {
        return self::$phoenixRates->getRate(array('code' => $code, 'property' => $hotelId));
    }

    /**
     *  canHotelRoomBeUpdated function
     * 
     * @access public
     * @static
     * @param  mixed $hotel_code
     * @param  mixed $code
     * @param  mixed $options
     * @return mixed self::canHotelBeImportedOrUpdated($hotel_code, $options) && $options['Override']
     *
     */
    public static function canHotelRoomBeUpdated($hotel_code, $code, $options)
    {
        return self::canHotelBeImportedOrUpdated($hotel_code, $options) && $options['Override'];
    }

    /**
     *  canHotelRateBeUpdated function
     * 
     * @access public
     * @static
     * @param  mixed $hotel_code
     * @param  mixed $code
     * @param  mixed $options
     * @return mixed self::canHotelBeImportedOrUpdated($hotel_code, $options) && $options['Override']
     *
     */
    public static function canHotelRateBeUpdated($hotel_code, $code, $options)
    {
        return self::canHotelBeImportedOrUpdated($hotel_code, $options) && $options['Override'];
    }

    /**
     *  canHotelAddonBeUpdated function
     * 
     * @access public
     * @static
     * @param  mixed $hotel_code
     * @param  mixed $code
     * @param  mixed $options
     * @return mixed self::canHotelBeImportedOrUpdated($hotel_code, $options) && $options['Override']
     *
     */
    public static function canHotelAddonBeUpdated($hotel_code, $code, $options)
    {
        return self::canHotelBeImportedOrUpdated($hotel_code, $options) && $options['Override'];
    }

    /**
     *  canHotelBeImportedOrUpdated function
     * 
     * @access public
     * @static
     * @param  mixed $code
     * @param  array $options
     * @return array in_array( $code, $options['Hotel'] ) || in_array( "0", $options['Hotel'], true )
     *
     */
    public function canHotelBeImportedOrUpdated($code, $options = array())
    {
        return in_array($code, $options['Hotel']) || in_array("0", $options['Hotel'], true);
    }

    /**
     *  matchesFilterCriterias function
     * 
     * @access public
     * @static
     * @param  mixed $code
     * @param  mixed $options
     * @return mixed count( $options['Criteria'] ) === 0 || in_array( $code, $options['Criteria'] )
     *
     */
    public static function matchesFilterCriterias($code, $options)
    {
        $options['Criteria'] = array_filter(array_map('trim', explode(',', $options['Criteria'])));
        return count($options['Criteria']) === 0 || in_array($code, $options['Criteria']);
    }

    /**
     *  importHotel function
     * 
     * @access public
     * @static
     * @param  mixed $code
     * @param  mixed $language
     * @param  array $options
     * @return mixed $result
     *
     */
    public static function importHotel($code, $language, $options = array(), $requireApproval = true)
    {
        try {
            $result = new ImportResult(
                    array(
                'code' => $code,
                'language' => $language,
                'start_time' => time()
                    )
            );

            if ($result->data_object = self::getHotelInfo($code, $language)) {
                $data['code'] = $result->code;
                
                if($result->code == 'LVOG') {
                    
                    $theHotelFound = true;

                }

               if($language[0] == 'FR') {
                    
                    $data['name_fr'] = $result->name;
                    $data['description_fr'] = $result->description;
                    $data['address_fr'] = $result->address;
                    $data['city_fr'] = $result->city;
                    $data['state_fr'] = $result->state_province;     
                    $options['Approve'][0] = $result->code;
                    
                } else {
                    $data['name'] = $result->name;
                    $data['description'] = $result->description;
                    $data['address'] = $result->address;
                    $data['city'] = $result->city;
                    $data['state'] = $result->state_province;  
                }
                


                
                
                $data['zip'] = $result->zip;
                $data['country'] = $result->country;
                $data['phoneNumber'] = $result->phone_number;
                $data['faxNumber'] = $result->fax_number;
                $data['email'] = $result->email;
                $serializedData = serialize($data);

                $data['owsData'] = $serializedData;
                $data['userModified'] = 0;

                if ($entityModel = self::isHotelAlreadyImported($code, $language)) {
                    if (self::canHotelBeImportedOrUpdated($code, $options) && self::hasOwsChanges($entityModel, $serializedData)) {
                        if (in_array($code, $options['Approve'])) {
                            /**
                             * Data coming from the OWS Service
                             */
                            self::$phoenixProperties->save($entityModel, $data);
                        }

                        if ($options['Recursive']) {
                            // self::importHotelAttributes( $code, $language, $options );
                            self::importHotelAddons($code, $language, $options, false);
                            self::importHotelRooms($code, $language, $options, false);
                            self::importHotelRates($code, $language, $options);
                        }

                        $result->setStatus(($entityModel->getUserModified() ? ImportResult::STATUS_OVERWRITE : ImportResult::STATUS_UPDATED));
                    } else {
                        $result->setStatus(ImportResult::STATUS_SKIPPED, 'Already imported, but should not be updated');
                    }
                } else {
                    if (self::matchesFilterCriterias($code, $options) === false) {
                        $result->setStatus(ImportResult::STATUS_SKIPPED, 'Not passed the filter criterias');
                    } else {
                        if (( $options['DryRun'] !== true ) && (in_array($code, $options['Approve']))) {
                            /**
                             * Data coming from the OWS Service
                             */
                            self::$phoenixProperties->createProperty($data, true);
                        }
                        if ($options['Recursive']) {
                            // self::importHotelAttributes( $code, $language, $options );
                            self::importHotelAddons($code, $language, $options);
                            self::importHotelRooms($code, $language, $options);
                            self::importHotelRates($code, $language, $options);
                        }

                        $result->setStatus(ImportResult::STATUS_CREATED);
                    }
                }
            } else {
                $result->setStatus(ImportResult::STATUS_SKIPPED, 'OWS did not return any info for this hotel code');
            }
        } catch (Exception $e) {
            $result = new ImportResult(
                    array(
                'code' => $code,
                'language' => $language,
                'start_time' => time()
                    )
            );
        }

        return $result;
    }

    /**
     *  importHotelAddons function
     * 
     * @access public
     * @static
     * @param  mixed $code
     * @param  mixed $language
     * @param  array $options
     * @return mixed $results
     *
     */
    public static function importHotelAddons($code, $language, $options = array(), $requireApproval = true)
    {
        $results = array();

        if ($hotel = self::getHotelInfo($code, $language)) {
            $addons = self::getHotelAddons($code, $language);

            foreach ($addons as $addonIndex => $addon) {

                if (substr($addon->code, 0, 4) == 'OWS-') {
                     // $addon->code = substr($addon->code, 4);
                    try {
                        $result = new ImportResult(
                                array(
                            'hotel_code' => $hotel->code,
                            'hotel_name' => $hotel->name,
                            'language' => $language,
                            'start_time' => time()
                                )
                        );

                        $result->data_object = $addon;
                        $result->description = substr($result->description, 4);
                        $data = array();
                        if($language[0] == 'FR') {
                        $data['name_fr'] = $result->name;
                        $data['description_fr'] = $result->description;
                        $options['Approve'][0] = $result->code;
                            
                        } else {
                        $data['name'] = $result->name;
                        $data['description'] = $result->description;                            
                        }
                        $data['code'] = $result->code;

                        $data['price'] = $result->price;
                        $data['currency'] = $result->currency;
                        $data['tax'] = $result->tax;

                        $serializedData = serialize($data);
                        $data['owsData'] = $serializedData;
                        $data['userModified'] = 0;
                        $hotelModel = self::isHotelAlreadyImported($code, $language);

                        if ($hotelModel && self::matchesFilterCriterias($addon->code, $options)) {
                            if ($entityModel = self::isHotelAddonAlreadyImported($hotelModel->getId(), $addon->code, $language)) {
                                if (self::canHotelAddonBeUpdated($code, $addon->code, $options) && self::hasOwsChanges($entityModel, $serializedData)) {
                                    if (($options['DryRun'] !== true) && ((in_array($addon->code, $options['Approve']) || (!$requireApproval)))) {
                                        /**
                                         * Data coming from the OWS Service
                                         */
                                        self::$phoenixAddons->save($entityModel, $data);
                                    }

                                    $result->setStatus(($entityModel->getUserModified() ? ImportResult::STATUS_OVERWRITE : ImportResult::STATUS_UPDATED));
                                } else {
                                    $result->setStatus(ImportResult::STATUS_SKIPPED, 'Already imported, but should not be updated');
                                }
                            } else {
                                if (($options['DryRun'] !== true) && ((in_array($addon->code, $options['Approve']) || (!$requireApproval)))) {


                                    $hotelModel->addAddon(self::$phoenixAddons->createAddon($data, true));
                                }

                                $result->setStatus(ImportResult::STATUS_CREATED);
                            }

                            $hotelModel->save();
                            $results[] = $result;
                        } else {
                            $result->setStatus(ImportResult::STATUS_SKIPPED, 'Not passed the filter criterias');
                        }
                    } catch (\Exception $e) {
                        $results[] = new ImportResult(
                                array(
                            'hotel_code' => $hotel->code,
                            'hotel_name' => $hotel->name,
                            'language' => $language,
                            'status' => ImportResult::STATUS_SKIPPED,
                            'description' => 'OWS Error'
                                )
                        );
                    }
                }
            }
        }

        return $results;
    }

    /**
     *  importHotelRooms function
     * 
     * @access public
     * @static
     * @param  mixed $code
     * @param  mixed $language
     * @param  array $options
     * @return mixed $results
     *
     */
    public static function importHotelRooms($code, $language, $options = array(), $requireApproval = true)
    {
        $results = array();
        $options['DryRun'] = false;
        if ($hotel = self::getHotelInfo($code, $language)) {
            $rooms = self::getHotelRooms($code, $language);

            foreach ($rooms as $roomIndex => $room) {
                try {
                    $result = new ImportResult(
                            array(
                        'hotel_code' => $hotel->code,
                        'hotel_name' => $hotel->name,
                        'language' => $language,
                        'start_time' => time()
                            )
                    );

                    $result->data_object = $room;

                    $data = array();
                    $data['code'] = $result->code;
                    
                    if($language[0] == 'FR') {
                        
                        $data['name_fr'] = $result->name;
                        $data['description_fr'] = $result->description;
                        $options['Approve'][0] = $result->code;
                            
                     } else {
                        $data['name'] = $result->name;
                        $data['description'] = $result->description;
                     }
                    $serializedData = serialize($data);
                    $data['owsData'] = $serializedData;
                    $data['userModified'] = 0;
                    

                    $hotelModel = self::isHotelAlreadyImported($code, $language);
                    
                    //$data['property'] = $hotelModel->getId();

                    if ($hotelModel && self::matchesFilterCriterias($room->code, $options)) {
                        if ($entityModel = self::isHotelRoomAlreadyImported($hotelModel->getId(), $room->code, $language)) {
                            if (self::canHotelRoomBeUpdated($code, $room->code, $options) && self::hasOwsChanges($entityModel, $serializedData)) {
                                if (($options['DryRun'] !== true) && ((in_array($room->code, $options['Approve']) || (!$requireApproval)))) {
                                    /**
                                     * Data coming from the OWS Service
                                     */
                                    self::$phoenixRooms->save($entityModel, $data);
                                }

                                $result->setStatus(($entityModel->getUserModified() ? ImportResult::STATUS_OVERWRITE : ImportResult::STATUS_UPDATED));
                            } else {
                                $result->setStatus(ImportResult::STATUS_SKIPPED, 'Already imported, but should not be updated');
                            }
                        } else {
                            if (($options['DryRun'] !== true) && ((in_array($room->code, $options['Approve']) || (!$requireApproval)))) {
                                /**
                                 * Data coming from the OWS Service
                                 */
                                $data['property'] = $hotelModel->getEntity();
                                /**
                                 * Data coming from the OWS Service
                                 */
                                $data['owsData'] = $serializedData;
                                $data['userModified'] = 0;                                
                                $hotelModel->addRoom(self::$phoenixRooms->createRoom($data, true));
                            }

                            $result->setStatus(ImportResult::STATUS_CREATED);
                        }

                        $hotelModel->save();
                        $results[] = $result;
                    } else {
                        $result->setStatus(ImportResult::STATUS_SKIPPED, 'Not passed the filter criterias');
                    }
                } catch (\Exception $e) {
                    $results[] = new ImportResult(
                            array(
                        'hotel_code' => $hotel->code,
                        'hotel_name' => $hotel->name,
                        'language' => $language,
                        'status' => ImportResult::STATUS_SKIPPED,
                        'description' => 'OWS Error'
                            )
                    );
                }
            }
        }

        return $results;
    }

    /**
     *  importHotelRates function
     * 
     * @access public
     * @static
     * @param  mixed $code
     * @param  mixed $language
     * @param  array $options
     * @return mixed $results
     *
     */
    public static function importHotelRates($code, $language, $options = array(), $requireApproval = true)
    {
        $results = array();

        if ($hotel = self::getHotelInfo($code, $language)) {
            
            
            if($options['Criteria'] != '') {
                try {
                    $rates[1] = \owsWebServiceInformation::getInstance()->getRate($code, $options['Criteria'], $language);
                } catch (Exception $ex) {
                    $theErrorMsg = $e->getMessage();
                }
                
                            
               // $rates = json_decode($rates);
            } else if( count($options['Approve']) == 1) {
                try {
                    $importRateCode = $options['Approve'][0];
                    $rates[1] = \owsWebServiceInformation::getInstance()->getRate($code, $importRateCode, $language);
                } catch (Exception $ex) {
                    $theErrorMsg = $e->getMessage();
                }
            } else {
                $rates = self::getHotelRates($code, $language);
                
            }

            foreach ($rates as $rateIndex => $rate) {
                try {
                    $result = new ImportResult(
                            array(
                        'hotel_code' => $hotel->code,
                        'hotel_name' => $hotel->name,
                        'language' => $language,
                        'start_time' => time()
                            )
                    );

                    $result->data_object = $rate;

                    $data = array();
                    $data['code'] = $result->code;
                    if($language[0] == 'FR') {
                        
                        $data['name_fr'] = $result->name;
                        $data['description_fr'] = $result->description;
                        $options['Approve'][0] = $result->code;
                            
                     } else {
                        $data['name'] = $result->name;
                        $data['description'] = $result->description;
                     }
                    $data['rateTypeCategory'] = $result->category;
                    $data['policy'] = $result->policy;
                    $data['terms'] = $result->terms;
                    $serializedData = serialize($data);
                    //echo $serializedData.'<br /><br />';
                    $hotelModel = self::isHotelAlreadyImported($code, $language);

                    if ($hotelModel && self::matchesFilterCriterias($rate->code, $options)) {
                        if ($entityModel = self::isHotelRateAlreadyImported($hotelModel->getId(), $rate->code, $language)) {
                            /**
                             * Data coming from the OWS Service
                             */
                            if (self::canHotelRateBeUpdated($code, $rate->code, $options) && self::hasOwsChanges($entityModel, $serializedData)) {

                                if (in_array($rate->code, $options['Approve']) || (!$requireApproval)) {
                                    $data['owsData'] = $serializedData;
                                    $data['userModified'] = 0;
                                    self::$phoenixRates->save($entityModel, $data);
                                }

                                if ($options['Recursive']) {
                                    // $result->children = self::updateRecursiveContent( $code, $language, $options );
                                }

                                $result->setStatus(($entityModel->getUserModified() ? ImportResult::STATUS_OVERWRITE : ImportResult::STATUS_UPDATED));
                            } else {
                                $result->setStatus(ImportResult::STATUS_SKIPPED, 'Already imported, but should not be updated');
                            }
                        } else {
                            if (($options['DryRun'] !== true) && ((in_array($rate->code, $options['Approve']) || (!$requireApproval)))) {
                                $data['property'] = $hotelModel->getEntity();
                                /**
                                 * Data coming from the OWS Service
                                 */
                                $data['owsData'] = $serializedData;
                                $data['userModified'] = 0;
                                $hotelModel->addRate(self::$phoenixRates->createRate($data, true));
                            }

                            if ($options['Recursive']) {
                                // $result->children = self::updateRecursiveContent( $code, $language, $options );
                            }

                            $result->setStatus(ImportResult::STATUS_CREATED);
                        }

                        $hotelModel->save();
                        $results[] = $result;
                    } else {
                        $result->setStatus(ImportResult::STATUS_SKIPPED, 'Not passed the filter criterias');
                    }
                } catch (\Exception $e) {
                    $results[] = new ImportResult(
                            array(
                        'hotel_code' => $hotel->code,
                        'hotel_name' => $hotel->name,
                        'language' => $language,
                        'status' => ImportResult::STATUS_SKIPPED,
                        'description' => 'OWS Error'
                            )
                    );
                }
            }
        }

        return $results;
    }

    public function hasOwsChanges($entityModel, $serializedData)
    {
        //echo serialize($data).'<br />';
        //echo $entityModel->getOwsData().'<br /><br />';
        return ($serializedData != $entityModel->getOwsData());
    }

    public function getHotelsArray($service)
    {
        $items = $service->getItemsBy(array('status' => \ListModule\Model\ListItem::ITEM_STATUS_PUBLISHED));
        $removeList = array();
        foreach ($items as $item) {
            if ($item->getCode() != 'Corporate') {
                $removeList[$item->getCode()] = array(
                    'name' => $item->getName()
                );
            }
        }
        return $removeList;
    }

    public function getItemsArray($service, $hotelFilter)
    {
        $items = $service->getItemsBy(array('status' => \ListModule\Model\ListItem::ITEM_STATUS_PUBLISHED));
        $removeList = array();
        foreach ($items as $item) {
            if (!$hotelFilter || in_array($item->getProperty()->getCode(), $hotelFilter)) {
                $removeList[$item->getCode()] = array(
                    'name' => $item->getName(),
                    'hotelCode' => $item->getProperty()->getCode()
                );
            }
        }
        return $removeList;
    }

    public function removeItems($removeList, $options, $service)
    {
        if (!$options['DryRun']) {
            foreach ($removeList as $code => $removeProperty) {
                if (in_array($code, $options['Approve'])) {
                    $item = $service->getItemBy(array('code' => $code));
                    $service->trash(array($item->getId()));
                    unset($removeList[$code]);
                }
            }
        }
        return $removeList;
    }

}
