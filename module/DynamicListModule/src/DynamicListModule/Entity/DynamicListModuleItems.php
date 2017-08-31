<?php
/**
 * File for the DynamicListModules entity class
 *
 *
 * @category    Toolbox
 * @package     DynamicListModules
 * @subpackage  Entity
 * @copyright   Copyright (c) 2014 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: Phoenix First Release 2014
 * @since       File available since release Phoenix First Release 2014
 * @author      A. Tate <atate@travelclick.com>
 * @filesource
 */

namespace DynamicListModule\Entity;

use \Doctrine\ORM\Mapping as ORM;
use \Doctrine\Common\Collections\ArrayCollection;
use ListModule\Entity\Entity;
use Toolbox\Entity\ComponentItems;

/**
 * DynamicListModules
 *
 * @ORM\Table(name="componentItems")
 * @ORM\Entity
 */
class DynamicListModuleItems extends ComponentItems
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
     * @var \Doctrine\Common\Collections\Collection $itemFieldValues
     *
     * @ORM\OneToMany(targetEntity="DynamicListModule\Entity\DynamicListModuleItemFields", mappedBy="item", cascade={"all"})
     */
    protected $itemFields;
    
    public function getId(){
        return $this->id;
    }

    public function __construct()
    {
        parent::__construct();
        $this->itemFields = new ArrayCollection();
    }
}