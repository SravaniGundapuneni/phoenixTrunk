<?php

namespace SeoFileUpload\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Phoenix\StdLib\FileHandling;

class SocketsController extends AbstractActionController {

    const IMAGESSWITCH_ENTITY_NAME = 'ImageSwitch\Entity\ImageSwitch';

    public function indexAction() {
        
    }

    public function seofileuploaduploadAction() {

        $seoFileUploadService = $this->getServiceLocator()->get('phoenix-seofileupload');

        $mediaRoot = SITE_PATH;

        $redirectUrl = $this->params()->fromPost('redirectUrl', '');

        $path = $this->params()->fromPost('path', '');

        $file = $this->getRequest()->getFiles()->toArray();

        $success = 1;
        $errorMessage = '';
        $newFiles = array();

        $mediaUpload = $file['mediaUpload'];



        if ($mediaUpload['error'] == UPLOAD_ERR_OK) {
            $origName = $mediaUpload['name'];

            $parts = explode('.', $origName);
            $lastPart = end($parts);
            $extension = strtolower($lastPart);

            if ($seoFileUploadService->isValidFileType($origName)) {
                if ($path == '/') {
                    $path = '';
                } else {
                    if (mb_substr($path, mb_strlen($path) - 1, 1) == '/') {
                        $path = mb_substr($path, 0, mb_strlen($path) - 1);
                    }
                }

                $name = $seoFileUploadService->formatFileName($origName);
                $dbPath = FileHandling::getDbFormattedPath($mediaRoot . $path);

                $fileParameters = array(
                    'name' => $name,
                    'origName' => $origName,
                    'path' => $dbPath
                );

                $savePath = $mediaRoot;

                //Check to see if file already exists
                $fileItems = $seoFileUploadService->getFilesBy($fileParameters, array(), 1);

                if (count($fileItems)) {
                    $fileItem = $fileItems[0];
                    $seoFileUploadService->removeThumbs($name, $mediaRoot . $path);

                    $fileItem->saveFile($mediaUpload['tmp_name'], $savePath);

                    $fileItem->getEntity()->setModified(new \DateTime());

                    $fileItem->save();
                } else {
                    $uploadData = array(
                        'name' => $name,
                        'origName' => $origName,
                        'path' => $savePath,
                        'tmpName' => $mediaUpload['tmp_name'],
                        'type' => $mediaUpload['type'],
                    );

                    $fileItem = $seoFileUploadService->save(null, $uploadData);

                    //Add code to attach this uploaded file to an imageSwitch entity.
                }

                if ($redirectUrl) {
                    return $this->redirect()->toUrl($redirectUrl);
                }

                $newFiles[] = $name;
            } else {
                $success = 0;
                $errorMessage = "The file extension <span style=\"font-weight:bold\">.$extension</span> is not in the list of accepted media file types.";
            }
        } else {
            $success = 0;
            $errorMessage = FileHandling::getFileErrorMessage($file['mediaUpload']['error']);
        }

        $data = array(
            'success' => $success,
            'files' => $newFiles,
            'errorMessage' => $errorMessage
        );

        return new JsonModel($data);
    }

}
