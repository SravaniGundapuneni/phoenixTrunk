<?php
/**
 * File for the FlexibleForm entity class
 *
 *
 * @category    Toolbox
 * @package     FlexibleForm
 * @subpackage  Entity
 * @copyright   Copyright (c) 2014 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: Phoenix First Release 2014
 * @since       File available since release Phoenix First Release 2014
 * @author      A. Tate <atate@travelclick.com>
 * @filesource
 */

namespace FlexibleForms\Entity;

use \Doctrine\ORM\Mapping as ORM;
use \Doctrine\Common\Collections\ArrayCollection;
use ListModule\Entity\Entity;

/**
 * FlexibleForm
 *
 * @ORM\Table(name="flexibleForm")
 * @ORM\Entity
 */
class FlexibleForm extends Entity
{
    /**
     * @var integer id
     *
     * @ORM\Column(name="`formId`", type="integer", length = 11)
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
     * @var integer emailEnabled
     *
     * @ORM\Column(name="emailEnabled", type="integer", length=11)
     *
    */

    protected $emailEnabled;

    /**
     * @var string description
     *
     * @ORM\Column(name="`description`", type="string")
     */
    protected $description;

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
     * @var \Doctrine\Common\Collections\Collection $formItems
     *
     * @ORM\OneToMany(targetEntity="FlexibleForms\Entity\FlexibleFormsItems", mappedBy="form", cascade={"all"})
     */
    protected $formItems;

    /**
     * @var \Doctrine\Common\Collections\Collection $formFields
     *
     * @ORM\OneToMany(targetEntity="FlexibleForms\Entity\FlexibleFormsFields", mappedBy="form", cascade={"all"})
     */
    protected $formFields;

    public function __construct()
    {
       $this->formFields = new ArrayCollection();
       $this->formItems = new ArrayCollection();
    }
}