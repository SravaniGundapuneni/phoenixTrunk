<?php

/**
 * The file for the GetPropertyAttributes Helper
 *
 * @category    Toolbox
 * @package     GetPropertyAttributes
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
 * The file for the GetPropertyAttributes Helper
 *
 * @category    Toolbox
 * @package     GetPropertyAttributes
 * @subpackage  Helper
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.4
 * @since       File available since release 13.4
 * @author      A. Tate <atate@travelclick.com>
 */


class GetPropertyAttributes extends \ListModule\Helper\ItemInformation
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
            'attributes'=>array()
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

    public function __invoke($propertyId)
    {
        $result = $this->getResutTemplate($propertyId);

        if ($property = $this->phoenixProperties->getProperty($propertyId))
        {
            // $integrationManager = $this->serviceManager->get('integration-manager');

            $result['id'] = $property->getId();
            $result['code'] = $property->getCode();
            $result['name'] = $property->getName();

            $serviceManager = $this->phoenixProperties->getServiceManager();
            $phoenixAttributes =  $serviceManager->get('phoenix-attributes');

            foreach ($property->GetPropertyAttributes() as $key => $item)
            {
                $item = $phoenixAttributes->createModel( $item );

                $result['attributes'][] = array(
                    'id' => $item->getId(),
                    'code' => $item->getCode(),
                    'name' => $item->getName(),
                    'intro' => $item->getDescription(),
                    'mainImage' => $this->getMainImage($item),
                    'attachments' => $this->getMediaAttachments($item),
                );
            }
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