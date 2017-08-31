<?php
/**
 * Page Router for Toolbox. 
 *
 * This will look and see if we have a page (right now a config array, 
 * but will eventually add the ability to check for a page in the db). Unless the page has a module set in
 * its config, the page will then be routed to the Page controller, which will handle processing collections, blocks,
 * etc... for the page.
 *
 * If no page config is found, will return null.
 * 
 * @category    Phoenix
 * @package     Mvc
 * @subpackage  Router
 * @copyright   Copyright (c) 2013 TravelClick
 * @license     All Rights Reserved
 * @version     Release: TBD
 * @since       File available since release TBD
 * @author      Andrew C. Tate <atate@travelclick.com>
 * @filesource
 */
namespace Pages\Mvc\Router\Http;

use \Zend\Mvc\Router\Http\Segment as ZendSegment;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

use Zend\Stdlib\RequestInterface as Request;

class Segment extends ZendSegment implements ServiceLocatorAwareInterface
{
    protected $routePluginManager = null;    

    protected $configManaqger;

    protected $modulesConfig;

    /**
     * Set service locator
     *
     * @param ServiceLocatorInterface $serviceLocator
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->routePluginManager = $serviceLocator;
    }

    public function match(Request $request, $pathOffset = null)
    {
        $routeMatch = parent::match($request, $pathOffset);

        if (empty($routeMatch)) 
        {
            return null;            
        }

        //We don't want page routing to mess with toolbox
        if ($routeMatch->getParam('subsite', '') == 'toolbox') {
            return null;
        }


        $siteModel = $this->routePluginManager->getServiceLocator()->get('phoenix-site-model');

        $subsite = $siteModel->getSubsite();

        $routeMatch->setParam('subsite', $subsite);

        $page = $routeMatch->getParam('page', '');

        if (!$page) {
            return null;
        }

        $pageParts = explode('-', $page);

        if (count($pageParts) > 1) {
            $langCode = end($pageParts);

            if (strlen($langCode) == 2) {
                //$routeMatch->setParam('langCode', $langCode);
                $siteModel->setLanguageCode($langCode);
                array_pop($pageParts);
            }

            $newPage = implode('-', $pageParts);
            $routeMatch->setParam('page', $newPage);
        } else {
            $newPage = $page;
        }

        $serviceManager = $this->routePluginManager->getServiceLocator();

        $pagesService = $this->routePluginManager->getServiceLocator()->get('phoenix-pages');
        $this->configManager = $this->routePluginManager->getServiceLocator()->get('phoenix-configmanager');

        $this->configManager = $serviceManager->get('phoenix-configmanager');
        $this->modulesConfig = $this->configManager->getRawConfig('modulesConfig');

        $moduleConfig = new \Config\Model\MergedConfig($this->modulesConfig);

        $defaultAlias = $moduleConfig->get('default-alias', '');

        if ($page == $defaultAlias) {
            $newPage = 'default';
            $routeMatch->setParam('page', $newPage);
        }
        //Blanket refusal of all pages named index. We don't want what this request is cooking.
        if ($newPage == 'index') {
            return null;
        }

        $pagesService->setConfig($moduleConfig);

        $page = $pagesService->loadPage($newPage, $subsite);

        if (!is_null($page)) {
            $this->routePluginManager->getServiceLocator()->setAllowOverride(true);
            $this->routePluginManager->getServiceLocator()->setService('currentPage', $page);
            $this->routePluginManager->getServiceLocator()->setAllowOverride(false);
            $config = $this->mergeConfigs($page);

            return $routeMatch;
        }

        return null;
    }

    /**
     * Get service locator
     *
     * @return ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->routePluginManager;
    }

    protected function mergeConfigs($page)
    {
        $config = $this->mergeTemplateConfig($page);

        if ($page->getPageConfig() instanceof \Config\Model\MergedConfig) {        
            $config = $this->configManager->mergeModuleConfigs($config, $page->getPageConfig()->getMergedConfig());
        }

        $this->configManager->saveConfig('modulesConfig', $config);        
    }

    protected function mergeTemplateConfig($page)
    {
        $template = $page->getTemplate();

        $config = $this->modulesConfig;

        if (!empty($template)) {
            $configFile = str_replace('.phtml', '.php', $template);
            $filePath = SITE_PATH . "/templates/main/config/$configFile";
            
            if (file_exists($filePath)) {
                $templateConfig = include $filePath;

                $config = $this->configManager->mergeModuleConfigs($config, $templateConfig);                
            }            
        }

        return $config;
    }

    protected function setRouteMatch($pageConfig, $routeMatch)
    {
        $namespaceName = ucfirst($pageConfig->getModule()) . '\Controller';
        $routeMatch->setParam('__NAMESPACE__', $namespaceName);

        $routeMatch->setParam('controller', 'Index');

        $pageController = $pageConfig->getController();
        $pageAction = $pageConfig->getAction();
        
        if ($pageController) {
            $routeMatch->setParam('controller', $pageController);
        }

        if ($pageAction) {
            $routeMatch->setParam('action', $pageAction);
        }

        return $routeMatch;
    }
}
