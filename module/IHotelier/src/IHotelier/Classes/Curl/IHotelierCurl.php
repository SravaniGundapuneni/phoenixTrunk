<?php
namespace IHotelier\Classes\Curl;
use IHotelier\Classes\Traits;

abstract class IHotelierCurl
{
	const ASYNC_REQUESTS = false;
	const PASSWORD       = 'C0nn3ct0taAp!';
	const TIMEOUT        = 10;
	const USERNAME       = 'ehotels';

	protected $curlOptUrl;
	protected $env;
	protected $hotelId;
	protected $languageId;
	protected $phpResponse;
	protected $rate;
	protected $to;
	protected $xmlResponse;

	private $action;
	private $soapXML;
	private $xmlGenerator;

	abstract protected function parseResponse();
	
	// needs a development environment and languageId
	public function __construct(array $options)
	{
		$this->setEnvironment($options);
		$this->setHotelId($options);
		$this->setLanguageId($options);

		$options['xmlGenerator']->setTo($this->to[$this->env]);	

		$this->action     = $options['action'];
		$this->soapXML    = $options['xmlGenerator']->buildRequestXML();
		$this->curlOptUrl = $this->to[$this->env];
		$this->rate       = new Traits\Rate();
	}	

	public function sendRequest()
	{
		$soap_do = curl_init();

		curl_setopt_array($soap_do, $this->getCurlHeaders());

		$xmlResponse       = curl_exec($soap_do);
		$this->xmlResponse = json_decode(json_encode((array) $xmlResponse), true);
		$err               = curl_error($soap_do);

		curl_close($soap_do);

		return $this->parseResponse(); 
	}

	protected function getAttribute($element, $attribute)
	{
		$attributesOfElement = $element->attributes();
		return current($attributesOfElement[$attribute]);
	}

	protected function getAttributeFromXPath($xpath, $attribute)
	{
		$xpath = current($xpath);
		return current($xpath[$attribute]);
	}

	private function getCurlHeaders()
	{
		return array(
			CURLOPT_CONNECTTIMEOUT => self::TIMEOUT,
			CURLOPT_ENCODING       => 'gzip,deflate',
			CURLOPT_HTTPAUTH       => CURLAUTH_BASIC,
			CURLOPT_HTTPHEADER     => $this->getHttpHeaders(),
			CURLOPT_POST           => true,
			CURLOPT_POSTFIELDS     => $this->soapXML,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_SSL_VERIFYHOST => false,
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_TIMEOUT        => self::TIMEOUT,
			CURLOPT_URL            => $this->curlOptUrl,
			CURLOPT_USERPWD        => self::USERNAME . ':' . self::PASSWORD,
		);
	}

	private function getHttpHeaders()
	{
		return array( 
			'Accept: text/xml', 
			'Cache-Control: no-cache', 
			'Content-Length: ' . strlen($this->soapXML), 
			'Content-Type: text/xml; charset="utf-8"', 
			'Pragma: no-cache', 
			'SOAPAction: "' . strtolower($this->action) . '"',
		); 
	}

	private function setEnvironment(array $options)
	{
		$this->env = $options['isDevelopmentEnv'] ? 'DEV' : 'PROD';
	}

	private function setHotelId(array $options)
	{
		$this->hotelId = current($options['hotelCodes']);
	}

	private function setLanguageId(array $options)
	{
		$this->languageId = $options['languageId'];
	}
}