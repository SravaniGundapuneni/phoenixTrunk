<?php
namespace MvcTest\EventManager;

use Phoenix\EventManager\Event as PhoenixEvent;

class Event extends PhoenixEvent
{
    const EVENT_REVIEW_SAVE = 'review save';
   // const EVENT_LOAD_CURRENTUSER = 'loadCurrentUser';
    //const EVENT_GET_USER = 'getUser';
    //const EVENT_USER_OPTIONS = 'getUserOptions';
    //const EVENT_USER_LOGIN = 'login';
}