<?php
namespace MediaManager\Classes\Preview;

/**
 * Preview Class
 * 
 * Previewable content items on the media manager toolbox page
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
abstract class Preview
{
	const THUMBS_FOLDER_PREFIX = '__thumbs';

	protected $baseFolder = 'd/';
	protected $class;
	protected $contentType;
	protected $currentPath = '';
	protected $entry;
	protected $ext;
	protected $fileSize; // this should be called file dimensions
	protected $file_size = '';
	protected $fileType;
	protected $mediaManagerImageHREF;
	protected $mediaRoot;
	protected $previewThumb;
	protected $siteRoot;
	protected $title;
	protected $type;
	protected $contextMenuType = '';

	private $fileId;
	private $mediaFile;
	private $parentItemId;
	private $parentItemModule;

	public function __construct($options)
	{
		$this->siteRoot              = $options['siteRoot'];
		$this->entry                 = $options['entry'];
		$this->mediaManagerImageHREF = $options['mediaManagerImageHREF'];
		$this->currentPath           = $options['currentPath'];
		$this->mediaFile             = isset($options['mediaFile'])    ? $options['mediaFile']    : null;
		$this->mediaRoot             = $options['mediaRoot'];
		$this->fileId                = isset($options['fileId'])       ? $options['fileId']       : 0;
		$this->parentItemId          = isset($options['parentItemId']) ? $options['parentItemId'] : null;
		$this->parentModule          = $options['parentModule']	       ? $options['parentModule'] : null;

		$this->initType();		
		$this->initTitle();
		$this->initFileId(); // overrides fileId if mediaFile is present
		$this->setPreviewThumb();
		$this->setPreviewNotes();
	}	

	abstract protected function setPreviewNotes();
	abstract protected function setPreviewThumb();

	/**
	 * Necessary method for passing object into partialLoop helpers
	 * @return Array
	 */
	public function toArray()
	{
		return array(
			'fileId'          => $this->fileId,
			'class'           => $this->class,
			'contentType'     => $this->contentType,
			'currentPath'     => $this->currentPath,
			'ext'             => $this->ext,
			'fileSize'        => $this->fileSize,
			'file_size'       => $this->file_size,
			'fileType'        => $this->fileType,
			'parentItemId'    => $this->parentItemId,
			'parentModule'    => $this->parentModule,
			'previewThumb'    => $this->previewThumb,
			'title'           => $this->title,
			'contextMenuType' => $this->contextMenuType
		);
	}

	public function setCurrentPath($currentPath)
	{
		$this->currentPath = $currentPath;
	}

	protected function formatFileSize($bytes) 
	{
		$GIGABYTE = 1073741824;
		$MEGABYTE = 1048576;
		$KILOBYTE = 1024;

		if (!is_int($bytes)) {
			$fileSizeText = '';
		} elseif ($bytes >= $GIGABYTE) {
			$fileSizeText =	number_format(($bytes / $GIGABYTE), 2).' GB';
		} elseif ($bytes >= $MEGABYTE) {
			$fileSizeText = number_format(($bytes / $MEGABYTE), 2).' MB';
		} else {
			$fileSizeText =	number_format(($bytes / $KILOBYTE), 2) . ' KB';	
		}

		return $fileSizeText;
	}		

	protected function getNonCachedImageUrl($filepath)
	{
		return $filepath . '?' . (time() * rand(0, 1000));
	}

	protected function getFileExtensionFromTitle()
	{
		return substr(strrchr($this->title, '.'), 1);	
	}

	protected function setFileSize()
	{
		$this->file_size = $this->formatFileSize(filesize($this->mediaRoot . $this->currentPath . $this->entry));
	}

	private function initFileId()
	{
		if ($this->mediaFile) {
			$this->fileId = $this->mediaFile->getId();
		}
	}

	private function initTitle()
	{
		$title = $this->entry;
		$mediaFile = $this->mediaFile;

		// this old code should be refactored upstream
		if ($mediaFile) {
			if ($mediaFile->getTitle()) {
				$title = $mediaFile->getTitle();
			} elseif ($mediaFile->getFileNameOrig()) {
				$title = $mediaFile->getFileNameOrig();
			}
		}
		$this->title = $title;
	}

	private function initType()
	{
		$this->type = pathinfo($this->entry, PATHINFO_EXTENSION); 
	}
}