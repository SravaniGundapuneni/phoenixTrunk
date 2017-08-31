<?php
/** 
 * MediaManager Model
 *
 * @category                              Toolbox
 * @package                               MediaManager
 * @subpackage                            Model
 * @copyright                             Copyright (c) 2013 TravelClick, Inc (http://travelclick.com)
 * @license                               All Rights Reserved
 * @version                               Release 13.5
 * @since                                 File available since release 13.5
 * @author                                Kevin Davis <kedavis@travelclick.com>
 * @filesource
*/

namespace MediaManager\Model;

/**
 * MediaManager Model
 *
 * @category                              Toolbox
 * @package                               MediaManager
 * @subpackage                            Model
 * @copyright                             Copyright (c) 2013 TravelClick, Inc (http://travelclick.com)
 * @license                               All Rights Reserved
 * @version                               Release 13.5
 * @since                                 File available since release 13.5
 * @author                                Kevin Davis <kedavis@travelclick.com>
*/


class MediaManager 
{
	const MEDIAMANGER_ENTITY_NAME = 'MediaManager\Entity\MediaManager';
	const DEFAULT_ITEM_STATUS = 1;

	/**
	 * The Media Manager DB Entity
	 * @var MediaManager\Entity\MediaManager
	*/

	protected $mediaManagerEntity;

	/**
	 * *
	 * setMediaManager
	 *
	 * @param MediaManager\Entity\MediaManager $mediaManagerEntity
	*/

	protected function setMediaManager($mediaManagerEntity)
	{
		$this->mediaManagerEntity = $mediaManagerEntity;
	}

	/**
	 * getMediaManager
	 *
	 * @param MediaManager\Entity\MediaManager $mediaManager
	*/

	public function getMediaManager()
	{
		return $this->mediaManagerEntity;
	}

	/**
	 *
	 * getMedia
	 * 
	 * Return what the media calls or uploads.
	 * @return string
	*/

	public function getMedia()
	{
	   return ($this->getMediaManager()) ? $this->getMediaManagerEntity()->getMedia() : '';
	}

	public function loadFromArray($loadArray = array())
	{
		if (!$this->mediaManagerEntity instanceof \MediaManager\Entity\MediaManager) {
			$this->mediaManagerEntity = new \MediaManager\Entity\MediaManager();
		}

		foreach ($loadArray as $key => $value) {
			$setMethod = 'set' . ucfirst($key);

			if (is_callable(array($this->mediaManagerEntity, $setMethod))) {
			   $this->mediaManagerEntity->$setMethod($value);
			}
		}
	}


	public function toArray()
	{
		return array (
			'id'           => $this->mediaManagerEntity->getId(),
			'getfilepath'  => $this->getMediaManagerEntity->getFilePath(),
			'status'       => $this->getMediaManagerEntity->getStatus(),
			'datasection'  => $this->getMediaManagerEntity->getDataSection(),
			'userid'       => $this->getMediaManagerEntity->getUserId(),
			'height'       => $this->getMediaManagerEntity->getHeight(),
			'width'        => $this->getMediaManagerEntity->getWidth(),
			'author'       => $this->getMediaManagerEntity->getAuthor(),
			'date'         => $this->getMediaManagerEntity->getDate(),
			'filenameorig' => $this->getMediaManagerEntity->getFileNameOrig(),
			'created'      => $this->getMediaManagerEntity()->getCreated(),
			'modified'     => $tihs->getMediaManagerEntity()->getModified(),
		);
	}
}