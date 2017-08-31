<?php
/**
 * SocialToolbarSmallSettings Entity File
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
 * @ORM\Table(name="socialtoolbar_small_settings")
 * @ORM\Entity
 */

 class SocialToolbarSmallSettings extends \ListModule\Entity\Entity
 {
    /**
     * @var integer id
     *
     * @ORM\Column(name="socialSettingsSmallId", type="integer", length=11)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     *
    */

    protected $id;

   /**
    * @var string smFacebook
    *
    * @ORM\Column(name="smFacebook", type="string", length=255)
   */

    protected $smFacebook;

   /**
    * @var integer smFacebookEnabled
    * 
    * @ORM\Column(name="smFacebookEnabled", type="integer", length=11)
    *
   */

    protected $smFacebookEnabled;

   /**
    * @var integer showStream
    * 
    * @ORM\Column(name="showStream", type="integer", length=11)
    *
   */

    protected $showStream;

   /**
    * @var integer showFaces
    * 
    * @ORM\Column(name="showFaces", type="integer", length=11)
    *
   */

    protected $showFaces;

   /**
    * @var string smTwitter
    *
    * @ORM\Column(name="smTwitter", type="string", length=255)
   */

    protected $smTwitter;  


   /**
    * @var integer smTwitterEnabled
    * 
    * @ORM\Column(name="smTwitterEnabled", type="integer", length=11)
    *
   */

    protected $smTwitterEnabled;


   /**
    * @var string smYouTube
    *
    * @ORM\Column(name="smYouTube", type="string", length=255)
   */

    protected $smYouTube;  


   /**
    * @var integer smYouTubeEnabled
    * 
    * @ORM\Column(name="smYouTubeEnabled", type="integer", length=11)
    *
   */

    protected $smYouTubeEnabled; 

    /**
    * @var string smTripAdivsor
    *
    * @ORM\Column(name="smTripAdivsor", type="string", length=255)
   */

    protected $smTripAdivsor;  


   /**
    * @var integer smTripAdivsorEnabled
    * 
    * @ORM\Column(name="smTripAdivsorEnabled", type="integer", length=11)
    *
   */

    protected $smTripAdivsorEnabled; 

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
     * @var integer socialToolId
     *
     * @ORM\Column(name="socialToolId", type="integer", length = 11)
     */
    protected $socialToolId;

    /**
     * @var SocialToobar\Entity\SocialToolbar
     *
     * @ORM\ManyToOne(targetEntity="SocialToolbar\Entity\SocialToolbar", inversedBy="socialToolbar")
     * @ORM\JoinColumns({
     * @ORM\JoinColumn(name="socialToolId", referencedColumnName="socialToolId", onDelete="cascade")
     * })
     */

    protected $socialTool;

 }
