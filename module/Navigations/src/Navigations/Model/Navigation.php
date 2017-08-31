<?php

/**
 * Navigationss Model
 *
 * @category    Toolbox
 * @package     Navigations
 * @subpackage  Model
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.6
 * @since       File available since release 13.6
 * @filesource
 */

namespace Navigations\Model;

use ListModule\Model\UnifiedListItem;

class Navigation extends UnifiedListItem
{

    const NAVIGATION_ENTITY_NAME = 'Navigations\Entity\NavigationsItems';
    const NAVIGATION_DATA_ENTITY = 'navigationsData';
    protected $dataEntityClass = 'Navigations\Entity\Navigations';

    public function __construct($config = array(), $fields = array())
    {
        $this->entityClass = self::NAVIGATION_ENTITY_NAME;
        $this->dataEntity = self::NAVIGATION_DATA_ENTITY;

        parent::__construct($config, $fields);
    }

    public function getPageIdOptionValues()
    {
        $optionValues = array();

        $pageProperties = $this->getPageProperties();

        foreach ($pageProperties as $valProperty) {
            $optionValues[] = $valProperty->getId();
        }
        return $optionValues;
    }

    public function getPageProperties()
    {
        $properties = array();
        $propertyIds = $this->getDefaultEntityManager()->getRepository('Pages\Entity\Pages')->findBy(array('pageId' => $this->getId()));
        foreach ($propertyIds as $valProperty) {
            $property = $this->getDefaultEntityManager()->getRepository('Pages\Entity\Pages')->findOneBy(array('id' => $valProperty->getPageId()));
            if (!empty($property)) {
                $properties[] = $property;
            }
        }
        return $properties;
    }

}
