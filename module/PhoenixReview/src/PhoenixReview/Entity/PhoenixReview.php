<?php
namespace PhoenixReview\Entity;

use \Doctrine\ORM\Mapping as ORM;

use Phoenix\Module\Entity\EntityAbstract;
use ListModule\Entity\Entity;
/**
 * PhoenixReview
 *
 * @ORM\Table(name="phoenixReview")
 * @ORM\Entity
 */
class PhoenixReview extends EntityAbstract
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
     * @var string title
     *
     * @ORM\Column(name="title", type="string", length = 255) 
     */
    protected $title;
	/**
     * @var string content
     *
     * @ORM\Column(name="content", type="string", length = 255) 
     */
    protected $content;
	/**
     * @var string name
     *
     * @ORM\Column(name="name", type="string", length = 255) 
     */
    protected $name;
	/**
     * @var string userId
     *
     * @ORM\Column(name="userId", type="integer", length =11) 
     */
    protected $userId;
	/**
     * @var string status
     *
     * @ORM\Column(name="status", type="integer", length =11) 
     */
    protected $status;
	
	/**
     * @var string emailId
     *
     * @ORM\Column(name="emailId", type="string", length = 255) 
     */
    protected $emailId;
	
	/**
     * @var datetime created
     *
     * @ORM\Column(name="created", type="datetime")
     * 
     * 
     */
    protected $created;

    /**
     * @var datetime created
     *
     * @ORM\Column(name="modified", type="datetime")
     * 
     * 
     */
    protected $modified;

    	
}