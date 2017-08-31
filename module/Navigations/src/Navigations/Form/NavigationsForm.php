<?php
/**
 * NavigationsForm Class
 *
 * This extends Zend\Form and creates a base form class for the Phoenix Navigations Model.
 *
 * @category        Toolbox
 * @package         Navigations
 * @subpackage      Form
 * @copyright       Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license         All Rights Reserved
 * @version         Release: 13.6
 * @since           File available since release 13.6
 * @author          A. Tate <atate@travelclick.com>
 * @filesource
 */

namespace Navigations\Form;

use Zend\InputFilter;
use Zend\Form\Element;
use Zend\Form\Form;

/**
 * MapMarkerForm Class
 *
 * This extends Zend\Form and creates a base form class for the Phoenix MapMarker Model.
 *
 * @category        Toolbox
 * @package         Navigations
 * @subpackage      Form
 * @copyright       Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license         All Rights Reserved
 * @version         Release: 13.6
 * @since           File available since release 13.6
 * @author          A. Tate <atate@travelclick.com>
 */
class NavigationsForm extends \ListModule\Form\Form
{
    /**
     * __construct
     *
     * The form class constructor
     *
     * This sets up all of the fields, which can then be modified to the needs of the code the form is being used in.
     * @param string $name
     */
    public function __construct($name = null)
    {
        // we want to ignore the name passed
        parent::__construct('navigations');

        $this->setAttribute('method', 'post');

    }
}