<?php
/**
 * The file for the GetProperties Helper
 *
 * @category    Toolbox
 * @package     SiteMap
 * @subpackage  Helper
 * @copyright   Copyright (c) 2014 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 14.1
 * @since       File available since release 14.1
 * @author      A. Kotsores <akotsores@travelclick.com>
 * @filesource
 */

namespace SiteMaps\Helper;

use Zend\View\Helper\AbstractHelper;

class GetSiteMap extends AbstractHelper
{
    protected $siteMap;

    /**
     *  __invoke function
     * 
     * @access public
     * @return mixed $result
     *
    */

    public function __invoke()
    {
        $result = array();

        $pages = $this->siteMap->getAllList(true);
		
        foreach ($pages as $key => $item)
        {
            $itemResult['id'] = $item->getId();
            $itemResult['title'] = $item->getTitle();
            $itemResult['path'] =  $item->getDataSectionId()->getDataSection() . "/" . $item->getPageKey();
            $itemResult['page'] = $item->getPageKey();
            $itemResult['page-type'] = $item->getDynamicPage();
            $result[] = $itemResult; 
        }
		
        return $result;
    }

    /**
     *  __construct function
     * 
     * @access public
     * @param  mixed $siteMap
     *
    */

    public function __construct($siteMap)
    {
        $this->siteMap = $siteMap;
    }
}