<?php

/**
 * SeoMetaText ToolboxController
 *
 * The ToolboxController for the SeoMetaText Module
 *
 * If it is a toolbox action for the phoenixAttributes module, it goes here.
 *
 * @category    Toolbox
 * @package     SeoMetaText
 * @subpackage  Controllers
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.4
 * @since       File available since release 13.4
 * @author      Grou <jrubio@travelclick.com>
 * @filesource
 */

namespace SeoMetaText\Controller;

/**
 * SeoMetaText ToolboxController
 *
 * The ToolboxController for the SeoMetaText Module
 *
 * If it is a toolbox action for the phoenixAttributes module, it goes here.
 *
 * This will need to have a way of deciding whether to show all attributes, or just the attribute for the current site
 * depending upon the user.
 *
 * @category    Toolbox
 * @package     SeoMetaText
 * @subpackage  Controllers
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.4
 * @since       Class available since release 13.4
 * @author      Grou <jrubio@travelclick.com>
 */
class ToolboxController extends \ListModule\Controller\ToolboxController
{

    protected $baseAdminLevel = \Users\Service\Acl::PERMISSIONS_RESOURCE_ADMIN;

    public function __construct()
    {

        $this->modsing = 'SeoMetaText';
        parent::__construct();
    }

    public function addItemAction()
    {
        //pass action for filtering file type
        $service = $this->getServiceLocator()->get('phoenix-seometatext');
        //pass action for filtering file type
        $service->_action = 'addItem';
        $viewModel = parent::addItemAction();
        return $viewModel;
    }

    public function edititemAction()
    {
        $service = $this->getServiceLocator()->get('phoenix-seometatext');
        //pass pageId to service
        $pageId = (int) $this->params()->fromRoute('itemId', 0);
        $service->getCurrentPageId($pageId);
        $viewModel = parent::edititemAction();
        return $viewModel;
    }

}
