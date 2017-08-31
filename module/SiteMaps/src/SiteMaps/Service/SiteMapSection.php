<?php

namespace SiteMaps\Service;

use SiteMaps\Model\PhoenixSiteMapSection;


class SiteMapSection extends \ListModule\Service\Lists {


  /**
     * __construct
     *
     * Construct our UserService service
     *
     * @return void
     */
    public function __construct()
    {
       
        $this->entityName = PhoenixSiteMapSection::SITEMAP_SECTION_ENTITY_NAME;
        $this->modelClass = "\SiteMaps\Model\PhoenixSiteMapSection";
    }
   
	 public function getDataSection()
    {
      
	   $qbSelectMapSection = $this->getDefaultEntityManager()->createQueryBuilder();
        
		$qbSelectMapSection->select('u')
                           ->from('SiteMaps\Entity\SiteMapSection', 'u');
                             //->where('ps.dataSectionId = :sId')
                             //->setParameter('sId', $this->PhoenixSiteMapSectionEntity->getId());
       
	    $result=$qbSelectMapSection->getQuery()->getResult();
		
	    
	   return $result;
	   
    }
	
	
   
   

}

