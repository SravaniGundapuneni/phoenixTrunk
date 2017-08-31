<?php

/**
 * Pages ToolboxController
 *
 * The ToolboxController for the Pages Module
 *
 * If it is a toolbox action for the Pages module, it goes here.
 *
 * @category    Toolbox
 * @package     Pages
 * @subpackage  Controllers
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.5
 * @since       File available since release 13.5
 * @author      A. Tate <atate@travelclick.com>
 * @filesource
 */

namespace Pages\Controller;

use ListModule\Controller\ToolboxController as ListModuleToolbox;
use Zend\View\Model\ViewModel;
use Pages\Form\PropertyForm;
use Pages\EventManager\Event as PagesEvent;

/**
 * Pages ToolboxController
 *
 * The ToolboxController for the Pages Module
 *
 * If it is a toolbox action for the Pages module, it goes here.
 *
 * For all intents and purposes, Pages is a ListModule, as pages will effectively be module items.
 * Therefore, we can utilize the ListModule CRUD functionality.
 *
 * @category    Toolbox
 * @package     Pages
 * @subpackage  Controllers
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.5
 * @since       Class available since release 13.5
 * @author      A. Tate <atate@travelclick.com>
 */
class ToolboxController extends ListModuleToolbox 
{
    const DEFAULT_NOITEM_ROUTE = 'pages-toolbox';

    protected $module = 'Pages';
    protected $editItemTemplate = 'pages/toolbox/edit-item';
    protected $editItemTitle = 'Edit Page';
    protected $editListTitle = 'Pages';
    protected $formName = '\Pages\Form\PagesForm';

    /**
     * The route to use in setting up the the module task menu (the left hand menu)
     * @var string
     */
    protected $toolboxRoute = self::DEFAULT_NOITEM_ROUTE;

    public function __construct() 
    {
        $this->modsing = $this->module;
        define('DEFAULT_NOITEM_ROUTE', static::DEFAULT_NOITEM_ROUTE);
        unset($this->editListOptions['Toggle Re-Order']);
        unset($this->editListOptions['Save Item Order']);

        $this->socketsRoute = lcfirst($this->module) . '-sockets';
    }

    public function editlistAction() 
    {
        $pagesService = $this->getServiceLocator()->get('phoenix-pages');
        $pagesService->setDataSection($this->params()->fromRoute('subsite', ''));
        $pagesService->setCurrentUser($this->getServiceLocator()->get('phoenix-users-current'));
        $viewModel = parent::editlistAction();

        if (!$viewModel instanceof ViewModel) {
            return $viewModel;
        }

        $children = $viewModel->getChildren();

        $taskViewModel = $children[0];

        $taskViewModel->setTemplate('pages/toolbox/edit-list');

        return $viewModel;
    }

    protected function preProcessForm($form, $service)
    {
        //Get the post parameters
        $post = $this->getRequest()->getPost();

        //build a machine friendly version of the pageKey
        $pageUrl = str_replace(' ', '-', $post['pageKey']);

        //Explode out pageUrl that is a multipart path 
        $pageUrlParts = explode('/', $pageUrl);

        //Check if the pageUrlParts array is a multipart array
        if (!empty($pageUrlParts) && count($pageUrlParts) > 1) {
            //Run through each url part
            foreach ($pageUrlParts as $keyPart => $valPart) {
                //trim whitespaces
                $part = trim($valPart);
                    
                //Check if part is empty and unset if it is
                if (empty($part)) {
                    unset($pageUrlParts[$keyPart]);
                }
            }

            //Pop the last section of the url array off to be the pageKey
            $pageUrl = array_pop($pageUrlParts);

            //Take the rest of the url array and build the dataSection for this page
            $dataSection = implode('/', $pageUrlParts);
            $subsite = $this->params()->fromRoute('subsite', '');

            if (!empty($subsite)) {
                $dataSection = str_replace($subsite, '', $dataSection);

                if (!empty($dataSection)) {
                    $dataSection = $subsite . '/' . $dataSection;
                }
            }

            //set the datasection in the post object
            $post->set('dataSection', $dataSection);
        }

        //Let's remove bad characters, allowing only Alphanumeric, '-', and '_'
        $pageUrl = preg_replace("/[^a-zA-Z0-9-_]/", "", $pageUrl);

        $post->set('pageKey', $pageUrl);

        $pageRates = $post->get('pageRates', array());
        //This is not allowing addditionalParams to save in db,however I am not able to track this.Please look at the commented code
        //Sravani
        /*
        if (count($pageRates) && implode(',', $pageRates)) {
            $post->set('additionalParams', '');
        }
        */
       
        //Remove the categoryId field
        $form->remove('categoryId');       
    }

    /**
     * postProcessForm
     *
     * Used to process form changes after the doEditItem method is called.
     * 
     * @param  mixed $form          
     * @param  mixed $service       
     * @param  View\Model\ViewModel $taskViewModel 
     * @return                
     */
    protected function postProcessForm($form, $service, $taskViewModel)
    {
        //Get the Service Manager
        $svcLoc = $this->getServiceLocator();

        //Get the Current User
        $currentUser = $svcLoc->get('phoenix-users-current');

        //Get the Page Type Element
        $pageType = $form->get('pageType');

        //Set the isContent variable on the view model
        $taskViewModel->isContent = ($pageType->getValue() == 'contentpage') ? true : false;

        //Remove the category ID
        $form->remove('categoryId');

        //Check if currentUser is corporate
        if (!$currentUser->isCorporate()) {
            //Get the hotel element
            $hotelSelect = $form->get('hotel');

            //Get the value from the hotel field
            $hotelSelectValue = $hotelSelect->getValue();

            //Remove the hotel field so this user can't change it.
            $form->remove('hotel');

            //Check if the hotel value is empty (i.e. new page)
            if (empty($hotelSelectValue)) {
                //Get the current property's ID
                $hotelValue = $svcLoc->get('currentProperty')->getId();

                //Create a hidden field element
                $hotel = new \Zend\Form\Element\Hidden('hotel');
                $hotel->setValue($hotelValue);
                $form->add($hotel);
            }
        }

        //Set a default value
        $dataSection = $form->get('dataSection');

        $dataSectionValue = $dataSection->getValue();

        if (empty($dataSectionValue)) {
            $dataSection->setValue($this->params()->fromRoute('subsite', ''));
        }

        //Check to see if user is a developer
        if (!$currentUser->isDeveloper()) {
            //Get the template value
            $templateField = $form->get('template');
            $templateValue = $templateField->getValue();

            //Check if templateValue is empty
            if (empty($templateValue)) {
                $templateValue = 'landingpages.phtml';
            }

            //Remove the template field
            $form->remove('template');
            
            $newTemplate = new \Zend\Form\Element\Hidden('template');
            $newTemplate->setValue($templateValue);

            $form->add($newTemplate);
        }
    }
}