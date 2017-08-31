<?php
namespace MediaManager\Helper;

use Zend\View\Helper\AbstractHelper;

class MediaManagerUploadForm extends AbstractHelper
{
    private $parameters;

    public function __invoke()
    {
        $this->parameters = func_get_args();

        $currentPath = $this->getCurrentPath();
        $uploadAction = $this->getUploadAction();

        $form = <<<HTML
        <form action="$uploadAction" id="uploadActionForm" method="POST" name="frmUploadFile" enctype="multipart/form-data">
            File: <input type="file" name="mediaUpload" id="mediaUpload"><br />
            <input type="hidden" name="path" value="$currentPath"/>
            <input type="submit" name="btnSubmit" value="Submit" />
        </form> 
HTML;
        return $form;
    }

    private function getCurrentPath() {
        $currentPath = '/';

        if (isset($this->parameters[0]) && $this->parameters[0]) {
            $currentPath = $this->parameters[0];
        }

        return $currentPath;
    }

    private function getUploadAction() {
        return $this->getView()->basePathPrefix 
            . $this->getView()->url('mediaManager-sockets', array('action' => 'mediaManagerUpload'));
    }
}