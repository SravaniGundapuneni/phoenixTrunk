<?php
namespace Languages\Helper;

use Zend\View\Helper\AbstractHelper;

/**
 * Takes the given $arguments and builds
 */
class PolytextElement extends AbstractHelper
{
    protected $textValue = null;
    protected $attributes = null;

    public function __invoke($bestMatch)
    {
        /**
         * Lets save the view from doing any logic
         */
        $this->textValue = \Phoenix\StdLib\ArrayHelper::getValueFromArray($bestMatch,'textValue');
        $this->attributes = array_diff_key($bestMatch,array('textValue'=>'Lets remove textValue'));

        /**
         * Build the element attributes
         */
        foreach ($this->attributes as $key => $value)
        {
            $this->attributes[$key] = " {$key}=\"{$value}\"";
        }

        $this->getView()->textValue = $this->textValue;
        $this->getView()->attributes = implode($this->attributes);

        return $this->getView()->render('polytext-element');
    }
}