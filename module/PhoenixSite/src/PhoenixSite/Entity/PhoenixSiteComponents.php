<?php
/**
 * File for the DynamicListModules entity class
 *
 *
 * @category    Toolbox
 * @package     PhoenixSite
 * @subpackage  Entity
 * @copyright   Copyright (c) 2014 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: Phoenix First Release 2014
 * @since       File available since release Phoenix First Release 2014
 * @author     Sravani Gundapunnei <sgundapuneni@travelclick.com>
 * @filesource
 */

namespace PhoenixSite\Entity;

use \Doctrine\ORM\Mapping as ORM;
use \Doctrine\Common\Collections\ArrayCollection;
use ListModule\Entity\Entity;
/**
 * PhoenixSiteComponents
 *
 * @ORM\Table(name="phoenixSiteComponents")
 * @ORM\Entity
 */
class PhoenixSiteComponents extends Entity
{
    /**
     * @var integer id
     *
     * @ORM\Column(name="`sitecomponentId`", type="integer", length = 11)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var string $name
     *
     * @ORM\Column(name="`name`", type="string" , length=255)
     */
    protected $name;

        
    /**
     * @var string $label
     *
     * @ORM\Column(name="`label`", type="string", length=255)
     */
    protected $label;
   
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
     * @var PhoenixProperties\Entity\PhoenixProperty $property
     *
     * @ORM\ManyToOne(targetEntity="PhoenixProperties\Entity\PhoenixProperty", inversedBy="phoenixSiteComponentId")
     * @ORM\JoinColumn(name="property", referencedColumnName="propertyId")
     */
    protected $property;
    

    /**
     * @var Toolbox\Entity\Components $component
     *
     * @ORM\ManyToOne(targetEntity="Toolbox\Entity\Components", inversedBy="siteComponents")
     * @ORM\JoinColumn(name="component", referencedColumnName="componentId")
     */
    protected $component;
     /**
     * @var Toolbox\Entity\ComponentFields $componentFields
     *
     * @ORM\ManyToOne(targetEntity="Toolbox\Entity\ComponentFields", inversedBy="siteComponentsFields")
     * @ORM\JoinColumn(name="componentFields", referencedColumnName="componentFieldId")
     */
    protected $componentFields;

    /**
     * @var PhoenixSite\Entity\PhoenixSiteItems $item
     *
     * @ORM\OneToOne(targetEntity="PhoenixSite\Entity\PhoenixSiteItems", inversedBy="phoenixSiteData", cascade="persist")
     * @ORM\JoinColumn(name="item", referencedColumnName="itemId")
     */
    protected $item;
    
    public function getComponent()
    {
        return $this->component;
    }

    public function setComponent($component)
    {
        $this->component = $component;

        return $this;
    }


    public function getLabel()
    {
        return $this->label;
    }

    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    public function getProperty()
    {
        return $this->property;
    }

    public function setProperty($property)
    {
        $this->property = $property;
    }

    public function getComponentFields()
    {
        return $this->componentFields;
    } 

    public function setComponentFields($componentFields)
    {
        $this->componentFields = $componentFields;

        return $this;
    }
}