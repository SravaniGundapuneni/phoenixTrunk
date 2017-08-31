<?php
namespace IHotelier\Classes\XMLGenerator;

abstract class XMLGenerator
{
	protected $options = array();
	protected $responseType;
	protected $to;

	private static $xmlns = array(
		'soap' => 'http://schemas.xmlsoap.org/soap/envelope/',
//		'wsa'  => 'http://www.w3.org/2005/08/addressing',
//		'wsa2' => 'http://schemas.xmlsoap.org/ws/2004/08/addressing',
		'wsa'  => 'http://schemas.xmlsoap.org/ws/2004/08/addressing',
//		'wsse' => 'http://schemas.xmlsoap.org/ws/2002/07/secext',
		'wsse' => 'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd',
		'wsu'  => 'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd',
		'xsd'  => 'http://www.w3.org/2001/XMLSchema',
		'xsi'  => 'http://www.w3.org/2001/XMLSchema-instance',
		'ota'  => 'http://services.ota.travelclick.com'
	);

	private static $acceptedNamespaces = array('soap', 'wsa', 'wsse', 'wsu');
	private static $guestCode          = array('adults' => '10', 'children' => '8', 'infants' => '7');

	abstract protected function buildHotelSearchCriteria($xml);

	public function __construct($options)
	{
		// TODO: sanitize and validate options
		$this->options = $options;
	}

	public function buildRequestXML()
	{
		$searchOptions = array();

		$xml = new \XMLWriter();
		$xml->openMemory();
		$xml->startDocument('1.0', 'UTF-8');
		$xml->setIndent(4);
		$xml->startElement('soap:Envelope');

			$this->buildNamespaces($xml);	
			$this->buildHeader($xml, $this->getHeaderOptions($this->to));

			$xml->startElement('soap:Body');
				$xml->startElement('OTA_HotelAvailRQ');
				$xml->writeAttribute('Version', '2.0');
				$this->setAttribute($xml, 'AvailRatesOnly');

					$this->buildPOS($xml);

					$xml->startElement('AvailRequestSegments');
						$xml->startElement('AvailRequestSegment');
							$xml->writeAttribute('ResponseType', $this->responseType);

							$this->buildHotelSearchCriteria($xml);

						$xml->endElement();
					$xml->endElement();
				$xml->endElement();

				$xml->startElement('bnr:serviceRequest');
				$xml->writeAttribute('xmlns:bnr', 'http://webstage.ch.com/OTA/services/BNRequestService');
				$xml->endElement();	
			$xml->endElement();
		$xml->endElement();

		return $xml->outputMemory(true);
	}

	public function setTo($to)
	{
		$this->to = $to;
	}

	protected function getGuests($xml)
	{
		$xml->startElement('GuestCounts');
			foreach ($this->options['guests'] as $guestType => $guestQuantity) {
				$xml->startElement('GuestCount');
				$xml->writeAttribute('AgeQualifyingCode', self::$guestCode[$guestType]);
				$xml->writeAttribute('Count', $guestQuantity);
				$xml->endElement();
			}
		$xml->endElement();
	}

	protected function getHotels($xml)
	{
		$xml->startElement('HotelRefs');
		foreach ($this->options['hotelCodes'] as $hotelCode) {
			$this->getHotel($xml, $hotelCode);
		}
		$xml->endElement();
	}

	protected function getHotel($xml, $hotelCode)
	{
		$xml->startElement('HotelRef');
		$xml->writeAttribute('HotelCode', $hotelCode);
		$xml->endElement();
	}

	protected function getRatePlanCandidates($xml)
	{
		$xml->startElement('RatePlanCandidates');
			$xml->startElement('RatePlanCandidate');

				$this->applyPromotions($xml);
				$this->getHotels($xml);

			$xml->endElement();
		$xml->endElement();
	}

	protected function getRooms($xml)
	{
		$xml->startElement('RoomStayCandidates');
			$xml->startElement('RoomStayCandidate');
			$xml->writeAttribute('Quantity', $this->options['numberOfRooms']);

				$this->getGuests($xml);

			$xml->endElement();
		$xml->endElement();
	}

	protected function getStayDateRange($xml)
	{
		$xml->startElement('StayDateRange');
		$xml->writeAttribute('Start', $this->options['startDate']);
		$xml->writeAttribute('End', $this->options['endDate']);
		$xml->endElement();
	}

	protected function setAttribute($xml, $attribute)
	{
		if (isset($this->options[$attribute])) {
			$xml->writeAttribute($attribute, $this->options[$attribute]);
		}	
	}

	private function applyPromotions($xml)
	{
		if (isset($this->options['promotions']) && is_array($this->options['promotions'])) {
			foreach ($this->options['promotions'] as $attribute => $value) {
				$xml->writeAttribute($attribute, $value);
			}
		}	
	}

	private function buildHeader($xml, $options)
	{
		$xml->startElement('soap:Header');
			$xml->startElement('wsa:MessageID');
			$xml->text($options['messageID']);
			$xml->endElement();

			$xml->startElement('wsa:ReplyTo');
				$xml->startElement('wsa:Address');
				$xml->text('NOT NEEDED FOR SYNC REQUEST');
				$xml->endElement();	
			$xml->endElement();

			$xml->startElement('wsa:To');
			$xml->text($this->to);
			$xml->endElement();

			$xml->startElement('wsa:Action');
			$xml->text($this->options['action']);
			$xml->endElement();

			$xml->startElement('wsa:From');
				$xml->startElement('SalesChannelInfo');
				$xml->writeAttribute('ID', $options['salesChannelInfoID']);
				$xml->endElement();
			$xml->endElement();

			$xml->startElement('wsse:Security');
			$xml->writeAttribute('soap:mustUnderStand', '0');
				$xml->startElement('wsu:Timestamp');
					$xml->startElement('wsu:Created');
					$xml->text($options['created']);
					$xml->endElement();

					$xml->startElement('wsu:Expires');
					$xml->text($options['expires']);
					$xml->endElement();
				$xml->endElement();

				$xml->startElement('wsse:UsernameToken');
					$xml->startElement('wsse:Username');
					$xml->text($options['username']);
					$xml->endElement();

					$xml->startElement('wsse:Password');
					$xml->text($options['password']);
					$xml->endElement();
				$xml->endElement();
			$xml->endElement();
		$xml->endElement();
	}

	private function buildNamespaces($xml)
	{
		foreach	(self::$acceptedNamespaces as $prefix) {
			$xml->writeAttribute('xmlns:' . $prefix, self::$xmlns[$prefix]);
		}
	}

	private function buildPOS($xml)
	{
		$xml->startElement('POS');	
			$xml->startElement('Source');
				$xml->startElement('RequestorID');
				$xml->writeAttribute('ID', $this->options['RequestorIDID']);
				$xml->writeAttribute('Type', $this->options['RequestorIDType']);
				$xml->endElement();

				$xml->startElement('BookingChannel');
				$xml->writeAttribute('Type', $this->options['BookingChannelType']);
					$xml->startElement('CompanyName');
					$xml->writeAttribute('Code', $this->options['CompanyNameCode']);
					$xml->endElement();
				$xml->endElement();
			$xml->endElement();
		$xml->endElement();
	}

	private function getHeaderOptions()
	{
		return array(
			'messageID'          => $this->options['header']['messageID'],
			'salesChannelInfoID' => $this->options['CompanyNameCode'],
			'created'            => '2011-01-28T11:21:16+01:00', 
			'expires'            => '2011-01-28T11:21:16+01:00',
			'username'           => $this->options['header']['username'],
			'password'           => $this->options['header']['password'],
		);
	}
}