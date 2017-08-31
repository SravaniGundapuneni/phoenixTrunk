<?php
/**
 * Property Entity file
 *
 * @category        Toolbox
 * @package         HeroImages
 * @subpackage      Entities
 * @copyright       Copyright (c) 2014 TravelClick, Inc (http://www.travelclick.com)
 * @license         All Rights Reserved
 * @version         Release: 13.5
 * @since           File available since release 13.5
 * @author          Daniel Yang <dyang@travelclick.com>
 * @filesource
 */
namespace HeroImages\Entity;

use \Doctrine\ORM\Mapping as ORM;
use \Doctrine\Common\Collections\ArrayCollection;

/**
 * HeroImages
 *
 * Module Tables Config
 *
 *      SchemaHelper::primary('itemId'),
 *      SchemaHelper::varchar('title'),
 *      SchemaHelper::datetime('created'),
 *      SchemaHelper::tinyint('status'),
 *      SchemaHelper::datetime('modified'),
 *      SchemaHelper::int('order'),
 *      SchemaHelper::int('userId'),
 *      SchemaHelper::varchar('url'),
 *      SchemaHelper::int('propertyId'),
 *      SchemaHelper::varchar('module'),
 *      SchemaHelper::varchar('caption'),
 *      SchemaHelper::int('moduleItemId'),
 *      SchemaHelper::varchar('moduleItemTitle')
 *
 * @ORM\Table(name="heroImages_attachments")
 * @ORM\Entity
 */
class Attachments extends \ListModule\Entity\Entity
{
    /**
     * @var integer itemId
     *
     * @ORM\Column(name="`itemId`", type="integer", length = 11)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var string text1
     *
     * @ORM\Column(name="`text1`", type="text")
     */
    protected $text1;
    /**
     * @var string text2
     *
     * @ORM\Column(name="`text2`", type="text")
     */
    protected $text2;
    
    /**
     * @var string text3
     *
     * @ORM\Column(name="`text3`", type="text")
     */
    protected $text3;
    /**
     * @var datetime created
     *
     * @ORM\Column(name="`created`", type="datetime")
     */
    protected $created;

    /**
     * @var integer status
     *
     * @ORM\Column(name="`status`", type="integer", length=11)
     */
    protected $status;
    
    /**
     * @var datetime modified
     *
     * @ORM\Column(name="`modified`", type="datetime")
     */
    protected $modified;
 
    /**
     * @var integer $createdUserId
     *
     * @ORM\Column(name="`createdUserId`", type="integer", length=11)
     */
    protected $createdUserId;
    /**
     * @var integer $modifiedUserId
     *
     * @ORM\Column(name="`modifiedUserId`", type="integer", length=11)
     */
    protected $modifiedUserId;
  
    /**
     * @var string caption
     *
     * @ORM\Column(name="`caption`", type="string", length=255)
     */
    protected $caption;
    
    /**
     * @var string url
     *
     * @ORM\Column(name="`url`", type="string", length=255)
     */
    protected $url;
    
      /**
     * @var integer attachmentId
     *
     * @ORM\Column(name="`attachmentId`", type="integer", length=11)
     */
    protected $attachmentId;

    public function getUrl()
    {
        return $this->url;
    }

    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    public function getCaption()
    {
        return $this->caption;
    }

    public function setCaption($caption)
    {
        $this->caption = $caption;

        return $this;
    }

    public function getAttachmentId()
    {
        return $this->attachmentId;
    }

    public function setAttachmentId($attachmentId)
    {
        $this->attachmentId = $attachmentId;

        return $this;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    public function getText1()
    {
        return $this->text1;
    }

    public function setText1($text1)
    {
        $this->text1 = $text1;

        return $this;
    }

    public function getText2()
    {
        return $this->text2;
    }

    public function setText2($text2)
    {
        $this->text2 = $text2;

        return $this;
    }

    public function getText3()
    {
        return $this->text3;
    }

    public function setText3($text3)
    {
        $this->text3 = $text3;

        return $this;
    }        
}