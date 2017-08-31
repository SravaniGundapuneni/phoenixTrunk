<?php
namespace Users\Entity;

use \Doctrine\ORM\Mapping as ORM;

/**
 * Permissions
 *
 * @ORM\Table(name="permissions")
 * @ORM\Entity
 */
class Permissions
{
    /**
     * @var integer id
     *
     * @ORM\Column(name="permissionId", type="integer", length = 11)
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
     * @var integer groupId
     *
     * @ORM\Column(name="groupId", type="integer", length = 11) 
     */
    protected $groupId;

    /**
     * @var string name
     *
     * @ORM\Column(name="authLevel", type="string", length = 50) 
     */
    protected $authLevel;


    /**
     * @var string area
     *
     * @ORM\Column(name="area", type="string", length = 50) 
     */
    protected $area;

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
     * getAuthLevel
     *
     * Gets the authLevel property
     * 
     * @return string $authLevel
     */
    public function getAuthLevel()
    {
        return $this->authLevel;
    }

    /**
     * setAuthLevel
     *
     * Sets the authLevel property
     * 
     * @param string authLevel
     */
    public function setAuthLevel($authLevel)
    {
        $this->authLevel = $authLevel;

        return $this;
    }


    /**
     * getArea
     *
     * Gets the area property
     * 
     * @return string $area
     */
    public function getArea()
    {
        return $this->area;
    }

    /**
     * setArea
     *
     * Sets the area property
     * 
     * @param string area
     */
    public function setArea($area)
    {
        $this->area = $area;

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
}