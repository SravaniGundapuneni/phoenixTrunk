<?php
namespace IHotelier\Classes\XMLGenerator;

class FullData extends XMLGenerator
{
	public function __construct($options)
	{
		parent::__construct($options);
		$this->responseType = 'FullList';
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