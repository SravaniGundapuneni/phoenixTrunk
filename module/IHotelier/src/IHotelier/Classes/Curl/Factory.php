<?php
namespace IHotelier\Classes\Curl;

class Factory
{
	public static function build($options)
	{
		switch ($options['action']) {
			case 'AALS':
				$iHotelierCurl = new AreaAvailability($options);
				break;
			case 'BOOKING':
				$iHotelierCurl = new Booking($options);
				break;
			case 'FULL':
				$iHotelierCurl = new FullData($options);
				break;
			case 'RPIN':
				$iHotelierCurl = new ProductInfo($options);
				break;
			case 'PALS':
				$iHotelierCurl = new PropertyAvailability($options);
				break;
			case 'DailyRate':
				$iHotelierCurl = new DailyRate($options);
				break;
			case 'LeadRate':
				$iHotelierCurl = new LeadRate($options);
				break;
			default:
				throw new \Exception('Undefined API action');
				break;	
		}

		return $iHotelierCurl;
	}	
}