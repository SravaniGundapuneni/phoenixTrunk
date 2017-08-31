<?php
namespace MediaManager\Helper;

use Zend\View\Helper\AbstractHelper;

class MediaAttachmentListItem extends AbstractHelper
{
	public function __invoke($mediaAttachmentItem)
	{
		$this->getView()->mediaAttachmentItem = $mediaAttachmentItem;
		return $this->getView()->render('media-manager/helpers/media-attachments-item');
	}
}