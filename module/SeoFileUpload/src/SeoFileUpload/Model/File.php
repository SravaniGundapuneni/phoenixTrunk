<?php
/**
 * Media Manager File Model class file
 *
 * Holds the Media Manager File Model class
 *
 * @category    Toolbox
 * @package     SeoFileUpload
 * @subpackage  Model
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.5
 * @since       File available since release 13.5
 * @author      A. Tate <atate@travelclick.com>
 * @filesource
 */

namespace SeoFileUpload\Model;

use Phoenix\Module\Model\ModelAbstract;

class File extends ModelAbstract
{
    const DEFAULT_ITEM_STATUS = 1;

    protected $entityClass = 'SeoFileUpload\Entity\SeoFileUploadFiles';

    public function exchangeArray($fileArray)
    {
        parent::exchangeArray($fileArray);
    }

    public function save()
    {
        $dbPath = \Phoenix\StdLib\FileHandling::getDbFormattedPath($this->getEntity()->getPath());
        $this->getEntity()->setPath($dbPath);

        parent::save();
    }

    public function saveFile($tmpName, $path = null) 
    {
        if (is_null($path)) {
            $path = $this->getEntity()->getPath();
        }

        $filePath = $path . '/' . $this->getEntity()->getName();

        move_uploaded_file($tmpName, $filePath);  
    }
}