<?php
namespace Pages\EventManager;

use Phoenix\EventManager\Event as PhoenixEvent;

class Event extends PhoenixEvent
{
    const EVENT_PAGE_EDITITEM = 'pageEditItem';
    const EVENT_PAGE_SAVE = 'pageSave';
    const EVENT_PAGE_DISPLAY = 'pageDisplay';
    const EVENT_PAGE_GETARRAYCOPY = 'pageGetArrayCopy';
    const EVENT_PAGE_GETINPUTFILTER = 'pageGetInputFilter';
}