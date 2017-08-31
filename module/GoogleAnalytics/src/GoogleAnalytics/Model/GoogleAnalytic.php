<?php

/**
 * MapMarker Model
 *
 * @category    Toolbox
 * @package     GoogleAnalytics
 * @subpackage  Model
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 2.14
 * @since       File available since release 13.6
 * @author      Sravani Gundapuneni <sgundapuneni@travelclick.com>
 * @filesource
 */

namespace GoogleAnalytics\Model;

class GoogleAnalytic extends \ListModule\Model\ListItem
{

    const GOOGLEANALYTIC_ENTITY_NAME = 'GoogleAnalytics\Entity\GoogleAnalytics';

    public function __construct($config = array(), $fields = array())
    {
        $this->entityClass = self::GOOGLEANALYTIC_ENTITY_NAME;

        parent::__construct($config, $fields);
    }

    public function getArrayCopy($datesToString = false)
    {
        //$translations = $this->getTranslations();
        //$defaultLanguageCode = $this->getLanguages()->getDefaultLanguage()->getCode();
        $arrayCopy = parent::getArrayCopy($datesToString);

        $gapServerData = $this->getFreshGapserverData();
//        $arrayCopy['uaAccount'] = $uaAccount;
//       
//        $arrayCopy['bidId'] = $bidId;
        //$arrayCopy['domainName'] = $domainName;
        return $arrayCopy;
    }

    public function getFreshGapserverData($bid = 81832)
    {
        $freshDataAvailable = false;
     
        $response = $this->gapserverApiCall('GET', $bid);
        if ($response) {
            $request = $response[0];
            $statusCode = $response[1];
            $responseBody = $response[2];

            //Handle the different possible status codes:
            switch ($statusCode) {
                case 200 :
                    //success
                    $freshDataAvailable = true;
                    break;
                case 404 :
                    //no data
                    trigger_error("GAPserver had no data to provide for request GET $request and so responded with HTTP status code $statusCode and response '$responseBody'");
                    break;
                default :
                    trigger_error("GAPserver responded to request $request with unexpected HTTP status code $statusCode and body $responseBody");
                    break;
            }
        }

        return ($freshDataAvailable) ? $responseBody : null;
    }
    function gapserverApiCall(/* string */ $action, /* string */ $bid, /* optional string */ $jsonBody)
    {
        // $baseUrl = (isset(M()->iniSettings['analytics']['gapServerUrl'])) ? (string) M()->iniSettings['analytics']['gapServerUrl'] : null;

        $request = "https://gap-api-t6.ihotelier.com/gap/v1/web/81832"; //eg https://gap-api-t6.ihotelier.com/gap/v1/web/81832
        $session = curl_init($request);
        curl_setopt($session, CURLOPT_TIMEOUT, 5); //Timeout all cURL functions after an abitrarily-chosen 5 seconds
        curl_setopt($session, CURLOPT_HEADER, true);
        curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($session, CURLOPT_SSL_VERIFYPEER, false); // ____CHANGE THIS!!  http://stackoverflow.com/questions/6400300/ and http://curl.haxx.se/docs/sslcerts.html ____


        curl_setopt($session, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($session, CURLOPT_POSTFIELDS, $jsonBody);


        $response = curl_exec($session);
        $requestError = curl_error($session);
        curl_close($session);

        if (!$response) { //No response from the API:
            trigger_error("GAPserver could not be reached at $request or else failed to respond. cURL error message was" . (!empty($requestError) ? ': ' . $requestError : 'empty' ));
        } else {
            //Pull out the HTTP status code from the header:
            $threeDigitSubstrings = array();
            preg_match('/\d\d\d/', $response, $threeDigitSubstrings);
            $statusCode = $threeDigitSubstrings[0];

            //Pull out the response body:
            $pos = mb_strpos($response, "\r\n\r\n"); //Position of first blank line in the string (ie two consecutive line endings)
            if ($pos === false) {
                trigger_error("GAPserver responded to request $request with unexpected response '$response'");
            } else {
                $responseBody = mb_substr($response, $pos + 4);
                return array($request, $statusCode, $responseBody);
            }
        }


        return false;
    }

}
