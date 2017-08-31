<?php
namespace MediaManager\Classes\MediaFiles;

class UnzippedFile extends MediaFile
{
	public function __construct($options)
	{
		$this->mediaRoot  = $options['mediaRoot'];
		$this->path       = $options['path'];
		$this->tmpName    = $options['tmpName'];
		$this->propertyId = $options['propertyId'];
		$this->userId 	  = (int) $options['userId'];

		$this->name       = $options['name'];
		$this->savePath   = $this->buildSavePath();

		$this->setTypes();

		// $this->formatFileName();
	}

	public function isOK($mediaManagerOptions)
	{
		return true;
	}
}