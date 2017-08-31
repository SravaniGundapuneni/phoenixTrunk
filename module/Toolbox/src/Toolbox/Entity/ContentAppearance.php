<?php
namespace Toolbox\Entity;

use \Doctrine\ORM\Mapping as ORM;

/**
 * ContentAppearance
 *
 * @ORM\Table(name="contentAppearance")
 * @ORM\Entity
 */

class ContentAppearance
{
    /**
     * @var integer id
     *
     * @ORM\Column(name="id", type="integer", length = 11)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var datetime created
     *
     * @ORM\Column(name="created", type="datetime")
     * 
     * 
     */
    protected $created;

    /**
     * @var datetime modified
     *
     * @ORM\Column(name="modified", type="datetime")
     * 
     * 
     */
    protected $modified;

    /**
     * @var string contentType
     *
     * @ORM\Column(name="contentType", type="string", length = 255)
     * 
     * 
     */
    protected $contentType;

    /**
     * @var string contentKey
     *
     * @ORM\Column(name="contentKey", type="string", length = 255)
     * 
     * 
     */
    protected $contentKey;

    /**
     * @var string page
     *
     * @ORM\Column(name="page", type="string", length = 255)
     * 
     * 
     */
    protected $page;

    /**
     * @var integer userId
     *
     * @ORM\Column(name="userId", type="integer", length = 11)
     * 
     * 
     */
    protected $userId;

    /**
     * @var string configuration
     *
     * @ORM\Column(name="configuration", type="string")
     * 
     * 
     */
    protected $configuration;

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
     * setModified
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
     * getContentType
     *
     * Gets the contentType property
     * 
     * @return string $contentType
     */
    public function getContentType()
    {
        return $this->contentType;
    }

    /**
     * setContentType
     *
     * Sets the contentType property
     * 
     * @param string contentType
     */
    public function setContentType($contentType)
    {
        $this->contentType = $contentType;

        return $this;
    }

    /**
     * getContentKey
     *
     * Gets the contentKey property
     * 
     * @return string $contentKey
     */
    public function getContentKey()
    {
        return $this->contentKey;
    }

    /**
     * setContentKey
     *
     * Sets the contentKey property
     * 
     * @param string contentKey
     */
    public function setContentKey($contentKey)
    {
        $this->contentKey = $contentKey;

        return $this;
    }

    /**
     * getPage
     *
     * Gets the page property
     * 
     * @return string $page
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * setPage
     *
     * Sets the page property
     * 
     * @param string page
     */
    public function setPage($page)
    {
        $this->page = $page;

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
     * getConfiguration
     *
     * Gets the configuration property
     * 
     * @return string $configuration
     */
    public function getConfiguration()
    {
        return $this->configuration;
    }

    /**
     * setConfiguration
     *
     * Sets the configuration property
     * 
     * @param string configuration
     */
    public function setConfiguration($configuration)
    {
        $this->configuration = $configuration;

        return $this;
    }
}