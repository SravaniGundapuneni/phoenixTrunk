<?php
namespace MediaManager\Classes\MediaFiles;

class AttachedFile extends MediaFile
{
	public function __construct($options)
	{
		$this->name     = $options['name'];
		$this->savePath = $options['savePath'];
		$this->setTypes();
	}	

	public function isOK($mediaManagerOptions)
	{
		return true;
	}
}