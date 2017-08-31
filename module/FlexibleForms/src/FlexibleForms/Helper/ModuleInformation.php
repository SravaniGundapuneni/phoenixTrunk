<?php
/**
 * The file for the FlexibleForms ModuleInformation Helper
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

namespace FlexibleForms\Helper;

use ListModule\Helper\ItemInformation as ListItemInformation;
use FlexibleForms\Model\Module;

/**
 * The file for the FlexibleForms ModuleInformation Helper
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
    public function __invoke($item)
    {
        $result = parent::__invoke($item);

        if ($item instanceof Module) {
            $result['name'] = $item->getName();
            $result['description'] = $item->getDescription();
            $result['fields'] = $this->getFields($item);
            $result['items'] = $this->getItems($item);
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
        $items = $module->getModuleItems();

        $itemsArray = array();

        foreach ($items as $valItem) {
            //No need to replicate the functionality the model already does.
            $itemModel = $this->itemService->createModel($valItem);

            $itemsArray[] = $this->getView()->itemInformation($itemModel);
        }

        return $itemsArray;
    }
}