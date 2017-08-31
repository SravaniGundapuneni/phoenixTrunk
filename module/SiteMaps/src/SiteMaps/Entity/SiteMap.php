<?php
/**
 * Property Entity file
 *
 * @category        Toolbox
 * @package         SiteMap
 * @subpackage      Entities
 * @copyright       Copyright (c) 2014 TravelClick, Inc (http://www.travelclick.com)
 * @license         All Rights Reserved
 * @version         Release: 13.5
 * @since           File available since release 13.5
 * @author          Alex Kotsores <akotsores@travelclick.com>
 * @filesource
 */
namespace SiteMaps\Entity;

use \Doctrine\ORM\Mapping as ORM;
use \Doctrine\Common\Collections\ArrayCollection;

/**
 * SiteMap
 * @ORM\Table(name="sitemaps")
 * @ORM\Entity
 */
class SiteMap extends \ListModule\Entity\Entity
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
     * @var integer userId
     *
     * @ORM\Column(name="`userId`", type="integer", length=11)
     */
    protected $userId;
        
    /**
     * @var string pageKey
     *
     * @ORM\Column(name="`pageKey`", type="string", length=255)
     */
    protected $pageKey;
	/**
     * @var integer visible
     *
     * @ORM\Column(name="`visible`", type="integer", length=11)
     */
    protected $visible;
	/**
     * @var integer dynamicPage
     *
     * @ORM\Column(name="`dynamicPage`", type="integer", length=11)
     */
    protected $dynamicPage;
    /**
     * @var integer orderNumber
     *
     * @ORM\Column(name="`orderNumber`", type="integer", length=11)
     */
    protected $orderNumber;
	/**
     * @var SiteMaps\Entity\SiteMapSection $dataSectionId
     *
     * @ORM\ManyToOne(targetEntity="SiteMaps\Entity\SiteMapSection", inversedBy="siteMaps")
     * 
     * @ORM\JoinColumn(name="dataSectionId", referencedColumnName="dataSectionId" )
     *
     */
    protected $dataSectionId; 

    /**
     * @var SiteMaps\Entity\SiteMapsItems $item
     *
     * @ORM\OneToOne(targetEntity="SiteMaps\Entity\SiteMapsItems", inversedBy="siteMapsData", cascade="persist")
     * @ORM\JoinColumn(name="item", referencedColumnName="itemId")
     */
    protected $item;	
}