<?php
namespace ContentApproval\Entity;

use \Doctrine\ORM\Mapping as ORM;
use Zend\Form\Annotation;

/**
 * ContentApprovalSettings
 *
 * @ORM\Table(name="contentApproval_Settings")
 * @ORM\Entity
 */
class ContentApprovalSettings
{
    /**
     * @var integer id
     *
     * @ORM\Column(name="id", type="integer", length = 11)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * 
     */
    protected $id;

    /**
     * @var integer enabled
     *
     * @ORM\Column(name="enabled", type="boolean")
     * @Annotation\Attributes({"type":"checkbox"})
     * @Annotation\Options({"label":"Enabled:"})
     */
    protected $enabled;
    

}