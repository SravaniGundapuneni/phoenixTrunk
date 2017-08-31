<?php
namespace IHotelier\Classes\Curl;

class AreaAvailability extends IHotelierCurl
{
	public function __construct($options)
	{
		$this->to = array(
			'PROD' => 'http://ota2.ihotelier.com/OTA_Seamless/services/AreaAvailabilityService',
			'DEV'  => 'http://ota2-t5.ihotelier.com/OTA_Seamless/services/AreaAvailabilityService'
		);
		parent::__construct($options);
	}

	protected function parseResponse()
	{
		$xmlReader = new \XMLReader;
		$xmlReader->xml(current($this->xmlResponse));
		$response = array();

		while ($xmlReader->read()) {
			//$this->setHotelName($xmlReader, $response);
			//$this->setRoomRate($xmlReader, $response);
		}	

		return $response;
	}
}
