<?php
/**
 * Pages Entity file
 *
 * @category    Toolbox
 * @package     Pages
 * @subpackage  Entities
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.4
 * @since       File available since release 13.4
 * @author      A. Tate <atate@travelclick.com>
 * @filesource
 */
namespace Pages\Entity;

use \Doctrine\ORM\Mapping as ORM;

/**
 * Pages
 *
 * @ORM\Table(name="pages")
 * @ORM\Entity
 */
class Pages extends \ListModule\Entity\Entity
{
    /**
     * @var integer id
     *
     * @ORM\Column(name="pageId", type="integer", length = 11)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * 
     */
    protected $id;

    /**
     * @ORM\Column(name="dataSection", type="string", length = 255)
     */
    protected $dataSection;

    /**
     * @ORM\Column(name="pageKey", type="string", length = 50)
     */
    protected $pageKey;

    /**
     * @ORM\Column(name="template", type="string", length = 50)
     */
    protected $template;

    /**
     * @ORM\Column(name="blocks", type="string", length = 2048)
     */
    protected $blocks;

    /**
     * @ORM\Column(name="categoryId", type="integer", length = 11)
     */
    protected $categoryId;

    /**
     * @ORM\Column(name="additionalParams", type="string", length=255)
     */
    protected $additionalParams;

    /**
     * @ORM\Column(name="startDate", type="datetime")
     */
    protected $startDate;

    /**
     * @ORM\Column(name="autoExpire", type="datetime")
     */
    protected $autoExpire;

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
     * @ORM\Column(name="created", type="datetime")
     */
    protected $created;

    /**
     * @ORM\Column(name="modified", type="datetime")
     */
    protected $modified;

    /**
     * @ORM\Column(name="status", type="integer", length = 11)
     */
    protected $status;
    /**
     * @ORM\Column(name="pageHeading", type="string", length = 9999)
     */    
    protected $pageHeading;
    /**
     * @ORM\Column(name="pageType", type="string", length = 9999)
     */    
    protected $pageType;

    /**
     * @ORM\Column(name="blocks2", type="text", length = 50000)
     */       
    protected $blocks2;
    
    /**
     * @ORM\Column(name="eventName", type="string", length = 255)
     */
    protected $eventName;

    /**
     * @var Pages\Entity\PagesItems $item
     *
     * @ORM\OneToOne(targetEntity="Pages\Entity\PagesItems", inversedBy="pagesData", cascade="persist")
     * @ORM\JoinColumn(name="item", referencedColumnName="itemId")
     */
    protected $item;    

    public function getAutoExpire()
    {
        return $this->autoExpire;
    }

    public function getAdditionalParams()
    {
        return $this->additionalParams;
    }

    public function getBlocks()
    {
        return $this->blocks;
    }

    public function getBlocks2()
    {
        return $this->blocks2;
    }

    public function getCategoryId()
    {
        return $this->categoryId;
    }

    public function getCreated()
    {
        return $this->created;
    }

    public function getCreatedUserId()
    {
        return $this->createdUserId;
    }

    public function getDataSection()
    {
        return $this->dataSection;
    }

    public function getEventName()
    {
        return $this->eventName;
    }
    
    public function getId()
    {
        return $this->id;
    }

    public function getModified()
    {
        return $this->modified;
    }

    public function getModifiedUserId()
    {
        return $this->modifiedUserId;
    }

    public function getPageHeading()
    {
        return $this->pageHeading;
    }

    public function getPageKey()
    {
        return $this->pageKey;
    }

    public function getPageType()
    {
        return $this->pageType;
    }

    public function getStartDate()
    {
        return $this->startDate;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getTemplate()
    {
        return $this->template;
    }
    
    public function setPageType($pageType)
    {
        $this->pageType = $pageType;

        return $this;
    }
    
    public function getPageId()
    {
        return $this->id;
    }
}
