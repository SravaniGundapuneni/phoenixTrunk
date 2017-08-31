<?php
/**
 * MediaManagerForm
 *
 * This will be the main form the Media Manager
 *
 * @category                    Toolbox
 * @package                     MediaManager
 * @subpackage                  Form
 * @copyright                   Copyright (c) 2013 TravelClick
 * @license                     All Rights Reserved
 * @since                       File available since release 13.5
 * @author                      Kevin Davis <kedavis@travelclick.com>
 * @filesource
*/

namespace MediaManager\Form;

/**
 * MediaManagerForm
 *
 * This will be the main form the Media Manager
 *
 * @category                    Toolbox
 * @package                     MediaManager
 * @subpackage                  Form
 * @copyright                   Copyright (c) 2013 TravelClick
 * @license                     All Rights Reserved
 * @since                       File available since release 13.5
 * @author                      Kevin Davis <kedavis@travelclick.com>
*/


class MediaManager extends Form
{
	public function __construct($name = null)
	{
		parent::__construct('mediaManager');
		$this->setAttribute('method', 'post');
		$this->add(array (
			'name' => 'id',
			 'type' => 'Hidden'
		));

		$this->add(array(
            'name' => 'filePath',
            'type' => 'hidden',
            'options' => array(
            	'label' => 'File Path',
            	'label_attributes' => array(
            		'class' => 'blockLabel')
            	),

             'attributes' => array(
             	'class' => 'stdTextInput',
             	),
			));
         
          $this->add(array(
          	'name' => 'fileName',
          	'type' => 'hidden',
          	'options' => array(
          		'label' => 'File Name',
          		'label_attributes' => array(
          			'class' => 'blockLabel')
          		),

          	  'attributes' => array(
          	  	'class' => 'stdTextInput'
          	  ),
          ));

          $this->add(array(
          	'name' => 'status',
          	'type' => 'checkbox',
          	'options' => array(
          		'label' => 'Status',
              'checked_value' => 1,
              'unchecked_value' => 0,
              'label_attributes' => array(
              	'class' => 'blockLabel')
          		),
             'attributes' => array(
             	//'class' => 'stdTextInputSmall',
             	),
          	 ));

             $this->add(array(
             	'name' => 'dataSection',
             	'type' => 'Textarea',
             	'options' => array(
             		'label' => 'Data Section',
             		'label_attributes' => array(
             			'class' => 'blockLabel')
             		 ),
                 'attributes' => array(
                   'class' => 'stdTextInput'
                  ),
             	));

             $this->add(array(
             	'name' => 'userId',
             	'type' => 'hidden',
             	'options' => array(
             		'label' => 'User Id',
             		'label_attributes' => array(
             			'class' => 'blockLabel')
             		),
             	'attributes' => array(
             		'class' => 'stdTextInput'
             	),
             ));

            $this->add(array(
            	'name' => 'height',
            	'type' => 'hidden',
            	'options' => array(
            		'label' => 'Height',
            		'label_attributes' => array(
            			'class' => 'blockLabel')
            		),
            	'attributes' => array(
            		'class' => 'stdTextInput'
            	),
            ));

            $this->add(array(
            	'name' => 'height',
            	'type' => 'hidden',
            	'option' => array(
            		'label' => 'Width',
            		'label_attributes' => array(
            			'class' => 'blockLabel')
            		),
            	'attributes' => array(
            		'class' => 'stdTextInput'
            		),
            	));


             $this->add(array(
             	'name' => 'author',
             	'type' => 'hidden',
             	'option' => array(
             		'label' => 'Author',
             		'label_attributes' => array(
             			'class' => 'blockLabel')
             		),
             	'attributes' => array(
             		'class' => 'stdTextInput'
             		),
             	));

             $this->add(array(
             	'name' => 'date',
             	'type' => 'hidden',
             	'option' => array(
             		'label' => 'Date Uploaded',
             		'label_attributes' => array(
             			'class' => 'blockLabel')
             		),
                'attributes' => array(
                	'class' => 'stdTextInput'
                	),
             	));

             $this->add(array(
             	'name' => 'fileNameOrig',
             	'type' => 'hidden',
             	'option' => array(
             		'label' => 'File Name Original',
             		'label_attributes' => array(
             			'class' => 'blockLabel')
             		),
             	'attributes' => array(
             		'class' => 'stdTextInput'
             		),
             	));

             $this->add(array(
             	'name' => 'created',
             	'type' => 'hidden',
             	'option' => array(
             		'label' => 'Date Created',
             		'label_attributes' => array(
             			'class' => 'blockLabel')
             		),
             	'attributes' => array(
             		'class' => 'stdTextInput'
             		),
             	));

             $this->add(array(
             	'name' => 'modified',
             	'type' => 'hidden',
             	'option' => array(
             		'label' => 'Date Modified',
             		'label_attributes' => array(
             			'class' => 'blockLabel')
             		),
             		'attributes' => array(
             			'class' => 'stdTextInput'
             			),
             	   ));
             



	}
}