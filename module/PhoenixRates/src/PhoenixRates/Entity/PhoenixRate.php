<?php
/**
 * PhoenixRates Entity file
 *
 * Module tables config
 *
 *     SchemaHelper::primary('rateId'),
 *     SchemaHelper::varchar('code'),
 *     SchemaHelper::varchar('name'),
 *     SchemaHelper::text('description'),
 *     SchemaHelper::text('policy'),
 *     SchemaHelper::decimal('price', 10, 2, 'NO', '0.00'),
 *     SchemaHelper::varchar('bookingLink'),
 *     SchemaHelper::tinyint('categories'),
 *     SchemaHelper::tinyint('showMainImage'),
 *     SchemaHelper::datetime('startDate'),
 *     SchemaHelper::datetime('autoExpiry'),
 *     SchemaHelper::tinyint('featured'),
 *     SchemaHelper::tinyint('isMember'),
 *
 * @category    Toolbox
 * @package     PhoenixRates
 * @subpackage  Entities
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.4
 * @since       File available since release 13.4
 * @author      A. Tate <atate@travelclick.com>
 * @filesource
 */
namespace PhoenixRates\Entity;

use \Doctrine\ORM\Mapping as ORM;

/**
 * PhoenixRates
 *
 * @ORM\Table(name="phoenixRates")
 * @ORM\Entity
 */
class PhoenixRate extends \ListModule\Entity\Entity
{
    /**
     * @var integer id
     *
     * @ORM\Column(name="rateId", type="integer", length = 11)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     *
     */
    protected $id;

    /**
     * @var string $code
     *
     * @ORM\Column(name="code", type="string", length=100)
     */
    protected $code;

    /**
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    protected $name;

    /**
     * @var string $description
     *
     * @ORM\Column(name="description", type="string")
     */
    protected $description;

    /**
     * @var string $policy
     *
     * @ORM\Column(name="policy", type="string")
     */
    protected $policy;

    /**
     * @var datetime startDate
     *
     * @ORM\Column(name="startDate", type="datetime")
     */
    protected $startDate;

    /**
     * @var datetime autoExpiry
     *
     * @ORM\Column(name="autoExpiry", type="datetime")
     */
    protected $autoExpiry;

    /**
     * @var string $bookingLink
     *
     * @ORM\Column(name="bookingLink", type="string", length=100)
     */
    protected $bookingLink;

    /**
     * @var  string $category
     *
     * @ORM\Column(name="category", type="string")
     */
    protected $category;

    /**
     * @var string $price
     *
     * @ORM\Column(name="price", type="decimal", length=10, scale=2)
     */
    protected $price;

    /**
     * @var integer categories
     *
     * @ORM\Column(name="categories", type="integer", length=1)
     */
    protected $categories;

    /**
     * @var integer showMainImage
     *
     * @ORM\Column(name="showMainImage", type="integer", length=11)
     */
    protected $showMainImage;

    /**
     * @var integer featured
     *
     * @ORM\Column(name="featured", type="integer", length=11)
     */
    protected $featured;
     /**
     * @var integer brandFeatured
     *
     * @ORM\Column(name="brandFeatured", type="integer", length=1)
     */
    protected $brandFeatured;
        /**
     * @var integer specialOffers
     *
     * @ORM\Column(name="specialOffers", type="integer", length=11)
     */
    protected $specialOffers;
    
        /**
     * @var integer corporateFeatured
     *
     * @ORM\Column(name="corporateFeatured", type="integer", length=11)
     */
    protected $corporateFeatured;
    
    /**
     * @var integer isMember
     *
     * @ORM\Column(name="isMember", type="integer", length=11)
     */
    protected $isMember;

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
     * @var string $membership
     *
     * @ORM\Column(name="membership", type="string")
     */
    protected $membership;

    /**
     * @var string $rateTypeCategory
     *
     * @ORM\Column(name="rateTypeCategory", type="string")
     */
    protected $rateTypeCategory;
    /**
     * @var text owsData
     *
     * @ORM\Column(name="`owsData`", type="text")
     */
    protected $owsData;
    
    /**
     * @var text terms
     *
     * @ORM\Column(name="`terms`", type="text")
     */
    protected $terms;
    
     /**
     * @var integer userModified
     *
     * @ORM\Column(name="`userModified`", type="integer", length=1)
     */
    protected $userModified;
    
    /**
     * @var PhoenixProperties\Entity\Property $property
     *
     * @ORM\ManyToOne(targetEntity="PhoenixProperties\Entity\PhoenixProperty", inversedBy="propertyRates")
     * @ORM\JoinColumn(name="property", referencedColumnName="propertyId")
     */
    protected $property;
    
    /**
     * @var string $description french
     *
     * @ORM\Column(name="description_fr", type="string")
     */
    protected $description_fr;
    
    /**
     * @var string name french
     *
     * @ORM\Column(name="name_fr", type="string")
     */
    protected $name_fr;
    /**
     * @var integer $orderNumber
     *
     * @ORM\Column(name="orderNumber", type="integer", length=11) 
     */
    protected $orderNumber; 

    /**
     * @var PhoenixRates\Entity\PhoenixRatesItems $item
     *
     * @ORM\OneToOne(targetEntity="PhoenixRates\Entity\PhoenixRatesItems", inversedBy="phoenixRatesData", cascade="persist")
     * @ORM\JoinColumn(name="item", referencedColumnName="itemId")
     */
    protected $item;

    public function getOrderNumber()
    {
        return $this->orderNumber;
    }

    public function setOrderNumber($orderNumber)
    {
        $this->orderNumber = $orderNumber;

        return $this;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    public function getPolicy()
    {
        return $this->policy;
    }

    public function setPolicy($policy)
    {
        $this->policy = $policy;

        return $this;
    }

    public function getStartDate()
    {
        return $this->startDate;
    }

    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getAutoExpiry()
    {
        return $this->autoExpiry;
    }

    public function setAutoExpiry($autoExpiry)
    {
        $this->autoExpiry = $autoExpiry;

        return $this;
    }

    public function getProperty()
    {
        return $this->property;
    }

    public function setProperty($property)
    {
        $this->property = $property;

        return $this;
    }

    public function getBrandFeatured()
    {
        return $this->brandFeatured;
    }

    public function setBrandFeatured($brandFeatured)
    {
        $this->brandFeatured = $brandFeatured;

        return $this;
    }

    public function getSpecialOffers()
    {
        return $this->specialOffers;
    }

    public function setSpecialOffers($specialOffers)
    {
        $this->specialOffers = $specialOffers;

        return $this;
    }

    public function getBookingLink()
    {
        return $this->bookingLink;
    }

    public function setBookingLink($bookingLink)
    {
        $this->bookingLink = $bookingLink;

        return $this;
    }

    public function getFeatured()
    {
        return $this->featured;
    }

    public function setFeatured($featured)
    {
        $this->featured = $featured;

        return $this;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    public function getCategories()
    {
        return $this->categories;
    }

    public function setCategories($categories)
    {
        $this->categories = $categories;

        return $this;
    }

    public function getCategory()
    {
        return $this->category;
    }

    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    public function getTerms()
    {
        return $this->terms;
    }

    public function setTerms($terms)
    {
        $this->terms = $terms;

        return $this;
    }

    public function getRateTypeCategory()
    {
        return $this->rateTypeCategory;
    }

    public function setRateTypeCategory($rateTypeCategory)
    {
        $this->rateTypeCategory = $rateTypeCategory;

        return $this;
    }

    public function getUserModified()
    {
        return $this->userModified;
    }

    public function setUserModified($userModified)
    {
        $this->userModified = $userModified;

        return $this;
    }

    public function getOwsData()
    {
        return $this->owsData;
    }

    public function setOwsData($owsData)
    {
        $this->owsData = $owsData;

        return $this;
    }

    public function getCorporateFeatured()
    {
        return $this->corporateFeatured;
    }

    public function setCorporateFeatured($corporateFeatured)
    {
        $this->corporateFeatured = $corporateFeatured;

        return $this;
    }

    public function getShowMainImage()
    {
        return $this->showMainImage;
    }

    public function setShowMainImage($showMainImage)
    {
        $this->showMainImage = $showMainImage;

        return $this;
    }

    public function getIsMember()
    {
        return $this->isMember;
    }

    public function setIsMember($isMember)
    {
        $this->isMember = $isMember;

        return $this;
    }

    public function getMembership()
    {
        return $this->membership;
    }

    public function setMembership($membership)
    {
        $this->membership = $membership;

        return $this;
    }

    public function getDescription_fr()
    {
        return $this->description_fr;
    }

    public function setDescription_fr($description_fr)
    {
        $this->description_fr = $description_fr;

        return $this;
    }

    public function getName_fr()
    {
        return $this->name_fr;
    }

    public function setName_fr($name_fr)
    {
        $this->name_fr = $name_fr;

        return $this;
    }
}