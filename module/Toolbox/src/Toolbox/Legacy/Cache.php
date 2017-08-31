<?php
namespace Toolbox\Legacy;

class Cache
{
    protected $newDb;
    protected $newAdminDb;
    protected $memcache;

    protected $cacheRefreshLockFile;
    protected $cacheRefreshLockTime = 10;

    public function __construct()
    {
        $this->cacheRefreshLockFile = CONDOR_DYNAMIC_CONTENT_DIR . 'cacheRefreshLock.txt';
    }

    public function setNewDb(\Doctrine\ORM\EntityManager $newDb)
    {
        $this->newDb = $newDb;        
    }

    public function getNewDb()
    {
        return $this->newDb;
    }

    public function setNewAdminDb(\Doctrine\ORM\EntityManager $newAdminDb)
    {
        $this->newAdminDb = $newAdminDb;        
    }

    public function getNewAdminDb()
    {
        return $this->newAdminDb;
    }

    public function setMemcache($memcache)
    {
        $this->memcache = $memcache;
    }

    public function getMemcache()
    {
        return $this->memcache;
    }

    public function refreshCache($dataSection = null, $useMemcache = false)
    {
        trigger_error("Running 'Main::refreshCache' with dataSection = '$dataSection'.", E_USER_NOTICE);

        if (!$this->cacheRefreshLockFile()) {
            return;
        }

        if($useMemcache) {
            $this->getMemcache()->flush();
        }

        $this->clearItibiti();

        $parameters = array();

        if (empty($dataSection)) {
            $sql = 'TRUNCATE TABLE `cache`';
        } else {
            $sql = "DELETE FROM `cache` where dataSection = :dataSection";
            $parameters['dataSection'] = $dataSection;
        }
        $this->newDb->getConnection()->executeUpdate($sql, $parameters);

        trigger_error("Database cached pages invalidated. Cache table has been cleared.", E_USER_NOTICE);

        return true;
    }

    public function setCacheRefreshLockFile($cacheRefreshLockFile)
    {
        $this->cacheRefreshLockFile = $cacheRefreshLockFile;
    }

    public function getCacheRefreshLockFile()
    {
        return $this->cacheRefreshLockFile;
    }

    protected function cacheRefreshLockFile()
    {
        /* To prevent multiple cache refresh being run on the same site simultaneously,
         * we will lock cache refresh by creating a file which holds the timestamp
         * when a cache refresh was issued
         */
        $cacheRefreshLockFile = $this->getCacheRefreshLockFile();

        if (file_exists($cacheRefreshLockFile)) {
            $cacheRefreshLockTimestamp = intval(trim(file_get_contents($cacheRefreshLockFile)));
            if ($cacheRefreshLockTimestamp + $this->cacheRefreshLockTime > time()) {
                trigger_error("Cache refresh aborted (another cache refresh was iniciated recently and might not yet have completed).", E_USER_NOTICE);
                return false; // Do NOT refresh the cache!
            }
        }
        file_put_contents($cacheRefreshLockFile, time());

        return true;   
    }

    protected function clearItibiti()
    {
        //Clear Itibiti bits
        \Itibiti::clearAll();
    }
}