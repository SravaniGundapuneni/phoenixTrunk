<?php
namespace IHotelier\Classes\Curl;

class ProductInfo extends IHotelierCurl
{
	public function __construct($options)
	{
		$this->to = array(
			'PROD' => 'http://ota2.ihotelier.com/OTA_Seamless/services/ProductInfoService',
			'DEV'  => 'http://ota2-t5.ihotelier.com/OTA_Seamless/services/ProductInfoService'
		);
		parent::__construct($options);
	}

	protected function parseResponse()
	{
		/*
		$xmlReader = new \XMLReader;
		$xmlReader->xml(current($this->xmlResponse));
		*/
		$response = array();

		/*
		while ($xmlReader->read()) {
			$this->setHotelName($xmlReader, $response);
			$this->setRoomRate($xmlReader, $response);
		}	
		*/

		return $response;
	}
}
