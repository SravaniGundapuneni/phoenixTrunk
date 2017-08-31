<?php
namespace IHotelier\Service;
use Phoenix\Service\ServiceAbstract;
use IHotelier\Classes\IHotelierServiceSocket;

class DailyRate extends IHotelierServiceSocket
{	
	public function __construct()
	{}

	public function getRateInfo()
	{
		$dataSource = $this->getServiceManager()->get('phoenix-ihotelier-settings')->getSettingValue('dataSource');
		
		if ($dataSource === 'dlm') {
			$rateInfo = $this->getRateInfoFromDLM();
		} else {
			$rateInfo = $this->getRateInfoFromIHotelier();
		}

		return $rateInfo;
	}

	private function getApiOptions()
	{
		$ihsService    = $this->getServiceManager()->get('phoenix-ihotelier-settings');
		$lookaheadDays = $ihsService->getSettingValue('lookaheadDays');
		$startDate     = $this->getStartDate();

		return array(
			'action'                 => 'DailyRate',
			'AvailableOnlyIndicator' => 'true',
			'AvailRatesOnly'         => 'true',
			'CompanyNameCode'        => $ihsService->getSettingValue('companyNameCode'),
			'endDate'                => $this->getEndDate($startDate, $lookaheadDays),
			'ExactMatch'             => '0',            
			'guests'                 => array(
				'adults'   => '1',
				'children' => '0',
				'infants'  => '0'
			),
			'header'                 => $this->getHeaderOptions(),
			'hotelCodes'             => array($ihsService->getSettingValue('hotelId')),
			'numberOfRooms'          => '1',
			'RequestorIDID'          => $ihsService->getSettingValue('requestorIDID'),
			'RequestorIDType'        => $ihsService->getSettingValue('requestorIDType'),
			'BookingChannelType'     => $ihsService->getSettingValue('bookingChannelType'),
			'promotions' => array(
//                'RatePlanID'         => '1',      // unknown if this is used or working
//                'RatePlanType'       => 'foo',    // this is the promotion type
//                'DiscountAccessCode' => '12345',  // unknown if this is used or working
//                'RatePlanCode'       => '680211', // this is the promotion code, cannot be non-empty for RPIN

			),
			'startDate' => $startDate,
		);    
	}

	private function getDLMRateOptions()
	{
		$ihsService    = $this->getServiceManager()->get('phoenix-ihotelier-settings');
		$lookaheadDays = $ihsService->getSettingValue('lookaheadDays');
		$startDate     = $this->getStartDate();

		$options = array();
		$options['startDate'] = $startDate;
		$options['endDate']   = $this->getEndDate($startDate, $lookaheadDays);

		return $options;
	}

	private function getEndDate($startDate, $lookaheadDays)
	{
		$lookaheadDays = ($lookaheadDays) ? $lookaheadDays : '1';
		$endDate       = new \DateTime($startDate);
		$endDate->add(new \DateInterval('P' . $lookaheadDays . 'D'));
		return $endDate->format('Y-m-d');
	}

	private function getHeaderOptions()
	{
		$ihsService = $this->getServiceManager()->get('phoenix-ihotelier-settings');
		return array(
			'messageID' => $ihsService->getSettingValue('messageID'),
			'username'  => $ihsService->getSettingValue('headerUsername'),
			'password'  => $ihsService->getSettingValue('headerPassword'),
		);
	}
	
	private function getRateInfoFromDLM()
	{
		$dlmOptions  = $this->getDLMRateOptions();
		$dailyRate   = $this->getServiceManager()->get('phoenix-rates')->getDailyRate($dlmOptions);

		return array(
			'amountAfterTax' => $dailyRate['price'],
			'bookingLink'    => $dailyRate['bookingLink'],
			'currency'       => '$',
		);
	}

	private function getRateInfoFromIHotelier()
	{
		$ihService = $this->getServiceManager()->get('phoenix-ihotelier');
		$rateInfo  = $ihService->callApi($this->getApiOptions());
		return $this->validateRateInfo($rateInfo);
	}

	private function getStartDate()
	{
		$tomorrow = new \DateTime('tomorrow');
		return $tomorrow->format('Y-m-d');
	}
	
	private function validateRateInfo(array $rateInfo)
	{
		$ihsService = $this->getServiceManager()->get('phoenix-ihotelier-settings');

		if (!is_numeric($rateInfo['amountAfterTax'])) {
			$rateInfo['amountAfterTax'] = $ihsService->getSettingValue('fallbackRate');
		}

		return $rateInfo;
	}
}
