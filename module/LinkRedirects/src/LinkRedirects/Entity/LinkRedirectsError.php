<?php
/**
 * Property Entity file
 *
 * @category        Toolbox
 * @package         LinkRedirects
 * @subpackage      Entities
 * @copyright       Copyright (c) 2014 TravelClick, Inc (http://www.travelclick.com)
 * @license         All Rights Reserved
 * @version         Release: 13.5
 * @since           File available since release 13.5
 * @author          Sravani Gundapuneni<sgundapuneni@travelclick.com>
 * @filesource
 */
namespace LinkRedirects\Entity;

use \Doctrine\ORM\Mapping as ORM;
use \Doctrine\Common\Collections\ArrayCollection;

/**
 * LinkRedirectsError
 *
 * Module Tables Config
 *
 *      SchemaHelper::primary('itemId'),
 *      SchemaHelper::datetime('created'),
 *      SchemaHelper::tinyint('status'),
 *      SchemaHelper::datetime('modified'),
 *      SchemaHelper::int('hits'),
 *      SchemaHelper::int('userId'),
 *      SchemaHelper::varchar('url'),
 *
 * @ORM\Table(name="linkRedirectsError")
 * @ORM\Entity
 */
class LinkRedirectsError extends \ListModule\Entity\Entity
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
     * @var integer hits
     *
     * @ORM\Column(name="`hits`", type="integer", length=11)
     */
    protected $hits;
    
    /**
     * @var datetime modified
     *
     * @ORM\Column(name="`modified`", type="datetime")
     */
    protected $modified;
 
    
    /**
     * @var string url
     *
     * @ORM\Column(name="`url`", type="string", length=255)
     */
    protected $url;
    
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
}