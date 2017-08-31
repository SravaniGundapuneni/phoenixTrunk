<?php

namespace ContentFilter\Form;

use ListModule\Form\Form;
///use Zend\Form\Form;

class ContentFilter extends Form
{
    public function __construct($name = null)
    {
	
	    // CONTENT FILTER FORM STARTS HERE 22 0CT 2014, 12:31 NOON 		
		parent::__construct('contentFilterForm');
        $this->setAttribute('method', 'post');
		$this->setAttribute('enctype', 'multipart/form-data');		
		// CONTENT FILTER FORM ENDS HERE 22 OCT 2014, 12:34 NOON
	    
		// HOTEL NAME CODE STARTS		
		// CHECKBOX CODE STARTS 	
        $this->add($this->checkbox('hotelCheckbox', 'Hotel Checkbox'));
		// CHECKBOX CODE ENDS 		
        // SELECT CODE STARTS  		
		$this->add(array(
            'name' => 'hotel',
			 'id'=>'hotel',
            'type' => 'select',
			 'options' => array(
               // 'value_options' => $this->getHotelOptions(),
                'label' => 'Property Name',
                'label_attributes' => array(
                    'class' => 'blockLabel'
                )
            ),           
            'attributes' => array(
                'class' => 'stdInputText',
            )            
        ));		
		// SELECT CODE ENDS 
		// HOTEL NAME CODE ENDS 
		
		// ROOM NAME CODE STARTS		
		// CHECKBOX CODE STARTS 	
        $this->add($this->checkbox('roomNameCheckbox', 'Room Name Checkbox'));
		// CHECKBOX CODE ENDS 		
        // SELECT CODE STARTS  		
		$this->add(array(
            'name' => 'roomName',
            'id'=>'roomName',
            'type' => 'select',
			 'options' => array(
                
                'label' => 'Room Name',
                'label_attributes' => array(
                    'class' => 'blockLabel'
                )
            ),           
            'attributes' => array(
                'class' => 'stdInputText',
            )            
        ));		
		// SELECT CODE ENDS 
		// ROOM NAME CODE ENDS 
		
		// BED TYPE CODE STARTS		
		// CHECKBOX CODE STARTS 	
        $this->add($this->checkbox('bedTypeCheckbox', 'Bed Type Checkbox'));
		// CHECKBOX CODE ENDS 		
        // SELECT CODE STARTS  		
	    $this->add(array(
            'name' => 'bedType',
			'id'=>'bedType',
            'type' => 'select',
			 'options' => array(
                /*'value_options' => array(
				                         'Twin' => 'Twin',
				                         'Queen' => 'Queen',
                                         'King' => 'King'                                      										 
                                        ),*/
                'label' => 'Bed Type',
                'label_attributes' => array(
                    'class' => 'blockLabel'
                )
            ),           
            'attributes' => array(
                'class' => 'stdInputText',
            )            
        ));		 
		// SELECT CODE ENDS 
		// BED TYPE CODE ENDS 
		
		// ROOM CATEGORIES CODE STARTS		
		// CHECKBOX CODE STARTS 	
        $this->add($this->checkbox('roomCategoriesCheckbox', 'Room Categories Checkbox'));
		// CHECKBOX CODE ENDS 		
        // SELECT CODE STARTS  		
        $this->add(array(
            'name' => 'roomCategories',
            'id'=>'roomCategories',
			'type' => 'select',
			'options' => array(
                /*'value_options' => array(
				                         '1' => 'Room',
				                         '2' => 'Suite',
                                         '3' => 'Villa'                                      										 
                                        ),*/
                'label' => 'Room Categories',
                'label_attributes' => array(
                    'class' => 'blockLabel'
                )
            ),           
            'attributes' => array(
                'class' => 'stdInputText',
            )            
        ));		 
		// SELECT CODE ENDS 
		// ROOM CATEGORIES CODE ENDS 
		
		// MAX OCCUPANCY CODE STARTS		
		// CHECKBOX CODE STARTS 	
        $this->add($this->checkbox('maxOccupancyCheckbox', 'Max Occupancy Checkbox'));
		// CHECKBOX CODE ENDS 		
        // SELECT CODE STARTS  		
        $this->add(array(
            'name' => 'maxOccupancy',
            'type' => 'select',
			 'options' => array(
                'value_options' => array(
				                         '2' => '2',
				                         '1' => '1',
				                         '2' => '2',
                                         '3' => '3',
                                         '4' => '4',
				                         '5' => '5',
                                         '6' => '6',
                                         '7' => '7',
				                         '8' => '8',
                                         '9' => '9',
                                         '10' => '10'										 
                                        ),
                'label' => 'Max Occupancy',
                'label_attributes' => array(
                    'class' => 'blockLabel'
                )
            ),           
            'attributes' => array(
                'class' => 'stdInputText',
            )            
        ));		 
		// SELECT CODE ENDS 
		// MAX OCCUPANCY CODE ENDS 
		
		
	    // ROOM CODE CODE STARTS		
		// CHECKBOX CODE STARTS 	
        $this->add($this->checkbox('roomCodeCheckbox', 'Room Code Checkbox'));
		// CHECKBOX CODE ENDS 		
        // SELECT CODE STARTS  		
        $this->add(array(
            'name' => 'roomCode',
            'id'=>'roomCode',
            'type' => 'select',
			 'options' => array(
                
                'label' => 'Room Code',
                'label_attributes' => array(
                    'class' => 'blockLabel'
                )
            ),           
            'attributes' => array(
                'class' => 'stdInputText',
            )            
        ));		 
		// SELECT CODE ENDS 
		// ROOM CODE CODE ENDS
		
		// ROOM ID CODE STARTS		
		// CHECKBOX CODE STARTS 	
        $this->add($this->checkbox('roomIdCheckbox', 'Room Id Checkbox'));
		// CHECKBOX CODE ENDS 		
        // SELECT CODE STARTS  		
        $this->add(array(
            'name' => 'roomId',
            'type' => 'text',
            'options' => array(               
                'label' => 'Room Id',
                'label_attributes' => array(
                    'class' => 'blockLabel'
                )
            ),
            'attributes' => array(
                'class' => 'stdInputText',
			    'value' => '000',
				'required' => true,
				'autocomplete' => true,
            )            
        ));
		// SELECT CODE ENDS 
		// ROOM ID CODE ENDS
		
		
		// ROOM DESCRIPTION CODE STARTS		
		// CHECKBOX CODE STARTS 	
        $this->add($this->checkbox('roomDescriptionCheckbox', 'Room Description Checkbox'));
		// CHECKBOX CODE ENDS 		
        // SELECT CODE STARTS  		
        $this->add(array(
            'name' => 'roomDescription',
            'type' => 'text',
            'options' => array(               
                'label' => 'Keywords',
                'label_attributes' => array(
                    'class' => 'blockLabel'
                )
            ),
            'attributes' => array(
                'class' => 'stdInputText',
			    'value' => 'none',
				'required' => true,
				'autocomplete' => true,
            )            
        ));
		// SELECT CODE ENDS 
		// ROOM DESCRIPTION CODE ENDS
				
		// RESRT BUTTON CODE STARTS 
		
		$this->add(array(
		'name'=>'reset',
		'attributes'=>array
		(
		'type'=>'reset',
		'value'=>'Reset',
		'id'=>'resetbutton',
		),    
		));

        // RESET BUTTON CODE ENDS 

		
		// SUBMIT BUTTON CODE STARTS 
		
		$this->add(array(
		'name'=>'submit',
		'attributes'=>array
		(
		'type'=>'submit',
		'value'=>'Submit',
		'id'=>'submitbutton',
		),    
		));
		
	
        // hidden field code starts

        $this->add(array(
            'name' => 'id',
            'type' => 'Hidden',
        ));

        // hidden field code ends 		
		
		
			
    }
}