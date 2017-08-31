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
namespace SiteMaps\Entity;

use \Doctrine\ORM\Mapping as ORM;
use \Doctrine\Common\Collections\ArrayCollection;
use ListModule\Entity\Entity;

/**
 * SiteMapSection
 *
 * @ORM\Table(name="sitemapsection")
 * @ORM\Entity
 */
class SiteMapSection extends \ListModule\Entity\Entity
{
    /**
     * @var integer dataSectionId
     *
     * @ORM\Column(name="`dataSectionId`", type="integer", length = 11)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var string dataSection
     *
     * @ORM\Column(name="`dataSection`", type="string", length=255)
     */
    protected $dataSection;
    
	/**
     * @var \Doctrine\Common\Collections\Collection $siteMaps
     * 
     * @ORM\OneToMany(targetEntity="SiteMaps\Entity\SiteMap", mappedBy="dataSectionId" , cascade={"all"})
     */
    protected $siteMaps;		

	public function __construct()
    {
       $this->siteMaps = new ArrayCollection();
       
    }
    
}