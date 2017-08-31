<?php
namespace MediaManager\Classes\MediaFiles;

abstract class MediaFile
{
	protected $generalType = '';
	protected $mediaRoot;
	protected $mediaUpload = null;
	protected $name        = '';
	protected $path        = '';
	protected $propertyId;
	protected $savePath    = '';
	protected $tmpName     = '';
	protected $type        = '';
	protected $userId;

	protected $imageTypes = array(
		'jpeg' => 'image/jpeg',
		'gif'  => 'image/gif',
		'png'  => 'image/png',
		'jpg'  => 'image/jpg',
		'bmp'  => 'image/bmp',
		'tif'  => 'image/tif',
		'tiff' => 'image/tiff'
	);

	protected $docTypes = array(
		'pdf'  => 'application/pdf',
		'xls'  => 'application/vnd.ms-excel',
		'doc'  => 'application/vnd.openxmlformats-officedocument.wordpressingml.document',
		'rtf'  => 'application/rtf',
		'ods'  => 'application/vnd.oasis.opendocument.spreadsheet',		
		'txt'  => 'text/plain',
		'csv'  => 'text/csv',
		'ppt'  => 'application/vnd.ms-powerpoint',
		'zip'  => 'application/zip',
		'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
		'xltx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.template',
		'potx' => 'application/vnd.openxmlformats-officedocument.presentationml.template',
		'ppsx' => 'application/vnd.openxmlformats-officedocument.presentationml.slideshow',
		'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
		'sldx' => 'application/vnd.openxmlformats-officedocument.presentationml.slide',
		'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
		'dotx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.template'
	);

	protected $videoTypes = array(
		'swf'  => 'application/x-shockwave-flash',
		'flv'  => 'video/x-flv',
		'mp4'  => 'video/mp4',  // can also be video/mp4
		'mpga' => 'audio/mpeg', // not sure why this is classified here
		'mpg'  => 'video/mpeg',
		'mpeg' => 'video/mpeg',
		'wmv'  => 'video/x-ms-wmv',
		'mov'  => 'video/quicktime'
	);

	abstract public function isOK($mediaManagerOptions);

	public function __construct() {}

	public function buildSavePath()
	{
		$savePath = $this->mediaRoot;
		if ($this->path !== '/') {
			$savePath .= $this->path;
		}
		return $savePath;
	}

	public function getDbPath()
	{
		return rtrim('/d/' . $this->path, '/');
	}

	public function getFileParameters()
	{
		return array(
			'name'     => $this->name,
			'path'     => $this->getDbPath()
		);	
	}

	public function getGeneralType()
	{
		return $this->generalType;
	}

	public function getImageOptions($fileId)
	{
		return array(
			'userId'        => (int) $this->userId,
			'fileId'        => $fileId,
			'socialSharing' => 1
		);
	}

	public function getResponseOptions($fileItem)
	{
		return array(
			'parentDirectory' => $this->savePath,
			'mediaRoot'       => $this->savePath,
			'entry'           => $this->name,
			'currentPath'     => $this->path,
			'fileId'          => $fileItem->getId()
		);
	}

	public function getName()
	{
		return $this->name;
	}

	public function getPath()
	{
		return $this->path;
	}

	public function getSaveData()
	{
		return array(
			'name'       => $this->name,
			'path'       => $this->savePath,
			'tmpName'    => $this->tmpName,
			'type'       => $this->type,
			'propertyId' => $this->propertyId,
			'userId'     => (int) $this->userId
		);
	}

	public function getSavePath()
	{
		return $this->savePath;
	}

	public function getTempName()
	{
		return $this->tmpName;
	}

	public function getType()
	{
		return $this->type;
	}

	protected function formatFileName()
	{
		$name = str_replace(' ', '_', $this->mediaUpload['name']);

		//Check if file name have other non-alphanumeric characters apart from underscore, full stop (period), hyphen-minus, plus 
		if (!preg_match('/^[A-Za-z0-9_\.\-\+]+$/', $name)) {

			$md5 = mb_substr(md5($name), 0, 6);

			//Remove other non-alphanumeric characters apart from underscore, full stop (period), hyphen-minus, plus from name
			$name = preg_replace('/[^A-Za-z0-9_\.\-\+]+/', "", $name);

			$lastDot = mb_strrpos($name, '.');
			
			//Insert md5 hash in name to preserve uniqueness of file name and Prepended the md5 hash with an underscore( so that the resulting filename can be easy to read )
			$name = mb_substr($name, 0, $lastDot) . '_' . $md5 . mb_substr($name, $lastDot);
		}

		$this->name = $name;
	}

	protected function setTypes()
	{
		$ext = substr(strrchr($this->name, '.'), 1);	

		if (array_key_exists($ext, $this->imageTypes)) {
			$this->type = $this->imageTypes[$ext];
			$this->generalType = 'IMAGE';
		} elseif (array_key_exists($ext, $this->videoTypes)) {
			$this->type = $this->videoTypes[$ext];
			$this->generalType = 'VIDEO';
		} elseif (array_key_exists($ext, $this->docTypes)) {
			$this->type = $this->docTypes[$ext];
			$this->generalType = 'DOC';
		}
	}
}