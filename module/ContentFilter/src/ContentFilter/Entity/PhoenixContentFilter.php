<?php
/**
 * Room Entity file
 *
 * @category        Toobox
 * @package         ContentFilter
 * @subpackage      Entities
 * @copyright       Copyright (c) 2013 Travelclick, Inc (http://www.travelclick.com)
 * @license         All Rights Reserved
 * @version         Release: 13.4
 * @since           File available since release 13.4
 * @author          Saurabh Shirgaonkar <sshirgaonkar@travelclick.com>
 * @filesource
 */
namespace ContentFilter\Entity;

use \Doctrine\ORM\Mapping as ORM;

 /**
  * PhoenixRoom
  *
  * Module tables config
  *
  *     SchemaHelper::primary('roomId'),
  *     SchemaHelper::varchar('code'),
  *     SchemaHelper::varchar('name'),
  *     SchemaHelper::text('description'),
  *     SchemaHelper::decimal('price', 10, 2, 'NO', '0.00'),
  *     SchemaHelper::varchar('bookingLink'),
  *     SchemaHelper::tinyint('categories'),
  *     SchemaHelper::tinyint('showMainImage'),
  *     SchemaHelper::varchar('bedType', 30),
  *     SchemaHelper::tinyint('featured'),
  *     SchemaHelper::tinyint('isCorporate')
  *
  * @ORM\Table(name="phoenixRooms")
  * @ORM\Entity
  */
class PhoenixContentFilter extends \ListModule\Entity\Entity
{
    /**
     * @var integer id
     *
     * @ORM\Column(name="roomId", type="integer", length=11)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var string code
     *
     * @ORM\Column(name="code", type="string", length=255)
     */
    protected $code;

    /**
     * @var string name
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    protected $name;

    /**
     * @var string description
     *
     * @ORM\Column(name="description", type="string", length=255)
     */
    protected $description;

    /**
     * @var string bedType
     *
     * @ORM\Column(name="bedType", type="string", length=30)
     */
    protected $bedType;

    /**
     * @var string maxOccupancy
     *
     * @ORM\Column(name="maxOccupancy", type="string", length=11)
     */
    protected $maxOccupancy;

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
     * @var integer $userId
     *
     * @ORM\Column(name="userId", type="integer", length=11)
     */
    protected $userId;

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
     * @var string $virtualTour
     *
     * @ORM\Column(name="virtualTour", type="string", length=200)
     */
    protected $virtualTour;
    
    /**
     * @var PhoenixProperties\Entity\Property $property
     *
     * @ORM\ManyToOne(targetEntity="PhoenixProperties\Entity\PhoenixProperty", inversedBy="propertyRooms")
     * @ORM\JoinColumn(name="property", referencedColumnName="propertyId")
     */
    protected $property;
    
    /**
     * @var text owsData
     *
     * @ORM\Column(name="`owsData`", type="text")
     */
    protected $owsData;

     /**
     * @var integer userModified
     *
     * @ORM\Column(name="`userModified`", type="integer", length=1)
     */
    protected $userModified;
    
    /**
     * @var integer isCorporate
     *
     * @ORM\Column(name="isCorporate", type="integer", length=11)
     */
    protected $isCorporate;
    
     /**
     * @var string description french
     *
     * @ORM\Column(name="description_fr", type="string", length=255)
     */
    protected $description_fr;
    
    /**
     * @var string name french
     *
     * @ORM\Column(name="name_fr", type="string", length=255)
     */
    protected $name_fr;
	   
    /**
     * @var string $bookingLink
     *
     * @ORM\Column(name="bookingLink", type="string", length=100)
     */
    protected $bookingLink;

    /**
     * @var  string $category
     *
     * @ORM\Column(name="category", type="integer", length=11)
     */
    protected $category;

    /**
     * @var string $price
     *
     * @ORM\Column(name="price", type="decimal", length=10, scale=2)
     */
    protected $price;
}