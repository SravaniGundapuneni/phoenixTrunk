<?php
/**
 * The ListItem Model File
 *
 * @category    Toolbox
 * @package     ListModule
 * @subpackage  Model
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.4
 * @since       File available since release 13.4
 * @author      A. Tate <atate@travelclick.com>
 * @filesource
 */
namespace ListModule\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Category extends ListItem
{
    const ENTITY_NAME = '\ListModule\Entity\Categories';

    protected $entityClass = self::ENTITY_NAME;
}