<?php
namespace SeoFileUpload\Helper;

use Zend\View\Helper\AbstractHelper;

class SeoFileUploadUploadForm extends AbstractHelper
{
    public function __invoke()
    {
        $parameters = func_get_args();

        $currentPath = '';

        if (isset($parameters[0]) && $parameters[0]) {
            $currentPath = $parameters[0];
        }

        if (isset($parameters[1]) && $parameters[1]) {
            $redirectUrl = $parameters[1];
        } else {
            $redirectUrl = $this->getView()->basePathPrefix . $this->getView()->url('seoFileUpload-toolbox') . '/editlist';
        }

        $uploadAction = $this->getView()->basePathPrefix . $this->getView()->url('seoFileUpload-sockets', array('subsite' => $this->getView()->subsiteRoute, 'action' => 'seoFileUploadUpload'));

        $form = <<<HTML
        <form action="$uploadAction" method="POST" name="frmUploadFile" enctype="multipart/form-data">
            File: <input type="file" name="mediaUpload" id="mediaUpload">
            <input type="hidden" name="path" value="$currentPath"/>
            <input type="hidden" name="redirectUrl" value="$redirectUrl" />
            <input type="submit" name="btnSubmit" value="Submit" />
        </form> 
HTML;
        return $form;
    }
}