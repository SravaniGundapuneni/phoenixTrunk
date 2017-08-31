<?php
namespace IHotelier\Classes\XMLGenerator;

class ProductInfo extends XMLGenerator
{
	public function __construct($options)
	{
		parent::__construct($options);
		$this->responseType = 'RoomRateInfo';
	}

	protected function buildHotelSearchCriteria($xml)
	{
		$xml->startElement('HotelSearchCriteria');
//		$xml->writeAttribute('AvailableOnlyIndicator', 'true');
			$xml->startElement('Criterion');
//			$xml->writeAttribute('ExactMatch', '0');

				$this->getStayDateRange($xml);
				$this->getRatePlanCandidates($xml); // discount access code?
				$this->getRooms($xml);

			$xml->endElement();
		$xml->endElement();
	}
}