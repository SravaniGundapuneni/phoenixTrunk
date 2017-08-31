<?php
namespace MediaManager\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\Servicemanager\ServiceLocatorInterface;

class CropRatios extends AbstractHelper implements ServiceLocatorAwareInterface
{
	protected $serviceLocator;

	public function __invoke()
	{
		return $this->getCropRatioOptions();
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
	
	private function getCropRatioOptions()
	{
		$mmCrop = $this->getServiceLocator()->getServiceLocator()->get('phoenix-mediamanager-crop');
		return $mmCrop->getCropRatios();
	}
}