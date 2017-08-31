<?php
namespace Users\Entity\Admin;

use \Doctrine\ORM\Mapping as ORM;

use \Phoenix\Module\Entity\EntityAbstract;

/**
 * Groups
 *
 * @ORM\Table(name="groups")
 * @ORM\Entity
 */
class Groups extends EntityAbstract
{
    protected $scope = 'global';
    
    /**
     * @var integer id
     *
     * @ORM\Column(name="groupId", type="integer", length = 11)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * 
     */
    protected $id;

    /**
     * @var string name
     *
     * @ORM\Column(name="name", type="string", length = 255) 
     */
    protected $name;

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

    /**
     * @var \Doctrine\Common\Collections\Collection $usersGroups
     * 
     * @ORM\OneToMany(targetEntity="UsersGroups", mappedBy="group")
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
     * getName
     *
     * Gets the name property
     * 
     * @return string $name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * setName
     *
     * Sets the name property
     * 
     * @param string name
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * getCreated
     *
     * Gets the created property
     * 
     * @return datetime $created
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * setCreated
     *
     * Sets the created property
     * 
     * @param datetime created
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * getModified
     *
     * Gets the modified property
     * 
     * @return datetime $modified
     */
    public function getModified()
    {
        return $this->modified;
    }

    /**
     * setCreated
     *
     * Sets the modified property
     * 
     * @param datetime modified
     */
    public function setModified($modified)
    {
        $this->modified = $modified;

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