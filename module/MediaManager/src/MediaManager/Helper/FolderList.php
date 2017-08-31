<?php
namespace MediaManager\Helper;

use Zend\View\Helper\AbstractHelper;

class FolderList extends AbstractHelper
{
    public function __invoke()
    {
        $parameters = func_get_args();

        $folders = $parameters[0];
        $path = $parameters[1];

        $dirList   = '';
        foreach($folders as $fullPath) {
            $folderName = pathinfo($fullPath, PATHINFO_BASENAME);

            if(mb_strpos($folerName, \MediaManager\Service\MediaManager::THUMBS_FOLDER_PREFIX) !== 0) {
                $dirList .= $this->getView()->createMediaListPreview($folderName, $path);
            }
        }

        return $dirList;
    }
}