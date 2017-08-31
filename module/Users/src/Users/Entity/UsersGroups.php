<?php
namespace Users\Entity;

use \Doctrine\ORM\Mapping as ORM;

use Phoenix\Module\Entity\EntityAbstract;

/**
 * Groups
 *
 * @ORM\Table(name="site_users_groups")
 * @ORM\Entity
 */
class UsersGroups extends EntityAbstract
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
     * @var Users\Entity\Users
     *
     * @ORM\ManyToOne(targetEntity="Users\Entity\Users", inversedBy="usersGroups", cascade={"persist"})
     * @ORM\JoinColumns({
     * @ORM\JoinColumn(name="userId", referencedColumnName="userID", onDelete="cascade")
     * })
     */
    protected $user;

    /**
     * @var Users\Entity\Groups
     *
     * @ORM\ManyToOne(targetEntity="Users\Entity\Groups", inversedBy="usersGroups", cascade={"persist"})
     * @ORM\JoinColumns({
     * @ORM\JoinColumn(name="groupId", referencedColumnName="groupId", onDelete="cascade")
     * })
     */
    protected $group;	
}