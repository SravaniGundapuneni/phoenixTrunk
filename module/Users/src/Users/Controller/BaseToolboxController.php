<?php

/**
 * The file for the Users ToolboxController
 *
 * @category    Toolbox
 * @package     Users
 * @subpackage  Controller
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.4
 * @since       File available since release 13.4
 * @author      A. Tate <atate@travelclick.com>
 * @filesource
 */

namespace Users\Controller;

use Zend\View\Model\ViewModel;
use Users\Form\UserForm;
//use Users\Form\UserGroupForm;
use Zend\Mvc\MvcEvent;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

/**
 * Users ToolboxController
 *
 * This is the primary controller to use for Toolbox actions for the Users module.
 * There are two other controllers that are used by this module in Toolbox, the GroupsController and the PermissionsController.
 * Anything that involves users themselves (including adding users to or removing them from groups) can be found here.
 *
 * @category    Toolbox
 * @package     Users
 * @subpackage  Controller
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.4
 * @since       File available since release 13.4
 * @author      A. Tate <atate@travelclick.com>
 */
class BaseToolboxController extends \ListModule\Controller\ToolboxController {

    /**
     * The base admin level for actions. Each action (or task within an action)
     * can require a higher adminLevel, but this is the lowest allowed.
     * 
     * @var string
     */
    protected $baseAdminLevel = \Users\Service\Acl::PERMISSIONS_RESOURCE_SUPER_ADMIN;

    protected function checkUserAllowed($acl, $currentUser, $baseAdminLevel) 
    {
        if (!parent::checkUserAllowed($acl, $currentUser, $baseAdminLevel)) {
            $groups = $currentUser->getGroups();
            foreach ($groups as $valGroup) {
                if ($valGroup->getName() == 'manageUsers') {
                    return $acl->isUserAllowed($currentUser, null, \Users\Service\Acl::PERMISSIONS_RESOURCE_ADMIN);
                }
            }

            return false;
        }
        
        return true;
    }    
}
