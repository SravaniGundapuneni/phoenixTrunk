<?php
/**
 * The Page Model class
 *
 * Contains the declaration of the Page Model class.
 *
 * @category    Toolbox
 * @package     Pages
 * @subpackage  Model
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: PhoenixTNG
 * @since       File available since release PhoenixTNG
 * @author      A. Tate <atate@travelclick.com>
 * @filesource
 */
namespace Pages\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Pages\EventManager\Event as PagesEvent;
use ListModule\Model\UnifiedListItem;

class Page extends UnifiedListItem
{
    const ENTITY_NAME = 'Pages\Entity\PagesItems';
    const PAGE_DATA_ENTITY = 'pagesData';
    protected $dataEntityClass = 'Pages\Entity\Pages';

    /**
    * This is used for the merged config. Not to be confused with the pageConfig.
    * @var Config\Model\MergedConfig
    */
    protected $config;

    /**
     * This holds the pages configuration. This can come from a config file on a site, or from the database
     * @var Config\Model\MergedConfig
     */
    protected $pageConfig;

    protected $pageProperties;

    public function __construct($config = array(), $fields = array())
    {
        $this->entityClass = self::ENTITY_NAME;
        $this->dataEntity = self::PAGE_DATA_ENTITY;        
        parent::__construct($config, $fields);
    }

    public function setPageConfig($pageConfig)
    {
        $this->pageConfig = $pageConfig;
    }

    public function getPageConfig()
    {
        return $this->pageConfig;
    }

    public function getPageKey()
    {
        return ($this->getEntity()) ? parent::getPageKey() : $this->getPageConfig()->get('pageKey', 'default');
    }

    public function getPageType()
    {
        return ($this->getEntity()) ? parent::getPageType() : 'contentpage';
    }
    public function getId()
    {
        return ($this->getEntity()) ? $this->getEntity()->getId() : false;
    }
    
    public function getPagePath()
    {
        $pageKey = $this->getPagekey();
        $dataSection = $this->getDataSection();

        $pagePath = '';

        if (!empty($dataSection)) {
            $pagePath .= $dataSection . '/';
        }

        if (!empty($pageKey) && $pageKey !== 'default') {
            $pagePath .= $pageKey;
        }

        return $pagePath;
    }

    public function getTemplate( $defaultTemplate = null)
    {
        $template = $this->getEntity() ? parent::getTemplate() : null;
        return !empty($template) ? $template : $this->getPageConfig()->get('template', $defaultTemplate);
    }

    public function getArrayCopy()
    {
        $pageArray = new \Zend\Stdlib\ArrayObject(parent::getArrayCopy());
        $pageArray->setFlags(\Zend\Stdlib\ArrayObject::ARRAY_AS_PROPS);

        $this->getArrayCopyDates($pageArray);
        $this->getArrayCopyAdditionalParams($pageArray);
		 
        $this->getEventManager()->trigger(PagesEvent::EVENT_PAGE_GETARRAYCOPY, '\Pages\EventManager\Event', array('pageArray' => $pageArray));

        return $pageArray->getArrayCopy();
    }

    /**
     * @todo This is not a good way to handle this. Should look into better ways to handle this.
     *       A. Tate <atate@travelclick.com|actate81@gmail.com>
     * @return array
     */
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

        return $properties;
    }

    /**
     * @todo This is not a standard implementation. If this is something we want to replicate, we need to figure out how to do it.
     * @return array
     */
    public function getHotelOptionValues()
    {
        $optionValues = array();

        $pageProperties = $this->getPageProperties();

        if (empty($pageProperties)) {
            return false;
        }

        $property = current($pageProperties);

        return $property->getId();
    }

    public function getItemId($id)
    {         
         $hero = $this->getDefaultEntityManager()->getRepository('HeroImages\Entity\HeroImages')->findOneBy(array('pageId'=>$id));  

         $pageId = false;

         if (!empty($hero)) {
            $pageId = $hero->getItem()->getId();
         }

         return $pageId;
    }

    public function exchangeArray($loadArray)
    {
        $this->exchangeDates($loadArray);
        $this->exchangeProperties($loadArray);
        parent::exchangeArray($loadArray);
    }

    public function setPageProperties($properties)
    {
        $this->pageProperties = (is_array($properties)) ? $properties : array();
    }

    public function getInputFilter()
    {
        $inputFilter = parent::getInputFilter();
        $factory = new InputFactory();
        
        $inputFilter->add($factory->createInput(array(
                          'name' => 'hotel',
                          'required' => false,
                          'disable_inarray_validator' => true
              )));
        //  $inputFilter->add($factory->createInput(array(
        //             'name' => 'pageKey',
        //             'validators' => array(
        //                 array(
        //                     'name' => 'NotEmpty',
        //                     'options' => array(
        //                         'messages' => array(
        //                             \Zend\Validator\NotEmpty::IS_EMPTY =>
        //                             'Please enter page url.'
        //                         )
                                
        //                     )
        //                 ),
        // ))));

        $this->getEventManager()->trigger(PagesEvent::EVENT_PAGE_GETINPUTFILTER, '\Pages\EventManager\Event', array('inputFilter' => $inputFilter));
        
        
        return $inputFilter;
    }

    public function save()
    {
        $this->setStatusByDate();

        parent::save();

        $qbDel = $this->getDefaultEntityManager()->createQueryBuilder();

        if (!empty($this->pageProperties)) {
            $qbDel->delete('Pages\Entity\PageProperties', 'pp')
                  ->where('pp.pageId = :pageId')
                  ->setParameter('pageId', $this->getId());
            $qbDel->getQuery()->execute();
        }

        foreach ($this->pageProperties as $valProperty) {
            $ppEntity = new \Pages\Entity\PageProperties();
            $ppEntity->setPageId($this->getId());
            $ppEntity->setPropertyId($valProperty);
            $this->getDefaultEntityManager()->persist($ppEntity);
        }

        $this->getDefaultEntityManager()->flush();
    }
    
    public function isAttached($pageId)
	{
        $hero = $this->getDefaultEntityManager()->getRepository('HeroImages\Entity\HeroImages')->findBy(array('pageId'=>$pageId));

        return  ((!empty($hero)) ? true : false);
    }

    /**
     * Set status based on current date and start/expire times if status is not archived or trashed,
     *
     * @return void;
     */
    protected function setStatusByDate()
    {
        $entity = $this->getEntity();
        
        $status = $entity->getStatus();
        $startDate =  $entity->getStartDate();
        $expireDate =  $entity->getAutoExpire();
        $now = new \DateTime('now');
        if (!($status == self::ITEM_STATUS_ARCHIVED || $status == self::ITEM_STATUS_TRASHED))
        {
            if (!empty($expireDate) && $now > $expireDate || !empty($startDate) && $now < $startDate )
            {
                $entity->setStatus(self::ITEM_STATUS_ARCHIVED);
            }
            elseif ((empty($startDate) || $now >= $startDate)&&(empty($expireDate) || $now <= $expireDate))
            {
                 $entity->setStatus(self::ITEM_STATUS_PUBLISHED);
            }
            if (is_null($this->getEntity()->getBlocks())) {
                $this->getEntity()->setBlocks('');
            }
        }
    }    

    protected function exchangeDates($loadArray)
    {
        $dateArrays = array('startDate', 'autoExpire');

        foreach ($dateArrays as $valDate) {
            if (!isset($loadArray[$valDate])) {
                continue;
            }
            $dateValue = $loadArray[$valDate];

            if ($loadArray['pageType'] == 'contentpage') {
                $dateValue = null;
            }

            if (is_string($dateValue)) {
                $dateValue = !empty($dateValue) ? new \DateTime($dateValue) : null;
            }

            $loadArray[$valDate] = $dateValue;
        }    
    }

    protected function exchangeProperties($loadArray)
    {

        if (!empty($loadArray['hotel'])) {
            $hotelValue = $loadArray['hotel'];

            if (!is_array($hotelValue)) {
                $hotelValue = array($hotelValue);
            }
        }

        $this->setPageProperties($value);

        unset($loadArray['hotel']);
    }

    protected function getArrayCopyDates($arrayCopy)
    {
        $startDate = $arrayCopy['startDate'];
        $autoExpire = $arrayCopy['autoExpire'];

        $arrayCopy['startDate'] = (is_null($startDate)) ? '' : $startDate->format('Y-m-d');
        $arrayCopy['autoExpire'] = (is_null($autoExpire)) ? '' : $autoExpire->format('Y-m-d');
    }

    protected function getArrayCopyAdditionalParams($arrayCopy)
    {
        $arrayCopy['additionalParams'] = str_replace('rateCode=', '', $arrayCopy['additionalParams']);
    }
}
