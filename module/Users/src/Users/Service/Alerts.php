<?php
/**
 * The file for the User Alerts Service
 *
 * @category    Toolbox
 * @package     Users
 * @subpackage  Service
 * @copyright   Copyright (c) 2014 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 1.14
 * @since       File available since release 1.14 
 * @author      A. Tate <atate@travelclick.com>
 * @filesource
 */

namespace Users\Service;

use Phoenix\Logger\Logger as LoggerAbstract;

/**
 * The User Alerts Service
 *
 * @category    Toolbox
 * @package     Users
 * @subpackage  Service
 * @copyright   Copyright (c) 2014 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 1.14
 * @since       File available since release 1.14 
 * @author      A. Tate <atate@travelclick.com>
 */

class Alerts extends LoggerAbstract
{
    /**
     * The Sessions Service Object
     * @var \Users\Service\Sessions $sessions
     */
    protected $sessions;

    /**
     * setSessions
     * 
     * @param \Users\Service\Sessions $sessions [description]
     */
    public function setSessions(Sessions $sessions)
    {
        $this->sessions = $sessions;
    }

    /**
     * getSessions
     * 
     * @return \Users\Service\Sessions
     */
    public function getSessions()
    {
        return $this->sessions;
    }

    public function log($message, $type, $tokens = array(), $options = array())
    {
        $this->messages[] = array('message' => $message, 'type' => $type, 'tokens' => $tokens);
    }

    public function broadcast($type = 'all', $options = array())
    {
        if ($type == 'all') {
            return $this->messages;            
        } else {
            $fnFilterByType = function ($value) use ($type) {
                return ($value['type'] == $type);
            };

            return array_filter($this->messages, $fnFilterByType);
        }

        return $this->messages;
    }

    /**
     * clean
     *
     * Empties the alert stack
     * 
     * @return void
     */
    public function clean()
    {
        $this->messages = array();
    }

    /**
     * save
     *
     * Not currently used, but exists because it's part of the interface. Might eventually be used for
     * a database driven activity log
     * 
     * @return void
     */
    public function save()
    {

    }
}