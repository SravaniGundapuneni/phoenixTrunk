<?php
/**
 * MediaManagerFiles Entity File
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
use \Doctrine\Common\Collections\ArrayCollection;

/**
 *
 * @ORM\Table(name="mediaManagerFiles")
 * @ORM\Entity
 */
 class MediaManagerFiles extends EntityAbstract
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
      * @var text name
      * 
      * @ORM\Column(name="name", type="string", length=255)
     */
     protected $name;


     /**
      * @var text name
      * 
      * @ORM\Column(name="origName", type="string", length=255)
     */
     protected $origName;

     /**
      * @var text path
      * 
      * @ORM\Column(name="path", type="string")
     */
     protected $path;

     
     /**
      * @var text type
      * 
      * @ORM\Column(name="type", type="string")
     */
     protected $type;
     
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
      * @var integer propertyId
      *
      * @ORM\Column(name="propertyId", type="integer")
     */
     protected $propertyId;

    /**
     * @var \Doctrine\Common\Collections\Collection $mediaManagerFileAttachments
     *
     * @ORM\OneToMany(targetEntity="MediaManager\Entity\MediaManagerFileAttachments", mappedBy="mediaManagerFile", cascade={"persist"})
     */
    protected $mediaManagerFileAttachments;

    public function __construct()
    {
       $this->mediaManagerFileAttachments = new ArrayCollection();
    }

    public function getPath()
    {
        return $this->path;
    }

    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }
    
    public function getFilePath()
    {
        
        return $this->path;  
    }

    public function getName(){

        return $this->name;

    }
    public function getType(){

        return $this->type;

    }

}


