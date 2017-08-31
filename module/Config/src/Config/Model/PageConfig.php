<?php
/**
 * File for the PageConfig Model
 *
 * @category    Toolbox
 * @package     Config
 * @subpackage  Model
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.4
 * @since       File available since release 13.4
 * @author      A. Tate <atate@travelclick.com>
 * @filesource
 */
namespace Config\Model;

/**
 * PageConfig
 *
 * The Model for handling a page's config.
 * Currently this is set up to use the pages Cxml, but eventually that will be abstracted out so multiple sources
 * for the page config can be used. 
 *
 * @category    Toolbox
 * @package     Config
 * @subpackage  Model
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.4
 * @since       File available since release 13.4
 * @author      A. Tate <atate@travelclick.com>
 */
class PageConfig
{
    /**
     * Holds the config array
     * @var array
     */
    protected $config = array();

    /**
     * The page's controller
     * @var string
     */
    protected $controller = '';

    /**
     * The page's action
     * @var string
     */
    protected $action = '';

    /**
     * The page's module
     * @var string
     */
    protected $module = '';

    /**
     * Any config settings for the given module.
     * @var array
     */
    protected $moduleConfig = array();

    /**
     * Hold's the raw config array, before it is manipulated
     * @var array
     */
    protected $rawConfig = array();

    /**
     * __construct
     *
     * The class constructor.
     * 
     * @param string $page
     * @param string $subsite
     */
    public function __construct($page, $subsite = '')
    {
        //Get the config array from the Cxml for the given page and subsite
        $config = $this->loadPageConfig($page, $subsite);

        if ($config) {
            //Set our model's properties from the config values
            $this->setFromConfig($config);
        }
    }

    /**
     * setConfig
     *
     * Setter for $this->config
     * 
     * @param array $config
     */
    public function setConfig($config)
    {
        $this->config = $config;
    }

    /**
     * getConfig
     *
     * Getter for $this->config
     * @return array $this->config
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * getModule
     *
     * Getter for $this->module
     * @return string
     */
    public function getModule()
    {
        return $this->module;
    }

    /**
     * getController
     *
     * Getter for $this->controller
     * @return string
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * getAction
     *
     * Getter for $this->action
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * getModuleConfig
     *
     * Getter for $this->moduleConfig
     * @return array $this->moduleConfig
     */
    public function getModuleConfig()
    {
        return $this->moduleConfig;
    }

    /**
     * getRawConfig
     *
     * Getter for $this->rawConfig
     * @return array
     */
    public function getRawConfig()
    {
        return $this->rawConfig;
    }

    /**
     * loadPageConfig
     *
     * Loads the Cxml for the given page and subsite, and converts it to an array.
     * @param  string $page
     * @param  string $subsite
     * @return array|boolean
     */
    protected function loadPageConfig($page, $subsite = '')
    {
        $subsitePath = '';

        //Add a slash to the subsite, if it isn't empty
        if ($subsite) {
            $subsitePath = $subsite . '/';
        }

        //Set the page Cxml path
        $pageConfigPath = SITE_PATH . $subsitePath . $page . '.cxml';

        //Check to see if the file exists.
        if (file_exists($pageConfigPath)) {
            //File does exist. Get the XmlString
            $pageConfigXmlString = file_get_contents($pageConfigPath);

            //Convert the Xml string to an array
            $pageConfigArray = \Phoenix\StdLib\TypeHandling::xmlToArray($pageConfigXmlString);

            //Return the array
            return $pageConfigArray;            
        }

        //file doesn't exist. Return false
        return false;
    }

    /**
     * setFromConfig
     *
     * Sets the model's properties from the given config array
     * 
     * @param array $config
     */
    protected function setFromConfig($config)
    {
        //We want to save the config array before things are done to it.
        $this->rawConfig = $config;

        //Check to see if there is a content key in the array
        if (isset($config['content']) && is_array($config['content'])) {
            //Ther is a content key, run through each of its children
            foreach ($config['content'] as $keyContent => $valContent) {
                //We only want one module per page
                if (isset($valContent['@attributes']['type']) && $valContent['@attributes']['type'] == 'module') {
                    //Set the module property. This should exist for both Phoenix and Condor modules
                    $this->module = $valContent['module'];

                    //Set the action to the defaultTask, if that is found
                    if (isset($valContent['conf']['defaultTask'])) {
                        $this->action = $valContent['conf']['defaultTask'];
                    }

                    //Set the action to the action value, if that is found. This will override the defaultTask
                    if (isset($valContent['action'])) {
                        $this->action = $valContent['action'];
                    }

                    //Set the controller to the controller value.
                    if (isset($valContent['controller'])) {
                        $this->controller = $valContent['controller'];
                    }

                    //Set the moduleConfig property
                    $this->moduleConfig = $valContent['conf'];

                    //To reduce redundancy, let's remove our extracted config settings from the config array
                    unset($config['content'][$keyContent]);
                    break;
                }
            }   
        }
        //Set the config array to our updated config.
        $this->config = $config;        
    }
}