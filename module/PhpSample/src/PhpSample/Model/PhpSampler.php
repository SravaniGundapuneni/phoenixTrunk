<?php
/**
 * PhpSampler Model
 *
 * @category    Toolbox
 * @package     PhpSample
 * @subpackage  Model
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.6
 * @since       File available since release 13.6
 * @author      A. Tate <atate@travelclick.com>
 * @filesource
 */
namespace PhpSample\Model;

class PhpSampler extends \ListModule\Model\UnifiedListItem
{
    const PHPSAMPLE_ENTITY_NAME = 'PhpSample\Entity\PhpSampleItems';
    const PHPSAMPLE_DATA_ENTITY = 'phpSampleData';

    protected $dataEntityClass = 'PhpSample\Entity\PhpSample';

    public function __construct($config = array(), $fields = array())
    {
        $this->entityClass = self::PHPSAMPLE_ENTITY_NAME;
        $this->dataEntity = self::PHPSAMPLE_DATA_ENTITY;
       
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