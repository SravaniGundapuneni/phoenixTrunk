<?php
/**
 * File for the MapMarkerItems entity class
 *
 *
 * @category    Toolbox
 * @package     Navigations
 * @subpackage  Entity
 * @copyright   Copyright (c) 2014 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: Phoenix First Release 2014
 * @since       File available since release Phoenix First Release 2014
 * @author      A. Tate <atate@travelclick.com>
 * @filesource
 */

namespace Navigations\Entity;

use \Doctrine\ORM\Mapping as ORM;
use \Doctrine\Common\Collections\ArrayCollection;
use ListModule\Entity\Entity;
use Toolbox\Entity\ComponentItems;

/**
 * NavigationsItems
 *
 * @ORM\Table(name="componentItems")
 * @ORM\Entity
 */
class NavigationsItems extends ComponentItems
{
    /**
     * @var integer id
     *
     * @ORM\Column(name="`itemId`", type="integer", length = 11)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var \Doctrine\Common\Collections\Collection $navigaionsData
     *
     * @ORM\OneToOne(targetEntity="Navigations\Entity\Navigations", mappedBy="item", cascade={"all"})
     */
    protected $navigationsData;
}