<?php
/**
 * ImageSwitch Entity File
 * 
 * @category         Toolbox
 * @package          PhoenixEvents
 * @subpackages      Entities
 * @copyright        Copyright (c) 2013 TravelClick, Inc (http://travelclick.com)
 * @license          All Rights Reserved
 * @version          Release 13.5.5
 * @since            File available since release 13.5.5
 * @author           Kevin Davis <kedavis@travelclick.com>
 * @filesource
 */

namespace PhoenixEvents\Entity;

use \Doctrine\ORM\Mapping as ORM;

/**
 *
 * @ORM\Table(name="phoenixEvents")
 * @ORM\Entity
 */

class PhoenixEvents extends \ListModule\Entity\Entity
{
     /**
      * @var integer id
      * 
      * @ORM\Column(name="eventId", type="integer", length=11)
      * @ORM\Id
      * @ORM\GeneratedValue(strategy="IDENTITY")
      *
    */
     protected $id;  
   
     /**
      * @var string eventName
      * 
      * @ORM\Column(name="eventName", type="string")
     */
     protected $eventName;

     /**
      * @var datetime created
      * 
      * @ORM\Column(name="created", type="datetime")
     */
     protected $created;

     /**
      * @var integer status
      *
      * @ORM\Column(name="status", type="integer")
     */
     protected $status;

     /**
      * @var datetime modified
      * 
      * @ORM\Column(name="modified", type="datetime")
     */
     protected $modified;

     /**
      * @var string eventStart
      * 
      * @ORM\Column(name="eventStart", type="string") 
     */
     protected $eventStart;

     /**
      * @var string eventStartTime
      * 
      * @ORM\Column(name="eventStartTime", type="string")
     */
     protected $eventStartTime;

     /**
      * @var string eventEnd
      * 
      * @ORM\Column(name="eventEnd", type="string")
     */
     protected $eventEnd;

    /**
      * @var string eventEndTime
      * 
      * @ORM\Column(name="eventEndTime", type="string")
     */
     protected $eventEndTime;

     /**
     * @var string eventDescription
     *
     * @ORM\Column(name="eventDescription", type="string", length=255)
     */
    protected $eventDescription;

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
      * @var integer showMainImage
      *
      * @ORM\Column(name="showMainImage", type="integer")
     */
     protected $showMainImage;
     
     /**
      * @var integer propertyId
      *
      * @ORM\Column(name="propertyId", type="integer")
     */
     protected $propertyId;
     /**
     * @var integer $orderNumber
     *
     * @ORM\Column(name="orderNumber", type="integer", length=11)
     */
    protected $orderNumber;

    /**
     * @var DynamicListModule\Entity\DynamicListModuleItems $item
     *
     * @ORM\OneToOne(targetEntity="PhoenixEvents\Entity\PhoenixEventItems", inversedBy="phoenixEventsData", cascade="persist")
     * @ORM\JoinColumn(name="item", referencedColumnName="itemId")
     */
    protected $item;
}