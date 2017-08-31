<?php
namespace MediaManager\Helper;

use Zend\View\Helper\AbstractHelper;

class MediaManagerUploadDefaultForm extends AbstractHelper
{
    public function __invoke()
    {
        $parameters = func_get_args();

        $currentPath = '/';

        if (isset($parameters[0]) && $parameters[0]) {
            $currentPath = $parameters[0];
        }

        if (isset($parameters[1]) && $parameters[1]) {
            $redirectUrl = $parameters[1];
        } else {
            $redirectUrl = $this->getView()->basePathPrefix . $this->getView()->url('mediaManager-toolbox') . '/defaultImage?path=' . $currentPath;
        }

        $uploadAction = $this->getView()->basePathPrefix . $this->getView()->url('mediaManager-sockets', array('action' => 'mediaManagerUploadDefault'));

        $form = <<<HTML
        <form action="$uploadAction" method="POST" name="frmUploadFile" enctype="multipart/form-data">
            File: <input type="file" name="mediaUploadDefault" id="mediaUploadDefault"><br />
            <input type="hidden" name="path" value="$currentPath"/>
            <input type="hidden" name="redirectUrl" value="$redirectUrl" />
            <input type="submit" name="btnSubmit" value="Submit" />
        </form> 
HTML;
        return $form;
    }
}