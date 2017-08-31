<?php
/**
 * MediaManager Entity File
 *
 * @category                   Toolbox
 * @package                    MediaManager
 * @subpackage                 Entity
 * @copyright                  Copyright (c) 2013 Travelclick, Inc (http://travelclick.com)
 * @license                    All Rights Reserved
 * @version                    Release 13.5
 * @since                      File available since release 13.5
 * @author                     Kevin Davis <kedavis@travelclick.com>
 * @filsource
 */

namespace MediaManager\Entity;

use \Doctrine\ORM\Mapping as ORM;

/**
 * MediaManager Entity File
 *
 * @category                   Toolbox
 * @package                    MediaManager
 * @subpackage                 Entity
 * @copyright                  Copyright (c) 2013 Travelclick, Inc (http://travelclick.com)
 * @license                    All Rights Reserved
 * @version                    Release 13.5
 * @since                      File available since release 13.5
 * @author                     Kevin Davis <kedavis@travelclick.com>
 */

/**
 *
 * @ORM\Table(name="mediamanager")
 * @ORM\Entity
 *
 */

class MediaManager
{
	/**
	 * @var integer id
	 *
	 * @ORM\Column(name="fileId", type="integer", length=11) 
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="IDENTITY")
	 *
    */

    protected $id;

    /**
     * @var text filePath
     *
     * @ORM\Column(name="filePath", type="text")
     *
    */

    protected $filePath;

    /**
     * @var text fileName
     *
     * @ORM\Column(name="fileName", type="text")
     *
    */

    protected $fileName;

    /**
     * @var integer status
     *
     * @ORM\Column(name="status", type="integer")
     *
    */

    protected $status;

    /**
     * @var text dataSection
     *
     * @ORM\Column(name="dataSection", type="text")
     *
    */

    protected $dataSection;

    /**
     * @var integer $createdUserId
     *
     * @ORM\Column(name="`createdUserId`", type="integer", length=11)
     */
    protected $createdUserId;
    /**
     * @var integer $modifiedUserId
     *
     * @ORM\Column(name="`modifiedUserId`", type="integer", length=11)
     */
    protected $modifiedUserId;

    /**
     * @var integer height
     *
     * @ORM\Column(name="height", type="integer")
     *
    */

     protected $height;

     /**
      * @var integer width
      *
      * @ORM\Column(name="width", type="integer")
      *
     */

     protected $width;

     /**
      * @var text author
      * 
      * @ORM\Column(name="author", type="text")
      *
     */

     protected $author;

     /**
      * @var text date
      *
      * @ORM\Column(name="date", type="text")
      *
     */

     protected $date;

     /**
      * @var text fileNameOrig
      *
      * @ORM\Column(name="fileNameOrig", type="text")
      * 
     */

     protected $fileNameOrig;

     /**
      * @var datetime created
      *
      * @ORM\Column(name="created", type="datetime")
      *
     */

     protected $created;

     /**
      * @var datetime modified
      * 
      * @ORM\Column(name="modified", type="datetime")
      *
     */

     protected $modified;

     /**
      * setId
      *
      * Setter for the id property
      * @param integer id
      *
     */

     public function setId($id)
     {
     	$this->id = $id;
     	return $this;
     }

     /**
      * getId
      *
      * Getter for the id property
      * @return integer id
      *
     */

     public function getId($id)
     {
     	return $this->id;
     }

     /**
      * setFilePath
      *
      * Setter for the filePath property
      * @param inetger filePath
      *
     */

     public function setFilePath($filePath)
     {
     	$this->filePath = $filePath;
     	return $this;
     }

     /**
      * getFilePath
      * 
      * Getter for the filePath property
      * @return text filePath
      *
     */

     
     public function getFilePath($filePath)
     {
      	return $this->filePath = $filePath;
     }

     /**
      * setStatus
      *
      * Setter for the status property
      * @param integer status
      *
     */

     public function setStatus($status)
     {
     	$this->status = $status;
     	return $this;
     }

     /**
      * getStatus
      * 
      * Getter for the status property
      * @return integer status
      *
     */

     public function getStatus($status)
     {
     	return $this->status = $status;
     }

     /**
      * setDataSection
      *
      * Setter for the dataSection property
      * @param text dataSection
      *
     */

     public function setDataSection($dataSection)
     {
     	$this->dataSection = $dataSection;
     	return $this;
     }

     /**
      * getDataSection
      * 
      * Getter for the dataSection property
      * @return text dataSection
      *
     */

     public function getDataSection($dataSection)
     {
       return $this->dataSection = $dataSection;
     }

     /**
      * setUserId
      * 
      * Setter for the userId property
      * @param integer userId
      *
     */

     public function setUserId($userId)     
     {
     	$this->userId = $userId;
     	return $this;
     }

     /**
      * getUserId
      * 
      * Getter for the userId propety
      * @return integer userId
      *
     */

     public function getUserId($userId)
     {
     	return $this->userId = $userId;
     }

     /**
      * setHeight
      *
      * Setter for the height property
      * @param integer height
     */

     public function setHeight($height)
     {
     	$this->height = $height;
     	return $this;
     }

     /**
      * getHeight
      *
      * Getter for the height property
      * @return integer height
      *
     */

     public function getHeight($height)
     {
     	return $this->height = $height;
     }

     /**
      * setWidth
      *
      * Setter for the width property
      * @param integer width
      *
     */

     public function setWidth($width)
     {
     	$this->width = $width;
     	return $this;
     }

     /**
      * getWidth
      * 
      * Getter for the width property
      * @return integer width
      *
     */

     public function getWidth($width)
     {
     	return $this->width = $width;
     }

     /**
      * setAuthor
      * 
      * Setter for the setter property
      * @param text author
      *
     */

     public function setAuthor($author)
     {
     	$this->author = $author;
     	return $this;
     }

     /**
      * getAuthor
      * 
      * Getter for the getter property
      * @return text author
      *
     */

     public function getAuhtor($author)
     {
     	return $this->author = $author;
     }

     /**
      * setDate
      * 
      * Setter for the date property
      * @param text date
      *
     */

     public function setDate($date)
     {
     	$this->date = $date;
     	return $date;
     }

     /**
      * getDate
      *
      * Getter for the date property
      * @return text date
      *
     */

     public function getDate($date)
     {
     	return $this->date = $date;
     }

     /**
      * setFileNameOrig
      *
      * Setter for the fileNameOrig
      * @param text fileNameOrog
      *
     */

     public function setFileNameOrig($fileNameOrig)
     {
     	$this->fileNameOrig = $fileNameOrig;
     	return $this;
     }

     /**
      * getFileNameOrig
      * 
      * Getter for the fileNameOrig
      * @return text fileNameOrig
      *
     */

     public function getFileNameOrig($fileNameOrig)
     {
     	return $this->getFileNameOrig = $fileNameOrig;     	
     }

     /**
      * setCreated
      *
      * Setter for created property
      * @param datetime created
      *
     */

     public function setCreated($created)
     {
     	$this->created - $created;
     	return $created;
     }

     /**
      * getCreated
      *
      * Getter for the created property
      * @return datetime created
      *
     */

     public function getCreated($created)
     {
        return $this->created = $created;
     }

    /**
     * setModified
     *
     * Setter for the modified property
     * @param datetime modified
     *
    */

    public function setModified($modified)
    {
    	$this->modified = $modified;
    	return $modified;
    }

    /**
     * getModified
     *
     * Getter for the modified property
     * @return datetime modified
     *
    */

    public function getModified($modified)
    {
    	return $this->modified = $modified;
    }

 }