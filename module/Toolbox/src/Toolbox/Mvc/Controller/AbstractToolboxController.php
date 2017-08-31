<?php
/**
 * Abstract Toolbox Controller
 *
 * The abstract controller class used by other modules as the base for their ToolboxController
 *
 * @category    Toolbox
 * @package     Mvc
 * @subpackage  Controller
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.4
 * @since       File available since release 13.4
 * @author      A. Tate <atate@travelclick.com>
 * @filesource
 */
namespace Toolbox\Mvc\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\MvcEvent;
use Users\Model\User;

/**
 * Abstract Toolbox Controller
 *
 * The abstract controller class used by other modules as the base for their ToolboxController
 *
 * @category    Toolbox
 * @package     Mvc
 * @subpackage  Controller
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.4
 * @since       File available since release 13.4
 * @author      A. Tate <atate@travelclick.com>
 */
class AbstractToolboxController extends AbstractActionController
{
    /**
     * default $baseAdminLevel setting. Each module's ToolboxController should set this to their base admin level.
     * @var string
     */
    protected $baseAdminLevel = '';

    protected $actionDefaultAdminLevel = array();

    public function setBaseAdminLevel($acl)
    {
        if (!$this->baseAdminLevel) {
            $this->baseAdminLevel = $acl::PERMISSIONS_RESOURCE_READ;
        }

        if (!empty($this->actionDefaultAdminLevel)) {
            $action = $this->params()->fromRoute('action');

            if (isset($this->actionDefaultAdminLevel[$action])) {
                $this->baseAdminLevel = $this->actionDefaultAdminLevel[$action];
            }
        }
    }

    /**
     * onDispatch
     * 
     * @param  MvcEvent $e
     * @return mixed
     */
    public function onDispatch(MvcEvent $e)
    {
        //Get the ACL service
        $acl = $this->getServiceLocator()->get('phoenix-users-acl');
        $mergedConfig = $this->getServiceLocator()->get('mergedConfig');
        $this->setBaseAdminLevel($acl);

        //Get the Current User Model
        $currentUser = $this->getServiceLocator()->get('phoenix-users-current');

        //Check to see if the user can use this module.
        //@todo Add ability to approve or deny access on an action by action basis. (Unless this can just be done in the action itself)

        $currentProperty = $this->getServiceLocator()->get('currentProperty');

        if (!$this->checkUserAllowed($acl, $currentUser, $this->baseAdminLevel)) {
            $actionResponse = $this->redirect()->toRoute('home/toolbox-root-noslash');
            $e->setResult($actionResponse);
            return $actionResponse;
        }

        $request = $this->getRequest();

        if (get_class($this) !== 'Pages\Controller\IndexController') {
            $subsiteToolboxPath = $mergedConfig->get('subsiteToolboxPath', '');
            $subsite = $this->params()->fromRoute('subsite', '');

            if ($subsiteToolboxPath && $subsiteToolboxPath != $subsite) {
                $baseUrl = str_replace($subsiteToolboxPath, '', $request->getBaseUrl());
                
                $this->getEvent()->getRouter()->setBaseUrl($baseUrl);

                return $this->redirect()->toUrl(urldecode($this->url()->fromRoute('home/toolbox-root-subsite', array('subsite' => substr($subsiteToolboxPath, 1)))));
            }

            $currentUserId = $currentUser->getId();

            if ($this->getServiceLocator()->has('phoenix-user-login-redirect') && !empty($currentUserId)) {
                if (!$currentUser->isCorporate() && !in_array($currentProperty->getId(), array_keys($currentUser->getUserProperties()))) {
                    $userLoginRedirect = $this->getServiceLocator()->get('phoenix-user-login-redirect');

                    $userLoginRedirect->setUserProperty(current($currentUser->getUserProperties()));
                    $userLoginRedirect->setActive(true);

                    if ($userLoginRedirect->isActive()) {
                        $doRedirect = $userLoginRedirect->doRedirect($this);

                        if ($doRedirect) {
                            return $doRedirect;
                        }
                    }                
                }
            }            
        }

        return parent::onDispatch($e);
    }    

    protected function checkUserAllowed($acl, $currentUser, $baseAdminLevel) 
    {
        return $acl->isUserAllowed($currentUser, null, $this->baseAdminLevel);
    }
}