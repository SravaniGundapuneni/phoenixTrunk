<?php
/**
 * This extends Zend\Form and creates a base form class for the DynamicListModule.
 *
 * @category    Toolbox
 * @package     DynamicListModule
 * @subpackage  Form
 * @copyright   Copyright (c) 2014 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 1.14
 * @since       File available since release 1.14
 * @author      A. Tate <atate@travelclick.com>
 * @filesource
 */

namespace DynamicListModule\Form;

use Zend\InputFilter;
use Zend\Form\Element;
use Zend\Form\Form;

/**
 * This extends Zend\Form and creates a base form class for the DynamicListModule.
 *
 * @category    Toolbox
 * @package     DynamicListModule
 * @subpackage  Form
 * @copyright   Copyright (c) 2014 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 1.14
 * @since       File available since release 1.14
 * @author      Daniel Yang <dyang@travelclick.com> 
 */
class UploadForm extends Form
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
        parent::__construct('Upload');
        $this->setAttribute('method', 'post');
        $this->setAttribute('enctype','multipart/form-data');
         
         
        $this->add(array(
            'name' => 'upload',
            'attributes' => array(
                'type'  => 'file',
            ),
            'options' => array(
                'label' => 'CSV Upload',
            ),
        )); 
         
         
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Upload Now'
            ),
        )); 
    }
}