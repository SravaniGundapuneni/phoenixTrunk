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

namespace SiteMap\Helper;

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

        $pages = $this->siteMap->getItems(true);

        foreach ($pages as $key => $item)
        {
            $itemResult['id'] = $item->getId();
            $itemResult['title'] = $item->getTitle();
            $itemResult['path'] = $item->getAreaKey() . $item->getDataSection() . "/" . $item->getPage();
            $itemResult['page'] = $item->getPage();
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