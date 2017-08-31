<?php
/**
 * Condor.php
 * 
 * Legacy Interface for handling condor/condor.php. Primarily this will be used to handle the iniSettings
 * things before Mains instantiation. The rest of condor.php's functionality will be moved to the LegacyController
 *
 * @category    Phoenix
 * @package     Application
 * @subpackage  Legacy
 * @copyright   Copyright (c) 2013 TravelClick
 * @license     All Rights Reserved
 * @version     Release: 13.4
 * @since       File available since release 13.4
 * @author      Andrew C. Tate <atate@travelclick.com>
 * @filesource
 */
namespace Toolbox\Legacy;

/**
 * Condor
 * 
 * Legacy Interface for handling condor/condor.php. Primarily this will be used to handle the iniSettings
 * things before Mains instantiation. The rest of condor.php's functionality will be moved to the LegacyController
 *
 * @category    Phoenix
 * @package     Application
 * @subpackage  Legacy
 * @copyright   Copyright (c) 2011 EZYield.com, Inc (http://www.ezyield.com)
 * @license     All Rights Reserved
 * @version     Release: [1.0.0]
 * @since       File available since release [1.0.0]
 * @author      Jose A Duarte <jduarte@travelclick.com>
 */
class Condor
{
    /**
     * List of steps to run. Used by the setIniSettings method.
     * @var array
     */
    protected $legacyIniSteps = array(
            'setEdgecast',
            'setAnalytics',
            'setServerScriptsDir',
            'filterPathVariables',
            'definePathConstants',
        );

    protected $mergedConfig;

    public function setMergedConfig(\Config\Service\ConfigManager $mergedConfig)
    {
        $this->mergedConfig = $mergedConfig;
    }

    public function getMergedConfig()
    {
        return $this->mergedConfig;
    }

    /**
     * Run through each step and do what needs to be done with the iniSettings
     * @param array $appSettings The array of application settings (including the legacy $iniSettings)
     */
    public function setIniSettings($appSettings)
    {
        $iniSettings = $appSettings['iniSettings'];

        foreach ($this->legacyIniSteps as $legacyStep)
        {
            $iniSettings = $this->$legacyStep($iniSettings, $appSettings);
        }

        $appSettings['iniSettings'] = $iniSettings;

        return $appSettings;
    }

    /**
     * setEdgecast
     * 
     * Ensure existence of authentication credentials for Akamai CDN web service for purging cache.
     * Hack to work around lack of global.server.ini usage for websites on the three original production webservers
     * This was an oversight when designing the CDN implementation in condor
     * The hack can and should be removed once all websites on those servers have been reconfigured to use global.server.ini
     * @todo Verify if this is still needed before code goes live. No need to put legacy "legacy" code out there.
     *
     * @param array $iniSettings
     * @param array $appSettings
     *
     * @return array $iniSettings;
     */
    public function setEdgecast($iniSettings, $appSettings)
    {
        //only affect sites which are configured to use CDN
        if (isset($iniSettings['edgecast']['enable']) && $iniSettings['edgecast']['enable']) {
            //don't affect sites which have already been configured for Akamai via local or global server.ini
            if (!isset($iniSettings['cdn']) || !isset($iniSettings['cdn']['username'])) {
                $iniSettings['cdn']['username'] = $appSettings['cdn']['username'];
                $iniSettings['cdn']['password'] = $appSettings['cdn']['password'];
                $iniSettings['cdn']['statusChangeEmail'] = $appSettings['cdn']['statusChangeEmail'];
            }
        }

        return $iniSettings;
    }

    /**
     * setAnalytics
     *
     * Ensure existence of Master Google Analytics tracking code.
     * CODE FOR BACKWARDS COMPATIBILITY WITH OLD SITES WHICH DON'T HAVE GLOBAL.SERVER.INI
     *
     * @param array $iniSettings
     * @param array $appSettings
     *
     * @return array $iniSettings;
     */
    public function setAnalytics($iniSettings, $appSettings)
    {
        if (!isset($iniSettings['analytics'])) {
            $iniSettings['analytics'] = array();
        }
        
        if (empty($iniSettings['analytics']) || !isset($iniSettings['analytics']['gaMasterTrackingCode'])) {
            $iniSettings['analytics']['gaMasterTrackingCode'] = $appSettings['analytics']['gaMasterTrackingCode'];
        }        

        return $iniSettings;
    }

    /**
     * setServerScriptsDir
     *
     * Ensure existence of new script files path
     * CODE FOR BACKWARDS COMPATIBILITY WITH OLD SITES WHICH DON'T HAVE GLOBAL.SERVER.INI
     * 
     * @param array $iniSettings
     * @param array $appSettings
     *
     * @return array $iniSetttings
     */
    public function setServerScriptsDir($iniSettings, $appSettings)
    {
        if (!isset($iniSettings['paths']['serverScriptsDir']) || empty($iniSettings['paths']['serverScriptsDir']) ) {
            $iniSettings['paths']['serverScriptsDir'] = $appSettings['paths']['serverScriptsDir'];
        }    

        return $iniSettings; 
    }

    /**
     * filterPathVariables
     * 
     * Filter out path variables such as #currentHost
     * 
     * @param  array $iniSettings 
     * @param  array $appSettings
     *  
     * @return array $iniSettings
     */
    public function filterPathVariables($iniSettings, $appSettings)
    {
        $currentProtocol = isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) == 'on' ? 'https://' : 'http://';        
        
        foreach ($iniSettings['paths'] as $key => $path) {
            $iniSettings['paths'][$key] = str_replace(array('#currentHost','http://'), array($_SERVER['HTTP_HOST'],$currentProtocol), $path);
        }

        return $iniSettings;
    }

    /**
     * definePathConstants
     * 
     * Define the path constants
     * 
     * @param  array $iniSettings 
     * @param  array $appSettings 
     * @return array              
     */
    public function definePathConstants($iniSettings, $appSettings)
    {
        define('CONDOR_BASE_DIR', $iniSettings['paths']['condorBaseDir']);
        define('SITE_BASE_DIR', $iniSettings['paths']['siteBaseDir']);

        return $iniSettings;
    }

    /**
     * loadRequiredFiles
     *
     * Loads the basic files needed for condor to operate. Currently this loads four. 
     * Eventually it will load only the Base.php class.
     * 
     * @param  array $iniSettings
     * @return array
     */
    public function loadRequiredFiles($iniSettings)
    {
        $legacyLoader = new Loader();

        //Let's load the required legacy files
        $legacyLoader->loadLegacyClasses($iniSettings);

        return $iniSettings;
    }

    /**
     * setHostMap
     * hostmap.ini processing inside index.php [website] is now deprecated. 
     * Refresh the hostmap data to keep old behaivour that     *
     * 
     * @param array $iniSettings
     * @param array $appSettings
     *
     * @return $iniSettings
     */
    public function setHostMap($iniSettings) 
    {
        require_once(CONDOR_BASE_DIR . 'modules/core/class.HostMapProperties.php');
        
        //hostmap.ini processing inside index.php [website] is now deprecated. 
        //Refresh the hostmap data to keep old behaivour that
        if (!defined('CONDOR_LINKS_BASE_LOCATION') && \HostMapProperties::reloadProperties()){ 
            
            $hosts = \HostMapProperties::getProperties();
            
            foreach ($hosts as $hostname => $dir){
                if (preg_replace("/(:.*)/", "", $_SERVER['HTTP_HOST']) == trim($hostname)) {
                    if(isset($iniSettings['edgecast']['enable'])){
                        $iniSettings['edgecast']['baseHREF'] = preg_replace("/_CURRENT_SITE_/", $hostname, $iniSettings['edgecast']['baseHREF']);
                        $iniSettings['edgecast']['condorBaseHREF'] = preg_replace("/_CURRENT_SITE_/", $hostname, $iniSettings['edgecast']['condorBaseHREF']);
                    }
                    
                    if('/' === $dir['folder']) {
                        define('CONDOR_LINKS_BASE_LOCATION', '/');
                    } else {
                        define('CONDOR_LINKS_BASE_LOCATION', $dir['folder'] . '/');
                    }
                    
                    if(isset($hosts[$hostname]['altDomain']) && isset($iniSettings['cdn']['enable']) && $iniSettings['cdn']['enable'] == 1) {
                        $protocol=strtolower(mb_substr($_SERVER["SERVER_PROTOCOL"],0,5))=='https'? 'https://' : 'http://';
                        define("CONDOR_ORIGIN_HREF",$protocol.$hosts["$hostname"]['altDomain'].'/');
                    }
                    break;
                 }
            }
        }
        if(!defined('CONDOR_ORIGIN_HREF')) {
            define("CONDOR_ORIGIN_HREF",$iniSettings['paths']['siteBaseHREF']);
        }
    }

    public function setHrefConstants($iniSettings)
    {
        define('SITE_BASE_HREF',           $iniSettings['paths']['siteBaseHREF']);
        define('CONDOR_BASE_HREF',         $iniSettings['paths']['condorBaseHREF']);
    }

    public function loadSitePublisher($iniSettings)
    {
        require_once(CONDOR_BASE_DIR . 'modules/sitePublisher/renderPage.php');        
    }
}