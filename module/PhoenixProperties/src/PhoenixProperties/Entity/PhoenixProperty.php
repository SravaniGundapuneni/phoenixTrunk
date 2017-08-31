<?php
/**
 * Property Entity file
 *
 * @category        Toolbox
 * @package         PhoenixProperties
 * @subpackage      Entities
 * @copyright       Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license         All Rights Reserved
 * @version         Release: 13.4
 * @since           File available since release 13.4
 * @author          A. Tate <atate@travelclick.com>
 * @filesource
 */
namespace PhoenixProperties\Entity;

use \Doctrine\ORM\Mapping as ORM;
use \Doctrine\Common\Collections\ArrayCollection;

/**
 * PhoenixProperty
 *
 * Module Tables Config
 *
 *      SchemaHelper::primary('propertyId'),
 *      SchemaHelper::varchar('code'),
 *      SchemaHelper::varchar('name'),
 *      SchemaHelper::text('description'),
 *      SchemaHelper::tinyint('isCorporate'),
 *      SchemaHelper::varchar('address', 100),
 *      SchemaHelper::varchar('suiteApt', 100),
 *      SchemaHelper::varchar('city', 100),
 *      SchemaHelper::varchar('state', 100),
 *      SchemaHelper::varchar('zip', 20),
 *      SchemaHelper::varchar('country'),
 *      SchemaHelper::varchar('tollfreeNumber', 12),
 *      SchemaHelper::varchar('phoneNumber', 22),
 *      SchemaHelper::varchar('faxNumber', 22),
 *      SchemaHelper::varchar('email'),
 *      SchemaHelper::int('userId'),
 *      SchemaHelper::datetime('created'),
 *      SchemaHelper::datetime('modified'),
 *      SchemaHelper::int('status')
 *
 * @ORM\Table(name="phoenixProperties")
 * @ORM\Entity
 */
class PhoenixProperty extends \ListModule\Entity\Entity
{
    /**
     * @var integer id
     *
     * @ORM\Column(name="`propertyId`", type="integer", length = 11)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var string code
     *
     * @ORM\Column(name="`code`", type="string", length=255)
     */
    protected $code;

    /**
     * @var string name
     *
     * @ORM\Column(name="`name`", type="string", length=255)
     */
    protected $name;

    /**
     * @var string description
     *
     * @ORM\Column(name="`description`", type="string")
     */
    protected $description;
    
    /**
     * @var text owsData
     *
     * @ORM\Column(name="`owsData`", type="text")
     */
    protected $owsData;
    /**
     * @var integer isCorporate
     *
     * @ORM\Column(name="`isCorporate`", type="integer", length=1)
     */
    protected $isCorporate;

     /**
     * @var integer userModified
     *
     * @ORM\Column(name="`userModified`", type="integer", length=1)
     */
    protected $userModified;
    
    /**
     * @var string address
     *
     * @ORM\Column(name="`address`", type="string", length=100)
     */
    protected $address;

    /**
     * @var string suiteApt
     *
     * @ORM\Column(name="`suiteApt`", type="string", length=100)
     */
    protected $suiteApt;

    /**
     * @var string city
     *
     * @ORM\Column(name="`city`", type="string", length=100)
     */
    protected $city;

    /**
     * @var string state
     *
     * @ORM\Column(name="`state`", type="string", length=100)
     */
    protected $state;

    /**
     * @var string zip
     *
     * @ORM\Column(name="`zip`", type="string", length=20)
     */
    protected $zip;

    /**
     * @var string country
     *
     * @ORM\Column(name="`country`", type="string", length=100)
     */
    protected $country;

     /**
     * @var string url
     *
     * @ORM\Column(name="`url`", type="string", length=200)
     */
    protected $url;
    
    /**
     * @var string tollfreeNumber
     *
     * @ORM\Column(name="`tollfreeNumber`", type="string", length=12)
     */
    protected $tollfreeNumber;

    /**
     * @var string phoneNumber
     *
     * @ORM\Column(name="`phoneNumber`", type="string", length=22)
     */
    protected $phoneNumber;

    /**
     * @var string faxNumber
     *
     * @ORM\Column(name="`faxNumber`", type="string", length=22)
     */
    protected $faxNumber;

    /**
     * @var string email
     *
     * @ORM\Column(name="`email`", type="string", length=12)
     */
    protected $email;

    /**
     * @var string $latitude
     *
     * @ORM\Column(name="`latitude`", type="string", length=20)
     */
    protected $latitude;

    /**
     * @var string $longitude
     *
     * @ORM\Column(name="`longitude`", type="string", length=20)
     */
    protected $longitude;

    /**
     * @var integer $labelX
     *
     * @ORM\Column(name="`labelX`", type="integer", length=11)
     */
    protected $labelX;

    /**
     * @var integer $labelY
     *
     * @ORM\Column(name="`labelY`", type="integer", length=11)
     */
    protected $labelY;

    /**
     * @var string group
     *
     * @ORM\Column(name="`group`", type="string", length=2)
     */
    protected $group;

    /**
     * @var string photo
     *
     * @ORM\Column(name="`photo`", type="string", length=255)
     */
    protected $photo;

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

    /**
     * @var \Doctrine\Common\Collections\Collection $addons
     *
     * @ORM\OneToMany(targetEntity="PhoenixAddons\Entity\PhoenixAddon", mappedBy="property", cascade={"persist"})
     */
    protected $propertyAddons;

    /**
     * @var \Doctrine\Common\Collections\Collection $attributes
     *
     * @ORM\OneToMany(targetEntity="PhoenixAttributes\Entity\PhoenixAttribute", mappedBy="property", cascade={"persist"})
     */
    protected $propertyAttributes;

    /**
     * @var \Doctrine\Common\Collections\Collection $rooms
     *
     * @ORM\OneToMany(targetEntity="PhoenixRooms\Entity\PhoenixRoom", mappedBy="property", cascade={"persist"})
     */
    protected $propertyRooms;

    /**
     * @var \Doctrine\Common\Collections\Collection $rates
     *
     * @ORM\OneToMany(targetEntity="PhoenixRates\Entity\PhoenixRate", mappedBy="property", cascade={"persist"})
     */
    protected $propertyRates;

    /**
     * @var \Doctrine\Common\Collections\Collection $propertyDynamicItems
     *
     * @ORM\OneToMany(targetEntity="DynamicListModule\Entity\DynamicListModuleItems", mappedBy="property", cascade={"persist"})
     */
    protected $propertyDynamicItems;

    /**
     * @var \Doctrine\Common\Collections\Collection $phoenixSiteComponentId
     *
     * @ORM\OneToMany(targetEntity="PhoenixSite\Entity\PhoenixSiteComponents", mappedBy="property", cascade={"persist"})
     */
    protected $phoenixSiteComponentId;
    
    /**
     * @var string twitter
     *
     * @ORM\Column(name="`twitter`", type="string", length=255)
     */
    protected $twitter;
    
    /**
     * @var string sitePath
     *
     * @ORM\Column(name="`sitePath`", type="string", length=255)
     */
    protected $sitePath;    

            /**
     * @var string facebook
     *
     * @ORM\Column(name="`facebook`", type="string", length=255)
     */
    protected $facebook;
        /**
     * @var string instagram
     *
     * @ORM\Column(name="`instagram`", type="string", length=255)
     */
    protected $instagram;

       /**
     * @var string tempFormat
     *
     * @ORM\Column(name="`tempFormat`", type="string", length=1)
     */
    protected $tempFormat;
    /**
     * @var string history
     *
     * @ORM\Column(name="`history`", type="string")
     */
    protected $history;
     /**
     * @var string name french
     *
     * @ORM\Column(name="`name_fr`", type="string")
     */
    protected $name_fr;    
     /**
     * @var string description french
     *
     * @ORM\Column(name="`description_fr`", type="string")
     */
    protected $description_fr;

     /**
     * @var string address french
     *
     * @ORM\Column(name="`address_fr`", type="string")
     */
    protected $address_fr;

       /**
     * @var string policy
     *
     * @ORM\Column(name="`policy`", type="string")
     */
    protected $policy;
    
    /**
     * @var string city french
     *
     * @ORM\Column(name="`city_fr`", type="string")
     */
    protected $city_fr;
    
    /**
     * @var \Doctrine\Common\Collections\Collection $propertyLanguages
     *
     * @ORM\OneToMany(targetEntity="Languages\Entity\PropertyLanguages", mappedBy="property", cascade={"persist"})
     */
    protected $propertyLanguages;

    /**
     * @var string state french
     *
     * @ORM\Column(name="`state_fr`", type="string")
     */
    protected $state_fr;
    
    /**
     * @var integer $orderNumber
     *
     * @ORM\Column(name="orderNumber", type="integer", length=11)
     */
    protected $orderNumber;
    
    public function __construct()
    {
       $this->propertyAddons     = new ArrayCollection();
       $this->propertyAttributes = new ArrayCollection();
       $this->propertyRooms      = new ArrayCollection();
       $this->propertyRates      = new ArrayCollection();
       $this->propertyDynamicItems = new ArrayCollection();
       $this->propertyLanguages = new ArrayCollection();     
       $this->phoenixSiteComponentId = new ArrayCollection();
    }

    public function getCode()
    {
        return $this->code;
    }

    public function getLatitude()
    {
        return $this->latitude;
    }

    public function getLongitude()
    {
        return $this->longitude;
    }

    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    public function getIsCorporate()
    {
        return $this->isCorporate;
    }

    public function setIsCorporate($isCorporate)
    {
        $this->isCorporate = $isCorporate;

        return $this;
    }
}