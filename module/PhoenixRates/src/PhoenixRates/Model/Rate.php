<?php

/**
 * Rate Model
 *
 * @category    Toolbox
 * @package     PhoenixRates
 * @subpackage  Model
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.4
 * @since       File available since release 13.4
 * @author      A. Tate <atate@travelclick.com>
 * @filesource
 */

namespace PhoenixRates\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use ListModule\Model\UnifiedListItem;

class Rate extends UnifiedListItem 
{

    const ENTITY_NAME = 'PhoenixRates\Entity\PhoenixRatesItems';
    const RATE_DATA_ENTITY = 'phoenixRatesData';
    protected $dataEntityClass = 'PhoenixRates\Entity\PhoenixRate';

    const DATES_TO_STRING = true;

    protected $inputFilter;

    public function __construct($config = array(), $fields = array()) 
    {
        $this->entityClass = self::ENTITY_NAME;
        $this->dataEntity = self::RATE_DATA_ENTITY;        
        parent::__construct($config, $fields);
       // var_dump($fields);
    }

    public function exchangeArray($loadArray) 
    {
         if (isset($loadArray['component'])) {
            $this->setComponent($loadArray['component']);
        }

        if (isset($loadArray['startDate']) && is_string($loadArray['startDate'])) {
            $loadArray['startDate'] = new \DateTime($loadArray['startDate']);
        }

        if (isset($loadArray['autoExpiry']) && is_string($loadArray['autoExpiry'])) {
            $loadArray['autoExpiry'] = new \DateTime($loadArray['autoExpiry']);
        }

        if (is_array($loadArray['policy'])) {
            $loadArray['policy'] = implode(' ', $loadArray['policy']);
        }

        parent::exchangeArray($loadArray);
    }

    /**
     * Pass in the extra data to forms
     * @param  boolean $datesToString
     * @return array
     */
    public function getArrayCopy($datesToString = false) 
    {
        $translations = $this->getTranslations();

        $defaultLanguageCode = $this->getLanguages()->getDefaultLanguage()->getCode();
        $arrayCopy = parent::getArrayCopy(static::DATES_TO_STRING);
         foreach ($this->getFields() as $valField) {
            if ($valField->getTranslate() == 1) {
                foreach ($this->getLanguages()->getLanguages() as $valLanguage) {
                    if (empty($arrayCopy[$valField->getName() . '_' . $valLanguage->getCode()])) {
                        if ($valLanguage->getCode() != $defaultLanguageCode) {
                                $arrayCopy[$valField->getName() . '_' . $valLanguage->getCode()] = $translations[$valField->getName()][$valLanguage->getCode()];
                        } else {
                            $arrayCopy[$valField->getName() . '_' . $valLanguage->getCode()] = $arrayCopy[$valField->getName()];
                        }
                    }

                    if ($valLanguage->getCode() == $this->getCurrentLanguage()->getCode() && $valLanguage->getCode() != $defaultLanguageCode) {

                        $arrayCopy[$valField->getName()] = $translations[$valField->getName()][$valLanguage->getCode()];
                    }
                }
            }
        }
        $arrayCopy['hotelName'] = $this->getProperty()->getName();
        $arrayCopy['isCorporate'] = $this->isCorporate(true);
        return $arrayCopy;
    }

    public function isCorporate($displayYesNo = false) 
    {
        $isCorporate = $this->getProperty()->getIsCorporate();
        if ($displayYesNo)
            $isCorporate = $this->getYesNo($isCorporate);

        return $isCorporate;
    }

}
