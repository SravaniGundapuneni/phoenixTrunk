<?php
namespace Toolbox\Legacy;

class ModuleAdapter
{
    public function getDbPatches($moduleName)
    {
        $modulePatches = $this->createModuleObject($moduleName);
        $modulePatches->applyTablePatches(); 

        return true;       
    }


    public function getModuleItems($params)
    {
        extract($params);

        if (!isset($moduleName)){
            return false;
        }

        if (!isset($dataSection)){
            $dataSection = false;
        }

        if (!isset($limit)) {
            $limit = false;
        }

        if (!isset($limitFields)){
            $limitFields = false;
        }

        if (!isset($exportAttachments)){
            $exportAttachments = false;
        }

        $categoryOrList = false;

        if (isset($categories)) {
            $categoryOrList = $categories;
        }

        if (isset($category)) {
            $categoryOrList = $category;
        }

        $moduleObj = $this->createModuleObject($moduleName, $dataSection);
        $moduleObj->exportAttachmentsInArray = $exportAttachments;

        // Injecting additional module object properties (ex. exportRatesInArray for ihotelierRates)
        if (isset($moduleObjectProperties)) {
            foreach ($moduleObjectProperties as $key => $value) {
                try {
                    $moduleObj->$key = $value;
                } catch (Exception $e) {
                    //Do nothing
                }
            }
        }

        if (!method_exists($moduleObj, 'loadCurrentItems')) {
            return array();
        }
        if (isset($itemKey)) {
            $moduleObj->itemList->key = $itemKey;
        }

        if (isset($itemId)) {
            $moduleObj->itemList->itemId = $itemId;
        }

        if (isset($selectWhere))    {
            $moduleObj->itemList->selectWhere($selectWhere);
            $moduleObj->itemList->load(__FILE__, __LINE__);
        } else {
            $moduleObj->loadCurrentItems($categoryOrList, $limit);
        }

        return $moduleObj->itemList->toArray($limitFields);
    }

    public function createModuleObject($module, $dataSectionOrCXML = false)
    {
        if(is_object($dataSectionOrCXML)) {
            // You can pass alternative CXML (simple_xml object), created by getLocalCXML()
            $cxml = $dataSectionOrCXML;
            $dataSection = (string) $cxml->dataSection;
        } else {
            $cxml = false;
            $dataSection = $dataSectionOrCXML;
        }

        if(file_exists(CONDOR_MODULES_DIR .$module . '/module.' . $module . '.php')) {
            require_once(CONDOR_MODULES_DIR . $module . '/module.' . $module . '.php');
            $moduleClassName = '\Module_' . $module;
            $module = new $moduleClassName($dataSection, $cxml);
        } else {
            $module = new \Module($module, $dataSection, $cxml);
        }

        return $module;
    }

    public function getModuleConfig($module)
    {
        return \Module::getModuleConf($module);
    }
}