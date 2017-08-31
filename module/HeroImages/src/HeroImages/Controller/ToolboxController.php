<?php

/**
 * The HeroImages ToolboxController File
 *
 * @category    Toolbox
 * @package     HeroImages
 * @subpackage  Controller
 * @copyright   Copyright (c) 2014 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.4
 * @since       File available since release 13.4
 * @author      Daniel Yang <dyang@travelclick.com>
 * @filesource
 */

namespace HeroImages\Controller;

use Zend\View\Model\ViewModel;
use HeroImages\Service\HeroImages;

class ToolboxController extends \ListModule\Controller\ToolboxController
{
    protected $editItemTemplate = "phoenix-heroimages/toolbox/edit-item";

    private $hiService = null;

    /**
     * The base admin level for actions. Each action (or task within an action)
     * can require a higher adminLevel, but this is the lowest allowed.
     * 
     * @var string
     */    
    protected $baseAdminLevel = \Users\Service\Acl::PERMISSIONS_RESOURCE_ADMIN;

    public function __construct()
    {
        parent::__construct();
        unset($this->editListOptions['Toggle Re-Ordeer']);
        unset($this->editListOptions['Save Item Ordr']);
    }

    public function addItemAction()
    {
        $viewModel       = parent::addItemAction();
        $this->hiService = $this->getServiceLocator()->get('phoenix-heroimages');

        if ($viewModel instanceof ViewModel) {
            $this->addAttachmentsForHeroImages($viewModel);
        }

        return $viewModel;
    }

    private function addAttachmentsForHeroImages($viewModel)
    {
        $attachedPages = $this->getAttachedPages();

        if (!empty($attachedPages)) {
            $this->addAttachedPagesToForm($viewModel, $attachedPages);
        }
    }

    private function getAttachedPages()
    {
        $attachedPages = $this->hiService->getAttachedPages();
        $itemId        = $this->params()->fromRoute();
        $page          = $this->hiService->getCurrentPage($itemId['itemId']);

        if (!empty($page)) {
            $attachedPages = array_merge(array($page), $attachedPages); // union of arrays
        }

        return $attachedPages;
    }

    private function addAttachedPagesToForm($viewModel, $attachedPages)
    {
        $children       = $viewModel->getChildren();
        $childViewModel = current($children);
        $form           = $childViewModel->form;

        foreach ($form as $valForm) {   
            if ($valForm->getName() == 'pageId') {
                $valForm->setValueOptions($attachedPages);  
            } 
        }   
    }
}
