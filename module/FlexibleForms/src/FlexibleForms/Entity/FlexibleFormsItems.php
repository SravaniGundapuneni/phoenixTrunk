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
 * @ORM\Table(name="flexibleForms_items")
 * @ORM\Entity
 */
class FlexibleFormsItems extends Entity
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
     * @var FlexibleForms\Entity\FlexibleForm $form
     *
     * @ORM\ManyToOne(targetEntity="FlexibleForms\Entity\FlexibleForm", inversedBy="formItems")
     * @ORM\JoinColumn(name="form", referencedColumnName="formId")
     */
    protected $form;

    /**
     * @var \Doctrine\Common\Collections\Collection $itemFieldValues
     *
     * @ORM\OneToMany(targetEntity="FlexibleForms\Entity\FlexibleFormsItemFields", mappedBy="item", cascade={"all"})
     */
    protected $itemFields;

    public function __construct()
    {
       $this->itemFields = new ArrayCollection();
    }    
}