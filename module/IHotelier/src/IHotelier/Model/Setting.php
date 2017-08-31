<?php
namespace IHotelier\Model;

use Phoenix\Module\Model\ModelAbstract;

class Setting extends ModelAbstract
{
	const DEFAULT_ITEM_STATUS = 1;
	protected $entityClass    = 'IHotelier\Entity\IHotelierSetting';
		
	public function save()
	{
		parent::save();
	}	
}