<?php

/**
 * The file for the GetPropertyRates Helper
 *
 * @category    Toolbox
 * @package     GetHeroImages
 * @subpackage  Helper
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 1.14
 * @since       File available since release 1.14
 * @author      Daniel Yang <dyang@travelclick.com>
 * @filesource
 */

namespace Blocks\Helper;

/**
 * The file for the GetHeroImages Helper
 *
 * @category    Toolbox
 * @package     GetPropertyRates
 * @subpackage  Helper
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 1.14
 * @since       File available since release 1.14
 * @author      Daniel Yang <dyang@travelclick.com>
 */
use Zend\View\Helper\AbstractHelper;

class GetBlocks extends AbstractHelper
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
            'images' => array()
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
     *  getMediaAttachments function
     * 
     * @access protected
     * @param mixed $item
     * @return mixed $result
     *
     */
    protected function getMediaAttachments($item)
    {
        $result = array();

        $attachments = $item->getMediaAttachments();

        $serviceManager = $this->phoenixProperties->getServiceManager();
        $heroImageAttachments = $serviceManager->get('phoenix-heroimageAttachments');
        foreach ($attachments as $key => $attachment) {
            $info = array();
            $imageInfo = $heroImageAttachments->getItemBy(array('attachmentId' => $attachment->getId()));
            if ($imageInfo)
            {
                $info = $imageInfo->toArray();
            }
            
            $result[] = array(
                'large' =>  $attachment->getFilePath(),
                'thumb' =>  $attachment->getThumbnail(),
                'title' => $attachment->getTitle(),
                'info' => $info
            );
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
    public function __invoke($propertyId, $url)
    {
        return 'test';
        $result = $this->getResutTemplate($propertyId);

        if ($property = $this->phoenixProperties->getProperty($propertyId)) {
            // $integrationManager = $this->serviceManager->get('integration-manager');

            $result['id'] = $property->getId();
            $result['code'] = $property->getCode();
            $result['name'] = $property->getName();

            $serviceManager = $this->phoenixProperties->getServiceManager();
            $heroImages = $serviceManager->get('phoenix-heroImages');

            foreach ($heroImages->getItemsBy(array('url' => $url, 'propertyId' => $propertyId)) as $heroImage) {
                $result['images'][] = array(
                    'id' => $heroImage->getId(),
                    'title' => $heroImage->getTitle(),
                    'url' => $heroImage->getUrl(),
                    'attachments' => $this->getMediaAttachments($heroImage),
                );
            }

            /*
              foreach ($property->GetPropertyRates() as $key => $item)
              {
              $item = $phoenixRates->createModel($item);

              $result['rates'][] = array(
              'id' => $item->getId(),
              'code' => $item->getCode(),
              'featured' => $item->getFeatured(),
              'name' => $item->getName(),
              'intro' => $item->getDescription(),
              'start' => $item->getStartDate(),
              'expires' => $item->getAutoExpiry(),
              'category' => $item->getCategory(),
              'mainImage' => $this->getMainImage($item),
              'attachments' => $this->getMediaAttachments($item),
              'addons' => array(),
              );
              }
             * */
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
