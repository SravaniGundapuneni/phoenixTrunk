<?php
namespace Users\Service;

use ListModule\Service\Lists;
use Users\Model\Group;

class Groups extends Lists
{
    protected $scope;

    public function setScope($scope)
    {
        $this->scope = $scope;
    }

    public function getScope()
    {
        return $this->scope;
    }

    public function getItems($scope = 'both')
    {

        switch ($scope) {
            case 'global':
                $results = $this->getItemsResult($this->getAdminEntityManager(), '\Users\Entity\Admin\Groups');
                break;
            case 'site':
                $results = $this->getItemsResult($this->getDefaultEntityManager(), '\Users\Entity\Groups');
                break;
            default:
                $results = array_merge($this->getItemsResult($this->getAdminEntityManager(), '\Users\Entity\Admin\Groups'), $this->getItemsResult($this->getDefaultEntityManager(), '\Users\Entity\Groups'));
                break;
        }

        $groups = array();

        //If there are results, loop through them and attach them to a model.
        if (!is_null($results)) {
            foreach($results as $valProperty) {
                $propertyModel = $this->createModel($valProperty);
                $groups[] = $propertyModel;
            }
        }

        return $groups;        
    }

    public function getItem($groupId)
    {
        if ($this->getScope() == 'global') {
            $entityManager = $this->getAdminEntityManager();
            $entityName = '\Users\Entity\Admin\Groups';
        } else {
            $entityManager = $this->getDefaultEntityManager();
            $entityName = '\Users\Entity\Groups';
        }

        $groupEntity = $entityManager->getRepository($entityName)->findOneBy(array('id' => $groupId));

        if (empty($groupEntity) && $this->getScope() != 'global') {
            $groupEntity = $this->getAdminEntityManager()->getRepository('\Users\Entity\Admin\Groups')->findOneBy(array('id' => $groupId));
        }

        if (!empty($groupEntity)) {
            $groupModel = $this->createModel($groupEntity);
            
            return $groupModel;
        }

        return false;
    }

    public function createModel($groupEntity = false)
    {
        $newModel = new Group($this->getConfig());
        $newModel->setDefaultEntityManager($this->getDefaultEntityManager());
        $newModel->setAdminEntityManager($this->getAdminEntityManager());

        if ($groupEntity instanceof \Phoenix\Module\Entity\EntityAbstract) {
            $newModel->setEntity($groupEntity);
        }

        return $newModel;
    }

    public function save($groupModel = null, $groupData = array())
    {
        if (!$groupModel instanceof Group) {
            $groupModel = $this->createModel();
        }

        $groupModel->loadFromArray($groupData);
        return $groupModel->save();
    }

    public function loadFromEntityArray(array $entityArray) 
    {
        $modelArray = array();
        foreach ($entityArray as $valEntity) {
            if (!$valEntity instanceof \Users\Entity\Admin\Groups) {
                continue;
            }

            $modelArray[] = $this->createModel($valEntity);
        }

        return $modelArray;
    }

    public function publish($items, $approved = false)
    {
        return true;
    }

    public function setGroupPermissions($itemId, $permissions, $authLevels)
    {
        $qbDelete = $this->defaultEntityManager->createQueryBuilder();

        $qbDelete->delete('Users\Entity\Permissions', 'p')
                 ->where('p.groupId = :itemId')
                 ->setParameter('itemId', $itemId);

        $qbDelete->getQuery()->execute();

        foreach ($authLevels as $valAuthLevel) {
            $fieldName = 'chk' . ucfirst($valAuthLevel);

            if (isset($permissions[$fieldName]) && is_array($permissions[$fieldName])) {
                foreach ($permissions[$fieldName] as $permsGroup) {
                    $fieldValues = explode('-', $permsGroup);
                    $area = $fieldValues[0];
                    $name = $fieldValues[1];
                    $permissionEntity = new \Users\Entity\Permissions();
                    $permissionEntity->setGroupId($itemId);
                    $permissionEntity->setArea($area);
                    $permissionEntity->setName($name);
                    $permissionEntity->setAuthLevel($valAuthLevel);
                    $permissionEntity->setModified(new \DateTime());
                    $permissionEntity->setCreated(new \DateTime());

                    $this->defaultEntityManager->persist($permissionEntity);
                    $this->defaultEntityManager->flush();
                }
            }
        }
    }
}