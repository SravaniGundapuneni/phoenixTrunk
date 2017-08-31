<?php
namespace Toolbox\Filter;

class DataCollector
{
    static protected $keysCollected = array();
    static protected $svcMgr;

    //===================================================================================================================
    public function __construct($svcLoc)
    {
        self::$svcMgr = $svcLoc;
        if (!isset($GLOBALS['tc_dynamic_data'])) {
            $GLOBALS['tc_dynamic_data'] = array();
        }
    }

    //===================================================================================================================
    /**
     * This is what Zend Calls when running the filterChain
     * This method may be called multiple times: once for every included template/view
     *
     * @param  string $contents
     * @return string
     */
    public static function collectData($contents)
    {
        return $contents;
        
        $m = array();
        // parse all data-definition attributes
        if (!preg_match_all("/phoenix-data='(.*?)'/", $contents, $m) && !preg_match_all("/phoenix-data=\"(.*?)\"/", $contents, $m)) {
            return $contents;  // if none return the unchanged template
        }

        // for each data definition found...
        foreach ($m[1] as $svcDef) {
            $kc = self::$keysCollected;		
            // do not collect the same data twice
            if (array_key_exists($svcDef, self::$keysCollected)) {
                continue;
            }
            // separate service name from method name
            $parts = explode('_', $svcDef);  // <service name>_<method name>
            $method = "dyna$parts[1]";   // actual implementation must have 'dyna' prfix for distinction
            // find the service and calling the method on it
            // and store returned data in $GLOBALS['tc_dynamic_data'] with the unique data definition name as the key
            if (self::$svcMgr->has($parts[0])) {
                $GLOBALS['tc_dynamic_data'][$svcDef] = self::$svcMgr->get($parts[0])->$method();
                // add all newly collected data key to remember
                self::$keysCollected[$svcDef] = 1;
            }
        }

        // when reached the top template and any data is collected
        // spit out the script initialized with collected data
        static $didItOnce = false;
        if (!$didItOnce && count($GLOBALS['tc_dynamic_data']) && stripos($contents, "--TOP TEMPLATE--")) { 
            $data = json_encode($GLOBALS['tc_dynamic_data']);
            // inject the script with collected data into the template
            $contents .= "<script class='init-script'> var tcWidgetsParameters=$data; populateWithData(tcWidgetsParameters); $('.template').css({display:'none'})</script>";
            $didItOnce = true;
        }

        return $contents;
    }
}