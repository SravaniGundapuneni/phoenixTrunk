<?php

namespace LayoutEditor;


use Phoenix\Module\Module as PhoenixModule;

class Module extends PhoenixModule
{
    protected $moduleNamespace = __NAMESPACE__;
    protected $moduleDirectory = __DIR__;

    public function onBootstrap($e)
    {

        $eventManager = $e->getApplication()->getEventManager();
    }

    public function getServiceConfig()
    {
        return array(
			
        );
    }
	
	
	public function getConfig()
	{
                // widget config name
		return include __DIR__ . '/config/module.config.php';
	}
}