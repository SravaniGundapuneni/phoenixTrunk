<?php
/**
 * PhoenixRates Entity file
 *
 * Module tables config
 *
 *     SchemaHelper::primary('rateId'),
 *     SchemaHelper::varchar('code'),
 *     SchemaHelper::varchar('name'),
 *     SchemaHelper::text('description'),
 *     SchemaHelper::text('policy'),
 *     SchemaHelper::decimal('price', 10, 2, 'NO', '0.00'),
 *     SchemaHelper::varchar('bookingLink'),
 *     SchemaHelper::tinyint('categories'),
 *     SchemaHelper::tinyint('showMainImage'),
 *     SchemaHelper::datetime('startDate'),
 *     SchemaHelper::datetime('autoExpiry'),
 *     SchemaHelper::tinyint('featured'),
 *     SchemaHelper::tinyint('isMember'),
 *
 * @category    Toolbox
 * @package     PhoenixRates
 * @subpackage  Entities
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.4
 * @since       File available since release 13.4
 * @author      A. Tate <atate@travelclick.com>
 * @filesource
 */
namespace PhoenixRates\Entity;

use \Doctrine\ORM\Mapping as ORM;

/**
 * Membership
 *
 * @ORM\Table(name="membership")
 * @ORM\Entity
 */
class Membership extends \ListModule\Entity\Entity
{
    /**
     * @var integer id
     *
     * @ORM\Column(name="id", type="integer", length = 11)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     *
     */
    protected $id;

    /**
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    protected $name;

    /**
     * @var string $description
     *
     * @ORM\Column(name="description", type="string")
     */
    protected $description;
}
