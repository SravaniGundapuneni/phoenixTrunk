<?php
namespace Toolbox\Helper;

use Zend\View\Helper\AbstractHelper;

class ToolboxToolUrl extends AbstractHelper
{
    public function __invoke($toolboxHomeUrl, $moduleName, $modulePath)
    {
        $baseUrl = ($moduleName === 'Refresh Cache') ? str_replace('/toolbox', '', $toolboxHomeUrl) : $toolboxHomeUrl;
        
        return $baseUrl . $modulePath;
    }
}