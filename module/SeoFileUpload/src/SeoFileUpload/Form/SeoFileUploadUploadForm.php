<?php
/**
 * SeoFileUploadUploadForm Class
 *
 * This will be the form for the upload of the image
 * 
 * @category            Toolbox
 * @package             SeoFileUpload
 * @subpackage          Form
 * @copyright           Copyright (c) 2013 TraveClick, Inc (http://travelclick.com)
 * @license             All Rights Reserved
 * @since               File available since release 13.5
 * @author              Kevin Davis <kedavis@travelclick.com>
 * @filesource
*/

namespace SeoFileUpload\Form;


use Zend\InputFilter;
use Zend\Form\Element;
use Zend\Form\Form;

class SeoFileUploadUploadForm extends Form
{
	public function __construct ($name = null, $options = array())
	{
		parent:: __construct($name, $options);
		$this->addElements();
		$this->add(array(
			'name' => 'id',
			'type' => 'hidden'
			));
    }

    public function addElements()
    {
        echo "form<br/>";
        $file = new Element\File('upload-file');
        $file->setLabel('Media Upload')->setAttribute('id', 'upload-file');
        $this->add($file);
    }

}