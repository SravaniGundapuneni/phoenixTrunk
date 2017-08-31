<?php
// When we upgrade to php >= 5.4.0, we should refactor this to a Trait at the first opporunity.
// Currently, it lives in IHotelierCurl, but is only used by DailyRate and LeadRate
// See http://php.net/manual/en/language.oop5.traits.php
namespace IHotelier\Classes\Traits;

class Rate
{
	const ISTAY_URL = 'https://booking.ihotelier.com/istay/istay.jsp';	

	private $response = array(
		'amountAfterTax' => '0',
		'bookingLink' => self::ISTAY_URL,
		'currency' => '$',
	);

	public function getResponse()
	{
		return $this->response;
	}

	public function setAmountAfterTax($xmlReader, &$response)
	{
		if ($xmlReader->nodeType == \XMLREADER::ELEMENT && $xmlReader->localName == 'Base') {
			$amountAfterTax = $xmlReader->getAttribute('AmountAfterTax');
			if ($this->shouldUpdateAmountAfterTax($amountAfterTax)) {
				$this->response['amountAfterTax'] = $amountAfterTax;
			}
		}
	}

	public function setCurrency($xmlReader, &$response)
	{
		if ($xmlReader->nodeType == \XMLREADER::ELEMENT && $xmlReader->localName == 'Tax') {
			$this->setCurrencySymbol($xmlReader->getAttribute('CurrencyCode'));
		}
	}

	private function setCurrencySymbol($currencyCode)
	{
		switch ($currencyCode) {
			case 'EUR':
				$currencySymbol = '&euro;';
				break;
			case 'GBP':
				$currencySymbol = '£';
				break;
			case 'AUD':
			case 'USD':
			default:
				$currencySymbol = '$';
				break;
		}

		$this->response['currency'] = $currencySymbol;
	}

	private function shouldUpdateAmountAfterTax($amountAfterTax)
	{
		$shouldUpdate = false;
		if (is_numeric($amountAfterTax) && ($amountAfterTax > $this->response['amountAfterTax'])) {
			$shouldUpdate = true;
		}
		return $shouldUpdate;
	}
}