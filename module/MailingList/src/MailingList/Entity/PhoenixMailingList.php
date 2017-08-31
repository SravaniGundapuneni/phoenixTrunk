<?php
namespace MailingList\Entity;

use \Doctrine\ORM\Mapping as ORM;

use Phoenix\Module\Entity\EntityAbstract;
use ListModule\Entity\Entity;
/**
 * PhoenixReview
 *
 * @ORM\Table(name="mailinglist")
 * @ORM\Entity
 */
class PhoenixMailingList extends EntityAbstract
{
    

     /**
     * @var integer id
     *
     * @ORM\Column(name="itemId", type="integer", length=11)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;
	 /**
     * @var string email
     *
     * @ORM\Column(name="email", type="string", length = 255) 
     */
    protected $email;

    /**
     * @var string title
     *
     * @ORM\Column(name="title", type="string", length = 6) 
     */
    protected $title;
	/**
     * @var string firstName
     *
     * @ORM\Column(name="firstName", type="string", length = 255) 
     */
    protected $firstName;
	/**
     * @var string lastName
     *
     * @ORM\Column(name="lastName", type="string", length = 255) 
     */
    protected $lastName;
	/**
     * @var string countryCode
     *
     * @ORM\Column(name="countryCode", type="string", length = 255) 
     */
    protected $countryCode;
	/**
     * @var string hash
     *
     * @ORM\Column(name="hash", type="string", length = 255) 
     */
    protected $hash;
	/**
     * @var string dataSection
     *
     * @ORM\Column(name="dataSection", type="string", length = 255) 
     */
    protected $dataSection;
	 /**
     * @var integer subscribe
     *
     * @ORM\Column(name="subscribe", type="integer", length =11) 
     */
    protected $subscribe;
	/**
     * @var integer emailConfirmed
     *
     * @ORM\Column(name="emailConfirmed", type="integer", length =11) 
     */
    protected $emailConfirmed;
	  
	/**
     * @ORM\Column(name="userId", type="integer", length = 11)
     */
    protected $userId;

    /**
     * @ORM\Column(name="created", type="datetime")
     */
    protected $created;

    /**
     * @ORM\Column(name="modified", type="datetime")
     */
    protected $modified;

    /**
     * @ORM\Column(name="status", type="integer", length = 11)
     */
    protected $status;
    	
}