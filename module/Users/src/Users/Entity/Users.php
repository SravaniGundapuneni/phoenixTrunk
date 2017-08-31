<?php
namespace Users\Entity;

use \Doctrine\ORM\Mapping as ORM;

//use Users\Entity\Admin\Users as GlobalUser;

use Phoenix\Module\Entity\EntityAbstract;

/**
 * Users
 *
 * @ORM\Table(name="site_users", options={"charset"="utf8_general_ci"})
 * @ORM\Entity
 */
class Users extends EntityAbstract
{
	protected $scope = 'site';

   /**
     * @var integer id
     *
     * @ORM\Column(name="userID", type="integer", length = 11)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * 
     */
    protected $id;

    /**
     * @var string username
     *
     * @ORM\Column(name="username", type="string", length = 32)
     * 
     * 
     */
    protected $username;

    /**
     * @var string password
     *
     * @ORM\Column(name="password", type="string", length = 255)
     * 
     * 
     */
    protected $password;

    /**
     * @var string givenName
     *
     * @ORM\Column(name="givenName", type="string", length = 255)
     * 
     * 
     */
    protected $givenName;

    /**
     * @var string surnames
     *
     * @ORM\Column(name="surnames", type="string", length = 255)
     * 
     * 
     */
    protected $surnames;

    /**
     * @var string email
     *
     * @ORM\Column(name="email", type="string", length = 255)
     * 
     * 
     */
    protected $email;

    /**
     * @var integer type
     *
     * @ORM\Column(name="type", type="integer", length = 11)
     * 
     * 
     */
    protected $type;

    /**
     * @var  integer isCorporate
     *
     * @ORM\Column(name="isCorporate", type="integer", length=1)
     */
    protected $isCorporate;

    /**
     * @var \Doctrine\Common\Collections\Collection $usersGroups
     * 
     * @ORM\OneToMany(targetEntity="\Users\Entity\UsersGroups", mappedBy="user")
     */
    protected $usersGroups;		
}