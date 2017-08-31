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
namespace SiteMap\Entity;

use \Doctrine\ORM\Mapping as ORM;
use \Doctrine\Common\Collections\ArrayCollection;

/**
 * SiteMap
 *
 * Module Tables Config
 *
 *      SchemaHelper::primary('itemId'),
 *      SchemaHelper::varchar('title'),
 *      SchemaHelper::datetime('created'),
 *      SchemaHelper::tinyint('status'),
 *      SchemaHelper::datetime('modified'),
 *      SchemaHelper::varchar('dataSection'),
 *      SchemaHelper::int('order'),
 *      SchemaHelper::int('userId'),
 *      SchemaHelper::varchar('page'),
 *      SchemaHelper::varchar('pageKey'),
 *      SchemaHelper::varchar('areaKey'),
 *      SchemaHelper::int('propertyId'),
 *      SchemaHelper::varchar('module'),
 *      SchemaHelper::varchar('friendlyType'),
 *      SchemaHelper::int('dynamicPage'),
 *      SchemaHelper::int('moduleItemId'),
 *      SchemaHelper::varchar('moduleItemTitle')
 *
 * @ORM\Table(name="siteMap")
 * @ORM\Entity
 */
class PhoenixSiteMap extends \ListModule\Entity\Entity
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
     * @var string dataSection
     *
     * @ORM\Column(name="`dataSection`", type="string", length=255)
     */
    protected $dataSection;
    
    /**
     * @var integer order
     *
     * @ORM\Column(name="`order`", type="integer", length=11)
     */
    protected $order;
    
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
     * @var string page
     *
     * @ORM\Column(name="`page`", type="string", length=255)
     */
    protected $page;
    
    /**
     * @var string pageKey
     *
     * @ORM\Column(name="`pageKey`", type="string", length=255)
     */
    protected $pageKey;
    
    /**
     * @var string areaKey
     *
     * @ORM\Column(name="`areaKey`", type="string", length=255)
     */
    protected $areaKey;
    
    /**
     * @var integer propertyId
     *
     * @ORM\Column(name="`propertyId`", type="integer", length=11)
     */
    protected $propertyId;
    
    /**
     * @var string module
     *
     * @ORM\Column(name="`module`", type="string", length=255)
     */
    protected $module;
    
    /**z
     * @var string friendlyType
     *
     * @ORM\Column(name="`friendlyType`", type="string", length=255)
     */
    protected $friendlyType;
    
    /**
     * @var integer dynamicPage
     *
     * @ORM\Column(name="`dynamicPage`", type="integer", length=11)
     */
    protected $dynamicPage;
    
    /**
     * @var integer moduleItemId
     *
     * @ORM\Column(name="`moduleItemId`", type="integer", length=11)
     */
    protected $moduleItemId;
    
    /**
     * @var string moduleItemTitle
     *
     * @ORM\Column(name="`moduleItemTitle`", type="string", length=255)
     */
    protected $moduleItemTitle;
    /**
     * @var integer orderNumber
     *
     * @ORM\Column(name="`orderNumber`", type="integer", length=11)
     */
    protected $orderNumber;
}