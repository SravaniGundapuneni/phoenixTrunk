<?php

/**
 * The file for the Field model class for the FlexibleForms
 *
 * @category    Toolbox
 * @package     FlexibleForms
 * @subpackage  Model
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 1.14
 * @since       File available since release 1.14
 * @author      A. Tate <atate@travelclick.com>
 * @filesource
 */

namespace FlexibleForms\Model;

/**
 * This class extends from the base ListItem class in ListModule
 */
use \ListModule\Model\ListItem;

/**
 * The Field class for the FlexibleForms
 *
 * @category    Toolbox
 * @package     FlexibleForms
 * @subpackage  Model
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 1.14
 * @since       File available since release 1.14
 * @author      A. Tate <atate@travelclick.com>
 */
class Field extends ListItem
{

    /**
     * The name of the entity associated with this model
     */
    const ENTITY_NAME = '\FlexibleForms\Entity\FlexibleFormsFields';

    /**
     * The name of the entity class associated with this model
     *
     * So, yeah, this is just a property version of the above constant. The reason for this so we keep
     * the functionality of the constant, while having the property available to be used for dynamically
     * loading the entity class, which for some reason you can't use constant values to do that.
     * 
     * @var string
     */
    protected $entityClass = self::ENTITY_NAME;

    public function getInputFilter()
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
            $factory = new InputFactory();

            $inputFilter->add(
                    $factory->createInput(array(
                        'name' => 'image-file',
                        'required' => true,
                        'MaxFileSize' => 60000, // 2097152 bytes = 2 megabytes
                        'validators' => array(
                            array('Count', false, 1),
                            array('Size', false, 2097152),
                            array('Extension', false, 'doc')
                        ),
                    ))
            );

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }

}
