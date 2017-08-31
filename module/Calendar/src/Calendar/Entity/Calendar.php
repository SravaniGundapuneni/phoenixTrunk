<?php
namespace Calendar\Entity;

use \Doctrine\ORM\Mapping as ORM;

use Phoenix\Module\Entity\EntityAbstract;
use ListModule\Entity\Entity;
/**
 * PhoenixCalendar
 *
 * @ORM\Table(name="calendar")
 * @ORM\Entity
 */
class Calendar extends EntityAbstract
{
    /**
     * @var integer id
     * @ORM\Column(name="itemId", type="integer", length=11)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;
    /**
     * @ORM\Column(name="startDate", type="datetime")
     */
    protected $startDate;
    /**
     * @ORM\Column(name="endDate", type="datetime")
     */
    protected $endDate;
	
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
     * @ORM\Column(name="calendarEventId", type="integer", length = 11)
     */
    protected $calendarEventId;
	
	
	public function getCalendarEventId(){
		return $this->calendarEventId;
	
	}
}