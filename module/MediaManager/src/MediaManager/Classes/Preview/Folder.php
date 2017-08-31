<?php
namespace MediaManager\Classes\Preview;

/**
 * PreviewFolder Class
 * 
 * Previewable folder items on the media manager toolbox page
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
class Folder extends Preview
{
	public function __construct($options)
	{
		parent::__construct($options);
		$this->contentType = 'folder';
		$this->class = 'mediaPreview folder';
	}	

	protected function setPreviewNotes()
	{
		$this->fileSize = '';
		$this->fileType = '';
	}

	protected function setPreviewThumb()
	{
		$this->previewThumb = $this->mediaManagerImageHREF . 'icon.folder.small.png';
	}
}