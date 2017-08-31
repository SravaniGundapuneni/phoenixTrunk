<?php
namespace ContentApproval\Entity;

use \Doctrine\ORM\Mapping as ORM;

/**
 * ContentApproval
 *
 * @ORM\Table(name="contentApproval")
 * @ORM\Entity
 */
class ContentApproval extends \ListModule\Entity\Entity
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
     * @var datetime created
     *
     * @ORM\Column(name="created", type="datetime")
     * 
     * 
     */
    protected $created;

    /**
     * @var datetime modified
     *
     * @ORM\Column(name="modified", type="datetime")
     * 
     * 
     */
    protected $modified;

    /**
     * @var string itemTable
     *
     * @ORM\Column(name="itemTable", type="string", length = 255)
     * 
     * 
     */
    protected $itemTable;

    /**
     * @var string itemId
     *
     * @ORM\Column(name="itemId", type="string", length = 255)
     * 
     * 
     */
    protected $itemId;

    /**
     * @var string approvalAction
     *
     * @ORM\Column(name="approvalAction", type="string", length = 255)
     * 
     * 
     */
    protected $approvalAction;

        /**
     * @var text data
     *
     * @ORM\Column(name="data", type="text")
     * 
     * 
     */
    protected $data;
            /**
     * @var text originalData
     *
     * @ORM\Column(name="originalData", type="text")
     * 
     * 
     */
    protected $originalData;
    /**
     * @var integer status
     *
     * @ORM\Column(name="status", type="integer", length = 1)
     * 
     * 
     */
    protected $status;

    /**
     * @ORM\OneToMany(targetEntity="\ContentApproval\Entity\ContentApprovalApprovals", mappedBy="item", cascade={"persist"})
     */
    protected $approvals;
    
    /**
     * @var integer $createdUserId
     *
     * @ORM\Column(name="`createdUserId`", type="integer", length=11)
     */
    protected $createdUserId;
    /**
     * @var integer $modifiedUserId
     *
     * @ORM\Column(name="`modifiedUserId`", type="integer", length=11)
     */
    protected $modifiedUserId;
    
        /**
     * @var integer approved
     *
     * @ORM\Column(name="approved", type="integer", length = 1)
     * 
     * 
     */
    protected $approved;
    
    public function __construct()
    {
        $this->approvals = new \Doctrine\Common\Collections\ArrayCollection();
    }
}