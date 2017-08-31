<?php
/**
 * ModuleFields Entity file
 *
 * @category        Toolbox
 * @package         Toolbox
 * @subpackage      Entities
 * @copyright       Copyright (c) 2014 Travelclick, Inc (http://www.travelclick.com)
 * @license         All Rights Reserved
 * @version         Release: 2.14
 * @since           File available since release 2.14
 * @author          Andrew Tate <atate@travelclick.com>
 * @filesource
 */
namespace Toolbox\Entity\Admin;

use \Doctrine\ORM\Mapping as ORM;

use ListModule\Entity\Entity;

/**
 * @ORM\Table(name="componentFields")
 * @ORM\Entity
 */
class ComponentFields extends Entity
{
    /**
     * @var integer id
     *
     * @ORM\Column(name="`componentFieldId`", type="integer", length = 11)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var string name
     *
     * @ORM\Column(name="`name`", type="string")
     */
    protected $name;


    /**
     * @var string label
     *
     * @ORM\Column(name="`label`", type="string", length=255)
     */
    protected $label;

    /**
     * @var integer $translate
     *
     * @ORM\Column(name="`translate`", type="integer", length=11)
     */
    protected $translate;

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
     * @var integer $required
     *
     * @ORM\Column(name="`required`", type="integer", length=11)
     */
    protected $required;

    /**
     * @var string type
     *
     * @ORM\Column(name="`type`", type="string", length=255)
     */
    protected $type;

    /**
     * @var integer $userId
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
     * @var Toolbox\Entity\Components $component
     *
     * @ORM\ManyToOne(targetEntity="Toolbox\Entity\Admin\Components", inversedBy="componentFields")
     * @ORM\JoinColumn(name="component", referencedColumnName="componentId")
     */
    protected $component;
}
