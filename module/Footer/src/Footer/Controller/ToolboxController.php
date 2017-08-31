<?php

/**
 * Footer ToolboxController
 *
 * The ToolboxController for the Footer Module
 *
 * If it is a toolbox action for the phoenixAttributes module, it goes here.
 *
 * @category    Toolbox
 * @package     Footer
 * @subpackage  Controllers
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.4
 * @since       File available since release 13.4
 * @author      Grou <jrubio@travelclick.com>
 * @filesource
 */

namespace Footer\Controller;

/**
 * Footer ToolboxController
 *
 * The ToolboxController for the Footer Module
 *
 * If it is a toolbox action for the phoenixAttributes module, it goes here.
 *
 * This will need to have a way of deciding whether to show all attributes, or just the attribute for the current site
 * depending upon the user.
 *
 * @category    Toolbox
 * @package     Footer
 * @subpackage  Controllers
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.4
 * @since       Class available since release 13.4
 * @author      Grou <jrubio@travelclick.com>
 */
class ToolboxController extends \ListModule\Controller\ToolboxController
{
 protected $tasksMenu = array('additem'=>'Add Footer Navigation','editlist'=>'Manage Footer Navigation');
    protected $baseAdminLevel = \Users\Service\Acl::PERMISSIONS_RESOURCE_ADMIN;

    public function __construct()
    {
        $this->modsing = 'Footer';
        parent::__construct();
    }

    public function addItemAction()
    {
        //pass action for filtering file type
        $service = $this->getServiceLocator()->get('phoenix-footer');
        //pass action for filtering file type
        $service->_action = 'addItem';
        $viewModel = parent::addItemAction();
        return $viewModel;
    }

}
