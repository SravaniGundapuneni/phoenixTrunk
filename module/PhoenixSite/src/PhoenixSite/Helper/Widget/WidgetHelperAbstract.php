<?php
namespace PhoenixSite\Helper\Widget;

use Zend\View\Helper\AbstractHelper;
use PhoenixSite\Widget\DataManager;
use Zend\Http\Request;

class WidgetHelperAbstract extends AbstractHelper
{
    protected $config;
    protected $dataManager;
    protected $serviceLocator;
    protected $frontendPath;
    protected $options;
    protected $request;

    public function setConfig($config)
    {
        $this->config = $config;
    }

    public function getConfig()
    {
        return $config;
    }

    public function setDataManager(DataManager $dataManager)
    {
        $this->dataManager = $dataManager;
    }

    public function setServiceLocator($serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
        $this->setPropertiesFromServiceLocator();
    }

    public function getDataManager()
    {
        return $this->dataManager;
    }

    protected function getWidgetDataOptions()
    {
        $widgetDataOptions = array(
            'abstractTemplateID' => $this->getTemplateId(),
            'baseName'           => static::BASE_NAME,
            'request'            => $this->request,
            'serviceLocator'     => $this->serviceLocator,
            'templateName'       => $this->getTemplateName(),
            'widgetName'         => static::WIDGET_NAME,
        );

       return $widgetDataOptions;
    }

    protected function getPath()
    {
        return $this->options['templatePage'] . '/widgets/' . ucfirst($this->options['widgetName']) . $this->options['widgetId'] . '/' . $this->getTemplateName() . '.phtml';
    }

    protected function getTemplateName()
    {
        return static::BASE_NAME . '-template' . $this->getTemplateId();
    }

    protected function getTemplateId()
    {
        return $this->config['templateVars']['templateID'];
    }

    private function setPropertiesFromServiceLocator()
    {
        $this->request = $this->serviceLocator->get('request');
    }
}