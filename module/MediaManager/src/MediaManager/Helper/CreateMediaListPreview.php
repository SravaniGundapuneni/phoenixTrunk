<?php
namespace MediaManager\Helper;

use Zend\View\Helper\AbstractHelper;

class CreateMediaListPreview extends AbstractHelper
{
    public function __invoke($fileInfo, $currentPath ='')
    {
        $this->getView()->fileId = $fileInfo['id'];
        $this->getView()->title = $fileInfo['title'];
        $this->getView()->class = $fileInfo['class'];
        $this->getView()->contentType = $fileInfo['contentType'];
        $this->getView()->previewThumb = $fileInfo['previewThumb'];
        $this->getView()->previewNotes = $fileInfo['previewNotes'];
        $this->getView()->currentPath = $currentPath;
        return $this->getView()->render('media-manager/toolbox/preview');
    }
}