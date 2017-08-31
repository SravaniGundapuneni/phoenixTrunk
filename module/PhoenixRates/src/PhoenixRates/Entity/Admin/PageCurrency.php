<?php
/**
 * PageCurrency Entity file
 *
 * @category    Toolbox
 * @package     PhoenixCurrency
 * @subpackage  Entities
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.5
 * @since       File available since release 13.5
 * @author      A. Tate <atate@travelclick.com>
 * @filesource
 */
namespace PhoenixRates\Entity\Admin;

use \Doctrine\ORM\Mapping as ORM;

/**
 * basecurrency
 *
 * @ORM\Table(name="basecurrency")
 * @ORM\Entity
 */
class PageCurrency extends \ListModule\Entity\Entity
{
    /**
     * @var integer id
     *
     * @ORM\Column(name="id", type="integer", length = 11)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * 
     */
    protected $id;
    
    /**
     * @var string currency
     *
     * @ORM\Column(name="currency", type="string", length = 50) 
     */
    protected $currency;  
    
    /**
     * @var string dollarequiv
     *
     * @ORM\Column(name="dollarequiv", type="string", length = 50) 
     */
    protected $dollarequiv;    
}