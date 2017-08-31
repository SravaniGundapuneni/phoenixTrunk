<?php

/**
 * The file for the GetPropertyRates Helper
 *
 * @category    Toolbox
 * @package     GetPropertyRates
 * @subpackage  Helper
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.4
 * @since       File available since release 13.4
 * @author      A. Tate <atate@travelclick.com>
 * @filesource
 */

namespace Integration\Helper;

/**
 * The file for the GetPropertyRates Helper
 *
 * @category    Toolbox
 * @package     GetPropertyRates
 * @subpackage  Helper
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.4
 * @since       File available since release 13.4
 * @author      A. Tate <atate@travelclick.com>
 */
use Zend\View\Helper\AbstractHelper;

class GetPropertyRates extends \ListModule\Helper\ItemInformation
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
    protected function getResultTemplate($propertyId)
    {
        return array(
            'id' => $propertyId,
            'code' => null,
            'name' => null,
            'rates' => array()
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

        if ($mediaAttachmetns = $this->getMediaAttachments($item)) {
            $result = array_shift($mediaAttachmetns);
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
        $result = $this->getResultTemplate($propertyId);

        
        $serviceManager = $this->phoenixProperties->getServiceManager();
        
        $currentLanguage['code'] = $theLanguage;

        if (empty($currentLanguage['code'])) {
            $thisCurrentLanguage = $serviceManager->get('currentLanguage');
            $currentLanguage['code']  = $thisCurrentLanguage->getCode();            
        }

        $phoenixRates = $serviceManager->get('phoenix-rates');

        $filters['status'] = \ListModule\Model\ListItem::ITEM_STATUS_PUBLISHED;
        $ratesArray = array();

        $replacementOrderNumber = 1000000;
        $currentOrderNumber = 0;
        
        if ($propertyId) {
            $property = $this->phoenixProperties->getProperty($propertyId);

            if (!$property) return $result;
 
            $filters['property'] = $property->getId();            
            if($currentLanguage['code'] == 'fr') 
            {
                $result['id'] = $property->getId();
                $result['code'] = $property->getCode();
                $result['name'] = $property->getName_fr();
            }
            else
            {
                $result['id'] = $property->getId();
                $result['code'] = $property->getCode();
                $result['name'] = $property->getName();   
            }
            $rates = $phoenixRates->getItemsBy($filters);
        } else {
            $rates = $phoenixRates->getItemsBy($filters);
        }

        foreach ($rates as $key => $item) {
            if (!$item instanceof \PhoenixRates\Model\Rate) {
                $item = $phoenixRates->createModel($item);
            }

            $itemOrderNumber = $item->getOrderNumber();
            $orderNumber = !empty($itemOrderNumber) ? $itemOrderNumber : $replacementOrderNumber;

            if (isset($ratesArray[$orderNumber])) {
                $currentOrderNumber = $orderNumber + 1;
            } else {
                $currentOrderNumber = $orderNumber;
            }

            $itemArray = $item->getArrayCopy();

                $currentRateName = $itemArray['name'];
                if($theLanguage == 'fr') {
                    $currentRateName = (!empty($itemArray['name_fr'])) ? $itemArray['name_fr'] : $itemArray['name_en'];
                    // if($currentRateName == '') {
                    //     $currentRateName = $itemArray['name_en'];
                    // }
                }
                $currentRateDescription = $itemArray['description'];
                 if($theLanguage == 'fr') {
                    $currentRateDescription = (!empty($itemArray['description_fr'])) ? $itemArray['description_fr'] : $itemArray['description_en'];
                    // if($currentRateDescription == '') {
                    //     $currentRateDescription = $itemArray['description_en'];
                    // }
                }
            if($currentRateName == '') { $currentRateName = $itemArray['name_en']; }
            if($currentRateDescription == '') { $currentRateDescription = $itemArray['description_en']; }
            $ratesArray[$currentOrderNumber] = array(
                'id' => $itemArray['id'],
                'code' => $itemArray['code'],
                'featured' => $item->getFeatured(),
                'corporateFeatured' => $item->getCorporateFeatured(),
                //This needs to be changed back once the issues with the names and booking engine is resolved.
                'name' => $currentRateName,
                'intro' => $currentRateDescription,
                'name_en' => $itemArray['name_en'],
                'intro_en' => $itemArray['description_en'],                
                'start' => $item->getStartDate(),
                'expires' => $item->getAutoExpiry(),
                'category' => $item->getCategory(),
                'terms' => $item->getTerms(),
                'rateTypeCategory' => $item->getRateTypeCategory(),
                'mainImage' => $this->getMainImage($item),
                'attachments' => $this->getMediaAttachments($item),
                'addons' => array(),
                'canceldate' => '',
            );
            $replacementOrderNumber++;
        }
        
        
        ksort($ratesArray);

        $result['rates'] = $ratesArray;



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

