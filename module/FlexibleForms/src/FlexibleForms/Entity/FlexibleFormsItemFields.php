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
 * @ORM\Table(name="flexibleForms_itemFields")
 * @ORM\Entity
 */
class FlexibleFormsItemFields extends Entity
{
    /**
     * @var integer id
     *
     * @ORM\Column(name="`ifId`", type="integer", length = 11)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var string value
     *
     * @ORM\Column(name="`value`", type="string")
     */
    protected $value;
    
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
     * @var FlexibleForms\Entity\FlexibleForm $field
     *
     * @ORM\ManyToOne(targetEntity="FlexibleForms\Entity\FlexibleFormsFields", inversedBy="fieldValues")
     * @ORM\JoinColumn(name="field", referencedColumnName="fieldId")
     */ 
    protected $field;

    /**
     * @var FlexibleForms\Entity\FlexibleFormsItems $item
     *
     * @ORM\ManyToOne(targetEntity="FlexibleForms\Entity\FlexibleFormsItems", inversedBy="moduleFieldValues", cascade="persist")
     * @ORM\JoinColumn(name="item", referencedColumnName="itemId")
     */
    protected $item;
}