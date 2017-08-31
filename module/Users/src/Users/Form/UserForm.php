<?php
namespace Users\Form;

use ListModule\Form\Form;

use Users\Service\Acl;

class UserForm extends Form
{
    public function __construct($name = null)
    {
        // we want to ignore the name passed
        /**
         * @todo  Switch this over to using the less manual way that the ListModule forms do this
         * @todo  Also, we should move the default form fields out of ListModule\Form\Form and into a Phoenix FormAbstract class
         */
        parent::__construct('user');
        $this->setAttribute('method', 'post');

        $this->add(array(
            'name' => 'id',
            'type' => 'Hidden',
        ));

        $this->add(array(
            'name' => 'username',
            'type' => 'Text',
            'options' => array(
                'label' => 'Username',
                'label_attributes' => array(
                    'class' => 'blockLabel'
                )
            ),
            'attributes' => array(
                'class' => 'stdInputText'
            )
        ));

        $this->add(array(
            'name' => 'givenName',
            'type' => 'Text',
            'options' => array(
                'label' => 'Display Name',
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

        $this->add(array(
            'name' => 'type',
            'type' => 'select',
            'options' => array(
                'value_options' => array(0 => 'Normal',
                                         1 => 'SuperAdmin',
                                         2 => 'Developer'),
                'label' => 'Type',
                'label_attributes' => array(
                    'class' => 'blockLabel'
                )
            ),
            'attributes' => array(
                'class' => 'stdInputText'
            )            
        ));

        $this->add(array(
            'name' => 'email',
            'type' => 'Text',
            'options' => array(
                'label' => 'Email',
                'label_attributes' => array(
                    'class' => 'blockLabel'
                )
            ),
            'attributes' => array(
                'class' => 'stdInputText'
            )
        ));

        $this->add(array(
            'name' => 'password',
            'type' => 'Password',
            'options' => array(
                'label' => 'Password',
                'label_attributes' => array(
                    'class' => 'blockLabel'
                )
            ),
            'attributes' => array(
                'class' => 'stdInputText'
            )
        ));

        $this->add(array(
            'name' => 'passwordConfirm',
            'type' => 'Password',
            'options' => array(
                'label' => 'Confirm Password',
                'label_attributes' => array(
                    'class' => 'blockLabel'
                )
            ),
            'attributes' => array(
                'class' => 'stdInputText'
            )
        ));

        $this->add(array(
                'name' => 'baseAccessLevel',
                'type' => 'select',
                'options' => array(
                    'label' => 'Base Access Level',
                    'label_attributes' => array(
                        'class' => 'blockLabel'
                    ),
                    'value_options' => array(
                        Acl::PERMISSIONS_GROUP_READ => 'Read',
                        Acl::PERMISSIONS_GROUP_WRITE => 'Write',
                        Acl::PERMISSIONS_GROUP_APPROVE => 'Approve',
                        Acl::PERMISSIONS_GROUP_ADMIN => 'Admin'
                    )
                ),
                'attributes' => array(
                    'class' => 'stdInputText',
                )
            )
        );

        $this->add($this->checkbox('isCorporate', 'Is Corporate'));
    }
}