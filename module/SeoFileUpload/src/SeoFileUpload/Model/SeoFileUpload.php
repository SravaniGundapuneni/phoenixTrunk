<?php
/** 
 * SeoFileUpload Model
 *
 * @category                              Toolbox
 * @package                               SeoFileUpload
 * @subpackage                            Model
 * @copyright                             Copyright (c) 2013 TravelClick, Inc (http://travelclick.com)
 * @license                               All Rights Reserved
 * @version                               Release 13.5
 * @since                                 File available since release 13.5
 * @author                                Kevin Davis <kedavis@travelclick.com>
 * @filesource
*/

namespace SeoFileUpload\Model;



class SeoFileUpload extends \ListModule\Entity\Entity
{
	const MEDIAMANGER_ENTITY_NAME = 'SeoFileUpload\Entity\SeoFileUpload';


	/**
	 * The Media Manager DB Entity
	 * @var SeoFileUpload\Entity\SeoFileUpload
	*/

	protected $seoFileUploadEntity;

	/**
	 * *
	 * setSeoFileUpload
	 *
	 * @param SeoFileUpload\Entity\SeoFileUpload $seoFileUploadEntity
	*/

	protected function setSeoFileUpload($seoFileUploadEntity)
	{
		$this->seoFileUploadEntity = $seoFileUploadEntity;
	}

	/**
	 * getSeoFileUpload
	 *
	 * @param SeoFileUpload\Entity\SeoFileUpload $seoFileUpload
	*/

	public function getSeoFileUpload()
	{
		return $this->seoFileUploadEntity;
	}

	/**
	 *
	 * getMedia
	 * 
	 * Return what the media calls or uploads.
	 * @return string
	*/

	public function getMedia()
	{
       return ($this->getSeoFileUpload()) ? $this->getSeoFileUploadEntity()->getMedia() : '';
	}

    public function loadFromArray($loadArray = array())
    {
    	if (!$this->seoFileUploadEntity instanceof \SeoFileUpload\Entity\SeoFileUpload) {
    		$this->seoFileUploadEntity = new \SeoFileUpload\Entity\SeoFileUpload();
    	}

    	foreach ($loadArray as $key => $value) {
    		$setMethod = 'set' . ucfirst($key);

    		if (is_callable(array($this->seoFileUploadEntity, $setMethod))) {
			   $this->seoFileUploadEntity->$setMethod($value);
    		}
    	}
    }


   public function toArray()
   {

   	  return array (
        'id' => $this->seoFileUploadEntity->getId(),
        'getfilepath' => $this->getSeoFileUploadEntity->getFilePath(),
       
        'datasection' => $this->getSeoFileUploadEntity->getDataSection(),
      
        'date' => $this->getSeoFileUploadEntity->getDate(),
        'filenameorig' => $this->getSeoFileUploadEntity->getFileNameOrig(),
      

   	  	);
   }
   
}