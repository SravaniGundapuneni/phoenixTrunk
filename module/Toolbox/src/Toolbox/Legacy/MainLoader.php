<?php
namespace Toolbox\Legacy;

class MainLoader
{
    /**
     * Holds the instance of the Main Legacy adapter
     * @var \Toolbox\Legacy\Main
     */
    protected static $main;

    /**
     * getInstance
     * 
     * Get our instance of the Main Adapter
     * 
     * @return Toolbox\Legacy\Main [description]
     */
    public static function getInstance()
    {
        if (!static::$main instanceof \Toolbox\Legacy\Main) {
            throw new \Exception('Toolbox\Legacy\MainLoader::getInstance called before $main is set.');
        }

        return static::$main;        
    }

    /**
     * setInstance
     * 
     * Sets the Instance property. We do not want the old main class being set here, only our legacy adapter.
     * @param Toolbox\Legacy\Main $main The legacy adapter object
     */
    public static function setInstance(\Toolbox\Legacy\Main $main)
    {
        static::$main = $main;
    }
}