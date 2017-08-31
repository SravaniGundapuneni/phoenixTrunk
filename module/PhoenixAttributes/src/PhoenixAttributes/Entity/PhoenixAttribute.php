<?php
/**
 * PhoenixAttributes Entity file
 *
 * Module Tables Config
 *
 *     SchemaHelper::primary('attributeId'),
 *     SchemaHelper::varchar('name'),
 *     SchemaHelper::text('description'),
 *     SchemaHelper::tinyint('categories'),
 *     SchemaHelper::int('userId'),
 *     SchemaHelper::datetime('created'),
 *     SchemaHelper::datetime('modified'),
 *     SchemaHelper::int('status')
 *
 * @category        Toolbox
 * @package         PhoenixAttributes
 * @subpackage      Entities
 * @copyright       Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license         All Rights Reserved
 * @version         Release: 13.4
 * @since           File available since release 13.4
 * @author          Grou <jrubio@travelclick.com>
 * @filesource
 */
namespace PhoenixAttributes\Entity;

use \Doctrine\ORM\Mapping as ORM;

/**
 * PhoenixAttributes
 *
 * @ORM\Table(name="phoenixAttributes")
 * @ORM\Entity
 */
class PhoenixAttribute extends \ListModule\Entity\Entity
{
    /**
     * @var integer id
     *
     * @ORM\Column(name="attributeId", type="integer", length=11)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var string code
     *
     * @ORM\Column(name="code", type="string", length=255)
     */
    protected $code;

    /**
     * @var string name
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    protected $name;

    /**
     * @var string description
     *
     * @ORM\Column(name="description", type="string")
     */
    protected $description;

    /**
     * @var integer categories
     *
     * @ORM\Column(name="categories", type="integer", length=1)
     */
    protected $categories;

    /**
     * @var integer showMainImage
     *
     * @ORM\Column(name="showMainImage", type="integer", length=11)
     */
    protected $showMainImage;

    /**
     * @var integer featured
     *
     * @ORM\Column(name="featured", type="integer", length=11)
     */
    protected $featured;

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
     * @ORM\Column(name="created", type="datetime")
     */
    protected $created;

    /**
     * @var datetime modified
     *
     * @ORM\Column(name="modified", type="datetime")
     */
    protected $modified;

    /**
     * @var integer $status
     *
     * @ORM\Column(name="status", type="integer", length=1)
     */
    protected $status;

    /**
     * @var PhoenixProperties\Entity\Property $property
     *
     * @ORM\ManyToOne(targetEntity="PhoenixProperties\Entity\PhoenixProperty", inversedBy="propertyAttributes")
     * @ORM\JoinColumn(name="property", referencedColumnName="propertyId")
     */
    protected $property;
}