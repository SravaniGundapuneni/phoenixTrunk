<?php

/**
 * The GoogleAnalytics Service
 *
 * @category    Toolbox
 * @package     GoogleAnalytics
 * @subpackage  Service
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.6
 * @since       File available since release 13.6
 * @author      Sravani Gundapuneni <sgundapuneni@travelclick.com>
 * @filesource
 */

namespace GoogleAnalytics\Service;

use GoogleAnalytics\Model\GoogleAnalytic;
use Pages\EventManager\Event as PagesEvent;
use PhoenixProperties\Service\SubmoduleNonIntegratedAbstract as BaseLists;

class GoogleAnalytics extends BaseLists
{

    public function __construct()
    {

        $this->entityName = GoogleAnalytic::GOOGLEANALYTIC_ENTITY_NAME;
        $this->modelClass = "\GoogleAnalytics\Model\GoogleAnalytic";
    }

    public function checkGoogleAnalytics()
    {
        $getgooleAnalytics = $this->getDefaultEntityManager()->getRepository('GoogleAnalytics\Entity\GoogleAnalytics')->findBy(array('status' => 1));
//$client = new Zend_Rest_Client('https://gap-api-t6.ihotelier.com/gap/v1/web/81832');
//var_dump($client);
        return($getgooleAnalytics ? array('account' => $getgooleAnalytics[0]->getGaAccount(), 'domain' => $getgooleAnalytics[0]->getDomain(), 'anonynize' => $getgooleAnalytics[0]->getAnonynize(), 'remarketing' => $getgooleAnalytics[0]->getRemarketing()) : false);
    }

    public function getPageNamesOptions()
    {
        $options_total = array(1 => '- Select -', 2 => 'Normalized(recommended)', 3 => 'Google auto Detect', 4 => 'Disable auto tracking');
        return $options_total;
    }

    

    function pushGapserverData($bid, $dataArr)
    {
        if (count(array_diff(array_keys($dataArr), array('bid', 'account', 'domain', 'foreigndomain'))) == 0 //array doesn't contain unexpected keys
                && !empty($dataArr['bid']) //array contains non-empty bid
                && count($dataArr) > 1) { //array contains data other than bid
            $gapDataArr = array();
            $gapDataArr['bidId'] = $dataArr['bid'];
            if (isset($dataArr['account'])) {
                $gapDataArr['gaAccounts'] = array('uaAccountId' => $dataArr['account']);
            }
            if (isset($dataArr['domain'])) {
                $gapDataArr['domain'] = $dataArr['domain'];
            }
            if (isset($dataArr['foreigndomain'])) {
                $gapDataArr['beDomain'] = $dataArr['foreigndomain'];
            }
            $jsonData = json_encode($gapDataArr);

            $response = $this->gapserverApiCall('PUT', $bid, $jsonData);
            if ($response) {
                $request = $response[0];
                $statusCode = $response[1];
                $responseBody = $response[2];

                //Handle the different possible status codes:
                switch ($statusCode) {
                    case 200 :
                        //success
                        return true;
                        break;
                    default :
                        trigger_error("GAPserver responded to request PUT $request and body $jsonData with unexpected HTTP status code $statusCode and body $responseBody");
                        break;
                }
            }
        } else {
            trigger_error('The array ' . print_r($dataArr, true) . ' has bad keys or values');
        }

        return false;
    }

}
