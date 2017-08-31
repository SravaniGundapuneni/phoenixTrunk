<?php
namespace IHotelier\Classes\Curl;

class DailyRate extends IHotelierCurl
{
	public function __construct($options)
	{
		$this->to = array(
			'PROD' => 'http://ota2.ihotelier.com/OTA_Seamless/services/DailyRateService',
			'DEV'  => 'http://ota2-t5.ihotelier.com/OTA_Seamless/services/DailyRateService'
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

		$response = $this->rate->getResponse();
		$response['basicBookingLink'] = $response['bookingLink'] . '?hotelid=' . $this->hotelId . '&amp;languageid=' . $this->languageId;
		
		return $response;
	}
}
