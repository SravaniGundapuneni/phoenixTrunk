<?php
namespace MediaManager\Form\Element;

use Zend\Form\Element;

class MediaAttachments extends Element
{
    protected $validator;
    
    public function getValidator()
    {
        return $this->validator;
    }

    public function setValidator($validator)
    {
        $this->validator = $validator;

        return $this;
    }
}