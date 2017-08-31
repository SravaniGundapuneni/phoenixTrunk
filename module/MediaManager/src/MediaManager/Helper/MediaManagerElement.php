<?php
namespace MediaManager\Helper;

use Zend\View\Helper\AbstractHelper;

class MediaManagerElement extends AbstractHelper
{
	public function __invoke()
	{
		$parameters = func_get_args();

		$filePath  =      $parameters[0];
		$fileName  =      $parameters[1];
		$status =         $parameters[2];
		$dataSection =    $parameters[3];
		$userId  =        $parameters[4];
		$height =         $parameters[5];
		$width =          $parameters[6];
		$author =         $parameters[7];
		$date =           $parameters[8];
		$fileNameOrig =   $parameters[9];
		$created =        $parameters[10];
		$modified =       $parameters[11];

		$this->getView()->mediaManagerView = $mediaManagerView;
		$this->getView()->attributes = $attributes;

		


	}
}