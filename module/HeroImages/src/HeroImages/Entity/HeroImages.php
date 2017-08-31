<?php
/**
 * Property Entity file
 *
 * @category        Toolbox
 * @package         HeroImages
 * @subpackage      Entities
 * @copyright       Copyright (c) 2014 TravelClick, Inc (http://www.travelclick.com)
 * @license         All Rights Reserved
 * @version         Release: 13.5
 * @since           File available since release 13.5
 * @author          Daniel Yang <dyang@travelclick.com>
 * @filesource
 */
namespace HeroImages\Entity;

use \Doctrine\ORM\Mapping as ORM;
use \Doctrine\Common\Collections\ArrayCollection;

/**
 * HeroImages
 *
 * Module Tables Config
 *
 *      SchemaHelper::primary('itemId'),
 *      SchemaHelper::varchar('title'),
 *      SchemaHelper::datetime('created'),
 *      SchemaHelper::tinyint('status'),
 *      SchemaHelper::datetime('modified'),
 *      SchemaHelper::int('order'),
 *      SchemaHelper::int('userId'),
 *      SchemaHelper::int('pageId'),
 *      SchemaHelper::varchar('url'),
 *      SchemaHelper::int('propertyId'),
 *      SchemaHelper::varchar('module'),
 *      SchemaHelper::int('moduleItemId'),
 *      SchemaHelper::varchar('moduleItemTitle')
 *
 * @ORM\Table(name="heroImages")
 * @ORM\Entity
 */
class HeroImages extends \ListModule\Entity\Entity
{
    /**
     * @var integer itemId
     *
     * @ORM\Column(name="`itemId`", type="integer", length = 11)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var string title
     *
     * @ORM\Column(name="`title`", type="string", length=255)
     */
    protected $title;
    
    /**
     * @var datetime created
     *
     * @ORM\Column(name="`created`", type="datetime")
     */
    protected $created;

    /**
     * @var integer status
     *
     * @ORM\Column(name="`status`", type="integer", length=11)
     */
    protected $status;
    
    /**
     * @var datetime modified
     *
     * @ORM\Column(name="`modified`", type="datetime")
     */
    protected $modified;  
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
     * @var integer propertyId
     *
     * @ORM\Column(name="`propertyId`", type="integer", length=11)
     */
    protected $propertyId;
    
     /**
     * @var integer pageId
     *
     * @ORM\Column(name="`pageId`", type="integer", length=11)
     */
    protected $pageId;

    /**
     * @var string module
     *
     * @ORM\Column(name="`module`", type="string", length=255)
     */
    protected $module;
    
    /**
     * @var HeroImages\Entity\HeroImageItems $item
     *
     * @ORM\OneToOne(targetEntity="HeroImages\Entity\HeroImageItems", inversedBy="heroImagesData", cascade="persist")
     * @ORM\JoinColumn(name="item", referencedColumnName="itemId")
     */
    protected $item;        
        
    public function getPageId()
    {
        return $this->pageId;
    }
    
    public function setPageId($pageId)
    {
        $this->pageId = $pageId;
    }
}