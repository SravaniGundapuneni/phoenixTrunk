<?php
namespace Integration\Helper;

use Zend\View\Helper\AbstractHelper; 

class GetPropertyAddonId extends AbstractHelper
{
    protected $entityManager;

    public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function __invoke($addonCode, $propertyCode)
    {
        $result = $this->entityManager->getConnection()->fetchColumn('select propertyId from phoenixProperties where code = :code', array(':code' => $propertyCode));

        if (empty($result)) {
            return false;
        }

        $addon = $this->entityManager->getConnection()->fetchColumn('select addonId from phoenixAddons where property = :propertyId and code = :addonCode', array(':propertyId' => $result, 'addonCode' => $addonCode));

        return (!empty($result)) ? (int) $addon : false;
    }
}