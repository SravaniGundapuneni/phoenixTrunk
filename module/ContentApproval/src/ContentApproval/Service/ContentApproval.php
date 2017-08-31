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
class ContentApproval extends \ListModule\Service\Lists
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
    protected $allowCas = false;
    protected $entityName;
    protected $modelClass = "ContentApproval\Model\ContentApproval";

    public function __construct()
    {
        $this->entityName = "ContentApproval\Entity\ContentApproval";
    }

    public function requiresApproval()
    {
        $requiresApproval = $this->config->get(array('contentApproval', 'requiresApproval'), false);

        return ($requiresApproval) ? true : false;
    }

    public function getApprovalCount($id)
    {
        $em = $this->getDefaultEntityManager();
        $approvalCount = 0;
        $approvals = $em->getRepository('ContentApproval\Entity\ContentApprovalApprovals')->findByItem($id);
        $userGroups = $this->getCurrentUser()->getGroups();
        foreach ($userGroups as $group) {
            foreach ($approvals as $approval) {
                if ($approval->getWorkflow()->getUserGroup() == $group->getId()) {
                    $approvalCount++;
                }
            }
        }
        
        return $approvalCount;
    }
    
    public function rollback($id)
    {
        $em = $this->getDefaultEntityManager();

        $userGroups = $this->getCurrentUser()->getGroups();

        $item = $this->getItem($id);
        
        if ($item->getApproved()==1) {
            
            $itemEntity = $item->getEntity();
            //perform approval action
            $serviceName = $this->getServiceName($item->getItemTable());
            
            $service = $this->sm->get('phoenix-'.$serviceName);

            if ($item->getApprovalAction() == 'save') {
                if ($serviceName == 'attachedmediafiles')
                {
                    $service->removeFile($item->getItemId());
                }
                else
                {
                    $service->delete($item->getItemId());
                }
                
            } 
            elseif (in_array($item->getApprovalAction(), array('publish', 'draft','archive','trash','update'))) {
                $itemModel = $service->getItem($item->getItemId());
                $service->save($itemModel, unserialize($item->getOriginalData()), true, $item->getItemTable());
            }

            $itemEntity->setApproved(2);
            $itemEntity->persist();
            $em->flush($itemEntity);
        }
        
        return $approvalCount;
    }

    public function approve($id)
    {

        $approvalCount = 0;

        $em = $this->getDefaultEntityManager();

        $userGroups = $this->getCurrentUser()->getGroups();

        $item = $this->getItem($id);

        //get approvals relevant to user group
        $approvals = $em->getRepository('ContentApproval\Entity\ContentApprovalApprovals')->findByItem($id);

        foreach ($userGroups as $group) {
            foreach ($approvals as $approval) {
                if ($approval->getWorkflow()->getUserGroup() == $group->getId()) {
                    $approval->setApproved(1);
                    $approval->persist();
                    $em->flush($approval);
                    $approvalCount++;
                }
            }
        }



        $unapproved = $em->getRepository('ContentApproval\Entity\ContentApprovalApprovals')->findBy(array('item' => $id, 'approved' => 0));

        if (count($unapproved) == 0) {

            $itemEntity = $item->getEntity();
            //perform approval action
            $serviceName = $this->getServiceName($item->getItemTable());

            $service = $this->sm->get('phoenix-'.$serviceName);
            try {
                if ($item->getApprovalAction() == 'save') {
                    $itemId = $service->save(null, unserialize($item->getData()), true, $item->getItemTable());
                    $itemEntity->setItemId($itemId);
                }
                elseif ($item->getApprovalAction() == 'update') {
                    $itemModel = $service->getItem($item->getItemId());
                    $service->save($itemModel, unserialize($item->getData()), true, $item->getItemTable());

                }
                elseif ($item->getApprovalAction() == 'publish') {
                    $service->publish(array($item->getItemId()),true);
                }
                elseif ($item->getApprovalAction() == 'draft') {
                    $service->draft(array($item->getItemId()),true);
                }
                elseif ($item->getApprovalAction() == 'archive') {
                    $service->archive(array($item->getItemId()),true);
                }
                elseif ($item->getApprovalAction() == 'trash') {
                    $service->trash(array($item->getItemId()),true);
                }
            } catch (Exception $e) {

            }
            $itemEntity->setApproved(1);
            $itemEntity->persist();
            $em->flush($itemEntity);
        }

        return $approvalCount;
    }

    public function getServiceName($moduleName)
    {
        $em = $this->getDefaultEntityManager();
        $dlms = $em->getRepository('Toolbox\Entity\Components')->findAll();

        foreach ($dlms as $dlm)
        {
            $dlmName = str_replace(' ', '-', $dlm->getName());

            if ( $dlmName == $moduleName && $dlm->getDynamic())
            {
                return 'dynamicmanager';
            }
        }

        return strtolower($moduleName);
    }
    
    public function trash($items, $approved = false)
    {
        $trashable = array();
        foreach ($items as $item)
        {
            if ($this->getApprovalCount($item)>0)
            {
                $trashable[]=$item;
            }
        }
        if (count($trashable)>0)
        {
            return parent::trash($trashable, $approved);
        }
    }
}
