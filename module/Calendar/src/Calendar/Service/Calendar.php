<?php
namespace Calendar\Service;

use Zend\ViewRenderer\RendererInterface as ViewRenderer;
use Calendar\Model\PhoenixCalendar;
use Calendar\EventManager\Event as CalEvent;

use Calendar\Classes;

class Calendar extends \ListModule\Service\Lists 
{
    private $calendarEvents = array();
    private $eventsData;

    public function __construct()
    {
        $this->entityName = PhoenixCalendar::CALENDAR_ENTITY_NAME;
        $this->modelClass = "\Calendar\Model\PhoenixCalendar";
    }
   
    public function getAllEventsByCalendarStartDate()
    {
        $items = $this->defaultEntityManager->getConnection()->fetchAll("
            SELECT
                cEvent.itemId,
                cEvent.title as eventtitle,
                cEvent.description,
                cEvent.eventCategoryId,
                cEvent.highlights,
                cEvent.url,
                c.startDate,
                c.endDate,
                eCategory.itemId,
                eCategory.title
            FROM
                calendarevent cEvent 
            INNER JOIN
                calendar c
            ON
                cEvent.item = c.calendarEventId 
            INNER JOIN
                eventcategory eCategory
            ON
                cEvent.eventCategoryId = eCategory.itemId
            ORDER BY
                c.startDate
        ");

        return $items;
    }

    public function getScheduledEvents()
    {
    
        $eitems = $this->defaultEntityManager->getConnection()->fetchAll("
            SELECT 
                cEvent.title as eventtitle,
                c.itemId as calendarItemId,
                c.startDate,
                c.endDate,
                c.calendarEventId as eventId
            FROM calendarevent cEvent 
            INNER JOIN
                calendar c
            ON
                cEvent.item = c.calendarEventId
        ");
        
        return  $eitems;
    }

    public function getBaseEvents()
    {
        $events = array();

        $calendarEvents = $this->getServiceManager()->get('phoenix-calendarevent');

        $result = $calendarEvents->getItemsBy(array('status' => 1));

        foreach ($result as $event) {
            $newEvent = (object) array(
                'title' => $event->getTitle(),
                'eventId' => $event->getId(),
                'description' => $event->getDescription()
            );
            
            $events[] = $newEvent;
        }

        return $events;
    } 

	public function deleteCalendarEvent($calendarItemId)
    {	
		$queryBuilder = $this->getDefaultEntityManager()->createQueryBuilder();
		
		$q = $queryBuilder->delete('Calendar\Entity\Calendar', 'c')
            ->where('c.id = :calItemID')
            ->setParameter('calItemID', $calendarItemId);
		
        return $q->getQuery()->execute();
	}

    public function getEvents($startDate)
    {
        $this->initCalendarEvents();
        return $this->getEventsData($startDate);
    }

    private function getEventsData($startDate)
    {
        $numberOfEvents = count($this->calendarEvents);
        $counter        = 1;
        $data           = array();
        
        foreach ($this->calendarEvents as $event) {
            $eventDate = new \DateTime($event->getYear() . '-' . $event->getMonth() . '-' . $event->getDate());

            if ($eventDate >= $startDate) {
                $data[$counter] = array_merge($event->getTemplateData(), array('counter' => $counter, 'maxglobaltracker' => $numberOfEvents));
                $counter++;
            }
        }   

        return $data;
    }

    private function initCalendarEvents()
    {
        $allEvents      = $this->getAllEventsByCalendarStartDate();
        $calendarEvents = array();
        $amfService     = $this->serviceManager->get('phoenix-attachedmediafiles');
        $mmfService     = $this->serviceManager->get('phoenix-mediamanagerfiles');

        foreach ($allEvents as $eventInfoArray) {
            $event = new Classes\CalendarEvent($eventInfoArray);
            $event->setAttachedMediaFilesService($amfService);
            $event->setMediaManagerFilesService($mmfService);
            $event->setToolboxIncludeUrl($this->config->get(array('paths', 'toolboxIncludeUrl')));
            $event->setSiteroot($this->config->get(array('templateVars', 'siteroot')));
            $calendarEvents[] = $event;
        }

        $this->calendarEvents = $calendarEvents;
    
    }
}

