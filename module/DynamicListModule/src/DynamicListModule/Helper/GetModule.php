<?php

/**
 * The file for the DynamicListModule GetModule Helper
 *
 * @category    Toolbox
 * @package     ListModule
 * @subpackage  Helper
 * @copyright   Copyright (c) 2014 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 1.14
 * @since       File available since release 1.14
 * @author      A. Tate <atate@travelclick.com>
 * @filesource
 */

namespace DynamicListModule\Helper;

use Zend\View\Helper\AbstractHelper;

/**
 * The file for the DynamicListModule GetModule Helper
 *
 * @category    Toolbox
 * @package     ListModule
 * @subpackage  Helper
 * @copyright   Copyright (c) 2014 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 1.14
 * @since       File available since release 1.14
 * @author      A. Tate <atate@travelclick.com>
 */
class GetModule extends AbstractHelper
{

    /**
     * $moduleService
     * 
     * @var DynamicListModule\Service\DynamicListModule
     */
    protected $moduleService;

    /**
     * $moduleManager
     * 
     * @var DynamicListModule\Service\DynamicManager
     */
    protected $moduleManager;

    /**
     * $moduleName
     * @var string
     */
    protected $moduleName;

    /**
     * __construct
     * 
     * @param DynamicListModule\Service\DynamicListModule $moduleService
     */
    public function __construct($moduleService, $moduleManager)
    {
        $this->moduleService = $moduleService;
        $this->moduleManager = $moduleManager;
    }

    /**
     * __invoke
     *
     * Return the item's front end friendly array
     * 
     * @param  mixed $item
     * 
     * @return array|boolean
     */
    public function __invoke($name)
    {
        $this->moduleName = $name;
        $this->moduleManager->setModuleName($this->moduleName);

        $module = $this->moduleService->getItemBy(array('name' => $this->moduleName));
        $this->moduleManager->setModule($module);

        return $this;
    }

    public function getInformation()
    {
        $module = $this->moduleService->getItemBy(array('name' => $this->moduleName));

        return ($module) ? $this->getView()->moduleInformation($module) : false;
    }

    public function getInformationByCategory()
    {
        $module = $this->moduleService->getItemBy(array('name' => $this->moduleName));

        return ($module) ? $this->getView()->moduleInformation($module, 'category') : false;
    }

    public function getItems($property = false)
    {
        if (is_array($property)) {
            $value = $property['property'];
        } else {
            $value = $property;
        }

        return $this->getItemsBy('property', $value);
    }

    public function getItemsBy($key,$value)
    {
        //@todo actually use Phoenix to filter, instead of doing this crap.
        $items = $this->moduleManager->getItems();

        $itemsArray = array();

        foreach ($items as $valItem) {
            if ($valItem->getStatus() == \ListModule\Model\ListItem::ITEM_STATUS_PUBLISHED)
            {
                $itemArray = $this->getView()->itemInformation($valItem);

                if ($itemArray[$key] == $value || ($key == 'property' && ($itemArray['property']['id'] == $value || $itemArray['property']['id'] == 'currentId' || $itemArray['property']['id'] == 'all'))) {
                    $itemsArray[] = $itemArray;
                }
            }
        }

        return $itemsArray;
    }

    public function getItemsByCategory($categoryName, $property = false)
    {
        $category = $this->moduleService->getCategoryBy(array('name' => $categoryName, 'module' => $this->moduleName));
        if (!is_object($category)) {
            return array();
        }

        $parameters = array(
            'status' => 1,
            'categoryId' => $category->getId(),
            'property' => $property
        );

        return $this->getItemsBy($parameters);
    }

    /**
     * getItem
     * 
     * @param  int $itemId
     * 
     * @return mixed
     */
    public function getItem($itemId)
    {
        $item = $this->moduleManager->getItem($itemId);

        return ($item) ? $this->getView()->itemInformation($item) : false;
    }

    /**
     * getItemBy
     *
     * NOT YET USABLE
     * 
     * @param  array  $params
     * @return mixed
     */
    public function getItemBy($params = array())
    {
        $item = false;

        if (!empty($params)) 
        {
            $item = $this->moduleManager->getItemBy($params);
        }

        return ($item) ? $this->getView()->itemInformation($item) : false;
    }
    
    
    // sds old code starts 20 aug 2014, 4:52pm
    /*
    public function getItemsByPage($pageId) 
    {
        $options_total = array();
        $hotels = $this->getDefaultEntityManager()->getRepository('Pages\Entity\Pages')->findBy(array('status' => 1));

        foreach ($hotels as $keyHotel => $valHotel) 
        {                  
            $options_total[$valHotel->getId()] = $valHotel->getDataSection() . '/' . str_replace("default","",$valHotel->getPageKey());
        }
        $optionsUnique = array_unique(($options_total));
        
        natcasesort($optionsUnique);
        $arr1 = array('' => 'Not Assigned');
        $options = $arr1 + $optionsUnique;
        return $options;
    }
    */
    // sds old code ends 20 aug 2014, 4:52pm
    
    // sds new code starts 20 aug 2014, 4:53pm
    public function getItemsByPage($pageId) 
    {
        $pageDownloads = array();
        $result = $this->getDefaultEntityManager()->getRepository(‘DynamicListModule\Entity\DynamicListModulePages’)->findBy(array(‘pageId’ => $pageId()));

        //We need to set this in case order numbers haven't been set for the items, so the array will work correctly
        $replacementOrderNumber = 1000000;

        //Start this at 0
        $currentOrderNumber = 0;

        foreach ($result as $keyitem => $valitem) {                  
            //Get the items order number
            $itemOrderNumber = $valItem->getOrderNumber();

            //Either use the db order number or the current replace number
            $orderNumber = !empty($itemOrderNumber) ? $itemOrderNumber : $replacementOrderNumber;

            //This is to ensure we don't overwrite items with the same order number.  Not a likely occurrence, but just in case
            if (isset($addons[$orderNumber])) {
                $currentOrderNumber = $orderNumber + 1;
            } else {
                $currentOrderNumber = $orderNumber;
            }

          // return dload items.
           $pageDownloads[$currentOrderNumber] = $this->getItem($valItem->getItemId());
           $replacementOrderNumber++;
        }

        //Make sure the array is sorted smallest orderNumber to largest.
        ksort($pageDownloads);

        return $pageDownloads;
    }
    // sds new code ends 20 aug 2014, 4:53pm
}
