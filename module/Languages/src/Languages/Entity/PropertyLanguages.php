<?php
/**
 * Languages Entity file
 *
 * @category        Toobox
 * @package         Languages
 * @subpackage      Enttites
 * @copyright       Copyright (c) 2013 Travelclick, Inc (http://www.travelclick.com)
 * @license         All Rights Reserved
 * @version         Release: 13.5.5
 * @since           File available since release 13.5.5
 * @author          Jose A. Duarte <jduarte@travelclick.com>
 * @filesource
 */
namespace Languages\Entity;

use \Doctrine\ORM\Mapping as ORM;

 /**
  * @ORM\Table(name="property_languages")
  * @ORM\Entity
  */
class PropertyLanguages extends \ListModule\Entity\Entity
{
    /**
     * @var integer id
     *
     * @ORM\Column(name="plId", type="integer", length=11)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var string languageId
     *
     * @ORM\Column(name="languageId", type="integer", length=11)
     */
    protected $languageId;

    /**
     * @var integer $default
     *
     * @ORM\Column(name="default", type="integer", length=1)
     */
    protected $default;

    
    /**
     * @var PhoenixProperties\Entity\Property $property
     *
     * @ORM\ManyToOne(targetEntity="PhoenixProperties\Entity\PhoenixProperty", inversedBy="propertyLanguages")
     * @ORM\JoinColumn(name="property", referencedColumnName="propertyId")
     */
    protected $property;    
}