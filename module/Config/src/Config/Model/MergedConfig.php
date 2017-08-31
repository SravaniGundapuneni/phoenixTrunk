<?php
/**
 * The MergedConfig model file
 *
 * This model holds the MergedConfig array, and is used as an interface to access and change settings
 * in the MergedConfig array.
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
 * The MergedConfig model file
 *
 * This model holds the MergedConfig array, and is used as an interface to access and change settings
 * in the MergedConfig array.
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
class MergedConfig
{
    /**
     * Holds the mergedConfig array
     * @var array
     */
    protected $mergedConfig = array();

    /**
     * __construct
     *
     * Class Constructor
     *
     * Accepts, but doesn't require, a merged array.
     * 
     * @param array $mergedConfig
     */
    public function __construct(array $mergedConfig = array())
    {
        $this->setMergedConfig($mergedConfig);
    }

    /**
     * setMergedConfig
     *
     * Setter for mergedConfig
     * @param array $mergedConfig
     */
    public function setMergedConfig(array $mergedConfig)
    {
        $this->mergedConfig = $mergedConfig;
    }

    /**
     * getMergedConfig
     *
     * Getter for mergedConfig
     * @return array
     */
    public function getMergedConfig()
    {
        return $this->mergedConfig;
    }

    /**
     * Get a value from the mergedConfig
     *
     * If $setting is a string, just returns the value associated with the given key in the top level array.
     * If setting is an array, this drills down through the MergedConfig array, with each value of the setting array representing another level of the MergedConfig array.
     * If the key is not found, the given default value is returned.
     * @param  string|array $setting
     * @param  mixed $default
     * @return mixed - Either the found value in the MergedConfig array, or the given default value.
     */
    public function get($setting, $default = '')
    {
        //Checks if $setting is string
        if (is_string($setting)) {
            //$setting is a string, checks if the given key is set in the top level of the $mergedConfig array.
            if (isset($this->mergedConfig[$setting])) {
                //The key is found, so return the value.
                return $this->mergedConfig[$setting];
            }
        } elseif (is_array($setting) && count($setting) > 0) {
            //$setting is a non-empty array. Let's drill down to find our value.
            return $this->getFromSubArray($setting, $this->mergedConfig, $default);
        }

        //$setting is not found in the MergedConfig array. Return the given default value.
        return $default;
    }

    /**
     * getFromSubArray
     *
     * Checks if a subArray of the given array has a setting.
     *
     * The setting is pulled from the given $setting array. It then checks if this is the lowest level of the array
     * to search, or if you need to make another call. If the given setting at this level is not found, it returns the
     * given default value.
     * 
     * @param  array $setting
     * @param  array $subArray
     * @param  mixed $default
     * @return mixed Either the found value, or the given default value.
     */
    public function getFromSubArray($setting, $subArray, $default = '')
    {
        //Pull the setting key for this level out of the setting array.
        $settingName = array_shift($setting);

        //Check to see if the key is found in our subArray
        if (isset($subArray[$settingName])) {
            //The key is found, let's check if we need to drill down deeper into the subArray
            if (is_array($subArray[$settingName]) && count($setting) > 0) {
                //We need to go down another level in the array. Return what is found at a lower level.
                return $this->getFromSubArray($setting, $subArray[$settingName], $default);
            }

            //This is the lowest level in our search. Return the associated value.
            return $subArray[$settingName];
        }

        //The given key cannot be found in the given subArray. Return the given default value.
        return $default;
    }

    /**
     * set
     *
     * Drills down the MergedConfig array until its finds the appropriate place to set a setting.
     * Will either edit an existing setting, or create a new setting at the appropriate level.
     * @param string|array $setting
     * @param mixed $value
     */
    public function set($setting, $value)
    {
        //Check to see if $setting is a non-empty array.
        if (is_array($setting) && count($setting) > 0) {
            //$setting is a non-empty array, and we should drill down into the next level of the MergedConfig array to set our value.
            $this->mergedConfig = $this->setSubArray($setting, $this->mergedConfig, $value);
            return true;
        } elseif (is_string($setting)) {
            //We only need to set this setting on the highest level. We don't care if the setting is already there.
            //Only that the setting is a string.
            $this->mergedConfig[$setting] = $value;
            return true;
        }

        //$setting is a non-empty array, or is not a string. Return false.
        return false;
    }

    /**
     * setSubArray
     *
     * Drills down through the given $subArray until it runs out of levels, and then sets the value for the key to the given $value.
     * @param array $setting
     * @param array $subArray
     * @param mixed $value
     *
     * @return  array $subArray - The edited array.
     */
    public function setSubArray($setting, $subArray, $value)
    {      
        //Extracts the key for this level of the subArray from the $setting array.
        $settingName = array_shift($setting);

        //Checks if the given $settingName is set and is an array.
        if (isset($subArray[$settingName]) && is_array($subArray[$settingName])) {
            //The given key is set, and it is an array.
            if (count($setting) > 0) {
                //In addition, there are more levels to drill down. Continue down deeper into the SubArray until we find the right key to set.
                $subArray[$settingName] = $this->setSubArray($setting, $subArray[$settingName], $value);
                return $subArray;
            }
        } 

        //Either this is the lowest level of search, we didn't find a key at this level (making this the end of the search), 
        //or we ran out of search levels. Set the value associated to our given key and return the $subArray.
        $subArray[$settingName] = $value;
        return $subArray;
    }
}