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

namespace Toolbox\Entity;

use \Doctrine\ORM\Mapping as ORM;
use \Doctrine\Common\Collections\ArrayCollection;
use ListModule\Entity\Entity;

 /**
  * @ORM\MappedSuperclass
  */
abstract class ComponentItems extends Entity
{
    /**
     * @var integer $categoryId
     *
     * @ORM\Column(name="`categoryId`", type="integer", length=11)
     */
    protected $categoryId;

    /**
     * @var integer $allProperties
     *
     * @ORM\Column(name="`allProperties`", type="integer", length=1)
     */
    protected $allProperties;

    /**
     * @var integer $createdUserId
     *
     * @ORM\Column(name="`createdUserId`", type="integer", length=11)
     */
    protected $createdUserId;

    /**
     * @var datetime created
     *
     * @ORM\Column(name="`created`", type="datetime")
     */
    protected $created;

    /**
     * @var integer $orderNumber
     *
     * @ORM\Column(name="`orderNumber`", type="integer", length=11)
     */
    protected $orderNumber;    

    /**
     * @var integer $modifiedUserId
     *
     * @ORM\Column(name="`modifiedUserId`", type="integer", length=11)
     */
    protected $modifiedUserId;

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
     * @var PhoenixProperties\Entity\PhoenixProperty $property
     *
     * @ORM\ManyToOne(targetEntity="PhoenixProperties\Entity\PhoenixProperty", inversedBy="propertyDynamicItems")
     * @ORM\JoinColumn(name="property", referencedColumnName="propertyId")
     */
    protected $property;

    /**
     * @var Toolbox\Entity\Components $component
     *
     * @ORM\ManyToOne(targetEntity="Toolbox\Entity\Components", inversedBy="moduleItems")
     * @ORM\JoinColumn(name="component", referencedColumnName="componentId")
     */
    protected $component;

    /**
     * @var \Doctrine\Common\Collections\Collection $languageTranslations
     *
     * @ORM\OneToMany(targetEntity="Languages\Entity\UnifiedLanguageTranslations", mappedBy="item")
     */
    protected $languageTranslations;


    public function __construct()
    {
       $this->languageTranslations = new ArrayCollection();
    }

    public function getComponent()
    {
        return $this->component;
    }

    public function setComponent($component)
    {
        $this->component = $component;

        return $this;
    }
}