<?php
/**
 * SocialToolbar Entity File
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

namespace PhoenixSocialToolbar\Entity;
use Phoenix\Module\Entity\EntityAbstract;

use \Doctrine\ORM\Mapping as ORM;

/**
 *
 * @ORM\Table(name="socialToolbar")
 * @ORM\Entity
 */
class PhoenixSocialToolbar extends \ListModule\Entity\Entity
{
    /**
     * @var integer id
     *
     * @ORM\Column(name="socialToolId", type="integer", length=11)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     *
    */

    protected $id;

    /**
     * @var integer toolbarEnabled
     *
     * @ORM\Column(name="toolbar_enabled", type="integer", length=11)
     *
    */

    protected $toolbarEnabled;

    /**
     * @var integer layout
     * 
     * @ORM\Column(name="layout", type="integer", length=11)
     *
    */

    protected $layout;

     /**
      * @var integer color
      * 
      * @ORM\Column(name="color", type="integer", length=11)
      *
     */

    protected $color;

     /**
      * @var integer recommend_email
      * 
      * @ORM\Column(name="recommend_email", type="integer", length=11)
      *
     */

    protected $recommend_email;
      
    /**
     * @var string dataSection
     *
     * @ORM\Column(name="dataSection", type="string", length=255)
    */

    protected $dataSection;

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
    * @var integer showStreamOrFaces
    * 
    * @ORM\Column(name="showStreamOrFaces", type="integer", length=11)
    *
   */

    protected $showStreamOrFaces;

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
     * @var integer iconPreset
     * 
     * @ORM\Column(name="iconPreset", type="integer", length=11)
     *
    */

    protected $iconPreset;


    /**
     * @var integer toolbarLayout
     * 
     * @ORM\Column(name="toolbarLayout", type="integer", length=11)
     *
    */

    protected $toolbarLayout;

    /**
     * @var integer colorScheme
     * 
     * @ORM\Column(name="colorScheme", type="integer", length=11)
     *
    */

    protected $colorScheme;

    /**
     * @var string gradientTop
     *
     * @ORM\Column(name="gradientTop", type="string", length=255)
    */

    protected $gradientTop;

    /**
     * @var string gradientBottom
     *
     * @ORM\Column(name="gradientBottom", type="string", length=255)
    */

    protected $gradientBottom;

    /**
     * @var string layoutFont
     *
     * @ORM\Column(name="layoutFont", type="string", length=255)
    */

    protected $layoutFont;
    
    /**
     * @var string layoutBorders
     *
     * @ORM\Column(name="layoutBorders", type="string", length=255)
    */

    protected $layoutBorders;

    /**
     * @var integer rounded
     * 
     * @ORM\Column(name="rounded", type="integer", length=11)
     *
    */

    protected $rounded;

    /**
     * @var integer extended
     * 
     * @ORM\Column(name="extended", type="integer", length=11)
     *
    */

    protected $extended;

    /**
     * @var integer showLabel
     * 
     * @ORM\Column(name="showLabel", type="integer", length=11)
     *
    */

    protected $showLabel;

    /**
     * @var integer shareTooltip
     * 
     * @ORM\Column(name="shareTooltip", type="integer", length=11)
     *
    */

    protected $shareTooltip;


    /**
     * @var integer onlyIcons
     * 
     * @ORM\Column(name="onlyIcons", type="integer", length=11)
     *
    */

    protected $onlyIcons;

    /**
     * @var string toolbarSize
     *
     * @ORM\Column(name="toolbarSize", type="string", length=255)
    */

    protected $toolbarSize;

     /**
     * @var integer imgTwitter
     * 
     * @ORM\Column(name="imgTwitter", type="integer", length=11)
     *
    */

    protected $imgTwitter;

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
     * @var string imgFacebookTitle
     *
     * @ORM\Column(name="imgFacebookTitle", type="string", length=255)
    */

    protected $imgFacebookTitle;

    /**
     * @var string imgFacebookCaption
     *
     * @ORM\Column(name="imgFacebookCaption", type="string", length=255)
    */

    protected $imgFacebookCaption;

    /**
     * @var string imgFacebookDescription
     *
     * @ORM\Column(name="imgFacebookDescription", type="string", length=255)
    */

    protected $imgFacebookDescription;


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
  
}