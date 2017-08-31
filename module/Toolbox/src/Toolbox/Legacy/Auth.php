<?php
/**
 * Legacy Condor Auth Adapter
 *
 * This is the legacy adapter that corresponds to the condor Auth class.
 *
 *
 * @category    Phoenix
 * @package     Application
 * @subpackage  Legacy
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.4
 * @since       File available since release 13.4
 * @author      Andrew C. Tate <atate@travelclick.com>
 * @filesource
 */
namespace Toolbox\Legacy;

/**
 * Legacy Condor Auth Adapter
 *
 * This is the legacy adapter that corresponds to the condor Auth class.
 * 
 * Unlike Main, this extends from the current class. Piece by piece this will replace
 * the Condor Auth class.
 *
 * @category    Phoenix
 * @package     Application
 * @subpackage  Legacy
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.4
 * @since       File available since release 13.4
 * @author      Andrew C. Tate <atate@travelclick.com>
 */
class Auth extends \Auth
{
    /**
     * Holds the Acl Service Object. Used by the Auth object in a non-static situation
     * @var \Users\Service\Acl
     */
    protected $aclService;

    /**
     * Same thing as $aclService, only for use in a static situation.
     * @var \Users\Service\Acl
     */
    protected static $aclServiceLegacy;

    /**
     * Gets the User Group from the DB
     * Might be @deprecated after I refactor Users Permissions to have subsite/page granularity
     * @var \Users\Entity\Permissions
     */
    protected static $userGroupFromDb;

    /**
     * Holds an instance of this object, to be used during static method calls. 
     * Yeah...This class is a bit weird, I know. This is why we shouldn't use so many static methods.
     * @var Toolbox\Legacy\Auth
     */
    protected static $authObject;

    /**
     * __construct
     * Don't use this as a static call (obviously).
     *
     * The Class Constructor
     */
    public function __construct()
    {
        /** 
         * OK, this looks like it's insane, and it may very well be.
         *
         * However, it makes our lives easier having an instantiated object as the legacy adapter.
         * It gives us something to mock in the Main Legacy Adapter, and begins moving us away from too
         * many static calls.
         * It's probably for the best this isn't done outside the realm of the Legacy Adapters
         * A. Tate 06/14/2013
         */        
        static::$authObject = $this;
    }

    /**
     * setNewDb
     * 
     * Set the NewDb property (The Default EntityManager)
     * Don't use this as a static call.
     * 
     * @param \Doctrine\ORM\EntityManager $newDb
     */
    public function setNewDb(\Doctrine\ORM\EntityManager $newDb)
    {
        $this->newDb = $newDb;        
    }

    /**
     * getNewDb
     * 
     * Get the New DB object
     * Don't use this as a static call.
     * 
     * @return \Doctrine\ORM\EntityManager $newDb
     */
    public function getNewDb()
    {
        return $this->newDb;
    }

    /**
     * setNewAdminDb
     * 
     * Set the NewDb property (The Admin EntityManager)
     * Don't use this as a static call.
     * @param \Doctrine\ORM\EntityManager $newDb
     */
    public function setNewAdminDb(\Doctrine\ORM\EntityManager $newAdminDb)
    {
        $this->newAdminDb = $newAdminDb;        
    }

    /**
     * getNewAdminDb
     * 
     * Get the newAdminDb object
     * Don't use this as a static call.
     * @return \Doctrine\ORM\EntityManager
     */
    public function getNewAdminDb()
    {
        return $this->newAdminDb;
    }

    /**
     * setAclService
     * 
     * Set the AclService and AclServiceLegacy properties
     * Don't use this as a static call.
     * @param \Users\Service\Acl $aclService
     */
    public function setAclService($aclService)
    {
        $this->aclService = $aclService;

        //Yes, I'm aware this is insane. Legacy Adapters with Toolbox have to be insane to work.
        static::$aclServiceLegacy = $aclService;
    }

    /**
     * getAclService
     *
     * Gets the AclService object.
     * Don't use this as a static call
     * 
     * @return \Users\Service\Acl
     */
    public function getAclService()
    {
        return $this->aclService;
    }

    /**
     * getAclServiceLegacy
     *
     * Gets the AclService Object
     * This is OK to call statically
     * 
     * @return \users\Service\Acl
     */
    public static function getAclServiceLegacy()
    {
        return static::$aclServiceLegacy;
    }

    /**
     * canAdmin
     *
     * Checks if the user has the canAdmin permission
     * OK to use statically
     * 
     * @return boolean
     */
    public static function canAdmin()
    {
        return parent::canAdmin();
    }

    /**
     * canUserAdmin
     *
     * non-static version of canAdmin
     * 
     * @return boolean
     */
    public function canUserAdmin()
    {
        return static::canAdmin();
    }

    public static function getUserGroupByUsername($username) 
    {
        return false;
        $authObject = static::$authObject;

        $user = $authObject->getNewAdminDb()->getRepository('Users\Entity\Admin\Users')->findOneBy(array('username' => $username));

        if (is_null($user)) {
            return false;
        }
        $aclServiceLegacy = $authObject->getAclServiceLegacy();

        $userModel = new \Users\Model\User(static::getAclServiceLegacy()->getConfig());
        $userModel->setUserEntity($user);

        return $aclServiceLegacy->getUserGroup($userModel);
    }

    public static function hasAuthLevel($authLevel, $username = false, $localCXML = false)
    {
        $main = m();
        $aclService = static::getAclServiceLegacy();

        $userGroupFromDb = false;

        //Check DB first 
        if (!static::$userGroupFromDb){
            if ($username) {
                $userGroupFromDb = static::getUserGroupByUsername($username);
            } elseif (isset($main->user->username)) {
                $userGroupFromDb = static::getUserGroupByUsername($main->user->username);
            }
        } else {
            $userGroupFromDb = static::$userGroupFromDb;
        }

        if ($userGroupFromDb instanceof \Users\Entity\Admin\Groups) {
            static::$userGroupFromDb = $userGroupFromDb;

            $isAllowed = static::getAclServiceLegacy()->isAllowed($userGroupFromDb->getName(), null, $authLevel);

            if ($isAllowed) {
                return true;
            }
        }

        return parent::hasAuthLevel($authLevel, $username, $localCXML);
    }
}