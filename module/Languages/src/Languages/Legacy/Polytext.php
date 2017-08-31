<?php
namespace Polytext\Legacy;

class Polytext extends \Polytext
{
    protected $defaultEntityManager;
    protected $adminEntityManager;

    public function init($langCode)
    {
        $removeLangCode = false;
        //Done for backwards compatibility
        if ($langCode) {
            $_GET['langCode'] = $langCode;
            $removeLangCode = true;
        }
        parent::__construct();
        //Removing this because we shouldn't use REQUEST supervariables that way
        if ($removeLangCode) {
            unset($_GET['langCode']);
        }
    }

    public function setDefaultEntityManager($defaultEntityManager)
    {
        $this->defaultEntityManager = $defaultEntityManager;
    }

    public function getDefaultEntityManager()
    {
        return $this->defaultEntityManager;
    }

    public function setAdminEntityManager($adminEntityManager)
    {
        $this->adminEntityManager = $adminEntityManager;
    }

    public function getAdminEntityManager()
    {
        return $this->adminEntityManager;
    }    
}