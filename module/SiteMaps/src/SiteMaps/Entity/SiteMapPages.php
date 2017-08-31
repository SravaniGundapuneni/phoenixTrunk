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
namespace SiteMaps\Entity;

use \Doctrine\ORM\Mapping as ORM;

/**
 * Pages
 *
 * @ORM\Table(name="pages")
 * @ORM\Entity
 */
class SiteMapPages extends \ListModule\Entity\Entity
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
    
    public function getPageType()
    {
        return $this->pageType;
    }
   
    
    public function setPageType($pageType)
    {
       $this->pageType = $pageType;
    }

    /**
     * @ORM\Column(name="blocks2", type="text", length = 50000)
     */       
    protected $blocks2;
    
    /**
     * @ORM\Column(name="eventName", type="string", length = 255)
     */
    protected $eventName;
}
