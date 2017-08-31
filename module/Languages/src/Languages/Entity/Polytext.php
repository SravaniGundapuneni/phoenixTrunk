<?php
/**
 * Polytext Entity file
 *
 * @category        Toobox
 * @package         Languages
 * @subpackage      Enttites
 * @copyright       Copyright (c) 2013 Travelclick, Inc (http://www.travelclick.com)
 * @license         All Rights Reserved
 * @version         Release: 13.5.5
 * @since           File available since release 13.5.5
 * @author          Jose A. Duarte <jduarte@travelclick.com>
 * @filesource
 */
namespace Languages\Entity;

use \Doctrine\ORM\Mapping as ORM;

 /**
  * Polytext Entity
  *
  * Module tables config
  *
  *     SchemaHelper::primary('textId'),
  *     SchemaHelper::varchar('type'),
  *     SchemaHelper::varchar('area'),
  *     SchemaHelper::varchar('name'),
  *     SchemaHelper::varchar('lang', 10),
  *     SchemaHelper::text('text'),
  *
  * @ORM\Table(name="polytext")
  * @ORM\Entity
  */
class Polytext extends \ListModule\Entity\Entity
{
    /**
     * @var integer id
     *
     * @ORM\Column(name="textId", type="integer", length=11)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var string type
     *
     * @ORM\Column(name="type", type="string", length=255)
     */
    protected $type;

    /**
     * @var string area
     *
     * @ORM\Column(name="area", type="string", length=255)
     */
    protected $area;

    /**
     * @var string name
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    protected $name;

    /**
     * @var string lang
     *
     * @ORM\Column(name="lang", type="string", length=10)
     */
    protected $lang;

    /**
     * @var string text
     *
     * @ORM\Column(name="text", type="text")
     */
    protected $text;

    /**
     * @var integer $userId
     *
     * @ORM\Column(name="userId", type="integer", length=11)
     */
    protected $userId;

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
}