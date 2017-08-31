<?php
/**
 * AddonsForm Class
 *
 * This will extend the Zend/Form and it will create a base form class for the Phoenix Addon Model.
 *
 * @category        Toolbox
 * @package         PhoenixAddons
 * @subpackage      Form
 * @copyright       Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license         All Rights Reserved
 * @version     	Release: 13.4
 * @since           File available since release 13.4
 * @author          Bradley Davidson <bdavidson@travelclick.com>
 * @filesource
 */

namespace PhoenixAddons\Form;

use Zend\InputFilter;
use Zend\Form\Element;
use Zend\Form\Form;

/**
 * AddonsForm Class
 *
 * This will extend the Zend/Form and it will create a base form class for the Phoenix Addon Model.
 *
 * @category        Toolbox
 * @package         PhoenixAddons
 * @subpackage      Form
 * @copyright       Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license         All Rights Reserved
 * @version         Release: 13.4
 * @since           File available since release 13.4
 * @author          Bradley Davidson <bdavidson@travelclick.com>
 */
class AddonForm extends \ListModule\Form\Form
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
		parent::__construct('phoenixAddon');
    	$this->setAttribute('method', 'post');
	}
}