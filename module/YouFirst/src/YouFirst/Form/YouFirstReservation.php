<?php
/** 
 * YouFirstReservation Class
 * 
 * This will extend the Zend/Form and it will create a login form the (Model to be named later)
 * 
 * @category       Toolbox
 * @package        YouFirst
 * @subpackage     Form
 * @copyright      Copyright (c) 2013 TravelClick, Inc (http://travelclick.com)
 * @license        All Rights Reserved
 * @since          File available since release 13.5 
 * @author         Kevin Davis <kedavis@travelclick.com>
 * @filesource
*/

namespace YouFirst\Form;

/** 
 * YouFirstReservation Class
 * 
 * This will extend the Zend/Form and it will create a login form the (Model to be named later)
 * 
 * @category       Toolbox
 * @package        YouFirst
 * @subpackage     Form
 * @copyright      Copyright (c) 2013 TravelClick, Inc (http://travelclick.com)
 * @license        All Rights Reserved
 * @since          File available since release 13.5 
 * @author         Kevin Davis <kedavis@travelclick.com>
*/

 use Zend\InputFilter;
 use Zend\Form\Element;
 use Zend\Form\Form;
 
 
 class YouFirstReservation extends form
 {
 	public function __construct()
 	{
   parent::__construct('youFirst');
   $this->setAttribute('method','post');
   
   $this->add(array(
	 'name' => 'title',
	 'type' => 'Select',
	 'options' => array (
	   'label' => 'Title',
	   'label_attributes' => array(
	     'class' => 'blocklabel'
		 ),
		'value_options' => array (
		   '1' => 'Mr',
		   '2' => 'Mrs',
		   '3' => 'Ms',
		  )
		),
		'attributes' => array(
		  'class' => 'stdTextInputSmall',
		)
		));
		
		
	$this->add(array(
	 'name' => 'firstname',
	 'type' => 'text',
	 'options' => array(
	   'label' => 'First Name',
	   'label_attributes' => array (
	    'class' => 'blocklabel'
	   )
	  ),
	  'attributes' => array (
	   'class' => 'stdTextInput'
	  )
	 ));
	 
	 
	 $this->add(array(
	 'name' => 'middlename',
	 'type' => 'text',
	 'options' => array(
	   'label' => 'Middle Name',
	   'label_attributes' => array (
	    'class' => 'blocklabel'
	   )
	  ),
	  'attributes' => array (
	   'class' => 'stdTextInput'
	  )
	 ));
	 
	 $this->add(array(
	 'name' => 'lastname',
	 'type' => 'text',
	 'options' => array(
	   'label' => 'Last Name',
	   'label_attributes' => array (
	    'class' => 'blocklabel'
	   )
	  ),
	  'attributes' => array (
	   'class' => 'stdTextInput'
	  )
	 ));
	 
	 $this->add(array(
	 'name' => 'dob-month',
	 'type' => 'Select',
	 'options' => array (
	   'label' => 'Date of Birth Month',
	   'label_attributes' => array(
	     'class' => 'blocklabel'
		 ),
		'value_options' => array (
		   '1' => 'Jan',
		   '2' => 'Feb',
		   '3' => 'Mar',
		   '4' => 'Apr',
		   '5' => 'May',
		   '6' => 'Jun',
		   '7' => 'Jul',
		   '8' => 'Aug',
		   '9' => 'Sep',
		   '10' => 'Oct',
		   '11' => 'Nov',
		   '12' => 'Dec',
		  )
		),
		'attributes' => array(
		  'class' => 'stdTextInputSmall',
		)
		));
		
	$this->add(array(
	 'name' => 'dob-date',
	 'type' => 'Select',
	 'options' => array (
	   'label' => 'Date of Birth Date',
	   'label_attributes' => array(
	     'class' => 'blocklabel'
		 ),
		'value_options' => array (
		   '1' => '1',
		   '2' => '2',
		   '3' => '3',
		   '4' => '4',
		   '5' => '5',
		   '6' => '6',
		   '7' => '7',
		   '8' => '8',
		   '9' => '9',
		   '10' => '10',
		   '11' => '11',
		   '12' => '12',
		   '13' => '13',
		   '14' => '14',
		   '15' => '15',
		   '16' => '16',
		   '17' => '17',
		   '18' => '18',
		   '19' => '19',
		   '20' => '20',
		   '21' => '21',
		   '22' => '22',
		   '23' => '23',
		   '24' => '24',
		   '25' => '25',
		   '26' => '26',
		   '27' => '27',
		   '28' => '28',
		   '29' => '29',
		   '30' => '30',
		   '31' => '31',
		  )
		),
		'attributes' => array(
		  'class' => 'stdTextInputSmall',
		)
		));
	
	 $this->add(array(
	 'name' => 'dob-year',
	 'type' => 'Select',
	 'options' => array (
	   'label' => 'Date of Birth year',
	   'label_attributes' => array(
	     'class' => 'blocklabel'
		 ),
		'value_options' => array (
		   '1' => '1970',
		   '2' => '1971',
		   '3' => '1972',
		   '4' => '1973',
		   '5' => '1974',
		   '6' => '1975',
		   '7' => '1976',
		   '8' => '1977',
		   '9' => '1978',		   
		  )
		),
		'attributes' => array(
		  'class' => 'stdTextInputSmall',
		)
		));
		
	
	 $this->add(array(
	 'name' => 'email',
	 'type' => 'text',
	 'options' => array(
	   'label' => 'Email Address',
	   'label_attributes' => array (
	    'class' => 'blocklabel'
	   )
	  ),
	  'attributes' => array (
	   'class' => 'stdTextInput'
	  )
	 ));
	 
	$this->add(array(
	 'name' => 'password',
	 'type' => 'password',
	 'options' => array(
	   'label' => 'Password',
	   'label_attributes' => array (
	    'class' => 'blocklabel'
	   )
	  ),
	  'attributes' => array (
	   'class' => 'stdTextInput'
	  )
	 ));
	 
	 $this->add(array(
	 'name' => 'confirm-password',
	 'type' => 'password',
	 'options' => array(
	   'label' => 'Confirm Password',
	   'label_attributes' => array (
	    'class' => 'blocklabel'
	   )
	  ),
	  'attributes' => array (
	   'class' => 'stdTextInput'
	  )
	 ));
	 
	 
	 $this->add(array(
	 'name' => 'home-address',
	 'type' => 'text',
	 'options' => array(
	   'label' => 'Home Address',
	   'label_attributes' => array (
	    'class' => 'blocklabel'
	   )
	  ),
	  'attributes' => array (
	   'class' => 'stdTextInput'
	  )
	 ));
	 
	 $this->add(array(
	 'name' => 'home-address-2',
	 'type' => 'text',
	 'options' => array(
	   'label' => 'Home Address 2',
	   'label_attributes' => array (
	    'class' => 'blocklabel'
	   )
	  ),
	  'attributes' => array (
	   'class' => 'stdTextInput'
	  )
	 ));
	 
	 $this->add(array(
	 'name' => 'home-city',
	 'type' => 'text',
	 'options' => array(
	   'label' => 'City',
	   'label_attributes' => array (
	    'class' => 'blocklabel'
	   )
	  ),
	  'attributes' => array (
	   'class' => 'stdTextInput'
	  )
	 ));
	 
	 $this->add(array(
	 'name' => 'home-state',
	 'type' => 'Select',
	 'options' => array (
	   'label' => 'State',
	   'label_attributes' => array(
	     'class' => 'blocklabel'
		 ),
		'value_options' => array (
		   '1' => 'AL',
           '2' => 'AK',
           '3'	=> 'AZ',
           '4'	=> 'AR',
           '5'	=> 'CA',
           '6'	=> 'CO',
           '7'	=> 'CT',
           '8'	=> 'DE',
           '9'	=> 'DC',
           '10' => 'FL',
           '11' => 'GA',
           '12' => 'HI',
           '13' => 'ID',
           '14' => 'IL',
           '15' => 'IN',
           '16' => 'IA',
           '17' => 'KS',
           '18' => 'KY',
           '19' => 'LA',
           '20' => 'ME',
           '21' => 'MD',
           '22' => 'MA',
           '23' => 'MI',
           '24' => 'MN',
           '25' => 'MS',
           '26' => 'MO',
           '27' => 'MT',
           '28' => 'NE',
           '29' => 'NV',
           '30' => 'NH',
           '31' => 'NJ',
           '32' => 'NM',
           '33' => 'NY',
           '34' => 'NC',
           '35' => 'ND',
           '36' => 'OH',
           '37' => 'OK',
           '38' => 'OR',
           '39' => 'PA',
           '40' => 'RI',
           '41' => 'SC',
           '42' => 'SD',
           '43' => 'TN',
           '44' => 'TX',
           '45' => 'UT',
           '46' => 'VT',
           '47' => 'VA',
           '48' => 'WA',
           '49' => 'WV',
           '50' => 'WI',
           '51' => 'WY',   
		  )
		),
		'attributes' => array(
		  'class' => 'stdTextInputSmall',
		)
		));
		
     	
	$this->add(array(
	 'name' => 'home-country',
	 'type' => 'Select',
	 'options' => array (
	   'label' => 'Country',
	   'label_attributes' => array(
	     'class' => 'blocklabel'
		 ),
		'value_options' => array (
		   '1' => 'USA',
		   '2' => 'Canada',
		   '3' => 'UK',
		   
		  )
		),
		'attributes' => array(
		  'class' => 'stdTextInputSmall',
		)
		));
		
	 $this->add(array(
	  'name' => 'home-zip',
	  'type' => 'text',
	  'options' => array(
	    'label' => 'Zip Code',
	    'label_attributes' => array (
	     'class' => 'blocklabel'
	   )
	  ),
	  'attributes' => array (
	   'class' => 'stdTextInput'
	  )
	 ));	
	 
	 
	 $this->add(array(
	 'name' => 'home-country',
	 'type' => 'Select',
	 'options' => array (
	   'label' => 'Country',
	   'label_attributes' => array(
	     'class' => 'blocklabel'
		 ),
		'value_options' => array (
		   '1' => 'USA',
		   '2' => 'Canada',
		   '3' => 'UK',
		   
		  )
		),
		'attributes' => array(
		  'class' => 'stdTextInputSmall',
		)
		));
		
	$this->add(array(
	 'name' => 'home-phone-type',
	 'type' => 'Select',
	 'options' => array (
	   'label' => 'Phone Type',
	   'label_attributes' => array(
	     'class' => 'blocklabel'
		 ),
		'value_options' => array (
		   '1' => 'Home',
		   '2' => 'Mobile',
		   '3' => 'Other',
		   
		  )
		),
		'attributes' => array(
		  'class' => 'stdTextInputSmall',
		)
		));
		
	
		
		$this->add(array(
	    'name' => 'home-country-code',
	    'type' => 'Select',
	    'options' => array (
	      'label' => 'Country Code',
	      'label_attributes' => array(
	       'class' => 'blocklabel'
		   ),
		   'value_options' => array (
		   '1' => '44',
		   '2' => '34',
		   '3' => '598',
		   
		  )
		),
		'attributes' => array(
		  'class' => 'stdTextInputSmall',
		)
		));
		
		 $this->add(array(
	      'name' => 'home-phone',
	      'type' => 'text',
	      'options' => array(
	      'label' => 'Home Phone',
	      'label_attributes' => array (
	      'class' => 'blocklabel'
	      )
	     ),
	     'attributes' => array (
	     'class' => 'stdTextInput'
	    )
	   ));	
	   
	 

	$this->add(array(
	 'name' => 'newspaper',
	 'type' => 'Select',
	 'options' => array (
	   'label' => 'Newspaper',
	   'label_attributes' => array(
	     'class' => 'blocklabel'
		 ),
		'value_options' => array (
		   '1' => 'Yes',
		   '2' => 'No',	   
		   
		  )
		),
		'attributes' => array(
		  'class' => 'stdTextInputSmall',
		)
		));

    $this->add(array(
	 'name' => 'correspondence',
	 'type' => 'Radio',
	 'options' => array (
	   'label' => 'Please send correspondence:',
	   'label_attributes' => array(
	     'class' => 'blocklabel'
		 ),
		'value_options' => array (
		   '1' => 'to my home',
		   '2' => 'to my business',
		   
		  )
		),
		'attributes' => array(
		  'class' => 'stdTextInputSmall',
		)
		));

     $this->add(array(
	 'name' => 'special-offers',
	 'type' => 'Radio',
	 'options' => array (
	   'label' => 'Would you like to receive special offers from Loews Hotels via email?',
	   'label_attributes' => array(
	     'class' => 'blocklabel'
		 ),
		'value_options' => array (
		   '1' => 'Yes',
		   '2' => 'No',
		   
		  )
		),
		'attributes' => array(
		  'class' => 'stdTextInputSmall',
		)
		));



	 $this->add(array(
	 'name' => 'bed-type',
	 'type' => 'Select',
	 'options' => array (
	   'label' => 'Bed Type',
	   'label_attributes' => array(
	     'class' => 'blocklabel'
		 ),
		'value_options' => array (
		   '1' => 'King',
		   '2' => 'Queen',
		   '3' => 'Dobule',
		   '4' => 'Twin',
		   
		  )
		),
		'attributes' => array(
		  'class' => 'stdTextInputSmall',
		)
		));

    

	 $this->add(array(
	 'name' => 'arrival-time',
	 'type' => 'Select',
	 'options' => array (
	   'label' => 'Arrival Time',
	   'label_attributes' => array(
	     'class' => 'blocklabel'
		 ),
		'value_options' => array (
		   '1' => 'Morning',
		   '2' => 'Afternoon',
		   '3' => 'Evening',
		   		   
		  )
		),
		'attributes' => array(
		  'class' => 'stdTextInputSmall',
		)
		));

	  $this->add(array(
	 'name' => 'floor-preference',
	 'type' => 'Select',
	 'options' => array (
	   'label' => 'Floor Preference',
	   'label_attributes' => array(
	     'class' => 'blocklabel'
		 ),
		'value_options' => array (
		   '1' => 'First',
		   '2' => 'Second',
		   '3' => 'Third',
		   '4' => 'Four',
		   
		  )
		),
		'attributes' => array(
		  'class' => 'stdTextInputSmall',
		)
		));

      $this->add(array(
	 'name' => 'program-interest',
	 'type' => 'Radio',
	 'options' => array (
	   'label' => 'Program Interest',
	   'label_attributes' => array(
	     'class' => 'blocklabel'
		 ),
		'value_options' => array (
		   '1' => 'Business Travel',
		   '2' => 'Resort Vacations',
		   '3' => 'Travel with Children',
		   '4' => 'Golf Getaways',
		   '5' => 'Spa Getaways',
		   '6' => 'Travel with Pets',
		   
		  )
		),
		'attributes' => array(
		  'class' => 'stdTextInputSmall',
		)
		));

     $this->add(array(
	 'name' => 'other-request',
	 'type' => 'Textarea',
	 'options' => array(
	   'label' => 'Other Reqeust',
	   'label_attributes' => array (
	    'class' => 'blocklabel'
	   )
	  ),
	  'attributes' => array (
	   'class' => 'stdTextInput'
	  )
	 ));

     $this->add(array(
	  'name' => 'card-number',
	  'type' => 'text',
	  'options' => array(
	    'label' => 'Card Number',
	    'label_attributes' => array (
	     'class' => 'blocklabel'
	   )
	  ),
	  'attributes' => array (
	   'class' => 'stdTextInput'
	  )
	 ));    


    $this->add(array(
	 'name' => 'card-type',
	 'type' => 'Select',
	 'options' => array (
	   'label' => 'Card Type',
	   'label_attributes' => array(
	     'class' => 'blocklabel'
		 ),
		'value_options' => array (
		   '1' => 'Mastercard',
		   '2' => 'Visa',
		   '3' => 'American Express',
		   '4' => 'Discover',
		   
		  )
		),
		'attributes' => array(
		  'class' => 'stdTextInputSmall',
		)
		));



     $this->add(array(
	 'name' => 'expiration-month',
	 'type' => 'Select',
	 'options' => array (
	   'label' => 'Expiration Month',
	   'label_attributes' => array(
	     'class' => 'blocklabel'
		 ),
		'value_options' => array (
		  '1' => 'January',
          '2' => 'February',
          '3' => 'March',
          '4' => 'April',
          '5' => 'May',
          '6' => 'June',
          '7' => 'July',
          '8' => 'August',
          '9' => 'September',
          '10' => 'October',
          '11' => 'November',
          '12' => 'December',
		   
		  )
		),
		'attributes' => array(
		  'class' => 'stdTextInputSmall',
		)
		));

      $this->add(array(
	 'name' => 'expiration-year',
	 'type' => 'Select',
	 'options' => array (
	   'label' => 'Expiration Year',
	   'label_attributes' => array(
	     'class' => 'blocklabel'
		 ),
		'value_options' => array (
		 '1' => '2013',
         '2' => '2014',
         '3' => '2015',
         '4' => '2016',
         '5' => '2017',
         '6' => '2018',
         '7' => '2019',
         '8' => '2020',
         '9' => '2021',
         '10' => '2022',
         '11' => '2023',		   
		  )
		),
		'attributes' => array(
		  'class' => 'stdTextInputSmall',
		)
		));

     $this->add(array(
	 'name' => 'arrival-month',
	 'type' => 'Select',
	 'options' => array (
	   'label' => 'Arrival Month',
	   'label_attributes' => array(
	     'class' => 'blocklabel'
		 ),
		'value_options' => array (
		   '1' => 'Jan',
		   '2' => 'Feb',
		   '3' => 'Mar',
		   '4' => 'Apr',
		   '5' => 'May',
		   '6' => 'Jun',
		   '7' => 'Jul',
		   '8' => 'Aug',
		   '9' => 'Sep',
		   '10' => 'Oct',
		   '11' => 'Nov',
		   '12' => 'Dec',
		  )
		),
		'attributes' => array(
		  'class' => 'stdTextInputSmall',
		)
		));
		
		
  $this->add(array(
	 'name' => 'arrival-date',
	 'type' => 'Select',
	 'options' => array (
	   'label' => 'Arrival Date',
	   'label_attributes' => array(
	     'class' => 'blocklabel'
		 ),
		'value_options' => array (
		   '1' => '1',
		   '2' => '2',
		   '3' => '3',
		   '4' => '4',
		   '5' => '5',
		   '6' => '6',
		   '7' => '7',
		   '8' => '8',
		   '9' => '9',
		   '10' => '10',
		   '11' => '11',
		   '12' => '12',
		   '13' => '13',
		   '14' => '14',
		   '15' => '15',
		   '16' => '16',
		   '17' => '17',
		   '18' => '18',
		   '19' => '19',
		   '20' => '20',
		   '21' => '21',
		   '22' => '22',
		   '23' => '23',
		   '24' => '24',
		   '25' => '25',
		   '26' => '26',
		   '27' => '27',
		   '28' => '28',
		   '29' => '29',
		   '30' => '30',
		   '31' => '31',
		  )
		),
		'attributes' => array(
		  'class' => 'stdTextInputSmall',
		)
		));
		
		
   $this->add(array(
	 'name' => 'arrival-year',
	 'type' => 'Select',
	 'options' => array (
	   'label' => 'Arrival year',
	   'label_attributes' => array(
	     'class' => 'blocklabel'
		 ),
		'value_options' => array (
		   '1' => '2013',
		   '2' => '2014',
		   '3' => '2015',
		   '4' => '2016',
			   
		  )
		),
		'attributes' => array(
		  'class' => 'stdTextInputSmall',
		)
		));

		
	 $this->add(array(
	 'name' => 'title',
	 'type' => 'Select',
	 'options' => array (
	   'label' => 'Title',
	   'label_attributes' => array(
	     'class' => 'blocklabel'
		 ),
		'value_options' => array (
		   '1' => 'Mr',
		   '2' => 'Mrs',
		   '3' => 'Ms',
		  )
		),
		'attributes' => array(
		  'class' => 'stdTextInputSmall',
		)
		));
		
		
	$this->add(array(
	 'name' => 'behalf-firstname',
	 'type' => 'text',
	 'options' => array(
	   'label' => 'First Name',
	   'label_attributes' => array (
	    'class' => 'blocklabel'
	   )
	  ),
	  'attributes' => array (
	   'class' => 'stdTextInput'
	  )
	 ));
	 
	 
	 $this->add(array(
	 'name' => 'behalf-middlename',
	 'type' => 'text',
	 'options' => array(
	   'label' => 'Middle Name',
	   'label_attributes' => array (
	    'class' => 'blocklabel'
	   )
	  ),
	  'attributes' => array (
	   'class' => 'stdTextInput'
	  )
	 ));
	 
	 $this->add(array(
	 'name' => 'behalf-lastname',
	 'type' => 'text',
	 'options' => array(
	   'label' => 'Last Name',
	   'label_attributes' => array (
	    'class' => 'blocklabel'
	   )
	  ),
	  'attributes' => array (
	   'class' => 'stdTextInput'
	  )
	 ));
	   
	 $this->add(array(
	 'name' => 'behalf-email',
	 'type' => 'text',
	 'options' => array(
	   'label' => 'Email Address',
	   'label_attributes' => array (
	    'class' => 'blocklabel'
	   )
	  ),
	  'attributes' => array (
	   'class' => 'stdTextInput'
	  )
	 ));
	   
	    $this->add(array(
	    'name' => 'behalf-country-code',
	    'type' => 'Select',
	    'options' => array (
	      'label' => 'Country Code',
	      'label_attributes' => array(
	       'class' => 'blocklabel'
		   ),
		   'value_options' => array (
		   '1' => '44',
		   '2' => '34',
		   '3' => '598',
		   
		  )
		),
		'attributes' => array(
		  'class' => 'stdTextInputSmall',
		)
		));
		
		 $this->add(array(
	      'name' => 'behalf-home-phone',
	      'type' => 'text',
	      'options' => array(
	      'label' => 'Home Phone',
	      'label_attributes' => array (
	      'class' => 'blocklabel'
	      )
	     ),
	     'attributes' => array (
	     'class' => 'stdTextInput'
	    )
	   ));	
 	}
 } 