<?php

namespace MediaManager\Helper;

use Zend\View\Helper\AbstractHelper;

class ImageElement extends AbstractHelper
{

    public function __invoke($bestMatch)
    {
        /**
         * Lets save the view from doing any logic
         */
        $this->imageValue = \Phoenix\StdLib\ArrayHelper::getValueFromArray($bestMatch, 'imageValue');
        $this->imageClass = \Phoenix\StdLib\ArrayHelper::getValueFromArray($bestMatch, 'sharingClass');
        $this->attributes = array_diff_key($bestMatch, array('imageValue' => true, 'sharingClass' => true));

        /**
         * Build the element attributes
         */
        foreach ($this->attributes as $key => $value) {
            $this->attributes[$key] = " {$key}=\"{$value}\"";
        }

        $this->getView()->imageValue = $this->imageValue;
        $this->getView()->imageClass = $this->imageClass;
        $this->getView()->attributes = implode($this->attributes);

        return $this->getView()->render('imageswitch-element');
    }

}
