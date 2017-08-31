<?php
/**
 * File for the FlexibleForm entity class
 *
 *
 * @category    Toolbox
 * @package     FlexibleForm
 * @subpackage  Entity
 * @copyright   Copyright (c) 2014 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: Phoenix First Release 2014
 * @since       File available since release Phoenix First Release 2014
 * @author      A. Tate <atate@travelclick.com>
 * @filesource
 */

namespace FlexibleForms\Entity;

use \Doctrine\ORM\Mapping as ORM;
use \Doctrine\Common\Collections\ArrayCollection;
use ListModule\Entity\Entity;

/**
 * FlexibleForm
 *
 * @ORM\Table(name="flexibleForms_attachments")
 * @ORM\Entity
 */
class FlexibleFormsAttachments extends Entity
{
    /**
     * @var integer id
     *
     * @ORM\Column(name="`attachmentId`", type="integer", length = 11)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;
    
     /**
     * @var string attachmentName
     *
     * @ORM\Column(name="`attachmentName`", type="string")
     */
    protected $attachmentName;
    
         
  /**
     * @var integer fieldId
     *
     * @ORM\Column(name="`fieldId`",  type="integer", length = 11)
     */
    protected $fieldId;   
}