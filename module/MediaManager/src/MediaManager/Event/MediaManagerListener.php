<?php
namespace MediaManager\Event;

use Zend\EventManager\ListenerAggregateInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\EventInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class MediaManagerListener implements ListenerAggregateInterface, ServiceLocatorAwareInterface
{
	protected $listeners = array();
	protected $serviceLocator;

	public function attach(EventManagerInterface $events)
	{

		$this->listeners[] = $events->attach('attachMediaForNewItem', array($this, 'attachMediaForNewItem'));
	}

	public function detach(EventManagerInterface $events)
	{
		foreach ($this->listeners as $index => $listener) {
			if ($events->detach($listener)) {
				unset($this->listeners[$index]);
			}
		}
	}

	public function setServiceLocator(ServiceLocatorInterface $serviceLocator) {
		$this->serviceLocator = $serviceLocator;
	}

	public function getServiceLocator() {
		return $this->serviceLocator;
	}

	public function attachMediaForNewItem(EventInterface $event)
	{
		$mmService = $this->getServiceLocator()->get('phoenix-mediamanager');
		$mmService->attachFiles($this->getAttachmentOptions($event));
	}
	
	private function getAttachmentOptions($event)
	{
		$options = array();
		$options['parentModule'] = $event->getParam('parentModule', '');
		$options['parentItemId'] = (int) $event->getParam('parentItemId', 0);
		$options['attachments'] = $event->getParam('mediaAttachments', ''); 
		$options['currentUser'] = $this->getServiceLocator()->get('phoenix-users-current');
		$options['currentProperty'] = $this->getServiceLocator()->get('currentProperty');
		return $options;
	}
}