<?php
/**
 * The ListModule FormElement View Helper File
 *
 * @category    Toolbox
 * @package     ListModule
 * @subpackage  Form
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.5.5
 * @since       File available since release 13.5.5
 * @author      A. Tate <atate@travelclick.com>
 * @filesource
 */
namespace ListModule\Form\View\Helper;

use Zend\Form\View\Helper\FormElement as ZendElement;

use Zend\Form\Element;
use Zend\Form\ElementInterface;

use MediaManager\Form\Element\MediaAttachments;

/**
 * The ListModule FormElement View Helper Class
 *
 * @category    Toolbox
 * @package     ListModule
 * @subpackage  Form
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.5.5
 * @since       Class available since release 13.5.5
 * @author      A. Tate <atate@travelclick.com>
 */
class FormElement extends ZendElement
{
    /**
     * This is only here because the default formElement helper is hard coded for the basic Zend helpers.
     * 
     * @param  ElementInterface $element
     * @return string
     */
    public function render(ElementInterface $element)
    {
        $renderer = $this->getView();
        if (!method_exists($renderer, 'plugin')) {
            // Bail early if renderer is not pluggable
            return '';
        }

        if ($element instanceof MediaAttachments) {
            $helper = $renderer->plugin('form_mediaAttachments');
            return $helper($element);
        }

        return parent::render($element);
    }
}
