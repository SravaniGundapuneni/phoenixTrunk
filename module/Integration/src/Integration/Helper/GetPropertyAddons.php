<?php

/**
 * The file for the GetPropertyAddons Helper
 *
 * @category    Toolbox
 * @package     GetPropertyAddons
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
 * The file for the GetPropertyAddons Helper
 *
 * @category    Toolbox
 * @package     GetPropertyAddons
 * @subpackage  Helper
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.4
 * @since       File available since release 13.4
 * @author      A. Tate <atate@travelclick.com>
 */



class GetPropertyAddons extends \ListModule\Helper\ItemInformation
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
            'addons'=>array()
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
     * @param mixed $item
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
            
            $currentLanguage['code'] = $theLanguage;

            $serviceManager = $this->phoenixProperties->getServiceManager();
            $phoenixAddons =  $serviceManager->get('phoenix-addons');
            $filters['property'] = $property->getId();
            $filters['status'] = \ListModule\Model\ListItem::ITEM_STATUS_PUBLISHED;
            $addons = array();

            $replacementOrderNumber = 1000000;
            $currentOrderNumber = 0;
            
            foreach ($property->GetPropertyAddons() as $key => $item)
            {
                $item = $phoenixAddons->createModel( $item );
                $itemOrderNumber = $item->getOrderNumber();
                $orderNumber = !empty($itemOrderNumber) ? $itemOrderNumber : $replacementOrderNumber;

                if (isset($addons[$orderNumber])) {
                    $currentOrderNumber = $orderNumber + 1;
                } else {
                    $currentOrderNumber = $orderNumber;
                }

                $itemArray = $item->getArrayCopy();

                $currentAddonName = $itemArray['name'];
                if($theLanguage == 'fr') {
                    $currentAddonName = $item->getName_fr();
                    if($currentAddonName == '') {
                        $currentAddonName = $itemArray['name_en'];
                    }
                }
                
                $currentAddonDescription = $itemArray['description'];
                 if($theLanguage == 'fr') {
                    $currentAddonDescription = $item->getDescription_fr();
                    if($currentAddonDescription =='') {
                        $currentAddonDescription = $itemArray['description_en'];
                    }
                }
                
                $addons[$currentOrderNumber] = array(
                    'id' => $item->getId(),
                    'code' => $item->getCode(),
                    //Need to change this back when this won't break booking engine.
                    'name' => $currentAddonName,
                    'intro' => $currentAddonDescription,
                    'name_en' => $itemArray['name_en'],
                    'intro_en' => $itemArray['description_en'],                    
                    'mainImage' => $this->getMainImage($item),
                    'attachments' => $this->getMediaAttachments($item),
                );

                $replacementOrderNumber++;
            }
            ksort($addons);

            $result['addons'] = $addons;
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