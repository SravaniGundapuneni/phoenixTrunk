<?php
/**
 * ContentApproval Service Class File
 *
 * This file declares the ContentApproval Service, which is the primary service of the ContentApproval
 * Module
 *
 * @category    Toolbox
 * @package     ContentApproval
 * @subpackage  Service
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: PhoenixTng
 * @since       File available since release PhoenixTng
 * @author      A. Tate <atate@travelclick.com>
 * @filesource
 */
namespace ContentApproval\Service;

use Phoenix\Service\ServiceAbstract;
use ContentApproval\Model\ContentApprovalApproval;
use ContentApproval\Entity\ContentApprovalApprovals;

/**
 * ContentApproval Service Class File
 *
 * This file declares the ContentApproval Service, which is the primary service of the ContentApproval
 * Module
 *
 * @category    Toolbox
 * @package     ContentApproval
 * @subpackage  Service
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: PhoenixTng
 * @since       File available since release PhoenixTng
 * @author      A. Tate <atate@travelclick.com>
 */
class Approval extends \ListModule\Service\Lists
{
   

    /**
     * requiresApproval 
     * 
     * @access public
     * @return boolean $requiresApproval 
     *
     */

    protected $allowCas = false;
    
    protected $entityName;
    protected $modelClass = "ContentApproval\Model\ContentApprovalApproval";

    public function __construct()
    {
        $this->entityName = "ContentApproval\Entity\ContentApprovalApprovals";
    }
    
    public function requiresApproval()
    {
        $requiresApproval = $this->config->get(array('contentApproval', 'requiresApproval'), false);

        return ($requiresApproval) ? true : false;
    }
    
}
