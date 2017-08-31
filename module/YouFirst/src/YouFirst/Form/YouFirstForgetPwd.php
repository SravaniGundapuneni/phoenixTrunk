<?php
/** 
 * YouFirstForgetPwd Class
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
 * YouFirstForgetPwd Class
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
 
 
 class YouFirstForgetPwd extends form
 {
     public function __construct()
     {
       parent::__construct('youFirst');
       $this->setAttribute('method','post');
       
       $this->add(array(
        'name' => 'email',
        'type' => 'text',
        'options' => array (
          'label' => 'E-Mail',
          'label_attributes' => array (
            'class' => 'blocklabel'
           )
          ),
          'attributes' => array (
           'class' => 'stdTextInput'
           )
          ));
     }
 }
