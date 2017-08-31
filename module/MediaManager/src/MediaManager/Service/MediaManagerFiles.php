<?php

namespace MediaManager\Service;

use ListModule\Service\Lists;

class MediaManagerFiles extends Lists
{
	protected $entityName = 'MediaManager\Entity\MediaManagerFiles';

	public function __construct()
	{
		$this->modelClass = "\MediaManager\Model\File";
	}

	public function getItems()
	{
	   
	   $qbMediaFile = $this->getDefualtEntityManager()->createQueryBuilder();

	   $qbMediaFile->select('mf')->from(MediaManagerFile::MEDIAMANAGERFILE_ENTITY_NAME,'mf');

	   $results = $qbMediaFile->getQuery()->getResult();
	   $mediaFile = array();

	   if (!is_null($results)) {
			foreach ($results as $valMedia) {
				$mediaModel = $this->createMediaModel($valMedia);
				$media[] = $mediaModel;
			}
		}
	}

	public function getMediaFile($mediaFileId)
	{
		return $this->getItem($mediaFileId);
	}

	/*public function getItem($mediaFileId)
	{
		$mediaFileEntity = $this->getDefaultEntityManager()->getRepository(MediaFile::MEDIAMANAGERFILE_ENTITY_NAME)->findOneByarray(array('id' => $mediaFileId));

		if (!is_null($mediaFileEntity)) {
			$mediaFileModel = $this->createMediaFileModel($mediaFileEntity);
			return $mediaFileUpload;
		}
	}*/

	public function save($mediaModelFile, $mediaFileData)
	{
		if (!$mediaModelFile instanceof MedaFile) {
			$mediaModelFile = $this->createMediaFileModel();
		}

		$mediaFileModel->loadFromArray($mediaFileData);
		$mediaFileModel->save();
	}

	public function upload($mediaModeFile, $mediaFileData )
	{
		if (!$mediaModelFile instanceof MediaFile) {
			$mediaModelFile = $this->createMediaFileModel();
		}

		$mediaFileModel->loadFromArray($mediaFileData);
		$mediaFileModel->upload();
	}
	
	public function getWidgetImageData($fid)
	{
		return $this->getDefaultEntityManager()
					->getRepository('\MediaManager\Entity\MediaManagerFiles')
					->findOneBy(array('id' => $fid));

	}

	public function getAllWidgetImageData($fid)
	{
		return $this->getDefaultEntityManager()
					->getRepository('\MediaManager\Entity\MediaManagerFiles')
					->findBy(array('id' => $fid));
	}
}
