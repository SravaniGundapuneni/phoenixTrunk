<?php

/**
 * The SeoMetaText Service
 *
 * @category    Toolbox
 * @package     SeoMetaText
 * @subpackage  Service
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.6
 * @since       File available since release 13.6
 * @author      A. Tate <atate@travelclick.com>
 * @filesource
 */

namespace SeoMetaText\Service;

use SeoMetaText\Model\SeoMetaTexts;
use Pages\EventManager\Event as PagesEvent;
use ListModule\Service\UnifiedLists;

class SeoMetaText extends UnifiedLists
{

    public $_action = ''; //to identify user action for filtering items  
    public $currentPageId;

    /**
     * __construct
     *
     * Construct our Map Markers service
     *
     * @return void
     */
    public function __construct()
    {

        $this->entityName = SeoMetaTexts::SEOMETATEXTS_ENTITY_NAME;
        $this->modelClass = "\SeoMetaText\Model\SeoMetaTexts";
        $this->dataEntity = SeoMetaTexts::SEOMETATEXTS_DATA_ENTITY;
    }

//    public function getForm($formName, $serviceManager)
//    {
//        echo "getting form from SeoMetaText Service getForm()<br/>";
//        $form = parent::getForm($formName, $serviceManager);
//        echo "got form from SeoMetaText Service getForm()<br/>";
//        return $form;
//    }
    /**
     * getPropertyIdOptions
     *
     * @todo  Remove this from this service class, so the PhoenixPropeties module will be properly decoupled from Pages.
     * 
     * @return array
     */
    public function getPageIdOptions()
    {

        $options_total = array();
        //inject default property as Not Assigned
        $hotels = $this->getDefaultEntityManager()->getRepository('Pages\Entity\Pages')->findBy(array('status' => 1));

        if ($this->_action == 'addItem') {
            $getSeoRecords = $this->getDefaultEntityManager()->getRepository('SeoMetaText\Entity\SeoMetaText')->findAll();
            $seoPageId = array();
            foreach ($getSeoRecords as $valSeo) {
                $seoPageId[] = $valSeo->getPageId();
            }
            foreach ($hotels as $keyHotel => $valHotel) {
                if (!in_array($valHotel->getId(), $seoPageId)) {
                    $options_total[$valHotel->getId()] = $valHotel->getDataSection() . '/' . str_replace("default", "", $valHotel->getPageKey());
                }
            }
        } else {
            $getCurrentRecords = $this->getDefaultEntityManager()->getRepository('SeoMetaText\Entity\SeoMetaText')->findById($this->currentPageId);
            $currentPageId = $getCurrentRecords[0]->getPageId();
            $queryString = $this->getDefaultEntityManager()->createQueryBuilder();

            $queryString->select('seo')
                    ->from('SeoMetaText\Entity\SeoMetaText', 'seo')
                    ->where('seo.pageId != :pageId')
                    ->setParameter('pageId', $currentPageId);

            $getSeoRecords = $queryString->getQuery()->getResult();
            $seoPageId = array();
            foreach ($getSeoRecords as $valSeo) {
                $seoPageId[] = $valSeo->getPageId();
            }
            foreach ($hotels as $keyHotel => $valHotel) {
                if (!in_array($valHotel->getId(), $seoPageId)) {
                    $options_total[$valHotel->getId()] = $valHotel->getDataSection() . '/' . str_replace("default", "", $valHotel->getPageKey());
                }
            }
        }
        $optionsUnique = array_unique(($options_total));
        natcasesort($optionsUnique);
        $arr1 = array('' => 'Not Assigned');
        $options = $arr1 + $optionsUnique;
        return $options;
    }

    public function getMetaText($pageId)
    {
        $metaTextArray = array();

        $result = $this->getItem($pageId);

        if (!empty($result)) {
            $metaTextArray[] = array(
                'metaH1' => $result->getMetaH1(),
                'metaTitle' => $result->getTitle(),
                'metaDescription' => $result->getMetaDescription(),
                'metaCanonical' => $result->getMetaCanonical()
            );
        }

        return $metaTextArray;
    }

    public function getCurrentPageId($pageId)
    {
        $this->currentPageId = $pageId;
    }

}
