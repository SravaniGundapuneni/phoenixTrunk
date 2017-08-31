<?php
namespace MediaManager\Classes\MediaFiles;

class UploadedFile extends MediaFile
{
	public function __construct($options)
	{
		$this->mediaUpload = $options['file']['mediaUpload'];

		$this->mediaRoot   = $options['mediaRoot'];
		$this->path        = $options['path'];
		$this->propertyId  = $options['propertyId'];
		$this->userId      = (int) $options['userId'];
		$this->type        = $this->mediaUpload['type'];
		$this->tmpName     = $this->mediaUpload['tmp_name'];
		$this->savePath    = $this->buildSavePath();

		$this->formatFileName();

		$this->setGeneralType();
	}	

	private function setGeneralType()
	{
		$generalType = false;

		if (isset($this->mediaUpload['tmp_name'])) {
			$finfo = finfo_open(FILEINFO_MIME_TYPE);
			$mime = finfo_file($finfo, $this->mediaUpload['tmp_name']);

			if (in_array($mime, $this->videoTypes)) {
				$generalType = 'VIDEO';
			} elseif (in_array($mime, $this->imageTypes)) {
				$generalType = "IMAGE";
			} elseif (in_array($mime, $this->docTypes)) {
				$generalType = "DOC";
			}
		} else {
		}

		$this->generalType = $generalType;
	}

	public function getMediaUpload()
	{
		return $this->mediaUpload;
	}

	public function isOK($mediaManagerOptions)
	{
		$isOK = true;
		if ($this->mediaUpload['error'] == UPLOAD_ERR_OK) {
			if ($this->getGeneralType === 'VIDEO') {
				$isOK = $mediaManagerOptions['isVideoUploadingEnabled'];
			}
		} else {
			$isOK = false;
		}

		return $isOK;
	}
	

}