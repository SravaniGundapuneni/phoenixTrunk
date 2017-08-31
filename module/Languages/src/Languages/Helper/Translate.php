<?php
namespace Languages\Helper;

use Zend\View\Helper\AbstractHelper;

/**
 * Takes the given $arguments and builds
 */
class Translate extends AbstractHelper
{
    public function __invoke($key, $defaultValue = '', $language = null)
    {
        //This is a passthrough so the helper can be implemented in widgets before 
        //the actual translation functionality is in place.
        return $defaultValue;
    }
}