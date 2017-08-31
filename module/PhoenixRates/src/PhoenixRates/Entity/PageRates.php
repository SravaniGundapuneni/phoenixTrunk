<?php
/**
 * PageRates Entity file
 *
 * @category    Toolbox
 * @package     PhoenixRates
 * @subpackage  Entities
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.5
 * @since       File available since release 13.5
 * @author      A. Tate <atate@travelclick.com>
 * @filesource
 */
namespace PhoenixRates\Entity;

use \Doctrine\ORM\Mapping as ORM;

/**
 * PhoenixRates
 *
 * @ORM\Table(name="pageRates")
 * @ORM\Entity
 */
class PageRates extends \ListModule\Entity\Entity
{
    /**
     * @var integer id
     *
     * @ORM\Column(name="prId", type="integer", length = 11)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * 
     */
    protected $id;

    /**
     * @var integer $pageId
     *
     * @ORM\Column(name="pageId", type="integer", length=11) 
     */
    protected $pageId;    


    /**
     * @var integer $rateId
     *
     * @ORM\Column(name="rateId", type="integer", length=11) 
     */
    protected $rateId;

    /**
     * @var integer $orderNumber
     *
     * @ORM\Column(name="orderNumber", type="integer", length=11) 
     */
    protected $orderNumber;    
}