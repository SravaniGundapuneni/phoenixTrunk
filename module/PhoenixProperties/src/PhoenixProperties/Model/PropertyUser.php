<?php
/**
 * FIle for PropertyUser Class
 *
 * @category    Toolbox
 * @package     PhoenixProperties
 * @subpackage  Model
 * @copyright   Copyright (c) 2014 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 1.14
 * @since       File available since release 1.14
 * @author      A. Tate <atate@travelclick.com>
 * @filesource
 */

namespace PhoenixProperties\Model;

use Users\Model\User;

class PropertyUser extends User
{
    protected $userProperties = array();

    protected $currentProperty;

    protected $skipProperties = false;

    public function setEntity($entity)
    {
        parent::setEntity($entity);
        $this->userEntity = $entity;
    }

    public function setProperties()
    {
        $userPropertiesResult = $this->getDefaultEntityManager()->getRepository('Users\Entity\UserProperty')->findBy(array('userId' => $this->getId()));

        if (!empty($userPropertiesResult)) {
            foreach ($userPropertiesResult as $valProperty) {
                $this->userProperties[$valProperty->getPropertyId()] = $valProperty;
            }
        }
    }

    public function getUserProperties()
    {
        return $this->userProperties;
    }

    public function isCorporate()
    {
        return ($this->isDeveloper() || $this->isSuperAdmin() || $this->getIsCorporate());
    }

    public function setBaseAccessLevel($property)
    {
        if (is_string($property)) {
            $this->baseAccessLevel = $property;
            return;
        }

        if ($this->isDeveloper()) {
            $this->baseAccessLevel = \Users\Service\Acl::PERMISSIONS_GROUP_DEVELOP;
            return;
        }

        if ($this->isSuperAdmin()) {
            $this->baseAccessLevel = \Users\Service\Acl::PERMISSIONS_GROUP_SUPER_ADMIN;

            return;
        }

        //O denotes Corporate Site in table
        if ($this->getIsCorporate()) {
            $this->baseAccessLevel = $this->userProperties[0]->getBaseAccessLevel();

            return;
        }

        if (in_array($property->getId(), array_keys($this->userProperties))) {
            $this->baseAccessLevel = $this->userProperties[$property->getId()]->getBaseAccessLevel();
        }
    }

    public function setCurrentProperty($currentProperty)
    {
        $this->currentProperty = $currentProperty;

        $this->setBaseAccessLevel($currentProperty);
    }

    public function getCurrentProperty()
    {
        return $this->currentProperty;
    }

    public function getArrayCopy()
    {
        $modelArray = parent::getArrayCopy();
        $modelArray['baseAccessLevel'] = $this->getBaseAccessLevel();

        return $modelArray;
    }

    public function loadFromArray($loadArray = array())
    {
        parent::loadFromArray($loadArray);

        $this->exchangeArray($loadArray);
    }    

    public function exchangeArray($data)
    {
        //parent::exchangeArray($data);

        //We don't need a site record for these type of users
        if ($this->isDeveloper() || $this->isSuperAdmin()) {
            return;
        }

        if (empty($data['skipProperties'])) {
            $propertyId = ($this->getIsCorporate()) ? 0 : $this->getCurrentProperty()->getId();

            if (!isset($this->userProperties[$propertyId])) {
                $userProperty = new \Users\Entity\UserProperty();
                $userProperty->setPropertyId($propertyId);
                $userProperty->setUserId($this->getUserId());
                $this->userProperties[$propertyId] = $userProperty;
            }

            $this->userProperties[$propertyId]->setBaseAccessLevel($data['baseAccessLevel']);
        } else {
            $this->skipProperties = true;
        }
    }

    public function save()
    {
        parent::save();

        $propertyId = ($this->getIsCorporate()) ? 0 : $this->getCurrentProperty()->getId();

        if (isset($this->userProperties[$propertyId]) && !$this->skipProperties) {
            $this->getDefaultEntityManager()->persist($this->userProperties[$propertyId]);
            $this->getDefaultEntityManager()->flush();
        }

    }
}