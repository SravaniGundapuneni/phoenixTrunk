<?php
namespace Users\Entity\Admin;

use \Doctrine\ORM\Mapping as ORM;

use Phoenix\Module\Entity\EntityAbstract;

/**
 * Users
 *
 * @ORM\Table(name="users", options={"charset"="utf8_general_ci"})
 * @ORM\Entity
 */
class Users extends EntityAbstract
{
    protected $scope = 'global';

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
     * @var datetime resetPassExpires
     *
     * @ORM\Column(name="resetPassExpires", type="datetime")
     * 
     * 
     */
    protected $resetPassExpires;
    
    /**
     * @var string resetPassKey
     *
     * @ORM\Column(name="resetPassKey", type="string")
     * 
     * 
     */
    protected $resetPassKey;

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
     * @ORM\OneToMany(targetEntity="\Users\Entity\Admin\UsersGroups", mappedBy="user")
     */
    protected $usersGroups;

    /**
     * getId
     *
     * Gets the id property
     * 
     * @return integer $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * setId
     *
     * Sets the id property
     * 
     * @param integer id
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * getUsername
     *
     * Gets the username property
     * 
     * @return string $username
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * setUsername
     *
     * Sets the username property
     * 
     * @param string username
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * getPassword
     *
     * Gets the password property
     * 
     * @return string $password
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * setPassword
     *
     * Sets the password property
     * 
     * @param string password
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * getGivenName
     *
     * Gets the givenName property
     * 
     * @return string $givenName
     */
    public function getGivenName()
    {
        return $this->givenName;
    }

    /**
     * setGivenName
     *
     * Sets the givenName property
     * 
     * @param string givenName
     */
    public function setGivenName($givenName)
    {
        $this->givenName = $givenName;

        return $this;
    }

    /**
     * getSurnames
     *
     * Gets the surnames property
     * 
     * @return string $surnames
     */
    public function getSurnames()
    {
        return $this->surnames;
    }

    /**
     * setSurnames
     *
     * Sets the surnames property
     * 
     * @param string surnames
     */
    public function setSurnames($surnames)
    {
        $this->surnames = $surnames;

        return $this;
    }

    /**
     * getEmail
     *
     * Gets the email property
     * 
     * @return string $email
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * setEmail
     *
     * Sets the email property
     * 
     * @param string email
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * getType
     *
     * Gets the type property
     * 
     * @return integer $type
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * setType
     *
     * Sets the type property
     * 
     * @param integer type
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    public function getIsCorporate()
    {
        return $this->isCorporate;
    }

    public function setIsCorporate($isCorporate)
    {
        $this->isCorporate = $isCorporate;

        return $this;
    }

    /**
     * setUsersGroups
     *
     * Setter for usersGroups
     * 
     * @param \Doctrine\Common\Collections\Collection usersGroups
     */
    public function setUsersGroups($usersGroups)
    {
        $this->usersGroups = $usersGroups;

        return $this;
    }        

    /**
     * getUsersGroups
     *
     * Getter for usersGroups 
     * @return \Doctrine\Common\Collections\Collection $usersGroups
     */
    public function getUsersGroups()
    {
        return $this->usersGroups;
    }          
}