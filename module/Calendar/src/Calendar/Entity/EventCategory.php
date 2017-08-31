<?php
namespace Calendar\Entity;

use \Doctrine\ORM\Mapping as ORM;

use Phoenix\Module\Entity\EntityAbstract;
use \Doctrine\Common\Collections\ArrayCollection;
use ListModule\Entity\Entity;
/**
 * EventCategory
 *
 * @ORM\Table(name="eventcategory")
 * @ORM\Entity
 */
class EventCategory extends EntityAbstract
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
     * @var \Doctrine\Common\Collections\Collection $eventCategoryIds 
     *
     * @ORM\OneToMany(targetEntity="Calendar\Entity\CalendarEvent", mappedBy="eventCategoryId", cascade={"all"})
     */
    protected $eventCategoryIds;
    
	public function __construct()
	{
		$this->eventCategoryIds=new ArrayCollection();
	}	
}