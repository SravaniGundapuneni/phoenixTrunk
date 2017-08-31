<?php 
namespace MediaManager\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Phoenix\StdLib\FileHandling;
use MediaManager\Classes\Crop;
use MediaManager\Classes\MediaFiles;

class SocketsController extends AbstractActionController
{
    const MEDIAMANAGERIMAGE_ENTITY_NAME             = 'MediaManager\Entity\MediaManagerImage';
    const REGEX_ALPHA_NUMERIC_SPACE_UNDERSCORE      = '/[^A-Za-z0-9 _]/';
    const REGEX_ALPHA_NUMERIC_SPACE_UNDERSCORE_DASH = '/[^A-Za-z0-9 _-]/';

    public function indexAction() {}

    public function addAttachmentsAction()
    {
        $mmService = $this->getServiceLocator()->get('phoenix-mediamanager');
        $options   = $this->getAttachmentOptions();

        try {
            $this->hasValidAttachmentOptions($options);
            $this->checkAuthorization();
            $this->checkReadOnly();
            $this->checkIsPost();

            $responseData = $mmService->attachFiles($options);
            $status       = 'success';
        } catch(\Exception $e) {
            $responseData = array();
            $status       = 'error';
        }

        return new JsonModel(array('status' => $status, 'data' => $responseData));
    }

    public function addFolderAction()
    {
        try {
            $this->checkAuthorization();
            $this->checkReadOnly();
            $this->checkIsPost();

            $responseData = $this->addNewFolder();
            $status       = 'success';
        } catch (\Exception $e) {
            $responseData = array();
            $status       = 'error';
        }

        return new JsonModel(array('status' => $status, 'data' => $responseData));
    }

    public function cropAction()
    {
        $mmService = $this->getServiceLocator()->get('phoenix-mediamanager');

        try {
            $this->checkAuthorization();
            $this->checkReadOnly();
            $this->checkIsPost();

            $croppedFile  = $this->getCropOptions();
            $responseData = $mmService->upload($croppedFile);
            $mmService->createThumbsForImage($croppedFile);
            $status       = 'success';
        } catch(\Exception $e) {
            $responseData = array(
                'message' => $e->getMessage()
            );
            $status       = 'error';
        }

        return new JsonModel(array('status' => $status, 'data' => $responseData));
    }

    public function deleteFileAction()
    {
        $fileId = (int) $this->params()->fromPost('fileId', '');

        try {
            $this->checkAuthorization();
            $this->checkReadOnly($fileId);
            $this->checkIsPost();

            $this->getServiceLocator()->get('phoenix-mediamanager')->deleteFile($fileId);
            $status = 'success';
        } catch (\Exception $e) {
            $status = 'error';
        }

        return new JsonModel(array('status' => $status));
    }

    public function deleteFolderAction()
    {
        $removeBaseFolder = true;
        $mmService        = $this->getServiceLocator()->get('phoenix-mediamanager');
        $mmService->setConfig($this->getServiceLocator()->get('mergedConfig'));

        try {
            $this->checkAuthorization();
            $this->checkReadOnly();
            $this->checkIsPost();

            $responseData = $mmService->deleteFolder($this->getFormattedPath($removeBaseFolder));
            $status       = 'success';
        } catch(\Exception $e) {
            $responseData = array();
            $status       = 'error';
        }

        return new JsonModel(array('status' => $status, 'data' => $responseData));
    }

    public function editImageAction()
    {
        $alt    = preg_replace(self::REGEX_ALPHA_NUMERIC_SPACE_UNDERSCORE_DASH, '', $this->params()->fromPost('alt'));
        $fileId = (int) $this->params()->fromPost('fileId');

        try {
            $this->checkAuthorization();
            $this->checkReadOnly($fileId);
            $this->checkIsPost();

            $this->setAltText($fileId, $alt);
            $status = 'success';
        } catch(\Exception $e) {
            $status = 'error';
        }

        return new JsonModel(array('status' => $status));
    }

    public function getResponsiveImageAction()
    {
        $riService  = $this->getServiceLocator()->get('phoenix-mediamanager-responsive-image');
        $fileId     = (int) $this->params()->fromQuery('fileId');
        $moduleName = preg_replace(self::REGEX_ALPHA_NUMERIC_SPACE_UNDERSCORE_DASH, '', $this->params()->fromQuery('name'));

        try {
            $this->checkAuthorization();
            $this->checkFileId($fileId);

            $responseData = $riService->getResponsiveImage($fileId, $moduleName);
            $status       = 'success';
        } catch(\Exception $e) {
            $responseData = array();
            $status       = 'error';
        }

        return new JsonModel(array('status' => $status, 'data' => $responseData));
    }

    public function loadAltTextAction()
    {
        $mmImageService = $this->getServiceLocator()->get('phoenix-mediamanager-image');
        $fileId         = (int) $this->params()->fromQuery('fileId');

        try {
            $this->checkAuthorization();
            $this->checkReadOnly($fileId); // because only those attempting to edit can view
            $this->checkFileId($fileId);

            $responseData = $mmImageService->getAltText($fileId);
            $status       = 'success';
        } catch(\Exception $e) {
            $responseData = '';
            $status       = 'error';    
        }

        return new JsonModel(array('status' => $status, 'data' => $responseData));
    }

    public function loadFilesAction()
    {
        $mmService = $this->getServiceLocator()->get('phoenix-mediamanager');

        try {
            $this->checkAuthorization();

            $responseData['files']   = $mmService->getFileList();
            $responseData['folders'] = $mmService->getFolders();
            $status                  = 'success';
        } catch(\Exception $e) {
            $responseData = array();
            $status       = 'error';
        }

        return new JsonModel(array('status' => $status, 'data' => $responseData));
    }

    public function loadOpenGraphAction()
    {
        $ogService = $this->getServiceLocator()->get('phoenix-mediamanager-opengraph');
        $fileId    = (int) $this->params()->fromQuery('fileId');

        try {
            $this->checkAuthorization();
            $this->checkReadOnly($fileId); // because only those attempting to edit can view
            $this->checkFileId($fileId);

            $responseData = $ogService->getOpenGraphTags($fileId);
            $status       = 'success';
        } catch(\Exception $e) {
            $responseData = array();
            $status       = 'error'; 
        }

        return new JsonModel(array('status' => $status, 'data' => $responseData));
    }

    public function loadSchemaDotOrgAction()
    {
        $sdoService = $this->getServiceLocator()->get('phoenix-mediamanager-schemadotorg');
        $fileId     = (int) $this->params()->fromQuery('fileId');

        try {
            $this->checkAuthorization();
            $this->checkReadOnly($fileId); // because only those attempting to edit can view
            $this->checkFileId($fileId);

            $responseData = $sdoService->getItemprop($fileId);
            $status       = 'success';
        } catch(\Exception $e) {
            $responseData = '';
            $status       = 'error';
        }
        return new JsonModel(array('status' => $status, 'data' => $responseData));
    }

    public function removeAttachmentAction()
    {
        try {
            $this->checkAuthorization();
            $this->checkReadOnly();
            $this->checkIsPost();

            $responseData = $this->removeAttachment();
            $status       = 'success';
        } catch(\Exception $e) {
            $responseData = array(); 
            $status       = 'error';
        }

        return new JsonModel(array('status' => $status, 'data' => $responseData));
    }

    public function saveModuleAltTextAction()
    {
        $options             = array();
        $options['attachId'] = (int) $this->params()->fromPost('attachId');
        $options['altText']  = preg_replace(self::REGEX_ALPHA_NUMERIC_SPACE_UNDERSCORE_DASH, '', $this->params()->fromPost('altText'));
        $amfService          = $this->getServiceLocator()->get('phoenix-attachedmediafiles');

        try {
            $this->checkAuthorization();
            $this->checkIsPost();
            $this->checkReadOnly();

            $amfService->saveModuleAttachmentAltText($options);
            $status = 'success';
        } catch(\Exception $e) {
            $status = 'error';
        }

        return new JsonModel(array('status' => $status));
    }

    public function saveOpenGraphAction()
    {
        $fileId = (int) $this->params()->fromPost('fileId');

        try {
            $this->checkAuthorization();
            $this->checkReadOnly($fileId);
            $this->checkIsPost();

            $this->saveOpenGraphTags($fileId);
            $status = 'success';
        } catch(\Exception $e) {
            $status = 'error'; 
        }

        return new JsonModel(array('status' => $status));
    }

    public function saveSchemaDotOrgAction()
    {
        $sdoService = $this->getServiceLocator()->get('phoenix-mediamanager-schemadotorg');
        $fileId     = (int) $this->params()->fromPost('fileId');
        $itemprop   = $this->params()->fromPost('itemprop');

        try {
            $this->checkAuthorization();
            $this->checkReadOnly($fileId);
            $this->checkIsPost();

            $sdoService->saveItemprop($fileId, $itemprop);
            $status = 'success';
        } catch(\Exception $e) {
            $status = 'error'; 
        }

        return new JsonModel(array('status' => $status));
    }

    public function setAttachmentOrderAction()
    {
        $attachedMedia = $this->getServiceLocator()->get('phoenix-attachedmediafiles');

        $options = array(
            'orderedItems' => $this->params()->fromPost('orderedItems', array()),
            'parentItemId' => $this->params()->fromPost('parentItemId', 0),
            'parentModule' => $this->params()->fromPost('parentModule', '')
        );

        try {
            $this->checkAuthorization();
            $this->checkReadOnly();
            $this->checkIsPost();

            $attachedMedia->saveAttachmentOrder($options);
            $status = 'success';
        } catch(\Exception $e) {
            $status = 'error';
        }

        return new JsonModel(array('status' => $status));
    }

    public function uploadAction()
    {
        $mmService = $this->getServiceLocator()->get('phoenix-mediamanager');

        try {
            $this->checkAuthorization();
            $this->checkReadOnly();
            $this->checkIsPost();
            
            $responseData = $this->upload(); 
            $success      = 'success';
        } catch(\Exception $e) {
            $responseData = array();
            $success      = 'error';
        }

        return new JsonModel(array('status' => $status, 'data' => $responseData));
    }

    public function unzipFilesAction()
    {
        $mmService = $this->getServiceLocator()->get('phoenix-mediamanager');
        $fileId    = $this->params()->fromPost('fileId', '');

        try {
            $this->checkAuthorization();
            $this->checkIsPost();
            $this->checkIsDeveloper();

            $responseData = $mmService->unzipFiles($fileId, $this->getBasicFileOptions());
            $status       = 'success';
        } catch(\Exception $e) {
            $responseData = array();
            $status       = 'error';

        }

        return new JsonModel(array('status' => $status, 'data' => $responseData));
    }

    private function addNewFolder()
    {
        $mmService  = $this->getServiceLocator()->get('phoenix-mediamanager');
        $path       = $this->getFormattedPath();
        $folderName = preg_replace(self::REGEX_ALPHA_NUMERIC_SPACE_UNDERSCORE, '', $this->params()->fromPost('name'));
        return $mmService->addNewFolder($path, $folderName);
    }

    private function cropImage($post)
    {
        $cropData     = array();
        $croppedImage = new Crop\CroppedImage($post);
        $cropData     = $croppedImage->generateImage();

        if (empty($cropData)) {
            throw new \Exception('500 Internal Server Error');
        }

        return $cropData;
    }

    private function getAttachmentOptions()
    {
        $options = array();
        $options['parentModule']    = $this->params()->fromPost('parentModule', '');
        $options['parentItemId']    = (int) $this->params()->fromPost('parentItemId', 0);
        $options['attachments']     = $this->params()->fromPost('attachments', ''); 
        $options['currentUser']     = $this->getServiceLocator()->get('phoenix-users-current');
        $options['currentProperty'] = $this->getServiceLocator()->get('currentProperty');
        return $options;
    }

    private function getBasicFileOptions()
    {
        $currentProperty = $this->getServiceLocator()->get('currentProperty');
        $propertyId      = (is_object($currentProperty)) ? $currentProperty->getId() : 0;
        $currentUser     = $this->getServiceLocator()->get('phoenix-users-current');       
        $currentUserId   = $currentUser->getId();

        return array(
            'userId'     => $currentUserId,
            'propertyId' => $propertyId
        );
    }

    private function getCropOptions()
    {
        $request              = $this->getRequest(); 
        $post                 = array_merge_recursive($request->getPost()->toArray());
        $data                 = $this->cropImage($post);
        $options              = array_merge($data, $this->getBasicFileOptions());
        $options['mediaRoot'] = $this->getMediaRoot();

        return new MediaFiles\CroppedFile($options);
    }

    private function getFormattedPath($removeBaseFolder = false)
    {
        $path = $this->params()->fromPost('path', '');
        $path = urldecode($path);

        if ($removeBaseFolder) {
            if (substr($path, 0, 2) == 'd/') {
                $path = substr($path, 2);
            }
        } else {
            $path = ltrim($path, '/');
        }

        return $path;
    }

    private function getMediaManagerSizes()
    {
        $mergedConfig = $this->getServiceLocator()->get('mergedConfig');
        $mmService    = $this->getServiceLocator()->get('phoenix-mediamanager');
        $mmService->setConfig($mergedConfig);
        return $mmService->getConfig()->get(array('images', 'mediaManagerSizes'), array());
    }

    private function getMediaRoot()
    {
        $mergedConfig = $this->getServiceLocator()->get('mergedConfig');
        return SITE_PATH . '/' . $mergedConfig->get(array('mediaRoot'), 'd/');
    }

    private function getUploadedFileOptions()
    {
        $currentProperty  = $this->getServiceLocator()->get('currentProperty');
        $propertyId       = (is_object($currentProperty)) ? $currentProperty->getId() : 0;
        $currentUser      = $this->getServiceLocator()->get('phoenix-users-current');
        $removeBaseFolder = true;

        return array(
            'mediaRoot'  => $this->getMediaRoot(),
            'path'       => $this->getFormattedPath($removeBaseFolder),
            'file'       => $this->getRequest()->getFiles()->toArray(),
            'propertyId' => $propertyId,
            'userId'     => $currentUser->getId()
        );
    }

    private function hasValidAttachmentOptions($options)
    {
        if (empty($options['attachments'])) {
            throw new \Exception('400 Bad Request');
        }
    }

    private function removeAttachment()
    {
        $attachedMedia = $this->getServiceLocator()->get('phoenix-attachedmediafiles');
        $options       = $this->getAttachmentOptions();

        $this->hasValidAttachmentOptions($options); 

        $attachedMedia->setParentInfo($options);
        $attachedMedia->removeFile($options['attachments'][0]);

        return array('fileId' => $options['attachments'][0]);
    }

    // we will have to validate tag data, create a regex that also allows colons
    private function saveOpenGraphTags($fileId)
    {
        $ogService = $this->getServiceLocator()->get('phoenix-mediamanager-opengraph');
        $tagData   = $this->params()->fromPost('tagData');

        // update tags
        foreach ($tagData as $tagDatum) {
            if (!empty($tagDatum['ogproperty']) && !empty($tagDatum['content'])) {
                $ogService->saveOpenGraphTags($fileId, $tagDatum);  
            }
        }

        // clean up removed tags
        $ogService->deleteOpenGraphTags($fileId, $tagData);
    }

    private function setAltText($fileId, $alt)
    {
        $mmImageService = $this->getServiceLocator()->get('phoenix-mediamanager-image');
        $mmImageService->setAltText($fileId, $alt);
    }

    private function upload()
    {
        $mmService    = $this->getServiceLocator()->get('phoenix-mediamanager');
        $options      = $this->getUploadedFileOptions();
        $uploadedFile = new MediaFiles\UploadedFile($options);
        return $mmService->upload($uploadedFile);
    }

    private function checkReadOnly($fileId = null)
    {
        $mmPermissions = $this->getServiceLocator()->get('phoenix-mediamanager-permissions');
        $isReadOnly    = $mmPermissions->isReadOnly($fileId);

        if ($isReadOnly === 'true') {
            throw new \Exception('401 Unauthorized');
        }
    }

    private function checkIsPost()
    {
        $request = $this->getRequest();
        if (!$request->isPost()) {
            throw new \Exception('405 Method Not Allowed');
        }
    }

    private function checkAuthorization()
    {
        $currentUser      = $this->getServiceLocator()->get('phoenix-users-current');
        $id               = $currentUser->getId();
        if (empty($id)) {
            throw new \Exception('401 Unauthorized');
        }
    }

    private function checkFileId($fileId)
    {
        if (!$fileId) {
            throw new \Exception('400 Bad Request');  
        }
    }

    private function checkIsDeveloper()
    {
       $currentUser = $this->getServiceLocator()->get('phoenix-users-current');
        if (!($currentUser->getUserEntity()->getType() === 2)) {
            throw new \Exception('403 Forbidden');
        }
    }
}
