<?php
namespace PhoenixProperties\Service;

use Phoenix\Service\ServiceAbstract;

class UserLoginRedirect extends ServiceAbstract
{
    protected $userProperty;

    protected $active = false;

    public function setUserProperty($userProperty)
    {
        $this->userProperty = $userProperty;
    }

    public function setActive($active)
    {
        $this->active = ($active) ? true : false;
    }

    public function isActive()
    {
        return $this->active;
    }

    public function getUserProperty()
    {
        return $this->userProperty;
    }

    public function doRedirect($controller)
    {
        $propertyService = $this->getServiceManager()->get('phoenix-properties');

        $property = $propertyService->getItem($this->getUserProperty()->getPropertyId());

        $url = $property->getUrl();

        if (!empty($url)) {
            return $controller->redirect()->toRoute('home/toolbox-root-subsite', array('subsite' => $url));
        }

        return false;
    }
}