<?php
namespace Toolbox\Helper;

use Zend\View\Helper\AbstractHelper;

class CamelCaseToWords extends AbstractHelper
{
    public function __invoke($camelCaseStr)
    {
        $abreviationsPattern = '/(?<=[A-Z])(?=[A-Z][a-z])/x';
        $camelCasePattern = '/(?<=[a-z])(?=[A-Z])/x';

        // split off abbreviations
        $arr = preg_split($abreviationsPattern, $camelCaseStr);
        $str = implode(' ', $arr);

        // split camel cased wordes
        $arr = preg_split($camelCasePattern, $str);
        $str = implode(' ', $arr);

        // finalize title
        $str = str_replace('_', ' ', ucfirst(trim($str)));

        return $str;
    }
}