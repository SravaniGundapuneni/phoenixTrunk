<?php
namespace MediaManager\Form\View\Helper;

use Zend\Form\View\Helper\AbstractHelper;
use Zend\Form\ElementInterface;

class MediaAttachments extends AbstractHelper
{
    static private function sortItems($a, $b)
    {
        if ($a->getOrderNumber() == $b->getOrderNumber()) {
            return 0;
        }
        return ($a->getOrderNumber() < $b->getOrderNumber()) ? -1 : 1;
    }

    public function __invoke(ElementInterface $element = null)
    {
        return $this->render($element);
    }

    public function render($element)
    {
        return $this->getView()->render('media-manager/helpers/media-attachments-list.phtml',
            array(
                'itemModelId' => $this->getItemModelId(),
                'mediaAttachments' => $this->getMediaAttachments($element),
                'toolboxIncludeUrl' => $this->toolboxIncludeUrl
            )
        );
    }

    private function getMediaAttachments($element)
    {
        $value = $element->getValue();

        if ($element && !empty($value)) {
            $mediaAttachments = $value;
            usort($mediaAttachments, array($this, 'sortItems'));
        } else {
            $mediaAttachments = array();
        }

        return $mediaAttachments;
    }

    private function getItemModelId()
    {
        $itemModel = $this->getView()->itemModel;
        return ($itemModel && $itemModel->getId()) ? $itemModel->getId() : 0;
    }
}