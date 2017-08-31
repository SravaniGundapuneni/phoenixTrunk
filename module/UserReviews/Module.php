<?php
namespace UserReviews;

use Phoenix\Module\Module as PhoenixModule;

use Zend\Mvc\MvcEvent;

class Module extends PhoenixModule
{
    protected $moduleNamespace = __NAMESPACE__;
    protected $moduleDirectory = __DIR__;

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'phoenix-userreviews' => function ($sm) {
             //echo "I am the bbase module";
                    $service = new Service\Reviews();
                    $service->setCurrentUser($sm->get('phoenix-users-current'));
                    $service->setDefaultEntityManager($sm->get('doctrine.entitymanager.orm_default'));
                    $service->setEventManager($sm->get('phoenix-eventmanager'));
                    $service->setConfig($sm->get('MergedConfig'));
                    $service->setServiceManager($sm);
                    return $service;
                },            
            ),    
        );
    }

    public function getViewHelperConfig()
	{
		
        return array(
            'factories' => array(
                /*'GetForm' => function($sm) {
                    $serviceManager = $sm->getServiceLocator();
                    $formService = $serviceManager->get('phoenix-flexibleforms');
                    $formManager = $serviceManager->get('phoenix-formmanager');
                 
                    $helper = new \FlexibleForms\Helper\GetForm($formService, $formManager);
                    return $helper;
                }, */
                   /* 'GetUser' => function($sm) {
                    $serviceManager = $sm->getServiceLocator();
              
                    $viewFormService = $serviceManager->get('phoenix-flexibleforms-view');
                    $helper = new \FlexibleForms\Helper\GetUser($viewFormService);
                    return $helper; 
                },*/
            )
        );
    }

    public function onBootstrap(MvcEvent $e)
    {
        //Get the Event Manager for the MVC Application
        $eventManager = $e->getApplication()->getEventManager();

        $eventManager->attach(\Zend\Mvc\MvcEvent::EVENT_DISPATCH, array($this, 'setLayout'), 100);
        
        /**
         * Module Migrations Support
         */
        $this->updateTableStructures($e);
    }

    /**
     * setLayout
     *
     * Sets the layout to the toolbox layout if this is a toolbox request
     *
     * @param MvcEvent $e
     * @return void
     */
    public function setLayout(MvcEvent $e)
    {
        $routeMatch = $e->getRouteMatch();

        $controller = $routeMatch->getParam('controller');

        if (strpos($controller, 'Controller\ViewToolbox') > 0||strpos($controller, 'Controller\ItemsToolbox') > 0 || strpos($controller, 'Controller\FormToolbox') > 0 || strpos($controller, 'Controller\FieldsToolbox') > 0) {
            $e->getViewModel()->setTemplate('toolbox-layout');
        }
    }    
}
