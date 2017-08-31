<?php
namespace Users\Model;

use Phoenix\Module\Model\ModelAbstract;

class Session extends ModelAbstract
{
    protected $sessionEntity;
    protected $eventManager;
    protected $config;
    protected $destroy = false;
    protected $name = '';
    protected $expireCookie = 0;
    protected $sessionVars = array();

    public function __construct($config)
    {
        parent::__construct($config);

        $this->name = $config->get(array('session', 'name'));
        $expireCookie = $config->get(array('session', 'sessionExpire'));

        if ($expireCookie) {
            $this->expireCookie = time() + $expireCookie;
        }
    }

    public function getEntity()
    {
        return $this->sessionEntity;
    }

    public function getSessionId()
    {
        return ($this->sessionEntity instanceof \Users\Entity\Admin\Sessions) ? $this->sessionEntity->getSessId() : false;
    }

    public function getUserId()
    {
        return ($this->sessionEntity instanceof \Users\Entity\Admin\Sessions) ? $this->sessionEntity->getUserId() : 0;
    }

    public function create($id, $ipAddress = '')
    {
        $entityResult = array();

        if ($id) {
            $queryBuilder = $this->getAdminEntityManager()->createQueryBuilder();

            $queryBuilder->select('s')
                ->from('Users\Entity\Admin\Sessions', 's')
                ->where('s.sessId = :sessId')
                ->andWhere('s.expire > :now')
                ->setParameter('sessId', $id)
                ->setParameter('now', new \DateTime());
            
            $entityResult = $queryBuilder->getQuery()->getResult();
        }

        if (empty($entityResult)) {
            $entity = new \Users\Entity\Admin\Sessions();
            $sessId = $this->newId();
            $entity->setSessId($sessId);
            $entity->setCreated(new \DateTime());
        } else {
            $entity = $entityResult[0];
        }

        $expireDate = new \DateTime(date('Y-m-d H:i:s', $this->expireCookie));

        $entity->setExpire($expireDate);
        $entity->setIpAddress($ipAddress);

        $this->sessionEntity = $entity;

        $this->getEventManager()->trigger(\Phoenix\EventManager\Event::EVENT_SESSION_CREATE, '\Phoenix\EventManager\Event', array('sessionModel' => $this));

        $sessionData = unserialize($this->sessionEntity->getData());

        if (!is_array($sessionData)) {
            $sessionData = array();
        }

        $this->sessionVars = array_merge($sessionData, $this->sessionVars);

        $this->sessionEntity->setData(serialize($this->sessionVars));

        $this->getAdminEntityManager()->persist($entity);
        $this->getAdminEntityManager()->flush();        
    }

    public function close($ipAddress = '')
    {
        $adminEm = $this->getAdminEntityManager();

        $this->sessionEntity->setData(serialize($this->sessionVars));

        if ($ipAddress){
            $this->sessionEntity->setIpAddress($ipAddress);
        }

        $this->getAdminEntityManager()->persist($this->sessionEntity);
        $this->getAdminEntityManager()->flush();

        return true;
    }

    public function get($area, $field, $default = false)
    {
        return isset ($this->sessionVars[$area][$field]) ? $this->sessionVars[$area][$field] : $default;
    }

    public function set($area, $field, $value)
    {
        $this->sessionVars[$area][$field] = $value;
    }

    public function getArea($area)
    {
        return isset($this->sessionVars[$area]) ? $this->sessionVars[$area] : false;
    }

    public function destroy()
    {
        $this->destroy = true;            //Used to keep the data from being rewritten.
        setcookie($this->name, 'destroyed', -1, '/');    //First set the cookie to have a traceable, worthless value
        setcookie($this->name, false, -1, '/');    //Then attempt to delete the cookie. Apparently IE doesn't actually delete the cookie straight away.
        unset($_COOKIE[$this->name]);
        $this->getAdminEntityManager()->remove($this->sessionEntity);
        $this->getAdminEntityManager()->flush();
        unset($this->sessionVars);
    }

    public function setCookie()
    {
        if (!setcookie($this->name, $this->getSessionId(), $this->expireCookie, '/')) {
            trigger_error('Failed to set session cookie', E_USER_ERROR);
        }
    }

    protected function newId()
    {
        $sid = '';
        
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';

        $count = mb_strlen($chars);
        
        for ($i = 0; $i < 48; $i++ ) {
            $sid .= $chars[mt_rand(0, $count - 1)];
        }
    
        return $sid;
    }      
}