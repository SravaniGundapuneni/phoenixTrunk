<?php
namespace MediaManager\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\Servicemanager\ServiceLocatorInterface;

class IsReadOnly extends AbstractHelper implements ServiceLocatorAwareInterface
{
	protected $serviceLocator;

	public function __invoke()
	{
		return $this->isReadOnly();
	}	

	public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
	{
		if ($this->serviceLocator !== null) {
			return $this;
		}

		$this->serviceLocator = $serviceLocator;
		return $this;
	}

	public function getServiceLocator()
	{
		return $this->serviceLocator;
	}
	
	private function isReadOnly()
	{
		$mmPermissions = $this->getServiceLocator()->getServiceLocator()->get('phoenix-mediamanager-permissions');
		return $mmPermissions->isReadOnly();
	}
}