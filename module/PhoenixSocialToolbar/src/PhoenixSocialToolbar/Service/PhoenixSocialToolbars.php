<?php
namespace PhoenixSocialToolbar\Service;

use PhoenixSocialToolbar\Model\PhoenixSocialToolbar;
use ListModule\Service\Lists;

class PhoenixSocialToolbars extends Lists
{
    public function __construct()
    {
         $this->socialToolbar  = PhoenixSocialToolbar::SOCIALTOOLBAR_ENTITY_NAME;
         //$this->socialToolbarSmallSettings = SocialToolbarSmallSettings::SOCIALTOOLBAR_SMALL_SETTINGS_ENTITY_NAME;
         $this->modelClass = "\PhoenixSocialToolbar\Model\PhoenixSocialToolbar";
    }

    public function getItems()
    {
        $results = $this->getItemsResult($this->getDefaultEntityManager(), PhoenixSocialToolbar::SOCIALTOOLBAR_ENTITY_NAME);
        //$qbSocialMedia = $this->getDefaultEntityManager()->createQueryBuilder();

        
        //var_dump($this->getDefaultEntityManager());
        //die;
        /**
        $qbSocialMedia->select('sm','smtss')
        ->from(SocialToolbar::SOCIALTOOLBAR_ENTITY_NAME,'sm')
        ->join(SocialToolbarSmallSettings::SOCIALTOOLBAR_SMALL_SETTINGS_ENTITY_NAME, 'smtss')
        ->where("sm.socialToolId = smtss.socialToolId");
        **/

        //$results = $qbSocialMedia->getQuery()->getResult();

       
        $socialMedia = array();

        if (!is_null($results)) {
            foreach ($results as $valSocialMedia) {               
                $socialModel = $this->createSocialToolbarModel($valSocialMedia);
                $socialMedia[] = $socialModel;
            }
        }

      return $socialMedia;
    }

   public function getItem($socialToolId) 
   {
     $socialEntity = $this->getDefaultEntityManager()->getRepository(PhoenixSocialToolbar::SOCIALTOOLBAR_ENTITY_NAME)
     ->findOneBy(array('id' => $socialToolId));
    //$socialEntity = $this->entityName;
   
     if (!is_null($socialEntity)) {
        $socialModel = $this->createSocialToolbarModel($socialEntity);
        return false;
     }
   }


    public function getSocialToolbarService($dataSection = '')
    {
        $socialToolbarArray = array();

        if ($dataSection) {         
              $socialToolbar = $this->getByDataSection($dataSection);  


            if ($socialToolbar) {
               $socialToolbarArray[] = $socialToolbar->toArray();
           } 
        }

        return $socialToolbarArray;
    }

    public function socialMatch($socialSource, $parameters)
    {
        $socialMatch = array();


        return $socialMatch;
    }

    public function createSocialToolbarModel($socialEntity = false)
    {
        $socialModel = new PhoenixSocialToolbar($this->getConfig());
        $socialModel->setDefaultEntityManager($this->getDefaultEntityManager());  
        $socialModel->setAdminEntityManager($this->getAdminEntityManager());

        if ($socialEntity) {
            $socialModel->setEntity($socialEntity);            
        }

        return $socialModel;
    }
   
}