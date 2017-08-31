<?php
/** 
 * YouFirstTempPwd Class
 * 
 * This will extend the Zend/Form and it will create a login form the (Model to be named later)
 * 
 * @category       Toolbox
 * @package        YouFirst
 * @subpackage     Form
 * @copyright      Copyright (c) 2013 TravelClick, Inc (http://travelclick.com)
 * @license        All Rights Reserved
 * @since          File available since release 13.5 
 * @author         Kevin Davis <kedavis@travelclick.com>
 * @filesource
*/

namespace YouFirst\Form;

/** 
 * YouFirstTempPwd Class
 * 
 * This will extend the Zend/Form and it will create a login form the (Model to be named later)
 * 
 * @category       Toolbox
 * @package        YouFirst
 * @subpackage     Form
 * @copyright      Copyright (c) 2013 TravelClick, Inc (http://travelclick.com)
 * @license        All Rights Reserved
 * @since          File available since release 13.5 
 * @author         Kevin Davis <kedavis@travelclick.com>
*/

 use Zend\InputFilter;
 use Zend\Form\Element;
 use Zend\Form\Form;
 
 
 class YouFirstTempPwd extends form
 {
 	public function __construct()
 	{
	   parent::__construct('youFirst');
	   $this->setAttribute('method','post');
	   
	   $this->add(array(
		 'name' => 'new-password',
		 'type' => 'Password',
		 'options' => array (
		   'label' => 'New Password',
		   'label_attributes' => array(
		     'class' => 'blocklabel'
			 )
			),
			'attributes' => array(
			  'class' => 'stdTextInput',
			)
			));
			
		$this->add(array(
		 'name' => 'confirm-password',
		 'type' => 'Password',
		 'options' => array (
		   'label' => 'Confirm New Password',
		   'label_attributes' => array(
		     'class' => 'blocklabel'
			 )
			),
			'attributes' => array(
			  'class' => 'stdTextInput',
			)
			));
 	}
 }
