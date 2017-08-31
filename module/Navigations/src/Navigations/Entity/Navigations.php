<?php
/**
 * Navigations Entity file
 *

 *
 * @category        Toolbox
 * @package         Navigations
 * @subpackage      Entities
 * @copyright       Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license         All Rights Reserved
 * @version         Release: 13.6
 * @since           File available since release 13.6
 * @author          A. Tate <atate@travelclick.com>
 * @filesource
 */
namespace Navigations\Entity;

use \Doctrine\ORM\Mapping as ORM;

/**
 * Navigations
 *
 * @ORM\Table(name="navigations")
 * @ORM\Entity
 */
class Navigations extends \ListModule\Entity\Entity
{
    /**
     * @var integer id
     *
     * @ORM\Column(name="navigationId", type="integer", length=11)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var string navigationName
     *
     * @ORM\Column(name="navigationName", type="string", length=255)
     */
    protected $navigationName;

    
    /**
     * @var string navigationKey
     *
     * @ORM\Column(name="navigationKey", type="string", length=255)
     */
    protected $navigationKey;
    
    /**
     * @var string navigationUrl
     *
     * @ORM\Column(name="navigationUrl", type="string", length=255)
     */
    protected $navigationUrl;


    /**
     * @var integer navigationOrder
     *
     * @ORM\Column(name="navigationOrder", type="integer" , length=11)
     */
    protected $navigationOrder;
    /**
     * @var integer parent
     *
     * @ORM\Column(name="parent", type="integer" , length=11)
     */
    protected $parent;
/**
     * @var string navCategory
     *
     * @ORM\Column(name="navCategory", type="string", length=255)
     */
    protected $navCategory;
  
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
     * @var integer status
     *
     * @ORM\Column(name="status", type="integer", length=11)
     */
    protected $status;
    /**
     * @var integer propertyId
     *
     * @ORM\Column(name="propertyId", type="integer", length=11)
     */
    protected $propertyId;

    /**
     * @var Navigations\Entity\NavigationsItems $item
     *
     * @ORM\OneToOne(targetEntity="Navigations\Entity\NavigationsItems", inversedBy="navigationsData", cascade="persist")
     * @ORM\JoinColumn(name="item", referencedColumnName="itemId")
     */
    protected $item;      
}