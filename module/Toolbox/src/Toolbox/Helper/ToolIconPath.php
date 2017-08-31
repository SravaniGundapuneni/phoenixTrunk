<?php
namespace Toolbox\Helper;

use Zend\View\Helper\AbstractHelper;

class ToolIconPath extends AbstractHelper
{
    public function __invoke($moduleName, $imageDirectory, $imageUrl, $defaultIcon = 'module')
    {
        $imgName = strtolower(str_replace(' ', '-', $moduleName));
        $icon = file_exists("$imageDirectory/$imgName.png") ? $imgName : $defaultIcon;

        return $imageUrl . $icon . ".png";
    }
}