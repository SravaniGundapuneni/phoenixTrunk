<?php
namespace Toolbox\Entity\Admin;

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
     * @var integer $dynamic
     *
     * @ORM\Column(name="`dynamic`", type="integer", length=1)
     */
    protected $dynamic;

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
     * @var \Doctrine\Common\Collections\Collection $componentFields
     *
     * @ORM\OneToMany(targetEntity="Toolbox\Entity\Admin\ComponentFields", mappedBy="component", cascade={"persist"})
     */
    protected $componentFields;

    public function __construct()
    {
        $this->componentFields = new ArrayCollection();
    }    
}