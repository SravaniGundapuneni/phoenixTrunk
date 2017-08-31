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

/**
 * DynamicListModules
 *
 * @ORM\Table(name="components")
 * @ORM\Entity
 */
class DynamicListModules extends Entity
{
    /**
     * @var integer id
     *
     * @ORM\Column(name="`moduleId`", type="integer", length = 11)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var string name
     *
     * @ORM\Column(name="`name`", type="string", length=255)
     */
    protected $name;

    /**
     * @var string description
     *
     * @ORM\Column(name="`description`", type="string")
     */
    protected $description;

    /**
     * @var integer $categories
     *
     * @ORM\Column(name="`categories`", type="integer", length=1)
     */
    protected $categories;

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
     * @ORM\Column(name="`created`", type="datetime")
     */
    protected $created;

    /**
     * @var datetime modified
     *
     * @ORM\Column(name="`modified`", type="datetime")
     */
    protected $modified;

    /**
     * @var integer $status
     *
     * @ORM\Column(name="`status`", type="integer", length=1)
     */
    protected $status;

    /**
     * @var \Doctrine\Common\Collections\Collection $moduleItems
     *
     * @ORM\OneToMany(targetEntity="DynamicListModule\Entity\DynamicListModuleItems", mappedBy="module", cascade={"all"})
     */
    protected $moduleItems;

    /**
     * @var \Doctrine\Common\Collections\Collection $moduleFields
     *
     * @ORM\OneToMany(targetEntity="DynamicListModule\Entity\DynamicListModuleFields", mappedBy="module", cascade={"all"})
     */
    protected $moduleFields;

    public function __construct()
    {
       $this->moduleFields = new ArrayCollection();
    }    
}