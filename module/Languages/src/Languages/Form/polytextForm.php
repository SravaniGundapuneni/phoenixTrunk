<?php
/**
 * PropertyForm Class
 *
 * This extends Zend\Form and creates a base form class for the Phoenix Property Model.
 *
 * @category    Toolbox
 * @package     PhoenixProperties
 * @subpackage  Form
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.4
 * @since       File available since release 13.4
 * @author      A. Tate <atate@travelclick.com>
 * @filesource
 */
namespace Languages\Form;

class PolytextForm extends \ListModule\Form\Form
{
    /**
     * __construct
     *
     * The form class constructor
     *
     * This sets up all of the fields, which can then be modified to the needs of the code the form is being used in.
     * @param string $name
     */
    public function __construct($name = null)
    {
        // we want to ignore the name passed
        parent::__construct('polytext');

        $this->setAttribute('method', 'post');

         $this
            ->add($this->text('type', 'Type',1))
            ->add($this->text('area', 'Text Area:',1))
            ->add($this->text('name', 'Text Name:',1))
            ->add($this->text('lang', 'Language:',1))
            ->add($this->textarea('text', 'Text Value:'));
    }
}

