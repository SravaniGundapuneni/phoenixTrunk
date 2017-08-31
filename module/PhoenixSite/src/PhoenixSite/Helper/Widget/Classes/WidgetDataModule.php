<?php
namespace PhoenixSite\Helper\Widget\Classes;
use DynamicListModule\Model\Module;

class WidgetDataModule
{
	protected $dlmService;
	protected $dmService;
	protected $lmcService;
	protected $mmiService;
	protected $piService;
	protected $moduleName;

	private static $statuses = array(
		'1' => 'Saved', // Published
		'2' => 'New',
		'3' => 'Draft',
		'5' => 'Archived',
		'9' => 'Trashed'
	);

	public function __construct($options)
	{
		$this->dlmService = $options['dynamicListModule']; // moduleService
		$this->dmService  = $options['dynamicManager'];    // moduleManager
		$this->mmiService = $options['mediaManagerImage'];
		$this->lmcService = $options['listModuleCategories'];
		$this->piService  = $options['propertyInformation'];
	}			

	public function getModule($moduleName)
	{
		$this->moduleName = $moduleName;
		$module = $this->dlmService->getItemBy(array('name' => $moduleName));

		$this->dmService->setModuleName($moduleName);
		$this->dmService->setModule($module);

		return $this;
	}

	public function getInformation($options = array())
	{
		$module = $this->dlmService->getItemBy(array('name' => $this->moduleName));
		return ($module) ? $this->getModuleInformation($module, $options) : false;
	}

	public function getItemsBy($key, $value = null)
	{
		$itemsArray = array();
		$items = $this->dmService->getItems();

		foreach ($items as $valItem) {
			if ($valItem->getStatus() == \ListModule\Model\ListItem::ITEM_STATUS_PUBLISHED) {

				$itemArray = $this->getItemInformation($valItem);

				if ($this->isValidItemArray($itemArray, $key, $value)) {
					$itemsArray[] = $itemArray;
				}
			}
		}

		return $itemsArray;
	}
	
	private function getCategoriesArray($itemsArray)
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

	// this may need an extra 'sort' flag, that if equal to 'category', calls getItemsByCategory instead of getItems
	private function getModuleInformation($module, $options)
	{
		$result = $this->getResultTemplate($module->getId());

		if ($module instanceof Module) {

			$result['name']        = $module->getName();
			$result['description'] = $module->getDescription();
			$result['fields']      = $this->getFields($module);

			if (isset($options['category'])) {
				$result['items'] = $this->getItemsByCategory($module);
			} else {
				$result['items'] = $this->getItems($module);
			}
		}

		return $result;
	}
	
	private function getFields($module)
	{
		$fieldsArray = array();
		$fields = $module->getComponentFields();

		foreach ($fields as $valField) {
			$fieldsArray[] = array(
				'name'   => $valField->getName(),
				'label'  => $valField->getDisplayName(),
				'type'   => $valField->getType(),
				'status' => $this->getStatusLabel($valField->getStatus())
			);
		}

		return $fieldsArray;
	}

	// called by getItems and getItemsBy
	private function getItemInformation($item)
	{
		$result = $this->getResultTemplate($item);

		$result = array_merge($result, $item->getArrayCopy());

		if (isset($result['property'])) {
			$result['property'] = $this->getView()->getPropertyInformation($result['property']);
		}
        
        if(isset($result['categoryId'])) {
            $result['category'] = $this->lmcService->getItem($result['categoryId'])->getName();
        }
		
		$result['attachments'] = $this->getMediaAttachments($item);

		foreach ($result['attachments'] as $key => $attachment)
		{
			$mmImage = $this->mmiService->getItemBy(array('fileId' => $attachment['fileId']));
			$result['attachments'][$key]['altText']= '';
			if ($mmImage) {
				$result['attachments'][$key]['altText'] = $mmImage->getAltText();
			}
		}
		
		return $result;
	}

	// this is the get items of ModuleInformation.php
	private function getItems($module)
	{
		$itemsArray = array();
		$items = $this->dmService->getItems(true);

		foreach ($items as $valItem) {
			$itemModel = $this->dmService->createModel($valItem);

			//$itemArray = $this->getItemInformation($itemModel);
			$itemArray = $this->getResultTemplate($itemModel->getId());

			if ($itemModel->getCategoryId()) {
				$itemArray['category'] = $this->lmcService->getItem($itemModel->getCategoryId())->getName();
			}
			if ($itemModel->getStatus() == 1) {
				$itemsArray[] = $itemArray;
			}	
		}
		
		return $itemsArray;
	}

	private function getMediaAttachments($item)
	{
		$result = array();

		$attachments = $item->getMediaAttachments();
		
		foreach ($attachments as $key => $attachment)
		{
			$result[] = array(
				'large'  => $attachment->getFilePath(),
				'thumb'  => $attachment->getThumbnail(),
				'title'  => $attachment->getTitle(),
				'fileId' => $attachment->getFile(),
			);
		}

		return $result;
	}
	
	private function getItemsByCategory($module)
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

	// TODO: review how this is called, since it is duplicated everywhere in the codebase
	private function getResultTemplate($itemId)
	{
		return array(
			'id' => $itemId,
			'code' => null,           // may not be needed, added to be safe
			'name' => null,
			'information' => array(), // may not be needed, added to be safe
		);
	}

	private function getStatusLabel($statusNumber)
	{
		$statusLabel = 'N/A';

		if (isset(self::$statuses[$statusNumber])) {
			$statusLabel = self::$statuses[$statusNumber];
		}

		return $statusLabel;
	}

	private function isValidItemArray($itemArray, $key, $value)
	{
		return (($itemArray[$key] == $value) ||
			($key == 'property' &&
				($itemArray['property']['id'] == $value ||
				 $itemArray['property']['id'] == 'currentId' ||
				 $itemArray['property']['id'] == 'all'
				)
			)
		);
	}

}
