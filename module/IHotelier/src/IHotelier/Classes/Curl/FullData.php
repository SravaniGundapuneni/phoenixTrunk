<?php
namespace IHotelier\Classes\Curl;

class FullData extends IHotelierCurl
{
	public function __construct($options)
	{
		$this->to = array(
			'PROD' => 'http://ota2.ihotelier.com/OTA_Seamless/services/FullDataService',
			'DEV'  => 'http://ota2-t5.ihotelier.com/OTA_Seamless/services/FullDataService'
		);
		parent::__construct($options);
	}

	protected function parseResponse()
	{
		$xmlReader = new \XMLReader;
		$xmlReader->xml(current($this->xmlResponse));
		$response = array();

		while ($xmlReader->read()) {
			$this->setHotelName($xmlReader, $response);
			$this->setRoomRate($xmlReader, $response);
		}	

		return $response;
	}

	private function setHotelName($xmlReader, &$response)
	{
		if ($xmlReader->nodeType == \XMLREADER::ELEMENT && $xmlReader->localName == 'BasicPropertyInfo') {
			$response['HotelName'] = $xmlReader->getAttribute('HotelName');
		}
	}

	private function setRoomRate($xmlReader, &$response)
	{
		if ($xmlReader->nodeType == \XMLREADER::ELEMENT && $xmlReader->localName == 'RoomRate' && $xmlReader->getAttribute('AvailabilityStatus') == 'AvailableForSale') {

			$roomRate = new \SimpleXMLElement($xmlReader->readOuterXML());

			$formattedRoomRate = array();
			$formattedRoomRate['RoomRatesRatePlanId'] = $xmlReader->getAttribute('RatePlanCode');
			$formattedRoomRate['RoomRatesRoomId']     = $xmlReader->getAttribute('RoomTypeCode');
			$formattedRoomRate['currency']            = $this->getAttribute($roomRate->Total, 'CurrencyCode');
			$formattedRoomRate['RoomRatesTotal']      = $this->getAttributeFromXPath($roomRate->xpath('Rates/Rate[@RateMode="Minimum"]/../../Total|Total'), 'AmountAfterTax'); // getting the lowest price

			$response['RoomRates'][] = $formattedRoomRate;
		}
	}
	/*
		//rateplanid
		self::RATEPLAN        => '//RoomStays/RoomStay/RatePlans/RatePlan[@AvailabilityStatus="AvailableForSale"]/@RatePlanCode',

		'RatePlanName'        => '//RoomStays/RoomStay/RatePlans/RatePlan[@AvailabilityStatus="AvailableForSale"]/@RatePlanName',
		'RatePlanDesc'        => '//RoomStays/RoomStay/RatePlans/RatePlan[@AvailabilityStatus="AvailableForSale"]/RatePlanDescription/Text/@Text',

		//roomtypeID
		self::ROOMTYPE        => '//RoomStays/RoomStay/RoomTypes/RoomType/@RoomTypeCode',

		'RoomName'            => '//RoomStays/RoomStay/RoomTypes/RoomType/@RoomTypeName',
		'RoomDesc'            => '//RoomStays/RoomStay/RoomTypes/RoomType/RoomDescription/Text/@Text',
		'roomRatesPrices'    => array('//RoomStays/RoomStay/RoomRates/RoomRate/Availability[@AvailabilityStatus="AvailableForSale"]/..', array(
			'amount'    => 'Rates/Rate/Fees/Fee/@Amount',
			'discount'  => 'Rates/Rate/Fees/Fee/@Discount',
			'date'      => 'Rates/Rate/Fees/Fee/@EffectiveDate'
			)
		)	
	*/
}
