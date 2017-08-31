<?php
/**
 * File for Group Model class
 *
 * @category    Phoenix
 * @package     Users
 * @subpackage  Model
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.4
 * @since       File available since release 13.4
 * @author      A. Tate <atate@travelclick.com>
 * @filesource
 */
namespace Users\Model;

use ListModule\Model\ListItem;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

/**
 * Group Model Class
 *
 * @category    Phoenix
 * @package     Users
 * @subpackage  Model
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.4
 * @since       File available since release 13.4
 * @author      A. Tate <atate@travelclick.com>
 */
class Group extends ListItem
{
    /**
     * The Namespace for the Groups entity
     */
    const GROUP_ENTITY_NAME = 'Users\Entity\Admin\Groups';

    /**
     * Holds the groups entity
     * @var Users\Entity\Admin\Groups
     */
    protected $entity;

    protected $scope;

    /**
     * setter for $this->entity
     * @param Users\Entity\Admin\Groups $entity
     */
    public function setEntity($entity)
    {
        $this->entity = $entity;
    }

    /**
     * getter for $this->entity
     * @return Users\Entity\Admin\Groups $this->entity
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * Returns the group name from the entity, if it exists. Otherwise, returns false.
     * @return string|boolean
     */
    public function getName()
    {
        return ($this->getEntity()) ? $this->getEntity()->getName() : false;
    }

    public function getScope()
    {
        if (empty($this->scope)) {
            $scope = $this->getEntity() instanceof \Users\Entity\Admin\Groups ? 'global' : 'site';
            $this->setScope($scope);
        }

        return $this->scope;
    }

    public function setScope($scope)
    {
        $this->scope = $scope;
    }


    /**
     * Returns the groupId from the entity, if it exists. Otherwise, returns 0.
     * @return integer
     */
    public function getId()
    {
        return ($this->getEntity()) ? $this->getEntity()->getId() : false;
    }

    public function getInputFilter()
    {
        if (!$this->inputFilter) {
            $this->inputFilter = new InputFilter();
        }

        $factory     = new InputFactory();

        $this->inputFilter->add($factory->createInput(array(
            'name'     => 'id',
            'required' => true,
            'filters'  => array(
                array('name' => 'Int'),
            ),
        )));

        $this->inputFilter->add($factory->createInput(array(
            'name'     => 'name',
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


        return $this->inputFilter;
    }

    public function getPermissions()
    {
        $permissionsArray = array();

        $permissions = $this->getDefaultEntityManager()->getRepository('Users\Entity\Permissions')->findBy(array('groupId' => $this->getEntity()->getId()));

        if (!is_null($permissions)) {
            foreach ($permissions as $valPermission) {
                $permissionsArray[$valPermission->getAuthLevel()][] = $valPermission->getArea() . '-' . $valPermission->getName();
            }
        }

        return $permissionsArray;
    }

    public function getArrayCopy()
    {
        return $this->toArray();
    }

    public function toArray()
    {
        return array(
            'id' => $this->entity->getId(),
            'name' => $this->entity->getName(),
            'created' => $this->entity->getCreated(),
            'modified' => $this->entity->getModified(),
        );
    }

    public function exchangeArray($loadArray = array())
    {
        return $this->loadFromArray($loadArray);
    }

    public function loadFromArray($loadArray = array())
    {
        $scope = !empty($loadArray['scope']) ? $loadArray['scope'] : 'site';

        $this->setScope($scope);

        if (!$this->entity instanceof \Phoenix\Module\Entity\EntityAbstract) {
            $entity = ($scope == 'global') ? new \Users\Entity\Admin\Groups() : new \Users\Entity\Groups();
            $this->entity = $entity;
            $this->entity->setModified(new \DateTime());
            $this->entity->setCreated(new \DateTime());
        }
        foreach ($loadArray as $key => $value) {
            $setMethod = 'set' . ucfirst($key);

            if (is_callable(array($this->entity, $setMethod))) {
                $this->entity->$setMethod($value);
            }
        }
    } 

    public function save()
    {
        if ($this->entity->getId() == 0) {
            $this->entity->setId('');
            $this->entity->setCreated(new \DateTime());
            $this->entity->setModified(new \DateTime());
            $this->entity->setUsersGroups(array());
        }

        $entityManager = ($this->getScope() == 'global') ? $this->adminEntityManager : $this->defaultEntityManager;

        $entityManager->persist($this->entity);
        $entityManager->flush();
    } 

    public function delete()
    {
        $entityManager = ($this->getScope() == 'global') ? $this->adminEntityManager : $this->defaultEntityManager;

        $entityManager()->remove($this->entity);
        $entityManager()->flush();
    }    
}