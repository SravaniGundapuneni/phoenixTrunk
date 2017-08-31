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
 * @author      Daniel Yang <dyang@travelclick.com>
 * @filesource
 */
namespace HeroImages\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Pages\EventManager\Event as PagesEvent;

use ListModule\Model\UnifiedListItem;

class HeroImages extends UnifiedListItem
{
    const ENTITY_NAME = 'HeroImages\Entity\HeroImageItems';
    const HEROIMAGES_DATA_ENTITY = 'heroImagesData';
    protected $dataEntityClass = 'HeroImages\Entity\HeroImages';

    public function __construct($config = array(), $fields = array())
    {
        $this->entityClass = self::ENTITY_NAME;
        $this->dataEntity = self::HEROIMAGES_DATA_ENTITY;
        parent::__construct($config, $fields);
    }
}