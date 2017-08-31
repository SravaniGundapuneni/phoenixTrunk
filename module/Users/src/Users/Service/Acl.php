<?php
/**
 * Acl Service Class
 *
 * The Acl Service for the Users Module. This is used to set up the access control list 
 * for Toolbox, as well as to check if the given user has the proper access rights.
 *
 * @category    Phoenix
 * @package     Users
 * @subpackage  Service
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.4
 * @since       File available since release 13.4
 * @author      A. Tate <atate@travelclick.com>
 * @filesource
 */
namespace Users\Service;

//Phoenix\Service
use Phoenix\Service\ServiceAbstract;

//Zend\Mvc
use Zend\Mvc\MvcEvent;

//Zend\InputFilter
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

//Zend\Acl
use Zend\Permissions\Acl\Acl as ZendAcl;
use Zend\Permissions\Acl\Role\GenericRole as Role;

/**
 * Acl Service Class
 *
 * The Acl Service for the Users Module. This is used to set up the access control list 
 * for Toolbox, as well as to check if the given user has the proper access rights.
 *
 * @category    Phoenix
 * @package     Users
 * @subpackage  Service
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.4
 * @since       File available since release 13.4
 * @author      A. Tate <atate@travelclick.com>
 */
class Acl extends ServiceAbstract
{
    /**
     * ACL Groups
     */
    const PERMISSIONS_GROUP_READ = 'authRead';
    const PERMISSIONS_GROUP_WRITE = 'authWrite';
    const PERMISSIONS_GROUP_APPROVE = 'authApprove';
    const PERMISSIONS_GROUP_PUBLISH = 'authPublish';
    const PERMISSIONS_GROUP_ADMIN = 'authAdmin';
    const PERMISSIONS_GROUP_SUPER_ADMIN = 'authSuperAdmin';
    const PERMISSIONS_GROUP_DEVELOP     = 'authDevelop';

    /**
     * The Default Group, if none is found for the user.
     */
    const DEFAULT_PERMISSIONS_GROUP = self::PERMISSIONS_GROUP_READ;

    /**
     * ACL Permissions
     */
    const PERMISSIONS_RESOURCE_READ = 'canRead';
    const PERMISSIONS_RESOURCE_WRITE = 'canWrite';
    const PERMISSIONS_RESOURCE_APPROVE = 'canApprove';
    const PERMISSIONS_RESOURCE_PUBLISH = 'canPublish';
    const PERMISSIONS_RESOURCE_ADMIN = 'canAdmin';
    const PERMISSIONS_RESOURCE_SUPER_ADMIN = 'canSuperAdmin';
    const PERMISSIONS_RESOURCE_DEVELOP = 'canDevelop';

    /**
     * User type that denotes a SuperAdmin.
     */
    const PERMISSIONS_GROUP_SUPER_ADMIN_TYPE = 1;

    /**
     * GroupID for the authRead group
     */
    const PERMISSIONS_GROUPID_READ = 1;

    protected $authLevels = array(
        self::PERMISSIONS_GROUP_READ => self::PERMISSIONS_RESOURCE_READ,
        self::PERMISSIONS_GROUP_WRITE => self::PERMISSIONS_RESOURCE_WRITE,
        self::PERMISSIONS_GROUP_APPROVE => self::PERMISSIONS_RESOURCE_APPROVE,
        self::PERMISSIONS_GROUP_PUBLISH => self::PERMISSIONS_RESOURCE_PUBLISH,
        self::PERMISSIONS_GROUP_ADMIN => self::PERMISSIONS_RESOURCE_ADMIN,
        self::PERMISSIONS_GROUP_SUPER_ADMIN => self::PERMISSIONS_RESOURCE_SUPER_ADMIN,
        self::PERMISSIONS_GROUP_DEVELOP => self::PERMISSIONS_RESOURCE_DEVELOP
    );

    protected $inheritance = array(
        self::PERMISSIONS_GROUP_WRITE => self::PERMISSIONS_GROUP_READ,
        self::PERMISSIONS_GROUP_APPROVE => self::PERMISSIONS_GROUP_WRITE,
        self::PERMISSIONS_GROUP_PUBLISH => self::PERMISSIONS_GROUP_APPROVE,
        self::PERMISSIONS_GROUP_ADMIN => self::PERMISSIONS_GROUP_PUBLISH,
        self::PERMISSIONS_GROUP_SUPER_ADMIN => self::PERMISSIONS_GROUP_ADMIN,
        self::PERMISSIONS_GROUP_DEVELOP => self::PERMISSIONS_GROUP_SUPER_ADMIN
    );

    /**
     * Holds the ACL list object.
     * @var Zend\Acl\Acl
     */
    protected $acl;

    /**
     * The list of groups retrieved from the database.
     * @var array
     */
    protected $groups = array();

    protected $groupsService = false;

    /**
     * addGroupField
     * 
     * Add's the groupId select form to the given UserForm. 
     * @param \Users\Form\UserForm $userForm The User form to add the field to.
     */
    // public function addGroupField($userForm)
    // {
    //     $groups = $this->getGroups();

    //     //Take the groups array and create the value_options list.
    //     $groupArray = array();
    //     foreach ($groups as $valGroup) {
    //         $groupArray[$valGroup->getId()] = $valGroup->getName();
    //     }

    //     //Add the form field to the UserForm
    //     $userForm->add(array(
    //             'name' => 'groupId',
    //             'class' => 'stdInput', 
    //             'type' => 'Select',
    //             'options' => array(
    //                 'value_options' => $groupArray,
    //                 'label' => 'Permissions Group',
    //                 'label_attributes' => array(
    //                     'class' => 'blockLabel',
    //                 )
    //             )
    //         )
    //     );

    //     //Attach the onUserSave event so our groupId will be saved when the user is saved.
    //     $this->getEventManager()->attach(\Users\EventManager\Event::EVENT_USER_SAVE, array($this, 'onUserSave'), 100);
    //     return $userForm;
    // }

    public function __construct()
    {
        $this->acl = new ZendAcl();        
    }

    public function getAuthLevels()
    {
        return $this->authLevels;
    }

    public function getGroupsService()
    {
        if (!$this->groupsService) {
            $this->groupsService = $this->getServiceManager()->get('phoenix-users-groups');
        }

        return $this->groupsService;
    }

    /**
     * getGroups
     * 
     * Get the list of user groups from the database.
     * @return array Array of User Groups.
     */
    public function getGroups()
    {
        //If the groups property hasn't been set, retrieve them from the DB.
        if (empty($this->groups)) {
            $qbGroups = $this->adminEntityManager->createQueryBuilder();

            $qbGroups->select('g')
                ->from('Users\Entity\Admin\Groups', 'g');

            $this->groups = $qbGroups->getQuery()->getResult();

        }

        return $this->groups;
    }

    /**
     * addGroupInputFilter
     * 
     * Adds the appropriate InputFilter to the given User InputFilter
     * @param \Zend\InputFilter\InputFilter $inputFilter The InputFilter object to add our groupId to.
     */
    // public function addGroupInputFilter($inputFilter)
    // {
    //     //Instantiate the InputFactory
    //     $factory = new InputFactory();

    //     //Add our groupId input filter to the existing InputFilter.
    //     $inputFilter->add($factory->createInput(array(
    //         'name'     => 'groupId',
    //         'required' => true,
    //         'filters'  => array(
    //             array('name' => 'Int'),
    //         ),
    //     )));

    //     //Now let's return the InputFilter object.
    //     return $inputFilter;
    // }

    /**
     * onUserSave
     *
     * Triggered by the userSave Users Event.
     * Saves the groupId to the database
     * 
     * @param  \Users\EventManager\Event $e The Users Event
     * @return void
     */
    public function onUserSave($e)
    {
        //Retrieve the parameters
        // $userData = $e->getParam('userData');
        // $userModel= $e->getParam('userModel');

        // $groupId = $userData['groupId'];

        // //See if we already have a permissions record for this user.
        // $userGroup = $this->getUserPermissions($userModel);
        
        // //If not, create it.
        // if (is_null($userGroup)) {
        //     $userGroup = new \Users\Entity\Permissions();
        //     $userGroup->setUserId($userModel->getUserId());
        //     $userGroup->setCreated(new \DateTime());
        // }        

        // //Set the GroupId and the modified datetime.
        // $userGroup->setGroupId($groupId);
        // $userGroup->setModified(new \DateTime());

        // //Save the permissions record
        // $this->defaultEntityManager->persist($userGroup);
        // $this->defaultEntityManager->flush();
    }

    /**
     * onConfigMerge
     *
     * Triggered by the configMerge Phoenix Event.
     * @param  \Phoenix\EventManager\Event $e The Phoenix Event
     * @return void
     */
    public function onConfigMerge($e)
    {
        //Set the config for this object to the MergedConfig object.
        //Because PHP 5 objects are by reference, all changes made after this trigger is called will
        //still be applied to this object.
        $this->setConfig($e->getParam('configManager')->getMergedConfig());
    }

    /**
     * onLoadCurrentUser
     *
     * Triggered by the loadCurrentUser Users Event.
     * Will set the appropriate users permissions group for the current user.
     * This checks first for a record in the permissions table, then falls back to the users list 
     * retrieved from the Cxml. If not found, it set the current user's permission group to the default.
     * 
     * @param  \Users\EventManager\Event $e The Users Event
     * @return boolean
     */
    public function onLoadCurrentUser($e)
    {
        //Retrieve the userModel from the event's parameters
        $userModel = $e->getParam('userModel');

        // //Superadmin type supercedes all
        // if ($userModel->getUserEntity()->getType() == static::PERMISSIONS_GROUP_SUPER_ADMIN_TYPE) {
        //     $userModel->setPermissionsGroup(static::PERMISSIONS_GROUP_SUPER_ADMIN);
        //     return true;
        // }

        // //Attempt to get the user group
        // $userGroup = $this->getUserGroup($userModel);

        // //Set the Permissions Group to something, no matter what.
        // if (is_null($userGroup)) {
        //     //User permission not found in DB, fall back to user list set in the localCxml
        //     $usersFromConfig = $this->getConfig()->get('usersFromConfig');

        //     if (isset($usersFromConfig[$userModel->getUsername()])) {
        //           //User found. Set the appropriate Permissions Group
        //         $userModel->setPermissionsGroup($usersFromConfig[$userModel->getUsername()]);
        //     } else {
        //         //User Not found. Set to the default permissions group.
        //         $userModel->setPermissionsGroup(static::DEFAULT_PERMISSIONS_GROUP);
        //     }
        // } else {
        //     //User permissions found in the DB. Set the group to the appropriate group.
        //     $userModel->setPermissionsGroup($userGroup->getName());
        // }
        
        $userModel->setPermissionsGroup(static::DEFAULT_PERMISSIONS_GROUP);
        return true;
    }

    /**
     * onGetUser
     *
     * Triggered by the getUser Users Event.
     * Sets the Permissions Entity for the UserModel to the user's group, if applicable.
     * 
     * @param  Users\EventManager\Event $e The Users event.
     * @return void
     */
    public function onGetUser($e)
    {
        $userModel = $e->getParam('userModel');

        $userGroup = $this->getUserGroup($userModel);

        if (!is_null($userGroup)) {
           $userModel->setPermissionsEntity($userGroup);
        }
    }

    /**
     * getGroup
     *
     * Get the group by the groupId
     * 
     * @param  integer $groupId
     * @return \Users\Entity\Admin\Groups $valGroup
     */
    public function getGroup($groupId)
    {
        $groups = $this->getGroups();

        foreach ($groups as $valGroup) {
            if ($groupId == $valGroup->getId()) {
                return $valGroup;
            }
        }

        return false;
    }

    /**
     * getUserPermissions
     * 
     * Get the Users Permissions Entity for the given User
     * @param  Users\Model\User $userModel
     * @return null|Users\Entity\Permissions
     */
    public function getUserPermissions($userModel)
    {
        return null;
        return $this->defaultEntityManager->getRepository('Users\Entity\Permissions')->findOneBy(array('userId' => $userModel->getUserId()));        
    }

    /**
     * getUserGroup
     * 
     * Get the User Group, depending on if they have a record in the db.
     * @param  Users\Model\User $userModel
     * @return Users\Entity\Admin\Groups
     */
    public function getUserGroup($userModel)
    {
        $userGroup = $this->getUserPermissions($userModel);

        if (!is_null($userGroup)) {
            return $this->getGroup($userGroup->getGroupId());
        } 

        return $userGroup;
    }

    /**
     * setupBaseAcl
     *
     * This will setup the base ACL from the Cxml. Username or group name, it does not matter what is set
     * in the CXML. 
     * 
     * @return void
     */
    public function setupBaseAcl()
    {
        $acl = $this->acl;

        //These are base roles. These are not users, nor are they groups. However, this is the easiest
        //way to set up inheritance for our dynamic ACL.
        $roleRead = new Role(static::PERMISSIONS_GROUP_READ);
        $acl->addRole($roleRead);

        $roleWrite = new Role(static::PERMISSIONS_GROUP_WRITE);
        $acl->addRole($roleWrite, static::PERMISSIONS_GROUP_READ);
        $roleApprove = new Role(static::PERMISSIONS_GROUP_APPROVE);
        $acl->addRole($roleApprove, static::PERMISSIONS_GROUP_WRITE);
        $rolePublish = new Role(static::PERMISSIONS_GROUP_PUBLISH);
        $acl->addRole($rolePublish, static::PERMISSIONS_GROUP_APPROVE);
        $roleAdmin = new Role(static::PERMISSIONS_GROUP_ADMIN);
        $acl->addRole($roleAdmin, static::PERMISSIONS_GROUP_PUBLISH);
        $roleSuperAdmin = new Role(static::PERMISSIONS_GROUP_SUPER_ADMIN);
        $acl->addRole($roleSuperAdmin, static::PERMISSIONS_GROUP_ADMIN);
        $roleDevelop = new Role(static::PERMISSIONS_GROUP_DEVELOP);
        $acl->addRole($roleDevelop, static::PERMISSIONS_GROUP_SUPER_ADMIN);

        //Set the Permissions for each group. Because the inheritance was set in the addRole
        //methods above, inheritance is already handled.
        $acl->allow(static::PERMISSIONS_GROUP_READ, null, static::PERMISSIONS_RESOURCE_READ);
        $acl->allow(static::PERMISSIONS_GROUP_WRITE, null, static::PERMISSIONS_RESOURCE_WRITE);
        $acl->allow(static::PERMISSIONS_GROUP_APPROVE, null, static::PERMISSIONS_RESOURCE_APPROVE);
        $acl->allow(static::PERMISSIONS_GROUP_PUBLISH, null, static::PERMISSIONS_RESOURCE_PUBLISH);
        $acl->allow(static::PERMISSIONS_GROUP_ADMIN, null, static::PERMISSIONS_RESOURCE_ADMIN);
        $acl->allow(static::PERMISSIONS_GROUP_SUPER_ADMIN, null, static::PERMISSIONS_RESOURCE_SUPER_ADMIN);
        //Developer has all rights, so no need to set a permission
        $acl->allow(static::PERMISSIONS_GROUP_DEVELOP);

        $usersAndGroups = $this->getConfig()->get('usersAndGroups', array());

        // print_r('<pre>');
        // print_r($usersAndGroups);
        // print_r('</pre>');
        // die('FILE: ' . __FILE__ . ' LINE: ' . __LINE__);

        foreach ($usersAndGroups as $keyLevel => $valLevel) {
            foreach ($valLevel as $userOrGroupName) {
                if (isset($this->authLevels[$keyLevel])) {
                    //This will ensure duplicates don't mess things up.
                    if (!$acl->hasRole($userOrGroupName)) {
                        $newRole = new Role($userOrGroupName);
                        if (isset($this->inheritance[$keyLevel])) {
                            $acl->addRole($userOrGroupName, $this->inheritance[$keyLevel]);
                        } else {
                            $acl->addRole($userOrGroupName);
                        }
                    }
                    $acl->allow($userOrGroupName, null, $this->authLevels[$keyLevel]);
                }
            }
        }

        $this->acl = $acl;
    }

    public function setupUserAcl(MvcEvent $e)
    {
        $serviceManager = $e->getApplication()->getServiceManager();
        $this->groupsService = $serviceManager->get('phoenix-users-groups');
        $currentUser = $serviceManager->get('phoenix-users-current');
        $groups = $this->groupsService->loadFromEntityArray($currentUser->getGroups());

        $dataSection = $this->config->get('dataSection', 'default');
        $rootDataSection = $this->config->get('rootDataSection', 'default');


        foreach ($groups as $valGroup) {
            if (!$this->acl->hasRole($valGroup->getName())) {
                //If role already exists, we don't want to try to add it again.
                $this->acl->addRole($valGroup->getName());
            }

            //This is where permissions for groups will be handled. DB overrides CXML.
            $permissions = $valGroup->getPermissions();

            foreach ($permissions as $valPermission) {
                $inheritedPermissions = $this->getGroupInheritance($valPermission->getAuthLevel());

                foreach ($inheritedPermissions as $valInherited) {
                    if ($valPermission->getName() == 'allSite' && $dataSection == $rootDataSection) {
                        $this->acl->allow($valGroup->getName(), null, $valInherited);
                    }
                }
            }
        }
    }

    public function getGroupInheritance($maxAuthLevel)
    {
        $groupLevels = array($maxAuthLevel);

        $maxAuthLevelRole = array_search($maxAuthLevel, $this->authLevels);

        if ($maxAuthLevelRole) {
            $groupLevels = $this->getLevelInheritance($maxAuthLevelRole, $groupLevels);
        }

        return $groupLevels;
    }

    protected function getLevelInheritance($role, array $authLevels = array())
    {
        if (in_array($role, array_keys($this->inheritance))) {
            $authLevels[] = $this->authLevels[$this->inheritance[$role]];

            if (isset($this->inheritance[$this->inheritance[$role]])) {
                $authLevels = $this->getLevelInheritance($this->inheritance[$role], $authLevels);
            }
        }

        return $authLevels;
    }

    public function hasRole($roleName)
    {
        return $this->acl->hasRole($roleName);
    }

    // protected function getGroupsAndPermissions()
    // {

    //     //Get group (and user) permissions from the MergedConfig
    //     $usersAndGroups = $this->mergedConfig->get('usersAndGroups');

    //     $
    // }

    /**
     * isAllowed
     *
     * Check if the given permission group has the proper rights for the given permission.
     * 
     * @param  string  $role
     * @param  string|null  $permission
     * @param  string  $level
     * @return boolean
     */
    public function isAllowed($role, $permission, $level)
    {
        return $this->acl->isAllowed($role, $permission, $level);
    }

    public function isUserAllowed(\Users\Model\User $user, $permission, $level)
    {
        $userName = $user->getUsername();

        //First, check if the permissions level is Read. If so, return true. Everything should be read accessible.
        if ($level == static::PERMISSIONS_RESOURCE_READ) {
            return true;
        }

        //Next, check if the user is a developer OR a super admin, and the level is not the developer level
        if ($user->isDeveloper() || ($user->isSuperAdmin() && $level != static::PERMISSIONS_RESOURCE_DEVELOP)) {
            return true;
        }

        //Next, check to see if the user's base Access level will suffice
        if ($this->isAllowed($user->getBaseAccessLevel(), $permission, $level)) {
            return true;
        }

        //Next, check to see if the user's permission was set through the CXML.
        if ($this->acl->hasRole($user->getUsername()) && $this->isAllowed($user->getUsername(), $permission, $level)) {
            return true;
        }

        // Now, check to see if the user's groups have sufficient authorization levels.
        $groups = $this->getGroupsService()->loadFromEntityArray($user->getGroups());

        foreach ($groups as $valGroup) {
            if ($this->isAllowed($valGroup->getName(), $permission, $level)) {
                return true;
            }
        }

        //Nope, no permissions found. Return false
        return false;
    }
}