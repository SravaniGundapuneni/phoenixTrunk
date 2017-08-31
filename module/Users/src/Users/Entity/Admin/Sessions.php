<?php
namespace Users\Entity\Admin;

use \Doctrine\ORM\Mapping as ORM;

/**
 * Sessions
 *
 * @ORM\Table(name="sessions")
 * @ORM\Entity
 */

class Sessions
{
    /**
     * @var integer id
     *
     * @ORM\Column(name="recordID", type="integer", length = 11)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * 
     */
    protected $id;

    /**
     * @var string sessId
     *
     * @ORM\Column(name="sessId", type="string", length = 64)
     * 
     * 
     */
    protected $sessId;

    /**
     * @var bigint userId
     *
     * @ORM\Column(name="userId", type="bigint", length = 20)
     * 
     * 
     */
    protected $userId;

    /**
     * @var datetime created
     *
     * @ORM\Column(name="created", type="datetime")
     * 
     * 
     */
    protected $created;

    /**
     * @var blob data
     *
     * @ORM\Column(name="data", type="blob")
     * 
     * 
     */
    protected $data;

    /**
     * @var datetime expire
     *
     * @ORM\Column(name="expire", type="datetime")
     * 
     * 
     */
    protected $expire;

    /**
     * @var string ipAddress
     *
     * @ORM\Column(name="ipAddress", type="string", length = 100)
     * 
     * 
     */
    protected $ipAddress;

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
     * getSessId
     *
     * Gets the sessId property
     * 
     * @return string $sessId
     */
    public function getSessId()
    {
        return $this->sessId;
    }

    /**
     * setSessId
     *
     * Sets the sessId property
     * 
     * @param string sessId
     */
    public function setSessId($sessId)
    {
        $this->sessId = $sessId;

        return $this;
    }

    /**
     * getUserId
     *
     * Gets the userId property
     * 
     * @return bigint $userId
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
     * @param bigint userId
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

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
     * getData
     *
     * Gets the data property
     * 
     * @return blob $data
     */
    public function getData()
    {
        return (is_resource($this->data)) ? stream_get_contents($this->data) : $this->data;
    }

    /**
     * setData
     *
     * Sets the data property
     * 
     * @param blob data
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * getExpire
     *
     * Gets the expire property
     * 
     * @return datetime $expire
     */
    public function getExpire()
    {
        return $this->expire;
    }

    /**
     * setExpire
     *
     * Sets the expire property
     * 
     * @param datetime expire
     */
    public function setExpire($expire)
    {
        $this->expire = $expire;

        return $this;
    }

    /**
     * getIpAddress
     *
     * Gets the ipAddress property
     * 
     * @return string $ipAddress
     */
    public function getIpAddress()
    {
        return $this->ipAddress;
    }

    /**
     * setIpAddress
     *
     * Sets the ipAddress property
     * 
     * @param string ipAddress
     */
    public function setIpAddress($ipAddress)
    {
        $this->ipAddress = $ipAddress;

        return $this;
    }
}