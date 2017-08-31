<?php
/**
 * The file for the DynamicListModule Class for the Dynamic List Module
 *
 * @category    Toolbox
 * @package     DynamicListModule
 * @subpackage  Service
 * @copyright   Copyright (c) 2014 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 1.14
 * @since       File available since release 1.14
 * @author      A. Tate <atate@travelclick.com>
 * @filesource
 */

namespace DynamicListModule\Service;

use DynamicListModule\Model\Module;
use ListModule\Service\Lists;

/**
 * The DynamicListModule Class for the Dynamic List Module
 *
 * @category    Toolbox
 * @package     DynamicListModule
 * @subpackage  Service
 * @copyright   Copyright (c) 2014 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 1.14
 * @since       File available since release 1.14
 * @author      A. Tate <atate@travelclick.com>
 */
class Install extends Lists
{
    /**
     * The name of the entity used by the associated model
     * @var string
     */
    protected $entityName = 'Toolbox\Entity\Components';

    /**
     * The name of the model class associated with this service
     * @var string
     */
    protected $modelClass = "\DynamicListModule\Model\Module";

    public function getItems($active = false, $showAll = false)
    {
        $findByArray = array('dynamic' => 1);

        if ($active) {
            $findByArray['status'] = 1;
        }

        $localItems = $this->getItemsBy($findByArray, $this->orderBy);

        $adminItems = $this->getAdminItems();

        if (empty($localItems) && empty($adminItems)) {
            return array();
        }

        $items = array();
        $accountedItems = array();

        foreach ($localItems as $valLocalItem) {
            $item = array();

            $item['id'] = $valLocalItem->getId('id');
            $item['name'] = $valLocalItem->getName();

            if (!empty($adminItems[$valLocalItem->getAdminRepoId()])) {
                $item['status'] = $this->checkForModifications($valLocalItem, $adminItems[$valLocalItem->getAdminRepoId()]);
                $item['adminId'] = $valLocalItem->getAdminRepoId();
                $accountedItems[] = $valLocalItem->getAdminRepoId();
            } else {
                $item['status'] = 'Local Only';
            }

            $items[$item['name']] = (object) $item;
        }

        foreach ($adminItems as $keyAdminItem => $valAdminItem) {
            $item = array();

            if (in_array($keyAdminItem, $accountedItems)) {
                continue;
            }

            if ($valAdminItem->status != 1) {
                continue;
            }

            $name = $valAdminItem->getName();

            if (isset($items[$name])) {
                $name .= ' (Admin)';
            }

            $item['name'] = $name;
            $item['id'] = $valAdminItem->getId();
            $item['adminId'] = $valAdminItem->getId();
            $item['status'] = 'Not Installed';

            $items[$name] = (object) $item;
        }

        return $items;
    } 

    protected function checkForModifications($localItem, $adminItem)
    {
        $properties = array();
        $fieldProperties = array();

        if ($adminItem->getStatus() == 2) {
            return 'Pending in Repo';
        }

        foreach ($properties as $valProperty) {
            $getterName = 'get' . ucfirst($valProperty);
            if ($localItem->{$getterName}() !== $adminItem->{$getterName}()) {
                return 'Modified Locally';
            }
        }

        return 'Installed';
    }

    public function getAdminItems()
    {
        $result = $this->getAdminEntityManager()->getRepository('Toolbox\Entity\Admin\Components')->findBy(array('dynamic' => 1));

        $adminModules = array();

        if (empty($result)) {
            return;
        }

        foreach ($result as $keyModule => $valModule) {
            $adminModules[$valModule->getId()] = $valModule;
        }

        return $adminModules;
    }

    public function getAdminItem($itemId)
    {
        return $this->getAdminEntityManager()->getRepository('Toolbox\Entity\Admin\Components')->findOneBy(array('id' => $itemId));
    }
}