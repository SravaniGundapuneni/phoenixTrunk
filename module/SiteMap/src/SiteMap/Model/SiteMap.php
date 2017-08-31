<?php
/**
 * Property Model
 *
 * @category    Toolbox
 * @package     PhoenixProperties
 * @subpackage  Model
 * @copyright   Copyright (c) 2014 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.5
 * @since       File available since release 13.5
 * @author      Alex Kotsores <akotsores@travelclick.com>
 * @filesource
 */
namespace SiteMap\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Pages\EventManager\Event as PagesEvent;

class SiteMap extends \ListModule\Model\ListItem
{
    const ENTITY_NAME = 'SiteMap\Entity\PhoenixSiteMap';

    public function __construct($config = array(), $fields = array())
    {
        $this->entityClass = self::ENTITY_NAME;
        parent::__construct($config, $fields);
    }
}