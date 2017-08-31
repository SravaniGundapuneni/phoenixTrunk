<?php
/**
 * SocialToolbarLayoutSettings Entity File
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
 * @ORM\Table(name="socialtoolbar_layout_settings")
 * @ORM\Entity
 */

 class SocialToolbarLayoutSettings extends \ListModule\Entity\Entity
 {

     /**
     * @var integer id
     *
     * @ORM\Column(name="socialLayoutSettingsId", type="integer", length=11)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     *
    */

    protected $id;

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
     * @ORM\ManyToOne(targetEntity="SocialToolbar\Entity\SocialToolbar", inversedBy="socialToolbarLayoutSettings")
     * @ORM\JoinColumns({
     * @ORM\JoinColumn(name="socialLayoutSettingsId", referencedColumnName="socialLayoutSettingsId", onDelete="cascade")
     * })
     */
    protected $socialToolbar;


 }