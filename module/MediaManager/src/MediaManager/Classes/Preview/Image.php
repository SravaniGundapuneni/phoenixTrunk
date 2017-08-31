<?php
namespace MediaManager\Classes\Preview;

/**
 * PreviewImage Class
 * 
 * Previewable image items on the media manager toolbox page
 *
 * @category           Toolbox
 * @package            MediaManager
 * @subpackage         Classes
 * @copyright          Copyright (c) 2014 TravelClick, Inc (http://travelclick.com)
 * @license            All Rights Reserved
 * @version            Release 3.14
 * @since              File available since release 3.14
 * @author             Jason Bowden <jbowden@travelclick.com>
 * @filesource
 */

class Image extends Preview
{
	const PREVIEW_FOLDER = '__thumbs_90_90_crop/';
	
	public function __construct($options)
	{
		parent::__construct($options);
		$this->contentType     = 'image';
		$this->class           = 'mediaPreview image';
		$this->ext             = $this->getFileExtensionFromTitle();
		$this->setFileSize();
		$this->contextMenuType = 'contextMenuImageLink';
	}	

	protected function setPreviewNotes()
	{
		$fileFullPath = $this->mediaRoot . $this->currentPath . $this->entry;

		$parentWidth = $parentHeight = 0;
		if (file_exists($fileFullPath)) {
			list($parentWidth, $parentHeight) = getimagesize($fileFullPath);
		}
		$this->fileType = $this->type . ' Image File';
		$this->fileSize = $this->size . ' ' . $parentWidth . ' x '.$parentHeight.' px';
	}

	protected function setPreviewThumb()
	{
		$this->previewThumb = $this->siteRoot . $this->baseFolder . $this->currentPath . self::PREVIEW_FOLDER . $this->title; 
	}
}