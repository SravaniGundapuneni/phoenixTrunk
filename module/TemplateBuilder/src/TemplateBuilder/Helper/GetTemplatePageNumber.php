<?php
namespace TemplateBuilder\Helper;

use Zend\View\Helper\AbstractHelper;

class GetTemplatePageNumber extends AbstractHelper
{
    public function __invoke($index, $perPage = 8)
    {
    	return ceil($index / $perPage);
    }
}