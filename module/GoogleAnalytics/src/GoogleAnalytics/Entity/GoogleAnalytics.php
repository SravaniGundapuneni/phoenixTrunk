<?php

/**
 * GoogleAnalytics Entity file
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
 * SchemaHelper::varchar('url')
 *
 * @category        Toolbox
 * @package         GoogleAnalytics
 * @subpackage      Entities
 * @copyright       Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license         All Rights Reserved
 * @version         Release: 2.14
 * @since           File available since release 13.6
 * @author          Sravani Gundapuneni <sgundapunnei@travelclick.com>
 * @filesource
 */
namespace GoogleAnalytics\Entity;

use \Doctrine\ORM\Mapping as ORM;

/**
 * GoogleAnalytics
 *
 * @ORM\Table(name="googleAnalytics")
 * @ORM\Entity
 */
class GoogleAnalytics extends \ListModule\Entity\Entity
{
    /**
     * @var integer id
     *
     * @ORM\Column(name="googleAnalyticId", type="integer", length=11)
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
     * @var string gaAccount
     *
     * @ORM\Column(name="gaAccount", type="string", length=255)
     */
    protected $gaAccount;

    
      /**
     * @var integer $eventTracking
     *
     * @ORM\Column(name="eventTracking", type="integer", length=1)
     */
    protected $eventTracking;
      /**
     * @var integer $crossTracking
     *
     * @ORM\Column(name="crossTracking", type="integer", length=1)
     */
    protected $crossTracking;
      /**
     * @var integer $remarketing
     *
     * @ORM\Column(name="remarketing", type="integer", length=1)
     */
    protected $remarketing;
     /**
     * @var integer $anonynize
     *
     * @ORM\Column(name="anonynize", type="integer", length=1)
     */
    protected $anonynize;
    /**
     * @var string domain
     *
     * @ORM\Column(name="domain", type="string", length=64)
     */
    protected $domain;
    /**
     * @var string keyValue
     *
     * @ORM\Column(name="keyValue", type="string", length=255)
     */
    protected $keyValue;

    /**
     * @var string crossDomain
     *
     * @ORM\Column(name="crossDomain", type="string", length=64)
     */
    protected $crossDomain;

    /**
     * @var string bookingUrl
     *
     * @ORM\Column(name="bookingUrl", type="string", length=128)
     */
    protected $bookingUrl;
       /**
     * @var string pageNames
     *
     * @ORM\Column(name="pageNames", type="string", length=255)
     */
    protected $pageNames;

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
   
}