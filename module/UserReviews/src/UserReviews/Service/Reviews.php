<?php

namespace UserReviews\Service;

use UserReviews\Model\UserReviews;
use UserReviews\EventManager\Event as UserReviewsEvent;

class Reviews extends \ListModule\Service\Lists {



  /**
     * __construct
     *
     * Construct our UserService service
     *
     * @return void
     */
    public function __construct()
    {
       
        $this->entityName = UserReviews::REVIEWS_ENTITY_NAME;
        $this->modelClass = "\UserReviews\Model\UserReviews";
    }
    /**
     * [getItems function gives all tests]
     * @return [result set]
     */
 	public function getItems() {
        return $this->getUserReviews();
    }

    public function getUserReviews() {

        $reviewQuery = $this->getReviewQuery();


        $reviewtitles = $reviewQuery->getQuery()->getResult();

        return  $reviewtitles;
    }

    /**
     * [getReviewQuery description function fetch all records order by created date]
     * @return [object] [result set]
     */
    protected function getReviewQuery() {
        $qb = $this->defaultEntityManager->createQueryBuilder();

        
	    $qb->select('u')
                ->from('UserReviews\Entity\UserReviews', 'u');
              

       return $qb; 
    }
	/**
	* create model
	**/
	public function createUserReviewModel() {
		
       
		$reviewModel = new \UserReviews\Model\UserReviews($this->config);
		
        $reviewModel->setDefaultEntityManager($this->getDefaultEntityManager());
        //$reviewModel->setAdminEntityManager($this->getAdminEntityManager());
        	
        return $reviewModel;
    }
	public function save($ureviewModel, $testData) {
	    if (!$ureviewModel instanceof \UserReviews\Model\UserReviews) {
           
			$ureviewModel = $this->createTestModel();
        }
		
		
		$ureviewModel->loadFromArray($testData);
		var_dump($testData);
        $ureviewModel->save();
		
        //$this->getEventManager()->trigger(\UserReviews\EventManager\Event::EVENT_MVCTEST_SAVE, '\UserReviews\EventManager\Event', array('ureviewModel' => $ureviewModel, 'ureviewData' => $testData));
    }

   
   

}

