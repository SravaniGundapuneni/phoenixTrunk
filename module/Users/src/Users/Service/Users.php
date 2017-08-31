<?php

namespace Users\Service;

use Users\Model\User;
use Users\EventManager\Event as UserEvent;
use Zend\Mail\Message;
use Zend\Mail\Transport\Sendmail as SendmailTransport;

class Users extends \ListModule\Service\Lists {

    protected $cachedUsers = array();
    protected $usersFromConfigKeys = array(
        'authRead',
        'authWrite',
        'authApprove',
        'authAdmin',
        'authPublish',
        'authSuperAdmin'
    );

    public function __construct() {
        $this->entityName = User::ENTITY_NAME;
        $this->modelClass = "\Users\Model\User";
    }

    public function getItem($userId) {
        return $this->getUser($userId);
    }

    public function getUser($userId, $siteOnly = true) {
        if ($this->userIsCached($userId)) {
            return $this->getCachedUser($userId);
        } else {
            $user = $this->createUserModel();
            if ($user->loadById($userId, $siteOnly)) {
                $currentProperty = $this->getServiceManager()->get('currentProperty');
                $corporateProperty = $this->getServiceManager()->get('corporateProperty');

                if ($user->isCorporate()) {
                    $propertyId = 0;
                } else {
                    $propertyId = $currentProperty->getId();
                }

                $userProperty = $this->getDefaultEntityManager()->getRepository('Users\Entity\UserProperty')->findBy(array('userId' => $user->getId(), 'propertyId' => $propertyId));

                if (empty($userProperty)) {
                    $currentAccessLevel = \Users\Service\Acl::PERMISSIONS_RESOURCE_READ;
                } else {
                    $currentAccessLevel = $userProperty[0]->getBaseAccessLevel();
                }

                $user->setBaseAccessLevel($currentAccessLevel);

                $eventResult = $this->getEventManager()->trigger(UserEvent::EVENT_GET_USER, 'Users\EventManager\Event', array('user' => $user));
                $lastEvent = $eventResult->last();

                if (!empty($lastEvent)) {

                    if (!empty($lastEvent) && $lastEvent instanceof User) {
                        $user = $lastEvent;
                    }
                }

                $this->cachedUsers[$userId] = $user;
                return $user;
            }
        }

        return false;
    }

    public function deleteUserGroup($userGroupId, $scope = 'site') {
        if ($scope == 'global') {
            $entityManager = $this->getAdminEntityManager();
            $entity = '\Users\Entity\Admin\UsersGroups';
        } else {
            $entityManager = $this->getDefaultEntityManager();
            $entity = '\Users\Entity\UsersGroups';
        }

        $userGroup = $entityManager->getRepository($entity)->findOneBy(array('id' => $userGroupId));

        $entityManager->remove($userGroup);
        $entityManager->flush();

        return true;
    }

    public function getItems() {
        $acl = $this->getServiceManager()->get('phoenix-users-acl');
        $currentUser = $this->getServiceManager()->get('phoenix-users-current');

        $siteOnly = true;

        if ($acl->isUserAllowed($currentUser, null, 'canSuperAdmin')) {
            $siteOnly = false;
        }

        return $this->getUsers($siteOnly);
    }

    public function getUsers($siteOnly = false) {

        if ($siteOnly) {
            $usersQuery = $this->getUsersQueryForSite();
        } else {
            $usersQuery = $this->getUsersQuery();
        }

        $users = $usersQuery->getQuery()->getResult();

        if (empty($users)) {
            $users = array();
        }

        $siteUsers = array();
        if (!$siteOnly) {
            $siteUsers = $this->getDefaultEntityManager()->getRepository('Users\Entity\Users')->findBy(array(), array('username' => 'ASC'));

            if (empty($siteUsers)) {
                $siteUsers = array();
            }
        }

 
        return array_merge($users, $siteUsers);
    }

    public function checkForLogin($username, $password) {
        //Check to see if this is a global user first.
        $qbLogin = $this->getAdminEntityManager()->createQueryBuilder();

        $password = md5($password);

        $qbLogin->select('u')
                ->from('Users\Entity\Admin\Users', 'u')
                ->where('u.username = :username')
                ->andWhere('u.password = :password')
                ->setParameter('username', $username)
                ->setParameter('password', $password);

        $result = $qbLogin->getQuery()->getResult();

        if (empty($result)) {
            //It is not a global user. Check to see if it is a site user.
            $result = $this->getDefaultEntityManager()->getRepository('Users\Entity\Users')->findBy(array('username' => $username, 'password' => $password));

            if (empty($result)) {
                return false;
            }
        }

        $user = $this->createUserModel();

        //TODO: Completely update the Users module so it doesn't have this obsolete entity structure
        $user->setUserEntity($result[0]);
        $user->setEntity($user->getUserEntity());

        $event = new UserEvent(UserEvent::EVENT_USER_LOGIN);

        $result = $this->getEventManager()->trigger(UserEvent::EVENT_USER_LOGIN, 'Users\EventManager\Event', array('loginUser' => $user));

        if ($result->stopped()) {
            $loginResult = $result->last();

            if (!$loginResult) {
                return false;
            }
        }

        $finalResult = $result->last();

        return ($finalResult instanceof User) ? $finalResult : $user;
    }

    public function setCurrentUserService($user) {
        $this->getServiceManager()->setAllowOverride(true);
        $this->getServiceManager()->setService('phoenix-users-current', $user);
        $this->getServiceManager()->setAllowOverride(false);
    }

    protected function getUsersQuery() {
        $qb = $this->getAdminEntityManager()->createQueryBuilder();

        $qb->select('u')
                ->from('Users\Entity\Admin\Users', 'u')
                ->orderBy('u.username');

        return $qb;
    }

    protected function getUsersQueryForSite() {
        $qb = $this->getDefaultEntityManager()->createQueryBuilder();

        $qb->select('u')
                ->from('Users\Entity\Users', 'u')
                ->orderBy('u.username');

        return $qb;
    }

    protected function userIsCached($userId) {
        return isset($this->cachedUsers[$userId]);
    }

    protected function getCachedUser($userId) {
        return $this->cachedUsers[$userId];
    }

    public function createUserModel($scope = 'site') {
        $user = new \Users\Model\User($this->config);
        $user->setDefaultEntityManager($this->getDefaultEntityManager());
        $user->setAdminEntityManager($this->getAdminEntityManager());
        $user->setScope($scope);

        return $user;
    }

    public function save($userModel, $userData) {
        if (!$userModel instanceof \Users\Model\User) {
            $userModel = $this->createUserModel($scope);
        }

        $userModel->loadFromArray($userData);
        $userModel->save();

        $this->getEventManager()->trigger(\Users\EventManager\Event::EVENT_USER_SAVE, '\Users\EventManager\Event', array('userModel' => $userModel, 'userData' => $userData));
    }

    public function onConfigMerge($e) {
        $configManager = $e->getParam('configManager');
        $usersFromConfig = array(
            'usersFromConfig' => $this->extractUsersFromConfig($configManager->getMergedConfig()->getMergedConfig())
        );
        $mergedConfig = $configManager->getMergedConfig();

        $mergedConfig->set('usersAndGroups', $this->extractUsersFromConfig($mergedConfig->getMergedConfig()));
    }

    public function trash($items) {
        if ($this->getServiceManager()->get('phoenix-users-current')->isSuperAdmin()) {
            $qbDelete = $this->getAdminEntityManager()->createQueryBuilder();
            $qbDelete->delete('Users\Entity\Admin\Users', 'u')
                    ->where($qbDelete->expr()->in('u.id', '?1'))
                    ->setParameter(1, $items);

            $qbDelete->getQuery()->execute();            
        }

        $qbDeleteSiteUsers = $this->getDefaultEntityManager()->createQueryBuilder();
        $qbDeleteSiteUsers->delete('Users\Entity\Users', 'u')
                ->where($qbDeleteSiteUsers->expr()->in('u.id', '?1'))
                ->setParameter(1, $items);

        $qbDeleteSiteUsers->getQuery()->execute();
    }

    protected function extractUsersFromConfig($config) {
        $usersFromConfig = array();
        foreach ($this->usersFromConfigKeys as $valKey) {
            if (isset($config[$valKey]['allow']['user'])) {
                $user = $config[$valKey]['allow']['user'];
                if (is_array($user)) {
                    foreach ($user as $valUser) {
                        $usersFromConfig[$valKey][] = $valUser;
                    }
                } else {
                    $usersFromConfig[$valKey][] = $valUser;
                }
            }
        }

        return $usersFromConfig;
    }

    // ForGot Password
    public function checkUsername($username) {
        $qbLogin = $this->getAdminEntityManager()->createQueryBuilder();

        $qbLogin->select('u')
                ->from('Users\Entity\Admin\Users', 'u')
                ->where('u.username = :username')
                ->setParameter('username', $username);

        $result = $qbLogin->getQuery()->getResult();

        if (empty($result)) {
            return false;
        } else {
            $uId = $result[0]->getId();
            $character_array = array_merge(range(a, z), range(0, 9));
            $random = "";
            for ($i = 0; $i < 25; $i++) {
                $random .= $character_array[rand(0, (count($character_array) - 1))];
            }

            // $random = substr(number_format(time() * rand(), 0, '', ''), 0, 15);

            $qbUpdate = $this->getAdminEntityManager()->createQueryBuilder();
            $qbUpdate->update('Users\Entity\Admin\Users', 'u')
                   
                    ->set('u.resetPassKey', '?1')
                    ->where('u.username = ?2')
                     ->setParameter(1, $random)
                    ->setParameter(2, $username);
            $qbUpdate->getQuery()->execute();

            $message = new Message();
            $message->addTo($result[0]->getemail())
                    ->addFrom('contact@travelclick.com')
                    ->setSubject('Password Reset Link!')
                    ->setBody("Reset Password Link  " . 'http://' . $_SERVER['HTTP_HOST'] . "/toolbox/?uId=$uId&authKey=$random");

            $transport = new SendmailTransport();
            $transport->send($message);
            return $result;
        }
    }

    // Update Password


    public function checkAuthKey($uId, $authKey, $password) {
        $passwordd = md5($password);
        $qbLogin = $this->getAdminEntityManager()->createQueryBuilder();

        $qbLogin->select('u')
                ->from('Users\Entity\Admin\Users', 'u')
                ->where('u.id = :uid')
                ->andWhere('u.resetPassKey = :authKey')
                ->setParameter('uid', $uId)
                ->setParameter('authKey', $authKey);

        $result = $qbLogin->getQuery()->getResult();
        if (empty($result)) {
            return false;
        } else {
            $qbUpdate = $this->getAdminEntityManager()->createQueryBuilder();
            $qbUpdate->
                    update('Users\Entity\Admin\Users', 'u')
                    ->set('u.password', '?1')
                    ->where('u.id = ?2')
                    ->setParameter(1, $passwordd)
                    ->setParameter(2, $uId);


            $qbUpdate->getQuery()->execute();

            return true;
        }
    }

// public function updatePassword($password) {
//        $passwordd = md5($password);
//        
//             $qbUpdate = $this->getAdminEntityManager()->createQueryBuilder();
//             $qbUpdate->update('Users\Entity\Admin\Users', 'u')
//                    ->set('u.password', $passwordd)
//                    ->where('u.id = :uid')
//                    ->setParameter('uid', 117);
//            $qbUpdate->getQuery()->execute();
//
//    
// }
}
