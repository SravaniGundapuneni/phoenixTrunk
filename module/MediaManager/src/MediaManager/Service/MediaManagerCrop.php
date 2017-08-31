<?php
namespace MediaManager\Service;
use Phoenix\Service\ServiceAbstract;

class MediaManagerCrop extends \ListModule\Service\Lists
{
	public function getCropPresets()
	{
		$counter = 0;
		$options = '';
		$presets = $this->getServiceManager()->get('MergedConfig')->get(array('mediamanager-crop-sizes', 'presets'));

		foreach ($presets as $name => $dimensions) {
			$options .= '<option data-width="'
					 . $dimensions['width']
					 . '" data-height="'
					 . $dimensions['height']
					 . '" value="'
					 . ++$counter
					 . '">' 
					 . $name
					 . '</option>';
		}	

		return $options;
	}

	public function getCropRatios()
	{
		$options = '';
		$ratios  = $this->getServiceManager()->get('MergedConfig')->get(array('mediamanager-crop-sizes', 'ratios'));

		foreach ($ratios as $name => $value) {
			$options .= '<option value="'
					 . $value
					 . '">'
					 . $name
					 . '</option>';
		}

		return $options;
	}
}
