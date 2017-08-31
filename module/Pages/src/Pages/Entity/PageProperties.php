<?php
/**
 * PageProperties   Entity file
 *
 * @category    Toolbox
 * @package     Pages
 * @subpackage  Entities
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.4
 * @since       File available since release 13.4
 * @author      A. Tate <atate@travelclick.com>
 * @filesource
 */
namespace Pages\Entity;

use \Doctrine\ORM\Mapping as ORM;

/**
 * Pages
 *
 * @ORM\Table(name="page_properties")
 * @ORM\Entity
 */
class PageProperties extends \ListModule\Entity\Entity
{
    /**
     * @var integer id
     *
     * @ORM\Column(name="ppId", type="integer", length = 11)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * 
     */
    protected $id;

    /**
     * @ORM\Column(name="pageId", type="integer", length = 11)
     */
    protected $pageId;

    /**
     * @ORM\Column(name="propertyId", type="integer", length = 11)
     */
    protected $propertyId;
}
