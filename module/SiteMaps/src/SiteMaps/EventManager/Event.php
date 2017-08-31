<?php
namespace SiteMaps\EventManager;

use Phoenix\EventManager\Event as PhoenixEvent;

class Event extends PhoenixEvent
{
    const EVENT_SITEMAP_EDITITEM = 'SiteMapEditItem';
    const EVENT_SITEMAP_SAVE = 'sitemapSave';
    const EVENT_SITEMAP_DISPLAY = 'sitemapDisplay';
    const EVENT_SITEMAP_GETARRAYCOPY = 'sitemapGetArrayCopy';
    const EVENT_SITEMAP_GETINPUTFILTER = 'sitemapGetInputFilter';
}