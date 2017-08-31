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

namespace ContentApproval\Helper;

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

class GetCasInfo extends \ListModule\Helper\ItemInformation
{

    protected $serviceManager;

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

    public function getMediaManagerFile($fileId)
    {
        $mmService = $this->serviceManager->get('phoenix-mediamanagerfiles');
        
        return $mmService->getItem($fileId);
    }
    
    public function getModuleItem($module, $itemId)
    {
        $casService = $this->serviceManager->get('phoenix-contentapproval');
        $serviceName = $casService->getServiceName(ucfirst($module));
        $service = $this->serviceManager->get('phoenix-'.$serviceName);
        return $service->getItem($itemId);
    }
    
    public function getPendingItemCount()
    {
        $casService = $this->serviceManager->get('phoenix-contentapproval');
        $casItems = $casService->getItemsBy(array('approved'=> 0));
        return count($casItems);
    }

    /**
     *  __invoke function
     * 
     * @access public
     * @param mixed $propertyId
     * @return mixed $result
     *
     */
    public function __invoke()
    {
        return $this;
    }

    /**
     *  __construct function
     * 
     * @access public
     * @param  mixed $phoenixProperties
     *
     */
    public function __construct($serviceManager)
    {
        $this->serviceManager = $serviceManager;
    }

}
