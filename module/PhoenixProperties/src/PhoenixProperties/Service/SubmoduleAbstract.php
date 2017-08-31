<?php
namespace PhoenixProperties\Service;

use ListModule\Service\Lists;
use Users\Model\User;

class SubmoduleAbstract extends Lists
{

    protected function getItemsResult($entityManager, $entityName, $active = false, $showAll = false)
    {
        if ($showAll || !$this->getCurrentUser() instanceof User || is_null($this->getCurrentUser()->getId()) || $this->getCurrentUser()->isCorporate()) {
            return parent::getItemsResult($entityManager, $entityName, $active);
        }

        if ($active) {
            return $entityManager->getRepository($entityName)->findBy(array('status' => 1, 'property' => $this->getCurrentUser()->getCurrentProperty()->getEntity()), $this->orderBy);
        }

        return $entityManager->getRepository($entityName)->findBy(array('property' => $this->getCurrentUser()->getCurrentProperty()->getEntity()), $this->orderBy);        
    }    
}