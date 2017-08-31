<?php
namespace MediaManager\Service;

use MediaManager\Model\OpenGraph as OpenGraphModel;
use Phoenix\Service\ServiceAbstract;

class MediaManagerOpenGraph extends \ListModule\Service\Lists
{
	const OPEN_GRAPH = '\MediaManager\Entity\MediaManagerOpenGraph';
	
	public function __construct()
	{}	

	public function getOpenGraphTags($fileId)
	{
		$responseData = array();
		$openGraphTags = $this->getAllByFileId($fileId);

		foreach ($openGraphTags as $tag) {
			array_push($responseData, array('propertyValue' => $tag->getOGProperty(),
											'contentValue'  => $tag->getContent()));
		}

		return $responseData;
	}

	public function deleteOpenGraphTags($fileId, $tagData)
	{
		$em = $this->getDefaultEntityManager();
		$entities = $em->getRepository(self::OPEN_GRAPH)->findBy(array('fileid' => $fileId));

		// clean this up with array_filter
		foreach ($entities as $entity) {
			$inArray = false;
			foreach ($tagData as $tagDatum) {
				if ($tagDatum['ogproperty'] === $entity->getOGProperty()) {
					$inArray = true;
					break;
				}	
			}

			if (!$inArray) {
				$this->deleteOpenGraphTag($fileId, $entity->getOGProperty());
			}
		}
	}

	private function deleteOpenGraphTag($fileId, $property)
	{
		$queryBuilder = $this->getDefaultEntityManager()->createQueryBuilder();
		$queryBuilder->delete(self::OPEN_GRAPH, 'mmog')
				 ->where('mmog.fileid = ?1')
				 ->andWhere('mmog.ogproperty = ?2')
				 ->setParameter(1, $fileId)
				 ->setParameter(2, $property)
				 ->setMaxResults(1)
				 ->getQuery()->execute();
	}

	public function saveOpenGraphTags($fileId, $tagDatum)
	{
		if ($this->tagExists($fileId, $tagDatum)) {
			$this->updateOpenGraphTag($fileId, $tagDatum);
		} else {
			$this->insertOpenGraphTag($fileId, $tagDatum);
		}
	}

	private function tagExists($fileId, $tagDatum)
	{
		return $this->getDefaultEntityManager()
					->getRepository(self::OPEN_GRAPH)
					->findOneBy(array('ogproperty' => $tagDatum['ogproperty'],
									  'fileid'     => $fileId));     
	}

	private function updateOpenGraphTag($fileId, $tagDatum)
	{
		$queryBuilder = $this->getDefaultEntityManager()->createQueryBuilder();
		$queryBuilder->update(self::OPEN_GRAPH, 'mmog')
				 ->set('mmog.content', '?1')
				 ->where('mmog.fileid = ?2')
				 ->andWhere('mmog.ogproperty = ?3')
				 ->setParameter(1, $tagDatum['content'])
				 ->setParameter(2, $fileId)
				 ->setParameter(3, $tagDatum['ogproperty']) 
				 ->getQuery()->execute();
	}

	private function insertOpenGraphTag($fileId, $tagDatum)
	{
		$openGraphTag = $this->createModel(new \MediaManager\Entity\MediaManagerOpenGraph());
		$openGraphTag->getEntity()->setFileid($fileId);
		$openGraphTag->getEntity()->setOGProperty($tagDatum['ogproperty']);
		$openGraphTag->getEntity()->setContent($tagDatum['content']);
		$openGraphTag->save();
	}

	private function getAllByFileId($fileId)
	{
		$em = $this->getDefaultEntityManager();
		$entity = $em->getRepository(self::OPEN_GRAPH)->findBy(array('fileid' => $fileId));
		return $entity;
	}

	public function createModel($entity)
	{
		if (!$entity) {
			throw new \Exception('Undefined entity');
		}

		$model = new OpenGraphModel();
		$model->setDefaultEntityManager($this->getDefaultEntityManager());
		$model->setEntity($entity);

		return $model;
	}
}