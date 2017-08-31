<?php

/**
 * MediaImage Class
 *
 * This will be the form for the upload of the image
 * 
 * @category            Toolbox
 * @package             MediaManager
 * @subpackage          Form
 * @copyright           Copyright (c) 2013 TraveClick, Inc (http://travelclick.com)
 * @license             All Rights Reserved
 * @since               File available since release 13.5
 * @author              Kevin Davis <kedavis@travelclick.com>
 * @filesource
 */

namespace MediaManager\Form\Element;

/**
 * MediaManagerUploadForm Class
 *
 * This will be the form for the upload of the image
 * 
 * @category            Toolbox
 * @package             MediaManager
 * @subpackage          Form
 * @copyright           Copyright (c) 2013 TraveClick, Inc (http://travelclick.com)
 * @license             All Rights Reserved
 * @since               File available since release 13.5
 * @author              Kevin Davis <kedavis@travelclick.com>
 */
use Zend\InputFilter;
use Zend\Form\Element;
use Zend\Form\Form;

class MediaImage extends Form
{

    public function __construct($name = null, $options = array())
    {
        parent:: __construct($name, $options);
        $this->setAttribute('method', 'post');
        $this->addElements();
        $this->add(array(
            'name' => 'id',
            'type' => 'hidden'
        ));

        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type' => 'submit',
                'value' => 'Go',
                'id' => 'submitbutton',
            )
                )
        );
    }

    public function addElements()
    {
        $text = new Element\Text('alt');
        $text->setLabel('Alt Text');
        $this->add($text);
    }

}
