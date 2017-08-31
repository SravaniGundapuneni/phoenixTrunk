<?php
/**
 * The ListModule Form File
 *
 * @category    Toolbox
 * @package     ListModule
 * @subpackage  Form
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.5.5
 * @since       File available since release 13.5.5
 * @author      A. Tate <atate@travelclick.com>
 * @filesource
 */
namespace ListModule\Form;

use Zend\Form\FormInterface;

/**
 * The ListModule Form Class
 *
 * @category    Toolbox
 * @package     ListModule
 * @subpackage  Form
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.5.5
 * @since       Class available since release 13.5.5
 * @author      A. Tate <atate@travelclick.com>
 */
class Form extends \Zend\Form\Form
{
    /**
     * Attached binders for the form
     * @var array
     */
    protected $attachedBinders = array();

    /**
     * __construct
     *
     * Class constructor
     * @param string $name
     */
    public function __construct($name)
    {
        parent::__construct($name);
        $this->setAttribute('method','post');
        $this->add(array('name'=>'id', 'type' =>'Hidden'));
        $this->add(array('name'=>'action', 'type' =>'Hidden'));
    }

    /**
     * text
     * 
     * @static
     * @param  string  $name   
     * @param  string  $label  
     * @param  boolean $readonly
     * @param  string  $class   
     * @return array           
     */
    static public function text($name, $label, $readonly=false, $class='')
    {
        return array(
            'name' => $name,
            'type' => 'text',
            'options' => array(
                'label' => $label,
                'label_attributes' => array(
                    'class' => 'blockLabel'
                )
            ),
            'attributes' => array(
                'class' => "stdTextInput $class",
                'readonly' => $readonly
            )
        );
    }

    /**
     * password
     * @param  string  $name
     * @param  string  $label    
     * @param  boolean $readonly 
     * @param  string  $class    
     * @return array            
     */
    static public function password($name, $label, $readonly=false, $class='')
    {
        return array(
            'name' => $name,
            'type' => 'password',
            'options' => array(
                'label' => $label,
                'label_attributes' => array(
                    'class' => 'blockLabel'
                )
            ),
            'attributes' => array(
                'class' => "stdTextInput $class",
                'readonly' => $readonly
            ),
           
        );
    }

    /**
     * checkbox
     * @param  string  $name
     * @param  string  $label    
     * @param  boolean $readonly 
     * @param  string  $class    
     * @return array            
     */
    static public function checkbox($name, $label, $readonly=false, $class='')
    {
        return array(
            'name' => $name,
            'type' => 'checkbox',
            'options' => array(
                'label' => $label,
                'label_attributes' => array(
                    'class' => 'blockLabel'
                ),
                'checked_value' => 1,
                'unchecked_value' => 0,
            ),
            'attributes' => array(
                'class' => "stdCheckboxInput $class",
                'readonly' => $readonly,
            )
        );
    }

    /**
     * textarea
     * @param  string  $name
     * @param  string  $label    
     * @param  boolean $readonly 
     * @param  string  $class    
     * @return array            
     */
    static public function textarea($name, $label, $readonly=false, $class='')
    {
        return array(
            'name' => $name,
            'type' => 'textarea',
            'options' => array(
                'label' => $label,
                'label_attributes' => array(
                    'class' => 'blockLabel'
                )
            ),
            'attributes' => array(
                'class' => "stdTextInput $class ckeditor",
                'readonly' => $readonly,
            )
        );
    }

    /**
     * select
     * @param  string  $name
     * @param  string  $label    
     * @param  boolean $readonly 
     * @param  string  $class    
     * @return array            
     */
    static public function select($name, $label, $options, $readonly=false, $class='')
    {
        return array(
            'name' => $name,
            'type' => 'select',
            'options' => array(
                'label' => $label,
                'label_attributes' => array(
                    'class' => 'blockLabel'
                  
                ),
                'value_options' => $options
            ),
            'attributes' => array(
                'class' => "stdTextInputSmall $class",
                'readonly' => $readonly,
                  'id'=>'selectForm'
            )
        );
    }

    /**
     * multiselect
     * @param  string  $name
     * @param  string  $label    
     * @param  boolean $readonly 
     * @param  string  $class    
     * @return array            
     */
    static public function multiselect($name, $label, $options, $readonly=false, $class='')
    {
        return array(
            'name' => $name,
            'type' => 'select',
            'options' => array(
                'label' => $label,
                'label_attributes' => array(
                    'class' => 'blockLabel'
                ),
                'value_options' => $options
            ),
            'attributes' => array(
                'class' => "stdTextInputSmall $class",
                'readonly' => $readonly,
                'multiple' => true,
            )
        );
    }
	
	
	
    /**
     * pageselect
     * @param  string  $name
     * @param  string  $label    
     * @param  boolean $readonly 
     * @param  string  $class    
     * @return array            
     */
    static public function pageselect($name, $label, $options, $readonly=false, $class='')
    {
        return array(
            'name' => $name,
            'type' => 'select',
            'options' => array(
                'label' => $label,
                'label_attributes' => array(
                    'class' => 'blockLabel'
                ),
                'value_options' => $options
            ),
            'attributes' => array(
                'class' => "stdTextInputSmall $class",
                'readonly' => $readonly,
                'multiple' => true,
            )
        );
    }

    /**
     * date
     * 
     * @param  string  $name
     * @param  string  $label
     * @param  mixed  $min
     * @param  mixed  $max
     * @param  boolean $readonly
     * @param  string  $class
     * @return array
     */
    static public function date($name, $label, $min=null, $max=null, $readonly=false, $class='')
    {
        return array(
            'name' => $name,
            'type' => 'date',
            'options' => array(
                'label' => $label,
                'label_attributes' => array(
                    'class' => 'blockLabel'
                )
            ),
            'attributes' => array(
                'class' => "stdInputDate $class",
                'readonly' => $readonly,
                'min'  => $min,
                'max'  => $max,
            )
        );
    }

    /**
     * image
     * @param  string  $name
     * @param  string  $label    
     * @param  boolean $readonly 
     * @param  string  $class    
     * @return array            
     */
    static public function image($name, $label, $readonly=true, $class='')
    {
        return array(
            'name' => $name,
            'type' => 'file',
            'options' => array(
                'label' => $label,
                'label_attributes' => array(
                    'class' => 'blockLabel'
                )
            ),
            'attributes' => array(
                'class' => "stdInputImage $class",
                'readonly' => $readonly,
                'id'  => 'upload-file',
            )
        );
    }
   /**
     * pageselect
     * @param  string  $name
     * @param  string  $label    
     * @param  boolean $readonly 
     * @param  string  $class    
     * @return array            
     */
    static public function countries($name, $label, $options, $readonly=false, $class='')
    {
        return array(
            'name' => $name,
            'type' => 'select',
            'options' => array(
                'label' => $label,
                'label_attributes' => array(
                    'class' => 'blockLabel'
                ),
                'value_options' => $options
            ),
            'attributes' => array(
                'class' => "stdTextInputSmall $class",
                'readonly' => $readonly,
                'multiple' => true,
            )
        );
    }
    
    /**
     * mediaAttachments
     * @param  string  $name
     * @param  string  $label    
     * @param  boolean $readonly 
     * @param  string  $class    
     * @return array            
     */
    static public function mediaAttachments($name, $label, $readonly=false, $class='')
    {
        return array(
            'name' => $name,
            'type' => 'MediaAttachments',
            'options' => array(
                'label' => $label,
                'label_attributes' => array(
                    'class' => 'blockLabel'
                ),
            ),
            'attributes' => array(
                'class' => 'stdTextInputSmall',
                'readonly' => $readonly
            ),
        );
    }

    /**
     * bind
     * @param  object  $object
     * @param  int  $flags    
     * 
     * @return void            
     */
    public function bind($object, $flags = FormInterface::VALUES_NORMALIZED)
    {
        parent::bind($object, $flags);

        foreach ($this->attachedBinders as $valBinder) {
            $this->populateValues($valBinder->getBindArray());
        }
    }

    /**
     * attachBinder
     * 
     * @param  string $binderKey
     * @param  Phoenix\Service\ServiceAbstract $binderService [description]
     * @return void
     */
    public function attachBinder($binderKey, $binderService)
    {
        $this->attachedBinders[$binderKey] = $binderService;
    }
}