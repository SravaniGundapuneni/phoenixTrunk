<?php

/**
 * SeoFileUpload ToolboxController
 * 
 * The ToolboxController for the SeoFileUpload Module
 * 
 * If it is a toolbox action for the seoFileUpload module.
 *
 * @category           Toolbox
 * @package            SeoFileUpload
 * @subpackage         Controllers
 * @copyright          Copyright (c) 2013 TravelClick, Inc (http://travelclick.com)
 * @license            All Rights Reserved
 * @version            Release 13.5
 * @since              File available since release 13.5
 * @author             Kevin Davis <kedavis@travelclick.com>
 * @author             Andrew Tate <atate@travelclick.com>
 * @filesource
 */

namespace SeoFileUpload\Controller;

use ListModule\Controller\ToolboxController as ListModuleToolbox;
use Zend\View\Model\ViewModel;
use SeoFileUpload\Form\SeoFileUploadForm;
use SeoFileUpload\Form\SeoFileUploadUploadForm as UploadForm;

class ToolboxController extends ListModuleToolbox {

    const DEFAULT_NOITEM_ROUTE = 'seoFileUpload-toolbox';

    protected $baseAdminLevel = \Users\Service\Acl::PERMISSIONS_RESOURCE_ADMIN;
    protected $tasksMenu = array('index' => 'Upload Files', 'editlist' => 'SEO Files', 'robotsFile' => 'Robot Files');

    public function indexAction() {
        
        $viewModel = parent::editlistAction();

        $children = $viewModel->getChildren();

        $taskViewModel = $children[0];

        $taskViewModel->setTemplate('seo-file-upload/toolbox/index');

        return $viewModel;
    }

    public function editlistAction() {
        
        $service = $this->getServiceLocator()->get('phoenix-seofileupload');
        //pass action for filtering file type
        $service->_action = 'editlist';
        
        $viewModel = parent::editlistAction();
        
        $children = $viewModel->getChildren();

        $taskViewModel = $children[0];

        $taskViewModel->setTemplate('seo-file-upload/toolbox/edit-list');
        
        //$taskViewModel->abc = 'text/xml';

        return $viewModel;
    }
     public function robotsFileAction() {
         
        $service = $this->getServiceLocator()->get('phoenix-seofileupload');
        //pass action for filtering file type
        $service->_action = 'robotsFile';
                 
        $viewModel = parent::editlistAction();

        $children = $viewModel->getChildren();

        $taskViewModel = $children[0];

        $taskViewModel->setTemplate('seo-file-upload/toolbox/robots-list');

        return $viewModel;
    }

    /**
     * deleteAction
     *
     * Delete a file
     * 
     * @return redirect
     */
    public function deleteFileAction() {
        $service = $this->getServiceLocator()->get('phoenix-seofileupload');
        //var_dump($seoFiles);
        $fileId = (int) $this->params()->fromRoute('itemId', 0);
        //var_dump($fileId);
        //return;
        if (!$fileId) {

            return $this->redirect()->toRoute('seoFileUpload-toolbox', array('action' => 'index'));
        }

        try {
           
            $file = $service->getItemBy(array('id' => $fileId));
            //echo 'GOT file <Br/>';
            //var_dump($file);
            $redirectUrl = $this->url()->fromRoute('seoFileUpload-toolbox', array('action' => 'editList'));

            echo "redirect url: $redirectUrl<br>";
            if ($file) {
                $redirectUrl .= '?fileDeleted=' . $fileId;
                $fileName = $file->getName();
                $file->delete();
                $mediaRoot = SITE_PATH . '/' . $this->getServiceLocator()->get('mergedConfig')->get('mediaRoot', '');
                $path = $this->params()->fromPost('path', '');
                if ($path == '/') {
                    $path = '';
                } else {
                    if (mb_substr($path, mb_strlen($path) - 1, 1) == '/') {
                        $path = mb_substr($path, 0, mb_strlen($path) - 1);
                    }
                }

                $service->removeFile($fileName, $mediaRoot . $path);
            }
            //echo 'deleted<br>';
            //die();
            //return;
            return $this->redirect()->toUrl($redirectUrl);
        } catch (\Exception $ex) {


            //var_dump($ex);
            return $this->redirect()->toRoute('seoFileUpload-toolbox', array(
                        'action' => 'index'
            ));
        }
    }

}
