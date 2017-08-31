<?php

namespace PageCache;
use \Zend\Mvc\MvcEvent;

class Module extends \Phoenix\Module\Module
{
    protected $moduleNamespace = __NAMESPACE__;
    protected $moduleDirectory = __DIR__;

    protected $exemptParams = array('showErrors',
                              'XDEBUG_PROFILE=1');

    public function onBootstrap(MvcEvent $e)
    {
        /**
         * Module Migrations Support
         */
        $this->updateTableStructures($e);

        $eventManager = $e->getApplication()->getEventManager();
        $eventManager->attach(\Zend\Mvc\MvcEvent::EVENT_ROUTE, array($this, 'displayFromCache'), -1001);
        //$eventManager->attach(\Zend\Mvc\MvcEvent::EVENT_ROUTE, array($this, 'refreshCache'), -10);
        //$eventManager->attach(\Zend\Mvc\MvcEvent::EVENT_ROUTE, array($this, 'refreshCache'), -10);
        $eventManager->attach(\Zend\Mvc\MvcEvent::EVENT_RENDER, array($this, 'setPageCache'), -10000);        
    }

    public function getServiceConfig()
    {
        return array('factories' => array(
            'phoenix-pagecache' => function($sm) {
                $service = new Service\PageCache();
                $service->setDefaultEntityManager($sm->get('doctrine.entitymanager.orm_default'));
                $service->setAdminEntityManager($sm->get('doctrine.entitymanager.orm_admin'));
                $service->setEventManager($sm->get('phoenix-eventmanager'));
                if ($sm->has('MergedConfig')) {
                    $service->setConfig($sm->get('MergedConfig'));
                }
                
                return $service;
            }
        ));
    }

    public function displayFromCache(MvcEvent $e)
    {
        $serviceManager = $e->getApplication()->getServiceManager();

        $response = $e->getResponse();

        if (!$this->useCache($e)) {
            return true;
        }

        $request = $e->getRequest();

        $cacheUrl = $this->getCacheUrl($request);

        $languageCode = $serviceManager->get('currentLanguage')->getCode();

        $siteModel = $serviceManager->get('phoenix-site-model');

        if (is_null($languageCode)) {
            $languageCode = $siteModel->getLanguageCode();            
        }



        $device = $siteModel->getDeviceType();

        $defaultEntityManager = $serviceManager->get('doctrine.entitymanager.orm_default');

        $cachedPage = $defaultEntityManager->getRepository('PageCache\Entity\Cache')->findOneBy(array('url' => $cacheUrl, 'device' => $device, 'langCode' => $languageCode, 'current' => 1));

        if (empty($cachedPage)) {
            return true;
        }

        $response->setContent($cachedPage->getContent());

        $e->stopPropagation(true);

        return $response;
    }


    protected function getCacheUrl($request)
    {
        $uri = $request->getUri();

        $queryString = str_replace("cache=refresh", '', $uri->getQuery());

        $cacheUrl = $uri->getScheme() . '://' . $uri->getHost() . $uri->getpath();

        if (!empty($queryString)) {
            $queryParts = explode('&', $queryString);

            $exemptArray = array_merge(array('cache=refresh'), $this->exemptParams);

            foreach ($queryParts as $keyQuery => $valQuery) {
                if (in_array($valQuery, $exemptArray)) {
                    unset($queryParts[$keyQuery]);         
                }
            }

            if (!empty($queryParts)) {
                $queryString = implode('&', $queryParts);

                $cacheUrl .= '?' . $queryString;
            }
        }
        return $cacheUrl;
    }

    public function refreshCache(MvcEvent $e)
    {
        $serviceManager = $e->getApplication()->getServiceManager();
        $mergedConfig = $serviceManager->get('MergedConfig');

        $refreshCache = $e->getRequest()->getQuery('cache', false);

        if ($mergedConfig->get('disableCache', false) == false && ($mergedConfig->get('development', false) || $mergedConfig->get('release', false)) && $refreshCache == 'refresh') {
            $this->doRefreshCache($serviceManager);
        }
    }

    protected function doRefreshCache($serviceManager)
    {
        $defaultEntityManager = $serviceManager->get('doctrine.entitymanager.orm_default');

        $connection = $defaultEntityManager->getConnection();

        $connection->executeUpdate('TRUNCATE TABLE cache');        
    }

    public function setPageCache(MvcEvent $e)
    {
        $serviceManager = $e->getApplication()->getServiceManager();

        //Don't save cache if the check returns false
        if (!$this->useCache($e)) {
            return true;
        }

        $response = $e->getResponse();

        $request = $e->getRequest();

        $cacheUrl = $this->getCacheUrl($request);

        $languageCode = $serviceManager->get('currentLanguage')->getCode();

        $siteModel = $serviceManager->get('phoenix-site-model');
        
        $device = $siteModel->getDeviceType();

        $defaultEntityManager = $serviceManager->get('doctrine.entitymanager.orm_default');

        $qbDeleteFromCache = $defaultEntityManager->createQueryBuilder();

        $qbDeleteFromCache->delete('PageCache\Entity\Cache', 'c')
                          ->where('c.url = :url')
                          ->andWhere('c.device = :device')
                          ->andWhere('c.langCode = :languageCode')
                          ->setParameter('url', $cacheUrl)
                          ->setParameter('device', $device)
                          ->setParameter('languageCode', $languageCode);
        $qbDeleteFromCache->getQuery()->execute();

        $pageCache = new Entity\Cache();

        $createdDate = new \DateTime();

        $content = $response->getContent() . '<!-- Served From Cache Created on: ' . $createdDate->format('Y-m-d h:i:s') . '-->';

        $pageCache->setDevice($device);
        $pageCache->setUrl($cacheUrl);
        $pageCache->setLangCode($languageCode);
        $pageCache->setContent($content);
        $pageCache->setCurrent(1);
        $pageCache->setCreated($createdDate);

        $defaultEntityManager->persist($pageCache);
        $defaultEntityManager->flush();
    }

    protected function useCache(MvcEvent $e)
    {
        $serviceManager = $e->getApplication()->getServiceManager();
        $currentUser = $serviceManager->get('phoenix-users-current');

        $mergedConfig = $serviceManager->get('MergedConfig');

        if ($mergedConfig->get('disableCache', false)) {
            return false;
        }

        //We absolutely don't want errors getting cached
        if ($e->getError()) {
            return false;
        }
 
        //Do not cache page if user is logged in.
        if ($currentUser->getId()) {
            return false;
        }

        //Sockets should not be cached
        if (strpos($e->getRouteMatch()->getParam('controller'), 'Sockets') !== false) {
            return false;
        }

        //Neither should the login screen
        if ('login' == $e->getRouteMatch()->getParam('action')) {
            return false;
        }

        return $this->checkQuery($e->getRequest()->getUri()->getQuery());
    }

    public function checkQuery($query)
    {
        if (!empty($query)) {
            $queryParts = explode('&', $query);

            foreach ($queryParts as $valQuery) {
                if (!in_array($valQuery, $this->exemptParams)) {
                    return false;
                }
            }
        }

        return true;
    }
}
