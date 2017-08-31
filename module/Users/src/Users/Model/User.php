<?php
namespace Users\Model;

use Phoenix\Module\Model\ModelAbstract;
use Users\Service\Acl;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

use Users\Entity\Admin\Users as GlobalUser;
use Users\Entity\Users as SiteUser;

use Users\Entity\Admin\UsersGroups as GlobalUsersGroups;
use Users\Entity\UsersGroups as SiteUsersGroups;

class User extends \ListModule\Model\ListItem
{
    protected $baseAccessLevel = Acl::PERMISSIONS_GROUP_READ;

    protected $userEntity;

    protected $inputFilter;

    protected $alerts;

    protected $propertyId = 0;

    protected $permissionsGroup;

    protected $permissionsEntity;

    protected $scope = 'site';

    const ENTITY_NAME = 'Users\Entity\Admin\Users';

    public function __construct($config)
    {
        $this->config = $config;
    }

    public function getBaseAccessLevel()
    {
        return $this->baseAccessLevel;
    }

    public function setAlerts($alerts)
    {
        $this->alerts = $alerts;
    }

    public function getAlerts()
    {
        return $this->alerts;
    }

    public function setBaseAccessLevel($accessLevel)
    {
        $this->baseAccessLevel = $accessLevel;
    }

    public function getUserEntity()
    {
        return $this->userEntity;
    }

    public function setUserEntity(\Phoenix\Module\Entity\EntityAbstract $userEntity)
    {
        $this->userEntity = $userEntity;
        $this->setEntity($userEntity);
    }

    public function isSuperAdmin()
    {
        return ($this->userEntity instanceof \Users\Entity\Admin\Users) ? ($this->isDeveloper() || $this->userEntity->getType() == 1) : false;
    }

    public function isDeveloper()
    {
        return ($this->userEntity instanceof \Users\Entity\Admin\Users) ? ($this->userEntity->getType() == 2) : false;
    }

    public function setPermissionsGroup($groupName)
    {
        $this->permissionsGroup = $groupName;
    }

    public function getPermissionsGroup()
    {
        return $this->permissionsGroup;
    }

    public function setPermissionsEntity(\Users\Entity\Permissions $entity)
    {
        $this->permissionsEntity = $entity;
    }

    public function getPermissionsEntity()
    {
        return $this->permissionsEntity;
    }

    public function getGroups()
    {
        $userEntity = $this->getUserEntity();

        $groups = array();

        if ($userEntity) {
            foreach ($userEntity->getUsersGroups() as $valUserGroup) {
                $groups[$valUserGroup->getId()] = $valUserGroup->getGroup();
            }
        }

        return $groups;
    }

    public function getInputFilter()
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
            $factory     = new InputFactory();

            $inputFilter->add($factory->createInput(array(
                'name'     => 'id',
                'required' => true,
                'filters'  => array(
                    array('name' => 'Int'),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'username',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 100,
                        ),
                    ),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'givenName',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 255,
                        ),
                    ),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'email',
                'required' => false,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'EmailAddress',
                        'options' => array(
                        ),
                    ),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'password',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 6,
                            'max'      => 50,
                        ),
                    ),
                ),
            )));            

            $inputFilter->add($factory->createInput(array(
                'name'     => 'passwordConfirm',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 6,
                            'max'      => 50,
                        ),
                    ),
                    array(
                        'name' => 'Identical',
                        'options' => array(
                            'token' => 'password',
                            'messages' => array(
                                'notSame' => 'The password and passwordConfirm fields must match.'
                            )
                        )
                    ),
                ),
            )));             

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;        
    }

    public function loadById($userId, $siteOnly = true)
    {
        if (is_null($userId) || !$userId) {
            return false;
        }
        $this->userEntity = $this->adminEntityManager->getRepository('Users\Entity\Admin\Users')->find($userId);

        if (empty($this->userEntity)) {
            //Check for site users
            $this->userEntity = $this->defaultEntityManager->getRepository('Users\Entity\Users')->find($userId);
        }

        //Added for forwards compatibility
        $this->entity = $this->userEntity;

        if (empty($this->userEntity)) {
            return false;
        }

        return (!empty($this->userEntity)) ? true : false;
    }

    public function exchangeArray($loadArray = array())
    {
        return $this->loadFromArray($loadArray);
    }

    public function loadFromArray($loadArray = array())
    {
        $scope = !empty($loadArray['scope']) ? $loadArray['scope'] : 'site';
        if (!$this->userEntity instanceof \Users\Entity\Admin\Users && !$this->userEntity instanceof \Users\Entity\Users) {
            $userEntity = ($scope == 'global') ? new GlobalUser() : new SiteUser();
            $this->userEntity = $userEntity;
            $this->userEntity->setSurnames('');
            $this->userEntity->setType(0);
            $this->entity = $this->userEntity;
        }
        foreach ($loadArray as $key => $value) {
            $setMethod = 'set' . ucfirst($key);

            if ($key == 'propertyId') {
                $this->propertyId = $value;
                continue;
            }

            if ($key == 'baseAccessLevel') {
                $this->setBaseAccessLevel($value);
            }

            if (is_callable(array($this->userEntity, $setMethod))) {
                if ($setMethod == 'setPassword') {
                    $this->userEntity->setPassword(md5($value));
                    continue;
                }
                $this->userEntity->$setMethod($value);
            }
        }
    }

    public function addGroup($group)
    {   
        $userEntity = $this->getUserEntity();

        $usersGroups = $userEntity->getUsersGroups();

        $userGroupEntity = ($this->getEntity()->getScope() == 'global') ? new GlobalUsersGroups() : new SiteUsersGroups();

        $userGroupEntity->setUser($userEntity);
        $userGroupEntity->setGroup($group->getEntity());

        $usersGroups->add($userGroupEntity);

        $entityManager = ($this->getEntity()->getScope() == 'global') ? $this->adminEntityManager : $this->defaultEntityManager;
        $entityManager->persist($userGroupEntity);
        $entityManager->flush();
    }

    public function save()
    {
        if ($this->userEntity->getId() == 0) {
            $this->userEntity->setId('');
        }

        $entityManager = ($this->getScope() == 'global') ? $this->adminEntityManager : $this->defaultEntityManager;
        $entityManager->persist($this->userEntity);
        $entityManager->flush();

        if (!$this->isSuperAdmin() && isset($this->propertyId) && !$this->skipProperties) {
            $this->doUserProperty();
        }
    }

    public function doUserProperty()
    {
        $qbDeleteUserProperty = $this->getDefaultEntityManager()->createQueryBuilder();

        $qbDeleteUserProperty->delete('Users\Entity\UserProperty', 'up')
                             ->where('up.userId = :userId')
                             ->setParameter('userId', $this->userEntity->getId());
        $qbDeleteUserProperty->getQuery()->execute();

        $userProperty = new \Users\Entity\UserProperty();

        if ($this->isCorporate()) {
            $this->propertyId = 0;
        }

        $userProperty->setUserId($this->userEntity->getId());
        $userProperty->setPropertyId($this->propertyId);
        $userProperty->setBaseAccessLevel($this->getBaseAccessLevel());
        $this->defaultEntityManager->persist($userProperty);
        $this->defaultEntityManager->flush();

    }

    public function isValid($userEntity, $siteOnly = true)
    {
        if (is_null($userEntity)) {
             return false;
         }

        //This is all...invalid...due to changes that are coming. We'll just call everybody valid until
        //those are in place.
        return true;
        // if (!$siteOnly || $this->isSiteUser($this->userEntity)) {
        //     return true;
        // }

        // return false;
    }

    /**
     * @deprecated
     * DO NOT USE!!! Property specific users are handled differently
     * @param  [type]  $userEntity [description]
     * @return boolean             [description]
     */
    public function isSiteUser($userEntity)
    {
        $siteUserCheck = $this->defaultEntityManager->getRepository('Users\Entity\Permissions')->findOneBy(array('userId' => $userEntity->getId()));

        if (is_null($siteUserCheck)) {
            return $this->checkConfigForUser($userEntity);
        }

        return true;
    }

    public function checkConfigForUser($userEntity)
    {
        return (in_array($userEntity->getUsername(), $this->config->get('usersFromConfig')));
    }

    /**
     * This will load the current user if they have a session in the db
     * Or 
     * This will set the userId for the session from the current user id, in cases such as login.
     * @param  PhoenixEvent $e 
     * @return boolean
     */
    public function onSessionCreate($e)
    {
        $sessionModel = $e->getParam('sessionModel');
        $sessionEntity = $sessionModel->getEntity();


        if ($this->isValid($this->userEntity, false)) {
            $sessionEntity->setUserId($this->userEntity->getId());
        } else {
            $userLoaded = $this->loadById($sessionEntity->getUserId(), false);
            if (!$userLoaded) {
                $sessionEntity->setUserId(0);
            }
        }

        //This is for compatibility with the legacy code.
        if ($sessionEntity->getUserId() > 0) {
            $sessionModel->set('users', 'userID', $this->userEntity->getId());
        }

        //We have our current user. Trigger the events associated with this event.
        $this->getEventManager()->trigger(\Users\EventManager\Event::EVENT_LOAD_CURRENTUSER, '\Users\EventManager\Event', array('userModel' => $this));

        return true;
    }

    public function getUserId()
    {
        return ($this->userEntity instanceof \Users\Entity\Admin\Users) ? $this->userEntity->getId() : 0;
    }

    public function getUsername()
    {
        return ($this->userEntity instanceof \Users\Entity\Admin\Users) ? $this->userEntity->getUsername() : false;   
    }

    public function getArrayCopy()
    {
        return $this->toArray();
    }

    public function toArray()
    {
        return array(
            'id' => $this->userEntity->getId(),
            'username' => $this->userEntity->getUsername(),
            'givenName' => $this->userEntity->getGivenName(),
            'email' => $this->userEntity->getEmail(),
            'groupId' => ($this->permissionsEntity instanceof \Users\Entity\Permissions) ? $this->permissionsEntity->getGroupId() : \Users\Service\Acl::PERMISSIONS_GROUPID_READ,
            'isCorporate' => $this->userEntity->getIsCorporate(),
            'type' => $this->userEntity->getType(),
            'baseAccessLevel' => $this->getBaseAccessLevel()
        );
    }

    public function delete()
    {
        $this->getAdminEntityManager()->remove($this->userEntity);
        $this->getAdminEntityManager()->flush();
    }
}