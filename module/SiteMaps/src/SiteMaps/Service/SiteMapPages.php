<?php

namespace SiteMaps\Service;

use SiteMaps\Model\PhoenixSiteMapPages;


class SiteMapPages extends \ListModule\Service\Lists {


  /**
     * __construct
     *
     * Construct our UserService service
     *
     * @return void
     */
    public function __construct()
    {
       
        $this->entityName = PhoenixSiteMapPages::SITEMAP_PAGES_ENTITY_NAME;
        $this->modelClass = "\SiteMaps\Model\PhoenixSiteMapPages`";
    }
   
	
	public function getPageDataSection()
	{
	    $qbSelectPageDataSection = $this->getDefaultEntityManager()->createQueryBuilder();
        
		$qbSelectPageDataSection->select('u')
                           ->from('SiteMaps\Entity\SiteMapPages', 'u');
                            
       
	    $result=$qbSelectPageDataSection->getQuery()->getResult();
		
	   
	   return $result;
	
	}
	
   
   

}

