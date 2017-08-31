<?php
namespace Users\Service;

use ListModule\Service\Lists;
use Users\EventManager\Event as UserEvent;

class Permissions extends Lists
{
    public function getItems()
    {
        $mergedConfig = $this->getConfig();

        return $mergedConfig->get('userPermissions', array());
    }
}