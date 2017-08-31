<?php
namespace Calendar\Classes;

class CalendarEvent
{
	const DEFAULT_ATTACHMENT_IMAGE = 'widget/CalendarEvents/view/calendar-events/helper/img/defaultImage.jpg';
	private $amfService;
	private $description;
	private $endDate;
	private $eventCategoryId;
	private $eventtitle;
	private $highlights;
	private $itemId;
	private $mmfService;
	private $siteroot;
	private $startDate;
	private $title;
	private $toolboxIncludeUrl;
	private $url;

	public function __construct(Array $options)
	{
		$this->description     = $options['description'];
		$this->endDate         = $options['endDate'];
		$this->eventCategoryId = $options['eventCategoryId'];
		$this->eventtitle      = $options['eventtitle'];
		$this->highlights      = $options['highlights'];
		$this->itemId          = $options['itemId'];
		$this->startDate       = $options['startDate'];
		$this->title           = $options['title'];
		$this->url             = $options['url'];
	}

	public function getDate()
	{
		return ltrim(substr($this->startDate, 8, 2), '0');
	}

	public function getMonth()
	{
		return ltrim(substr($this->startDate, 5, 2), '0');
	}

	public function getMonthName()
	{
		return date('F', mktime(0, 0, 0, $this->getMonth(), 10));
	}

	public function getYear()
	{
		return substr($this->startDate, 0, 4);
	}

	public function getTemplateData()
	{
		return array(
			'date'        => $this->getDate(),
			'description' => $this->description,
			'eventtitle'  => $this->eventtitle,
			'imageUrl'    => $this->getImageUrl(),
			'month'       => $this->getMonth(),
			'monthName'   => $this->getMonthName(),
			'url'         => $this->url,
			'year'        => $this->getYear(),
		);
	}

	public function setAttachedMediaFilesService($amfService)
	{
		$this->amfService = $amfService;
	}

	public function setMediaManagerFilesService($mmfService)
	{
		$this->mmfService = $mmfService;	
	}

	public function setSiteroot($siteroot)
	{
		$this->siteroot = $siteroot;
	}

	public function setToolboxIncludeUrl($toolboxIncludeUrl)
	{
		$this->toolboxIncludeUrl = $toolboxIncludeUrl;
	}

	private function getDefaultImage()
	{
		return $this->toolboxIncludeUrl . self::DEFAULT_ATTACHMENT_IMAGE;
	}

	private function getImageFromEventAttachments($eventAttachments)
	{
		$files = $eventAttachments->getFile();
		$image = $this->mmfService->getWidgetImageData($files);
		return $this->siteroot . substr($image->getPath(), 1) . '/' . $image->getName();
	}

	private function getImageUrl()
	{
		$eventAttachments = $this->amfService->getItemForWidgets($this->itemId);

		// TODO: make sure this isn't returning PDF, type 'DOC', etc.
		if (is_null($eventAttachments)) {
			$imageUrl = $this->getDefaultImage();
		} else {
			$imageUrl = $this->getImageFromEventAttachments($eventAttachments);

		}

		return $imageUrl;
	}
}
