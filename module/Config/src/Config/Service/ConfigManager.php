<?php
/**
 * The unified configuration manager for Toolbox
 *
 * This will take all of our disparate configurations and create a unified interface for accessing the configuration.
 * 
 * @category    Modules
 * @package     Config
 * @subpackage  Service
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.4
 * @since       File available since release 13.4
 * @author      A. Tate <atate@travelclick.com>
 * @filesource
 */
namespace Config\Service;

use Phoenix\Service\ServiceAbstract;
use Phoenix\EventManager\Event as PhoenixEvent;
use Zend\Stdlib\ArrayUtils;

/**
 * The unified configuration manager for Toolbox
 * 
 * Configs in Toolbox are a mess. From condor alone we have local.ini or server.ini, global.ini,
 * hostmap.ini and all of the disparate cxmls. With Phoenix, we throw in the application and module configs
 * plus the router config files that individual sites can use. In the interest of sanity we need to 
 * get a handle on this and create a unified configuration. This class will do that.
 *
 * Eventually this will allow us to create dynamic configuration using the database, like any good CMS would.
 *
 * In the interest of backwards compatibility, we'll still keep the ability to 
 * retrieve the iniSettings array and the LocalCxml SimpleXmlElement objects. All new code should use the new interface.
 * 
 * @category    Modules
 * @package     Config
 * @subpackage  Service
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.4
 * @since       File available since release 13.4
 * @author      A. Tate <atate@travelclick.com>
 */
class ConfigManager extends ServiceAbstract
{
    const CONFIG_ARRAY = 'array';
    const CONFIG_SIMPLEXML = 'simplexml';
    const CONFIG_DATABASE = 'database';

    protected $eventManager;

    /**
     * mergedConfig
     *
     * The merged config built from all of the other configs
     * 
     * @var array
     */
    protected $mergedConfig = array();

    /**
     * Holds all of the various rawConfigs
     * @var array
     */
    protected $rawConfigs = array();

    /**
     * __construct
     *
     * Class Constructor
     */
    public function __construct()
    {
        //Initialize our Priority Queue
        $this->priorityQueue = new \SplPriorityQueue();
    }

    /**
     * addConfig
     *
     * Adds the given config to the appropriate queue. 
     * The higher the priority, the sooner the config is merged.
     *
     * @param string label
     * @param array $config
     * @param integer $priority
     */
    public function addConfig($label, $config, $priority = 0)
    {
        $this->saveConfig($label, $config);

        $this->setPriority($label, $priority);
    }

    public function saveConfig($label, $config)
    {
        $this->rawConfigs[$label] = $config;
    }

    /**
     * getRawConfigs
     *
     * Returns all of the pre-merged config arrays
     * @return array
     */
    public function getRawConfigs()
    {
        return $this->rawConfigs;
    }

    /**
     * getRawConfig
     *
     * If the given label is found in the rawConfigs array, returns the rawConfig array for that label. If not, returns false.
     * 
     * @param  string $label
     * @param  boolean $asObject
     * @return array|boolean
     */
    public function getRawConfig($label, $asObject = false)
    {
        $rawConfig = (isset($this->rawConfigs[$label])) ? $this->rawConfigs[$label] : false;
        return (is_array($rawConfig) && $asObject == true) ? new \Config\Model\MergedConfig($rawConfig) : $rawConfig;
    }

    /**
     * setPriority
     *
     * Sets the priority in the PriorityQueue for the given label
     * 
     * @param string  $label
     * @param integer $priority
     */
    protected function setPriority($label, $priority = 0)
    {
        //If the priority isn't set, will make this the top of the list
        if ($priority == 0) {
            $priority = $this->getNewTopPriority();
        }

        //Inserts the label into the priorityQueue
        $this->priorityQueue->insert($label, $priority);        
    }

    /**
     * getNewTopPriority
     *
     * Returns the new top priority value
     * 
     * @return integer
     */
    protected function getNewTopPriority()
    {
        //We only want to check the top priority level, not the value itself
        $this->priorityQueue->setExtractFlags(\SplPriorityQueue::EXTR_PRIORITY);

        //Check to see if we have items in our queue
        if ($this->priorityQueue->count() > 0) {
            //We do, Get the current top priority and increase it by one.
            $priority = $this->priorityQueue->top() + 1;
        } else {
            //We don't. Set the top priority to 1
            $priority = 1;
        }

        //Unless we want our merge to break, we need to reset this to return the priority queue's data
        $this->priorityQueue->setExtractFlags(\SplPriorityQueue::EXTR_DATA);

        //Return the new top priority
        return $priority;
    }

    /**
     * mergeConfigs
     *
     * Merge all of the configs into one config array
     * @return void
     */
    public function mergeConfigs()
    {
        $mergedConfig = array();

        foreach($this->priorityQueue as $valQueue) {
            $config = $this->rawConfigs[$valQueue];
            if (!is_array($config)) {
                var_dump($config);
                die('FILE: ' . __FILE__ . ' LINE: ' . __LINE__);
            }
            $mergedConfig = array_merge_recursive($config, $mergedConfig);
        }

        $this->mergedConfig = new \Config\Model\MergedConfig($mergedConfig);
        //Trigger the onMergeConfig events. This can be used by other modules to do their own merging
        //All configMerge events should return an array of the manipulated values, so we aren't trying
        //to merge virtually the same array over and over.
        $onConfigMerge = $this->eventManager->trigger(PhoenixEvent::EVENT_CONFIGMERGE, '\Phoenix\EventManager\Event', array('configManager' => $this));

    }

    /**
     * Merge a given config into our mergedConfig
     * @param  array $configToMerge
     * @return void
     */
    public function mergeConfig($configToMerge)
    {
        $this->mergedConfig->setMergedConfig(array_merge($this->mergedConfig->getMergedConfig(), $configToMerge));
    }

    /**
     * getMergedConfig
     * 
     * Get the Merged Config
     * @return array
     */
    public function getMergedConfig()
    {
        return $this->mergedConfig;
    }

    public function mergeModuleConfigs($parentConfig, $childConfig)
    {
        $config = ArrayUtils::merge($parentConfig, $childConfig);

        return $config;
    }        
}
