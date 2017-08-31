<?php
namespace IHotelier\Classes\Curl;

class LeadRate extends IHotelierCurl
{
	public function __construct($options)
	{
		$this->to = array(
			'PROD' => 'http://ota2.ihotelier.com/OTA_Seamless/services/LeadRateService',
			'DEV'  => 'http://ota2-t5.ihotelier.com/OTA_Seamless/services/LeadRateService'
		);
		parent::__construct($options);
	}

	protected function parseResponse()
	{
		$xmlReader = new \XMLReader;
		$xmlReader->xml(current($this->xmlResponse));

		while ($xmlReader->read()) {
			$this->rate->setAmountAfterTax($xmlReader, $response);
			$this->rate->setCurrency($xmlReader, $response);
		}	

		return $this->rate->getResponse();
	}
}
