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
 * LinkRedirects
 *
 * Module Tables Config
 *
 *      SchemaHelper::primary('itemId'),

 *      SchemaHelper::datetime('created'),
 *      SchemaHelper::tinyint('status'),
 *  *      SchemaHelper::tinyint('response'),
 *      SchemaHelper::datetime('modified'),

 *      SchemaHelper::int('userId'),
 *      SchemaHelper::varchar('incomingUrl'),
 *       SchemaHelper::varchar('redirectUrl'),
 

 *
 * @ORM\Table(name="linkRedirects")
 * @ORM\Entity
 */
class LinkRedirects extends \ListModule\Entity\Entity
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
     * @var integer response
     *
     * @ORM\Column(name="`response`", type="integer", length=11)
     */
    protected $response;
    
    /**
     * @var datetime modified
     *
     * @ORM\Column(name="`modified`", type="datetime")
     */
    protected $modified;
 
    
    /**
     * @var string incomingUrl
     *
     * @ORM\Column(name="`incomingUrl`", type="string", length=255)
     */
    protected $incomingUrl;
    
     /**
     * @var string redirectUrl
     *
     * @ORM\Column(name="`redirectUrl`", type="string", length=255)
     */
    protected $redirectUrl;
    
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