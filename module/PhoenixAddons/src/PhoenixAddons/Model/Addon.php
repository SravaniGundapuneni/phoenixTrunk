<?php

/**
 * Addons Model
 *
 * @category      Toolbox
 * @package       PhoenixAddons
 * @subpackage    Model
 * @copyright     Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license       All Rights Reserved
 * @version       Release 13.4
 * @since         File available since release 13.4
 * @author        Bradley Davidson <bdavidson@travelclick.com>
 * @filesource
 */

namespace PhoenixAddons\Model;
use ListModule\Model\UnifiedListItem;

class Addon extends UnifiedListItem {

    const ENTITY_NAME = 'PhoenixAddons\Entity\PhoenixAddonItems';
    const EVENT_DATA_ENTITY = 'phoenixAddonData';
    protected $dataEntityClass = 'PhoenixAddons\Entity\PhoenixAddons';    

    public function __construct($config = array(), $fields = array()) {
        $this->entityClass = self::ENTITY_NAME;
        parent::__construct($config, $fields);
    }

    /**
     * Pass in the extra data to forms
     * @param  boolean $datesToString
     * @return array
     */
    public function getArrayCopy($datesToString = false) {
        // $translations = $this->getTranslations();

        // $defaultLanguageCode = $this->getLanguages()->getDefaultLanguage()->getCode();
        $arrayCopy = parent::getArrayCopy($datesToString);


        // foreach ($this->getFields() as $valField) {
        //     if ($valField->getTranslate() == 1) {
        //         foreach ($this->getLanguages()->getLanguages() as $valLanguage) {
        //             if ($valLanguage->getCode() != $defaultLanguageCode) {

        //                 $arrayCopy[$valField->getName() . '_' . $valLanguage->getCode()] = $translations[$valField->getName()][$valLanguage->getCode()];
        //             } else {

        //                 $arrayCopy[$valField->getName() . '_' . $valLanguage->getCode()] = $arrayCopy[$valField->getName()];
        //             }

        //             if ($valLanguage->getCode() == $this->getCurrentLanguage()->getCode() && $valLanguage->getCode() != $defaultLanguageCode) {

        //                 $arrayCopy[$valField->getName()] = $translations[$valField->getName()][$valLanguage->getCode()];
        //             }
        //         }
        //     }
        // }
        //var_dump($arrayCopy);
        $arrayCopy['hotelName'] = $this->getProperty()->getName();
        $arrayCopy['isCorporate'] = $this->isCorporate(true);

        return $arrayCopy;
    }

    public function isCorporate($displayYesNo = false) {
        $isCorporate = $this->getProperty()->getIsCorporate();
        if ($displayYesNo)
            $isCorporate = $this->getYesNo($isCorporate);

        return $isCorporate;
    }

}
