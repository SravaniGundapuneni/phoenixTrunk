<?php
/**
 * The file for the RateForm Form
 *
 * @category    Toolbox
 * @package     RoomForm
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
 * The file for the RateForm Form
 *
 * @category    Toolbox
 * @package     RoomForm
 * @subpackage  Controller
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.4
 * @since       File available since release 13.4
 * @author      A. Tate <atate@travelclick.com>
 */

class RoomForm extends Form
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
        parent::__construct('room');

        $this->setAttribute('method', 'post');

        $this->add(array(
            'name' => 'hotel',
            'type' => 'Select',
            'options' => array(
                'label' => 'Hotels',
                'label_attributes' => array(
                    'class' => 'blockLabel'
                ),
                'value_options' => array_merge(
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
                    array('0' => '-- All --'),
                    $languages
                )
            ),
            'attributes' => array(
                'multiple' => '10',
                'class' => 'stdInputText',
                'value' => 'E',
            )
        ));

        $this->add(array(
            'name' => 'criteria',
            'type' => 'Textarea',
            'options' => array(
                'label' => 'Filter<br>(example: roomCode1, roomCode2, roomCode3, ..)',
                'label_attributes' => array(
                    'class' => 'blockLabel'
                )
             ),
            'attributes' => array(
                'class' => 'stdInputText'
            )
        ));

        $this->add(array(
            'name' => 'override',
            'type' => 'Hidden',
            'options' => array(
                'label' => 'Override existing?',
                'label_attributes' => array(
                    'class' => 'blockLabel'
                ),
                'use_hidden_element' => false,
                'checked_value' => '1',
                'unchecked_value' => '0'
             ),
            'attributes' => array(
                'class' => 'stdInputText',
                'value' => '1'
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
                'unchecked_value' => '0'
             ),
            'attributes' => array(
                'class' => 'stdInputText',
                'checked' => true,
                'value' => '1'
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