<?php
namespace Users\Form;

use \ListModule\Form\Form;

class GroupForm extends Form
{
    public function __construct($name = null)
    {
        // we want to ignore the name passed
        parent::__construct('groups');
        $this->setAttribute('method', 'post');
        $this->add(array(
            'name' => 'id',
            'type' => 'Hidden',
        ));
        $this->add(array(
            'name' => 'name',
            'type' => 'Text',
            'options' => array(
                'label' => 'Name',
                'label_attributes' => array(
                    'class' => 'blockLabel'
                )
            ),
            'attributes' => array(
                'class' => 'stdInputText'
            )
        ));
        $this->add(array(
            'name' => 'scope',
            'type' => 'select',
            'options' => array(
                'value_options' => array('site' => 'Site',
                                         'global' => 'Global'),
                'label' => 'Scope',
                'label_attributes' => array(
                    'class' => 'blockLabel'
                )
            ),
            'attributes' => array(
                'class' => 'stdInputText'
            )            
        ));                
    }
}