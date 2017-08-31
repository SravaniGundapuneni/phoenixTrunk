<?php
/**
 * File for the DynamicListModulesFields entity class
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
 * DynamicListModulesFields
 *
 * @ORM\Table(name="dynamicListModule_fields")
 * @ORM\Entity
 */
class DynamicListModuleFields extends Entity
{
    /**
     * @var integer id
     *
     * @ORM\Column(name="`fieldId`", type="integer", length = 11)
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
     * @var string displayName
     *
     * @ORM\Column(name="`displayName`", type="string", length=255)
     */
    protected $displayName;

    /**
     * @var string type
     *
     * @ORM\Column(name="`type`", type="string", length=255)
     */
    protected $type;

    /**
     * @var integer $orderNumber
     *
     * @ORM\Column(name="`orderNumber`", type="integer", length=11)
     */
    protected $orderNumber;

    /**
     * @var integer $showInList
     *
     * @ORM\Column(name="`showInList`", type="integer", length=11)
     */
    protected $showInList;

    /**
     * @var integer $userId
     *
     * @ORM\Column(name="`userId`", type="integer", length=11)
     */
    protected $userId;

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
     * @var DynamicListModule\Entity\DynamicListModules $module
     *
     * @ORM\ManyToOne(targetEntity="Toolbox\Entity\Components", inversedBy="moduleFields")
     * @ORM\JoinColumn(name="module", referencedColumnName="componentId")
     */
    protected $module;

    /**
     * @var \Doctrine\Common\Collections\Collection $fieldValues
     *
     * @ORM\OneToMany(targetEntity="DynamicListModule\Entity\DynamicListModuleItemFields", mappedBy="field", cascade={"all"})
     */
    protected $fieldValues;

    public function __construct()
    {
       $this->fieldValues = new ArrayCollection();
    }        
}