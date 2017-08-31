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
 * @ORM\Table(name="flexibleForms_depEmails")
 * @ORM\Entity
 */
class FlexibleFormsDepartmentEmails extends Entity
{

    /**
     * @var integer id
     *
     * @ORM\Column(name="`emailId`", type="integer", length = 11)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var string departmentEmail
     *
     * @ORM\Column(name="`departmentEmail`", type="string", length=255)
     */
    protected $departmentEmail;

    /**
     * @var integer property
     *
     * @ORM\Column(name="`property`", type="integer", length=11)
     */
    protected $property;

    /**
     * @var integer departmentId
     *
     * @ORM\Column(name="`departmentId`", type="integer", length=11)
     */
    protected $departmentId;

    /**
     * @var integer $status
     *
     * @ORM\Column(name="`status`", type="integer", length=1)
     */
    protected $status;

}
