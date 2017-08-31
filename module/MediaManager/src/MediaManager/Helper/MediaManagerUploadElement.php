<?php
namespace MediaManager\Helper;

use Zend\View\Helper\AbstractHelper;

class MediaManagerUploadElement extends AbstractHelper
{
	public function __invoke()
	{
		$parameters = func_get_args();

		$modified       =    $parameters[0];
		$created        =    $parameters[1];
		$fileId         =    $parameters[2];
		$parentTable    =    $parameters[3];
		$parentRowId    =    $parameters[4];
		$order          =    $parameters[5];
		$status         =    $parameters[6];
		$version        =    $parameters[7];
		$itemId         =    $paramteers[8];

		$this->getView()->mediaManagerUploadView = $mediaManagerUploadView;
		$this->getView()->attributes = $attributes;

		return $this->getView()->rerender('toolbox/tools/upload.phtml');
	}

	public function baseUrl()
	{
		return rtrim(Zend_Controller_Front::getInstance()->getBaseUrl());
	}
}