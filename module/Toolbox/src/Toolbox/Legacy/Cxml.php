<?php
/**
 * Legacy Cxml Adapter
 *
 * @category    Phoenix
 * @package     Application
 * @subpackage  Legacy
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.4
 * @since       File available since release 13.4
 * @author      Andrew C. Tate <atate@travelclick.com>
 * @filesource
 */
namespace Toolbox\Legacy;

/**
 * Legacy Cxml Adapter
 *
 * This is the legacy adapter that corresponds to the condor code that handles CXML.
 * 
 * This does not extend from any extant class, as condor uses functions and parts of 
 * main to handle this.
 *
 * @category    Phoenix
 * @package     Application
 * @subpackage  Legacy
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.4
 * @since       File available since release 13.4
 * @author      Andrew C. Tate <atate@travelclick.com>
 */
class Cxml
{
    protected $pathway = array();

    public function setServiceManager(\Zend\ServiceManager\ServiceLocatorInterface $serviceManager)
    {
        $this->serviceManager = $serviceManager;
    }

    public function getServiceManager()
    {
        return $this->serviceManager;
    }

    public function getPathway()
    {
        return $this->pathway;        
    }

    public function getLocalCxml($location, $page, $setEnvironment = false, $ignorePage = false)
    {
        static $cxmlCache;

        $serviceManager = $this->getServiceManager();

        if ($page === false) {
            // If $page is not provided, assume $location holds "location/page"
            $parts = explode('/', $location);
            $page = array_pop($parts);
            $location = implode('/', $parts);
        }

        // Remove task etc. from page
        list($page) = explode(' ', $page);

        if ($location) {
            $location = preg_replace("/(?<!\/)$/", "/", $location); // Get location with trailing slash
        }        
        
        if(isset($cxmlCache[$location . $page]))
        {
            $serviceManager->setService('localCxml', $cxmlCache[$location . $page]);
            return;
        }

        $siteDef = simplexml_load_file('module/Toolbox/config/legacy/parserDefinition.cxml');

        $path        = '';
        
        
        $finalCXML   = array();
        $cxmlFiles[] =  array( 'path' => '',
                               'file' => '_area.cxml', 
                               'page' => null);
        if($location != '')
        {
            $locationTrimmed = mb_substr($location, 0 , mb_strlen($location) - 1);

            if(strlen($locationTrimmed) > 0) {
                $locationParts = explode('/', $locationTrimmed);
                
                foreach($locationParts as $part) {
                    $path .= $part . '/';
                    
                    //Add on all of the areas to the array
                    $cxmlFiles[]  = array('path' => $path ,
                                          'file' => '_area.cxml',
                                          'page' => null);
                }
            }
        }
        
        //Only add on the page to the array if ignorePage is false
        //A. Tate 06/14/2013 during creation of PhoenixProperties module
        if (!$ignorePage) {
            $cxmlFiles[] = array('path' => $location, 
                                 'file' => $page . '.cxml',
                                 'page' => $page);
        }
       
        //Reverse the array to work with the fies from page -> root _area.cxml file
        $cxmlFiles      = array_reverse($cxmlFiles);
        
        $cxmlNotFound   = false;
        $cxmlObjects    = array();
        $virtualArea    = false;
        
        $missedFiles = array();

        //Loop through them
        foreach ($cxmlFiles as $file)
        {
            $currentFile  = SITE_PATH . $file['path'] . $file['file'];
                   
            if(file_exists($currentFile ))
            {
                $areaCXML = simplexml_load_file($currentFile );
                
                if(!$areaCXML)
                {
                    trigger_error("Error parsing cxml file " . $currentFile , E_USER_ERROR);
                }
                else
                {
                    $cxmlObjects[] = array ('xml'      => $areaCXML,
                                            'realPath' => SITE_BASE_DIR . $file['path'],
                                            'realFile' => $file['file'],
                                            'area'     => $file['path'],
                                            'page'       => $file['page']);
                }
                
                if((string) $areaCXML->virtualArea != '' && ! $virtualArea)
                {
                    
                    $virtualArea     = (string) $areaCXML->virtualArea;
                    $virtualBaseArea = $file['path'];
                    $cxmlNotFound    = false;  
                    
                    $serviceManager->setAllowOverride(true);
                    $config = $serviceManager->get('Config');
                    
                    if($setEnvironment == true) {                    
                        $config['virtualBaseArea'] = $virtualBaseArea;
                    }

                    $serviceManager->setService('Config', $config);
                    $serviceManager->setAllowOverride(false);
                }                                         
                
                if((string) $areaCXML->baseArea == 'true')
                {
                    $serviceManager->setAllowOverride(true);
                    $config = $serviceManager->get('Config');
                    if($setEnvironment == true) {                         
                        $config['baseArea'] = pathinfo($currentFile, PATHINFO_DIRNAME);
                    }
                    $serviceManager->setService('Config', $config);
                    $serviceManager->setAllowOverride(false);
                }
            }
            else 
            {
                //Put the missed files in an array in case this is a virtual area
                $missedFiles[] = $file;
                $cxmlNotFound = true;
            } 
        }    

        $applicationConfig = $serviceManager->get('ApplicationConfig');
        $condorVirtualAreasDir = $applicationConfig['iniSettings']['paths']['condorBaseDir'] . 'virtualAreas';
                
         //Take each of our missed files and try to scan them
        if($virtualArea) 
        {   
            $cxmlNotFound = false;  
            $baseAreaFile = array('path' => $virtualBaseArea,
                                  'file' => '_area.cxml',
                                  'page' => null);
            
            array_push($missedFiles, $baseAreaFile);
             
            foreach($missedFiles as $file)
            {
                 $virtualPath = str_replace($virtualBaseArea, '', $file['path']);
                 $virtualFile = $condorVirtualAreasDir . '/' . $virtualArea . '/' . $virtualPath . $file['file'];
               
                 if(file_exists($virtualFile))
                 { 
                       $areaCXML = simplexml_load_file($virtualFile);

                       if(!$areaCXML)
                       {                                      
                           trigger_error("Error parsing virtual area cxml file " . $condorVirtualAreasDir . '/' . $virtualArea . '/'. $file['path'] . $file['file'], E_USER_ERROR);
                           return false;
                       }
                       else 
                       {
                           $virtualCXMLObjects[] = array 
                                           ('xml'      => $areaCXML,
                                            'realPath' => $condorVirtualAreasDir . '/' . $virtualArea . '/' . $virtualPath ,
                                            'realFile' => $file['file'],
                                            'area'     => $file['path'],
                                            'page'       => $file['page'],
                                            );
                       }
                 }
                 else
                 {
                      trigger_error("Could not load cxml $virtualFile for location " . $location . ' page ' . $page , E_USER_WARNING);
                      
                      if($setEnvironment == true)
                      {
                            $main->virtualBaseArea = false;
                      }
                      
                      return false;
                 }
             }
        }

        if($cxmlNotFound)
        {
            if(!$setEnvironment)
            {
                trigger_error("Could not load cxml for " . $location . $page . " (Requested by " . $_SERVER['REMOTE_ADDR'] . ")", E_USER_WARNING);
            }
            return false;                   
        }   
        else if (isset($virtualCXMLObjects))
        {
            foreach($virtualCXMLObjects as $virtualcxmlObject)
            {
                $finalObjectArray[] = $virtualcxmlObject;
            }
            
            foreach($cxmlObjects as $cxmlObject)
            {
                $finalObjectArray[] = $cxmlObject;
            }    
        
        }
        else
        {
            $finalObjectArray = $cxmlObjects;
        }
        
        $pathway = array();
        
        foreach ($finalObjectArray as $area)
        {    
            $areaCXML = $area['xml'];
            
            
            //If it's a page, set it as the pathway page, otherwise add it as an area

            if($area['page'])
            {
                if(isset($areaCXML->pageKey))
                {
                    $area['pageKey'] = (string) $areaCXML->pageKey;
                }
                
                $pathway['page'] = $area;
            }
            else
            {
                if(isset($areaCXML->areaKey))
                {
                      $area['areaKey'] = (string) $areaCXML->areaKey;
                }
                
                $pathway['areas'][] = $area;
            }
                         
            foreach ($siteDef->area as $areaDef)
            {
                $rootTag = (string) $areaDef->rootTag;
                   
                if(is_object($areaCXML->$rootTag))
                {     
                    switch((string) $areaDef->parseType)
                    {
                        
                        case 'firstInstance' :    if(!isset($finalCXML[$rootTag]) 
                                                    and mb_strlen(trim($areaCXML->$rootTag->asXML()) != '')                                                )
                                                {          
                                                    $finalCXML[$rootTag] = $areaCXML->$rootTag->asXML();    
                                                    
                                                }
                                                
                                                break;
                                                
                                                
                        case 'createArray'   :  $node = $areaCXML->$rootTag;
                                                $finalCXML[$rootTag] = $areaCXML->$rootTag->asXML() . $finalCXML[$rootTag] ;
                                                            
                                                break;
    /*
    The following works, but then I decided that I don't actually need it! The idea was
    to allow addHeaderContent to be injected into toolbox management pages via areaCXML    -- Anton                                        
                        case 'mergeContent' :    if (!isset($finalCXML[$rootTag])) {
                                                    $finalCXML[$rootTag] ='';
                                                }
                                                // Strip the rootTag from the current $finalCXML[$rootTag] string:
                                                $temp = (string) simplexml_load_string($finalCXML[$rootTag]);
                                                // Append (as a string) the contents of the $areaCXML->$rootTag:
                                                $temp .= (string) $areaCXML->$rootTag;
                                                // Re-enclose in the rootTag:
                                                $finalCXML[$rootTag] = '<' . $rootTag . '><![CDATA[' . $temp .  ']]></' . $rootTag . '>';
                                                break;
    */
                                                
                        case 'mergeChildren' :  foreach ($areaDef->child as $childName)
                                                {
                                                    $childName = (string) $childName;
                                                    
                                                    
                                                    if(is_object($areaCXML->$rootTag->$childName))
                                                    {
                                                        // 25/07-08 JG: I changed this so that i.e. template vars appear in the 'appropriate' order, making it simple to reference template vars from a 'lower' cxml file - hope this doesn't break anything!!!
                                                        $thisElement = array();
                                                        if (!isset($finalCXML[$rootTag][$childName]))
                                                            $finalCXML[$rootTag][$childName] = array();
                                                        foreach($areaCXML->$rootTag->$childName as $child)
                                                        {
                                                            // First gather all the children of this XML element
                                                            if(!isset($finalCXML[$rootTag][$childName][(string) $child['name']]))
                                                            {
                                                                $thisElement[(string) $child['name']] = $child->asXML();
                                                            }
                                                        }
                                                        // Then merged it with the final CXML, placing it at the beginning (since the files are parsed 'backwards')
                                                        $finalCXML[$rootTag][$childName] = array_merge($thisElement, $finalCXML[$rootTag][$childName]);
                                                    }
                                                        
                                                }                                            
                                            
                                                
                                                break;
                                            
                        case 'mergeChildrenByTag' :  foreach ($areaDef->child as $childName)
                                                {
                                                    $childName = (string) $childName;
                                                    
                                                    
                                                    if(is_object($areaCXML->$rootTag->$childName))
                                                    {
                                                        
                                                        foreach($areaCXML->$rootTag->$childName as $child)
                                                        {
                                                            
                                                            if(!isset($finalCXML[$rootTag][$childName][(string) $child['name']]))
                                                            {
                                                                $finalCXML[$rootTag][$childName][(string) $child['name']] = $child->asXML();
                                                            }
                                                        }
                                                    }
                                                        
                                                }
                                                
                                                break;
                    }
                }
                
            }
        }
    
        $finalCXMLString = "";
            
        foreach ($finalCXML as $areaName => $area)    
        {
            if(is_array($area))
            {
                $finalCXMLString .= "<$areaName>\n";
                
                foreach ($area as $childName => $child)
                {
                
                    if(is_array($child))
                    {
                        foreach ($child as $childXML)
                        {
                            $finalCXMLString .= $childXML;
                        }
                    }    
                    else 
                    {    
                        $finalCXMLString .= $child;
                    }
                }
                
                
                
                $finalCXMLString .= "</$areaName>\n";
            }
            else 
            {
                $finalCXMLString .= $area;
            }        
        }        
    
        //Tag on some notes for using later when we need to figure out where the cxml came from
        $finalCXMLString .= <<<EOD
    
    <source>
        <location>$location</location>
        <page>$page</page>
    </source>
    
    
EOD;

        $localCXML = simplexml_load_string("<?xml version=\"1.0\" encoding=\"utf-8\" ?><definition>$finalCXMLString</definition>");

        if($setEnvironment == true) {
            $this->pathway['areas'] = array_reverse($pathway['areas']);
            if (!$ignorePage) {
                $this->pathway['page']  = $pathway['page'];
            }
        }
        
        $cxmlCache[$location . $page] = $localCXML;

        return $localCXML;        
    }

    public function normalizeTemplateVars($templateVars, $pageKey = '', $areaKey = '')
    {
        $newTemplateVars = array();
        foreach($templateVars['var'] as $valVar) {
            if (isset($valVar['@attributes']['name'])) {
                $varName = $valVar['@attributes']['name'];
                switch ($valVar['@attributes']['name']) {
                    case 'shortPageKey':
                        $value = str_replace('page_', '', $pageKey);
                        break;
                    case 'shortAreaKey':
                        $value = str_replace('area_', '', $areaKey);
                        break;
                    case 'toolList':
                    case 'toolboxToolList':
                    case 'generalToolList':
                    case 'adminToolList':
                        $value = $valVar['tool'];
                        break;
                    default:
                        if (!isset($valVar['value'])) {
                            $value = '';
                        } else {
                            $value = $valVar['value'];
                        }
                        break;
                }
                $newTemplateVars[$varName] = $value;
            } else {
                $newTemplateVars[] = $valVar['value'];
            }
        }

        return $newTemplateVars;
    }
}