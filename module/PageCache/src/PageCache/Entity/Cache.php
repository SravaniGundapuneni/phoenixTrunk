<?php
namespace PageCache\Entity;

use \Doctrine\ORM\Mapping as ORM;
use \Doctrine\Common\Collections\ArrayCollection;

use ListModule\Entity\Entity;

/**
 * Modules
 *
 * @ORM\Table(name="cache")
 * @ORM\Entity
 */
class Cache extends Entity
{
    /**
     * @var integer id
     *
     * @ORM\Column(name="`id`", type="integer", length = 11)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var string device
     *
     * @ORM\Column(name="`device`", type="string", length=35)
     */
    protected $device;

    /**
     * @var string url
     *
     * @ORM\Column(name="`url`", type="string", length=255)
     */
    protected $url;

    /**
     * @var string content
     *
     * @ORM\Column(name="`content`", type="string")
     */
    protected $content;

    /**
     * @var integer $current
     *
     * @ORM\Column(name="`current`", type="integer", length=1)
     */
    protected $current;

    /**
     * @var integer $langCode
     *
     * @ORM\Column(name="`langCode`", type="string", length=2)
     */
    protected $langCode;

    /**
     * @var datetime created
     *
     * @ORM\Column(name="`created`", type="datetime")
     */
    protected $created;
}