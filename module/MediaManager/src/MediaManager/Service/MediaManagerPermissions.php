<?php
namespace MediaManager\Service;
use Phoenix\Service\ServiceAbstract;

class MediaManagerPermissions extends \ListModule\Service\Lists
{
	protected $mmService;

	public function setMediaManager($mmService)
	{
		$mmService->setConfig($this->getServiceManager()->get('mergedConfig'));
		$this->mmService = $mmService;
	}

	public function getMediaManager()
	{
		return $this->mmService;
	}

	public function isReadOnly($fileId = null)
	{
		$isReadOnly  = 'false';

		if ((!$this->isValidPath($fileId) && !$this->isValidUser()) || !$this->isValidAcl()) {
			$isReadOnly = 'true';
		}

		return $isReadOnly;
	}

	private function isValidAcl()
	{
		$acl = $this->getServiceManager()->get('phoenix-users-acl');
		return $acl->isUserAllowed($this->getCurrentUser(), null, \Users\Service\Acl::PERMISSIONS_RESOURCE_ADMIN);
	}

	private function isValidPath($fileId)
	{
		$mmService = $this->getMediaManager();
		$path      = $fileId ? $this->getFullFilePath($fileId) : $mmService->getMediaRoot();
		return in_array($path, $mmService->getUserBasePaths());
	}

	private function isValidUser()
	{
		return $this->getCurrentUser()->getIsCorporate();
	}
	
	private function getFullFilePath($fileId)
	{
		$mmService = $this->getMediaManager();
		$fileInfo  = $mmService->getFilesBy(array('id' => $fileId), array('name' => 'ASC'));
		if (!$fileInfo) {
			throw new \Exception('Bad fileId');
		}
		$filePath  = ltrim($fileInfo[0]->getPath() . '/', '\/');
		return $mmService->getMediaRoot() . $filePath;
	}
}
