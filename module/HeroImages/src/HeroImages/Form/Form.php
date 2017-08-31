<?php
/**
 * SiteForm Class
 *
 * This extends Zend\Form and creates a base form class for the Phoenix Property Model.
 *
 * @category    Toolbox
 * @package     PhoenixProperties
 * @subpackage  Form
 * @copyright   Copyright (c) 2014 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.5
 * @since       File available since release 13.5
 * @author      Daniel Yang <dyang@travelclick.com>
 * @filesource
 */

namespace HeroImages\Form;

use Zend\InputFilter;
use Zend\Form\Element;
use Zend\Form\Form as ZendForm;
use ListModule\Form\Form as ListModuleForm;

/**
 * SiteForm Class
 *
 * This extends Zend\Form and creates a base form class for the Site Map                Model.
 *
 * @category    Toolbox
 * @package     HeroImages
 * @subpackage  Form
 * @copyright   Copyright (c) 2014 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.5
 * @since       File available since release 13.5
 * @author      Daniel Yang <dyang@travelclick.com>
 */
class Form extends ListModuleForm
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
        parent::__construct('heroImages');
        $this->setAttribute('method', 'post');
    }

}