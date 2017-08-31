<?php

namespace PhoenixSite\Helper;

/**
 * The file for the PhoenixSite Helper
 *
 * @category    Toolbox
 * @package     LanguagesStatic data
 * @subpackage  Helper
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 1.14
 * @since       File available since release 1.14
 * @author     Sravani Gundapuneni <sgundapuneni@travelclick.com>
 */
use Zend\View\Helper\AbstractHelper;

class PhoenixSites extends \ListModule\Helper\ItemInformation {

    protected $formService;

    public function __construct($formService, $languageComponents) {
        $this->formService = $formService;
        $this->languageComponents = $languageComponents;
    }

    public function __invoke($components) {
      
        $componentsIds = $this->languageComponents->getItemsBy(array('name' => $components));
        $idArray = array();
        foreach ($componentsIds as $valResult) {
            $idArray[] = $valResult->getId();
        }
        $result = $this->formService->getItemsBy(array('component' => $idArray));
        $resultArray = array();
       
        foreach ($result as $valResult) {
            $item = $valResult->getArrayCopy();
            $resultMenu = array();
            //unset($item['component']);
            //unset($item['componentFields']);
            if (!empty($item['parentId']) && $item['parentId'] == 1) {

                $resultSubMenu = $this->formService->getItemsBy(array('parentId' => $item['id']));

                foreach ($resultSubMenu as $valResultMenu) {
                    $resultMenu[] = array(
                        'id' => $valResultMenu->getId(),
                        'name' => $valResultMenu->getName(),
                        'order' => $valResultMenu->getOrder(),
                        'label_en' => $valResultMenu->getLabel_en(),
                        'label_fr' => $valResultMenu->getLabel_fr(),
                    );
                }
            }
            $resultArray[trim($item['name'])] = array(
                'id' => $item['id'],
                'name' => $item['name'],
                'label' => $item['label'],
                'status' => $item['status'],
                'label_en' => $item['label_en'],
                'label_fr' => $item['label_fr'],
                'property' => array(
                    'id' => $item['property']->getId(),
                    'code' => $item['property']->getCode(),
                    'name' => $item['property']->getName()
                ),
                'component' => array(
                    'id' => $item['component']->getId(),
                    'name' => $item['component']->getName(),
                    'label' => $item['component']->getLabel(),
                    'description' => $item['component']->getDescription(),
                    'status' => $item['component']->getStatus()
                ),
                'submenu' => $resultMenu
            );
        }

      return $resultArray;
           }

}
