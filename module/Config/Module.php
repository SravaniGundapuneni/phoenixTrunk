<?php
namespace Config;

use Phoenix\Module\Module as PhoenixModule;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\ModuleManager\ModuleManager as ModuleManager;

class Module extends PhoenixModule
{
    const EXCEPTION_TEMPLATE = 'error/index';
    const NOT_FOUND_TEMPLATE = 'error/404';

    protected $moduleNamespace = __NAMESPACE__;
    protected $moduleDirectory = __DIR__;

    /**
     * onBootstrap
     *
     * This is triggered by the application's onBoostrap event. The only function of this:
     *
     * Attach a callback to the dispatch event that is used to merge configs. The priority is set to 105 so it will
     * run before the events
     *
     * @param  MvcEvent $e [description]
     * @return void
     */
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

        $eventManager->attach( \Zend\Mvc\MvcEvent::EVENT_ROUTE, array($this, 'mergeConfigs'), -3);
        $eventManager->attach( \Zend\Mvc\MvcEvent::EVENT_DISPATCH_ERROR, array($this, 'mergeConfigsError'), 15);      
        $eventManager->attach(\Zend\Mvc\MvcEvent::EVENT_DISPATCH_ERROR, array($this, 'setDispatchErrorConfig'), 100);       
        $eventManager->attach(MvcEvent::EVENT_RENDER_ERROR, array($this, 'setDispatchErrorConfig'), 100);
        // $eventManager->attach(\Zend\Mvc\MvcEvent::EVENT_ROUTE, array($this, 'setIhotelierLangCode'), -751);
    }

    /**
     * mergeConfigsErrors
     *
     * Used in the instance an error (such as 404) occurs
     * 
     * @param  MvcEvent $e
     * @return void
     */
    public function mergeConfigsError(MvcEvent $e)
    {
        $viewManager = $e->getApplication()->getServiceManager()->get('view_manager');

        $showNotFound = $viewManager->getRouteNotFoundStrategy()->displayNotFoundReason();
        $showExceptions = $viewManager->getRouteNotFoundStrategy()->displayExceptions();
        //$showNotFoundReasons = $viewManager->get

        if ($e->getError() && $e->getError() != 'error-exception') {
            if (!($showExceptions || $showNotFound)) {
                $configManager = $e->getApplication()->getServiceManager()->get('phoenix-configmanager');
                $modulesConfig = $configManager->getRawConfig('modulesConfig');

                $siteModel = $e->getApplication()->getServiceManager()->get('phoenix-sitemodel');

                $newModulesConfig = $this->get404Config($siteModel, $configManager, $modulesConfig);

                $configManager->saveConfig('modulesConfig', $newModulesConfig);
            } else {
                $e->setParam('ignoreTemplateSetup', true);
            }
            
            $this->mergeConfigs($e);            
        }
    }

    public function setDispatchErrorConfig(MvcEvent $e)
    {
        $serviceManager = $e->getApplication()->getServiceManager();
        $viewManager = $serviceManager->get('view_manager');
        $displayExceptions = $viewManager->getExceptionStrategy()->displayExceptions();
        // var_dump($displayExceptions);
        // die('FILE: ' . __FILE__ . ' LINE: ' . __LINE__);
        //$showExceptions = $mergedConfig->get(array('view'))

        if ($e->getError() == 'error-exception' && !$displayExceptions) {
            if ($displayExceptions) {
                //Override the error handling template with our default
                $viewManager->getExceptionStrategy()->setExceptionTemplate(self::EXCEPTION_TEMPLATE);
            } else {
                //We have an error, and we need to funnel it to the 404 page
                $mergedConfig = $serviceManager->get('MergedConfig');

                $configManager = $serviceManager->get('phoenix-configmanager');

                $siteModel = $e->getApplication()->getServiceManager()->get('phoenix-sitemodel');

                $newConfig = $this->get404Config($siteModel, $configManager, $mergedConfig->getMergedConfig());

                $mergedConfig->setMergedConfig($newConfig);                
            }
        }
    }

    protected function get404Config($siteModel, $configManager, $modulesConfig)
    {
        $config404 = include SITE_PATH . '/404.php';

        $newModulesConfig = $configManager->mergeModuleConfigs($modulesConfig, $config404);

        $subsite = $siteModel->getSubsite();
        if ($subsite) {
            $subsitePath404 = SITE_PATH . $subsite . '/404.php';
            if (file_exists($subsitePath404)) {
                $subsite404 = include $subsitePath404;
                $newModulesConfig = $configManager->mergeModuleConfigs($newModulesConfig, $subsite404);       
            }
        }

        return $newModulesConfig;
    }

    /**
     * getServiceConfig
     *
     * Return the ServiceManager config for this module.
     *
     * @return array The array of configuration (including factories for services related to the module)
     */
    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'phoenix-configmanager' => function ($sm) {
                    $service = new Service\ConfigManager();
                    $service->setDefaultEntityManager($sm->get('doctrine.entitymanager.orm_default'));
                    $service->setAdminEntityManager($sm->get('doctrine.entitymanager.orm_admin'));
                    $service->setEventManager($sm->get('phoenix-eventmanager'));
                    $applicationConfig = $sm->get('ApplicationConfig');
                    $service->addConfig('applicationConfig', $applicationConfig);
                    $service->addConfig('modulesConfig', $sm->get('Config'));
                    return $service;
                },
                )
            );
    }

    /**
     * mergeConfigs
     *
     * dispatch event used to merge all of the configs into one unified config array.
     *
     * @param  MvcEvent $e
     * @return void
     */
    public function mergeConfigs($e)
    {
        //Get the Service Manager
        $serviceManager = $e->getApplication()->getServiceManager();

        //Get the Config Manager service
        $configManager = $serviceManager->get('phoenix-configmanager');

        //Merge the configs
        $configManager->mergeConfigs();

        $serviceManager->setAllowOverride(true);
        //Add the MergedConfig model to the service manager for easy access
        $serviceManager->setService('MergedConfig', $configManager->getMergedConfig());
        $serviceManager->setAllowOverride(false);
    }

    /**
     * setIHotelierLangCode
     *
     * Sets the iHotelierLangID in the templateVars
     *
     * @param MvcEvent $e
     */
    public function setIhotelierLangCode($e)
    {
        $serviceManager = $e->getApplication()->getServiceManager();

        //Get the MergedConfig model and the Polytext service
        $mergedConfig = $serviceManager->get('MergedConfig');
        $polytext = $serviceManager->get('phoenix-polytext');

        //Set the ihotelierLangID in the template vars in the mergedConfig to the proper langID
        $mergedConfig->set(array('templateVars', 'ihotelierlangID'), $this->getIHotelierLangID($polytext->getLangCode()));
    }

    /**
     * getIHotelierLangID
     *
     * Returns the correct iHotelierLangID based upon the given two letter language code.
     *
     * Ideally, this will be part of a iHotelier service in the future, but as there is none in Phoenix as of now,
     * is as good of a place as any to put it.
     *
     * @param  string $langCode
     * @return integer
     */
    public function getIHotelierLangID($langCode)
    {
        switch($langCode)
        {
            case 'en':
                $lanId = 1;
                break;
            case 'es':
                $lanId = 2;
                break; // Spanish | Español | Castellano
            case 'fr':
                $lanId = 3;
                break; // French | Français
            case 'pt':
                $lanId = 4;
                break; // Portuguese | Português
            case 'zh':
                $lanId = 5;
                break; // Chinese (simplified) | 中文
            case 'zs':
                $lanId = 5;
                break; // Chinese (simplified) | 简体中文            THIS IS AN INVENTED 2-digit CODE
            case 'ja':
                $lanId = 6;
                break; // Japanese | Nihongo / 日本語
            case 'de':
                $lanId = 7;
                break; // German | Deutsch
            case 'it':
                $lanId = 8;
                break; // Italian | Italiano
            case 'ru':
                $lanId = 9;
                break; // Russian | Русский
            case 'nl':
                $lanId = 10;
                break; // Dutch | Nederlands
            case 'zt':
                $lanId = 12;
                break; // Chinese (traditional) | 繁體中文          THIS IS AN INVENTED 2-digit CODE
            case 'hu':
                $lanId = 13;
                break; // Hungarian | Magyar
            case 'el':
                $lanId = 14;
                break; // Greek | Ελληνικά
            case 'ar':
                $lanId = 15;
                break; // Arabic | الْعَرَبيّة
            case 'sv':
                $lanId = 16;
                break; // Swedish | Svenska
            case 'no':
                $lanId = 17;
                break; // Norwegian | Norsk
            case 'cs':
                $lanId = 18;
                break; // Czech | čeština
            case 'tr':
                $lanId = 19;
                break; // Turkish | Türkçe
            case 'ca':
                $lanId = 20;
                break; // Catalan | Català
            case 'da':
                $lanId = 21;
                break; // Danish | Dansk
            // 22 missing
            case 'hr':
                $lanId = 23;
                break; // Croatian | Hrvatski
            case 'sk':
                $lanId = 24;
                break; // Slovak | slovenčina
            case 'lt':
                $lanId = 25;
                break; // Lithuanian | Lietuvių
            case 'ko':
                $lanId = 26;
                break; // Korean |
            case 'pl':
                $lanId = 27;
                break; // Polish | Polski
            case 'id':
                $lanId = 28;
                break; // Indonesian | Bahasa indonesia
            case 'he':
                $lanId = 29;
                break; // Hebrew | Ivrit | עברית
            case 'et':
                $lanId = 30;
                break; // Estonian | Eesti
            case 'lv':
                $lanId = 31;
                break; // Latvian | latviešu
            case 'ro':
                $lanId = 32;
                break; // Romanian | Română
            case 'is':
                $lanId = 33;
                break; // Icelandic | Íslenska
            case 'fi':
                $lanId = 34;
                break; // Finnish | Suomi
            // Non-standard, non-authorized language codes, purely for backwards compatibility
            case 'gr':
                $lanId = 14;
                break; // Fake for Greek, do not use this! The code 'gr' is not used for any language, so this hack is safe. Added for fixing Myconian etc.
            default:
                $lanId = 1;
                break;
        }

        return $lanId;
    }
}