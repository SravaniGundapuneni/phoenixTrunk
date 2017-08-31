<?php
/**
 * The SiteMap Service
 *
 * @category    Toolbox
 * @package     SiteMap
 * @subpackage  Service
 * @copyright   Copyright (c) 2014 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.5
 * @since       File available since release 13.5
 * @author      Alex Kotsores <akotsores@travelclick.com>
 * @filesource
 */
namespace SiteMaps\Service;

use SiteMaps\Model\PhoenixSiteMap;
use SiteMaps\Entity\SiteMapsItems;
use ListModule\Service\UnifiedLists;

class SiteMaps extends UnifiedLists
{
    protected $isScan = false;
    protected $orderList = true;
    

   
     public function __construct()
    {
       
        $this->entityName = PhoenixSiteMap::SITEMAP_ENTITY_NAME;
        $this->modelClass = "\SiteMaps\Model\PhoenixSiteMap";
        $this->dataEntity = PhoenixSiteMap::SITEMAP_DATA_ENTITY;
    }
   
    
    public function scanFolder()
    {
       
		$this->isScan = true;
        //clear records in table
        $em = $this->getDefaultEntityManager();
        
        $this->getTranslateFields();

        $entries = $em->getRepository($this->entityName)->findAll();
        
        foreach($entries as $entry){
            $em->remove($entry);
        }
        $em->flush();
       
        /*****Added code ****/
		
		$sectionService = $this->getServiceManager()->get('phoenix-sitemapsection');
		$pageSectionService=$this->getserviceManager()->get('phoenix-sitemappages');
		$dataSection=$sectionService->getDataSection();
		$pageDataSection=$pageSectionService->getPageDataSection();
		
		$PageKey="";
		$dataSectionId='';
		
			$flag=0;
			$intflag=0;
			foreach($dataSection as $section)
			{
				
				foreach($pageDataSection as $pgsection)
				{
					//echo "datasection:---".$section->getDataSection()."----:pg datasection:----".$pgsection->getDataSection()."<br>";				
					//echo "page keys:---".$pgsection->getPageKey()."<br>";
					//echo "<br>";
					if($pgsection->getDataSection()==null && $flag==0)
					{
					    $dataSectionId=$section;
						$pageKey=$pgsection->getPageKey();
						
						
					}else
					{
					    //$val=strpos($pgsection->getDataSection(),$section->getDataSection());
						if(strpos($pgsection->getDataSection(),$section->getDataSection())===0)
						{
						    $dataSectionId=$section;
							if(mb_substr_count($pgsection->getDataSection(),"/")>1)
							{
							    $dataseclen=strlen($section->getDataSection());
								$secondDir=substr($pgsection->getDataSection(),$dataseclen);
								$pageKey=$secondDir."/".$pgsection->getPageKey();
															
							}else
							{
							    if(strcmp($pgsection->getPageKey(),"default")===0)
								{
								     
								}
								$pageKey=$pgsection->getPageKey();
							}
						}
						else
						{
							$intflag=1;
						}
										
					}
					$created=$pgsection->getCreated();
					$modified=$pgsection->getModified();
					$visible=1;
					
					if($intflag==1)
					{
						//echo "do not insert..<br>";
						$intflag=0;
					}
					else{
						$data = array(
                            'dataSectionId' => $section,
							'pageKey' => $pageKey,
							'dynamicPage' => 1,
							'visible'=>$visible,
							'created'=> $created,
							'modified'=> $modified
							);
					    
						$this->save($this->createModel(), $data);
					}
				
				}
				$flag=1;
				
				
			}
		
		/****END CODE **************/
	}
	
	 public function getAllList()
    {
        
			$qb2=$this->defaultEntityManager
                                         ->createQuery('
                                                       SELECT sp, sps
                                                       FROM SiteMaps\Entity\SiteMap sp
                                                       JOIN sp.dataSectionId sps ');
            				
			$result = $qb2->getResult();
		    return $result;
	   
    }
    public function getAllPageKeyGrpByDataSection(){
        
        
        $qbLink=$this->getDefaultEntityManager()->createQueryBuilder();
        $qbLink->select('sp','sps')
               ->from('SiteMaps\Entity\SiteMap','sp')
               ->join('sp.dataSectionId','sps')
			   ->where('sp.visible=:v')
			   ->setParameter('v',1);
               //->groupBy('sps.id');
        $result=$qbLink->getQuery()->getResult();
        return $result;
    }
	
	 /**
     * method populating dropdown for dataSection on the page..
	 *getDataSectionIdOptions
     *
     * @return array
     */
    public function getDataSectionIdOptions ()
    {
         //echo "I am in Pages Service's getDataSectionOption<br/>";
        $options = array();
         //inject default datasection as Not Assigned
        $options[0] = 'Not Assigned';
        $datasections = $this->getDefaultEntityManager()->getRepository('SiteMaps\Entity\SiteMapSection')->findAll();

        foreach ($datasections as $keySection => $valSection) {
            $options[$valSection->getId()] = $valSection->getDataSection();
        }

            $datasection = new \Zend\Form\Element\Select('dataSectionId');
            $datasection->setLabel('Data Section');
            $datasection->setLabelAttributes(array('class' => 'blockLabel'));
            $datasection->setAttribute('class', 'stdTextInput');
            $datasection->setValueOptions($options);
       
		
        return $options;
    }
	
	public function createSiteMap($data, $save = false)
    {
        //$status = Attribute::DEFAULT_ITEM_STATUS;
        $entityModel = $this->createModel();
        $entityModel->setEntity(new SiteMapsItems);
        //$entityModel->getEntity()->setStatus($status);
        $entityModel->loadFromArray($data);
        if ($save) $entityModel->save();
        return $entityModel;
    }
	
}