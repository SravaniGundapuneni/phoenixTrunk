<?php
namespace MediaManager\Service;

use MediaManager\Model\SchemaDotOrg as SchemaDotOrgModel;
use Phoenix\Service\ServiceAbstract;

class MediaManagerSchemaDotOrg extends \ListModule\Service\Lists
{
	const SCHEMA_DOT_ORG = '\MediaManager\Entity\MediaManagerSchemaDotOrg';
	
	public function __construct()
	{}	

	public function getItemprop($fileId)
	{
		$itemprop = '';
		$em = $this->getDefaultEntityManager();
		$entity = $em->getRepository(self::SCHEMA_DOT_ORG)->findOneBy(array('fileId' => $fileId));

		if ($entity) {
			$itemprop = $entity->getItemprop();
		}

		return $itemprop;
	}

	public function saveItemprop($fileId, $itemprop)
	{
		if ($this->itempropExists($fileId, $itemprop)) {
			$this->updateItemprop($fileId, $itemprop);	
		} else {
			$this->insertItemprop($fileId, $itemprop);
		}
	}

	private function itempropExists($fileId, $itemprop)
	{
		return $this->getDefaultEntityManager()
					->getRepository(self::SCHEMA_DOT_ORG)
					->findOneBy(array('fileId' => $fileId));
	}

	private function updateItemprop($fileId, $itemprop)
	{
		$queryBuilder = $this->getDefaultEntityManager()->createQueryBuilder();
		$queryBuilder->update(self::SCHEMA_DOT_ORG, 'sdo')
				 ->set('sdo.itemprop', '?1')
				 ->where('sdo.fileId = ?2')
				 ->setParameter(1, $itemprop)
				 ->setParameter(2, $fileId)
				 ->getQuery()->execute();
	}

	private function insertItemprop($fileId, $itemprop)
	{
		$openGraphTag = $this->createModel(new \MediaManager\Entity\MediaManagerSchemaDotOrg());
		$openGraphTag->getEntity()->setFileId($fileId);
		$openGraphTag->getEntity()->setItemprop($itemprop);
		$openGraphTag->save();
	}

	public function createModel($entity)
	{
		if (!$entity) {
			throw new \Exception('Undefined entity');
		}

		$model = new SchemaDotOrgModel();
		$model->setDefaultEntityManager($this->getDefaultEntityManager());
		$model->setEntity($entity);

		return $model;
	}
}