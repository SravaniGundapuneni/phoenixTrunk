<?php
namespace ContentApproval\Entity;

use \Doctrine\ORM\Mapping as ORM;

/**
 * ContentApprovalWorkflows
 *
 * @ORM\Table(name="contentApproval_workflow")
 * @ORM\Entity
 */
class ContentApprovalWorkflows extends \ListModule\Entity\Entity
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
     * @ORM\OneToMany(targetEntity="\ContentApproval\Entity\ContentApprovalApprovals", mappedBy="id", cascade={"persist"})
     */
    protected $approvals;
    
    /**
     * @var integer status
     *
     * @ORM\Column(name="status", type="integer", length = 1)
     * 
     * 
     */
    protected $status;

     /**
     * @var string userGroup
     *
     * @ORM\Column(name="userGroup", type="string", length = 255)
     * 
     * 
     */
    protected $userGroup;
    
    public function __construct()
    {
        $this->approvals = new \Doctrine\Common\Collections\ArrayCollection();
    }
}