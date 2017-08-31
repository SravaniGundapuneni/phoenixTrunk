<?php
namespace IHotelier\Classes\XMLGenerator;

class PropertyAvailability extends XMLGenerator
{
	public function __construct($options)
	{
		parent::__construct($options);
		$this->responseType = 'PropertyList';
	}

	protected function buildHotelSearchCriteria($xml)
	{
		$xml->startElement('HotelSearchCriteria');
//		$xml->writeAttribute('AvailableOnlyIndicator', 'true');
			$xml->startElement('Criterion');
//			$xml->writeAttribute('ExactMatch', '0');

				$this->getStayDateRange($xml);
				$this->getRatePlanCandidates($xml);
				$this->getRooms($xml);

			$xml->endElement();
		$xml->endElement();
	}
}