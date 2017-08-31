<?php
namespace MediaManager\Classes\Preview;

/**
 * Video Class
 * 
 * Previewable document items on the media manager toolbox page
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
class Video extends Preview
{
	public function __construct($options)
	{
		parent::__construct($options);
		$this->contentType     = 'video';		
		$this->ext             = $this->getFileExtensionFromTitle();
		$this->setFileSize();
		$this->contextMenuType = 'contextMenuLink';
		$this->class           = 'mediaPreview video';
	}	
	
	protected function setPreviewNotes()
	{
		$this->fileType = $this->type.' File';
		$this->fileSize = '';
	}

	protected function setPreviewThumb()
	{
		$this->previewThumb = $this->mediaManagerImageHREF.'film.png';
	}
}