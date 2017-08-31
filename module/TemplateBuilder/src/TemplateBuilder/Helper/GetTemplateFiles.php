<?php
namespace TemplateBuilder\Helper;

use Zend\View\Helper\AbstractHelper;

class GetTemplateFiles extends AbstractHelper
{
	private $blacklist = array('index.phtml');
	private $global = array('header.phtml', 'footer.phtml','sidebar.phtml');

	private $directory;

    public function __invoke($directory, $key = 'page')
    {
    	$this->setDirectory($directory);

    	$scannedFiles   = scandir($this->getDirectory());
    	$returnFiles    = array_filter($scannedFiles, array($this, 'filterExistingFiles'));
    	$filterCallback = $key === 'global' ? array($this, 'filterGlobal') : array($this, 'filterPages');

    	return array_values(array_filter($returnFiles, $filterCallback));
    }

    private function filterExistingFiles($file)
    {
    	return is_file($this->getDirectory() . $file);
    }

    private function filterGlobal($pageName)
    {
    	return !in_array($pageName, $this->blacklist) && in_array($pageName, $this->global);
    }

    private function filterPages($pageName)
    {
    	return !in_array($pageName, $this->blacklist) && !in_array($pageName, $this->global);
    }

    private function setDirectory($directory)
    {
    	$this->directory = $directory;
    }

    private function getDirectory()
    {
    	return $this->directory;
    }
}