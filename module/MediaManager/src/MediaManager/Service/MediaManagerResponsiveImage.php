<?php
namespace MediaManager\Service;
use Phoenix\Service\ServiceAbstract;

class MediaManagerResponsiveImage extends \ListModule\Service\Lists
{
	protected $mmService;	
	protected $sizes = array('Small', 'Medium', 'Large');
	protected $imagePath;
	protected $imageName;

	public function setMediaManager($mmService)
	{
		$mmService->setConfig($this->getServiceManager()->get('mergedConfig'));
		$this->mmService = $mmService;
	}

	public function getMediaManager()
	{
		return $this->mmService;
	}

	public function getResponsiveImage($fileId, $moduleName)
	{
		$this->setImageName($fileId);
		$this->setImagePath($fileId);

		$config          = $this->getServiceManager()->get('mergedConfig');
		$responsiveSizes = $config->get(array('responsive-sizes', $moduleName));
		$allSizes        = $this->getAllSizes();
		$returnData      = array();	

		foreach ($this->sizes as $size) {
			if (isset($responsiveSizes[$size])) {
				$returnData[$size] = $this->buildPath($allSizes[$responsiveSizes[$size]]['folder']);
			} else {
				$returnData[$size] = $this->buildPath($allSizes[$size]['folder']);
			}
		}

		return $returnData;
	}

	private function buildPath($folder)
	{
		return $this->imagePath . $folder . '/' . $this->imageName;
	}

	private function getAllSizes()
	{
		$config = $this->getServiceManager()->get('mergedConfig');
		$mediaManagerSizes = $config->get(array('images', 'mediaManagerSizes'));
		$defaultSizes      = $config->get(array('images', 'defaultSizes'));
		return array_merge($mediaManagerSizes, $defaultSizes);
	}

	private function setImageName($fileId)
	{
		$this->imageName = $this->mmService->getFileName($fileId);
	}

	private function setImagePath($fileId)
	{
		$this->imagePath = $this->mmService->getPath($fileId);
	}
}