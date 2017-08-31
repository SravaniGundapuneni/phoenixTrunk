<?php
/**
 * PhoenixAddons Entity file
 *
 * Module tables config
 *
 *     SchemaHelper::primary('addonId'),
 *     SchemaHelper::varchar('code'),
 *     SchemaHelper::varchar('name'),
 *     SchemaHelper::text('description'),
 *     SchemaHelper::decimal('price', 10, 2, 'YES', '0.00'),
 *     SchemaHelper::tinyint('categories'),
 *     SchemaHelper::tinyint('showMainImage'),
 *     SchemaHelper::varchar('bookingLink'),
 *     SchemaHelper::datetime('startDate'),
 *     SchemaHelper::datetime('autoExpiry'),
 *     SchemaHelper::int('userId'),
 *     SchemaHelper::datetime('created'),
 *     SchemaHelper::datetime('modified'),
 *     SchemaHelper::int('status')
 *
 * @category        Toolbox
 * @package         PhoenixAddons
 * @subpackage      Enttites
 * @copyright       Copyright (c) 2013 Travelclick, Inc (http://www.travelclick.com)
 * @license         All Rights Reserved
 * @version         Release: 13.4
 * @since           File available since release 13.4
 * @author          Bradley Davidson <bdavidson@travelclick.com>
 * @filesource
*/
namespace PhoenixAddons\Entity;

use \Doctrine\ORM\Mapping as ORM;

 /**
  * PhoenixAddons
  *
  * @ORM\Table(name="phoenixAddons")
  * @ORM\Entity
  */
 class PhoenixAddon extends \ListModule\Entity\Entity
 {
    /**
    * @var integer id
    *
    * @ORM\Column(name="addonId", type="integer", length=11)
    * @ORM\Id
    * @ORM\GeneratedValue(strategy="IDENTITY")
    */
    protected $id;

    /**
    * @var string code
    *
    * @ORM\Column(name="code", type="string", length=100)
    */
    protected $code;

    /**
    * @var string name
    *
    * @ORM\Column(name="name", type="string", length=100)
    */
    protected $name;

    /**
    * @var string description
    *
    * @ORM\Column(name="description", type="string", length=1000)
    */
    protected $description;

    /**
    * @var string price
    *
    * @ORM\Column(name="price", type="string", length=100)
    */
    protected $price;

    /**
    * @var string currency
    *
    * @ORM\Column(name="currency", type="string", length=100)
    */
    protected $currency;

    /**
    * @var string tax
    *
    * @ORM\Column(name="tax", type="string", length=100)
    */
    protected $tax;

    /**
     * @var integer featured
     *
     * @ORM\Column(name="featured", type="integer", length=11)
     */
    protected $featured;
    
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
     * @var PhoenixProperties\Entity\Property $property
     *
     * @ORM\ManyToOne(targetEntity="PhoenixProperties\Entity\PhoenixProperty", inversedBy="propertyAddons")
     * @ORM\JoinColumn(name="property", referencedColumnName="propertyId")
     */
    protected $property;
    
    /**
    * @var string name french
    *
    * @ORM\Column(name="name_fr", type="string", length=1000)
    */
    protected $name_fr;
    
    /**
    * @var string description french
    *
    * @ORM\Column(name="description_fr", type="string", length=1000)
    */
    protected $description_fr;
    /**
     * @var integer $orderNumber
     *
     * @ORM\Column(name="orderNumber", type="integer", length=11)
     */
    protected $orderNumber;

    /**
     * @var DynamicListModule\Entity\DynamicListModuleItems $item
     *
     * @ORM\OneToOne(targetEntity="PhoenixAddons\Entity\PhoenixAddonItems", inversedBy="phoenixAddonData", cascade="persist")
     * @ORM\JoinColumn(name="item", referencedColumnName="itemId")
     */
    protected $item;
 }