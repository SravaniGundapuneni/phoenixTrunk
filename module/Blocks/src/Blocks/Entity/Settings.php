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
 * @ORM\Table(name="settings")
 * @ORM\Entity
 */
class Settings extends \ListModule\Entity\Entity
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
     * @ORM\Column(name="screenName", type="string", length = 50)
     */
    protected $screenName;

    /**
     * @ORM\Column(name="widget", type="string", length = 50)
     */
    protected $widget;

    /**
     * @ORM\Column(name="parameters", type="string", length = 4096)
     */
    protected $parameters;
}
