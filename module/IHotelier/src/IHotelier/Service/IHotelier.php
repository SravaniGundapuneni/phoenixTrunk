<?php
namespace IHotelier\Service;

use Phoenix\Service\ServiceAbstract;
use IHotelier\Classes\XMLGenerator;
use IHotelier\Classes\Curl;

class IHotelier extends \ListModule\Service\Lists
{
	private $mergedConfig;

	public function setConfig($mergedConfig)
	{
		$this->mergedConfig = $mergedConfig;
	}

	public function callApi(array $options)
	{
		$options['isDevelopmentEnv'] = $this->mergedConfig->get(array('paths', 'development'));
		$options['languageId']       = $this->getServiceManager()->get('currentLanguage')->getId();
		$options['xmlGenerator']     = XMLGenerator\Factory::build($options);
		$iHotelierCurl               = Curl\Factory::build($options);
		$responseData                = $iHotelierCurl->sendRequest();

		return $responseData;
	}
}
