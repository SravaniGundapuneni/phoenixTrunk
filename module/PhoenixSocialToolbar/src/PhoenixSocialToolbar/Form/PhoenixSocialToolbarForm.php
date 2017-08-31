<?php
/**
 * SocialToolbarForm Class
 * 
 * This extends Zend\Form and create a base from class for the Phoenix Social Toolbar.
 * 
 * @category       Toolbox
 * @package        SocialToolbar
 * @subpackage     Form
 * @copyright      Copyright (c) 2013 TravelClick,  Inc (http://travelclick.com)
 * @license        All Rights Reserved
 * @version        Release 13.5
 * @since          File available for release since 13.5
 * @author         Kevin Davis <kedavis@travelclick.com>
 * @filesource
 */

namespace PhoenixSocialToolbar\Form;

/**
 * SocialToolbarForm Class
 * 
 * This extends Zend\Form and create a base from class for the Phoenix Social Toolbar.
 * 
 * @category       Toolbox
 * @package        SocialToolbar
 * @subpackage     Form
 * @copyright      Copyright (c) 2013 TravelClick,  Inc (http://travelclick.com)
 * @license        All Rights Reserved
 * @version        Release 13.5
 * @since          File available for release since 13.5
 * @author         Kevin Davis <kedavis@travelclick.com>
 */

use Zend\InputFilter;
use Zend\Form\Element;
use Zend\Form\Element\Checkbox as CheckboxElement;
use Zend\Form\Form;

class PhoenixSocialToolbarForm extends Form
{
    public function __construct($name = null)
    {
       parent::__construct('socialToolbar');
       $this->setAttribute('method', 'post');
       $this->add(array(
           'name' => 'id',
           'type' => 'hidden',
       ))

       ->add(array(
        'name' => 'toolbarEnabled',
        'type' => 'checkbox',
        'validator' => 'false',
        'options' => array(
            'label' => 'Show Toolbar:',
            'label_attributes' => array('class' => 'blocklabel'),
            'checked_value' => 1,
            'unchecked_value' => 0,
            ),
        'attributes' => array(

            'class' => 'textSwitchHolder version_1',
            )
         ))

       ->add(array(
        'name' => 'layout',
        'type' => 'checkbox',
        'options' => array(
            'label' => 'Minimized:',
            'label_attributes' => array('class' => 'blocklabel'),
            'checked_value' => 1,
            'unchecked_value' => 0,
            ),
        'attributes' => array(
            'class' => 'textSwitchHolder version_1',
            )
         ))
        
       ->add(array(
        'name' => 'smFacebook',
        'type' => 'text',
        'options' => array(
          'label' => 'Facebook URL:',
          'label_attributes' => array(
            'class' => 'blocklabel'
            )
          ),
        'attributes' => array(
          'class' => 'textSwitchHolder version_1',
          )
        ))

       ->add(array(
        'name' => 'smFacebookEnabled',
        'type' => 'checkbox',
        'options' => array(
          'label' => 'Facebook Enabled',
          'label_attributes' => array(
            'class' => 'blocklabel'),
            'checked_value' => 1,
            'unchecked_value' => 0,
            ),
        'attributes' => array(
          'class' => 'textSwitchHolder version_1',
          )
        ))

       ->add(array(
            'name' => 'showStreamOrFaces',
            'type' => 'radio',
            'options' => array(
                'label' => 'Stream',
                'value_options' => array(
                    '0' => 'Stream',
                    '1' => 'Faces',
                ),
            ),
            'label_attributes' => array(
                'class' => 'blocklabel'
            ),
            'attributes' => array(
                'class' => 'textSwitchHolder version_1',
            )
        ))

      ->add(array(
        'name' => 'smTwitter',
        'type' => 'text',
        'options' => array(
          'label' => 'Twitter Username:',
          'label_attributes' => array(
            'class' => 'blocklabel'
            )
          ),
        'attributes' => array(
          'class' => 'textSwitchHolder version_1',
          )
        ))

       ->add(array(
        'name' => 'smTwitterEnabled',
        'type' => 'checkbox',
        'options' => array(
          'label' => '',
          'label_attributes' => array(
            'class' => 'blocklabel'),
            'checked_value' => 1,
            'unchecked_value' => 0,
            ),
        'attributes' => array(
          'class' => 'textSwitchHolder version_1',
          )
        ))

       ->add(array(
        'name' => 'smYouTube',
        'type' => 'text',
        'options' => array(
          'label' => 'YouTube Video:',
          'label_attributes' => array(
            'class' => 'blocklabel'
            )
          ),
        'attributes' => array(
          'class' => 'textSwitchHolder version_1',
          )
        ))

       ->add(array(
        'name' => 'smYouTubeEnabled',
        'type' => 'checkbox',
        'options' => array(
          'label' => '',
          'label_attributes' => array(
            'class' => 'blocklabel'),
            'checked_value' => 1,
            'unchecked_value' => 0,
            ),
        'attributes' => array(
          'class' => 'textSwitchHolder version_1',
          )
        ))

       ->add(array(
        'name' => 'smTripAdivsor',
        'type' => 'text',
        'options' => array(
          'label' => 'Tripadvisor URL:',
          'label_attributes' => array(
            'class' => 'blocklabel'
            )
          ),
        'attributes' => array(
          'class' => 'textSwitchHolder version_1',
          )
        ))

       ->add(array(
        'name' => 'smTripAdivsorEnabled',
        'type' => 'checkbox',
        'options' => array(
          'label' => '',
          'label_attributes' => array(
            'class' => 'blocklabel'),
            'checked_value' => 1,
            'unchecked_value' => 0,
            ),
        'attributes' => array(
          'class' => 'textSwitchHolder version_1',
          )
        ))

       ->add(array(
        'name' => 'iconPreset',
        'type' => 'select',
        'options' => array(
          'label' => 'Icon Preset:',
          'value_options' => array(
            '1' => 'Standard',
            '2' => 'Circle',
            '3' => 'Flat',
            ),
          'label_attributes' => array(
            'class' => 'blocklabel'),
            '1' => 'Standard',
            '2' => 'Circle',
            '3' => 'Flat'
            ),
        'attributes' => array(
          'class' => 'textSwitchHolder version_1',
          )
        ))

       ->add(array(
        'name' => 'toolbarLayout',
        'type' => 'select',
        'options' => array(
          'label' => 'Toolbar Layout:',
           'value_options' => array(
            '1' => 'Default',
            '2' => 'Vertical Left',
            '3' => 'Vertical Right',
            ),
          'label_attributes' => array(
            'class' => 'blocklabel'),
            '1' => 'Default',
            '2' => 'Vertical Left',
            '3' => 'Vertical Right'
            ),
        'attributes' => array(
          'class' => 'textSwitchHolder version_1',
          )
        ))

        ->add(array(
        'name' => 'colorScheme',
        'type' => 'select',
        'options' => array(
          'label' => 'Color Scheme:',
          'value_options' => array(
            '1' => 'Light',
            '2' => 'Dark',
            '3' => 'Custom',
            ),
          'label_attributes' => array(
            'class' => 'blocklabel'),
            '1' => 'Light',
            '2' => 'Dark',
            '3' => 'Custom'
            ),
        'attributes' => array(
          'class' => 'textSwitchHolder version_1',
          )
        ))

        ->add(array(
        'name' => 'gradientTop',
        'type' => 'text',
        'options' => array(
          'label' => 'Gradient Top',
          'label_attributes' => array(
            'class' => 'blocklabel'
            )
          ),
        'attributes' => array(
          'class' => 'textSwitchHolder version_1',
          )
        ))

        ->add(array(
        'name' => 'gradientBottom',
        'type' => 'text',
        'options' => array(
          'label' => 'Gradient Bottom',
          'label_attributes' => array(
            'class' => 'blocklabel'
            )
          ),
        'attributes' => array(
          'class' => 'textSwitchHolder version_1',
          )
        ))

        ->add(array(
        'name' => 'layoutFont',
        'type' => 'text',
        'options' => array(
          'label' => 'Font',
          'label_attributes' => array(
            'class' => 'blocklabel'
            )
          ),
        'attributes' => array(
          'class' => 'textSwitchHolder version_1',
          )
        ))

        ->add(array(
        'name' => 'layoutBorders',
        'type' => 'text',
        'options' => array(
          'label' => 'Borders',
          'label_attributes' => array(
            'class' => 'blocklabel'
            )
          ),
        'attributes' => array(
          'class' => 'textSwitchHolder version_1',
          )
        ))

        ->add(array(
        'name' => 'rounded',
        'type' => 'checkbox',
        'options' => array(
          'label' => 'Rounded:',
          'label_attributes' => array(
            'class' => 'blocklabel'),
            'checked_value' => 1,
            'unchecked_value' => 0,
            ),
        'attributes' => array(
          'class' => 'textSwitchHolder version_1',
          )
        ))

      ->add(array(
        'name' => 'extended',
        'type' => 'checkbox',
        'options' => array(
          'label' => 'Extended:',
          'label_attributes' => array(
            'class' => 'blocklabel'),
            'checked_value' => 1,
            'unchecked_value' => 0,
            ),
        'attributes' => array(
          'class' => 'textSwitchHolder version_1',
          )
        ))
      
       ->add(array(
        'name' => 'showLabel',
        'type' => 'checkbox',
        'options' => array(
          'label' => 'Show Label:',
          'label_attributes' => array(
            'class' => 'blocklabel'),
            'checked_value' => 1,
            'unchecked_value' => 0,
            ),
        'attributes' => array(
          'class' => 'textSwitchHolder version_1',
          )
        ))

       ->add(array(
        'name' => 'shareTooltip',
        'type' => 'checkbox',
        'options' => array(
          'label' => 'Share as Tooltip:',
          'label_attributes' => array(
            'class' => 'blocklabel'),
            'checked_value' => 1,
            'unchecked_value' => 0,
            ),
        'attributes' => array(
          'class' => 'textSwitchHolder version_1',
          )
        ))

        ->add(array(
        'name' => 'onlyIcons',
        'type' => 'checkbox',
        'options' => array(
          'label' => 'Only Icons:',
          'label_attributes' => array(
            'class' => 'blocklabel'),
            'checked_value' => 1,
            'unchecked_value' => 0,
            ),
        'attributes' => array(
          'class' => 'textSwitchHolder version_1',
          )
        ))

        ->add(array(
        'name' => 'toolbarSize',
        'type' => 'text',
        'options' => array(
          'label' => 'Toolbar size:',
          'label_attributes' => array(
            'class' => 'blocklabel'
            )
          ),
        'attributes' => array(
          'class' => 'textSwitchHolder version_1',
          )
        ))

        ->add(array(
        'name' => 'butTwitter',
        'type' => 'checkbox',
        'options' => array(
          'label' => 'Twitter:',
          'label_attributes' => array(
            'class' => 'blocklabel'),
            'checked_value' => 1,
            'unchecked_value' => 0,
            ),
        'attributes' => array(
          'class' => 'textSwitchHolder version_1',
          )
        ))

        ->add(array(
        'name' => 'butPinterest',
        'type' => 'checkbox',
        'options' => array(
          'label' => 'Pinterest:',
          'label_attributes' => array(
            'class' => 'blocklabel'),
            'checked_value' => 1,
            'unchecked_value' => 0,
            ),
        'attributes' => array(
          'class' => 'textSwitchHolder version_1',
          )
        ))

        ->add(array(
        'name' => 'butGoogle',
        'type' => 'checkbox',
        'options' => array(
          'label' => 'Google+:',
          'label_attributes' => array(
            'class' => 'blocklabel'),
            'checked_value' => 1,
            'unchecked_value' => 0,
            ),
        'attributes' => array(
          'class' => 'textSwitchHolder version_1',
          )
        ))

        ->add(array(
        'name' => 'butFacebook',
        'type' => 'checkbox',
        'options' => array(
          'label' => 'Facebook:',
          'label_attributes' => array(
            'class' => 'blocklabel'),
            'checked_value' => 1,
            'unchecked_value' => 0,
            ),
        'attributes' => array(
          'class' => 'textSwitchHolder version_1',
          )
        ))

        ->add(array(
        'name' => 'butEmail',
        'type' => 'checkbox',
        'options' => array(
          'label' => 'Notify by E-mail:',
          'label_attributes' => array(
            'class' => 'blocklabel'),
            'checked_value' => 1,
            'unchecked_value' => 0,
            ),
        'attributes' => array(
          'class' => 'textSwitchHolder version_1',
          )
        ))

         ->add(array(
        'name' => 'imgTwitter',
        'type' => 'checkbox',
        'options' => array(
          'label' => 'Twitter:',
          'label_attributes' => array(
            'class' => 'blocklabel'),
            'checked_value' => 1,
            'unchecked_value' => 0,
            ),
        'attributes' => array(
          'class' => 'textSwitchHolder version_1',
          )
        ))

        ->add(array(
        'name' => 'imgGoogle',
        'type' => 'checkbox',
        'options' => array(
          'label' => 'Google+:',
          'label_attributes' => array(
            'class' => 'blocklabel'),
            'checked_value' => 1,
            'unchecked_value' => 0,
            ),
        'attributes' => array(
          'class' => 'textSwitchHolder version_1',
          )
        ))

         ->add(array(
        'name' => 'imgFacebook',
        'type' => 'checkbox',
        'options' => array(
          'label' => 'Facebook:',
          'label_attributes' => array(
            'class' => 'blocklabel'),
            'checked_value' => 1,
            'unchecked_value' => 0,
            ),
        'attributes' => array(
          'class' => 'textSwitchHolder version_1',
          )
        ))

        ->add(array(
        'name' => 'imgFacebookTitle',
        'type' => 'text',
        'options' => array(
          'label' => 'Facebook Title:',
          'label_attributes' => array(
            'class' => 'blocklabel'
            )
          ),
        'attributes' => array(
          'class' => 'textSwitchHolder version_1',
          )
        ))

        ->add(array(
        'name' => 'imgFacebookCaption',
        'type' => 'text',
        'options' => array(
          'label' => 'Facebook Caption:',
          'label_attributes' => array(
            'class' => 'blocklabel'
            )
          ),
        'attributes' => array(
          'class' => 'textSwitchHolder version_1',
          )
        ))

        ->add(array(
        'name' => 'imgFacebookDescription',
        'type' => 'text',
        'options' => array(
          'label' => 'Facebook Description:',
          'label_attributes' => array(
            'class' => 'blocklabel'
            )
          ),
        'attributes' => array(
          'class' => 'textSwitchHolder version_1',
          )
        ));
    } 
}