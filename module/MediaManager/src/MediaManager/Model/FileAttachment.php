<?php
/**
 * Media Manager File Model class file
 *
 * Holds the Media Manager File Model class
 *
 * @category    Toolbox
 * @package     MediaManager
 * @subpackage  Model
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.5
 * @since       File available since release 13.5
 * @author      A. Tate <atate@travelclick.com>
 * @filesource
 */

namespace MediaManager\Model;

use Phoenix\Module\Model\ModelAbstract;

class FileAttachment extends ModelAbstract
{
    const DEFAULT_ITEM_STATUS = 1;

    protected $entityClass = 'MediaManager\Entity\MediaManagerFileAttachments';

    public function exchangeArray($fileArray)
    {
        parent::exchangeArray($fileArray);
    }

    public function getName()
    {
        return ($this->entity) ? $this->entity->getMediaManagerFile()->getName() : '';
    }

    public function getPath()
    {
        return ($this->entity) ? $this->entity->getMediaManagerFile()->getPath() : '';
    }

    public function getFileId()
    {
        return ($this->entity) ? $this->entity->getMediaManagerFile()->getId() : '';
    }

    public function getFilePath()
    {
        return implode('/', array($this->getPath(),$this->getName()));
    }
}