<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Pages\Controller;

use Toolbox\Mvc\Controller\AbstractToolboxController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractToolboxController
{
    protected $currentPage;

    private $viewModel;

    private $mergedConfig;
    //=====================================================================================================================
    // Page initialization action. Expects page ID in URL as the last segment
    public function indexAction()
    {  
        $this->viewModel = new ViewModel();

        // this is for adding all necessary <script> tags for widgets used on the page
        $GLOBALS['tc_page_widgets'] = array();
        $GLOBALS['tc_dynamic_data'] = array();

        $viewManager       = $this->getServiceLocator()->get('view-manager');
        $this->mergedConfig = $this->getServiceLocator()->get('MergedConfig');

        $this->currentPage = $this->getServiceLocator()->get('currentPage');

        //$viewModel->setTerminal($mergedConfig->get(array('auto-layout'), false));

        $viewManager->getViewModel()->currentPage = $this->currentPage;

        $this->viewModel->setVariables($viewManager->getViewModel()->getVariables());

        // If it's a landing page get all the page rates
        if ($this->currentPage->getId()) {

            $currentLanguage = $this->viewModel->currentLanguage;

            $this->viewModel->additionalParams        = $this->getAdditionalParams();
            $this->viewModel->blocks                  = $this->getBlocks($currentLanguage);
            $this->viewModel->blocks2                 = $this->getBlocks2(); 
            $this->viewModel->currentDataSection      = $this->getCurrentDataSection(); 
            $this->viewModel->currentLanguageCode     = $this->getCurrentLanguageCode($currentLanguage);
            $this->viewModel->currentPageRates        = $this->getCurrentPageRates();
            $this->viewModel->eventName               = $this->getEventName();
            $this->viewModel->metaText                = $this->getMetaText();
            $this->viewModel->pageKey                 = $this->getPageKey();
            $this->viewModel->pageKeyNum              = $this->getPageKeyNum();
            $this->viewModel->pageProperties          = $this->getPageProperties();
            $this->viewModel->propertyHomepageHeading = $this->getPropertyHomePageHeading();
            $this->viewModel->templateSlug            = $this->getTemplateSlug();
        }

        $this->setLayoutTemplate($viewManager->getViewModel());

        $this->viewModel->setTemplate($this->currentPage->getTemplate());

        return $this->viewModel;
    }

    private function setLayoutTemplate($layoutViewModel)
    {
        if (!$this->setViewModelToTerminal()) {
            $layout = $this->mergedConfig->get(array('view_manager', 'layout'), false);

            if ($layout) {
                $layoutViewModel->setTemplate($layout);
            }
        }
    }

    private function setViewModelToTerminal()
    {
        $terminal = $this->mergedConfig->get(array('auto-layout'), false);

        if (!$terminal) {
            $this->viewModel->setTerminal(true);
        }

        return $this->viewModel->terminate();
    }

    private function getAdditionalParams()
    {
        return $this->currentPage->getAdditionalParams();
    }

    private function getBlocks($currentLanguage)
    {
        $pagesService    = $this->getServiceLocator()->get('phoenix-pages');
        $currentProperty = $this->getServiceLocator()->get('currentProperty');

        if ($currentLanguage['code'] == 'fr') {
            $blocks = $pagesService->getTranslatedBlocks($this->currentPage->getId());
        } else {
            $blocks = $this->currentPage->getBlocks();
        }

        return $blocks;
    }

    private function getBlocks2()
    {
        return $this->currentPage->getBlocks2();
    }

    private function getCurrentLanguageCode($currentLanguage)
    {
        return $currentLanguage['code'];
    }

    private function getCurrentDataSection()
    {
        return $this->currentPage->getDataSection();
    }

    private function getCurrentPageRates()
    {
        $ratesService     = $this->getServiceLocator()->get('phoenix-rates');
        $currentPageRates = $ratesService->getPageRates($this->currentPage->getId());

        foreach ($currentPageRates as $key => $pageRate) {
            $currentPageRates[$key] = $ratesService->getRate($pageRate->getRateId());
        }

        return $currentPageRates;
    }

    private function getEventName()
    {
        return $this->currentPage->getEventName(); 
    }

    private function getMetaText()
    {
        $metaTextService = $this->getServiceLocator()->get('phoenix-seoMetaText');
        return $metaTextService->getMetaText($this->currentPage->getId());
    }

    private function getPageKey()
    {
        return $this->currentPage->getPageKey();
    }

    private function getPageKeyNum()
    {
        return $this->currentPage->getId();
    }

    private function getPageProperties()
    {
        $pageProperties = array();

        $currentPageProperties = $this->currentPage->getPageProperties();

        foreach ($currentPageProperties as $valProperty) {
            $pageProperties[$valProperty->getCode()] = $valProperty->getId();
        }

        return $pageProperties;
    }

    private function getPropertyHomePageHeading()
    {
        return $this->currentPage->getPageHeading();
    }

    private function getTemplateSlug()
    {
        return str_replace(".phtml", "", $this->currentPage->getTemplate());
    }
}
