<?php
namespace IHotelier\Classes\XMLGenerator;

class Booking extends XMLGenerator
{
	public function __construct($options)
	{
		parent::__construct($options);
		$this->responseType = 'Booking';
	}

	protected function buildHotelSearchCriteria($xml)
	{
		$xml->startElement('HotelSearchCriteria');
		$xml->writeAttribute('AvailableOnlyIndicator', 'true');
			$xml->startElement('Criterion');
			$this->setAttribute($xml, 'ExactMatch');

			/*
				$this->getStayDateRange($xml);
				$this->getRatePlanCandidates($xml);
				$this->getRooms($xml);
			*/

			$xml->endElement();
		$xml->endElement();
	}
}