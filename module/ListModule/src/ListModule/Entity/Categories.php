<?php
/**
 * Property Entity file
 *
 * @category        Toolbox
 * @package         ListModule
 * @subpackage      Entities
 * @copyright       Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license         All Rights Reserved
 * @version         Release: 13.5.5
 * @since           File available since release 13.5.5
 * @author          A. Tate <atate@travelclick.com>
 * @filesource
 */
namespace ListModule\Entity;

use \Doctrine\ORM\Mapping as ORM;
use \Doctrine\Common\Collections\ArrayCollection;

/**
 * Categories
 *
 * Module Tables Config
 *
 *           SchemaHelper::primary('categoryId'),
 *           SchemaHelper::int('propertyId'),
 *           SchemaHelper::varchar('name', 255),
 *           SchemaHelper::varchar('module', 255),
 *           SchemaHelper::tinyint('allProperties'),
 *           SchemaHelper::int('status'),
 *           SchemaHelper::int('userId'),
 *           SchemaHelper::datetime('modified'),
 *           SchemaHelper::datetime('created')
 *
 * @ORM\Table(name="categories")
 * @ORM\Entity
 */
class Categories  extends \ListModule\Entity\Entity
{
    /**
     * @var integer id
     *
     * @ORM\Column(name="`categoryId`", type="integer", length = 11)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var integer propertyId
     *
     * @ORM\Column(name="`propertyId`", type="integer", length = 11)
     */
    protected $propertyId;

    /**
     * @var string name
     *
     * @ORM\Column(name="`name`", type="string", length=255)
     */
    protected $name;

    /**
     * @var string module
     *
     * @ORM\Column(name="`module`", type="string", length=255)
     */
    protected $module;
    
  
    /**
     * @var integer allProperties
     *
     * @ORM\Column(name="`allProperties`", type="integer", length=1)
     */
    protected $allProperties;
    
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
     * @var datetime created
     *
     * @ORM\Column(name="`created`", type="datetime")
     */
    protected $created;

    /**
     * @var datetime modified
     *
     * @ORM\Column(name="`modified`", type="datetime")
     */
    protected $modified;

    /**
     * @var integer $status
     *
     * @ORM\Column(name="`status`", type="integer", length=1)
     */
    protected $status;

    public function getId()
    {
        return $this->id;
    }

    public function getPropertyId()
    {
        return $this->propertyId;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getModule()
    {
        return $this->module;
    }

    public function getAllProperties()
    {
        return $this->allProperties;
    }

    public function getCreatedUserId()
    {
        return $this->createdUserId;
    }

    public function getModifiedUserId()
    {
        return $this->modifiedUserId; 
    }

    public function getCreated()
    {
        return $this->created;
    }

    public function getModified()
    {
        return $this->modified;
    }

    public function getStatus()
    {
        return $this->status;
    }

}