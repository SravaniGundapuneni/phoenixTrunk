<?php
/**
 * MediaManagerFile Model
 *
 * @category                                 Toolbox
 * @package                                  MediaManagerFile
 * @subpackage                               Model
 * @copyright                                Copyright (c) 2013 Travelclick, Inc (http://travelclick.com)
 * @license                                  All Rights Reserved
 * @version                                  Release 13.5
 * @since                                    File available since release 13.5
 * @author                                   Kevin Davis <kedavis@travelclick.com>
 * @filesource           
 */

namespace MediaManager\Model;

/**
 * MediaManagerFile Model
 *
 * @category                                 Toolbox
 * @package                                  MediaManagerFile
 * @subpackage                               Model
 * @copyright                                Copyright (c) 2013 Travelclick, Inc (http://travelclick.com)
 * @license                                  All Rights Reserved
 * @version                                  Release 13.5
 * @since                                    File available since release 13.5
 * @author                                   Kevin Davis <kedavis@travelclick.com>
 */

class MediaManagerFile
{
	const MEDIAMANAGERFILE = 'MediaManager\Entity\MediaManagerFile';
	const DEFAULT_ITEM_STATUS = 1;

	protected $mediaManagerFileEntity;

    /**
     * setMediaManagerFile
     *
     * @param MediaManager\Entity\MediaManagerFile $mediaManagerFileEntity
     */

    protected function setMediaManagerFile($mediaManagerFileEntity)
    {
    	$this->mediaManagerFileEntity = $mediaManagerFileEntity;
    	return $this;
    }

    /**
     * getMediaManagerFile
     * 
     * @param MediaManager\Entity\MediaManagerFile $mediaManagerFileEntity
     *
     */

    public function getMediaManagerFile($mediaManagerFileEntity)
    {
    	return $this->mediaManagerFileEntity -> $mediaManagerFileEntity;
    }

    /**
     * getMediaFiel
     *
     * Return what the media file calls or uploads.
     * @return string
    */

    public function getMediaFile()
    {
    	return ($this->getMediaManagerFile()) ? $this->getMediaManagerFileEntity()->getMediaFile : '';
    }

    public function loadFromArray($loadArray = array())
    {
    	if (!$this->mediaManagerFileEntity instanceof \MediaManager\Entity\MediaManagerFile) {
    		$this->mediaManagerFileEntity = new \MediaManager\Entity\MediaManagerFile();
    	}

    	foreach ($loadArray as $key => $value) {
    		$setMethod = 'set' . ucfirst($key);
    		if (is_callable(array($this->mediaManagerFileEntity, $setMethod ))) {
    			$this->mediaManagerFileEntity->$setMethod($value);
    		}

    	}

    }

    public function toArray()
    {
    	return array (
            'id' => $this->mediaManagerFileEntity->getId(),
            'modified' => $this->mediaManagerFileEntity->getModified(),
            'created' => $this->mediaManagerFileEntity->getCreated(),
            'fileId' => $this->mediaManagerFileEntity->getFileId(),
            'filePath' => $this->mediaManagerFileEntity->getFileId(),
            'langCode' => $this->mediaManagerFileEntity->getLangCode(),
            'parentTable' => $this->mediaManagerFileEntity->getParentTable(),
            'parentRowId' => $this->mediaManagerFileEntity->getParentRowId(),
            'order' => $this->mediaManagerFileEntity->getOrder(),
            'status' => $this->mediaManagerFileEntity->getStatus(),
            'version' => $this->mediaManagerFileEntity->getVersion(),
            'itemId' => $this->mediaManagerFileEntity->getItemId(),
    		);
    }


}