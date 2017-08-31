<?php

// Created by Trevor Niemi
// Used to display widget using setTerminal to disable toolbox related pre-includes.

namespace PhoenixTemplates\Controller;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class Template1Controller extends AbstractActionController {

    public function indexAction()
    {
		$viewModel = new ViewModel();
		
		$mrgdConf = $this->getServiceLocator()->get('MergedConfig');
		$viewModel->toolboxIncludeUrl = $mrgdConf->get(array('paths', 'toolboxIncludeUrl'), '');
		
                // Used for accessing the correct css/js includes for the view
		$viewModel->moduleName = 'PhoenixTemplates';
                // Only loads the widget view, skipping Toolbox includes
		$viewModel->setTerminal(true);
		
                // Add global includes used on all widgets
                
		$renderer = $this->getServiceLocator()->get('Zend\View\Renderer\PhpRenderer');
                
                $renderer->headLink()->appendStylesheet("http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css",'text/stylesheet');
                //$renderer->headLink()->appendStylesheet($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/css/foundation.css');
                $renderer->headLink()->appendStylesheet($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/css/foundation-for-gm-plugin.css');
                $renderer->headLink()->appendStylesheet($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/css/jquery.gridmanager.css');
                $renderer->headLink()->appendStylesheet($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/css/demo.css');
                $renderer->headLink()->appendStylesheet($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/css/template-builder.css');

                $renderer->headLink()->appendStylesheet("http://code.jquery.com/ui/1.11.0/themes/smoothness/jquery-ui.css",'text/stylesheet');
                $renderer->headLink()->appendStylesheet("http://cdnjs.cloudflare.com/ajax/libs/prettify/r224/prettify.css",'text/stylesheet');
                        
                $renderer->headLink()->appendStylesheet($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/css/vendor/ui/example.css');
                $renderer->headLink()->appendStylesheet($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/css/vendor/ui/foundation-datepicker.css');
                $renderer->headLink()->appendStylesheet($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/css/vendor/ui/jquery.classygradient.css');
                $renderer->headLink()->appendStylesheet($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/css/vendor/ui/jquery.colorpicker.css');
                $renderer->headLink()->appendStylesheet($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/css/vendor/ui/layout.css');
                $renderer->headLink()->appendStylesheet($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/css/vendor/ui/simple-slider.css');
                
                $renderer->HeadScript()->appendFile('http://code.jquery.com/jquery.min.js','text/javascript');
                $renderer->HeadScript()->appendFile('http://code.jquery.com/ui/1.11.1/jquery-ui.js','text/javascript');
                $renderer->HeadScript()->appendFile($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/js/vendor/modernizr.js','text/javascript');
                $renderer->HeadScript()->appendFile($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/js/vendor/fastclick.js','text/javascript');
                
                $renderer->HeadScript()->appendFile($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/js/foundation/foundation.js','text/javascript');
                $renderer->HeadScript()->appendFile($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/js/jquery.gridmanager.js','text/javascript');
                $renderer->HeadScript()->appendFile($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/js/foundation/foundation.dropdown.js','text/javascript');
                $renderer->HeadScript()->appendFile($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/js/foundation/foundation.topbar.js','text/javascript');
                $renderer->HeadScript()->appendFile($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/js/foundation/foundation.tab.js','text/javascript');
                $renderer->HeadScript()->appendFile($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/js/foundation/foundation.reveal.js','text/javascript');
                $renderer->HeadScript()->appendFile($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/js/vendor/ui/jquery.jeditable.js','text/javascript');
                $renderer->HeadScript()->appendFile($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/js/vendor/ui/excanvas.js','text/javascript');
                $renderer->HeadScript()->appendFile($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/js/vendor/ui/foundation-datepicker.js','text/javascript');
                $renderer->HeadScript()->appendFile($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/js/vendor/ui/jquery.knob.js','text/javascript');
                $renderer->HeadScript()->appendFile($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/js/vendor/ui/simple-slider.min.js','text/javascript');
                $renderer->HeadScript()->appendFile($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/js/template-header.js','text/javascript');
 
                
		return $viewModel;
    }
    
    public function headerAction()
    {
		$viewModel = new ViewModel();
		
		$mrgdConf = $this->getServiceLocator()->get('MergedConfig');
		$viewModel->toolboxIncludeUrl = $mrgdConf->get(array('paths', 'toolboxIncludeUrl'), '');
		
                // Used for accessing the correct css/js includes for the view
		$viewModel->moduleName = 'PhoenixTemplates';
                // Only loads the widget view, skipping Toolbox includes
		$viewModel->setTerminal(true);
		
                // Add global includes used on all widgets
                
		$renderer = $this->getServiceLocator()->get('Zend\View\Renderer\PhpRenderer');
                
                $renderer->headLink()->appendStylesheet("http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css",'text/stylesheet');
                //$renderer->headLink()->appendStylesheet($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/css/foundation.css');
                $renderer->headLink()->appendStylesheet($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/css/foundation-for-gm-plugin.css');
                $renderer->headLink()->appendStylesheet($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/css/jquery.gridmanager.css');
                $renderer->headLink()->appendStylesheet($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/css/demo.css');
                $renderer->headLink()->appendStylesheet($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/css/template-builder.css');

                $renderer->headLink()->appendStylesheet("http://code.jquery.com/ui/1.11.0/themes/smoothness/jquery-ui.css",'text/stylesheet');
                $renderer->headLink()->appendStylesheet("http://cdnjs.cloudflare.com/ajax/libs/prettify/r224/prettify.css",'text/stylesheet');
                        
                $renderer->headLink()->appendStylesheet($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/css/vendor/ui/example.css');
                $renderer->headLink()->appendStylesheet($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/css/vendor/ui/foundation-datepicker.css');
                $renderer->headLink()->appendStylesheet($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/css/vendor/ui/jquery.classygradient.css');
                $renderer->headLink()->appendStylesheet($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/css/vendor/ui/jquery.colorpicker.css');
                $renderer->headLink()->appendStylesheet($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/css/vendor/ui/layout.css');
                $renderer->headLink()->appendStylesheet($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/css/vendor/ui/simple-slider.css');
                
                $renderer->HeadScript()->appendFile('http://code.jquery.com/jquery.min.js','text/javascript');
                $renderer->HeadScript()->appendFile('http://code.jquery.com/ui/1.11.1/jquery-ui.js','text/javascript');
                $renderer->HeadScript()->appendFile($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/js/vendor/modernizr.js','text/javascript');
                $renderer->HeadScript()->appendFile($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/js/vendor/fastclick.js','text/javascript');
                
                $renderer->HeadScript()->appendFile($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/js/foundation/foundation.js','text/javascript');
                $renderer->HeadScript()->appendFile($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/js/jquery.gridmanager.js','text/javascript');
                $renderer->HeadScript()->appendFile($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/js/foundation/foundation.dropdown.js','text/javascript');
                $renderer->HeadScript()->appendFile($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/js/foundation/foundation.topbar.js','text/javascript');
                $renderer->HeadScript()->appendFile($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/js/foundation/foundation.tab.js','text/javascript');
                $renderer->HeadScript()->appendFile($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/js/foundation/foundation.reveal.js','text/javascript');
                $renderer->HeadScript()->appendFile($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/js/vendor/ui/jquery.jeditable.js','text/javascript');
                $renderer->HeadScript()->appendFile($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/js/vendor/ui/excanvas.js','text/javascript');
                $renderer->HeadScript()->appendFile($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/js/vendor/ui/foundation-datepicker.js','text/javascript');
                $renderer->HeadScript()->appendFile($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/js/vendor/ui/jquery.knob.js','text/javascript');
                $renderer->HeadScript()->appendFile($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/js/vendor/ui/simple-slider.min.js','text/javascript');
                $renderer->HeadScript()->appendFile($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/js/template-header.js','text/javascript');
 
                
		return $viewModel;
    }
    
    public function footerAction()
    {
		$viewModel = new ViewModel();
		
		$mrgdConf = $this->getServiceLocator()->get('MergedConfig');
		$viewModel->toolboxIncludeUrl = $mrgdConf->get(array('paths', 'toolboxIncludeUrl'), '');
		
                // Used for accessing the correct css/js includes for the view
		$viewModel->moduleName = 'PhoenixTemplates';
                // Only loads the widget view, skipping Toolbox includes
		$viewModel->setTerminal(true);
		
                // Add global includes used on all widgets
                
		$renderer = $this->getServiceLocator()->get('Zend\View\Renderer\PhpRenderer');
                
                $renderer->headLink()->appendStylesheet("http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css",'text/stylesheet');
                //$renderer->headLink()->appendStylesheet($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/css/foundation.css');
                $renderer->headLink()->appendStylesheet($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/css/foundation-for-gm-plugin.css');
                $renderer->headLink()->appendStylesheet($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/css/jquery.gridmanager.css');
                $renderer->headLink()->appendStylesheet($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/css/demo.css');
                $renderer->headLink()->appendStylesheet($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/css/template-builder.css');

                $renderer->headLink()->appendStylesheet("http://code.jquery.com/ui/1.11.0/themes/smoothness/jquery-ui.css",'text/stylesheet');
                $renderer->headLink()->appendStylesheet("http://cdnjs.cloudflare.com/ajax/libs/prettify/r224/prettify.css",'text/stylesheet');
                        
                $renderer->headLink()->appendStylesheet($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/css/vendor/ui/example.css');
                $renderer->headLink()->appendStylesheet($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/css/vendor/ui/foundation-datepicker.css');
                $renderer->headLink()->appendStylesheet($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/css/vendor/ui/jquery.classygradient.css');
                $renderer->headLink()->appendStylesheet($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/css/vendor/ui/jquery.colorpicker.css');
                $renderer->headLink()->appendStylesheet($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/css/vendor/ui/layout.css');
                $renderer->headLink()->appendStylesheet($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/css/vendor/ui/simple-slider.css');
                
                $renderer->HeadScript()->appendFile('http://code.jquery.com/jquery.min.js','text/javascript');
                $renderer->HeadScript()->appendFile('http://code.jquery.com/ui/1.11.1/jquery-ui.js','text/javascript');
                $renderer->HeadScript()->appendFile($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/js/vendor/modernizr.js','text/javascript');
                $renderer->HeadScript()->appendFile($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/js/vendor/fastclick.js','text/javascript');
                
                $renderer->HeadScript()->appendFile($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/js/foundation/foundation.js','text/javascript');
                $renderer->HeadScript()->appendFile($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/js/jquery.gridmanager.js','text/javascript');
                $renderer->HeadScript()->appendFile($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/js/foundation/foundation.dropdown.js','text/javascript');
                $renderer->HeadScript()->appendFile($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/js/foundation/foundation.topbar.js','text/javascript');
                $renderer->HeadScript()->appendFile($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/js/foundation/foundation.tab.js','text/javascript');
                $renderer->HeadScript()->appendFile($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/js/foundation/foundation.reveal.js','text/javascript');
                $renderer->HeadScript()->appendFile($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/js/vendor/ui/jquery.jeditable.js','text/javascript');
                $renderer->HeadScript()->appendFile($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/js/vendor/ui/excanvas.js','text/javascript');
                $renderer->HeadScript()->appendFile($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/js/vendor/ui/foundation-datepicker.js','text/javascript');
                $renderer->HeadScript()->appendFile($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/js/vendor/ui/jquery.knob.js','text/javascript');
                $renderer->HeadScript()->appendFile($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/js/vendor/ui/simple-slider.min.js','text/javascript');
                $renderer->HeadScript()->appendFile($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/js/template-header.js','text/javascript');
 
                
		return $viewModel;
    }

    public function roomAction()
    {
		$viewModel = new ViewModel();
		
		$mrgdConf = $this->getServiceLocator()->get('MergedConfig');
		$viewModel->toolboxIncludeUrl = $mrgdConf->get(array('paths', 'toolboxIncludeUrl'), '');
		
                // Used for accessing the correct css/js includes for the view
		$viewModel->moduleName = 'PhoenixTemplates';
                // Only loads the widget view, skipping Toolbox includes
		$viewModel->setTerminal(true);
		
                // Add global includes used on all widgets
                
		$renderer = $this->getServiceLocator()->get('Zend\View\Renderer\PhpRenderer');
                
                $renderer->headLink()->appendStylesheet("http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css",'text/stylesheet');
                //$renderer->headLink()->appendStylesheet($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/css/foundation.css');
                $renderer->headLink()->appendStylesheet($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/css/foundation-for-gm-plugin.css');
                $renderer->headLink()->appendStylesheet($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/css/jquery.gridmanager.css');
                $renderer->headLink()->appendStylesheet($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/css/demo.css');
                $renderer->headLink()->appendStylesheet($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/css/template-builder.css');

                $renderer->headLink()->appendStylesheet("http://code.jquery.com/ui/1.11.0/themes/smoothness/jquery-ui.css",'text/stylesheet');
                $renderer->headLink()->appendStylesheet("http://cdnjs.cloudflare.com/ajax/libs/prettify/r224/prettify.css",'text/stylesheet');
                        
                $renderer->headLink()->appendStylesheet($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/css/vendor/ui/example.css');
                $renderer->headLink()->appendStylesheet($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/css/vendor/ui/foundation-datepicker.css');
                $renderer->headLink()->appendStylesheet($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/css/vendor/ui/jquery.classygradient.css');
                $renderer->headLink()->appendStylesheet($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/css/vendor/ui/jquery.colorpicker.css');
                $renderer->headLink()->appendStylesheet($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/css/vendor/ui/layout.css');
                $renderer->headLink()->appendStylesheet($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/css/vendor/ui/simple-slider.css');
                
                $renderer->HeadScript()->appendFile('http://code.jquery.com/jquery.min.js','text/javascript');
                $renderer->HeadScript()->appendFile('http://code.jquery.com/ui/1.11.1/jquery-ui.js','text/javascript');
                $renderer->HeadScript()->appendFile($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/js/vendor/modernizr.js','text/javascript');
                $renderer->HeadScript()->appendFile($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/js/vendor/fastclick.js','text/javascript');
                
                $renderer->HeadScript()->appendFile($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/js/foundation/foundation.js','text/javascript');
                $renderer->HeadScript()->appendFile($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/js/jquery.gridmanager.js','text/javascript');
                $renderer->HeadScript()->appendFile($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/js/foundation/foundation.dropdown.js','text/javascript');
                $renderer->HeadScript()->appendFile($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/js/foundation/foundation.topbar.js','text/javascript');
                $renderer->HeadScript()->appendFile($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/js/foundation/foundation.tab.js','text/javascript');
                $renderer->HeadScript()->appendFile($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/js/foundation/foundation.reveal.js','text/javascript');
                $renderer->HeadScript()->appendFile($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/js/vendor/ui/jquery.jeditable.js','text/javascript');
                $renderer->HeadScript()->appendFile($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/js/vendor/ui/excanvas.js','text/javascript');
                $renderer->HeadScript()->appendFile($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/js/vendor/ui/foundation-datepicker.js','text/javascript');
                $renderer->HeadScript()->appendFile($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/js/vendor/ui/jquery.knob.js','text/javascript');
                $renderer->HeadScript()->appendFile($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/js/vendor/ui/simple-slider.min.js','text/javascript');
                $renderer->HeadScript()->appendFile($viewModel->toolboxIncludeUrl . 'module/Toolbox/view/layout/js/template-header.js','text/javascript');
 
                
		return $viewModel;
    }
    
}