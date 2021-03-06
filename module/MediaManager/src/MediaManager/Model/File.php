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

class File extends ModelAbstract
{
    const DEFAULT_ITEM_STATUS = 1;

    protected $entityClass = 'MediaManager\Entity\MediaManagerFiles';

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
            
            // SDS NEW CODE ADDED STARTS, 22 AUG 2014, 3:51AM 
            //print_r("Warning for saving a file :");           
            //print_r(" &nbsp; ");          
            //print_r($this->getEntity()->getPath());           
            //print_r(" &nbsp; ");          
            //print_r($this->getEntity()->getName());           
            //print_r(" &nbsp; ");          
            //$mediaids=0;          
            //$mediaids = $this->getDefaultEntityManager()->getRepository('MediaManager\Entity\MediaManagerFiles')->findBy(array('name' => $this->getName()));
            //die();
            // SDS NEW CODE ADDED ENDS, 22 AUG 2014, 3:51AM
        }

        $filePath = $path . '/' . $this->getEntity()->getName();

        move_uploaded_file($tmpName, $filePath);  
    }
}