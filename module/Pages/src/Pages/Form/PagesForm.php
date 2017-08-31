<?php
/**
 * PagesForm Class
 *
 * This extends Zend\Form and creates a base form class for the Ppages Model.
 *
 * @category    Toolbox
 * @package     Pages
 * @subpackage  Form
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.5
 * @since       File available since release 13.5
 * @author      A. Tate <atate@travelclick.com>
 * @filesource
 */
namespace Pages\Form;

use Zend\InputFilter;
use Zend\Form\Element;
use Zend\Form\Form;
use ListModule\Form\Form as ListModuleForm;

/* Pages
 *
 * This extends Zend\Form and creates a base form class for the Ppages Model.
 *
 * @category    Toolbox
 * @package     Pages
 * @subpackage  Form
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.5
 * @since       File available since release 13.5
 * @author      A. Tate <atate@travelclick.com>
 */
class PagesForm extends \ListModule\Form\Form
{
    /**
     * __construct
     *
     * The form class constructor
     *
     * This sets up all of the fields, which can then be modified to the needs of the code the form is being used in.
     * @param string $name
     */
    public function __construct()
    {
       
        /**
         * Lets set the service manager
         */
        //$this->serviceManager = $sm;
        // we want to ignore the name passed
        parent::__construct('pages');
        $this->setAttribute('method', 'post');
        
    }    
}

