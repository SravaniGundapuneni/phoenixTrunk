<?php
namespace Toolbox\Entity;

use \Doctrine\ORM\Mapping as ORM;
use \Doctrine\Common\Collections\ArrayCollection;

use ListModule\Entity\Entity;

/**
 * Modules
 *
 * @ORM\Table(name="components")
 * @ORM\Entity
 */
class Components extends Entity
{
    /**
     * @var integer id
     *
     * @ORM\Column(name="`componentId`", type="integer", length = 11)
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
     * @var string label
     *
     * @ORM\Column(name="`label`", type="string", length=255)
     */
    protected $label;

    /**
     * @var string description
     *
     * @ORM\Column(name="`description`", type="string")
     */
    protected $description;

    /**
     * @var integer $dynamic
     *
     * @ORM\Column(name="`dynamic`", type="integer", length=1)
     */
    protected $dynamic;

    /**
     * @var integer $casEnabled
     *
     * @ORM\Column(name="`casEnabled`", type="integer", length=1)
     */
    protected $casEnabled;
    
    /**
     * @var integer $casAllowed
     *
     * @ORM\Column(name="`casAllowed`", type="integer", length=1)
     */
    protected $casAllowed;

    /**
     * @var integer $categories
     *
     * @ORM\Column(name="`categories`", type="integer", length=1)
     */
    protected $categories;

    /**
     * This is used to match the DLM to a core DLM stored in the repo
     * 
     * @var integer adminRepoId
     *
     * @ORM\Column(name="`adminRepoId`", type="integer", length=11)
     */
    protected $adminRepoId;

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
     * @var \Doctrine\Common\Collections\Collection $languageTranslations
     *
     * @ORM\OneToMany(targetEntity="Languages\Entity\LanguageTranslations", mappedBy="component", cascade={"persist"})
     */
    protected $languageTranslations;

    /**
     * @var \Doctrine\Common\Collections\Collection $componentFields
     *
     * @ORM\OneToMany(targetEntity="Toolbox\Entity\ComponentFields", mappedBy="component", cascade={"persist"})
     */
    protected $componentFields;

    /**
     * @var \Doctrine\Common\Collections\Collection $moduleItems
     *
     * @ORM\OneToMany(targetEntity="DynamicListModule\Entity\DynamicListModuleItems", mappedBy="component", cascade={"all"})
     */
    protected $moduleItems;    

     /**
     * @var \Doctrine\Common\Collections\Collection $siteComponents
     *
     * @ORM\OneToMany(targetEntity="PhoenixSite\Entity\PhoenixSiteComponents", mappedBy="component", cascade={"all"})
     */
    protected $siteComponents; 
    
    public function __construct()
    {
        $this->languageTranslations = new ArrayCollection();        
        $this->componentFields = new ArrayCollection();
        $this->moduleItems = new ArrayCollection();
         $this->siteComponents = new ArrayCollection();
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

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description) 
    {
        $this->description = $description;

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
}