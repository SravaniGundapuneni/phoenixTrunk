<?php
/**
 * Blocks       Entity file
 *
 * @category    Toolbox
 * @package     Pages
 * @subpackage  Entities
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.4
 * @since       File available since release 13.4
 * @author      isorokin <isorokin@travelclick.com>
 * @filesource
 */
namespace Blocks\Entity;

use \Doctrine\ORM\Mapping as ORM;

/**
 * Blocks
 *
 * @ORM\Table(name="blocks")
 * @ORM\Entity
 */
class Blocks extends \ListModule\Entity\Entity
{
    /**
     * @var integer id
     *
     * @ORM\Column(name="id", type="integer", length=11)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * 
     */
    protected $id;

    /**
     * @ORM\Column(name="name", type="string", length = 100)
     */
    protected $name;

    /**
     * @ORM\Column(name="content", type="text")
     */
    protected $content;

    /**
     * @ORM\Column(name="page", type="integer", length = 11)
     */
    protected $page;
    
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
     * @ORM\Column(name="created", type="datetime")
     */
    protected $created;

    /**
     * @ORM\Column(name="modified", type="datetime")
     */
    protected $modified;    

    /**
     * @ORM\Column(name="status", type="integer", length = 1)
     */
    protected $status;
}
