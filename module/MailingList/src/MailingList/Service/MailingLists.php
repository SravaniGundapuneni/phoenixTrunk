<?php

namespace MailingList\Service;


use Zend\ViewRenderer\RendererInterface as ViewRenderer;

use MailingList\Model\PhoenixMailingList;
use MailingList\EventManager\Event as PhoenixReviewEvent;

class MailingLists extends \ListModule\Service\Lists {



  /**
     * __construct
     *
     * Construct our MailingList service
     *
     * @return void
     */
    public function __construct()
    {
       
        $this->entityName = PhoenixMailingList::MAILINGLIST_ENTITY_NAME;
        $this->modelClass = "\MailingList\Model\PhoenixMailingList";
    }
    
    public function createMailingListModel()
    {
        $mlModel = new \MailingList\Model\PhoenixMailingList($this->config);        
        $mlModel->setDefaultEntityManager($this->getDefaultEntityManager());
        $mlModel->setLanguages($this->getLanguageService());
        return $mlModel;
    }

    public function save($mlModel, $mlData)
    {
        if (!$mlModel instanceof \MailingList\Model\PhoenixMailingList) {
            $mlModel = $this->createMailingListModel();
        }

        $mlModel->exchangeArray($mlData);
        return $mlModel->save();
    }
   
	
	
	/***
	* function to Export Mailing list in the text.
	******/
	public function exportContacts() {
	
		$mlistqry=$this->getContactName();
		$rs=$mlistqry->getQuery()->getResult();
		
		$listArray[]=array("FirstName","LastName","Email");
		foreach($rs as $result)
		{
			//$title=$result['title'];
			$fname=$result['firstName'];
			$lname=$result['lastName'];
			$email=$result['email'];
			$listArray[]=array($fname,$lname,$email);
		}
		
		return $listArray;
}
    /****
	**function to Export Mailing list in the Spreadsheet
	*******/
	
    public function exportDataAsCSVFormat()
	{
		$emailResult=array();
		
		//$record=1;
		$qry=$this->getAllDetails();
		
		
		// THIS LINE WRITES THE TABLE HEADERS VALUES TO A MULTI-DIMENSIONAL ARRAY	
		$emailResult[]=array("itemId","title","firstName","lastName","email","countryCode","subscribe");
		
		
		foreach($qry as $rs)
		{
			
			$itemId=$rs->getId();
			$title=$rs->getTitle();
			$firstName=$rs->getFirstName();
			$lastName=$rs->getLastName();
			$email=$rs->getEmail();
			$countryCode=$rs->getCountryCode();
			$subscribe=$rs->getSubscribe();
			
			
			
			if($subscribe==1){
				
				
				$excelFileSubscribe='Subscribe';
			}
			else{
				
				$excelFileSubscribe='Unsubscribe';
			
			}
			
			// THIS LINE WRITES THE ROW VALUES OBTAINED TO A MULTI-DIMENSIONAL ARRAY	
			$emailResult[]=array($itemId,$title,$firstName,$lastName,$email,$countryCode,$excelFileSubscribe);
			
		}
		
		return $emailResult;
	
	}
   
	protected function getContactName()
	{
	    $qbLogin = $this->getDefaultEntityManager()->createQueryBuilder();

        $qbLogin->select('m.firstName,m.lastName,m.email')
                ->from('MailingList\Entity\PhoenixMailingList', 'm')
				->where('m.status=:st')
				->andwhere('m.subscribe=:s')
				->setParameter('s',1)
				->setParameter('st',1);
         
		return $qbLogin;	
	
	}
	
	protected function getAllDetails()
	{
		//$dlist = $this->defaultEntityManager->getConnection()->fetchAll("select * from mailinglist");
		 $qbLogin = $this->getDefaultEntityManager()->createQueryBuilder();
		 $qbLogin->select('m')
              ->from('MailingList\Entity\PhoenixMailingList', 'm')
			  ->where('m.status=:st')
			  ->andwhere('m.subscribe=:s')
			  ->setParameter('s',1)
			  ->setParameter('st',1);
		 $result =  $qbLogin->getQuery()->getResult();	
		return  $result;
	}
	
	public function getStatistics()
	{
		
		$c=$this->getTotalEmailByCountryCode();
		
		$arrySize=count($c);
		$t=$this->getTotalRecords();
		$totalrecords=$t[0]['rtotal'];
		
		foreach($c as $tlist)
		{
			$ctotal=(int)$tlist['total'];
			$ccode=$tlist['countryCode'];
			$per=round((int)$ctotal/((int)$totalrecords),2)*100;
			$result[]=array("total"=>$ctotal,"country"=>$ccode,"per"=>$per);
		}
			
			/*$ctotal=(int)($c[$i]['total']);
			$ccode=$c[$i]['countryCode'];
			$per=round(((int)$ctotal)/((int)$totalrecords),2)*100;
			$result[$i]=array($ccode,$ctotal,$per);*/
			
	
		//$result="country".$c[0]['countryCode']."Emails".(int)$ctotal."per".$per;
		
		return $result;
	}
	
	protected function getTotalEmailByCountryCode()
	{
		$clist=$this->getDefaultEntityManager()->createQueryBuilder();
		$clist->select('COUNT(m.id) as total,m.countryCode')
		      ->from('MailingList\Entity\PhoenixMailingList','m')
			  ->groupBy('m.countryCode')
			  ->where('m.subscribe=:s')
			  ->andwhere('m.status=:st')
			  ->setParameter('s',1)
			  ->setParameter('st',1);
		$result=$clist->getQuery()->getResult();
		return $result;
	
	}
	protected function getTotalRecords()
	{
		$rlist=$this->getDefaultEntityManager()->createQueryBuilder();
		$rlist->select('COUNT(r.id) as rtotal')
			  ->from('MailingList\Entity\PhoenixMailingList','r')
			  ->where('r.status=:st')
			  ->setParameter('st',1);
		
		$rtotal=$rlist->getQuery()->getResult();
		return $rtotal;
	}
}

