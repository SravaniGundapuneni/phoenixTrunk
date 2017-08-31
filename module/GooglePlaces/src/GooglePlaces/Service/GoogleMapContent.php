<?php
/**
 * The GoogleMapContent Service
 *
 * @category    Toolbox
 * @package     GoogleMapContent
 * @subpackage  Service
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.4
 * @since       File available since release 13.4
 * @author      Igor Sorokin <isorokin@travelclick.com>
 * @filesource
 */

namespace GooglePlaces\Service;

/**
 * The GoogleMapContent Service
 *
 * @category    Toolbox
 * @package     GoogleMapContent
 * @subpackage  Service
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.4
 * @since       File available since release 13.4
 * @author      Igor Sorokin <isorokin@travelclick.com>
 */

class GoogleMapContent extends \Blocks\Service\Blocks
{
    //============================================================================================================
    /**
     * setViewVars function
     * 
     * @access public
     * @param mixed $view
     * @param mixed $block
     *
     * This function does not return a value. 
    */
    public function setViewVars($view, $block)
    {
        $lmData = &$GLOBALS['tc_page_parameters']['loewsMap'];
        $em = $this->getDefaultEntityManager();
        $lmData->hotels = $em->createQueryBuilder()
            ->select(
                array('pp.name',
                      'pp.description',
                      'pp.address',
                      'pp.city',
                      'pp.state',
                      'pp.zip',
                      'pp.country',
                      'pp.latitude',
                      'pp.longitude',
                      'pp.group',
                      'pp.labelX',
                      'pp.labelY',
                      'pp.phoneNumber',
                      'pp.photo',
                    ))
                ->from('PhoenixProperties\Entity\PhoenixProperty', 'pp')
            ->getQuery()->getResult();

        $lmData->featuredPlaces = $em->createQueryBuilder()
            ->select('mm')->from('MapMarkers\Entity\MapMarkers', 'mm')
            ->getQuery()->getArrayResult();
    }
}
