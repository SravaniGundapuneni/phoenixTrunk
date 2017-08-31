<?php

/**
 * The file for the Integration ToolboxController
 *
 * @category    Toolbox
 * @package     Integration
 * @subpackage  Controller
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.4
 * @since       File available since release 13.4
 * @author      A. Tate <atate@travelclick.com>
 * @filesource
 */

namespace Integration\Controller;

use Toolbox\Mvc\Controller\AbstractToolboxController;
use Zend\View\Model\ViewModel;
use Integration\Form\HotelForm;
use Integration\Form\RoomForm;
use Integration\Form\RateForm;
use Integration\Form\AddonForm;
use Zend\Mvc\MvcEvent;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

/**
 * Integration ToolboxController
 *
 * This is the primary controller to use for Toolbox actions for the Integration module.
 * There are two other controllers that are used by this module in Toolbox, the GroupsController and the PermissionsController.
 * Anything that involves users themselves (including adding users to or removing them from groups) can be found here.
 *
 * @category    Toolbox
 * @package     Integration
 * @subpackage  Controller
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.4
 * @since       File available since release 13.4
 * @author      A. Tate <atate@travelclick.com>
 */
class ToolboxController extends AbstractToolboxController
{
    /**
     * The base admin level for actions. Each action (or task within an action)
     * can require a higher adminLevel, but this is the lowest allowed.
     *
     * @var string
     */
    protected $baseAdminLevel = \Users\Service\Acl::PERMISSIONS_RESOURCE_ADMIN;

    /**
     * indexAction function
     *
     * The Index Action
     * @access public
     * @return ViewModel $viewModel
     */
    public function indexAction()
    {
        return $this->hotelsAction();
    }

    /**
     * thisIsARequestToImportWebServiceData function
     *
     * @access protected
     * @static
     * @return $post 
     */
    protected static function thisIsARequestToImportWebServiceData()
    {
        return ($_POST && isset($_POST['import']));
    }

    /**
     * setTimeAndMemoryLimitsForIntegration function
     *
     * @access protected
     * @static
     *
     */
    protected static function setTimeAndMemoryLimitsForIntegration()
    {
        ini_set('max_execution_time', 300);
        ini_set('memory_limit', '128M');
    }

    /**
     * hotelsAction function
     *
     * @access public
     * @return mixed $viewModel
     *
     */
    public function hotelsAction()
    {
        self::setTimeAndMemoryLimitsForIntegration();

        $viewManager = $this->getServiceLocator()->get('view-manager');
        $integrationManager = $this->getServiceLocator()->get('integration-manager');

        $taskViewModel = new ViewModel();
        $taskViewModelResult = new ViewModel();

        $taskViewModel->title = "OWS Hotels Integration";
        $taskViewModel->formAction = array('action' => 'hotels');
        $taskViewModel->setTemplate('integration/toolbox/import');
        $taskViewModel->setVariable('taskResult', null);

        $taskViewModelResult->hotels = $integrationManager->getHotels();
        $taskViewModelResult->languages = $integrationManager->getLanguages();
        $taskViewModelResult->properties = $integrationManager->getProperties();

        /**
         * Create our impor form
         */
        $taskViewModel->form = new HotelForm(
                'hotels', $taskViewModelResult->properties, $taskViewModelResult->languages
        );

        $taskViewModelResult->results = $results = array();

        if (self::thisIsARequestToImportWebServiceData()) {
            $taskViewModel->form->setData($this->getRequest()->getPost());

            $taskViewModel->title = "{$taskViewModel->title} Results";
            $taskViewModelResult->setTemplate('integration/toolbox/hotels');

            $options['Approve'] = $this->getRequest()->getPost('approve', array());
            $options['Hotel'] = $this->getRequest()->getPost('hotel', array());
            $options['Language'] = $this->getRequest()->getPost('language', array());
            $options['Criteria'] = $this->getRequest()->getPost('criteria', '');
            $options['Recursive'] = (bool) $this->getRequest()->getPost('recursive');
            $options['DryRun'] = (bool) $this->getRequest()->getPost('dryrun');

            foreach ($options['Language'] as $language) {
                $service = $this->getServiceLocator()->get('phoenix-properties');
                $removeList = $integrationManager->getHotelsArray($service);

                foreach ($taskViewModelResult->hotels as $code => $name) {
                    unset($removeList[$code]);
                    if ($integrationManager->canHotelBeImportedOrUpdated($code, $options)) {
                        /**
                         * Just the the results from integration manger
                         */
                        $results[] = $integrationManager::importHotel($code, $language, $options);
                    }
                }

                $removeList = $integrationManager->removeItems($removeList, $options, $service);
            }
            $taskViewModelResult->setVariable('hotelParams', $options['Hotel']);
            $taskViewModelResult->setVariable('languageParams', $options['Language']);
            $taskViewModelResult->options = $options;
            $taskViewModelResult->removeList = $removeList;
            $taskViewModelResult->results = $results;

            $taskViewModel->addChild($taskViewModelResult, 'taskResult');
        }

        $viewModel = $this->getServiceLocator()->get('integration-layout');
        $viewModel->setVariables($viewManager->getViewModel()->getVariables());
        $viewModel->setVariable('currentTask', str_replace('Action', '', __FUNCTION__));

        //$viewModel->setVariable('removeList', $removeProperties);

        $viewModel->addChild($taskViewModel, 'taskContent');
        return $viewModel;
    }

    /**
     * hotelsAction function
     *
     * @access public
     * @return mixed $viewModel
     *
     */
    public function addonsAction()
    {
        self::setTimeAndMemoryLimitsForIntegration();

        $viewManager = $this->getServiceLocator()->get('view-manager');
        $integrationManager = $this->getServiceLocator()->get('integration-manager');

        $taskViewModel = new ViewModel();
        $taskViewModelResult = new ViewModel();

        $taskViewModel->title = "OWS Addons Integration";
        $taskViewModel->formAction = array('action' => 'addons');
        $taskViewModel->setTemplate('integration/toolbox/import');
        $taskViewModel->setVariable('taskResult', null);

        $taskViewModelResult->hotels = $integrationManager->getHotels();
        $taskViewModelResult->languages = $integrationManager->getLanguages();
        $taskViewModelResult->properties = $integrationManager->getProperties();

        $taskViewModelResult->results = $results = array();

        /**
         * Create our import form
         */
        $taskViewModel->form = new AddonForm(
                'addons', $taskViewModelResult->properties, $taskViewModelResult->languages
        );

        $taskViewModelResult->results = $results = array();

        if (self::thisIsARequestToImportWebServiceData()) {
            $taskViewModel->form->setData($this->getRequest()->getPost());

            $taskViewModel->title = "{$taskViewModel->title} Results";
            $taskViewModelResult->setTemplate('integration/toolbox/addons');

            $options['Approve'] = $this->getRequest()->getPost('approve', array());
            $options['Hotel'] = $this->getRequest()->getPost('hotel', array());
            $options['Language'] = $this->getRequest()->getPost('language', array());
            $options['Criteria'] = $this->getRequest()->getPost('criteria', '');
            $options['Override'] = (bool) $this->getRequest()->getPost('override');
            $options['DryRun'] = (bool) $this->getRequest()->getPost('dryrun');

            $service = $this->getServiceLocator()->get('phoenix-addons');
            $removeList = $integrationManager->getItemsArray($service,$options['Hotel']);
            foreach ($options['Language'] as $language) {


                foreach ($taskViewModelResult->properties as $code => $name) {

                    if ($integrationManager->canHotelBeImportedOrUpdated($code, $options)) {
                        /**
                         * We need to merge all the results in case we are importing from multiple hotels
                         */
                        $results = array_merge($results, $integrationManager::importHotelAddons($code, $language, $options));
                    }
                }

                foreach ($results as $result) {
                    unset($removeList[$result->code]);
                }
                $removeList = $integrationManager->removeItems($removeList, $options, $service);
            }
            $taskViewModelResult->setVariable('hotelParams', $options['Hotel']);
            $taskViewModelResult->setVariable('languageParams', $options['Language']);
            $taskViewModelResult->results = $results;
            $taskViewModelResult->options = $options;
            $taskViewModelResult->removeList = $removeList;
            $taskViewModel->addChild($taskViewModelResult, 'taskResult');
        }

        $viewModel = $this->getServiceLocator()->get('integration-layout');
        $viewModel->setVariables($viewManager->getViewModel()->getVariables());
        $viewModel->setVariable('currentTask', str_replace('Action', '', __FUNCTION__));
        $viewModel->addChild($taskViewModel, 'taskContent');

        return $viewModel;
    }

    /**
     * rooomsAction function
     *
     * @access public
     * @return mixed $viewModel
     *
     */
    public function roomsAction()
    {
        self::setTimeAndMemoryLimitsForIntegration();

        $viewManager = $this->getServiceLocator()->get('view-manager');
        $integrationManager = $this->getServiceLocator()->get('integration-manager');

        $taskViewModel = new ViewModel();
        $taskViewModelResult = new ViewModel();

        $taskViewModel->title = "OWS Rooms Integration";
        $taskViewModel->formAction = array('action' => 'rooms');
        $taskViewModel->setTemplate('integration/toolbox/import');
        $taskViewModel->setVariable('taskResult', null);

        $taskViewModelResult->hotels = $integrationManager->getHotels();
        $taskViewModelResult->languages = $integrationManager->getLanguages();
        $taskViewModelResult->properties = $integrationManager->getProperties();

        $taskViewModelResult->results = $results = array();

        /**
         * Create our import form
         */
        $taskViewModel->form = new RoomForm(
                'rooms', $taskViewModelResult->properties, $taskViewModelResult->languages
        );

        $taskViewModelResult->results = $results = array();

        if (self::thisIsARequestToImportWebServiceData()) {
            $taskViewModel->form->setData($this->getRequest()->getPost());

            $taskViewModel->title = "{$taskViewModel->title} Results";
            $taskViewModelResult->setTemplate('integration/toolbox/rooms');

            $options['Approve'] = $this->getRequest()->getPost('approve', array());
            $options['Hotel'] = $this->getRequest()->getPost('hotel', array());
            $options['Language'] = $this->getRequest()->getPost('language', array());
            $options['Criteria'] = $this->getRequest()->getPost('criteria', '');
            $options['Override'] = $this->getRequest()->getPost('override');
            $options['DryRun'] = $this->getRequest()->getPost('dryrun');

            $service = $this->getServiceLocator()->get('phoenix-rooms');
            $removeList = $integrationManager->getItemsArray($service,$options['Hotel']);
            foreach ($options['Language'] as $language) {
                foreach ($taskViewModelResult->properties as $code => $name) {
                    if ($integrationManager->canHotelBeImportedOrUpdated($code, $options)) {
                        /**
                         * We need to merge all the results in case we are importing from multiple hotels
                         */
                        $results = array_merge($results, $integrationManager::importHotelRooms($code, $language, $options));
                    }
                }
            }
            foreach ($results as $result) {
                unset($removeList[$result->code]);
            }
            $removeList = $integrationManager->removeItems($removeList, $options, $service);

            $taskViewModelResult->setVariable('hotelParams', $options['Hotel']);
            $taskViewModelResult->setVariable('languageParams', $options['Language']);
            $taskViewModelResult->results = $results;
            $taskViewModelResult->options = $options;
            $taskViewModelResult->removeList = $removeList;
            $taskViewModel->addChild($taskViewModelResult, 'taskResult');
        }

        $viewModel = $this->getServiceLocator()->get('integration-layout');
        $viewModel->setVariables($viewManager->getViewModel()->getVariables());
        $viewModel->setVariable('currentTask', str_replace('Action', '', __FUNCTION__));
        $viewModel->addChild($taskViewModel, 'taskContent');

        return $viewModel;
    }

    /**
     * rooomsAction function
     *
     * @access public
     * @return mixed $viewModel
     *
     */
    public function ratesAction()
    {
        self::setTimeAndMemoryLimitsForIntegration();

        $viewManager = $this->getServiceLocator()->get('view-manager');
        $integrationManager = $this->getServiceLocator()->get('integration-manager');

        $taskViewModel = new ViewModel();
        $taskViewModelResult = new ViewModel();

        $taskViewModel->title = "OWS Rates Integration";
        $taskViewModel->formAction = array('action' => 'rates');
        $taskViewModel->setTemplate('integration/toolbox/import');
        $taskViewModel->setVariable('taskResult', null);

        $taskViewModelResult->hotels = $integrationManager->getHotels();
        $taskViewModelResult->languages = $integrationManager->getLanguages();
        $taskViewModelResult->properties = $integrationManager->getProperties();

        $taskViewModelResult->results = $results = array();

        /**
         * Create our import form
         */
        $taskViewModel->form = new RateForm(
                'rates', $taskViewModelResult->properties, $taskViewModelResult->languages
        );

        $taskViewModelResult->results = $results = array();

        if (self::thisIsARequestToImportWebServiceData()) {
            $taskViewModel->form->setData($this->getRequest()->getPost());

            $taskViewModel->title = "{$taskViewModel->title} Results";
            $taskViewModelResult->setTemplate('integration/toolbox/rates');

            $options['Approve'] = $this->getRequest()->getPost('approve', array());
            $options['Hotel'] = $this->getRequest()->getPost('hotel', array());
            $options['Language'] = $this->getRequest()->getPost('language', array());
            $options['Criteria'] = $this->getRequest()->getPost('criteria', '');
            $options['Method'] = $this->getRequest()->getPost('Method', 'query-rate');
            $options['StartDate'] = $this->getRequest()->getPost('StartDate', null);
            $options['EndDate'] = $this->getRequest()->getPost('EndDate', null);
            $options['Recursive'] = (bool) $this->getRequest()->getPost('recursive');
            $options['Override'] = (bool) $this->getRequest()->getPost('override');
            $options['DryRun'] = (bool) $this->getRequest()->getPost('dryrun');

            $service = $this->getServiceLocator()->get('phoenix-rates');
            $removeList = $integrationManager->getItemsArray($service,$options['Hotel']);
            foreach ($options['Language'] as $language) {

                foreach ($taskViewModelResult->properties as $code => $name) {
                    if ($integrationManager->canHotelBeImportedOrUpdated($code, $options)) {
                        /**
                         * We need to merge all the results in case we are importing from multiple hotels
                         */
                        $results = array_merge($results, $integrationManager::importHotelRates($code, $language, $options));
                        // echo('<pre>'); print_r($results); echo('</pre>');
                        //exit();
                        
                    }
                }
            }
            foreach ($results as $result) {
                            unset($removeList[$result->code]);
                        }
                        $removeList = $integrationManager->removeItems($removeList, $options, $service);
            
            $taskViewModelResult->setVariable('hotelParams', $options['Hotel']);
            $taskViewModelResult->setVariable('languageParams', $options['Language']);
            $taskViewModelResult->results = $results;
            $taskViewModelResult->options = $options;
            $taskViewModelResult->removeList = $removeList;
            $taskViewModel->addChild($taskViewModelResult, 'taskResult');
        }

        $viewModel = $this->getServiceLocator()->get('integration-layout');
        $viewModel->setVariables($viewManager->getViewModel()->getVariables());
        $viewModel->setVariable('currentTask', str_replace('Action', '', __FUNCTION__));
        $viewModel->addChild($taskViewModel, 'taskContent');

        return $viewModel;
    }

}
