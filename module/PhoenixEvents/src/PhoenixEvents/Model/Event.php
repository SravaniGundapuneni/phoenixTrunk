<?php
/**
 * Events Model
 *
 * @category        Toolbox
 * @package         PhoenixEvents
 * @subpackage      Model
 * @copyright       Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license         All Rights Reserved
 * @version         Release 13.5.5
 * @since           File available since release 13.5.5
 * @author          Kevin Davis <kedavis@travelclick.com>
 * @filesource
 */

namespace PhoenixEvents\Model;
use ListModule\Model\UnifiedListItem;

class Event extends UnifiedListItem
{
    const EVENT_ENTITY_NAME = 'PhoenixEvents\Entity\PhoenixEventItems';
    const EVENT_DATA_ENTITY = 'phoenixEventsData';
    protected $dataEntityClass = 'PhoenixEvents\Entity\PhoenixEvents';

    public function __construct($config = array(), $fields = array())
    {
        $this->entityClass = self::EVENT_ENTITY_NAME;
         parent::__construct($config, $fields);
    }
    public function getPropertyIdOptionValues()
    {
        $optionValues = array();

        $pageProperties = $this->getPageProperties();

        foreach ($pageProperties as $valProperty) {
            $optionValues[] = $valProperty->getId();
        }
        //$optionValues[] = 5; //inject dummy id
        var_dump($optionValues);

        return $optionValues;
    }
     public function getPageProperties()
    {
        $properties = array();
        $propertyIds = $this->getDefaultEntityManager()->getRepository('Pages\Entity\PageProperties')->findBy(array('pageId' => $this->getId()));
        foreach ($propertyIds as $valProperty) {
            $property = $this->getDefaultEntityManager()->getRepository('PhoenixProperties\Entity\PhoenixProperty')->findOneBy(array('id' => $valProperty->getPropertyId()));
            if (!empty($property)) {
                $properties[] = $property;
            }
        }
        var_dump($propertyIds);
        var_dump($properties);
        return $properties;
    }
}