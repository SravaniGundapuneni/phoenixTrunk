<?php

namespace Calendar\Service;


use Zend\ViewRenderer\RendererInterface as ViewRenderer;

use Calendar\Model\PhoenixEventCategory;
use Calendar\EventManager\Event as PhoenixCalendarEvent;

class EventCategory extends \ListModule\Service\Lists {



  /**
     * __construct
     *
     * Construct our Calendar service
     *
     * @return void
     */
    public function __construct()
    {
       
        $this->entityName = PhoenixEventCategory::EVENT_CATEGORY_ENTITY_NAME;
        $this->modelClass = "\Calendar\Model\PhoenixEventCategory";
    }
   
	/*public function createEventCategoryModel() {
		
        $eventCategoryModel = new \Calendar\Model\PhoenixEventCategory($this->config);
        $eventCategoryModel->setDefaultEntityManager($this->getDefaultEntityManager());
        return $eventCategoryModel;
    }
	public function save($eventCatModel, $eventCatData) {
        //echo "save method from service called";		
	    if (!$eventCatModel instanceof \Calendar\Model\PhoenixEventCategory) {
            $eventCatModel = $this->createModel();
        }
		//var_dump($eventCatData);
		$eventCatModel->loadFromArray($eventCatData);

		$eventCatModel->save();
		 
    }*/
    public function getEventCategory($eid){
        $qbEventCategory = $this->getDefaultEntityManager()->createQueryBuilder();
        
		$qbEventCategory->select('c')
                        ->from('Calendar\Entity\EventCategory', 'c')
                        ->where('c.id = :eid')
                        ->setParameter('eid', $eid);
       
	    $result=$qbEventCategory->getQuery()->getResult();
		
	    
	   return $result;
    }
}

