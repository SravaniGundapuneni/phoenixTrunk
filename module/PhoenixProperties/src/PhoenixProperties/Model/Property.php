<?php
/**
 * Property Model
 *
 * @category    Toolbox
 * @package     PhoenixProperties
 * @subpackage  Model
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.4
 * @since       File available since release 13.4
 * @author      A. Tate <atate@travelclick.com>
 * @filesource
 */
namespace PhoenixProperties\Model;

class Property extends \ListModule\Model\ListItem
{
    const ENTITY_NAME = 'PhoenixProperties\Entity\PhoenixProperty';

    public function __construct($config = array(), $fields = array())
    {
        $this->entityClass = self::ENTITY_NAME;
        parent::__construct($config, $fields);
    }

    public function addAddon($entityModel)
    {
        $entityModel->getEntity()->setProperty($this->getEntity());
        $this->getEntity()->getPropertyAddons()->add($entityModel->getEntity());
    }

    public function addAttribute($entityModel)
    {
        $entityModel->getEntity()->setProperty($this->getEntity());
        $this->getEntity()->getPropertyAttributes()->add($entityModel->getEntity());
    }

    public function addRoom($entityModel)
    {
        $entityModel->getEntity()->setProperty($this->getEntity());
        $this->getEntity()->getPropertyRooms()->add($entityModel->getEntity());
    }

    public function addRate($entityModel)
    {
        $entityModel->getEntity()->setProperty($this->getEntity());
        $this->getEntity()->getPropertyRates()->add($entityModel->getEntity());
    }

    public function getAddons()
    {
        return $this->getPropertyAddons();
    }

    public function getAttributes()
    {
        return $this->getPropertyAttributes();
    }

    public function getRooms()
    {
        return $this->getPropertyRooms();
    }

    public function getRates()
    {
        return $this->getPropertyRates();
    }
}