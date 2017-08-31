<?php
namespace Users\Entity\Admin;

use \Doctrine\ORM\Mapping as ORM;

/**
 * Groups
 *
 * @ORM\Table(name="users_groups")
 * @ORM\Entity
 */
class UsersGroups
{
    /**
     * @var integer id
     *
     * @ORM\Column(name="ugId", type="integer", length = 11)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * 
     */
    protected $id;

    /**
     * @var integer userId
     *
     * @ORM\Column(name="userId", type="integer", length = 11)
     */
    protected $userId;

    /**
     * @var integer groupId
     *
     * @ORM\Column(name="groupId", type="integer", length = 11)
     */
    protected $groupId;

    /**
     * @var Users\Entity\Admin\Users
     *
     * @ORM\ManyToOne(targetEntity="Users\Entity\Admin\Users", inversedBy="usersGroups", cascade={"persist"})
     * @ORM\JoinColumns({
     * @ORM\JoinColumn(name="userId", referencedColumnName="userID", onDelete="cascade")
     * })
     */
    protected $user;

    /**
     * @var Users\Entity\Admin\Groups
     *
     * @ORM\ManyToOne(targetEntity="Users\Entity\Admin\Groups", inversedBy="usersGroups", cascade={"persist"})
     * @ORM\JoinColumns({
     * @ORM\JoinColumn(name="groupId", referencedColumnName="groupId", onDelete="cascade")
     * })
     */
    protected $group;

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
     * getUserId
     *
     * Gets the userId property
     * 
     * @return integer $userId
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * setUserId
     *
     * Sets the userId property
     * 
     * @param integer userId
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * getGroupId
     *
     * Gets the groupId property
     * 
     * @return integer $groupId
     */
    public function getGroupId()
    {
        return $this->groupId;
    }

    /**
     * setGroupId
     *
     * Sets the groupId property
     * 
     * @param integer groupId
     */
    public function setGroupId($groupId)
    {
        $this->groupId = $groupId;

        return $this;
    }    


    /**
     * getGroup
     *
     * Gets the group property
     * 
     * @return Users\Entity\Admin\Group $group
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * setGroup
     *
     * Sets the group property
     * 
     * @param integer group
     */
    public function setGroup($group)
    {
        $this->group = $group;

        return $this;
    }     

    /**
     * getUser
     *
     * Gets the user property
     * 
     * @return Users\Entity\Admin\Users $user
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * setUser
     *
     * Sets the user property
     * 
     * @param Users\Entity\Admin\Users user
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }    
}