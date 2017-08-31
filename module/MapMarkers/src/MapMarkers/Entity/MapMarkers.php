<?php

/**
 * MapMarkers Entity file
 *
 * Module Tables Config
 *
 *     SchemaHelper::primary('attributeId'),
 *     SchemaHelper::varchar('name'),
 *     SchemaHelper::text('description'),
 *     SchemaHelper::tinyint('categories'),
 *     SchemaHelper::int('userId'),
 *     SchemaHelper::datetime('created'),
 *     SchemaHelper::datetime('modified'),
 *     SchemaHelper::int('status'),
 *     SchemaHelper::varchar('category')
 *     
 * SchemaHelper::varchar('url')
 *
 * @category        Toolbox
 * @package         MapMarkers
 * @subpackage      Entities
 * @copyright       Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license         All Rights Reserved
 * @version         Release: 13.6
 * @since           File available since release 13.6
 * @author          A. Tate <atate@travelclick.com>
 * @filesource
 */
namespace MapMarkers\Entity;

use \Doctrine\ORM\Mapping as ORM;

/**
 * MapMarkers
 *
 * @ORM\Table(name="mapMarkers")
 * @ORM\Entity
 */
class MapMarkers extends \ListModule\Entity\Entity
{
    /**
     * @var integer id
     *
     * @ORM\Column(name="markerId", type="integer", length=11)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var string title
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    protected $title;

    /**
     * @var string latitude
     *
     * @ORM\Column(name="latitude", type="string", length=255)
     */
    protected $latitude;

    /**
     * @var string longitude
     *
     * @ORM\Column(name="longitude", type="string", length=255)
     */
    protected $longitude;

    /**
     * @var string description
     *
     * @ORM\Column(name="description", type="string")
     */
    protected $description;

    /**
     * @var string queryString
     *
     * @ORM\Column(name="queryString", type="string", length=255)
     */
    protected $queryString;

    /**
     * @var integer $propertyId
     *
     * @ORM\Column(name="propertyId", type="integer", length=11)
     */
    protected $propertyId;

    /**
     * @var integer $orderNumber
     *
     * @ORM\Column(name="orderNumber", type="integer", length=11)
     */
    protected $orderNumber;
    
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
     * @ORM\Column(name="created", type="datetime")
     */
    protected $created;

    /**
     * @var datetime modified
     *
     * @ORM\Column(name="modified", type="datetime")
     */
    protected $modified;

    /**
     * @var integer $status
     *
     * @ORM\Column(name="status", type="integer", length=1)
     */
    protected $status;
     /**
     * @var string url
     *
     * @ORM\Column(name="url", type="string", length=255)
     */
    protected $url;

    /**
     * @var DynamicListModule\Entity\DynamicListModuleItems $item
     *
     * @ORM\OneToOne(targetEntity="MapMarkers\Entity\MapMarkerItems", inversedBy="mapMarkerData", cascade="persist")
     * @ORM\JoinColumn(name="item", referencedColumnName="itemId")
     */
    protected $item;
    
    /**
     * @var string $category
     *
     * @ORM\Column(name="category", type="integer", length=255)
     */
    protected $category;

    public function getId()
    {
        return $this->id;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getLatitude()
    {
        return $this->latitude;
    }

    public function getLongitude()
    {
        return $this->longitude;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getQueryString()
    {
        return $this->queryString;
    }

    public function getPropertyId()
    {
        return $this->propertyId;
    }

    public function getOrderNumber()
    {
        return $this->orderNumber;
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

    public function getItem()
    {
        return $this->item;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function getCategory()
    {
        return $this->category;
    }
}