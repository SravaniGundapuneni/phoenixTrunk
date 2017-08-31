<?php
use \ListModule\Model\ListItem;
use \ListModule\StdLib\FormHelper;

/**
 * Here is the list of supported methods
 *
 *   Fields
 *
 *     ModuleHelper::text($name, $label, $options = array())
 *     ModuleHelper::textarea($label, $readonly = false, $class = false)
 *     ModuleHelper::checkbox($label, $readonly = false, $class = false)
 *     ModuleHelper::select($label, $options = null, $readonly = false, $class = false)
 *     ModuleHelper::multiselect($label, $options = null, $readonly = false, $class = false)
 *     ModuleHelper::date($label, $min = null, $max = null, $readonly = false, $class = false)
 *     ModuleHelper::attachment($label, $readonly = false, $class = false)
 *     ModuleHelper::image($label, $readonly = false, $class = false)
 *
 * As we add support for more methods we will update this file
 */
return array(
    'itemFormOptions' => array(
       
    ),
    'itemFormFields' => array(
        'Calendar\Form\Form' => array(
			'title'            => FormHelper::text('Title'),
			'description'      => FormHelper::textarea('Content'),
			'url'              => FormHelper::text('URL'),
            'highlights'       => FormHelper::text('Hightlights'),
			'eventCategoryId'  => FormHelper::select('Event Category'),
			'mediaAttachments' => FormHelper::mediaAttachments('Attach Media'),
        
        ),
    'Calendar\Form\CategoryForm' => array(
		    'title'	=> FormHelper::text('Enter Event Category'),
		),
         
    ),
    'inputFilters' => array(
        'Calendar\Model\PhoenixCalendarEvent' => array(
            'eventCategoryId' => array(
                'name' => 'eventCategoryId',
                'required' => true,
                'allowEmpty' => false,
                'validators' => array(
                    array(
                        'name' => 'Regex',
                        'options' => array(
                            'pattern'  => '/^[a-zA-Z1-9][a-zA-Z0-9]*$/i',
                            'messages' =>array(
                                \Zend\Validator\Regex::NOT_MATCH => 'Category field is empty, Please select Event Category',
                            )
                        ),
                    ),
                ),  
            ),
            'url' => array(
                'name' => 'url',
                'required' => true,
                'allowEmpty' => false,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'Hostname',
                        'options' => array(
                            'allow'       => \Zend\Validator\Hostname::ALLOW_URI || \Zend\Validator\Hostname::ALLOW_DNS, // Allow these hostnames
                            'useIdnCheck' => true,  // Check IDN domains
                            'useTldCheck' => true,  // Check TLD elements
                            'ipValidator' => null,  // IP validator to use
                            'messages'=>array(
                                \Zend\Validator\Hostname::INVALID_HOSTNAME => 'Please Use a Valid URL',
                            )
                        ),
                    ),
                ),
            ),
            'title' => array(
                'name'       => 'title',
                'required'   => true,
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 64,
                            'messages' =>array(
                                \Zend\Validator\StringLength::TOO_LONG => 'Title can not be more than 64 characters long.',
                                \Zend\Validator\StringLength::TOO_SHORT => 'Title is required.', 
                            ),
                        ),
                    ),
                    array(
                        'name'    => 'Regex',
                        'options' => array(
                            'pattern'  => '/^[a-zA-Z0-9 ]*$/i',
                            'messages' => array(
                               \Zend\Validator\Regex::NOT_MATCH => 'Title cannot contain special characters.',
                            ),
                        ),
                    ),
                )
            )
        )
    ),
   'fieldsBindParameters' => array(
        'Calendar\Form\Form' => array(
			'mediaAttachments' => array(
                'class' => 'phoenix-attachedmediafiles',
                'parameters' => array('module', 'itemModel', 'currentProperty')
            )
        )
    ),
    'toggleEnabledFields' => array(
        'Calendar\Form\Form' => array(
			
        ),
    ),
    'disabledFields' => array(
        'Calendar\Form\Form' => array(
        ),
    ),
);