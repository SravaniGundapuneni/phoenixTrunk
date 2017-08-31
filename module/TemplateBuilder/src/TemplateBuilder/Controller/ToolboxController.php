<?php

// Created by Trevor Niemi
// Used to display widget using setTerminal to disable toolbox related pre-includes.

namespace TemplateBuilder\Controller;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;

class ToolboxController extends AbstractActionController {

    public function indexAction() 
    {
                $viewModel = new ViewModel();
		
		$mrgdConf = $this->getServiceLocator()->get('MergedConfig');
		$viewModel->toolboxIncludeUrl = $mrgdConf->get(array('paths', 'toolboxIncludeUrl'), '');
                $viewModel->templateID = "template".$mrgdConf->get(array('templateVars', 'templateId'), '');
		$viewModel->siteroot = $mrgdConf->get(array('templateVars', 'siteroot'), '');
                // Used for accessing the correct css/js includes for the view
		$viewModel->moduleName = 'TemplateBuilder';
                
                // Only loads the widget view, skipping Toolbox includes
		$viewModel->setTerminal(true);
                
                // Add global includes used on all widgets
		$renderer = $this->getServiceLocator()->get('Zend\View\Renderer\PhpRenderer');
                
                $renderer->headLink()->appendStylesheet("http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css",'text/stylesheet');
                //$renderer->headLink()->appendStylesheet($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/css/foundation.css');
                $renderer->headLink()->appendStylesheet($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/css/foundation-for-gm-plugin.css');
                
                $renderer->headLink()->appendStylesheet($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/css/demo.css');
                $renderer->headLink()->appendStylesheet($viewModel->toolboxIncludeUrl . 'module/TemplateBuilder/view/template-builder/toolbox/css/IconFont/styles.css');
                $renderer->headLink()->appendStylesheet("//code.jquery.com/ui/1.11.0/themes/smoothness/jquery-ui.css",'text/stylesheet');
                $renderer->headLink()->appendStylesheet("//cdnjs.cloudflare.com/ajax/libs/prettify/r224/prettify.css",'text/stylesheet');
                $renderer->headLink()->appendStylesheet($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/css/vendor/slick/slick.css');
                $renderer->headLink()->appendStylesheet($viewModel->toolboxIncludeUrl . 'module/TemplateBuilder/view/template-builder/toolbox/css/template-builder.css');
                $renderer->headLink()->appendStylesheet($viewModel->toolboxIncludeUrl . "module/PhoenixTemplates/view/phoenix-templates/{$viewModel->templateID}/css/main.css");
                
                        
                $renderer->HeadScript()->appendFile('http://code.jquery.com/jquery.min.js','text/javascript');
                $renderer->HeadScript()->appendFile('http://code.jquery.com/jquery-migrate-1.2.1.min.js','text/javascript');                    
                $renderer->HeadScript()->appendFile($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/js/vendor/slick.js','text/javascript');
                $renderer->HeadScript()->appendFile('//code.jquery.com/ui/1.11.1/jquery-ui.js','text/javascript');
                
                $renderer->HeadScript()->appendFile($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/js/vendor/modernizr.js','text/javascript');
                $renderer->HeadScript()->appendFile($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/js/vendor/fastclick.js','text/javascript');
                
                $renderer->HeadScript()->appendFile($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/js/foundation/foundation.js','text/javascript');
                $renderer->HeadScript()->appendFile($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/js/foundation/foundation.tab.js','text/javascript');
                $renderer->HeadScript()->appendFile($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/js/foundation/foundation.reveal.js','text/javascript');
                $renderer->HeadScript()->appendFile($viewModel->toolboxIncludeUrl . "module/PhoenixTemplates/view/phoenix-templates/{$viewModel->templateID}/js/media.queries.js",'text/javascript');
                $renderer->HeadScript()->appendFile($viewModel->toolboxIncludeUrl . 'module/TemplateBuilder/view/template-builder/toolbox/js/notifications.js','text/javascript');
                $renderer->HeadScript()->appendFile($viewModel->toolboxIncludeUrl . 'module/TemplateBuilder/view/template-builder/toolbox/js/page-layouts.js','text/javascript');
                
                
		return $viewModel;   
    }
    
    public function pageLayoutConfigAction()
    {
		$viewModel = new ViewModel();
		
		$mrgdConf = $this->getServiceLocator()->get('MergedConfig');
		$viewModel->toolboxIncludeUrl = $mrgdConf->get(array('paths', 'toolboxIncludeUrl'), '');
                $viewModel->templateID = "template".$mrgdConf->get(array('templateVars', 'templateId'), '');
                $viewModel->siteroot = $mrgdConf->get(array('templateVars', 'siteroot'), '');
                
                // Used for accessing the correct css/js includes for the view
		$viewModel->moduleName = 'TemplateBuilder';
                // Only loads the widget view, skipping Toolbox includes
		$viewModel->setTerminal(true);
		
                // Add global includes used on all widgets
                
		$renderer = $this->getServiceLocator()->get('Zend\View\Renderer\PhpRenderer');
                
                $renderer->headLink()->appendStylesheet("http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css",'text/stylesheet');
                //$renderer->headLink()->appendStylesheet($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/css/foundation.css');
                $renderer->headLink()->appendStylesheet($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/css/foundation-for-gm-plugin.css');
                
                $renderer->headLink()->appendStylesheet($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/css/demo.css');
                $renderer->headLink()->appendStylesheet($viewModel->toolboxIncludeUrl . 'module/TemplateBuilder/view/template-builder/toolbox/css/IconFont/styles.css');
                $renderer->headLink()->appendStylesheet("//code.jquery.com/ui/1.11.0/themes/smoothness/jquery-ui.css",'text/stylesheet');
                $renderer->headLink()->appendStylesheet("//cdnjs.cloudflare.com/ajax/libs/prettify/r224/prettify.css",'text/stylesheet');
                $renderer->headLink()->appendStylesheet($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/css/vendor/slick/slick.css');
                $renderer->headLink()->appendStylesheet($viewModel->toolboxIncludeUrl . 'module/TemplateBuilder/view/template-builder/toolbox/css/template-builder.css');
                
                $viewModel->setVariable('tmplId', $mrgdConf->get(array('templateVars', 'templateId'), ''));
                $viewModel->setVariable('subTmpl', $this->params()->fromPost('sub-template'));
                $viewModel->setVariable('editFlag', $this->params()->fromPost('edit-flag'));
                $viewModel->setVariable('tmplURL', $viewModel->toolboxIncludeUrl);
                        
                $renderer->HeadScript()->appendFile('http://code.jquery.com/jquery.min.js','text/javascript');
                $renderer->HeadScript()->appendFile('http://code.jquery.com/jquery-migrate-1.2.1.min.js','text/javascript');                    
                $renderer->HeadScript()->appendFile($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/js/vendor/slick.js','text/javascript');
                $renderer->HeadScript()->appendFile('//code.jquery.com/ui/1.11.1/jquery-ui.js','text/javascript');
                
                $renderer->HeadScript()->appendFile($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/js/vendor/modernizr.js','text/javascript');
                $renderer->HeadScript()->appendFile($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/js/vendor/fastclick.js','text/javascript');
                
                $renderer->HeadScript()->appendFile($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/js/foundation/foundation.js','text/javascript');
                $renderer->HeadScript()->appendFile($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/js/foundation/foundation.dropdown.js','text/javascript');
                $renderer->HeadScript()->appendFile($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/js/foundation/foundation.tab.js','text/javascript');
                $renderer->HeadScript()->appendFile($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/js/foundation/foundation.reveal.js','text/javascript');
                $renderer->HeadScript()->appendFile($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/js/foundation/foundation.offcanvas.js','text/javascript');
                $renderer->HeadScript()->appendFile($viewModel->toolboxIncludeUrl . 'module/TemplateBuilder/view/template-builder/toolbox/js/notifications.js','text/javascript');
                $renderer->HeadScript()->appendFile($viewModel->toolboxIncludeUrl . 'module/TemplateBuilder/view/template-builder/toolbox/js/page-layout-config.js','text/javascript');
                
		return $viewModel;
    }
}