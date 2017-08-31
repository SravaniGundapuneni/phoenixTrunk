<?php
namespace Languages\EventManager;

use Phoenix\EventManager\Event as PhoenixEvent;

class Event extends PhoenixEvent
{
    const EVENT_EXPORT = 'export';
    const EVENT_IMPORT = 'import';
}