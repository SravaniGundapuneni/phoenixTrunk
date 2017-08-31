<?php
namespace MediaManager\Model;

use Phoenix\Module\Model\ModelAbstract;

class SchemaDotOrg extends ModelAbstract
{
	const DEFAULT_ITEM_STATUS = 1;
	protected $entityClass    = 'MediaManager\Entity\MediaManagerSchemaDotOrg';
		
	public function save()
	{
		parent::save();
	}	
}