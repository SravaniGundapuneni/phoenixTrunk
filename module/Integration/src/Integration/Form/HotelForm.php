<?php
/**
 * The file for the HotelForm Form
 *
 * @category    Toolbox
 * @package     HotelForm
 * @subpackage  Controller
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.4
 * @since       File available since release 13.4
 * @author      A. Tate <atate@travelclick.com>
 * @filesource
 */
namespace Integration\Form;

use Zend\Form\Form;

/**
 * The file for the HotelForm Form
 *
 * @category    Toolbox
 * @package     HotelForm
 * @subpackage  Controller
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.4
 * @since       File available since release 13.4
 * @author      A. Tate <atate@travelclick.com> 
 */

class HotelForm extends Form
{
    /**
     *  __construct function
     * 
     * @access public
     * @param null $null
     * @param array $hotels
     * @param array $languages
     *
    */
    
    public function __construct($name = null, $hotels = array(), $languages = array())
    {
        // we want to ignore the name passed
        parent::__construct('hotel');

        $this->setAttribute('method', 'post');

        $this->add(array(
            'name' => 'hotel',
            'type' => 'Select',
            'options' => array(
                'label' => 'Update Hotel',
                'label_attributes' => array(
                    'class' => 'blockLabel'
                ),
                'value_options' => array_merge(
                    array('0' => '-- All --'),
                    $hotels
                )
            ),
            'attributes' => array(
                'multiple' => '10',
                'class' => 'stdInputText',
                'value' => 0,
            )
        ));

        $this->add(array(
            'name' => 'language[]',
            'type' => 'select',
            'options' => array(
                'label' => 'Languages',
                'label_attributes' => array(
                    'class' => 'blockLabel'
                ),
                'value_options' => array_merge(
                    $languages
                )
            ),
            'attributes' => array(
                'multiple' => '5',
                'class' => 'stdInputText',
                'value' => 'E',
            )
        ));

        $this->add(array(
            'name' => 'recursive',
            'type' => 'Checkbox',
            'options' => array(
                'label' => 'Update content tree? (this will overwrite all addons, rates, and rooms for each updated hotel',
                'label_attributes' => array(
                    'class' => 'blockLabel'
                ),
                'use_hidden_element' => false,
                'checked_value' => '1',
                'unchecked_value' => '0'
             ),
            'attributes' => array(
                'class' => 'stdInputText'
            )
        ));

        $this->add(array(
            'name' => 'dryrun',
            'type' => 'Hidden',
            'options' => array(
                'label' => 'Preview',
                'label_attributes' => array(
                    'class' => 'blockLabel'
                ),
                'use_hidden_element' => false,
                'checked_value' => '1',
                'unchecked_value' => '0',
             ),
            'attributes' => array(
                'class' => 'stdInputText',
                'checked' => true,
                 'value' => '1',
            )
        ));

        $this->add(array(
            'name' => 'import',
            'type' => 'Submit',
            'options' => array(
                'label' => ' ',
                'label_attributes' => array(
                    'class' => 'blockLabel'
                )
             ),
            'attributes' => array(
                'type' => 'submit',
                'value' => 'Import',
                'class' => 'stdInputText'
            )
        ));
    }
}