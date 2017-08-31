<?php
/**
 * This extends Zend\Form and creates a base form class for the PhoenixReview.
 *
 * @category    Toolbox
 * @package     PhoenixReview
 * @subpackage  Form
 * @copyright   Copyright (c) 2014 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 1.14
 * @since       File available since release 1.14
 * @author      A. Tate <atate@travelclick.com>
 * @filesource
 */

namespace ContentFilter\Form;

use Zend\InputFilter;
use Zend\Form\Element;

/**
 * This extends Zend\Form and creates a base form class for the MailingList.
 *
 * @category    Toolbox
 * @package     ContentFilter
 * @subpackage  Form
 * @copyright   Copyright (c) 2014 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 1.14
 * @since       File available since release 1.14
 * @author      Saurabh Shirgaonkar <sshirgaonkar@travelclick.com>
 */
class Form extends \ListModule\Form\Form
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
        parent::__construct('phoenixContentFilter');
        $this->setAttribute('method', 'post');	
		$this->setAttribute('action','filterControl');
		
		//$action = 'filterControl';
        //$this->setAttribute('action', $this->url()->fromRoute($this->defaultNoItemRoute, array_merge(array('action' => 'filterControl', 'subsite' => $subsite), $this->additionalRouteParams)));            
		
		 //Retrieve the itemId from either the route or the post, depending on what is available.
         //$itemId = (int) $this->params()->fromRoute('itemId', $this->params()->fromPost('id', 0));
         //$itemForm->setAttribute('action', $this->url()->fromRoute($this->defaultNoItemRoute, array_merge(array('action' => 'editItem', 'subsite' => $subsite, 'itemId' => $itemId), $this->additionalRouteParams)));
         // code to be removed ends  
		
    }
}