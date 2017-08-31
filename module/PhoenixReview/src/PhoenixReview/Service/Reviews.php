<?php

namespace PhoenixReview\Service;

use Zend\Mail;
use Zend\ViewRenderer\RendererInterface as ViewRenderer;

use Zend\Mime\Part as MimePart;
use Zend\Mime\Message as MimeMessage;

use PhoenixReview\Model\PhoenixReview;
use PhoenixReview\EventManager\Event as PhoenixReviewEvent;

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
       
        $this->entityName = PhoenixReview::REVIEWS_ENTITY_NAME;
        $this->modelClass = "\PhoenixReview\Model\PhoenixReview";
    }
   
	/**
	* create model
	**/
	/*public function createUserReviewModel() {
		
       
		$reviewModel = new \PhoenixReview\Model\PhoenixReview($this->config);
		
        $reviewModel->setDefaultEntityManager($this->getDefaultEntityManager());
        
        	
        return $reviewModel;
    }
	public function save($ureviewModel, $testData) {
	
		
	    if (!$ureviewModel instanceof \PhoenixReview\Model\PhoenixReview) {
           
			$ureviewModel = $this->createTestModel();
        }
		
		
		$ureviewModel->loadFromArray($testData);
		//var_dump($testData);
        $ureviewModel->save();
		
        
    }*/
	
	/***
	* function to send email to user.
	******/
	public function sendEmail($remail,$msgTitle,$name, $content) {
		
		
		$subject = $msgTitle;
		$mail = new Mail\Message();
		//$mail->setBody($content);
		$mail->setFrom('contactt@travelclick.com', 'Contact');
		$mail->addTo($remail,$name);
		
		$mail->setSubject($subject);
		// make a header as html  
        $html = new MimePart($content);
        $html->type = "text/html";
        $body = new MimeMessage();
        $body->setParts(array($html,));
		$mail->setBody($body);
		$transport = new Mail\Transport\Sendmail();
		$transport->send($mail);
			
		return true;
			
		
}
   
   

}

