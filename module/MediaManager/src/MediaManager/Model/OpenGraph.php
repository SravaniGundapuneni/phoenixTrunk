<?php
namespace MediaManager\Model;

use Phoenix\Module\Model\ModelAbstract;

class OpenGraph extends ModelAbstract
{
	const DEFAULT_ITEM_STATUS = 1;
	protected $entityClass    = 'MediaManager\Entity\MediaManagerOpenGraph';
		
	public function save()
	{
		parent::save();
	}	
}