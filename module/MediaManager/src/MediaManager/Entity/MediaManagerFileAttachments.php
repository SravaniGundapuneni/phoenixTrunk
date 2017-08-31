<?php
/**
 * MediaManagerFileAttachments Entity File
 * 
 * @category         Toolbox
 * @package          MediaManager
 * @subpackage       Entity
 * @copyright        Copyright (c) 2013 TravelClick, Inc (http://travelclick.com)
 * @license          All Rights Reserved
 * @version          Release 13.5
 * @since            File available since release 13.5
 * @author           Andrew Tate <atate@travelclick.com>
 * @filesource
 */

namespace MediaManager\Entity;

use Phoenix\Module\Entity\EntityAbstract;
use \Doctrine\ORM\Mapping as ORM;

/**
 *
 * @ORM\Table(name="mediaManagerFileAttachments")
 * @ORM\Entity
 */
class MediaManagerFileAttachments extends EntityAbstract
{
    /**
     * @var integer id
     * 
     * @ORM\Column(name="attachId", type="integer", length=11)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     *
     */
    protected $id;

    /**
     * @var integer fileId
     *
     * @ORM\Column(name="file", type="integer")
     */
    protected $file;

    /**
     * @var string langCode
     *
     * @ORM\Column(name="langCode", type="string", length=3)
     */
    protected $langCode;

    /**
     * @var string parentModule
     *
     * @ORM\Column(name="parentModule", type="string", length=255)
     */
    protected $parentModule;

    /**
     * @var integer parentItemId
     *
     * @ORM\Column(name="parentItemId", type="integer")
     */
    protected $parentItemId;

    /**
     * @var integer orderNumber
     *
     * @ORM\Column(name="orderNumber", type="integer")
     */    
    protected $orderNumber;

    /**
     * @var integer propertyId
     *
     * @ORM\Column(name="propertyId", type="integer")
     */
    protected $propertyId;
    
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
     * @var datetime modified
     * 
     * @ORM\Column(name="modified", type="datetime")
     */
    protected $modified;

    /**
     * @var datetime created
     * 
     * @ORM\Column(name="created", type="datetime") 
     */
    protected $created;

    /**
     * @var integer status
     *
     * @ORM\Column(name="status", type="integer")
     */
    protected $status;

    /**
     * @var string altText
     *     
     * @ORM\Column(name="altText", type="string", length=255)
     */
    protected $altText;

    /**
     * @var MediaManager\Entity\MediaManagerFiles $mediaManagerFiles
     *
     * @ORM\ManyToOne(targetEntity="MediaManager\Entity\MediaManagerFiles", inversedBy="mediaManagerFileAttachments", fetch="EAGER")
     * @ORM\JoinColumn(name="file", referencedColumnName="fileId")
     */
    protected $mediaManagerFile;

    public function getMediaManagerFile()
    {
        return $this->mediaManagerFile;
    } 

    public function setMediaManagerFile($mediaManagerFile)
    {
        $this->mediaManagerFile = $mediaManagerFile;

        return $this;
    }

    /*
    public function getFile(){


        return $this->file;
    }
    */

   /* public function getThumbnail()
    {
        return $this->thumbnail;
    }

    public function setThumbnail($thumbnail)
    {
        $this->thumbnail = $thumbnail;

        return $this;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }
*/
    public function getFile()
    {
        return $this->file;
    }

    public function setFile($file)
    {
        $this->file = $file;

        return $this;
    }

    public function getOrderNumber()
    {
        return $this->orderNumber;
    }

    public function setOrderNumber($orderNumber)
    {
        $this->orderNumber = $orderNumber;

        return $this;
    }

}