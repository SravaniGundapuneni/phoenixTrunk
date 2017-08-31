<?php
/**
 * SocialToolbarButtonSettings Entity File
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
 * @ORM\Table(name="socialtoolbar_share_buttons_settings")
 * @ORM\Entity
 */

class SocialToolbarButtonSettings extends \ListModule\Entity\Entity
{
    /**
     * @var integer id
     *
     * @ORM\Column(name="socialToolShareButtonId", type="integer", length=11)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     *
    */

    protected $id;

    /**
     * @var integer butTwitter
     * 
     * @ORM\Column(name="butTwitter", type="integer", length=11)
     *
    */

    protected $butTwitter;


    /**
     * @var integer butFacebook
     * 
     * @ORM\Column(name="butFacebook", type="integer", length=11)
     *
    */

    protected $butFacebook;

    /**
     * @var integer butPinterest
     * 
     * @ORM\Column(name="butPinterest", type="integer", length=11)
     *
    */

    protected $butPinterest;

    /**
     * @var integer butGoogle
     * 
     * @ORM\Column(name="butGoogle", type="integer", length=11)
     *
    */

    protected $butGoogle;

    /**
     * @var integer butEmail
     * 
     * @ORM\Column(name="butEmail", type="integer", length=11)
     *
    */

    protected $butEmail; 
    
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
     * @ORM\JoinColumn(name="socialToolShareButtonId", referencedColumnName="socialToolShareButtonId", onDelete="cascade")
     * })
     */
    protected $socialToolbar;   


}
 