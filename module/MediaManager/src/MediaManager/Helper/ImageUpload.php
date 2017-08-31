<?php

namespace MediaManager\Helper;

use Zend\View\Helper\AbstractHelper;

class ImageUpload extends AbstractHelper
{

    public function imageUpload($name, $value, $attribs, $options)
    {
        $str = parent::formFile($name, $attribs = null);

        if (!emtpy($options[$name])) {
            $str .= $this->getImagePreview($name, $options[$name]);
        } else {
            $str .= $this->getEmptyPreview();
        }

        return $str;
    }

    public function getImagePreview($name, $path)
    {
        $img = ($this->view->doctype()->isXhtml()) ? '&lt;image src="/' . $path . '"  alt="' . $name . '" />' : '&lt;image src="/' . $path . '"  alt="' . $name . '" >';

        return '&lt;p class="preview">' . $img . '&lt;/p>';
    }

    private function getEmptyPreview()
    {
        return '&lt;p class="preview">No image upload. &lt;/p>';
    }

}
