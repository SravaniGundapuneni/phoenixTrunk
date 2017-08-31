<?php
namespace TemplateBuilder\Helper;

use Zend\View\Helper\AbstractHelper;

class GetTemplateFileImage extends AbstractHelper
{
    public function __invoke($fileName)
    {
    	return ($fileName === 'header.phtml' || $fileName === 'footer.phtml') ? str_replace(".phtml", "", $fileName) . '-icon.png' : 'content-icon.png';
    }
}