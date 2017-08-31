<?php
namespace ContentApproval\Entity;

use \Doctrine\ORM\Mapping as ORM;

/**
 * ContentApproval
 *
 * @ORM\Table(name="contentApproval_approvals")
 * @ORM\Entity
 */
class ContentApprovalApprovals extends \ListModule\Entity\Entity
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
     * @ORM\ManyToOne(targetEntity="\ContentApproval\Entity\ContentApprovalWorkflows", inversedBy="approvals", cascade={"persist"})
     * @ORM\JoinColumn(name="workflow", referencedColumnName="id", unique=false, nullable=false)
     */
    protected $workflow;
    
    /**
     * @var integer status
     *
     * @ORM\Column(name="status", type="integer", length = 1)
     * 
     * 
     */
    protected $status;



  /**
     * @ORM\ManyToOne(targetEntity="\ContentApproval\Entity\ContentApproval", inversedBy="approvals", cascade={"persist"})
     * @ORM\JoinColumn(name="item", referencedColumnName="id", unique=false, nullable=false)
     */
    
    protected $item;
    
    /**
     * @var integer approved
     *
     * @ORM\Column(name="approved", type="integer", length = 1)
     * 
     * 
     */
    protected $approved;
}