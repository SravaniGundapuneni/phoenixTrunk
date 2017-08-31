<?php
namespace IHotelier\Classes\XMLGenerator;

class Factory
{
	public static function build($options)
	{
		switch ($options['action']) {
			case 'AALS':
				$generator = new AreaAvailability($options);
				break;
			case 'BOOKING':
				$generator = new Booking($options);
				break;
			case 'FULL':
				$generator = new FullData($options);
				break;
			case 'RPIN':
				$generator = new ProductInfo($options);
				break;
			case 'PALS':
				$generator = new PropertyAvailability($options);
				break;
			case 'DailyRate':
				$generator = new DailyRate($options);
				break;
			case 'LeadRate':
				$generator = new LeadRate($options);
				break;
			default:
				throw new \Exception('Undefined XML Generator Type');
				break;	
		}

		return $generator;
	}	
}