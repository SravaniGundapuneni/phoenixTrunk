<?php

/**
 * LinkRedirects ToolboxController
 *
 * The ToolboxController for the LinkRedirects Module
 *
 * If it is a toolbox action for the phoenixAttributes module, it goes here.
 *
 * @category    Toolbox
 * @package     LinkRedirects
 * @subpackage  Controllers
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.4
 * @since       File available since release 13.4
 * @author      Grou <jrubio@travelclick.com>
 * @filesource
 */

namespace LinkRedirects\Controller;

use Zend\View\Model\ViewModel;

/**
 * LinkRedirects ToolboxController
 *
 * The ToolboxController for the LinkRedirects Module
 *
 * If it is a toolbox action for the phoenixAttributes module, it goes here.
 *
 * This will need to have a way of deciding whether to show all attributes, or just the attribute for the current site
 * depending upon the user.
 *
 * @category    Toolbox
 * @package     LinkRedirects
 * @subpackage  Controllers
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.4
 * @since       Class available since release 13.4
 * @author      Sravani Gundapuneni <sgundapuneni@travelclick.com>
 */
class ToolboxController extends \ListModule\Controller\ToolboxController
{

    protected $baseAdminLevel = \Users\Service\Acl::PERMISSIONS_RESOURCE_ADMIN;
    protected $tasksMenu = array('urlList' => '404 URL List', 'addItem' => 'New Redirect', 'editList' => 'Manage Redirects', 'import' => 'Import Redirects');

    public function __construct()
    {
        $this->modsing = 'LinkRedirects';
        parent::__construct();
    }

    public function urlListAction()
    {
        $service = $this->getServiceLocator()->get('phoenix-linkredirects');
        $viewModel = parent::editlistAction();
        $children = $viewModel->getChildren();
        $taskViewModel = $children[0];
        $taskViewModel->items = $service->getList_404();
        //  var_dump($taskViewModel->items);
        $taskViewModel->setTemplate('phoenix-linkredirects/toolbox/url-list');
        return $viewModel;
    }

    public function importAction()
    {

        $request = $this->getRequest();
        $form = new \MediaManager\Form\MediaManagerUploadForm();
        $viewModel = new ViewModel();
        $viewModel->setTemplate('phoenix-linkredirects/toolbox/import');
        $viewModel->form = $form;
        $viewModel->formFinished = false;
        if ($this->request->isPost()) {

            $file = $this->params()->fromFiles('upload-file');
            $service = $this->getServiceLocator()->get('phoenix-linkredirects');
            $service->import($file);
            $viewModel->formFinished = true;
        }
        $form->get('upload-file')->setLabel('Import LinkRedirects File (csv). The file should have 3 columns Incoming Url, Redirect Url, Header Response Type');

        return $viewModel;
    }

}
