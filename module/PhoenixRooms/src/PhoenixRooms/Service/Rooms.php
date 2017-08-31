<?php

namespace PhoenixRooms\Service;

use PhoenixRooms\Model\Room;
use PhoenixRooms\Entity\PhoenixRoom;
use PhoenixProperties\Service\SubmoduleUnifiedAbstract;

class Rooms extends SubmoduleUnifiedAbstract
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
        $this->entityName = Room::ENTITY_NAME;
        $this->modelClass = "\PhoenixRooms\Model\Room";
    }

    /**
     * This method is called from the parent class and adds ordering by 'featured' field to the query
     * @param type $qb
     */
    protected function modifyQuery(&$qb)
    {
        $qb->orderBy('pp.featured DESC, pp.property ASC, pp.name ASC');
    }

    /**
     * getRoom
     *
     * An alias of getItem
     *
     * @param  integer $selector
     * @return \PhoenixRooms\Model\Room
     */
    public function getRoom($selector)
    {
        $result = null;

        if (is_numeric($selector) && intval($selector)) {
            $result = $this->getItem($selector);
        } elseif (is_string($selector) && strlen($selector)) {
            $result = $this->getItemBy(array('code' => $selector));
        } elseif (is_array($selector) && $selector) {
            $result = $this->getItemBy($selector);
        }

        return $result;
    }

    public function createRoom($data, $save = false)
    {
        $status = Room::DEFAULT_ITEM_STATUS;
        $entityModel = $this->createModel();
        $entityModel->setEntity(new PhoenixRoom);
        $entityModel->getEntity()->setStatus($status);
        $entityModel->loadFromArray($data);
        if ($save)
            $entityModel->save();
        return $entityModel;
    }

    public function save($model, $data)
    {
        if (!$model->getUserModified()) {
            $data['userModified'] = $this->hasChanges($model, $data);
        }

        if (!empty($data['property']) && !is_object($data['property'])) {
            $data['property'] = $this->getServiceManager()->get('phoenix-properties')->getItem($data['property'])->getEntity();
        } else {
            $data['property'] = $this->getServiceManager()->get('currentProperty')->getEntity();
        }

        parent::save($model, $data);
    }

}
