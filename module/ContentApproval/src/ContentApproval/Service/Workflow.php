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
use ContentApproval\Model\ContentApprovalWorkflow;
use ContentApproval\Entity\ContentApprovalWorkflows;

use Phoenix\Service\ServiceAbstract;

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
class Workflow extends \ListModule\Service\Lists
{
    const ITEM_VERSION_APPROVED = 1;
    const ITEM_VERSION_PENDING = 2;
    const ITEM_VERSION_REJECTED = 3;
    const ITEM_VERSION_HISTORY = 4;

    /**
     * requiresApproval 
     * 
     * @access public
     * @return boolean $requiresApproval 
     *
     */

    protected $entityName;
    protected $modelClass = "ContentApproval\Model\ContentApprovalWorkflow";

    protected $allowCas = false;
    
    public function __construct()
    {
        $this->entityName = "ContentApproval\Entity\ContentApprovalWorkflows";
    }
    
    public function requiresApproval()
    {
        $requiresApproval = $this->config->get(array('contentApproval', 'requiresApproval'), false);

        return ($requiresApproval) ? true : false;
    }
    
       /**
     * getForm
     * 
     * @param  string $formName
     * @param  mixed $sl
     * @return HeroImages\Form\AttachmentForm
     */
    public function getForm($formName, $sl = null)
    {
        $formName = '\ContentApproval\Form\WorkflowForm';

        return parent::getForm($formName, $sl);
    }
    
    public function getUserGroupOptions ()
    {
        //echo "I am in Pages Service's getHotelOption<br/>";
        $options = array();
         //inject default property as Not Assigned
        $options[0] = 'Not Assigned';
        $groups = $this->getAdminEntityManager()->getRepository('\Users\Entity\Admin\Groups')->findAll();

        foreach ($groups as $key => $val) {
            $options[$val->getId()] = $val->getName();
        }
        //var_dump($options);
        return $options;
    }
    /**
     * trash
     * @param  array $items
     * @return void
     */
    public function trash($items, $approved = false)
    {
       foreach($items as $itemId)
       {
           $item = $this->getItem($itemId);
           $item->delete();
       }
    }
}
