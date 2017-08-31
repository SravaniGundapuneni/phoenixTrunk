<?php

/**
 * The file for the FieldsToolboxController class for the DynamicListModule
 *
 * @category    Toolbox
 * @package     DynamicListModule
 * @subpackage  Controllers
 * @copyright   Copyright (c) 2014 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 1.14
 * @since       File available since release 1.14
 * @author      A. Tate <atate@travelclick.com>
 * @filesource
 */

namespace HeroImages\Controller;

use ListModule\Controller\ToolboxController as BaseToolboxController;
use Zend\View\Model\ViewModel;

/**
 * The FieldsToolboxController class for the DynamicListModule
 *
 * @category    Toolbox
 * @package     DynamicListModule
 * @subpackage  Controllers
 * @copyright   Copyright (c) 2014 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 1.14
 * @since       File available since release 1.14
 * @author      A. Tate <atate@travelclick.com>
 */
class AttachmentToolboxController extends BaseToolboxController
{
    protected $tasksMenu = array('' => '');

    /*
     * The name of the primary service used by this controller
     */

    const MODULE_SERVICE_NAME = 'heroimageAttachments';


    /**
     * The base admin level for actions. Each action (or task within an action)
     * can require a higher adminLevel, but this is the lowest allowed.
     * 
     * @var string
     */    
    protected $baseAdminLevel = \Users\Service\Acl::PERMISSIONS_RESOURCE_ADMIN;

    /**
     * The template used for the editList action
     * @var string
     */
    protected $editListTemplate = 'phoenix-heroimages/attachments-toolbox/edit-list';

    /**
     * The title to use for the module associated with the field(s)
     * @var string
     */
    protected $moduleTitle = '';

    /**
     * The baseId name
     */
    protected $baseIdTitle = 'moduleId';

    /**
     * __construct
     *
     * Class Constructor
     */
    public function __construct()
    {
        parent::__construct();
        //Set the Toolbox Route. This has to be done here, because it is set dynamically in
        //the parent class constructor
        $this->toolboxRoute = 'heroImages-attachmentToolbox';
    }

    /**
     * getTemplateName
     * 
     * @param  string $moduleName (this is used in this class, but it must maintain the structure of the parent)
     * @return string
     */
    protected function getTemplateName($moduleName)
    {
        return $this->editListTemplate;
    }

    /**
     * getModuleName
     * 
     * @return string
     */
    public function getModuleName()
    {
        return static::MODULE_SERVICE_NAME;
    }

    public function edititemAction()
    {
        //Set the defaultNoItemRoute, so redirects will work correctly
        $this->defaultNoItemRoute = $this->toolboxRoute;

        //Run the editItem action method
        $viewList = parent::edititemAction();

        //Return the result
        return $viewList;
    }

    public function attachmentInfoAction()
    {
        $serviceLocator = $this->getServiceLocator();
        $moduleName = $this->getModuleName($this->module);
        $moduleService = $serviceLocator->get("phoenix-$moduleName");

        $itemId = (int) $this->params()->fromRoute('itemId', $this->params()->fromPost('id', 0));

        //Return to the main screen of the module if no itemId
        if (!$itemId) {
            return $this->doRedirect('noItem');
        }

        //Get the itemModel associated with the given itemId
        try {
            if (!($itemModel = $moduleService->getItemBy(array('attachmentId' => $itemId)))) {
                $itemModel = $moduleService->createModel();
                $moduleService->save($itemModel, array('attachmentId' => $itemId));
            }
            return $this->redirect()->toRoute($this->toolboxRoute, array('action' => 'editItem', 'itemId' => $itemModel->getId()));
        } catch (\Exception $ex) {
            return $this->doRedirect('noItem');
        }
    }

    public function doEditItem($moduleService, $itemForm, $viewModel = null, $newItem = false)
    {

        $itemId = (int) $this->params()->fromRoute('itemId', $this->params()->fromPost('id', 0));
        $itemModel = $moduleService->getItem($itemId);
        $itemEntity = $itemModel->getEntity();
        
        $attachmentId = $itemEntity->getAttachmentId();
        
         $serviceLocator = $this->getServiceLocator();
        $mediaFilesService = $serviceLocator->get("phoenix-attachedmediafiles");
        
         $mediaFilesService->setMediaManager( $serviceLocator->get('phoenix-mediamanager'));
        $mediaAttachment = $mediaFilesService->getItem($attachmentId);
        
        $parentId = $mediaAttachment->getParentItemId();
        return parent::doEditItem($moduleService, $itemForm, $viewModel, $newItem, 'heroImages-toolbox', array('itemId' => $parentId, 'action' => 'editItem'));
    }

}
