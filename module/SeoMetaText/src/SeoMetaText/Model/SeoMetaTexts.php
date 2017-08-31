<?php
/**
 * SeoMetaTexts Model
 *
 * @category    Toolbox
 * @package     SeoMetaText
 * @subpackage  Model
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.6
 * @since       File available since release 13.6
 * @author      A. Tate <atate@travelclick.com>
 * @filesource
 */
namespace SeoMetaText\Model;

use ListModule\Model\UnifiedListItem;

class SeoMetaTexts extends UnifiedListItem
{
    const SEOMETATEXTS_ENTITY_NAME = 'SeoMetaText\Entity\SeoMetaTextItems';

    const SEOMETATEXTS_DATA_ENTITY = 'seoMetaTextData';
    protected $dataEntityClass = 'SeoMetaText\Entity\SeoMetaText';


    public function __construct($config = array(), $fields = array()) 
    {
        $this->entityClass = self::SEOMETATEXTS_ENTITY_NAME;
        $this->dataEntity = self::SEOMETATEXTS_DATA_ENTITY;        
        parent::__construct($config, $fields);

    }

    public function getPageIdOptionValues()
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
        $propertyIds = $this->getDefaultEntityManager()->getRepository('Pages\Entity\Pages')->findBy(array('pageId' => $this->getId()));
        foreach ($propertyIds as $valProperty) {
            $property = $this->getDefaultEntityManager()->getRepository('Pages\Entity\Pages')->findOneBy(array('id' => $valProperty->getPageId()));
            if (!empty($property)) {
                $properties[] = $property;
            }
        }
        //var_dump($propertyIds);
        //var_dump($properties);
        return $properties;
    }
}