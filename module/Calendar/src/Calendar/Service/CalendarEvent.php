<?php

namespace Calendar\Service;


use Zend\ViewRenderer\RendererInterface as ViewRenderer;

use Calendar\Model\PhoenixCalendarEvent;
use Calendar\EventManager\Event as PhoenixCalEvent;

use ListModule\Service\UnifiedLists;

class CalendarEvent extends UnifiedLists 
{
    /**
     * __construct
     *
     * Construct our Calendar service
     *
     * @return void
     */
    public function __construct()
    {
       
        $this->entityName = PhoenixCalendarEvent::CALENDAR_EVENT_ENTITY_NAME;
        $this->modelClass = "\Calendar\Model\PhoenixCalendarEvent";
        $this->dataItem = PhoenixCalendarEvent::CALENDAR_DATA_ENTITY;
    }
   
     /**
     * method populating checkboxes for events on the page..
     *getDataSectionIdOptions
     *
     * @return array
     */
    public function getEventCategoryIdOptions()
    {
        $options = array();
         //inject default Event Category as Not Assigned
        $options[0] = 'Not Assigned';
        $eventCategories = $this->getDefaultEntityManager()->getRepository('Calendar\Entity\EventCategory')->findBy(array('status'=>1));

        foreach ($eventCategories as $keyEvent => $valEvent) {
            $options[$valEvent->getId()] = $valEvent->getTitle();
        }

            $eventcategory = new \Zend\Form\Element\Select('eventCategoryId');
            $eventcategory->setLabel('Event Category');
            $eventcategory->setLabelAttributes(array('class' => 'blockLabel'));
            
            $eventcategory->setAttribute('class', 'stdTextInput');
            $eventcategory->setValueOptions($options);
       
    
        return $options;  
       
    }
    
    
    public function createCalendarEventModel()
    {
        
        $calEventModel = new \Calendar\Model\PhoenixCalendarEvent($this->config);
        $calEventModel->setDefaultEntityManager($this->getDefaultEntityManager());
        return $calEventModel;
    }

    public function getAllEvents()
    {
        $qbSelectPageDataSection = $this->getDefaultEntityManager()->createQueryBuilder();
        
        $qbSelectPageDataSection->select('e')
            ->from('Calendar\Entity\CalendarEvent', 'e')
            ->where('e.status = :estatus')
            ->setParameter('estatus',1);
       
        $result = $qbSelectPageDataSection->getQuery()->getResult();

        return $result;
    } 

    public function save($calEventModel,$ArryValue)
    {   
        $eventIdValue=$ArryValue['eventCategoryId'];
        $eventTitle=$ArryValue['title'];
        $eventDescription=$ArryValue['description'];
        $eventUrl=$ArryValue['url'];
        $eventHighlight=$ArryValue['highlights'];
        $eventLanguage=$ArryValue['currentLanguage'];
        $eventCategoryentity=$this->getServiceManager()->get('phoenix-eventcategory')->getEventCategory($eventIdValue);
        $eventid=$eventCategoryentity[0];

        $data=array(
            'title'=>$eventTitle,
            'description'=>$eventDescription,
            'url'=>$eventUrl,
            'highlights'=>$eventHighlight,
            'eventCategoryId'=>$eventid,
            'currentLanguage'=>$eventLanguage,
        );
        
        return parent::save($calEventModel, $data);
    }
        
        
        
        
}

