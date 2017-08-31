<?php
namespace PhoenixSite\Traits;

/*
 * Expects the following properties to be defined:
 *
 * $amfService (Attached Media File Service) 
 * $mrgdConf
 *
 * $item (module item)
 */
trait ImageAttachment
{
    /*
     * Possible Options:
     * - returnDefault (boolean)    Determines whether to return the default image or nothing when an image is not found.
     */
    public function getImage($options)
    {
        $imageTag = '';
        $src = $this->getImageSrc();

        if ($src) {
            $imageTag = $this->getImageTag($src);
        } else if ($this->shouldReturnDefault($options)) {
            $imageTag = $this->getImageTag($this->getDefaultSrc());
        }

        return $imageTag;
    }

    private function getAllowedImageTypes()
    {
        return $this->mrgdConf->get(array('mediamanager-allowed-image-types'));
    }

    private function getAttachments()
    {
        return $this->amfService->getItemsForWidgets($this->item->getItem());
    }

    private function getDefaultWidgetImage()
    {
        return $this->mrgdConf->get(array('default-widget-image'));
    }

    private function getDefaultSrc()
    {
        return $this->getToolboxIncludeUrl() . $this->getDefaultWidgetImage();
    }

    private function getImagePath($mediaManagerFile)
    {
        return rtrim($this->getSiteRoot(), "/") . $mediaManagerFile->getPath() . '/' . $mediaManagerFile->getName();
    }

    private function getImageSrc()
    {
        $image = '';

        foreach ($attachments = $this->getAttachments() as $attachment) {

            $mediaManagerFile = $attachment->getMediaManagerFile();

            if ($this->isFileImage($mediaManagerFile)) {
                $image = $this->getImagePath($mediaManagerFile);
                break;
            }
        }

        return $image;
    }

    private function getImageTag($src)
    {
        return '<img src="' . $src . '">';
    }

    private function getSiteRoot()
    {
        return $this->mrgdConf->get(array('templateVars', 'siteroot'));
    }

    private function getToolboxIncludeUrl()
    {
        return $this->mrgdConf->get(array('paths', 'toolboxIncludeUrl'));
    }

    private function isFileImage($mediaManagerFile)
    {
        return in_array($mediaManagerFile->getType(), $this->getAllowedImageTypes());
    }

    private function shouldReturnDefault($options)
    {
        $shouldReturnDefault = false;

        if (isset($options['returnDefault'])) {
            $shouldReturnDefault = $options['returnDefault'];
        }

        return $shouldReturnDefault;
    }
}
