<?php
namespace PhoenixSite\Helper\Widget;
use       PhoenixSite\Helper\Widget\Classes;

class WidgetDataAbstract
{
	const FRONTEND_TEMPLATE_DIR = 'templates/main/core-templates/';
	protected $abstractTemplateID;
	protected $baseName;
	protected $frontendPath;
	protected $mrgdConf;
	protected $renderer;
	protected $request;
	protected $serviceLocator;
	protected $siteroot;
	protected $templateName;
	protected $toolboxIncludeUrl;
	protected $widgetDataModule;
	protected $widgetDataTranslate;
	protected $widgetname;

	private $options;
	private $widgetHelperPath;

	public function __construct($options)
	{
		$this->initOptions($options);	
		$this->initFromServiceLocator();
		$this->initFromMergedConfig();
	}

	private function getFilePath($filename)
	{
		return $this->toolboxIncludeUrl . 'widget/' . $this->widgetname . '/view/' . $this->baseName . '/helper/' . $filename . '.phtml';
	}

	private function initOptions($options)
	{
		$this->options = $options;

		$this->widgetHelperPath   = 'widget/' . $options['widgetName'] . '/view/' . $options['baseName'] . '/helper/';
		$this->serviceLocator     = $options['serviceLocator'];
		$this->baseName           = $options['baseName'];
		$this->widgetname         = $options['widgetName'];
		$this->templateName       = $options['templateName'];
		$this->request            = $options['request'];
		$this->abstractTemplateID = $options['abstractTemplateID'];
	}

	private function initFromServiceLocator()
	{
		$this->mrgdConf          = $this->serviceLocator->get('MergedConfig');
		$this->renderer          = $this->serviceLocator->get('Zend\View\Renderer\PhpRenderer');
		$this->initWidgetDataModule();
	}

	private function initFromMergedConfig()
	{
		$this->siteroot          = $this->mrgdConf->get(array('templateVars', 'siteroot'));
		$this->toolboxIncludeUrl = $this->mrgdConf->get(array('paths', 'toolboxIncludeUrl'));
	}

	private function initWidgetDataModule()
	{
		$options = array(
			'dynamicListModule'    => $this->serviceLocator->get('phoenix-dynamiclistmodule'),
			'dynamicManager'       => $this->serviceLocator->get('phoenix-dynamicmanager'),
			'mediaManagerImage'    => $this->serviceLocator->get('phoenix-mediamanager-image'),
			'mediaManagerImage'    => $this->serviceLocator->get('phoenix-mediamanager-image'),
			'listModuleCategories' => $this->serviceLocator->get('phoenix-listmodule-categories'),
			'propertyInformation'  => $this->serviceLocator->get('phoenix-property-information'),
		);
		$this->widgetDataModule    = new Classes\WidgetDataModule($options);
		$this->widgetDataTranslate = new Classes\WidgetDataTranslate();
	}

	protected function renderTemplate($filename, array $data)
	{
		$template = file_get_contents($this->getFilePath($filename));	

		if ($template) {
			foreach ($data as $key => $value) {
				$template = str_replace('{{ ' . $key . ' }}', $value, $template);
			}
		} else {
			$template = "";
		}

		return $template;
	}
	
	protected function getTemplateId()
	{
		return $this->config['templateVars']['templateID'];
	}	
}
