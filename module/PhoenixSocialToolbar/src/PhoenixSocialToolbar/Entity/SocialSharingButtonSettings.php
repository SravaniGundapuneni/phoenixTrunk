<?php
/**
 * SocialSharingButtonSettings Entity File
 * 
 * @category      Toolbox
 * @package       SocialToolbar
 * @subpackage    Entities
 * @copyright     Copyright (c) 2013 TravelClick, Inc (http://travelclick.com)
 * @license       All Rights Reserved
 * @version       Release 13.5
 * @since         File available since release 13.5
 * @author        Kevin Davis <kedavis@travelclick.com>
 * @filename
 */

namespace SocialToolbar\Entity;
use \Doctrine\ORM\Mapping as ORM;

/**
 *
 * @ORM\Table(name="socialtoolbar_image_share_buttons_settings")
 * @ORM\Entity
 */

class SocialSharingButtonSettings extends \ListModule\Entity\Entity
{
    /**
     * @var integer id
     *
     * @ORM\Column(name="socialImageShareButtonId", type="integer", length=11)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     *
    */

    protected $id;

    /**
     * @var integer imgTwitter
     * 
     * @ORM\Column(name="imgTwitter", type="integer", length=11)
     *
    */

    protected $imgTwitter;

    /**
     * @var integer imgPinterest
     * 
     * @ORM\Column(name="imgPinterest", type="integer", length=11)
     *
    */

    protected $imgPinterest;

    /**
     * @var integer imgGoogle
     * 
     * @ORM\Column(name="imgGoogle", type="integer", length=11)
     *
    */

    protected $imgGoogle;

    /**
     * @var integer imgFacebook
     * 
     * @ORM\Column(name="imgFacebook", type="integer", length=11)
     *
    */

    protected $imgFacebook;

    /**
     * @var datetime created
     *
     * @ORM\Column(name="created", type="datetime")
    */

    protected $created;

     /**
      * @var datetime modified
      *
      * @ORM\Column(name="modified", type="datetime")
     */
     
    protected $modified;

    /**
     * @var SoicalToobar\Entity\SocialToolbar
     *
     * @ORM\ManyToOne(targetEntity="SocialToolbar\Entity\SocialToolbar", inversedBy="socialToolbarButtonsSettings")
     * @ORM\JoinColumns({
     * @ORM\JoinColumn(name="socialImageShareButtonId", referencedColumnName="socialImageShareButtonId", onDelete="cascade")
     * })
     */
    protected $socialToolbar;   
}