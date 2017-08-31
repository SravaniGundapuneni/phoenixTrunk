<?php
namespace PhoenixReview\Form;

use ListModule\Form\Form as ListModuleForm;

class PhoenixReviewForm extends ListModuleForm
{
    public function __construct($name = null)
    {
        // we want to ignore the name passed
        parent::__construct('phoenixReview');
        $this->setAttribute('method', 'post');
        $this->add(array(
            'name' => 'itemId',
            'type' => 'Hidden',
        ));
        $this->add(array(
            'name' => 'title',
            'type' => 'Text',
            'options' => array(
                'label' => 'Title',
                'label_attributes' => array(
                    'class' => 'blockLabel'
                )
            ),
            'attributes' => array(
                'class' => 'stdInputText'
            )
        ));
		 $this->add(array(
            'name' => 'content',
            'type' => 'textarea',
            'options' => array(
                'label' => 'Content',
                'label_attributes' => array(
                    'class' => 'blockLabel'
                )
            ),
            'attributes' => array(
                 'class' => "stdTextInput ckeditor",
            )
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
            'name' => 'emailId',
            'type' => 'Text',
            'options' => array(
                'label' => 'EmailId',
                'label_attributes' => array(
                    'class' => 'blockLabel'
                )
            ),
            'attributes' => array(
                'class' => 'stdInputText'
            )
        ));
		$this->add(array(
            'name' => 'emailMe',
            'type' => 'checkbox',
            'options' => array(
                'label' => 'Email',
                'label_attributes' => array(
                    'class' => 'blockLabel'
                ),
                'checked_value' => 1,
                'unchecked_value' => 0,
            ),
            'attributes' => array(
                'class' => "stdCheckboxInput",
                
            )
        ));
                   
    }
}