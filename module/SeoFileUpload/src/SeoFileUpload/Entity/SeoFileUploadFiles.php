<?php
/**
 * SeoFileUploadFiles Entity File
 * 
 * @category         Toolbox
 * @package          SeoFileUpload
 * @subpackage       Entity
 * @copyright        Copyright (c) 2013 TravelClick, Inc (http://travelclick.com)
 * @license          All Rights Reserved
 * @version          Release 13.5
 * @since            File available since release 13.5
 * @author           Andrew Tate <atate@travelclick.com>
 * @filesource
 */

namespace SeoFileUpload\Entity;

use Phoenix\Module\Entity\EntityAbstract;

use \Doctrine\ORM\Mapping as ORM;
use \Doctrine\Common\Collections\ArrayCollection;

/**
 *
 * @ORM\Table(name="seoFileUploadFiles")
 * @ORM\Entity
 */
 class SeoFileUploadFiles extends \ListModule\Entity\Entity
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

        
}
