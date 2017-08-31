<?php
namespace Calendar\Entity;

use \Doctrine\ORM\Mapping as ORM;
use \Doctrine\Common\Collections\ArrayCollection;
use Phoenix\Module\Entity\EntityAbstract;
use ListModule\Entity\Entity;
/**
 * PhoenixCalendar
 *
 * @ORM\Table(name="calendarevent")
 * @ORM\Entity
 */
class CalendarEvent extends EntityAbstract
{
    /**
     * @var integer id
     *
     * @ORM\Column(name="itemId", type="integer", length=11)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;
	 /**
     * @var string title
     *
     * @ORM\Column(name="title", type="string", length = 255) 
     */
    protected $title;
	 /**
     * @var string description
     *
     * @ORM\Column(name="description", type="string", length = 255) 
     */
    protected $description;

    /**                                                                                                              
     * @var string highlights
     *
     * @ORM\Column(name="highlights", type="string", length = 6) 
     */
    protected $highlights;
	/**
     * @var string url
     *
     * @ORM\Column(name="url", type="string", length = 255) 
     */
    protected $url;
	/**
     * @var string dataSection
     *
     * @ORM\Column(name="dataSection", type="string", length = 255) 
     */
    protected $dataSection;
		
	/**
     * @var integer orderNumber
     *
     * @ORM\Column(name="orderNumber", type="integer", length =11) 
     */
    protected $orderNumber;
	  
	/**
     * @ORM\Column(name="userId", type="integer", length = 11)
     */
    protected $userId;

    /**
     * @ORM\Column(name="created", type="datetime")
     */
    protected $created;

    /**
     * @ORM\Column(name="modified", type="datetime")
     */
    protected $modified;

    /**
     * @ORM\Column(name="status", type="integer", length = 11)
     */
    protected $status;
    /**
     * @var Calendar\Entity\EventCategory $eventCategoryId
     *
     * @ORM\ManyToOne(targetEntity="Calendar\Entity\EventCategory", inversedBy="eventCategoryIds", cascade="persist")
     * @ORM\JoinColumn(name="eventCategoryId", referencedColumnName="itemId")
    */
    protected $eventCategoryId;	

    /**
     * @var Calendar\Entity\CalendarItems $item
     *
     * @ORM\OneToOne(targetEntity="Calendar\Entity\CalendarItems", inversedBy="calendarData", cascade="persist")
     * @ORM\JoinColumn(name="item", referencedColumnName="itemId")
     */
    protected $item;  	
}