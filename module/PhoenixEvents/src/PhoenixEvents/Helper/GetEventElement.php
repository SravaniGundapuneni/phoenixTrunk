<?php
namespace PhoenixEvents\Helper;

use Zend\View\Helper\AbstractHelper;

class GetEventElement extends \ListModule\Helper\ItemInformation
{
    protected $phoenixEvents;

    public function __invoke($filters = array())
    {
        $result = array();
        
        $events = $this->phoenixEvents->getItemsBy($filters);

        foreach ($events as $key => $item)
        {
            if ($item->getStatus()==\ListModule\Model\ListItem::ITEM_STATUS_PUBLISHED)
            {
            $eventArray = $item->toArray();
            /**
             * @todo We should never assume a date's format. Date's should always be stored as DateTime, and when converted
             *       converted to a string should be in the YYYY-MM-DD HH:MM:SS format. It is up to the code using our returned value
             *       to format it in a way that they deem useful. In other words, the following code should be nuked as soon as we can
             *       circle back to fix this module the right way.
             * 
             */
            $eventArray['eventStartOriginal'] = $eventArray['eventStart'];
            $eventArray['eventEndOriginal'] = $eventArray['eventEnd'];
            $eventArray['eventStart'] = date('m/d/Y', strtotime($eventArray['eventStartOriginal']));
            $eventArray['eventEnd'] = date('m/d/Y', strtotime($eventArray['eventEndOriginal']));
            /**
             * @todo  We need to abstract out these methods, so we aren't duplicating code all over the place.
             */
            $eventArray['mainImage'] = $this->getMainImage($item);
            $eventArray['mediaAttachments'] = $this->getMediaAttachments($item);
            $result[] = $eventArray;
            //$result[] = $this->view->getEventInformation($item->getId());
            }
        }

        //return $result;
        $this->getView()->eventInfo = $result;

        //return $this->getview()->render('event-item');
        return $result;
    }


    protected function getMainImage($item)
    {
        $result = null;

        if ( $mediaAttachmetns = $this->getMediaAttachments($item) )
        {
            $result = array_shift( $mediaAttachmetns );
        }

        return $result;
    }    

    public function __construct($phoenixEvents)
    {
        $this->phoenixEvents = $phoenixEvents;
    }
}