<?php
/**
 * MediaManagerFile
 *
 * @category                              Toolbox
 * @package                               MediaManagerFile
 * @subpackage                            Entity
 * @copyright                             Copyright (c) 2013 Travelclick, Inc (http://travelclick.com)
 * @license                               All Rights Reserved
 * @version                               Release 13.5
 * @since                                 File available since release 13.5
 * @author                                Kevin Davis <kedavis@travelclick.com>
 * @filesource
 */

namespace MediaManager\Entity;

use \Doctrine\ORM\Mapping as ORM;

/**
 * MediaManagerFile
 *
 * @category                              Toolbox
 * @package                               MediaManagerFile
 * @subpackage                            Entity
 * @copyright                             Copyright (c) 2013 Travelclick, Inc (http://travelclick.com)
 * @license                               All Rights Reserved
 * @version                               Release 13.5
 * @since                                 File available since release 13.5
 * @author                                Kevin Davis <kedavis@travelclick.com>
 */

/**
 *
 * @ORM\Table(name="mediafileattach")
 * @ORM\Entity
 *
 */

class MediaManagerFile
{
	/**
	 * @var integer id
	 *
	 * @ORM\Column(name="attachId", type="integer", length=11 )
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="IDENTITY")
	 *
	*/
	protected $id;

	/**
	 * @var datetime modified
	 *
	 * @ORM\Column(name="modified", type="datetime")
	 *
	*/

	protected $modified;

	/**
	 * @var datetime created
	 *
	 * @ORM\Column(name="created", type="datetime")
	 *
	*/

	protected $created;
		
    /**
     * @var integer fileId
     *
     * @ORM\Column(name="fileId", type="integer")
     *
    */

    protected $fileId;

    /**
     * @var text langCode
     *
     * @ORM\Column(name="langCode", type="text")
     *
    */

    protected $langCode;

    /**
     * @var text parentTable
     *
     * @ORM\Column(name="parentTable", type="text")
     *
    */

    protected $parentTable;

    /**
     * @var integer parentRowId
     *
     * @ORM\Column(name="parentRowId", type="integer")
     *
    */

    protected $parentRowId;

    /**
     * @var integer status
     *
     * @ORM\Column(name="status", type="integer")
     *
    */

    protected $status;

    /**
     * @var integer version
     *
     * @ORM\Column(name="version", type="integer")
     *
    */

    protected $version;

    /**
     * @var integer itemId
     *
     * @ORM\Column(name="itemId", type="integer")
     *
    */

    protected $itemId;

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
     * Getter for the id proprty
     * @return integer id
     *
    */

    public function getId($id)
    {
    	return $this->id = $id;
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
    	return $this;
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

    /**
     * setCreated
     *
     * Setter for the created property
     * @param datetime created
     *
    */

    public function setCreated($created)
    {
    	$this->created = $created;
    	return $this;
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
     * setFileId
     *
     * Setter for the fileId
     * @param integer fileId
     *
    */

    public function setFileId($fileId)
    {
    	$this->fileId = $fileId;
    	return $this;
    }

    /**
     * getFileId
     *
     * Getter for the fileId
     * @return integer fileId
     *
    */

    public function getFileId($fileId)
    {
    	return $this->getFileId = $fileId;
    }

    /**
     * setLangCode
     *
     * Setter for the langCode
     * @param text langCode
     *
    */

    public function setLangCode($langCode)
    {
    	$this->langCode = $langCode;
    	return $this;
    }

    /**
     * getLangCode
     *
     * Getter for the langCode
     * @return text langCode
     *
    */

    public function getLangCode($langCode)
    {
    	return $this->langCode = $langCode;
    }

    /**
     * setParentTable
     *
     * Setter for the parentTable
     * @param text parentTable
     *
    */

    public function setParentTable($parentTable)
    {
    	$this->parentTable = $parentTable;
    	return $this;
    }

    /**
     * getParentTable
     *
     * Getter for the parentTable
     * @return text parentTable
     *
    */

    public function getParentTable($parentTable)
    {
    	return $this->parentTable = $parentTable;
    }

    /**
     * setParentRowId
     *
     * Setter for the parentRowId
     * @param integer parentRowId
     *
    */

    public function setParentRowId($parentRowId)
    {
    	$this->parentRowId = $parentRowId;
    	return $this;
    }

    /**
     * getParentRowId
     *
     * Getter for the parentRowId
     * @return integer parentRowId
     *
    */

    public function getParentRowId($parentRowId)
    {
    	return $this->parentRowId = $parentRowId;
    }

    /**
     * setOrder
     *
     * Setter for the order
     * @param integer order
     *
    */

    public function setOrder($order)
    {
    	$this->order = $order;
    	return $this;
    }

    /**
     * getOrder
     *
     * Getter for the order
     * @return integer order
     *
    */

    public function getOrder($order)
    {
    	return $this->order = $order;
    }

    /**
     * setStatus
     *
     * Setter for the status
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
     * Getter for the status
     * @return integer status
     *
    */

    public function getStatus($status)
    {
    	return $this->status = $status;
    }

    /**
     * setVersion
     *
     * Setter for the version
     * @param integer version
     *
    */

    public function setVersion($version)
    {
    	$this->version = $version;
    	return $this;
    }

    /**
     * getVersion
     *
     * Getter for the version
     * @return integer version
     *
    */

    public function getVersion($version)
    {
    	return $this->version = $version;
    }

    /**
     * setItemId
     *
     * Setter for the itemId
     * @param integer itemId
     *
    */

    public function setItemId($itemId)
    {
    	$this->itemId = $itemId;
    	return $this;
    }

    /**
     * getItemId
     *
     * Getter for the itemId
     * @return integer itemId
     *
    */

    public function getItemId($itemId)
    {
    	return $this->itemId = $itemId;
    }

}