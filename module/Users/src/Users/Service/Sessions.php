<?php
namespace Users\Service;

use Phoenix\Service\ServiceAbstract;
use Zend\Mvc\MvcEvent;

class Sessions extends ServiceAbstract
{
    protected $cleanUpProb;

    protected $sessionModel;

    public function setCleanUpProb($cleanUpProb)
    {
        $this->cleanUpProb = $cleanUpProb;
    }

    public function start(MvcEvent $e)
    {
        //Skip this if we are heading to legacy. We'll let it handle sessions it's own way for now.
        $logout = $e->getRequest()->getQuery()->get('logout');

        if (!$logout && $e->getParam('isLegacy')) {
            return true;
        }

        $ipAddress = $e->getRequest()->getServer()->get('REMOTE_ADDR');
        $serviceManager = $e->getApplication()->getServiceManager();
        $this->setEventManager($serviceManager->get('phoenix-eventmanager'));
        $user = $serviceManager->get('phoenix-users-current');
        $this->getEventManager()->attach(\Phoenix\EventManager\Event::EVENT_SESSION_CREATE, array($user, 'onSessionCreate'), 100);
        $this->config = $serviceManager->get('MergedConfig');
        $this->setCleanUpProb($this->config->get('session', 'cleanUpProb'));

        $sessionId = $this->exists();
        if ($sessionId) {
            $this->perpetuate($sessionId, $ipAddress);
        }
    }

    public function perpetuate($sessionId = false, $ipAddress = '')
    {
        $rand = rand(0, 100);

        if ($rand < $this->cleanUpProb){
            $this->cleanUp();
        }

        $name = $this->config->get('sessions', 'name');

        if ($sessionId){
            $id = $sessionId;
        } elseif (isset($_COOKIE[$name]) && $_COOKIE[$name] != 'destroyed'){
            $id = $_COOKIE[$name];
        } else {
            $id = false;
        }

        if ($id){
            if (!$this->read($id)){
                $this->create($id, $ipAddress);
            }
        } else {
            $this->create($id, $ipAddress);
        }

    }

    public function create($id, $ipAddress)
    {
        $this->sessionModel = new \Users\Model\Session($this->config);
        $this->sessionModel->setEventManager($this->getEventManager());
        $this->sessionModel->setDefaultEntityManager($this->getDefaultEntityManager());
        $this->sessionModel->setAdminEntityManager($this->getAdminEntityManager());
        $this->sessionModel->create($id, $ipAddress);
    }

    public function setCookie()
    {
        if (!$this->sessionModel instanceof \Users\Model\Session) {
            return false;
        }

        $this->sessionModel->setCookie();
    }

    public function close($ipAddress)
    {
        if ($this->sessionModel instanceof \Users\Model\Session) {
            return $this->sessionModel->close($ipAddress);
        }
    }

    public function exists()
    {
        $sessionName = $this->config->get(array('session', 'name'));

        if (isset($_COOKIE[$sessionName])){
            if ($_COOKIE[$sessionName] == 'destroyed'){
                return false;
            } else {
                return $_COOKIE[$sessionName];
            }
        } elseif ($this->sessionModel && $this->sessionModel instanceof \Users\Model\Session) {
            return $this->sessionModel->getSessionId();
        }

        return false;
    }

    public function isLoggedIn()
    {
        if ($this->read()) {
            if ($this->sessionModel->getUserId() > 0) {
                return true;
            }
        }

        return false;
    }

    public function get($area, $field, $default = false)
    {
        return $this->sessionModel->get($area, $field, $default);
    }

    public function set($area, $field, $value)
    {
        $this->sessionModel->set($area, $field, $value);
    }

    public function getArea($area)
    {
        return $this->sessionModel->getArea($area);
    }

    public function destroy()
    {
        return $this->sessionModel->destroy();
    }

    public function cleanUp()
    {
        $queryBuilder = $this->getAdminEntityManager()->createQueryBuilder();

        $queryBuilder->delete('Users\Entity\Admin\Sessions', 's')
                     ->where('s.expire < :now')
                     ->setParameter('now', new \DateTime());
        $queryBuilder->getQuery()->execute();
    }

    public function read()
    {
        //If Session has already been 
        if ($this->sessionModel instanceof \Users\Model\Session) {
            if ($this->sessionModel->getSessionId()) {
                return true;
            }
        }

        return false;
    }

    public function getSession()
    {
        if ($this->read()) {
            return $this->sessionModel;
        }

        return false;
    }
}