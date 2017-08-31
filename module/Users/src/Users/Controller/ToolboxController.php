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
class ToolboxController extends BaseToolboxController
{

    /**
     * The base admin level for actions. Each action (or task within an action)
     * can require a higher adminLevel, but this is the lowest allowed.
     * 
     * @var string
     */
    protected $baseAdminLevel = \Users\Service\Acl::PERMISSIONS_RESOURCE_SUPER_ADMIN;

    public function __construct()
    {
        $this->modsing = 'User';
        parent::__construct();
    }

    public function getModuleName($moduleName)
    {
        return 'users';
    }

    protected function getTemplateName($moduleName)
    {
        return "users/toolbox/list-users";
    }

    protected function setAdditionalVars($view)
    {
        $serviceLocator = $this->getServiceLocator();
        $subsite = $this->getEvent()->getRouteMatch()->getParam('subsite', '');

        if ($subsite) {
            $subsitePath .= $subsite . '/';
            $virtualBaseArea = substr($subsite . '/' . $virtualBaseArea, 1);
            $toolboxRootRoute = 'home/toolbox-root-subsite';
            $subsiteRoute = substr($subsite, 1);
        } else {
            $subsiteRoute = null;
        }

        $view->optionsLinks = array(
            'password' => $this->url()->fromRoute('users-toolbox', array('subsite' => $subsiteRoute, 'action' => 'changePassword')),
            'groups' => $this->url()->fromRoute('users-toolbox', array('subsite' => $subsiteRoute, 'action' => 'userGroups')),
                //'permissions' => $this->url()->fromRoute('users-toolbox', array('subsite' => $subsite, 'action' => 'userPermissions')),
        );

        $serviceLocator->get('phoenix-eventmanager')->trigger(\Users\EventManager\Event::EVENT_USER_OPTIONS, '\Users\EventManager\Event', array('optionsLinks' => $view->optionsLinks, 'urlHelper' => $this->url(), 'serviceManager' => $serviceLocator));
    }

    /**
     * newuserAction
     * 
     * Add a new user
     * @return ViewModel $viewModel
     */
    public function newUserAction()
    {
        $acl = $this->getServiceLocator()->get('phoenix-users-acl');
        $viewManager = $this->getServiceLocator()->get('view-manager');
        $viewModel = $this->getServiceLocator()->get('users-layout');

        $taskViewModel = new ViewModel();
        $taskViewModel->setTemplate('users/toolbox/new-user');
        $taskViewModel->setVariable('taskTitle', "Add User");
        $taskViewModel->setVariables($viewManager->getViewModel()->getVariables());

        $users = $this->getServiceLocator()->get('phoenix-users');

        $form = new UserForm();

        $subsite = $this->params()->fromRoute('subsite', '');

        if ($subsite) {
            $subsite = substr($subsite, 1);
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $user = $users->createUserModel($this->params()->fromPost('scope', 'site'));
            $form->setInputFilter($user->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {

                $currentProperty = $this->getServiceLocator()->get('currentProperty');
                $corporateProperty = $this->getServiceLocator()->get('corporateProperty');

                $data = $form->getData();

                if (empty($data['isCorporate']) && $currentProperty != $corporateProperty) {
                    $data['propertyId'] = $currentProperty->getId();
                }

                $users->save($user, $data);

                $taskViewModel->userAdded = true;
                //$form = $acl->addGroupField(new UserForm());
                $this->flashMessenger()->addSuccessMessage('User has been saved.');

                $redirectUrl = $this->url()->fromRoute('users-toolbox', array('action' => 'index', 'subsite' => $subsite));
                return $this->redirect()->toUrl($redirectUrl);

                $form = new UserForm();
            } else {
                $this->flashMessenger()->addErrorMessage('There are problems with the form.');
                foreach ($form->getMessages() as $valError) {
                    $this->flashMessenger()->addErrorMessage($valError);
                }
            }
        }

        $currentUser = $this->getServiceLocator()->get('phoenix-users-current');

        $form->setAttribute('action', $this->url()->fromRoute('users-toolbox', array('action' => 'newUser', 'subsite' => $subsite)));

        if (!$currentUser->isDeveloper()) {
            $form->remove('scope');
            $scope = new \Zend\Form\Element\Hidden('scope');
            $scope->setValue('site');
            $form->add($scope);
            $form->remove('type');
            $type = new \Zend\Form\Element\Hidden('type');
            $type->setValue(0);
            $form->add($type);
        }

        $taskViewModel->form = $form;

        $viewModel->addChild($taskViewModel, 'taskContent');
        $viewModel->setVariables($viewManager->getViewModel()->getVariables());
        $viewModel->setVariable('currentTask', str_replace('Action', '', __FUNCTION__));

        return $viewModel;
    }

    /**
     * changeprofileAction
     * 
     * Edit an existing user
     * 
     * @return ViewModel $viewModel
     */
    public function changeprofileAction()
    {
        $itemId = (int) $this->params()->fromRoute('itemId', $this->params()->fromPost('id', 0));

        if (!$itemId) {
            return $this->redirect()->toRoute('users-toolbox', array('action' => 'index'));
        }

        $serviceLocator = $this->getServiceLocator();
        $users = $serviceLocator->get('phoenix-users');
        $acl = $serviceLocator->get('phoenix-users-acl');
        $viewManager = $serviceLocator->get('view-manager');

        $viewModel = $serviceLocator->get('users-layout');
        $viewModel->setVariables($viewManager->getViewModel()->getVariables());

        try {
            $user = $users->getUser($itemId, false);
        } catch (\Exception $ex) {
            return $this->redirect()->toRoute('users-toolbox', array(
                        'action' => 'index'
            ));
        }


        if ($this->getRequest()->isPost() && !$this->params()->fromPost('scope', '')) {
            $this->getRequest()->getPost()->set('scope', $user->getScope());
        }

        $taskViewModel = new ViewModel();
        $taskViewModel->setTemplate('users/toolbox/change-profile');
        $taskViewModel->setVariables($viewManager->getViewModel()->getVariables());
        $taskViewModel->setVariable('taskTitle', 'Change Profile');

        $form = new UserForm();

        $scope = $form->get('scope');

        $scope->setAttributes(array('disabled' => 'disabled'));

        $currentUser = $this->getServiceLocator()->get('phoenix-users-current');

        if (!$currentUser->isDeveloper()) {
            $form->remove('type');
        }

        $subsite = $this->params()->fromRoute('subsite', '');

        if ($subsite) {
            $subsite = substr($subsite, 1);
        }

        $form->setAttribute('action', $this->url()->fromRoute('users-toolbox', array('action' => 'changeProfile', 'subsite' => $subsite, 'itemId' => $itemId)));
        $form->get('username')->setAttributes(array('disabled' => 'disabled'));
        $form->get('givenName')->setLabel('Name');
        $form->remove('passwordConfirm');
        $form->remove('password');
        $form->prepare();


        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($user->getInputFilter());
            $inputFilter = $form->getInputFilter();
            $inputFilter->remove('password');
            $inputFilter->remove('passwordConfirm');
            $inputFilter->remove('username');

            $form->setData($request->getPost());

            if ($form->isValid()) {
                $currentProperty = $this->getServiceLocator()->get('currentProperty');
                $corporateProperty = $this->getServiceLocator()->get('corporateProperty');

                $data = $form->getData();
                if (empty($data['isCorporate']) && $currentProperty != $corporateProperty) {
                    $data['propertyId'] = $currentProperty->getId();
                }
                $users->save($user, $data);
                $viewModel->userEdited = true;
                $redirectUrl = $this->url()->fromRoute('users-toolbox', array('action' => 'index', 'subsite' => $subsite));
                return $this->redirect()->toUrl($redirectUrl);
            }
        } else {
            $form->bind($user);
        }

        $taskViewModel->form = $form;

        $viewModel->addChild($taskViewModel, 'taskContent');

        return $viewModel;
    }

    /**
     * changepasswordAction
     *
     * Change a user's password
     * @return ViewModel $viewModel
     */
    public function changepasswordAction()
    {
        $itemId = (int) $this->params()->fromRoute('itemId', $this->params()->fromPost('id', 0));

        if (!$itemId) {
            return $this->redirect()->toRoute('users-toolbox', array('action' => 'index'));
        }

        $serviceLocator = $this->getServiceLocator();
        $config = $serviceLocator->get('MergedConfig');
        $acl = $serviceLocator->get('phoenix-users-acl');
        $users = $serviceLocator->get('phoenix-users');
        $viewManager = $serviceLocator->get('view-manager');

        $viewModel = $serviceLocator->get('users-layout');
        $viewModel->setVariables($viewManager->getViewModel()->getVariables());

        try {
            $user = $users->getUser($itemId, false);
        } catch (\Exception $ex) {
            return $this->redirect()->toRoute('users-toolbox', array(
                        'action' => 'index'
            ));
        }

        $taskViewModel = new ViewModel();
        $taskViewModel->setTemplate('users/toolbox/change-password');
        $taskViewModel->setVariables($viewManager->getViewModel()->getVariables());
        $taskViewModel->setVariable('taskTitle', 'Change Password');

        $form = new UserForm();
        $form->setAttribute('action', $this->url()->fromRoute('users-toolbox', array('action' => 'changePassword', 'itemId' => $itemId)));
        $form->get('username')->setAttributes(array('disabled' => 'disabled'));
        $form->get('password')->setLabel('New Password');
        $form->remove('givenName');
        $form->remove('isCorporate');
        $form->remove('email');
        $form->remove('scope');
        $form->remove('type');
        $form->remove('baseAccessLevel');
        $form->prepare();

        $request = $this->getRequest();

        if ($request->isPost()) {
            if (!$request->getPost()->get('action')) {
                $redirectUrl = $this->url()->fromRoute('users-toolbox', array('action' => 'index'));
                return $this->redirect()->toUrl($redirectUrl);
            }

            $form->setInputFilter($user->getInputFilter());
            $inputFilter = $form->getInputFilter();
            $inputFilter->remove('username');
            $inputFilter->remove('givenName');
            $inputFilter->remove('email');
            $form->remove('isCorporate');

            $form->setData($request->getPost());

            if ($form->isValid()) {
                $data = $form->getData();
                $data['skipProperties'] = true;

                $users->save($user, $data);
                $viewModel->userEdited = true;
                $redirectUrl = $this->url()->fromRoute('users-toolbox', array('action' => 'index'));
                return $this->redirect()->toUrl($redirectUrl);
            }
        } else {
            $form->bind($user);
        }

        $taskViewModel->form = $form;

        $viewModel->addChild($taskViewModel, 'taskContent');

        return $viewModel;
    }

    /**
     * deleteAction
     *
     * Delete a user
     * 
     * @return redirect
     */
    public function deleteAction()
    {
        $users = $this->getServiceLocator()->get('phoenix-users');
        $itemId = (int) $this->params()->fromRoute('itemId', 0);

        if (!$itemId) {
            return $this->redirect()->toRoute('users-toolbox', array('action' => 'index'));
        }

        try {
            $user = $users->getUser($itemId, false);

            $redirectUrl = $this->url()->fromRoute('users-toolbox', array('action' => 'index'));
            if ($user) {
                $username = $user->getUsername();
                $user->delete();
                $redirectUrl .= '?userDeleted=' . $username;
            }

            return $this->redirect()->toUrl($redirectUrl);
        } catch (\Exception $ex) {
            return $this->redirect()->toRoute('users-toolbox', array(
                        'action' => 'index'
            ));
        }
    }

    /**
     * usergroupsAction
     * 
     * @return ViewModel $viewModel
     */
    public function usergroupsAction()
    {

        $acl = $this->getServiceLocator()->get('phoenix-users-acl');
        $users = $this->getServiceLocator()->get('phoenix-users');
        $viewManager = $this->getServiceLocator()->get('view-manager');
        $viewModel = $this->getServiceLocator()->get('users-layout');
        $viewModel->setVariables($viewManager->getViewModel()->getVariables());
        $itemId = (int) $this->params()->fromRoute('itemId', $this->params()->fromPost('id', 0));

        if (!$itemId) {
            return $this->redirect()->toRoute('users-toolbox', array('action' => 'index'));
        }

        $taskViewModel = new ViewModel();
        $taskViewModel->setTemplate('users/toolbox/user-groups');
        $taskViewModel->setVariables($viewManager->getViewModel()->getVariables());
        $taskViewModel->setVariable('taskTitle', 'User Groups');

        $user = $users->getUser($itemId);
        $taskViewModel->user = $user;

        $viewModel->addChild($taskViewModel, 'taskContent');

        return $viewModel;
    }

    /**
     * useraddgroupAction
     *
     * Add a user to a group.
     * 
     * @return ViewModel $viewModel
     */
    public function useraddgroupAction()
    {
        $itemId = (int) $this->params()->fromRoute('itemId', $this->params()->fromPost('id', 0));

        if (!$itemId) {
            return $this->redirect()->toRoute('users-toolbox', array('action' => 'index'));
        }

        $users = $this->getServiceLocator()->get('phoenix-users');
        $acl = $this->getServiceLocator()->get('phoenix-users-acl');
        $groups = $this->getServiceLocator()->get('phoenix-users-groups');
        $viewManager = $this->getServiceLocator()->get('view-manager');

        $viewModel = $this->getServiceLocator()->get('users-layout');
        $viewModel->setVariables($viewManager->getViewModel()->getVariables());

        $taskViewModel = new ViewModel();
        $taskViewModel->setVariables($viewManager->getViewModel()->getVariables());
        //$taskViewModel->setTemplate('edit-item');

        $taskViewModel->editItemOptions = array(
            'Save' => 'publish',
            'Cancel' => 'cancel',
        );


        $user = $users->getUser($itemId);
        $taskViewModel->user = $user;

        $taskViewModel->title = 'Add group for ' . $user->getUsername();

        $form = new \Users\Form\UserGroupForm;

        $groups->setScope($user->getEntity()->getScope());

        $groupList = $groups->getItems($user->getEntity()->getScope());

        $groupOptions = array();

        foreach ($groupList as $valGroup) {
            $groupOptions[$valGroup->getId()] = $valGroup->getName();
        }

        $userGroups = $user->getGroups();

        foreach ($userGroups as $valUserGroup) {
            unset($groupOptions[$valUserGroup->getId()]);
        }

        $form->add(array(
            'name' => 'groupId',
            'class' => 'stdInput',
            'type' => 'Select',
            'options' => array(
                'value_options' => $groupOptions,
                'label' => 'Group',
                'label_attributes' => array(
                    'class' => 'blockLabel',
                )
            )
        ));

        $inputFilter = new InputFilter();

        $request = $this->getRequest();

        if ($request->isPost()) {
            $form->setInputFilter($inputFilter);
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $data = $form->getData();
                $group = $groups->getItem($data['groupId']);

                $user->addGroup($group);

                $user->save();

                $redirectUrl = $this->url()->fromRoute('users-toolbox', array('action' => 'userGroups', 'itemId' => $itemId));
                return $this->redirect()->toUrl($redirectUrl);
            }
        } else {
            $form->bind($user);
        }

        $taskViewModel->form = $form;
        $taskViewModel->setTemplate('list-module/toolbox/edit-item');

        $viewModel->addChild($taskViewModel, 'taskContent');

        return $viewModel;
    }

    public function deleteusergroupAction()
    {
        $users = $this->getServiceLocator()->get('phoenix-users');
        $itemId = (int) $this->params()->fromRoute('itemId', $this->params()->fromPost('id', 0));

        if (!$itemId) {
            return $this->redirect()->toRoute('users-toolbox', array('action' => 'index'));
        }

        $userId = $this->params()->fromQuery('userId', 0);

        if (!$userId) {
            return $this->redirect()->toRoute('users-toolbox', array('action' => 'index'));
        }

        $users->deleteUserGroup($itemId, $this->params()->fromQuery('scope', 'site'));

        return $this->redirect()->toRoute('users-toolbox', array('action' => 'userGroups', 'itemId' => $userId));
    }

    public function loginAction()
    {

        $users = $this->getServiceLocator()->get('phoenix-users');
        $sessions = $this->getServiceLocator()->get('phoenix-users-sessions');
        $currentUser = $this->getServiceLocator()->get('phoenix-users-current');
        $viewModel = new ViewModel();
        $viewModel->setTerminal(true);

        $viewModel->error = false;
        $subsite = $this->params()->fromRoute('subsite', '');

        $resetPassword = $this->params()->fromQuery('resetPassword');
        $uId = $this->params()->fromQuery('uId');
        $authKey = $this->params()->fromQuery('authKey');
        if ($resetPassword) {
            $viewModel->setTemplate('users/toolbox/forgot-password');
        } else if ($uId && $authKey) {
            $viewModel->setTemplate('users/toolbox/update-password');
        } else {
            $viewModel->setTemplate('users/toolbox/login');
        }
        $viewManager = $this->getServiceLocator()->get('view-manager');
        $viewModel->setVariables($viewManager->getViewModel()->getVariables());
        $viewModel->subsite = $subsite;
        $request = $this->getRequest();

        $actionRoute = 'home/toolbox-root';
        $actionTokens = array();

        if ($subsite) {
            $actionRoute = 'home/toolbox-root-subsite';
            $actionTokens = array('subsite' => substr($subsite, 1));
        }
        $viewModel->actionTokens = $actionTokens;
        $viewModel->actionRoute = $actionRoute;


        if ($request->isPost()) {
            if ($resetPassword) {
                $username = $request->getPost()->get('username', '');
                $password = $request->getPost()->get('password', '');
                if (!empty($username)) {
                    $checkUsername = $users->checkUsername($username);
                    if ($checkUsername) {

                        $viewModel->errorMessage = 'An activation link is sent to your email';
                        $viewModel->error = true;
                    } else {
                        $viewModel->errorMessage = 'Password not found in Records';
                        $viewModel->error = true;
                    }
                } else {
                    $viewModel->errorMessage = 'Please enter your Username';
                    $viewModel->error = true;
                }
            } else if ($uId && $authKey) {
                $password = $request->getPost()->get('password', '');
                $confirm_password = $request->getPost()->get('confirm_password', '');

                if ($password == $confirm_password) {
                    $checkUsername = $users->checkAuthKey($uId, $authKey, $password);
                    if ($checkUsername) {

                        $viewModel->errorMessage = 'Password has been updated. Please Login with the updated password';
                        $viewModel->error = true;
                    } else {
                        $viewModel->errorMessage = "Invalid Link";
                        $viewModel->error = true;
                    }
                } else {
                    $viewModel->errorMessage = 'Please enter same password';
                    $viewModel->error = true;
                }
            } else {

                $username = $request->getPost()->get('username', '');
                $password = $request->getPost()->get('password', '');

                if ($username) {
                    $loggedInUser = $users->checkForLogin($username, $password);

                    if ($loggedInUser) {
                        if (get_class($loggedInUser) !== get_class($currentUser)) {
                            $currentUser = $loggedInUser;
                            $users->setCurrentUserService($currentUser);
                        } else {
                            $currentUser->setUserEntity($loggedInUser->getUserEntity());
                        }

                        $ipAddress = $request->getServer()->get('REMOTE_ADDR');

                        $sessions->create('', $ipAddress);
                        $sessions->setCookie();

                        if ($this->getServiceLocator()->has('phoenix-user-login-redirect')) {
                            $userLoginRedirect = $this->getServiceLocator()->get('phoenix-user-login-redirect');

                            if ($userLoginRedirect->isActive()) {
                                $doRedirect = $userLoginRedirect->doRedirect($this);

                                if ($doRedirect) {
                                    return $doRedirect;
                                }
                            } else {
                                return $this->redirect()->toRoute($actionRoute, $actionTokens);
                            }
                        } else {
                            return $this->redirect()->toRoute($actionRoute, $actionTokens);
                        }
                    }
                }
                $viewModel->errorMessage = 'Sorry, the username or password is invalid.';
                $viewModel->error = true;
            }
        }
        $renderer = $this->getServiceLocator()->get('Zend\View\Renderer\PhpRenderer');

        $renderer->headLink()->appendStylesheet("http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css", 'text/stylesheet');
        $renderer->headLink()->appendStylesheet($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/css/foundation.css');
        $renderer->headLink()->appendStylesheet($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/css/jquery.gridmanager.css');
        $renderer->headLink()->appendStylesheet($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/css/demo.css');
        $renderer->headLink()->appendStylesheet($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/css/template-builder.css');

        $renderer->headLink()->appendStylesheet("http://code.jquery.com/ui/1.11.0/themes/smoothness/jquery-ui.css", 'text/stylesheet');
        $renderer->headLink()->appendStylesheet("http://cdnjs.cloudflare.com/ajax/libs/prettify/r224/prettify.css", 'text/stylesheet');

        $renderer->headLink()->appendStylesheet($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/css/vendor/ui/example.css');
        $renderer->headLink()->appendStylesheet($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/css/vendor/ui/foundation-datepicker.css');
        $renderer->headLink()->appendStylesheet($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/css/vendor/ui/jquery.classygradient.css');
        $renderer->headLink()->appendStylesheet($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/css/vendor/ui/jquery.colorpicker.css');
        $renderer->headLink()->appendStylesheet($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/css/vendor/ui/layout.css');
        $renderer->headLink()->appendStylesheet($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/css/vendor/ui/simple-slider.css');
        $renderer->headLink()->appendStylesheet($viewModel->toolboxIncludeUrl . 'module/TemplateBuilder/view/template-builder/toolbox/css/template-builder.css');

        $renderer->HeadScript()->appendFile('http://code.jquery.com/jquery.min.js', 'text/javascript');
        $renderer->HeadScript()->appendFile('http://code.jquery.com/ui/1.11.1/jquery-ui.js', 'text/javascript');
        $renderer->HeadScript()->appendFile($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/js/vendor/modernizr.js', 'text/javascript');
        $renderer->HeadScript()->appendFile($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/js/vendor/fastclick.js', 'text/javascript');

        $renderer->HeadScript()->appendFile($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/js/foundation/foundation.js', 'text/javascript');
        $renderer->HeadScript()->appendFile($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/js/jquery.gridmanager.js', 'text/javascript');
        $renderer->HeadScript()->appendFile($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/js/foundation/foundation.dropdown.js', 'text/javascript');
        $renderer->HeadScript()->appendFile($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/js/foundation/foundation.topbar.js', 'text/javascript');
        $renderer->HeadScript()->appendFile($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/js/foundation/foundation.tab.js', 'text/javascript');
        $renderer->HeadScript()->appendFile($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/js/foundation/foundation.reveal.js', 'text/javascript');
        $renderer->HeadScript()->appendFile($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/js/vendor/ui/jquery.jeditable.js', 'text/javascript');
        $renderer->HeadScript()->appendFile($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/js/vendor/ui/excanvas.js', 'text/javascript');
        $renderer->HeadScript()->appendFile($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/js/vendor/ui/foundation-datepicker.js', 'text/javascript');
        $renderer->HeadScript()->appendFile($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/js/vendor/ui/jquery.knob.js', 'text/javascript');
        $renderer->HeadScript()->appendFile($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/js/vendor/ui/simple-slider.min.js', 'text/javascript');
        $renderer->HeadScript()->appendFile($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/js/template-header.js', 'text/javascript');

        return $viewModel;
    }

    /**
     * onDispatch
     * 
     * @param  MvcEvent $e
     * @return mixed
     */
    public function onDispatch(MvcEvent $e)
    {
        $routeMatch = $e->getRouteMatch();

        if ($routeMatch->getParam('controller') == 'Users\Controller\Toolbox' && $routeMatch->getParam('action') == 'login') {
            $this->baseAdminLevel = \Users\Service\Acl::PERMISSIONS_RESOURCE_READ;
        }

        return parent::onDispatch($e);
    }

}
