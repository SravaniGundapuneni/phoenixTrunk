<?php
/**
 * File for the DynamicListModules entity class
 *
 *
 * @category    Toolbox
 * @package     DynamicListModules
 * @subpackage  Entity
 * @copyright   Copyright (c) 2014 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: Phoenix First Release 2014
 * @since       File available since release Phoenix First Release 2014
 * @author      A. Tate <atate@travelclick.com>
 * @filesource
 */

namespace DynamicListModule\Entity;

use \Doctrine\ORM\Mapping as ORM;
use \Doctrine\Common\Collections\ArrayCollection;
use ListModule\Entity\Entity;

/**
 * DynamicListModules
 *
 * @ORM\Table(name="dynamicListModule_selectValues")
 * @ORM\Entity
 */
class DynamicListModuleSelectValues extends Entity
{
    /**
     * @var integer id
     *
     * @ORM\Column(name="`valId`", type="integer", length = 11)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;
    
     /**
     * @var string name
     *
     * @ORM\Column(name="`name`", type="string")
     */
    protected $name;
    
         
  /**
     * @var integer field
     *
     * @ORM\Column(name="`field`",  type="integer", length = 11)
     */
    protected $field;
    

    
}