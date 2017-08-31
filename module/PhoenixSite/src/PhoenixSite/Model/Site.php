<?php
/**
 * Site Model File
 *
 * @category    Toolbox
 * @package     PhoenixSite
 * @subpackage  Model
 * @copyright   Copyright (c) 2014 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 1.14
 * @since       File available since release 1.14
 * @author      A. Tate <atate@travelclick.com>
 * @filesource
 */

namespace PhoenixSite\Model;

use Phoenix\Module\Model\ModelAbstract;

/**
 * Site Model
 *
 * @category    Toolbox
 * @package     PhoenixSite
 * @subpackage  Model
 * @copyright   Copyright (c) 2014 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 1.14
 * @since       File available since release 1.14
 * @author      A. Tate <atate@travelclick.com>
 */
class Site extends ModelAbstract
{
    protected $subsite = '';
    protected $languageCode = 'en';
    protected $languageCodeFromRoute = false;    
    protected $deviceType;

    /**
     * setSubsite
     * 
     * @param string $subsite
     */
    public function setSubsite($subsite)
    {
        $this->subsite = $subsite;
    }

    /**
     * getSubsite
     * 
     * @return string
     */
    public function getSubsite()
    {
        return $this->subsite;
    }

    public function setLanguageCode($languageCode)
    {
        $this->languageCode = $languageCode;
    }

    public function getLanguageCode()
    {
        return $this->languageCode;
    }

    public function setLanguageCodeFromRoute($languageCodeFromRoute)
    {
        $this->languageCodeFromRoute = $languageCodeFromRoute;
    }

    public function getLanguageCodeFromRoute()
    {
        return $this->languageCodeFromRoute;
    }

    public function getDeviceType()
    {
        if (empty($this->deviceType)) {
            $this->setDeviceType();
        }

        return $this->deviceType;
    }

    public function setDeviceType()
    {
        $mobileDetect = $this->getServiceManager()->get('MobileDetect');
        $deviceType = 'default';

        if ($mobileDetect->isTablet()) {
            $deviceType = 'tablet';
        } elseif($mobileDetect->isMobile()) {
            $deviceType = 'mobile';
        }

        $this->deviceType = $deviceType;        
    }
}

