<?php

/**
 * The file for the DynamicListModule ModuleInformation Helper
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

use ListModule\Helper\ItemInformation as ListItemInformation;
use DynamicListModule\Model\Module;

/**
 * The file for the DynamicListModule ModuleInformation Helper
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
class ModuleInformation extends ListItemInformation
{

    /**
     * __invoke
     *
     * Return the item's front end friendly array
     * 
     * @param  mixed $item
     * 
     * @return array
     */
    public function __invoke($item, $sort = null)
    {
        $result = parent::__invoke($item);

        if ($item instanceof Module) {
            $result['name'] = $item->getName();
            $result['description'] = $item->getDescription();
            $result['fields'] = $this->getFields($item);
            if ($sort == 'category') {
                $result['items'] = $this->getItemsByCategory($item);
            } else {
                $result['items'] = $this->getItems($item);
            }
        }
        return $result;
    }

    /**
     *  getResultTemplate function
     * 
     * @access protected
     * @param mixed $itemId
     * @return array $itemId
     *
     */
    protected function getResultTemplate($itemId)
    {
        return array(
            'id' => $itemId,
            'name' => null,
        );
    }

    /**
     * getFields
     *
     * Return an array of field data
     * 
     * @param  $item [description]
     * @return [type]       [description]
     */
    protected function getFields($item)
    {
        $fields = $item->getComponentFields();

        $fieldsArray = array();

        foreach ($fields as $valField) {
            $fieldsArray[] = array(
                'name' => $valField->getName(),
                'label' => $valField->getDisplayName(),
                'type' => $valField->getType(),
                'status' => $this->getView()->displayStatus($valField->getStatus())
            );
        }

        return $fieldsArray;
    }

    protected function getItems($module)
    {
        $items = $this->itemService->getItems(true);
        $itemsArray = array();

        foreach ($items as $valItem) {
            //No need to replicate the functionality the model already does.
            $itemModel = $this->itemService->createModel($valItem);

            // notes: the itemInformation view helper is just returning an id
            $itemArray = $this->getView()->itemInformation($itemModel);
            if ($itemModel->getCategoryId()) {
                $itemArray['category'] = $this->categoryService->getItem($itemModel->getCategoryId())->getName();
            }
            if ($itemModel->getStatus() == 1) {
                $itemsArray[] = $itemArray;
            }
        }
        
        return $itemsArray;
    }

    protected function getItemsByCategory($module)
    {
        $itemsArray = $this->getItems($module);

        $categoriesArray = $this->getCategoriesArray($itemsArray);

        foreach ($categoriesArray as $index => $value) {
            foreach ($itemsArray as $item) {
                if ($item['category'] == $index) {
                    $categoriesArray[$index][] = $item;
                }
            }
        }
        return $categoriesArray;
    }

    protected function getCategoriesArray($itemsArray)
    {
        $categoriesArray = array();
        foreach ($itemsArray as $item) {
            if (!in_array($item['category'], $categoriesArray)) {
                $categoriesArray[$item['category']] = array();
            }
        }
        
        ksort($categoriesArray);
        return $categoriesArray;
    }

}
