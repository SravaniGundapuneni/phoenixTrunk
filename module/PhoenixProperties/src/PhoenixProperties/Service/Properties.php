<?php
/**
 * The PhoenixProperties Service
 *
 * @category    Toolbox
 * @package     PhoenixProperties
 * @subpackage  Service
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.4
 * @since       File available since release 13.4
 * @author      A. Tate <atate@travelclick.com>
 * @filesource
 */
namespace PhoenixProperties\Service;

use PhoenixProperties\Model\Property;
use PhoenixProperties\Entity\PhoenixProperty;
use PhoenixProperties\Model\PropertyUser;

use Users\Model\User;

use Phoenix\EventManager\Event as UserEvent;

class Properties extends \ListModule\Service\Lists
{
    protected $orderList = true;
    protected $phoenixAddons;
    protected $phoenixRooms;
    protected $phoenixRates;

    /**
     * __construct
     *
     * Construct our properties service
     *
     * @return void
     */
    public function __construct()
    {
        $this->entityName = Property::ENTITY_NAME;
        $this->modelClass = "\PhoenixProperties\Model\Property";
    }

    /**
     * This method is called from the parent class and adds ordering by 'featured' field to the query
     * @param type $qb
     */
    protected function modifyQuery(&$qb)
    {
        $qb->orderBy('pp.isCorporate DESC, pp.name ASC');
    }

    protected function getItemsResult($entityManager, $entityName, $active = false, $showAll = false)
    {
        if ($showAll || !$this->getCurrentUser() instanceof User || is_null($this->getCurrentUser()->getId()) || $this->getCurrentUser()->isCorporate()) {
            return parent::getItemsResult($entityManager, $entityName, $active);
        }

        $orderBy = array_merge(array('orderNumber' => 'ASC'), $this->orderBy);

        if ($active) {
            return $entityManager->getRepository($entityName)->findBy(array('status' => 1, 'id' => $this->getCurrentUser()->getCurrentProperty()->getId()), $orderBy);
        }

        return $entityManager->getRepository($entityName)->findBy(array('id' => $this->getCurrentUser()->getCurrentProperty()->getId()), $orderBy);        
    }

    /**
     * getProperty
     *
     * An alias of getItem
     *
     * @param  integer $selector
     * @return \PhoenixProperties\Model\Property
     */
    public function getProperty($selector)
    {
        $result = null;

        if ( is_numeric($selector) && intval($selector) )
        {
            $result = $this->getItem($selector);
        }
        elseif ( is_string($selector) && strlen($selector))
        {
            $result = $this->getItemBy(array('code' => $selector));
        }
        elseif ( is_array($selector) && $selector )
        {
            $result = $this->getItemBy($selector);
        }

        return $result;
    }

    public function createProperty($data, $save = false)
    {
        $status = Property::DEFAULT_ITEM_STATUS;
        $entityModel = $this->createModel();
        $entityModel->setEntity(new PhoenixProperty);
        $entityModel->getEntity()->setStatus($status);
        $entityModel->loadFromArray($data);
        if ($save) $entityModel->save();
        return $entityModel;
    }

    public function onUserLogin(UserEvent $e)
    {
        $loginUser = $e->getParam('loginUser');

        $user = $this->createPropertyUser($loginUser);

        //Developers, SuperAdmins, and Corporate Users have access to all sites
        if ($user->isCorporate()) {
            return $user;
        }

        $currentProperty = $this->getServiceManager()->get('currentProperty');
        $corporateProperty = $this->getServiceManager()->get('corporateProperty');

        if ($currentProperty == $corporateProperty) {
            $userProperties = $user->getUserProperties();
            if (!empty($userProperties)) {
                $userLoginRedirect = $this->getServiceManager()->get('phoenix-user-login-redirect');
                $userLoginRedirect->setActive(true);
                $userLoginRedirect->setUserProperty(current($user->getUserProperties()));
                return $user;                
            }
        }

        if (in_array($currentProperty->getId(), array_keys($user->getUserProperties()))) {
            return $user;
        }

        $e->stopPropagation();
        return false;
    }

    public function onGetUser(UserEvent $e)
    {
        $user = $e->getParam('user');

        if ($user instanceof User) {
            $propertyUser = $this->createPropertyUser($user);
        }

        return $propertyUser;
    }

    public function onSessionCreate(\Phoenix\EventManager\Event $e)
    {
        $currentUser = $this->getServiceManager()->get('phoenix-users-current');

        $this->setUserPropertyInfo($currentUser);
    }

    public function setUserPropertyInfo($propertyUser)
    {
        $propertyUser->setProperties($this->getItems(true));
        $propertyUser->setCurrentProperty($this->getServiceManager()->get('currentProperty'));        
    }

    public function createPropertyUser($user)
    {
        $userEntity = $user->getEntity();

        $propertyUser = new PropertyUser($this->getConfig());
        $propertyUser->setDefaultEntityManager($this->getDefaultEntityManager());
        $propertyUser->setAdminEntityManager($this->getAdminEntityManager());        
        $propertyUser->setEntity($userEntity);
        $propertyUser->setEventManager($this->getServiceManager()->get('phoenix-eventmanager'));
        $propertyUser->setAlerts($this->getServiceManager()->get('phoenix-users-alerts'));

        $this->setUserPropertyInfo($propertyUser);
        
        return $propertyUser;
    }
    
    
    public function save($model, $data)
    {
        if (!$model->getUserModified())
        {
            $data['userModified'] = $this->hasChanges($model, $data);
        }
        
        parent::save($model, $data);
    }
    
}
