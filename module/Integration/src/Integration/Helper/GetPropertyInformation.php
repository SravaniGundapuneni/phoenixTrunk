<?php

/**
 * The file for the GetPropertyInformation Helper
 *
 * @category    Toolbox
 * @package     GetPropertyInformation
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
 * The file for the GetPropertyInformation Helper
 *
 * @category    Toolbox
 * @package     GetPropertyInformation
 * @subpackage  Helper
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.4
 * @since       File available since release 13.4
 * @author      A. Tate <atate@travelclick.com>
 */

class GetPropertyInformation extends \ListModule\Helper\ItemInformation
{
    protected $phoenixProperties;

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
            'information' => array(),
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
     * @param array $params
     * @return mixed $result
     *
    */

    public function __invoke($propertyId, $params = array())
    {
        $result = $this->getResutTemplate($propertyId);

        if (!is_object($propertyId)) {
            $property = $this->phoenixProperties->loadProperty($propertyId);
        } else {
            $property = $propertyId;
        }

        if ($property)
        {
            $result['id'] = $property->getId();
            $result['code'] = $property->getCode();
            $result['name'] = $property->getName();

            $isCorporate = $property->getIsCorporate();

            $result['information'] = array(
                'code' => $property->getCode(),
                'isCorporate' => ($isCorporate == 1) ? true : false,
                'name' => $property->getName(),
                'intro' => $property->getDescription(),
                'street' => $property->getAddress(),
                'state' => $property->getState(),
                'city' => $property->getCity(),
                'country' => $property->getCountry(),
                'url' => $property->getUrl(),
                'userId' => $property->getUserId(),
                'labelX' => $property->getLabelX(),
                'labelY' => $property->getLabelY(),
                'zip' => $property->getZip(),
                'map' => null,
                'coordinates' => $property->getLatitude() . ',' . $property->getLongitude(),
                'phone' => $property->getPhoneNumber(),
                'resPhone' => $property->getTollfreeNumber(),
                'fax' => $property->getFaxNumber(),
                'email' => $property->getEmail(),
                'facebook' => $property->getFacebook(),
                'twitter' => $property->getTwitter(),
                'instagram' => $property->getInstagram(),
                'sitePath' => $property->getSitePath(),
                'history' => $property->getHistory(),
                'policy' => $property->getPolicy(),
                'tempFormat' => $property->getTempFormat(),
                'mainImage' => $this->getMainImage($property),
                'attachments' => $this->getMediaAttachments($property),
            );
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