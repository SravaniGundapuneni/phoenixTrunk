<?php
/**
 * Footer Entity file
 *

 *
 * @category        Toolbox
 * @package         Footer
 * @subpackage      Entities
 * @copyright       Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license         All Rights Reserved
 * @version         Release: 13.6
 * @since           File available since release 13.6
 * @author          A. Tate <atate@travelclick.com>
 * @filesource
 */
namespace Footer\Entity;

use \Doctrine\ORM\Mapping as ORM;

/**
 * Footer
 *
 * @ORM\Table(name="footer")
 * @ORM\Entity
 */
class Footer extends \ListModule\Entity\Entity
{
    /**
     * @var integer id
     *
     * @ORM\Column(name="footerId", type="integer", length=11)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var string footerName
     *
     * @ORM\Column(name="footerName", type="string", length=255)
     */
    protected $footerName;

    
    /**
     * @var string footerKey
     *
     * @ORM\Column(name="footerKey", type="string", length=255)
     */
    protected $footerKey;
    
    /**
     * @var string footerUrl
     *
     * @ORM\Column(name="footerUrl", type="string", length=255)
     */
    protected $footerUrl;


    /**
     * @var integer footerOrder
     *
     * @ORM\Column(name="footerOrder", type="integer" , length=11)
     */
    protected $footerOrder;

  
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
     * @var Footer\Entity\FooterItems $item
     *
     * @ORM\OneToOne(targetEntity="Footer\Entity\FooterItems", inversedBy="footerData", cascade="persist")
     * @ORM\JoinColumn(name="item", referencedColumnName="itemId")
     */
    protected $item;
}