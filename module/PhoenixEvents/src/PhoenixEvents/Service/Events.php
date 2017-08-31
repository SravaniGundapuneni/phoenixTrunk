<?php

namespace PhoenixEvents\Service;

use PhoenixEvents\Model\Event;
use PhoenixEvents\Entity\PhoenixEvents;

use PhoenixProperties\Service\SubmoduleNonIntegratedUnifiedAbstract as BaseLists;

class Events extends BaseLists
{
    protected $orderList = true;
    /**
     * __construct
     *
     * Construct our rooms service
     *
     * @return void
     */

    public function __construct()
    {
        $this->entityName = Event::EVENT_ENTITY_NAME;
        $this->modelClass = "\PhoenixEvents\Model\Event";
        $this->dataItem = EVENT::EVENT_DATA_ENTITY;        
    }

    /**
     * getEvent
     *
     * An alias of getItem
     *
     * @param  integer $selector
     * @return \PhoenixEvents\Model\Event
     */
    public function getEvent($selector)
    {
        $result = null;

        if ( intval($selector) )
        {
            $result = $this->getItem($selector);
        }
        elseif ( strlen($selector) )
        {
            $result = $this->getEventBy(array('code' => $selector));
        }

        return $result;
    }

    public function getEventBy(array $filters)
    {
        $entityManager = $this->getDefaultEntityManager();
        $entityRepository = $entityManager->getRepository($this->entityName);
        $entity = $entityRepository->findOneBy(array('code'=>$selector));
        $result = is_null($entity) ? null : $this->createModel($entity);

        return $result;
    }

    public function createEvent($data, $save = false)
    {
        $status = Room::DEFAULT_ITEM_STATUS;
        $entityModel = $this->createModel();
        $entityModel->setEntity(new PhoenixEvent);
        $entityModel->getEntity()->setStatus($status);
        $entityModel->loadFromArray($data);
        if ($save) $entityModel->save();
        return $entityModel;
    }
     public function getPropertyIdOptions ()
    {
        //echo "I am in Pages Service's getHotelOption<br/>";
        $options = array();
         //inject default property as Not Assigned
        $options[0] = 'Not Assigned';
        $hotels = $this->getDefaultEntityManager()->getRepository('PhoenixProperties\Entity\PhoenixProperty')->findBy(array('status' => 1));

        foreach ($hotels as $keyHotel => $valHotel) {
            $options[$valHotel->getId()] = $valHotel->getName();
        }
        //var_dump($options);
        return $options;
    }
}