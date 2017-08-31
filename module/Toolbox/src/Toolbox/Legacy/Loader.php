<?php
/**
 * [What's up with this file?]
 *
 * [Long Description for this file]
 *
 * @category    [Category]
 * @package     [Package]
 * @subpackage  [Subpackage]
 * @copyright   Copyright (c) 2011 EZYield.com, Inc (http://www.ezyield.com)
 * @license     All Rights Reserved
 * @version     Release: [1.0.0]
 * @since       File available since release [1.0.0]
 * @author      Jose A Duarte <jduarte@travelclick.com>
 * @filesource
 */
namespace Toolbox\Legacy;

class Loader
{
    protected $classArray = array(
        'required' => array(
                'conf/constants.php',
                'modules/core/class.Base.php',
                'modules/core/class.IncludeLib.php',
                'modules/core/class.Main.php'
            )
        );

    protected $ext = '.php';

    protected $prefix = '';

    protected $path;


    public function __construct($path = '', $options = array())
    {
        $this->path = $path;

        if (!empty($options)) {
            $this->setOptions($options);
        }
    }

    public function loadLegacyClasses($iniSettings, $area = 'required')
    {

        foreach ($this->classArray[$area] as $valFile) {
            require_once $iniSettings['paths']['condorBaseDir'] . $valFile;
        }
    }

    public function setClassArray($classArray)
    {
        $this->classArray = $classArray;
    }

    public function load($module, $fileName, $file = null, $line = null)
    {
        $success = false;

        /**
         * Reset global main and bring it into scope
         */
        $main = $GLOBALS['main'] = M();

        $filePath = $this->buildPath($module, $fileName);

        if (file_exists($filePath)) {
            include_once $filePath;
            $success = true;
        } else {
            trigger_error("Failed to include $fileName called from $file on $line.", E_USER_ERROR);
        }

        return $success;
    }

    protected function buildPath($module, $fileName)
    {
        $filePath = $this->path . $module . '/' . $this->prefix . $fileName . $this->ext;

        return $filePath;
    }

    protected function setOptions($options)
    {
        if (isset($options['prefix'])) {
            $this->prefix = $options['prefix'];
        }

        if (isset($options['ext'])) {
            $this->ext = $options['ext'];
        }
    }
}