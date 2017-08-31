<?php

/**
 * MeidaManagerImages Entity File
 * 
 * @category         Toolbox
 * @package          Mediamanager
 * @subpackages      Entities
 * @copyright        Copyright (c) 2013 TravelClick, Inc (http://travelclick.com)
 * @license          All Rights Reserved
 * @version          Release 1.14
 * @since            1.14
 * @author           Daniel Yang <dyang@travelclick.com>
 * @filesource
 */

namespace MediaManager\Entity;

use \Doctrine\ORM\Mapping as ORM;

/**
 *
 * @ORM\Table(name="mediaManager_images")
 * @ORM\Entity
 */
class MediaManagerImage extends \ListModule\Entity\Entity
{
    /**
     * @var integer id
     * 
     * @ORM\Column(name="imageId", type="integer", length=11)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     *
     */
    protected $id;

    /**
     * @var integer fileId
     * 
     * @ORM\Column(name="fileId", type="integer")
     */
    protected $fileId;
    
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
     * @var datetime modified
     * 
     * @ORM\Column(name="modified", type="datetime")
     */
    protected $modified;

    /**
     * @var datetime created
     * 
     * @ORM\Column(name="created", type="datetime")
     */
    protected $created;

    /**
     * @var integer status
     *
     * @ORM\Column(name="status", type="integer")
     */
    protected $status;

    /**
     * @var text altText
     * 
     * @ORM\Column(name="altText", type="string", length=255)
     */
    protected $altText;

    /**
     * @var integer imageWidth
     *
     * @ORM\Column(name="imageWidth", type="integer")
     */
    protected $imageWidth;

    /**
     * @var integer imageHeight
     *
     * @ORM\Column(name="imageHeight", type="integer")
     */
    protected $imageHeight;

    /**
     * @var integer socialSharing
     *
     * @ORM\Column(name="socialSharing", type="integer")
     */
    protected $socialSharing;

    public function fromArray($data)
    {
        foreach ($data as $key => $value) {
            $method = 'set' . ucfirst($key);
            $this->$method($value);
        }
    }
}
