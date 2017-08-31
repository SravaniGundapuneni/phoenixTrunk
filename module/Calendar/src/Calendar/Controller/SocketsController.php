<?php

namespace Calendar\Controller;

use Zend\View\Model\JsonModel;
use ListModule\Controller\SocketsController as BaseSockets;

class SocketsController extends BaseSockets
{
    public function addEventAction() 
    {
        $eventDate       = $this->params()->fromPost('date');
        $event           = $this->params()->fromPost('event');
        $calendarService = $this->getServiceLocator()->get('phoenix-calendar');
        
        $eventData = array(
            'calendarEventId' => $event,
            'startDate' => $eventDate,
            'endDate' => $eventDate,
        );
    
        $calendarModel               = $calendarService->createCalendarModel();
        $eventData['calendarItemId'] = $calendarService->save($calendarModel, $eventData);
        $eventData['eventId']        = $eventData['calendarEventId'];
        unset($eventData['calendarEventId']);

        return new JsonModel(array('success' => true, 'event' => $eventData));
    }

	public function getEventsDataAction()
    {
        $calendarService = $this->getServiceLocator()->get('phoenix-calendar');
        $scheduledEvents = $calendarService->getScheduledEvents();
        $allEvents       = $calendarService->getBaseEvents();

        return new JsonModel(array('scheduled' => $scheduledEvents, 'all' => $allEvents));
	}

	public function removeEventAction()
    {
        $calendarId      = $this->params()->fromPost('id');
        $calendarService = $this->serviceLocator->get('phoenix-calendar');
        $success         = $calendarService->deleteCalendarEvent($calendarId);

		return new JsonModel(array('success' => $success));
	}

    public function getEventsFromDateAction()
    {
        $calendarService = $this->serviceLocator->get('phoenix-calendar');
        $date            = $this->params()->fromQuery('date');
        $month           = $this->params()->fromQuery('month');
        $year            = $this->params()->fromQuery('year');
        $startDate       = new \DateTime($year . '-' . $month . '-' . $date);
        $events          = $calendarService->getEvents($startDate);

        return new JsonModel(array('events' => $events)); 
    }
    
    protected function getItemsService()
    {
        return $this->getServiceLocator()->get('phoenix-calendarevent');
    }    
}