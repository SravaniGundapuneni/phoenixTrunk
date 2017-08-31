<?php
namespace PhoenixProperties\Service;

use ListModule\Service\Lists;
use Users\Model\User;

class SubmoduleNonIntegratedAbstract extends Lists
{

    protected function getItemsResult($entityManager, $entityName, $active = false, $showAll = false)
    {
        if ($showAll || !$this->getCurrentUser() instanceof User || is_null($this->getCurrentUser()->getId()) || $this->getCurrentUser()->isCorporate()) {
            return parent::getItemsResult($entityManager, $entityName, $active);
        }

        if ($active) {
            return $entityManager->getRepository($entityName)->findBy(array('status' => 1, 'propertyId' => $this->getCurrentUser()->getCurrentProperty()->getId()), $this->orderBy);
        }
        
        return $entityManager->getRepository($entityName)->findBy(array('propertyId' => $this->getCurrentUser()->getCurrentProperty()->getId()), $this->orderBy);
    }    
}