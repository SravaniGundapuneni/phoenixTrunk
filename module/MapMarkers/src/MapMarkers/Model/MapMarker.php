<?php
/**
 * MapMarker Model
 *
 * @category    Toolbox
 * @package     MapMarkers
 * @subpackage  Model
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.6
 * @since       File available since release 13.6
 * @author      A. Tate <atate@travelclick.com>
 * @filesource
 */
namespace MapMarkers\Model;

class MapMarker extends \ListModule\Model\UnifiedListItem
{
    const MAPMARKER_ENTITY_NAME = 'MapMarkers\Entity\MapMarkerItems';
    const MAPMARKER_DATA_ENTITY = 'mapMarkerData';

    protected $dataEntityClass = 'MapMarkers\Entity\MapMarkers';

    public function __construct($config = array(), $fields = array())
    {
        $this->entityClass = self::MAPMARKER_ENTITY_NAME;
        $this->dataEntity = self::MAPMARKER_DATA_ENTITY;
       
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
        //var_dump($optionValues);

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
        //var_dump($propertyIds);
        //var_dump($properties);
        return $properties;
    }
}