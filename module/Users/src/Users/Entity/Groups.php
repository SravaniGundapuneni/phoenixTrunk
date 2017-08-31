<?php
namespace Users\Entity;

use \Doctrine\ORM\Mapping as ORM;

use Phoenix\Module\Entity\EntityAbstract;

/**
 * Groups
 *
 * @ORM\Table(name="site_groups")
 * @ORM\Entity
 */
class Groups extends EntityAbstract
{
    protected $scope = 'site';

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
}