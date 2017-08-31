<?php

/**
 * PhoenixRates ToolboxController
 *
 * The ToolboxController for the PhoenixRates Module
 *
 * If it is a toolbox action for the phoenixRates module, it goes here.
 *
 * @category    Toolbox
 * @package     PhoenixRates
 * @subpackage  Controllers
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.4
 * @since       File available since release 13.4
 * @author      A. Tate <atate@travelclick.com>
 * @filesource
 */

namespace PhoenixRates\Controller;

use Zend\View\Model\ViewModel;

/**
 * PhoenixRates ToolboxController
 *
 * The ToolboxController for the PhoenixRates Module
 *
 * If it is a toolbox action for the phoenixRates module, it goes here.
 *
 * This will need to have a way of deciding whether to show all rates, or just the property for the current site
 * depending upon the user.
 *
 * @category    Toolbox
 * @package     PhoenixRates
 * @subpackage  Controllers
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.4
 * @since       Class available since release 13.4
 * @author      A. Tate <atate@travelclick.com>
 */
class ToolboxController extends \ListModule\Controller\ToolboxController
{

    /**
     * The base admin level for actions. Each action (or task within an action)
     * can require a higher adminLevel, but this is the lowest allowed.
     * 
     * @var string
     */
    protected $baseAdminLevel = \Users\Service\Acl::PERMISSIONS_RESOURCE_ADMIN;

    /**
     * This is to override the default listModule's tasksMenu
     */
    protected $tasksMenu = array(
        'editList' => 'Manage Items',
        //'addBrandItem' => 'Add Brand Rate'
        'additem' => 'Add New Rate',
        'editcategorylist' => 'Edit Categories',
    );

    protected $newBrandItem = false;


    public function __construct()
    {
        $this->modsing = 'Rate';
        parent::__construct();
    }

    public function addbranditemAction()
    {
        $this->newBrandItem = true;

        return $this->additemAction();
    }

    public function editcategorylistAction()
    {
        $modulesService = $this->getServiceLocator()->get('phoenix-modules');


        $module = $modulesService->getItemBy(array('name' => 'PhoenixRates'));

        $this->redirect()->toRoute('categories-toolbox',array('moduleId' => $module->getId()));        
    }

    public function doEditItem($moduleService, $itemForm, $viewModel = null, $newItem = false)
    {
        $membership = $itemForm->get('membership');
        $membershipService = $this->getServiceLocator()->get('phoenix-membership');

        $corporateProperty = $this->getServiceLocator()->get('corporateProperty');

        if ($newItem == true && $this->newBrandItem == true) {
            $hotelName = $itemForm->get('hotelName');
            $hotelName->setValue($corporateProperty->getName());

            $isBrand = new \Zend\Form\Element\Hidden('isBrand');
            $isBrand->setValue(true);
            $itemForm->add($isBrand);            
        }

        $membershipService->setMembershipLevelOptions($membership);

        $parentEditItem = parent::doEditItem($moduleService, $itemForm, $viewModel, $newItem);


        if ($parentEditItem instanceof ViewModel) {
            if ($newItem == true && $this->newBrandItem == true) {
                $subsite = $this->params()->fromRoute('subsite', '');

                if ($subsite) {
                    $subsite = substr($subsite, 1);
                } else {
                    $subsite = null;
                }

                $action = 'addBrandItem';

                $itemForm->setAttribute('action', $this->url()->fromRoute($this->defaultNoItemRoute, array_merge(array('action' => $action, 'subsite' => $subsite), $this->additionalRouteParams)));            
                
                if ($hotelName->getValue() == $corporateProperty->getName()) {
                    $code = $itemForm->get('code');

                    $code->setAttribute('readonly', false);

                    // $itemForm->remove('rateTypeCategory');

                    // $itemForm->remove();
                    // var_dump($code);
                    // die('DIE CALLED AT LINE: ' . __LINE__ . ' in FILE: ' . __FILE__);
                }
            }
        }
       
        return $parentEditItem;
    }

   

}
