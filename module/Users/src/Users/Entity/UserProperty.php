<?php
namespace Users\Entity;

use Phoenix\Module\Entity\EntityAbstract;
use \Doctrine\ORM\Mapping as ORM;

/**
 *
 * @ORM\Table(name="user_property")
 * @ORM\Entity
 */
 class UserProperty extends EntityAbstract
 {
     /**
      * @var integer id
      * 
      * @ORM\Column(name="upId", type="integer", length=11)
      * @ORM\Id
      * @ORM\GeneratedValue(strategy="IDENTITY")
      *
    */
     protected $id;

     /**
      * @var integer userId
      *
      * @ORM\Column(name="userId", type="integer")
     */
     protected $userId;

     /**
      * @var integer propertyId
      *
      * @ORM\Column(name="propertyId", type="integer")
     */
     protected $propertyId;     

     /**
      * @var integer baseAccessLevel
      *
      * @ORM\Column(name="baseAccessLevel", type="string", length=255)
     */
     protected $baseAccessLevel;          
 }