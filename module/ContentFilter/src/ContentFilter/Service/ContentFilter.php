<?php

namespace ContentFilter\Service;


use Zend\ViewRenderer\RendererInterface as ViewRenderer;
use ContentFilter\Model\PhoenixContentFilter;
use ContentFilter\EventManager\Event as PhoenixContentFilterEvent;

class ContentFilter extends \ListModule\Service\Lists {



  /**
     * __construct
     *
     * Construct our UserService service
     *
     * @return void
     */
    public function __construct()
    {       
        $this->entityName = PhoenixContentFilter::CONTENTFILTER_ENTITY_NAME;
        $this->modelClass = "\ContentFilter\Model\PhoenixContentFilter";
    }
		
	public function getHotelOptions()
    {
        $options = array();
        $hotels = $this->getDefaultEntityManager()->getRepository('PhoenixProperties\Entity\PhoenixProperty')->findBy(array('status' => 1));
        foreach ($hotels as $keyHotel => $valHotel) {
            $options[$valHotel->getId()] = $valHotel->getName();
        }
        return $options;
    }
	//getRoom

	public function getRoomOptions()
    {
        $options = array();
        $rooms = $this->getDefaultEntityManager()->getRepository('PhoenixRooms\Entity\PhoenixRoom')->findBy(array('status' => 1));
        foreach ($rooms as $keyRoom => $valRoom) {
            $options[$valRoom->getName()] = $valRoom->getName();
        }
        return $options;
    }

    //getCode
    //getRoom

	public function getRoomCodeOptions()
    {
        $options = array();
        $rooms = $this->getDefaultEntityManager()->getRepository('PhoenixRooms\Entity\PhoenixRoom')->findBy(array('status' => 1));
        foreach ($rooms as $keyRoomCode => $valRoomCode) {
            $options[$valRoomCode->getCode()] = $valRoomCode->getCode();
        }
        return $options;
    }

	//get bed type
	public function getBedTypeOptions(){
	
		$options = array();

        $rooms = $this->getDefaultEntityManager()->getRepository('PhoenixRooms\Entity\PhoenixRoom')->findBy(array('status' => 1));

        foreach ($rooms as $keyBedType => $valBedType) {
            $options[$valBedType->getBedType()] = $valBedType->getBedType();
        }
        return $options;
	}
	
	//get RoomCategories	
	public function getRoomCategoryOptions()
    {
        $options = array();
        $cat = $this->getDefaultEntityManager()->getRepository('ListModule\Entity\Categories')->findBy(array('module' => 'PhoenixRooms','status' => 1));
        foreach ($cat as $keyCat => $valCat) {
            $options[$valCat->getId()] = $valCat->getName();
        }
        return $options;
    }
		
	public function getFilteredContents(
	                                    $hotelCheckbox, $hotel,
		                                $roomNameCheckbox, $roomName,
										$bedTypeCheckbox, $bedType,
										$roomCategoriesCheckbox, $roomCategories,
										$maxOccupancyCheckbox, $maxOccupancy,
										$roomCodeCheckbox, $roomCode,
										$roomIdCheckbox, $roomId,
										$roomDescriptionCheckbox, $roomDescription
										)
	{
	

	
	// QUERY FIRES BASED ON THE USER SELECTIONS STARTS  
		 
	   // LEVEL 1 STARTS  
		   // HOTELNAME ONLY STARTS 
		   if($hotelCheckbox==1 && $roomNameCheckbox==0 && $bedTypeCheckbox==0 && $roomCategoriesCheckbox==0
		   && $maxOccupancyCheckbox==0 && $roomCodeCheckbox==0 && $roomIdCheckbox==0 && $roomDescriptionCheckbox==0)
		   {       	
		   $queryString = $this->getDefaultEntityManager()->createQueryBuilder();  	   
		   $queryString->select('m.id,m.code,m.name,m.description,m.bedType,m.maxOccupancy,m.categories,m.featured,m.status')
						->from('ContentFilter\Entity\PhoenixContentFilter', 'm')
						->where('m.property = :pro')                
						->setParameter('pro', $hotel);
		   $result = $queryString->getQuery()->getResult();	
		   goto exitRoutrine;   
		  }
		  // HOTELNAME ONLY ENDS 
		  
		  // ROOMNAME ONLY STARTS 
		   if($hotelCheckbox==0 && $roomNameCheckbox==1 && $bedTypeCheckbox==0 && $roomCategoriesCheckbox==0
		   && $maxOccupancyCheckbox==0 && $roomCodeCheckbox==0 && $roomIdCheckbox==0 && $roomDescriptionCheckbox==0)
		   {       	
		   $queryString = $this->getDefaultEntityManager()->createQueryBuilder();  	   
		   $queryString->select('m.id,m.code,m.name,m.description,m.bedType,m.maxOccupancy,m.categories,m.featured,m.status')
						->from('ContentFilter\Entity\PhoenixContentFilter', 'm')
						->where('m.name = :rn')                
						->setParameter('rn', $roomName);
		   $result = $queryString->getQuery()->getResult();	
		   goto exitRoutrine;   
		  }
		  // ROOMNAME ONLY ENDS	 

		  // BEDTYPE ONLY STARTS 
		   if($hotelCheckbox==0 && $roomNameCheckbox==0 && $bedTypeCheckbox==1 && $roomCategoriesCheckbox==0
		   && $maxOccupancyCheckbox==0 && $roomCodeCheckbox==0 && $roomIdCheckbox==0 && $roomDescriptionCheckbox==0)
		   {       	
		   $queryString = $this->getDefaultEntityManager()->createQueryBuilder();  	   
		   $queryString->select('m.id,m.code,m.name,m.description,m.bedType,m.maxOccupancy,m.categories,m.featured,m.status')
						->from('ContentFilter\Entity\PhoenixContentFilter', 'm')
						->where('m.bedType = :bedTy')                
						->setParameter('bedTy', $bedType);
		   $result = $queryString->getQuery()->getResult();	
		   goto exitRoutrine;   
		  }
		  // BEDTYPE ONLY ENDS	

		  // ROOMCAREGORY ONLY STARTS 
		   if($hotelCheckbox==0 && $roomNameCheckbox==0 && $bedTypeCheckbox==0 && $roomCategoriesCheckbox==1
		   && $maxOccupancyCheckbox==0 && $roomCodeCheckbox==0 && $roomIdCheckbox==0 && $roomDescriptionCheckbox==0)
		   {       	
		   $queryString = $this->getDefaultEntityManager()->createQueryBuilder();  	   
		   $queryString->select('m.id,m.code,m.name,m.description,m.bedType,m.maxOccupancy,m.category,m.featured,m.status')
						->from('ContentFilter\Entity\PhoenixContentFilter', 'm')
						->where('m.category = :rmCat')                
						->setParameter('rmCat', $roomCategories);
		   $result = $queryString->getQuery()->getResult();	
		   goto exitRoutrine;   
		  }
		  // ROOMCATEGORY ONLY ENDS	

		  // MAXOCCUPANCY ONLY STARTS 
		   if($hotelCheckbox==0 && $roomNameCheckbox==0 && $bedTypeCheckbox==0 && $roomCategoriesCheckbox==0
		   && $maxOccupancyCheckbox==1 && $roomCodeCheckbox==0 && $roomIdCheckbox==0 && $roomDescriptionCheckbox==0)
		   {       	
		   $queryString = $this->getDefaultEntityManager()->createQueryBuilder();  	   
		   $queryString->select('m.id,m.code,m.name,m.description,m.bedType,m.maxOccupancy,m.categories,m.featured,m.status')
						->from('ContentFilter\Entity\PhoenixContentFilter', 'm')
						->where('m.maxOccupancy = :mxOcu')                
						->setParameter('mxOcu', $maxOccupancy);
		   $result = $queryString->getQuery()->getResult();	
		   goto exitRoutrine;   
		  }
		  // MAXOCCUPANCY ONLY ENDS	  
						  
		   // ROOMCODE ONLY STARTS 
		   if($hotelCheckbox==0 && $roomNameCheckbox==0 && $bedTypeCheckbox==0 && $roomCategoriesCheckbox==0
		   && $maxOccupancyCheckbox==0 && $roomCodeCheckbox==1 && $roomIdCheckbox==0 && $roomDescriptionCheckbox==0)
		   {       	
		   $queryString = $this->getDefaultEntityManager()->createQueryBuilder();  	   
		   $queryString->select('m.id,m.code,m.name,m.description,m.bedType,m.maxOccupancy,m.categories,m.featured,m.status')
						->from('ContentFilter\Entity\PhoenixContentFilter', 'm')
						->where('m.code = :roomCod')                
						->setParameter('roomCod', $roomCode);
		   $result = $queryString->getQuery()->getResult();	
		   goto exitRoutrine;   
		  }
		  // ROOMCODE ONLY ENDS

		   // ROOMID ONLY STARTS 
		   if($hotelCheckbox==0 && $roomNameCheckbox==0 && $bedTypeCheckbox==0 && $roomCategoriesCheckbox==0
		   && $maxOccupancyCheckbox==0 && $roomCodeCheckbox==0 && $roomIdCheckbox==1 && $roomDescriptionCheckbox==0)
		   {       	
		   $queryString = $this->getDefaultEntityManager()->createQueryBuilder();  	   
		   $queryString->select('m.id,m.code,m.name,m.description,m.bedType,m.maxOccupancy,m.categories,m.featured,m.status')
						->from('ContentFilter\Entity\PhoenixContentFilter', 'm')
						->where('m.id = :rmId')                
						->setParameter('rmId', $roomId);
		   $result = $queryString->getQuery()->getResult();	
		   goto exitRoutrine;   
		  }
		  // ROOMID ONLY ENDS

		  // ROOMDESCRIPTION ONLY STARTS 
		   if($hotelCheckbox==0 && $roomNameCheckbox==0 && $bedTypeCheckbox==0 && $roomCategoriesCheckbox==0
		   && $maxOccupancyCheckbox==0 && $roomCodeCheckbox==0 && $roomIdCheckbox==0 && $roomDescriptionCheckbox==1)
		   {       	
		   $queryString = $this->getDefaultEntityManager()->createQueryBuilder();  	
		   $queryString->select('m.id,m.code,m.name,m.description,m.bedType,m.maxOccupancy,m.categories,m.featured,m.status')
						->from('ContentFilter\Entity\PhoenixContentFilter', 'm')
						->where('m.property = :par1')  
						->orwhere('m.name = :par2')                 
						->orwhere('m.code = :par3')  
						->orwhere('m.categories = :par4')
						->orwhere('m.bedType = :par5')
						->orwhere('m.description like :par6')   
						->setParameter('par1', $roomDescription)			    
						->setParameter('par2', $roomDescription)
						->setParameter('par3', $roomDescription)
						->setParameter('par4', $roomDescription)					
						->setParameter('par5', $roomDescription)
						->setParameter('par6', '%'.$roomDescription.'%')
						;			
		   $result = $queryString->getQuery()->getResult();	
		   goto exitRoutrine;   
		   }
		  // ROOMDESCRIPTION ONLY ENDS
      // LEVEL 1 ENDS 


      // LEVEL 2 STARTS 	  
	  // HOTELNAME AND ROOMNAME ONLY STARTS 
	    if($hotelCheckbox==1 && $roomNameCheckbox==1 && $bedTypeCheckbox==0 && $roomCategoriesCheckbox==0
	   && $maxOccupancyCheckbox==0 && $roomCodeCheckbox==0 && $roomIdCheckbox==0 && $roomDescriptionCheckbox==0)
	   {       	
       $queryString = $this->getDefaultEntityManager()->createQueryBuilder();  	
	   $queryString->select('m.id,m.code,m.name,m.description,m.bedType,m.maxOccupancy,m.categories,m.featured,m.status')
                    ->from('ContentFilter\Entity\PhoenixContentFilter', 'm')
					->where('m.property = :par1')  
                    ->andwhere('m.name = :par2')                   
                    ->setParameter('par1', $hotel)			    
					->setParameter('par2', $roomName);			
	   $result = $queryString->getQuery()->getResult();	
	   goto exitRoutrine;   
	   }
	  // HOTELNAME AND ROOMNAME ONLY STARTS 
	  
	  // HOTELNAME AND BEDTYPE ONLY STARTS 
	  if($hotelCheckbox==1 && $roomNameCheckbox==0 && $bedTypeCheckbox==1 && $roomCategoriesCheckbox==0
	   && $maxOccupancyCheckbox==0 && $roomCodeCheckbox==0 && $roomIdCheckbox==0 && $roomDescriptionCheckbox==0)
	   {       	
       $queryString = $this->getDefaultEntityManager()->createQueryBuilder();  	
	   $queryString->select('m.id,m.code,m.name,m.description,m.bedType,m.maxOccupancy,m.categories,m.featured,m.status')
                    ->from('ContentFilter\Entity\PhoenixContentFilter', 'm')
					->where('m.property = :par1')  
                    ->andwhere('m.bedType = :par2')                   
                    ->setParameter('par1', $hotel)			    
					->setParameter('par2', $bedType);			
	   $result = $queryString->getQuery()->getResult();	
	   goto exitRoutrine;   
	   }
	  // HOTELNAME AND BEDTYPE ONLY STARTS 
	  
	  // HOTELNAME AND ROOMCATEGORY ONLY STARTS 
	  if($hotelCheckbox==1 && $roomNameCheckbox==0 && $bedTypeCheckbox==0 && $roomCategoriesCheckbox==1
	   && $maxOccupancyCheckbox==0 && $roomCodeCheckbox==0 && $roomIdCheckbox==0 && $roomDescriptionCheckbox==0)
	   {       	
       $queryString = $this->getDefaultEntityManager()->createQueryBuilder();  	
	   $queryString->select('m.id,m.code,m.name,m.description,m.bedType,m.maxOccupancy,m.categories,m.category,m.featured,m.status')
                    ->from('ContentFilter\Entity\PhoenixContentFilter', 'm')
					->where('m.property = :par1')  
                    ->andwhere('m.category = :par2')                   
                    ->setParameter('par1', $hotel)			    
					->setParameter('par2', $roomCategories);			
	   $result = $queryString->getQuery()->getResult();	
	   goto exitRoutrine;   
	   }
	   // HOTELNAME AND ROOMCATEGORY ONLY STARTS 
	   
	  // HOTELNAME AND MAXOCCUPANCY ONLY STARTS 
	  if($hotelCheckbox==1 && $roomNameCheckbox==0 && $bedTypeCheckbox==0 && $roomCategoriesCheckbox==0
	   && $maxOccupancyCheckbox==1 && $roomCodeCheckbox==0 && $roomIdCheckbox==0 && $roomDescriptionCheckbox==0)
	   {       	
       $queryString = $this->getDefaultEntityManager()->createQueryBuilder();  	
	   $queryString->select('m.id,m.code,m.name,m.description,m.bedType,m.maxOccupancy,m.categories,m.featured,m.status')
                    ->from('ContentFilter\Entity\PhoenixContentFilter', 'm')
					->where('m.property = :par1')  
                    ->andwhere('m.maxOccupancy = :par2')                   
                    ->setParameter('par1', $hotel)			    
					->setParameter('par2', $maxOccupancy);			
	   $result = $queryString->getQuery()->getResult();	
	   goto exitRoutrine;   
	   }
	   // HOTELNAME AND MAXOCCUPANCY ONLY STARTS 
	   
	  // HOTELNAME AND ROOMCODE ONLY STARTS 
	  if($hotelCheckbox==1 && $roomNameCheckbox==0 && $bedTypeCheckbox==0 && $roomCategoriesCheckbox==0
	   && $maxOccupancyCheckbox==0 && $roomCodeCheckbox==1 && $roomIdCheckbox==0 && $roomDescriptionCheckbox==0)
	   {       	
       $queryString = $this->getDefaultEntityManager()->createQueryBuilder();  	
	   $queryString->select('m.id,m.code,m.name,m.description,m.bedType,m.maxOccupancy,m.categories,m.featured,m.status')
                    ->from('ContentFilter\Entity\PhoenixContentFilter', 'm')
					->where('m.property = :par1')  
                    ->andwhere('m.code = :par2')                   
                    ->setParameter('par1', $hotel)			    
					->setParameter('par2', $roomCode);			
	   $result = $queryString->getQuery()->getResult();	
	   goto exitRoutrine;   
	   }
	   // HOTELNAME AND ROOMCODE ONLY STARTS
	   
	  // HOTELNAME AND ROOMID ONLY STARTS 
	  if($hotelCheckbox==1 && $roomNameCheckbox==0 && $bedTypeCheckbox==0 && $roomCategoriesCheckbox==0
	   && $maxOccupancyCheckbox==0 && $roomCodeCheckbox==0 && $roomIdCheckbox==1 && $roomDescriptionCheckbox==0)
	   {       	
       $queryString = $this->getDefaultEntityManager()->createQueryBuilder();  	
	   $queryString->select('m.id,m.code,m.name,m.description,m.bedType,m.maxOccupancy,m.categories,m.featured,m.status')
                    ->from('ContentFilter\Entity\PhoenixContentFilter', 'm')
					->where('m.property = :par1')  
                    ->andwhere('m.id = :par2')                   
                    ->setParameter('par1', $hotel)			    
					->setParameter('par2', $roomId);			
	   $result = $queryString->getQuery()->getResult();	
	   goto exitRoutrine;   
	   }
	   // HOTELNAME AND ROOMID ONLY STARTS
	   	  
	   // HOTELNAME AND ROOMDESCRIPTION ONLY STARTS 
	   if($hotelCheckbox==1 && $roomNameCheckbox==0 && $bedTypeCheckbox==0 && $roomCategoriesCheckbox==0
	   && $maxOccupancyCheckbox==0 && $roomCodeCheckbox==0 && $roomIdCheckbox==0 && $roomDescriptionCheckbox==1)
	   {       	
       $queryString = $this->getDefaultEntityManager()->createQueryBuilder();  	
	   $queryString->select('m.id,m.code,m.name,m.description,m.bedType,m.maxOccupancy,m.category,m.featured,m.status')
                    ->from('ContentFilter\Entity\PhoenixContentFilter', 'm')
					->where('m.property = :par1')               
                    ->andwhere('m.description like :par6')   
                    ->setParameter('par1', $hotel)		
					->setParameter('par6', '%'.$roomDescription.'%');								
	   $result = $queryString->getQuery()->getResult();	
	   goto exitRoutrine;   
	   }
	  // HOTELNAME AND ROOMDESCRIPTION ONLY ENDS
	  	  
	  // ROOMNAME AND BEDTYPE ONLY STARTS 
	  if($hotelCheckbox==0 && $roomNameCheckbox==1 && $bedTypeCheckbox==1 && $roomCategoriesCheckbox==0
	   && $maxOccupancyCheckbox==0 && $roomCodeCheckbox==0 && $roomIdCheckbox==0 && $roomDescriptionCheckbox==0)
	   {       	
       $queryString = $this->getDefaultEntityManager()->createQueryBuilder();  	
	   $queryString->select('m.id,m.code,m.name,m.description,m.bedType,m.maxOccupancy,m.categories,m.featured,m.status')
                    ->from('ContentFilter\Entity\PhoenixContentFilter', 'm')
					->where('m.name = :par1')  
                    ->andwhere('m.bedType = :par2')                   
                    ->setParameter('par1', $roomName)			    
					->setParameter('par2', $bedType);			
	   $result = $queryString->getQuery()->getResult();	
	   goto exitRoutrine;   
	   }
	  // ROOMNAME AND BEDTYPE ONLY STARTS 
	  
	  // ROOMNAME AND ROOMCATEGORIES ONLY STARTS 
	  if($hotelCheckbox==0 && $roomNameCheckbox==1 && $bedTypeCheckbox==0 && $roomCategoriesCheckbox==1
	   && $maxOccupancyCheckbox==0 && $roomCodeCheckbox==0 && $roomIdCheckbox==0 && $roomDescriptionCheckbox==0)
	   {       	
       $queryString = $this->getDefaultEntityManager()->createQueryBuilder();  	
	   $queryString->select('m.id,m.code,m.name,m.description,m.bedType,m.maxOccupancy,m.categories,m.category,m.featured,m.status')
                    ->from('ContentFilter\Entity\PhoenixContentFilter', 'm')
					->where('m.name = :par1')  
                    ->andwhere('m.category = :par2')                   
                    ->setParameter('par1', $roomName)			    
					->setParameter('par2', $roomCategories);			
	   $result = $queryString->getQuery()->getResult();	
	   goto exitRoutrine;   
	   }
	  // ROOMNAME AND ROOMCATEGORIES ONLY STARTS
	  
	  // ROOMNAME AND MAXOCCUPANCY ONLY STARTS 
	  if($hotelCheckbox==0 && $roomNameCheckbox==1 && $bedTypeCheckbox==0 && $roomCategoriesCheckbox==0
	   && $maxOccupancyCheckbox==1 && $roomCodeCheckbox==0 && $roomIdCheckbox==0 && $roomDescriptionCheckbox==0)
	   {       	
       $queryString = $this->getDefaultEntityManager()->createQueryBuilder();  	
	   $queryString->select('m.id,m.code,m.name,m.description,m.bedType,m.maxOccupancy,m.categories,m.featured,m.status')
                    ->from('ContentFilter\Entity\PhoenixContentFilter', 'm')
					->where('m.name = :par1')  
                    ->andwhere('m.maxOccupancy = :par2')                   
                    ->setParameter('par1', $roomName)			    
					->setParameter('par2', $maxOccupancy);			
	   $result = $queryString->getQuery()->getResult();	
	   goto exitRoutrine;   
	   }
	  // ROOMNAME AND MAXOCCUPANCY ONLY STARTS
	  
	  // ROOMNAME AND ROOMCODE ONLY STARTS 
	  if($hotelCheckbox==0 && $roomNameCheckbox==1 && $bedTypeCheckbox==0 && $roomCategoriesCheckbox==0
	   && $maxOccupancyCheckbox==0 && $roomCodeCheckbox==1 && $roomIdCheckbox==0 && $roomDescriptionCheckbox==0)
	   {       	
       $queryString = $this->getDefaultEntityManager()->createQueryBuilder();  	
	   $queryString->select('m.id,m.code,m.name,m.description,m.bedType,m.maxOccupancy,m.categories,m.category,m.featured,m.status')
                    ->from('ContentFilter\Entity\PhoenixContentFilter', 'm')
					->where('m.name = :par1')  
                    ->andwhere('m.code = :par2')                   
                    ->setParameter('par1', $roomName)			    
					->setParameter('par2', $roomCode);			
	   $result = $queryString->getQuery()->getResult();	
	   goto exitRoutrine;   
	   }
	  // ROOMNAME AND ROOMCODE ONLY STARTS
	  
	  // ROOMNAME AND ROOMID ONLY STARTS 
	  if($hotelCheckbox==0 && $roomNameCheckbox==1 && $bedTypeCheckbox==0 && $roomCategoriesCheckbox==0
	   && $maxOccupancyCheckbox==0 && $roomCodeCheckbox==0 && $roomIdCheckbox==1 && $roomDescriptionCheckbox==0)
	   {       	
       $queryString = $this->getDefaultEntityManager()->createQueryBuilder();  	
	   $queryString->select('m.id,m.code,m.name,m.description,m.bedType,m.maxOccupancy,m.categories,m.featured,m.status')
                    ->from('ContentFilter\Entity\PhoenixContentFilter', 'm')
					->where('m.name = :par1')  
                    ->andwhere('m.id = :par2')                   
                    ->setParameter('par1', $roomName)			    
					->setParameter('par2', $roomId);			
	   $result = $queryString->getQuery()->getResult();	
	   goto exitRoutrine;   
	   }
	  // ROOMNAME AND ROOMID ONLY STARTS
	  
	  // ROOMNAME AND ROOMDESCRIPTION ONLY STARTS 
	  if($hotelCheckbox==0 && $roomNameCheckbox==1 && $bedTypeCheckbox==0 && $roomCategoriesCheckbox==0
	   && $maxOccupancyCheckbox==0 && $roomCodeCheckbox==0 && $roomIdCheckbox==0 && $roomDescriptionCheckbox==1)
	   {       	
       $queryString = $this->getDefaultEntityManager()->createQueryBuilder();  	
	   $queryString->select('m.id,m.code,m.name,m.description,m.bedType,m.maxOccupancy,m.categories,m.featured,m.status')
                    ->from('ContentFilter\Entity\PhoenixContentFilter', 'm')
					->where('m.name = :par1') 				
                    ->andwhere('m.description like :par2')
                    ->setParameter('par1', $roomName)             				
					->setParameter('par2', '%'.$roomDescription.'%');                  		
	   $result = $queryString->getQuery()->getResult();	
	   goto exitRoutrine;   
	   }
	   // ROOMNAME AND ROOMDESCRIPTION ONLY STARTS 
	   
	   // ROOMID AND ROOMDESCRIPTION ONLY STARTS 
	  if($hotelCheckbox==0 && $roomNameCheckbox==0 && $bedTypeCheckbox==0 && $roomCategoriesCheckbox==0
	   && $maxOccupancyCheckbox==0 && $roomCodeCheckbox==0 && $roomIdCheckbox==1 && $roomDescriptionCheckbox==1)
	   {       	
       $queryString = $this->getDefaultEntityManager()->createQueryBuilder();  	
	   $queryString->select('m.id,m.code,m.name,m.description,m.bedType,m.maxOccupancy,m.categories,m.featured,m.status')
                    ->from('ContentFilter\Entity\PhoenixContentFilter', 'm')
					->where('m.id = :par1') 				
                    ->andwhere('m.description like :par2')
                    ->setParameter('par1', $roomId)             				
					->setParameter('par2', '%'.$roomDescription.'%');                  		
	   $result = $queryString->getQuery()->getResult();	
	   goto exitRoutrine;   
	   }
	   // ROOMID AND ROOMDESCRIPTION ONLY STARTS 
	   
	  // ROOMCODE AND ROOMDESCRIPTION ONLY STARTS 
	  if($hotelCheckbox==0 && $roomNameCheckbox==0 && $bedTypeCheckbox==0 && $roomCategoriesCheckbox==0
	   && $maxOccupancyCheckbox==0 && $roomCodeCheckbox==1 && $roomIdCheckbox==0 && $roomDescriptionCheckbox==1)
	   {       	
       $queryString = $this->getDefaultEntityManager()->createQueryBuilder();  	
	   $queryString->select('m.id,m.code,m.name,m.description,m.bedType,m.maxOccupancy,m.categories,m.featured,m.status')
                    ->from('ContentFilter\Entity\PhoenixContentFilter', 'm')
					->where('m.code = :par1') 				
                    ->andwhere('m.description like :par2')
                    ->setParameter('par1', $roomCode)             				
					->setParameter('par2', '%'.$roomDescription.'%');                  		
	   $result = $queryString->getQuery()->getResult();	
	   goto exitRoutrine;   
	   }
	   // ROOMCODE AND ROOMDESCRIPTION ONLY STARTS 
	   
	   // ROOMCODE AND ROOMID ONLY STARTS 
	   if($hotelCheckbox==0 && $roomNameCheckbox==0 && $bedTypeCheckbox==0 && $roomCategoriesCheckbox==0
	   && $maxOccupancyCheckbox==0 && $roomCodeCheckbox==1 && $roomIdCheckbox==1 && $roomDescriptionCheckbox==0)
	   {       	
       $queryString = $this->getDefaultEntityManager()->createQueryBuilder();  	
	   $queryString->select('m.id,m.code,m.name,m.description,m.bedType,m.maxOccupancy,m.categories,m.featured,m.status')
                    ->from('ContentFilter\Entity\PhoenixContentFilter', 'm')
					->where('m.code = :par1') 				
                    ->andwhere('m.id = :par2')
                    ->setParameter('par1', $roomCode)             				
					->setParameter('par2', $roomId);                  		
	   $result = $queryString->getQuery()->getResult();	
	   goto exitRoutrine;   
	   }
	   // ROOMCODE AND ROOMID ONLY STARTS 
	   
	   // MAXOCCUPANCY AND ROOMDESCRIPTION ONLY STARTS 
	   if($hotelCheckbox==0 && $roomNameCheckbox==0 && $bedTypeCheckbox==0 && $roomCategoriesCheckbox==0
	   && $maxOccupancyCheckbox==1 && $roomCodeCheckbox==0 && $roomIdCheckbox==0 && $roomDescriptionCheckbox==1)
	   {       	
       $queryString = $this->getDefaultEntityManager()->createQueryBuilder();  	
	   $queryString->select('m.id,m.code,m.name,m.description,m.bedType,m.maxOccupancy,m.categories,m.featured,m.status')
                    ->from('ContentFilter\Entity\PhoenixContentFilter', 'm')
					->where('m.maxOccupancy = :par1') 				
                    ->andwhere('m.description like :par2')
                    ->setParameter('par1', $maxOccupancy)             				
					->setParameter('par2', '%'.$roomDescription.'%');                  		
	   $result = $queryString->getQuery()->getResult();	
	   goto exitRoutrine;   
	   }
	   // MAXOCCUPANCY AND ROOMDESCRIPTION ONLY STARTS
	   
	  // MAXOCCUPANCY AND ROOMID ONLY STARTS 
	  if($hotelCheckbox==0 && $roomNameCheckbox==0 && $bedTypeCheckbox==0 && $roomCategoriesCheckbox==0
	   && $maxOccupancyCheckbox==1 && $roomCodeCheckbox==0 && $roomIdCheckbox==1 && $roomDescriptionCheckbox==0)
	   {       	
       $queryString = $this->getDefaultEntityManager()->createQueryBuilder();  	
	   $queryString->select('m.id,m.code,m.name,m.description,m.bedType,m.maxOccupancy,m.categories,m.featured,m.status')
                    ->from('ContentFilter\Entity\PhoenixContentFilter', 'm')
					->where('m.maxOccupancy = :par1') 				
                    ->andwhere('m.id = :par2')
                    ->setParameter('par1', $maxOccupancy)             				
					->setParameter('par2', $roomId);                  		
	   $result = $queryString->getQuery()->getResult();	
	   goto exitRoutrine;   
	   }
	   // MAXOCCUPANCY AND ROOMID ONLY STARTS
	   
	  // MAXOCCUPANCY AND ROOMCODE ONLY STARTS 
	  if($hotelCheckbox==0 && $roomNameCheckbox==0 && $bedTypeCheckbox==0 && $roomCategoriesCheckbox==0
	   && $maxOccupancyCheckbox==1 && $roomCodeCheckbox==1 && $roomIdCheckbox==0 && $roomDescriptionCheckbox==0)
	   {       	
       $queryString = $this->getDefaultEntityManager()->createQueryBuilder();  	
	   $queryString->select('m.id,m.code,m.name,m.description,m.bedType,m.maxOccupancy,m.categories,m.featured,m.status')
                    ->from('ContentFilter\Entity\PhoenixContentFilter', 'm')
					->where('m.maxOccupancy = :par1') 				
                    ->andwhere('m.code = :par2')
                    ->setParameter('par1', $maxOccupancy)             				
					->setParameter('par2', $roomCode);                  		
	   $result = $queryString->getQuery()->getResult();	
	   goto exitRoutrine;   
	   }
	   // MAXOCCUPANCY AND ROOMCODE ONLY STARTS
	   
	   // BEDTYPE AND ROOMCATEGORIES ONLY STARTS 
	   if($hotelCheckbox==0 && $roomNameCheckbox==0 && $bedTypeCheckbox==1 && $roomCategoriesCheckbox==1
	   && $maxOccupancyCheckbox==0 && $roomCodeCheckbox==0 && $roomIdCheckbox==0 && $roomDescriptionCheckbox==0)
	   {       	
       $queryString = $this->getDefaultEntityManager()->createQueryBuilder();  	
	   $queryString->select('m.id,m.code,m.name,m.description,m.bedType,m.maxOccupancy,m.categories,m.category,m.featured,m.status')
                    ->from('ContentFilter\Entity\PhoenixContentFilter', 'm')
					->where('m.bedType = :par1') 				
                    ->andwhere('m.category = :par2')
                    ->setParameter('par1', $bedType)             				
					->setParameter('par2', $roomCategories);                  		
	   $result = $queryString->getQuery()->getResult();	
	   goto exitRoutrine;   
	   }
	   // BEDTYPE AND ROOMCATEGORIES ONLY STARTS
	   
	   // BEDTYPE AND MAXOCCUPANCY ONLY STARTS 
	  if($hotelCheckbox==0 && $roomNameCheckbox==0 && $bedTypeCheckbox==1 && $roomCategoriesCheckbox==0
	   && $maxOccupancyCheckbox==1 && $roomCodeCheckbox==0 && $roomIdCheckbox==0 && $roomDescriptionCheckbox==0)
	   {       	
       $queryString = $this->getDefaultEntityManager()->createQueryBuilder();  	
	   $queryString->select('m.id,m.code,m.name,m.description,m.bedType,m.maxOccupancy,m.categories,m.featured,m.status')
                    ->from('ContentFilter\Entity\PhoenixContentFilter', 'm')
					->where('m.bedType = :par1') 				
                    ->andwhere('m.maxOccupancy = :par2')
                    ->setParameter('par1', $bedType)             				
					->setParameter('par2', $maxOccupancy);                  		
	   $result = $queryString->getQuery()->getResult();	
	   goto exitRoutrine;   
	   }
	   // BEDTYPE AND MAXOCCUPANCY ONLY STARTS
	   
	  // BEDTYPE AND ROOMCODE ONLY STARTS 
	  if($hotelCheckbox==0 && $roomNameCheckbox==0 && $bedTypeCheckbox==1 && $roomCategoriesCheckbox==0
	   && $maxOccupancyCheckbox==0 && $roomCodeCheckbox==1 && $roomIdCheckbox==0 && $roomDescriptionCheckbox==0)
	   {       	
       $queryString = $this->getDefaultEntityManager()->createQueryBuilder();  	
	   $queryString->select('m.id,m.code,m.name,m.description,m.bedType,m.maxOccupancy,m.categories,m.featured,m.status')
                    ->from('ContentFilter\Entity\PhoenixContentFilter', 'm')
					->where('m.bedType = :par1') 				
                    ->andwhere('m.code = :par2')
                    ->setParameter('par1', $bedType)             				
					->setParameter('par2', $roomCode);                  		
	   $result = $queryString->getQuery()->getResult();	
	   goto exitRoutrine;   
	   }
	   // BEDTYPE AND ROOMCODE ONLY STARTS
	   	      
	  // BEDTYPE AND ROOMID ONLY STARTS 
	  if($hotelCheckbox==0 && $roomNameCheckbox==0 && $bedTypeCheckbox==1 && $roomCategoriesCheckbox==0
	   && $maxOccupancyCheckbox==0 && $roomCodeCheckbox==0 && $roomIdCheckbox==1 && $roomDescriptionCheckbox==0)
	   {       	
       $queryString = $this->getDefaultEntityManager()->createQueryBuilder();  	
	   $queryString->select('m.id,m.code,m.name,m.description,m.bedType,m.maxOccupancy,m.categories,m.featured,m.status')
                    ->from('ContentFilter\Entity\PhoenixContentFilter', 'm')
					->where('m.bedType = :par1') 				
                    ->andwhere('m.id = :par2')
                    ->setParameter('par1', $bedType)             				
					->setParameter('par2', $roomId);                  		
	   $result = $queryString->getQuery()->getResult();	
	   goto exitRoutrine;   
	   }
	   // BEDTYPE AND ROOMID ONLY STARTS
	   
	  // BEDTYPE AND ROOMDESCRIPTION ONLY STARTS 
	  if($hotelCheckbox==0 && $roomNameCheckbox==0 && $bedTypeCheckbox==1 && $roomCategoriesCheckbox==0
	   && $maxOccupancyCheckbox==0 && $roomCodeCheckbox==0 && $roomIdCheckbox==0 && $roomDescriptionCheckbox==1)
	   {       	
       $queryString = $this->getDefaultEntityManager()->createQueryBuilder();  	
	   $queryString->select('m.id,m.code,m.name,m.description,m.bedType,m.maxOccupancy,m.categories,m.featured,m.status')
                    ->from('ContentFilter\Entity\PhoenixContentFilter', 'm')
					->where('m.bedType = :par1') 
                    ->andwhere('m.description like :par2')	          
                    ->setParameter('par1', $bedType)             				
				    ->setParameter('par2', '%'.$roomDescription.'%');                  		
	   $result = $queryString->getQuery()->getResult();	
	   goto exitRoutrine;   
	   }
	   // BEDTYPE AND ROOMDESCRIPTION ONLY STARTS
	   
	   // ROOMCATEGORIES AND ROOMDESCRIPTION ONLY STARTS 
	  if($hotelCheckbox==0 && $roomNameCheckbox==0 && $bedTypeCheckbox==0 && $roomCategoriesCheckbox==1
	   && $maxOccupancyCheckbox==0 && $roomCodeCheckbox==0 && $roomIdCheckbox==0 && $roomDescriptionCheckbox==1)
	   {       	
       $queryString = $this->getDefaultEntityManager()->createQueryBuilder();  	
	   $queryString->select('m.id,m.code,m.name,m.description,m.bedType,m.maxOccupancy,m.categories,m.category,m.featured,m.status')
                    ->from('ContentFilter\Entity\PhoenixContentFilter', 'm')
					->where('m.category = :par1') 
                    ->andwhere('m.description like :par2')	          
                    ->setParameter('par1', $roomCategories)             				
				    ->setParameter('par2', '%'.$roomDescription.'%');                  		
	   $result = $queryString->getQuery()->getResult();	
	   goto exitRoutrine;   
	   }
	   // ROOMCATEGORIES AND ROOMDESCRIPTION ONLY STARTS
	  
	  // ROOMCATEGORIES AND ROOMCODE ONLY STARTS 
	  if($hotelCheckbox==0 && $roomNameCheckbox==0 && $bedTypeCheckbox==0 && $roomCategoriesCheckbox==1
	   && $maxOccupancyCheckbox==0 && $roomCodeCheckbox==1 && $roomIdCheckbox==0 && $roomDescriptionCheckbox==0)
	   {       	
       $queryString = $this->getDefaultEntityManager()->createQueryBuilder();  	
	   $queryString->select('m.id,m.code,m.name,m.description,m.bedType,m.maxOccupancy,m.categories,m.category,m.featured,m.status')
                    ->from('ContentFilter\Entity\PhoenixContentFilter', 'm')
					->where('m.category = :par1') 				
                    ->andwhere('m.code = :par2')
                    ->setParameter('par1', $roomCategories)             				
					->setParameter('par2', $roomCode);                  		
	   $result = $queryString->getQuery()->getResult();	
	   goto exitRoutrine;   
	   }
	   // ROOMCATEGORIES AND ROOMCODE ONLY STARTS
	   	      
	  // ROOMCATEGORIES AND ROOMID ONLY STARTS 
	  if($hotelCheckbox==0 && $roomNameCheckbox==0 && $bedTypeCheckbox==0 && $roomCategoriesCheckbox==1
	   && $maxOccupancyCheckbox==0 && $roomCodeCheckbox==0 && $roomIdCheckbox==1 && $roomDescriptionCheckbox==0)
	   {       	
       $queryString = $this->getDefaultEntityManager()->createQueryBuilder();  	
	   $queryString->select('m.id,m.code,m.name,m.description,m.bedType,m.maxOccupancy,m.categories,m.category,m.featured,m.status')
                    ->from('ContentFilter\Entity\PhoenixContentFilter', 'm')
					->where('m.category = :par1') 				
                    ->andwhere('m.id = :par2')
                    ->setParameter('par1', $roomCategories)             				
					->setParameter('par2', $roomId);                  		
	   $result = $queryString->getQuery()->getResult();	
	   goto exitRoutrine;   
	   }
	   // ROOMCATEGORIES AND ROOMID ONLY STARTS
	   
	  // ROOMCATEGORIES AND MAXOCCUPANCY ONLY STARTS 
	  if($hotelCheckbox==0 && $roomNameCheckbox==0 && $bedTypeCheckbox==0 && $roomCategoriesCheckbox==1
	   && $maxOccupancyCheckbox==1 && $roomCodeCheckbox==0 && $roomIdCheckbox==0 && $roomDescriptionCheckbox==0)
	   {       	
       $queryString = $this->getDefaultEntityManager()->createQueryBuilder();  	
	   $queryString->select('m.id,m.code,m.name,m.description,m.bedType,m.maxOccupancy,m.categories,m.category,m.featured,m.status')
                    ->from('ContentFilter\Entity\PhoenixContentFilter', 'm')
					->where('m.category = :par1') 				
                    ->andwhere('m.maxOccupancy = :par2')
                    ->setParameter('par1', $roomCategories)             				
					->setParameter('par2', $maxOccupancy);                  		
	   $result = $queryString->getQuery()->getResult();	
	   goto exitRoutrine;   
	   }
	   // ROOMCATEGORIES AND MAXOCCUPANCY ONLY STARTS	   	   	  
     // LEVEL 2 ENDS   	  

	 // LEVEL 3 STARTS	 
		// PROPERTYNAME, ROOMNAME AND BEDTYPE ONLY STARTS 
	        if($hotelCheckbox==1 && $roomNameCheckbox==1 && $bedTypeCheckbox==1 && $roomCategoriesCheckbox==0
			    && $maxOccupancyCheckbox==0 && $roomCodeCheckbox==0 && $roomIdCheckbox==0 && $roomDescriptionCheckbox==0)
			    {      	   
			    $queryString = $this->getDefaultEntityManager()->createQueryBuilder();  	
			    $queryString->select('m.id,m.code,m.name,m.description,m.bedType,m.maxOccupancy,m.category,m.featured,m.status')
							->from('ContentFilter\Entity\PhoenixContentFilter', 'm')
							->where('m.property = :par1') 
							->andwhere('m.name = :par2')
							->andwhere('m.bedType = :par3')							
							->setParameter('par1', $hotel)
							->setParameter('par2', $roomName)
							->setParameter('par3', $bedType);								
			    $result = $queryString->getQuery()->getResult();	
			    goto exitRoutrine;   
			    } 			
	    // PROPERTYNAME, ROOMNAME AND BEDTYPE ONLY ENDS
		
		// PROPERTYNAME, ROOMNAME AND ROOMCATEGORIES ONLY STARTS 
	        if($hotelCheckbox==1 && $roomNameCheckbox==1 && $bedTypeCheckbox==0 && $roomCategoriesCheckbox==1
			    && $maxOccupancyCheckbox==0 && $roomCodeCheckbox==0 && $roomIdCheckbox==0 && $roomDescriptionCheckbox==0)
			    {      	   
			    $queryString = $this->getDefaultEntityManager()->createQueryBuilder();  	
			    $queryString->select('m.id,m.code,m.name,m.description,m.bedType,m.maxOccupancy,m.category,m.featured,m.status')
							->from('ContentFilter\Entity\PhoenixContentFilter', 'm')
							->where('m.property = :par1') 
							->andwhere('m.name = :par2')							
							->andwhere('m.category = :par4')							
							->setParameter('par1', $hotel)
							->setParameter('par2', $roomName)							
							->setParameter('par4', $roomCategories);							
			    $result = $queryString->getQuery()->getResult();	
			    goto exitRoutrine;   
			    } 			
	    // PROPERTYNAME, ROOMNAME AND ROOMCATEGORIES ONLY ENDS
	    
		// PROPERTYNAME, ROOMNAME AND MAXOCCUPANCY ONLY STARTS 
		if($hotelCheckbox==1 && $roomNameCheckbox==1 && $bedTypeCheckbox==0 && $roomCategoriesCheckbox==0
			   && $maxOccupancyCheckbox==1 && $roomCodeCheckbox==0 && $roomIdCheckbox==0 && $roomDescriptionCheckbox==0)
			   {      	   
			   $queryString = $this->getDefaultEntityManager()->createQueryBuilder();  	
			   $queryString->select('m.id,m.code,m.name,m.description,m.bedType,m.maxOccupancy,m.category,m.featured,m.status')
							->from('ContentFilter\Entity\PhoenixContentFilter', 'm')
							->where('m.property = :par1') 
							->andwhere('m.name = :par2')						
							->andwhere('m.maxOccupancy = :par5')						
							->setParameter('par1', $hotel)
							->setParameter('par2', $roomName)							
							->setParameter('par5', $maxOccupancy);									
			   $result = $queryString->getQuery()->getResult();	
			   goto exitRoutrine;   
			  } 
		// PROPERTYNAME, ROOMNAME AND MAXOCCUPANCY ENDS 
		
		// PROPERTYNAME, ROOMNAME AND ROOMCODE ONLY STARTS 
		if($hotelCheckbox==1 && $roomNameCheckbox==1 && $bedTypeCheckbox==0 && $roomCategoriesCheckbox==0
			   && $maxOccupancyCheckbox==0 && $roomCodeCheckbox==1 && $roomIdCheckbox==0 && $roomDescriptionCheckbox==0)
			   {      	   
			   $queryString = $this->getDefaultEntityManager()->createQueryBuilder();  	
			   $queryString->select('m.id,m.code,m.name,m.description,m.bedType,m.maxOccupancy,m.category,m.featured,m.status')
							->from('ContentFilter\Entity\PhoenixContentFilter', 'm')
							->where('m.property = :par1') 
							->andwhere('m.name = :par2')						
							->andwhere('m.code = :par6')						
							->setParameter('par1', $hotel)
							->setParameter('par2', $roomName)							
							->setParameter('par6', $roomCode);									
			   $result = $queryString->getQuery()->getResult();	
			   goto exitRoutrine;   
			  } 
		// PROPERTYNAME, ROOMNAME AND ROOMCODE ENDS 
		
		// PROPERTYNAME, ROOMNAME AND ROOMID ONLY STARTS 
		if($hotelCheckbox==1 && $roomNameCheckbox==1 && $bedTypeCheckbox==0 && $roomCategoriesCheckbox==0
			   && $maxOccupancyCheckbox==0 && $roomCodeCheckbox==0 && $roomIdCheckbox==1 && $roomDescriptionCheckbox==0)
			   {      	   
			   $queryString = $this->getDefaultEntityManager()->createQueryBuilder();  	
			   $queryString->select('m.id,m.code,m.name,m.description,m.bedType,m.maxOccupancy,m.category,m.featured,m.status')
							->from('ContentFilter\Entity\PhoenixContentFilter', 'm')
							->where('m.property = :par1') 
							->andwhere('m.name = :par2')							
							->andwhere('m.id = :par7')							
							->setParameter('par1', $hotel)
							->setParameter('par2', $roomName)						
							->setParameter('par7', $roomId);								
			   $result = $queryString->getQuery()->getResult();	
			   goto exitRoutrine;   
			  } 
		// PROPERTYNAME, ROOMNAME AND ROOMID ENDS 
		
		// PROPERTYNAME, ROOMNAME AND KEYWORDS ONLY STARTS 
		if($hotelCheckbox==1 && $roomNameCheckbox==1 && $bedTypeCheckbox==0 && $roomCategoriesCheckbox==0
			   && $maxOccupancyCheckbox==0 && $roomCodeCheckbox==0 && $roomIdCheckbox==0 && $roomDescriptionCheckbox==1)
			   {      	   
			   $queryString = $this->getDefaultEntityManager()->createQueryBuilder();  	
			   $queryString->select('m.id,m.code,m.name,m.description,m.bedType,m.maxOccupancy,m.category,m.featured,m.status')
							->from('ContentFilter\Entity\PhoenixContentFilter', 'm')
							->where('m.property = :par1') 
							->andwhere('m.name = :par2')					
							->andwhere('m.description like :par8')
							->setParameter('par1', $hotel)
							->setParameter('par2', $roomName)											
							->setParameter('par8', '%'.$roomDescription.'%');			
			   $result = $queryString->getQuery()->getResult();	
			   goto exitRoutrine;   
			  } 
		// PROPERTYNAME, ROOMNAME AND KEYWORDS ENDS 
		
		// PROPERTYNAME, ROOMNAME AND KEYWORDS ONLY STARTS 
		if($hotelCheckbox==1 && $roomNameCheckbox==1 && $bedTypeCheckbox==0 && $roomCategoriesCheckbox==0
			   && $maxOccupancyCheckbox==0 && $roomCodeCheckbox==0 && $roomIdCheckbox==0 && $roomDescriptionCheckbox==1)
			   {      	   
			   $queryString = $this->getDefaultEntityManager()->createQueryBuilder();  	
			   $queryString->select('m.id,m.code,m.name,m.description,m.bedType,m.maxOccupancy,m.category,m.featured,m.status')
							->from('ContentFilter\Entity\PhoenixContentFilter', 'm')
							->where('m.property = :par1') 
							->andwhere('m.name = :par2')							
							->andwhere('m.description like :par8')
							->setParameter('par1', $hotel)
							->setParameter('par2', $roomName)												
							->setParameter('par8', '%'.$roomDescription.'%');			
			   $result = $queryString->getQuery()->getResult();	
			   goto exitRoutrine;   
			  } 
		// PROPERTYNAME, ROOMNAME AND KEYWORDS ENDS 
		
		// ROOMNAME, BEDTYPE AND ROOMCATEGORIES ONLY STARTS 
		if($hotelCheckbox==0 && $roomNameCheckbox==1 && $bedTypeCheckbox==1 && $roomCategoriesCheckbox==1
			   && $maxOccupancyCheckbox==0 && $roomCodeCheckbox==0 && $roomIdCheckbox==0 && $roomDescriptionCheckbox==0)
			   {      	   
			   $queryString = $this->getDefaultEntityManager()->createQueryBuilder();  	
			   $queryString->select('m.id,m.code,m.name,m.description,m.bedType,m.maxOccupancy,m.category,m.featured,m.status')
							->from('ContentFilter\Entity\PhoenixContentFilter', 'm')							
							->where('m.name = :par2')
							->andwhere('m.bedType = :par3')
							->andwhere('m.category = :par4')							
							->setParameter('par2', $roomName)
							->setParameter('par3', $bedType)
							->setParameter('par4', $roomCategories);								
			   $result = $queryString->getQuery()->getResult();	
			   goto exitRoutrine;   
			  } 
		// ROOMNAME, BEDTYPE AND ROOMCATEGORIES ONLY ENDS 
		
		// ROOMNAME, BEDTYPE AND MAXOCCUPANCY ONLY STARTS 
		if($hotelCheckbox==0 && $roomNameCheckbox==1 && $bedTypeCheckbox==1 && $roomCategoriesCheckbox==0
			   && $maxOccupancyCheckbox==1 && $roomCodeCheckbox==0 && $roomIdCheckbox==0 && $roomDescriptionCheckbox==0)
			   {      	   
			   $queryString = $this->getDefaultEntityManager()->createQueryBuilder();  	
			   $queryString->select('m.id,m.code,m.name,m.description,m.bedType,m.maxOccupancy,m.category,m.featured,m.status')
							->from('ContentFilter\Entity\PhoenixContentFilter', 'm')							
							->where('m.name = :par2')
							->andwhere('m.bedType = :par3')							
							->andwhere('m.maxOccupancy = :par5')						
							->setParameter('par2', $roomName)
							->setParameter('par3', $bedType)						
							->setParameter('par5', $maxOccupancy);								
			   $result = $queryString->getQuery()->getResult();	
			   goto exitRoutrine;   
			  } 
		// ROOMNAME, BEDTYPE AND MAXOCCUPANCY ONLY ENDS 
		
		// ROOMNAME, BEDTYPE AND ROOMCODE ONLY STARTS 
		if($hotelCheckbox==0 && $roomNameCheckbox==1 && $bedTypeCheckbox==1 && $roomCategoriesCheckbox==0
			   && $maxOccupancyCheckbox==0 && $roomCodeCheckbox==1 && $roomIdCheckbox==0 && $roomDescriptionCheckbox==0)
			   {      	   
			   $queryString = $this->getDefaultEntityManager()->createQueryBuilder();  	
			   $queryString->select('m.id,m.code,m.name,m.description,m.bedType,m.maxOccupancy,m.category,m.featured,m.status')
							->from('ContentFilter\Entity\PhoenixContentFilter', 'm')						
							->where('m.name = :par2')
							->andwhere('m.bedType = :par3')							
							->andwhere('m.code = :par6')							
							->setParameter('par2', $roomName)
							->setParameter('par3', $bedType)							
							->setParameter('par6', $roomCode);										
			   $result = $queryString->getQuery()->getResult();	
			   goto exitRoutrine;   
			  } 
		// ROOMNAME, BEDTYPE AND ROOMCODE ONLY ENDS 
				
		// ROOMNAME, BEDTYPE AND ROOMID ONLY STARTS 
		if($hotelCheckbox==0 && $roomNameCheckbox==1 && $bedTypeCheckbox==1 && $roomCategoriesCheckbox==0
			   && $maxOccupancyCheckbox==0 && $roomCodeCheckbox==0 && $roomIdCheckbox==1 && $roomDescriptionCheckbox==0)
			   {      	   
			   $queryString = $this->getDefaultEntityManager()->createQueryBuilder();  	
			   $queryString->select('m.id,m.code,m.name,m.description,m.bedType,m.maxOccupancy,m.category,m.featured,m.status')
							->from('ContentFilter\Entity\PhoenixContentFilter', 'm')							
							->where('m.name = :par2')
							->andwhere('m.bedType = :par3')							
							->andwhere('m.id = :par7')						
							->setParameter('par2', $roomName)
							->setParameter('par3', $bedType)					
							->setParameter('par7', $roomId);											
			   $result = $queryString->getQuery()->getResult();	
			   goto exitRoutrine;   
			  } 
		// ROOMNAME, BEDTYPE AND ROOMID ONLY ENDS 
		
		// ROOMNAME, BEDTYPE AND KEYWORDS ONLY STARTS 
		if($hotelCheckbox==0 && $roomNameCheckbox==1 && $bedTypeCheckbox==1 && $roomCategoriesCheckbox==0
			   && $maxOccupancyCheckbox==0 && $roomCodeCheckbox==0 && $roomIdCheckbox==0 && $roomDescriptionCheckbox==1)
			   {      	   
			   $queryString = $this->getDefaultEntityManager()->createQueryBuilder();  	
			   $queryString->select('m.id,m.code,m.name,m.description,m.bedType,m.maxOccupancy,m.category,m.featured,m.status')
							->from('ContentFilter\Entity\PhoenixContentFilter', 'm')							
							->where('m.name = :par2')
							->andwhere('m.bedType = :par3')							
							->andwhere('m.description like :par8')							
							->setParameter('par2', $roomName)
							->setParameter('par3', $bedType)											
							->setParameter('par8', '%'.$roomDescription.'%');			
			   $result = $queryString->getQuery()->getResult();	
			   goto exitRoutrine;   
			  } 
		// ROOMNAME, BEDTYPE AND KEYWORDS ONLY ENDS 
		
		// BEDTYPE, ROOM CATEGORIES AND MAX OCCUPANCY ONLY STARTS 
		if($hotelCheckbox==0 && $roomNameCheckbox==0 && $bedTypeCheckbox==1 && $roomCategoriesCheckbox==1
			   && $maxOccupancyCheckbox==1 && $roomCodeCheckbox==0 && $roomIdCheckbox==0 && $roomDescriptionCheckbox==0)
			   {      	   
			   $queryString = $this->getDefaultEntityManager()->createQueryBuilder();  	
			   $queryString->select('m.id,m.code,m.name,m.description,m.bedType,m.maxOccupancy,m.category,m.featured,m.status')
							->from('ContentFilter\Entity\PhoenixContentFilter', 'm')
							->where('m.bedType = :par3')
							->andwhere('m.category = :par4')
							->andwhere('m.maxOccupancy = :par5')					
							->setParameter('par3', $bedType)
							->setParameter('par4', $roomCategories)
							->setParameter('par5', $maxOccupancy);									
			   $result = $queryString->getQuery()->getResult();	
			   goto exitRoutrine;   
			  } 
		// BEDTYPE, ROOM CATEGORIES AND MAX OCCUPANCY ONLY ENDS 
		
		// BEDTYPE, ROOM CATEGORIES AND ROOM CODE ONLY STARTS 
		if($hotelCheckbox==0 && $roomNameCheckbox==0 && $bedTypeCheckbox==1 && $roomCategoriesCheckbox==1
			   && $maxOccupancyCheckbox==0 && $roomCodeCheckbox==1 && $roomIdCheckbox==0 && $roomDescriptionCheckbox==0)
			   {      	   
			   $queryString = $this->getDefaultEntityManager()->createQueryBuilder();  	
			   $queryString->select('m.id,m.code,m.name,m.description,m.bedType,m.maxOccupancy,m.category,m.featured,m.status')
							->from('ContentFilter\Entity\PhoenixContentFilter', 'm')		                    
							->where('m.bedType = :par3')
							->andwhere('m.category = :par4')							
							->andwhere('m.code = :par6')							
							->setParameter('par3', $bedType)
							->setParameter('par4', $roomCategories)				
							->setParameter('par6', $roomCode);									   
			   $result = $queryString->getQuery()->getResult();	
			   goto exitRoutrine;   
			  } 
		// BEDTYPE, ROOM CATEGORIES AND ROOM CODE ONLY ENDS 
		
		// BEDTYPE, ROOM CATEGORIES AND ROOMID ONLY STARTS 
		if($hotelCheckbox==0 && $roomNameCheckbox==0 && $bedTypeCheckbox==1 && $roomCategoriesCheckbox==1
			   && $maxOccupancyCheckbox==0 && $roomCodeCheckbox==0 && $roomIdCheckbox==1 && $roomDescriptionCheckbox==0)
			   {      	   
			   $queryString = $this->getDefaultEntityManager()->createQueryBuilder();  	
			   $queryString->select('m.id,m.code,m.name,m.description,m.bedType,m.maxOccupancy,m.category,m.featured,m.status')
							->from('ContentFilter\Entity\PhoenixContentFilter', 'm')		                  
							->where('m.bedType = :par3')
							->andwhere('m.category = :par4')							
							->andwhere('m.id = :par7')							
							->setParameter('par3', $bedType)
							->setParameter('par4', $roomCategories)					
							->setParameter('par7', $roomId);							   
			   $result = $queryString->getQuery()->getResult();	
			   goto exitRoutrine;   
			  } 
		// BEDTYPE, ROOM CATEGORIES AND ROOMID ONLY ENDS 
		
		// BEDTYPE, ROOM CATEGORIES AND KEYWORDS ONLY STARTS 
		if($hotelCheckbox==0 && $roomNameCheckbox==0 && $bedTypeCheckbox==1 && $roomCategoriesCheckbox==1
			   && $maxOccupancyCheckbox==0 && $roomCodeCheckbox==0 && $roomIdCheckbox==0 && $roomDescriptionCheckbox==1)
			   {      	   
			   $queryString = $this->getDefaultEntityManager()->createQueryBuilder();  	
			   $queryString->select('m.id,m.code,m.name,m.description,m.bedType,m.maxOccupancy,m.category,m.featured,m.status')
							->from('ContentFilter\Entity\PhoenixContentFilter', 'm')		                    
							->where('m.bedType = :par3')
							->andwhere('m.category = :par4')							
							->andwhere('m.description like :par8')							
							->setParameter('par3', $bedType)
							->setParameter('par4', $roomCategories)												
							->setParameter('par8', '%'.$roomDescription.'%');			   
			   $result = $queryString->getQuery()->getResult();	
			   goto exitRoutrine;   
			  } 
		// BEDTYPE, ROOM CATEGORIES AND KEYWORDS ONLY ENDS 
		
		// ROOM CATEGORIES, MAX OCCUPANCY AND ROOM CODE ONLY STARTS 
		if($hotelCheckbox==0 && $roomNameCheckbox==0 && $bedTypeCheckbox==0 && $roomCategoriesCheckbox==1
			   && $maxOccupancyCheckbox==1 && $roomCodeCheckbox==1 && $roomIdCheckbox==0 && $roomDescriptionCheckbox==0)
			   {      	   
			   $queryString = $this->getDefaultEntityManager()->createQueryBuilder();  	
			   $queryString->select('m.id,m.code,m.name,m.description,m.bedType,m.maxOccupancy,m.category,m.featured,m.status')
							->from('ContentFilter\Entity\PhoenixContentFilter', 'm')		                   
							->where('m.category = :par4')
							->andwhere('m.maxOccupancy = :par5')
							->andwhere('m.code = :par6')						
							->setParameter('par4', $roomCategories)
							->setParameter('par5', $maxOccupancy)
							->setParameter('par6', $roomCode);										   
			   $result = $queryString->getQuery()->getResult();	
			   goto exitRoutrine;   
			  } 
		// ROOM CATEGORIES, MAX OCCUPANCY AND ROOM CODE ONLY ENDS 
		
		// ROOM CATEGORIES, MAX OCCUPANCY AND ROOM ID ONLY STARTS 
		if($hotelCheckbox==0 && $roomNameCheckbox==0 && $bedTypeCheckbox==0 && $roomCategoriesCheckbox==1
			   && $maxOccupancyCheckbox==1 && $roomCodeCheckbox==0 && $roomIdCheckbox==1 && $roomDescriptionCheckbox==0)
			   {      	   
			   $queryString = $this->getDefaultEntityManager()->createQueryBuilder();  	
			   $queryString->select('m.id,m.code,m.name,m.description,m.bedType,m.maxOccupancy,m.category,m.featured,m.status')
							->from('ContentFilter\Entity\PhoenixContentFilter', 'm')		                   
							->where('m.category = :par4')
							->andwhere('m.maxOccupancy = :par5')							
							->andwhere('m.id = :par7')							
							->setParameter('par4', $roomCategories)
							->setParameter('par5', $maxOccupancy)					
							->setParameter('par7', $roomId);								   
			   $result = $queryString->getQuery()->getResult();	
			   goto exitRoutrine;   
			  } 
		// ROOM CATEGORIES, MAX OCCUPANCY AND ROOM ID ONLY ENDS 
		
		// ROOM CATEGORIES, MAX OCCUPANCY AND KEYWORDS ONLY STARTS 
		if($hotelCheckbox==0 && $roomNameCheckbox==0 && $bedTypeCheckbox==0 && $roomCategoriesCheckbox==1
			   && $maxOccupancyCheckbox==1 && $roomCodeCheckbox==0 && $roomIdCheckbox==0 && $roomDescriptionCheckbox==1)
			   {      	   
			   $queryString = $this->getDefaultEntityManager()->createQueryBuilder();  	
			   $queryString->select('m.id,m.code,m.name,m.description,m.bedType,m.maxOccupancy,m.category,m.featured,m.status')
							->from('ContentFilter\Entity\PhoenixContentFilter', 'm')		                   
							->where('m.category = :par4')
							->andwhere('m.maxOccupancy = :par5')						
							->andwhere('m.description like :par8')							
							->setParameter('par4', $roomCategories)
							->setParameter('par5', $maxOccupancy)											
							->setParameter('par8', '%'.$roomDescription.'%');			   
			   $result = $queryString->getQuery()->getResult();	
			   goto exitRoutrine;   
			  } 
		// ROOM CATEGORIES, MAX OCCUPANCY AND KEYWORDS ONLY ENDS 
		
		// ROOM CODE, ROOM ID AND KEYWORDS ONLY STARTS 
		if($hotelCheckbox==0 && $roomNameCheckbox==0 && $bedTypeCheckbox==0 && $roomCategoriesCheckbox==0
			   && $maxOccupancyCheckbox==0 && $roomCodeCheckbox==1 && $roomIdCheckbox==1 && $roomDescriptionCheckbox==1)
			   {      	   
			   $queryString = $this->getDefaultEntityManager()->createQueryBuilder();  	
			   $queryString->select('m.id,m.code,m.name,m.description,m.bedType,m.maxOccupancy,m.category,m.featured,m.status')
							->from('ContentFilter\Entity\PhoenixContentFilter', 'm')		                  
							->where('m.code = :par6')
							->andwhere('m.id = :par7')
							->andwhere('m.description like :par8')						
							->setParameter('par6', $roomCode)
							->setParameter('par7', $roomId)					
							->setParameter('par8', '%'.$roomDescription.'%');			   
			   $result = $queryString->getQuery()->getResult();	
			   goto exitRoutrine;   
			  } 
		// ROOM CODE, ROOM ID AND KEYWORDS ONLY ENDS 
		
		// MAXOCCUPANCY, ROOM CODE AND KEYWORDS ONLY STARTS 
		if($hotelCheckbox==0 && $roomNameCheckbox==0 && $bedTypeCheckbox==0 && $roomCategoriesCheckbox==0
			   && $maxOccupancyCheckbox==1 && $roomCodeCheckbox==1 && $roomIdCheckbox==0 && $roomDescriptionCheckbox==1)
			   {      	   
			   $queryString = $this->getDefaultEntityManager()->createQueryBuilder();  	
			   $queryString->select('m.id,m.code,m.name,m.description,m.bedType,m.maxOccupancy,m.category,m.featured,m.status')
							->from('ContentFilter\Entity\PhoenixContentFilter', 'm')		                    
							->where('m.maxOccupancy = :par5')
							->andwhere('m.code = :par6')							
							->andwhere('m.description like :par8')							
							->setParameter('par5', $maxOccupancy)
							->setParameter('par6', $roomCode)												
							->setParameter('par8', '%'.$roomDescription.'%');			   
			   $result = $queryString->getQuery()->getResult();	
			   goto exitRoutrine;   
			  } 
		//  MAXOCCUPANCY, ROOM CODE AND KEYWORDS ONLY ENDS 
		
		// MAXOCCUPANCY, ROOM CODE AND ROOMID ONLY STARTS 
		if($hotelCheckbox==0 && $roomNameCheckbox==0 && $bedTypeCheckbox==0 && $roomCategoriesCheckbox==0
			   && $maxOccupancyCheckbox==1 && $roomCodeCheckbox==1 && $roomIdCheckbox==1 && $roomDescriptionCheckbox==0)
			   {      	   
			   $queryString = $this->getDefaultEntityManager()->createQueryBuilder();  	
			   $queryString->select('m.id,m.code,m.name,m.description,m.bedType,m.maxOccupancy,m.category,m.featured,m.status')
							->from('ContentFilter\Entity\PhoenixContentFilter', 'm')		                    
							->where('m.maxOccupancy = :par5')
							->andwhere('m.code = :par6')
							->andwhere('m.id = :par7')										
							->setParameter('par5', $maxOccupancy)
							->setParameter('par6', $roomCode)
							->setParameter('par7', $roomId);									   
			   $result = $queryString->getQuery()->getResult();	
			   goto exitRoutrine;   
			  } 
		//  MAXOCCUPANCY, ROOM CODE AND ROOMID ONLY ENDS 
		
		// HOTELNAME, BEDTYPE, MAXOCCUPANCY ONLY STARTS
			   if($hotelCheckbox==1 && $roomNameCheckbox==0 && $bedTypeCheckbox==1 && $roomCategoriesCheckbox==0
			   && $maxOccupancyCheckbox==1 && $roomCodeCheckbox==0 && $roomIdCheckbox==0 && $roomDescriptionCheckbox==0)
			   {      	   
			   $queryString = $this->getDefaultEntityManager()->createQueryBuilder();  	
			   $queryString->select('m.id,m.code,m.name,m.description,m.bedType,m.maxOccupancy,m.category,m.featured,m.status')
							->from('ContentFilter\Entity\PhoenixContentFilter', 'm')
							->where('m.property = :par1') 							
							->andwhere('m.bedType = :par3')							
							->andwhere('m.maxOccupancy = :par5')							
							->setParameter('par1', $hotel)							
							->setParameter('par3', $bedType)							
							->setParameter('par5', $maxOccupancy);									
			   $result = $queryString->getQuery()->getResult();	
			   goto exitRoutrine;   
			  } 
	    // HOTELNAME, BEDTYPE, MAXOCCUPANCY ONLY ENDS
		
		// HOTELNAME, BEDTYPE, ROOMID ONLY STARTS
			   if($hotelCheckbox==1 && $roomNameCheckbox==0 && $bedTypeCheckbox==1 && $roomCategoriesCheckbox==0
			   && $maxOccupancyCheckbox==0 && $roomCodeCheckbox==0 && $roomIdCheckbox==1 && $roomDescriptionCheckbox==0)
			   {      	   
			   $queryString = $this->getDefaultEntityManager()->createQueryBuilder();  	
			   $queryString->select('m.id,m.code,m.name,m.description,m.bedType,m.maxOccupancy,m.category,m.featured,m.status')
							->from('ContentFilter\Entity\PhoenixContentFilter', 'm')
							->where('m.property = :par1') 							
							->andwhere('m.bedType = :par3')							
							->andwhere('m.id = :par7')							
							->setParameter('par1', $hotel)							
							->setParameter('par3', $bedType)
                            ->setParameter('par7', $roomId);															
			   $result = $queryString->getQuery()->getResult();	
			   goto exitRoutrine;   
			  } 
	    // HOTELNAME, BEDTYPE, ROOMID ONLY ENDS
		
		// HOTELNAME, BEDTYPE, ROOMCODE ONLY STARTS
			   if($hotelCheckbox==1 && $roomNameCheckbox==0 && $bedTypeCheckbox==1 && $roomCategoriesCheckbox==0
			   && $maxOccupancyCheckbox==0 && $roomCodeCheckbox==1 && $roomIdCheckbox==0 && $roomDescriptionCheckbox==0)
			   {      	   
			   $queryString = $this->getDefaultEntityManager()->createQueryBuilder();  	
			   $queryString->select('m.id,m.code,m.name,m.description,m.bedType,m.maxOccupancy,m.category,m.featured,m.status')
							->from('ContentFilter\Entity\PhoenixContentFilter', 'm')
							->where('m.property = :par1') 							
							->andwhere('m.bedType = :par3')	
                            ->andwhere('m.code = :par6')												
							->setParameter('par1', $hotel)							
							->setParameter('par3', $bedType)
							->setParameter('par6', $roomCode);                            															
			   $result = $queryString->getQuery()->getResult();	
			   goto exitRoutrine;   
			  } 
	    // HOTELNAME, BEDTYPE, ROOMCODE ONLY ENDS
		
		// HOTELNAME, BEDTYPE, KEYWORDS ONLY STARTS
			   if($hotelCheckbox==1 && $roomNameCheckbox==0 && $bedTypeCheckbox==1 && $roomCategoriesCheckbox==0
			   && $maxOccupancyCheckbox==0 && $roomCodeCheckbox==0 && $roomIdCheckbox==0 && $roomDescriptionCheckbox==1)
			   {      	   
			   $queryString = $this->getDefaultEntityManager()->createQueryBuilder();  	
			   $queryString->select('m.id,m.code,m.name,m.description,m.bedType,m.maxOccupancy,m.category,m.featured,m.status')
							->from('ContentFilter\Entity\PhoenixContentFilter', 'm')
							->where('m.property = :par1') 							
							->andwhere('m.bedType = :par3')	
                            ->andwhere('m.description like :par8')												
							->setParameter('par1', $hotel)							
							->setParameter('par3', $bedType)
							->setParameter('par8', '%'.$roomDescription.'%');							                          															
			   $result = $queryString->getQuery()->getResult();	
			   goto exitRoutrine;   
			  } 
	    // HOTELNAME, BEDTYPE, KEYWORDS ONLY ENDS
		
		// HOTELNAME, BEDTYPE, CATEGORIES ONLY STARTS
			   if($hotelCheckbox==1 && $roomNameCheckbox==0 && $bedTypeCheckbox==1 && $roomCategoriesCheckbox==1
			   && $maxOccupancyCheckbox==0 && $roomCodeCheckbox==0 && $roomIdCheckbox==0 && $roomDescriptionCheckbox==0)
			   {      	   
			   $queryString = $this->getDefaultEntityManager()->createQueryBuilder();  	
			   $queryString->select('m.id,m.code,m.name,m.description,m.bedType,m.maxOccupancy,m.category,m.featured,m.status')
							->from('ContentFilter\Entity\PhoenixContentFilter', 'm')
							->where('m.property = :par1') 							
							->andwhere('m.bedType = :par3')	
                            ->andwhere('m.category = :par4')											
							->setParameter('par1', $hotel)							
							->setParameter('par3', $bedType)
							->setParameter('par4', $roomCategories);														                          															
			   $result = $queryString->getQuery()->getResult();	
			   goto exitRoutrine;   
			  } 
	    // HOTELNAME, BEDTYPE, CATEGORIES ONLY ENDS
		
		// HOTELNAME, BEDTYPE, ROOMNAME ONLY STARTS
			   if($hotelCheckbox==1 && $roomNameCheckbox==1 && $bedTypeCheckbox==1 && $roomCategoriesCheckbox==0
			   && $maxOccupancyCheckbox==0 && $roomCodeCheckbox==0 && $roomIdCheckbox==0 && $roomDescriptionCheckbox==0)
			   {      	   
			   $queryString = $this->getDefaultEntityManager()->createQueryBuilder();  	
			   $queryString->select('m.id,m.code,m.name,m.description,m.bedType,m.maxOccupancy,m.category,m.featured,m.status')
							->from('ContentFilter\Entity\PhoenixContentFilter', 'm')
							->where('m.property = :par1') 
                            ->andwhere('m.name = :par2')							
							->andwhere('m.bedType = :par3')	                            											
							->setParameter('par1', $hotel)
                            ->setParameter('par2', $roomName) 							
							->setParameter('par3', $bedType);																					                          															
			   $result = $queryString->getQuery()->getResult();	
			   goto exitRoutrine;   
			  } 
	    // HOTELNAME, BEDTYPE, ROOMNAME ONLY ENDS
		
		// KEYWORDS, ROOMCODE, ROOMCATEGORIES ONLY STARTS
			   if($hotelCheckbox==0 && $roomNameCheckbox==0 && $bedTypeCheckbox==0 && $roomCategoriesCheckbox==1
			   && $maxOccupancyCheckbox==0 && $roomCodeCheckbox==1 && $roomIdCheckbox==0 && $roomDescriptionCheckbox==1)
			   {      	   
			   $queryString = $this->getDefaultEntityManager()->createQueryBuilder();  	
			   $queryString->select('m.id,m.code,m.name,m.description,m.bedType,m.maxOccupancy,m.category,m.featured,m.status')
							->from('ContentFilter\Entity\PhoenixContentFilter', 'm')						  
							->where('m.category = :par4')							
							->andwhere('m.code = :par6')							
							->andwhere('m.description like :par8')							
							->setParameter('par4', $roomCategories)						
							->setParameter('par6', $roomCode)										
							->setParameter('par8', '%'.$roomDescription.'%'); 																					                          															
			   $result = $queryString->getQuery()->getResult();	
			   goto exitRoutrine;   
			  } 
	    // KEYWORDS, ROOMCODE, ROOMCATEGORIES  ONLY ENDS
		
	    // ROOMID, ROOMCODE, ROOMCATEGORIES ONLY STARTS
			   if($hotelCheckbox==0 && $roomNameCheckbox==0 && $bedTypeCheckbox==0 && $roomCategoriesCheckbox==1
			   && $maxOccupancyCheckbox==0 && $roomCodeCheckbox==1 && $roomIdCheckbox==1 && $roomDescriptionCheckbox==0)
			   {      	   
			   $queryString = $this->getDefaultEntityManager()->createQueryBuilder();  	
			   $queryString->select('m.id,m.code,m.name,m.description,m.bedType,m.maxOccupancy,m.category,m.featured,m.status')
							->from('ContentFilter\Entity\PhoenixContentFilter', 'm')						  
							->where('m.category = :par4')							
							->andwhere('m.code = :par6')
                            ->andwhere('m.id = :par7')															
							->setParameter('par4', $roomCategories)						
							->setParameter('par6', $roomCode)		
                            ->setParameter('par7', $roomId);																												                          															
			   $result = $queryString->getQuery()->getResult();	
			   goto exitRoutrine;   
			  } 
	    // ROOMID, ROOMCODE, ROOMCATEGORIES  ONLY ENDS
		
		// HOTELNAME, ROOMCODE, ROOMCATEGORIES ONLY STARTS
			   if($hotelCheckbox==1 && $roomNameCheckbox==0 && $bedTypeCheckbox==0 && $roomCategoriesCheckbox==1
			   && $maxOccupancyCheckbox==0 && $roomCodeCheckbox==1 && $roomIdCheckbox==0 && $roomDescriptionCheckbox==0)
			   {      	   
			   $queryString = $this->getDefaultEntityManager()->createQueryBuilder();  	
			   $queryString->select('m.id,m.code,m.name,m.description,m.bedType,m.maxOccupancy,m.category,m.featured,m.status')
							->from('ContentFilter\Entity\PhoenixContentFilter', 'm')	
                            ->where('m.property = :par1') 							
							->andwhere('m.category = :par4')							
							->andwhere('m.code = :par6') 
                            ->setParameter('par1', $hotel)							
							->setParameter('par4', $roomCategories)						
							->setParameter('par6', $roomCode);                          																												                          															
			   $result = $queryString->getQuery()->getResult();	
			   goto exitRoutrine;   
			  } 
	    // HOTELNAME, ROOMCODE, ROOMCATEGORIES  ONLY ENDS
			
		// ROOMCODE, BEDTYPE, KEYWORDS ONLY STARTS
			   if($hotelCheckbox==0 && $roomNameCheckbox==0 && $bedTypeCheckbox==1 && $roomCategoriesCheckbox==0
			   && $maxOccupancyCheckbox==0 && $roomCodeCheckbox==1 && $roomIdCheckbox==0 && $roomDescriptionCheckbox==1)
			   {      	   
			   $queryString = $this->getDefaultEntityManager()->createQueryBuilder();  	
			   $queryString->select('m.id,m.code,m.name,m.description,m.bedType,m.maxOccupancy,m.category,m.featured,m.status')
							->from('ContentFilter\Entity\PhoenixContentFilter', 'm')	
                            ->where('m.bedType = :par3') 													
							->andwhere('m.code = :par6') 
                      		->andwhere('m.description like :par8')	
                            ->setParameter('par3', $bedType)							
							->setParameter('par6', $roomCode)
							->setParameter('par8', '%'.$roomDescription.'%');                          																												                          															
			   $result = $queryString->getQuery()->getResult();	
			   goto exitRoutrine;   
			  } 
	    // ROOMCODE, BEDTYPE, KEYWORDS ONLY ENDS
		
		// ROOMNAME, ROOM CODE AND KEYWORDS ONLY STARTS 
		if($hotelCheckbox==0 && $roomNameCheckbox==1 && $bedTypeCheckbox==0 && $roomCategoriesCheckbox==0
			   && $maxOccupancyCheckbox==0 && $roomCodeCheckbox==1 && $roomIdCheckbox==0 && $roomDescriptionCheckbox==1)
			   {      	   
			   $queryString = $this->getDefaultEntityManager()->createQueryBuilder();  	
			   $queryString->select('m.id,m.code,m.name,m.description,m.bedType,m.maxOccupancy,m.category,m.featured,m.status')
							->from('ContentFilter\Entity\PhoenixContentFilter', 'm')
                            ->where('m.name = :par2')						
							->andwhere('m.code = :par6')							
							->andwhere('m.description like :par8')							
							->setParameter('par2', $roomName)
							->setParameter('par6', $roomCode)												
							->setParameter('par8', '%'.$roomDescription.'%');			   
			   $result = $queryString->getQuery()->getResult();	
			   goto exitRoutrine;   
			  } 
		//  ROOMNAME, ROOM CODE AND KEYWORDS ONLY ENDS
		
		// HOTELNAME, ROOM CODE AND KEYWORDS ONLY STARTS 
		       if($hotelCheckbox==1 && $roomNameCheckbox==0 && $bedTypeCheckbox==0 && $roomCategoriesCheckbox==0
			   && $maxOccupancyCheckbox==0 && $roomCodeCheckbox==1 && $roomIdCheckbox==0 && $roomDescriptionCheckbox==1)
			   {      	   
			   $queryString = $this->getDefaultEntityManager()->createQueryBuilder();  	
			   $queryString->select('m.id,m.code,m.name,m.description,m.bedType,m.maxOccupancy,m.category,m.featured,m.status')
							->from('ContentFilter\Entity\PhoenixContentFilter', 'm')
							->where('m.property = :par1')                           						
							->andwhere('m.code = :par6')							
							->andwhere('m.description like :par8')
                            ->setParameter('par1', $hotel)							
							->setParameter('par6', $roomCode)												
							->setParameter('par8', '%'.$roomDescription.'%');			   
			   $result = $queryString->getQuery()->getResult();	
			   goto exitRoutrine;   
			  } 
		//  HOTELNAME, ROOM CODE AND KEYWORDS ONLY ENDS
		
		// ROOMCODE, ROOMCATEGORIES, KEYWORDS ONLY STARTS
			   if($hotelCheckbox==0 && $roomNameCheckbox==0 && $bedTypeCheckbox==0 && $roomCategoriesCheckbox==1
			   && $maxOccupancyCheckbox==0 && $roomCodeCheckbox==1 && $roomIdCheckbox==0 && $roomDescriptionCheckbox==1)
			   {      	   
			   $queryString = $this->getDefaultEntityManager()->createQueryBuilder();  	
			   $queryString->select('m.id,m.code,m.name,m.description,m.bedType,m.maxOccupancy,m.category,m.featured,m.status')
							->from('ContentFilter\Entity\PhoenixContentFilter', 'm')                            							
							->where('m.category = :par4')							
							->andwhere('m.code = :par6')  
                            ->andwhere('m.description like :par8')							
							->setParameter('par4', $roomCategories)						
							->setParameter('par6', $roomCode)
                            ->setParameter('par8', '%'.$roomDescription.'%');							
			   $result = $queryString->getQuery()->getResult();	
			   goto exitRoutrine;   
			  } 
	    // ROOMCODE, ROOMCATEGORIES, KEYWORDS  ONLY ENDS
			
	 
	 // LEVEL 3 ENDS 
	 
	 // LEVEL 4 STARTS 
	    // HOTELNAME, ROOMNAME, BEDTYPE, CATEGORY ONLY STARTS
			   if($hotelCheckbox==1 && $roomNameCheckbox==1 && $bedTypeCheckbox==1 && $roomCategoriesCheckbox==1
			   && $maxOccupancyCheckbox==0 && $roomCodeCheckbox==0 && $roomIdCheckbox==0 && $roomDescriptionCheckbox==0)
			   {      	   
			   $queryString = $this->getDefaultEntityManager()->createQueryBuilder();  	
			   $queryString->select('m.id,m.code,m.name,m.description,m.bedType,m.maxOccupancy,m.category,m.featured,m.status')
							->from('ContentFilter\Entity\PhoenixContentFilter', 'm')
							->where('m.property = :par1') 
							->andwhere('m.name = :par2')
							->andwhere('m.bedType = :par3')
							->andwhere('m.category = :par4')							
							->setParameter('par1', $hotel)
							->setParameter('par2', $roomName)
							->setParameter('par3', $bedType)
							->setParameter('par4', $roomCategories);									
			   $result = $queryString->getQuery()->getResult();	
			   goto exitRoutrine;   
			  } 
	    // HOTELNAME, ROOMNAME, BEDTYPE, CATEGORY ONLY ENDS
		
		// HOTELNAME, ROOMNAME, BEDTYPE, MAX OCCUPANCY ONLY STARTS
			   if($hotelCheckbox==1 && $roomNameCheckbox==1 && $bedTypeCheckbox==1 && $roomCategoriesCheckbox==0
			   && $maxOccupancyCheckbox==1 && $roomCodeCheckbox==0 && $roomIdCheckbox==0 && $roomDescriptionCheckbox==0)
			   {      	   
			   $queryString = $this->getDefaultEntityManager()->createQueryBuilder();  	
			   $queryString->select('m.id,m.code,m.name,m.description,m.bedType,m.maxOccupancy,m.category,m.featured,m.status')
							->from('ContentFilter\Entity\PhoenixContentFilter', 'm')
							->where('m.property = :par1') 
							->andwhere('m.name = :par2')
							->andwhere('m.bedType = :par3')							
							->andwhere('m.maxOccupancy = :par5')						
							->setParameter('par1', $hotel)
							->setParameter('par2', $roomName)
							->setParameter('par3', $bedType)							
							->setParameter('par5', $maxOccupancy);								
			   $result = $queryString->getQuery()->getResult();	
			   goto exitRoutrine;   
			  } 
	    // HOTELNAME, ROOMNAME, BEDTYPE, MAX OCCUPANCY ONLY ENDS
		
		// HOTELNAME, ROOMNAME, BEDTYPE, ROOM CODE ONLY STARTS
			   if($hotelCheckbox==1 && $roomNameCheckbox==1 && $bedTypeCheckbox==1 && $roomCategoriesCheckbox==0
			   && $maxOccupancyCheckbox==0 && $roomCodeCheckbox==1 && $roomIdCheckbox==0 && $roomDescriptionCheckbox==0)
			   {      	   
			   $queryString = $this->getDefaultEntityManager()->createQueryBuilder();  	
			   $queryString->select('m.id,m.code,m.name,m.description,m.bedType,m.maxOccupancy,m.category,m.featured,m.status')
							->from('ContentFilter\Entity\PhoenixContentFilter', 'm')
							->where('m.property = :par1') 
							->andwhere('m.name = :par2')
							->andwhere('m.bedType = :par3')							
							->andwhere('m.code = :par6')						
							->setParameter('par1', $hotel)
							->setParameter('par2', $roomName)
							->setParameter('par3', $bedType)							
							->setParameter('par6', $roomCode);								
			   $result = $queryString->getQuery()->getResult();	
			   goto exitRoutrine;   
			   } 
	    // HOTELNAME, ROOMNAME, BEDTYPE, ROOM CODE ONLY ENDS
				
		// HOTELNAME, ROOMNAME, BEDTYPE, ROOM ID ONLY STARTS
			   if($hotelCheckbox==1 && $roomNameCheckbox==1 && $bedTypeCheckbox==1 && $roomCategoriesCheckbox==0
			   && $maxOccupancyCheckbox==0 && $roomCodeCheckbox==0 && $roomIdCheckbox==1 && $roomDescriptionCheckbox==0)
			   {      	   
			   $queryString = $this->getDefaultEntityManager()->createQueryBuilder();  	
			   $queryString->select('m.id,m.code,m.name,m.description,m.bedType,m.maxOccupancy,m.category,m.featured,m.status')
							->from('ContentFilter\Entity\PhoenixContentFilter', 'm')
							->where('m.property = :par1') 
							->andwhere('m.name = :par2')
							->andwhere('m.bedType = :par3')						
							->andwhere('m.id = :par7')			
							->setParameter('par1', $hotel)
							->setParameter('par2', $roomName)
							->setParameter('par3', $bedType)						
							->setParameter('par7', $roomId);									
			   $result = $queryString->getQuery()->getResult();	
			   goto exitRoutrine;   
			   } 
	    // HOTELNAME, ROOMNAME, BEDTYPE, ROOM ID ONLY ENDS
		
		// HOTELNAME, ROOMNAME, BEDTYPE, KEYWORDS ONLY STARTS
			   if($hotelCheckbox==1 && $roomNameCheckbox==1 && $bedTypeCheckbox==1 && $roomCategoriesCheckbox==0
			   && $maxOccupancyCheckbox==0 && $roomCodeCheckbox==0 && $roomIdCheckbox==0 && $roomDescriptionCheckbox==1)
			   {      	   
			   $queryString = $this->getDefaultEntityManager()->createQueryBuilder();  	
			   $queryString->select('m.id,m.code,m.name,m.description,m.bedType,m.maxOccupancy,m.category,m.featured,m.status')
							->from('ContentFilter\Entity\PhoenixContentFilter', 'm')
							->where('m.property = :par1') 
							->andwhere('m.name = :par2')
							->andwhere('m.bedType = :par3')						
							->andwhere('m.description like :par8')
							->setParameter('par1', $hotel)
							->setParameter('par2', $roomName)
							->setParameter('par3', $bedType)											
							->setParameter('par8', '%'.$roomDescription.'%');			
			   $result = $queryString->getQuery()->getResult();	
			   goto exitRoutrine;   
			  } 
	    // HOTELNAME, ROOMNAME, BEDTYPE, KEYWORDS ONLY ENDS
		
		// HOTELNAME, ROOMNAME, BEDTYPE, KEYWORDS ONLY STARTS
			   if($hotelCheckbox==1 && $roomNameCheckbox==1 && $bedTypeCheckbox==1 && $roomCategoriesCheckbox==0
			   && $maxOccupancyCheckbox==0 && $roomCodeCheckbox==0 && $roomIdCheckbox==0 && $roomDescriptionCheckbox==1)
			   {      	   
			   $queryString = $this->getDefaultEntityManager()->createQueryBuilder();  	
			   $queryString->select('m.id,m.code,m.name,m.description,m.bedType,m.maxOccupancy,m.category,m.featured,m.status')
							->from('ContentFilter\Entity\PhoenixContentFilter', 'm')
							->where('m.property = :par1') 
							->andwhere('m.name = :par2')
							->andwhere('m.bedType = :par3')						
							->andwhere('m.description like :par8')
							->setParameter('par1', $hotel)
							->setParameter('par2', $roomName)
							->setParameter('par3', $bedType)											
							->setParameter('par8', '%'.$roomDescription.'%');			
			   $result = $queryString->getQuery()->getResult();	
			   goto exitRoutrine;   
			  } 
	    // HOTELNAME, ROOMNAME, BEDTYPE, KEYWORDS ONLY ENDS
		
		// ROOMNAME, BEDTYPE, CATGORIES, MAXOCCUPANCY ONLY STARTS
			   if($hotelCheckbox==0 && $roomNameCheckbox==1 && $bedTypeCheckbox==1 && $roomCategoriesCheckbox==1
			   && $maxOccupancyCheckbox==1 && $roomCodeCheckbox==0 && $roomIdCheckbox==0 && $roomDescriptionCheckbox==0)
			   {      	   
			   $queryString = $this->getDefaultEntityManager()->createQueryBuilder();  	
			   $queryString->select('m.id,m.code,m.name,m.description,m.bedType,m.maxOccupancy,m.category,m.featured,m.status')
							->from('ContentFilter\Entity\PhoenixContentFilter', 'm')							
							->where('m.name = :par2')
							->andwhere('m.bedType = :par3')
							->andwhere('m.category = :par4')
							->andwhere('m.maxOccupancy = :par5')							
							->setParameter('par2', $roomName)
							->setParameter('par3', $bedType)
							->setParameter('par4', $roomCategories)
							->setParameter('par5', $maxOccupancy);		
			   $result = $queryString->getQuery()->getResult();	
			   goto exitRoutrine;   
			  } 
	    // ROOMNAME, BEDTYPE, CATEGORIES, MAXOCCUPANCY ONLY ENDS
				
		// ROOMNAME, BEDTYPE, CATEGORIES, ROOMCODE ONLY STARTS
			   if($hotelCheckbox==0 && $roomNameCheckbox==1 && $bedTypeCheckbox==1 && $roomCategoriesCheckbox==1
			   && $maxOccupancyCheckbox==0 && $roomCodeCheckbox==1 && $roomIdCheckbox==0 && $roomDescriptionCheckbox==0)
			   {      	   
			   $queryString = $this->getDefaultEntityManager()->createQueryBuilder();  	
			   $queryString->select('m.id,m.code,m.name,m.description,m.bedType,m.maxOccupancy,m.category,m.featured,m.status')
							->from('ContentFilter\Entity\PhoenixContentFilter', 'm')							
							->where('m.name = :par2')
							->andwhere('m.bedType = :par3')
							->andwhere('m.category = :par4')
							->andwhere('m.code = :par6')						
							->setParameter('par2', $roomName)
							->setParameter('par3', $bedType)
							->setParameter('par4', $roomCategories)							
							->setParameter('par6', $roomCode);									
			   $result = $queryString->getQuery()->getResult();	
			   goto exitRoutrine;   
			  } 
	    // ROOMNAME, BEDTYPE, CATEGORIES, ROOMCODE ONLY ENDS
		
		// ROOMNAME, BEDTYPE, CATEGORIES, ROOMID ONLY STARTS
			   if($hotelCheckbox==0 && $roomNameCheckbox==1 && $bedTypeCheckbox==1 && $roomCategoriesCheckbox==1
			   && $maxOccupancyCheckbox==0 && $roomCodeCheckbox==0 && $roomIdCheckbox==1 && $roomDescriptionCheckbox==0)
			   {      	   
			   $queryString = $this->getDefaultEntityManager()->createQueryBuilder();  	
			   $queryString->select('m.id,m.code,m.name,m.description,m.bedType,m.maxOccupancy,m.category,m.featured,m.status')
							->from('ContentFilter\Entity\PhoenixContentFilter', 'm')							
							->where('m.name = :par2')
							->andwhere('m.bedType = :par3')
							->andwhere('m.category = :par4')
							->andwhere('m.id = :par7')							
							->setParameter('par2', $roomName)
							->setParameter('par3', $bedType)
							->setParameter('par4', $roomCategories)	
                            ->setParameter('par7', $roomId);													
			   $result = $queryString->getQuery()->getResult();	
			   goto exitRoutrine;   
			  } 
	    // ROOMNAME, BEDTYPE, CATEGORIES, ROOMID ONLY ENDS
		
		// ROOMNAME, BEDTYPE, CATEGORIES, KEYWORDS ONLY STARTS
			   if($hotelCheckbox==0 && $roomNameCheckbox==1 && $bedTypeCheckbox==1 && $roomCategoriesCheckbox==1
			   && $maxOccupancyCheckbox==0 && $roomCodeCheckbox==0 && $roomIdCheckbox==0 && $roomDescriptionCheckbox==1)
			   {      	   
			   $queryString = $this->getDefaultEntityManager()->createQueryBuilder();  	
			   $queryString->select('m.id,m.code,m.name,m.description,m.bedType,m.maxOccupancy,m.category,m.featured,m.status')
							->from('ContentFilter\Entity\PhoenixContentFilter', 'm')							
							->where('m.name = :par2')
							->andwhere('m.bedType = :par3')
							->andwhere('m.category = :par4')
							->andwhere('m.description like :par8')						
							->setParameter('par2', $roomName)
							->setParameter('par3', $bedType)
							->setParameter('par4', $roomCategories)	
                            ->setParameter('par8', '%'.$roomDescription.'%');													
			   $result = $queryString->getQuery()->getResult();	
			   goto exitRoutrine;   
			  } 
	    // ROOMNAME, BEDTYPE, CATEGORIES, KEYWORDS ONLY ENDS
		
		// BEDTYPE, ROOMCATEGORIES, MAXOCCUPANCY, ROOMCODE ONLY STARTS
			   if($hotelCheckbox==0 && $roomNameCheckbox==0 && $bedTypeCheckbox==1 && $roomCategoriesCheckbox==1
			   && $maxOccupancyCheckbox==1 && $roomCodeCheckbox==1 && $roomIdCheckbox==0 && $roomDescriptionCheckbox==0)
			   {      	   
			   $queryString = $this->getDefaultEntityManager()->createQueryBuilder();  	
			   $queryString->select('m.id,m.code,m.name,m.description,m.bedType,m.maxOccupancy,m.category,m.featured,m.status')
							->from('ContentFilter\Entity\PhoenixContentFilter', 'm')						
							->where('m.bedType = :par3')
							->andwhere('m.category = :par4')
							->andwhere('m.maxOccupancy = :par5')
							->andwhere('m.code = :par6')							
							->setParameter('par3', $bedType)
							->setParameter('par4', $roomCategories)
							->setParameter('par5', $maxOccupancy)
							->setParameter('par6', $roomCode);							
			   $result = $queryString->getQuery()->getResult();	
			   goto exitRoutrine;   
			  } 
	    // BEDTYPE, ROOMCATEGORIES, MAXOCCUPANCY, ROOMCODE ONLY ENDS
		
		// BEDTYPE, ROOMCATEGORIES, MAXOCCUPANCY, ROOMID ONLY STARTS
			   if($hotelCheckbox==0 && $roomNameCheckbox==0 && $bedTypeCheckbox==1 && $roomCategoriesCheckbox==1
			   && $maxOccupancyCheckbox==1 && $roomCodeCheckbox==0 && $roomIdCheckbox==1 && $roomDescriptionCheckbox==0)
			   {      	   
			   $queryString = $this->getDefaultEntityManager()->createQueryBuilder();  	
			   $queryString->select('m.id,m.code,m.name,m.description,m.bedType,m.maxOccupancy,m.category,m.featured,m.status')
							->from('ContentFilter\Entity\PhoenixContentFilter', 'm')						
							//->where('m.bedType = :par3')
							->andwhere('m.category = :par4')
							->andwhere('m.maxOccupancy = :par5')
							->andwhere('m.id = :par7')							
							->setParameter('par3', $bedType)
							->setParameter('par4', $roomCategories)
							->setParameter('par5', $maxOccupancy)
							->setParameter('par7', $roomId);																					
			   $result = $queryString->getQuery()->getResult();	
			   goto exitRoutrine;   
			  } 
	    // BEDTYPE, ROOMCATEGORIES, MAXOCCUPANCY, ROOMID ONLY ENDS
		
		// BEDTYPE, ROOMCATEGORIES, MAXOCCUPANCY, KEYWORDS ONLY STARTS
			   if($hotelCheckbox==0 && $roomNameCheckbox==0 && $bedTypeCheckbox==1 && $roomCategoriesCheckbox==1
			   && $maxOccupancyCheckbox==1 && $roomCodeCheckbox==0 && $roomIdCheckbox==0 && $roomDescriptionCheckbox==1)
			   {      	   
			   $queryString = $this->getDefaultEntityManager()->createQueryBuilder();  	
			   $queryString->select('m.id,m.code,m.name,m.description,m.bedType,m.maxOccupancy,m.category,m.featured,m.status')
							->from('ContentFilter\Entity\PhoenixContentFilter', 'm')						
							//->where('m.bedType = :par3')
							->andwhere('m.category = :par4')
							->andwhere('m.maxOccupancy = :par5')
							->andwhere('m.description like :par8')							
							->setParameter('par3', $bedType)
							->setParameter('par4', $roomCategories)
							->setParameter('par5', $maxOccupancy)												
							->setParameter('par8', '%'.$roomDescription.'%');													
			   $result = $queryString->getQuery()->getResult();	
			   goto exitRoutrine;   
			  } 
	    // BEDTYPE, ROOMCATEGORIES, MAXOCCUPANCY, KEYWORDS ONLY ENDS
		
		//
		
		// ROOMCATEGORIES, MAXOCCUPANCY, ROOMID, ROOMCODE ONLY STARTS
			   if($hotelCheckbox==0 && $roomNameCheckbox==0 && $bedTypeCheckbox==0 && $roomCategoriesCheckbox==1
			   && $maxOccupancyCheckbox==1 && $roomCodeCheckbox==1 && $roomIdCheckbox==1 && $roomDescriptionCheckbox==0)
			   {      	   
			   $queryString = $this->getDefaultEntityManager()->createQueryBuilder();  	
			   $queryString->select('m.id,m.code,m.name,m.description,m.bedType,m.maxOccupancy,m.category,m.featured,m.status')
							->from('ContentFilter\Entity\PhoenixContentFilter', 'm')						
							->where('m.category = :par4')
							->andwhere('m.maxOccupancy = :par5')
							->andwhere('m.code = :par6')
							->andwhere('m.id = :par7')							
							->setParameter('par4', $roomCategories)
							->setParameter('par5', $maxOccupancy)
							->setParameter('par6', $roomCode)
							->setParameter('par7', $roomId);																					
			   $result = $queryString->getQuery()->getResult();	
			   goto exitRoutrine;   
			  } 
	    // ROOMCATEGORIES, MAXOCCUPANCY, ROOMID, ROOMCODE ONLY ENDS
		
		// ROOMCATEGORIES, MAXOCCUPANCY, ROOMCODE, KEYWORDS ONLY STARTS
			   if($hotelCheckbox==0 && $roomNameCheckbox==0 && $bedTypeCheckbox==0 && $roomCategoriesCheckbox==1
			   && $maxOccupancyCheckbox==1 && $roomCodeCheckbox==1 && $roomIdCheckbox==0 && $roomDescriptionCheckbox==1)
			   {      	   
			   $queryString = $this->getDefaultEntityManager()->createQueryBuilder();  	
			   $queryString->select('m.id,m.code,m.name,m.description,m.bedType,m.maxOccupancy,m.category,m.featured,m.status')
							->from('ContentFilter\Entity\PhoenixContentFilter', 'm')						
							->where('m.category = :par4')
							->andwhere('m.maxOccupancy = :par5')
							->andwhere('m.code = :par6')
							->andwhere('m.description like :par8')							
							->setParameter('par4', $roomCategories)
							->setParameter('par5', $maxOccupancy)
                            ->setParameter('par6', $roomCode)							
							->setParameter('par8', '%'.$roomDescription.'%');													
			   $result = $queryString->getQuery()->getResult();	
			   goto exitRoutrine;   
			  } 
	    // ROOMCATEGORIES, MAXOCCUPANCY, ROOMCODE, KEYWORDS ONLY ENDS
		
		// MAXOCCUPANCY, ROOMCODE, ROOMID, KEYWORDS ONLY STARTS
			   if($hotelCheckbox==0 && $roomNameCheckbox==0 && $bedTypeCheckbox==0 && $roomCategoriesCheckbox==0
			   && $maxOccupancyCheckbox==1 && $roomCodeCheckbox==1 && $roomIdCheckbox==1 && $roomDescriptionCheckbox==1)
			   {      	   
			   $queryString = $this->getDefaultEntityManager()->createQueryBuilder();  	
			   $queryString->select('m.id,m.code,m.name,m.description,m.bedType,m.maxOccupancy,m.category,m.featured,m.status')
							->from('ContentFilter\Entity\PhoenixContentFilter', 'm')						
							->where('m.maxOccupancy = :par5')
							->andwhere('m.code = :par6')
							->andwhere('m.id = :par7')	
							->andwhere('m.description like :par8')						
							->setParameter('par5', $maxOccupancy)							
                            ->setParameter('par6', $roomCode)
                            ->setParameter('par7', $roomId)							
							->setParameter('par8', '%'.$roomDescription.'%');													
			   $result = $queryString->getQuery()->getResult();	
			   goto exitRoutrine;   
			  } 
	    // MAXOCCUPANCY, ROOMCODE, ROOMID, KEYWORDS ONLY ENDS
		 
	 // LEVEL 4 ENDS 
	 
	 
	 // LEVEL 5 STARTS 
	 
	 // HOTELNAME, ROOMNAME, BEDTYPE, CATEGORIES, MAXOCCUPANCY ONLY STARTS
			   if($hotelCheckbox==1 && $roomNameCheckbox==1 && $bedTypeCheckbox==1 && $roomCategoriesCheckbox==1
			   && $maxOccupancyCheckbox==1 && $roomCodeCheckbox==0 && $roomIdCheckbox==0 && $roomDescriptionCheckbox==0)
			   {      	   
			   $queryString = $this->getDefaultEntityManager()->createQueryBuilder();  	
			   $queryString->select('m.id,m.code,m.name,m.description,m.bedType,m.maxOccupancy,m.category,m.featured,m.status')
							->from('ContentFilter\Entity\PhoenixContentFilter', 'm')
							->where('m.property = :par1') 
							->andwhere('m.name = :par2')
							->andwhere('m.bedType = :par3')
							->andwhere('m.category = :par4')
							->andwhere('m.maxOccupancy = :par5')							
							->setParameter('par1', $hotel)
							->setParameter('par2', $roomName)
							->setParameter('par3', $bedType)
							->setParameter('par4', $roomCategories)
							->setParameter('par5', $maxOccupancy);										
			   $result = $queryString->getQuery()->getResult();	
			   goto exitRoutrine;   
			  } 
	    // HOTELNAME, ROOMNAME, BEDTYPE, CATEGORIES, MAXOCCUPANCY ONLY ENDS
		
		// HOTELNAME, ROOMNAME, BEDTYPE, CATEGORIES, ROOMCODE ONLY STARTS
			   if($hotelCheckbox==1 && $roomNameCheckbox==1 && $bedTypeCheckbox==1 && $roomCategoriesCheckbox==1
			   && $maxOccupancyCheckbox==0 && $roomCodeCheckbox==1 && $roomIdCheckbox==0 && $roomDescriptionCheckbox==0)
			   {      	   
			   $queryString = $this->getDefaultEntityManager()->createQueryBuilder();  	
			   $queryString->select('m.id,m.code,m.name,m.description,m.bedType,m.maxOccupancy,m.category,m.featured,m.status')
							->from('ContentFilter\Entity\PhoenixContentFilter', 'm')
							->where('m.property = :par1') 
							->andwhere('m.name = :par2')
							->andwhere('m.bedType = :par3')
							->andwhere('m.category = :par4')							
							->andwhere('m.code = :par6')							
							->setParameter('par1', $hotel)
							->setParameter('par2', $roomName)
							->setParameter('par3', $bedType)
							->setParameter('par4', $roomCategories)							
							->setParameter('par6', $roomCode);									
			   $result = $queryString->getQuery()->getResult();	
			   goto exitRoutrine;   
			  } 
	    // HOTELNAME, ROOMNAME, BEDTYPE, CATEGORIES, ROOMCODE ONLY ENDS
		
		// HOTELNAME, ROOMNAME, BEDTYPE, CATEGORIES, ROOMID ONLY STARTS
			   if($hotelCheckbox==1 && $roomNameCheckbox==1 && $bedTypeCheckbox==1 && $roomCategoriesCheckbox==1
			   && $maxOccupancyCheckbox==0 && $roomCodeCheckbox==0 && $roomIdCheckbox==1 && $roomDescriptionCheckbox==0)
			   {      	   
			   $queryString = $this->getDefaultEntityManager()->createQueryBuilder();  	
			   $queryString->select('m.id,m.code,m.name,m.description,m.bedType,m.maxOccupancy,m.category,m.featured,m.status')
							->from('ContentFilter\Entity\PhoenixContentFilter', 'm')
							->where('m.property = :par1') 
							->andwhere('m.name = :par2')
							->andwhere('m.bedType = :par3')
							->andwhere('m.category = :par4')							
							->andwhere('m.id = :par7')					
							->setParameter('par1', $hotel)
							->setParameter('par2', $roomName)
							->setParameter('par3', $bedType)
							->setParameter('par4', $roomCategories)							
							->setParameter('par7', $roomId);								
			   $result = $queryString->getQuery()->getResult();	
			   goto exitRoutrine;   
			  } 
	    // HOTELNAME, ROOMNAME, BEDTYPE, CATEGORIES, ROOMID ONLY ENDS
		
		// HOTELNAME, ROOMNAME, BEDTYPE, CATEGORIES, KEYWORDS ONLY STARTS
			   if($hotelCheckbox==1 && $roomNameCheckbox==1 && $bedTypeCheckbox==1 && $roomCategoriesCheckbox==1
			   && $maxOccupancyCheckbox==0 && $roomCodeCheckbox==0 && $roomIdCheckbox==0 && $roomDescriptionCheckbox==1)
			   {      	   
			   $queryString = $this->getDefaultEntityManager()->createQueryBuilder();  	
			   $queryString->select('m.id,m.code,m.name,m.description,m.bedType,m.maxOccupancy,m.category,m.featured,m.status')
							->from('ContentFilter\Entity\PhoenixContentFilter', 'm')
							->where('m.property = :par1') 
							->andwhere('m.name = :par2')
							->andwhere('m.bedType = :par3')
							->andwhere('m.category = :par4')							
							->andwhere('m.description like :par8')
							->setParameter('par1', $hotel)
							->setParameter('par2', $roomName)
							->setParameter('par3', $bedType)
							->setParameter('par4', $roomCategories)												
							->setParameter('par8', '%'.$roomDescription.'%');			
			   $result = $queryString->getQuery()->getResult();	
			   goto exitRoutrine;   
			  } 
	    // HOTELNAME, ROOMNAME, BEDTYPE, CATEGORIES, KEYWORDS ONLY ENDS
	 
	    
		// HOTELNAME, ROOMNAME, BEDTYPE, CATEGORIES, KEYWORDS ONLY STARTS
			   if($hotelCheckbox==1 && $roomNameCheckbox==1 && $bedTypeCheckbox==1 && $roomCategoriesCheckbox==1
			   && $maxOccupancyCheckbox==0 && $roomCodeCheckbox==0 && $roomIdCheckbox==0 && $roomDescriptionCheckbox==1)
			   {      	   
			   $queryString = $this->getDefaultEntityManager()->createQueryBuilder();  	
			   $queryString->select('m.id,m.code,m.name,m.description,m.bedType,m.maxOccupancy,m.category,m.featured,m.status')
							->from('ContentFilter\Entity\PhoenixContentFilter', 'm')
							->where('m.property = :par1') 
							->andwhere('m.name = :par2')
							->andwhere('m.bedType = :par3')
							->andwhere('m.category = :par4')
							//->andwhere('m.maxOccupancy = :par5')
							//->andwhere('m.code = :par6')
							//->andwhere('m.id = :par7')
							->andwhere('m.description like :par8')
							->setParameter('par1', $hotel)
							->setParameter('par2', $roomName)
							->setParameter('par3', $bedType)
							->setParameter('par4', $roomCategories)
							//->setParameter('par5', $maxOccupancy);
							//->setParameter('par6', $roomCode);
							//->setParameter('par7', $roomId);					
							->setParameter('par8', '%'.$roomDescription.'%');			
			   $result = $queryString->getQuery()->getResult();	
			   goto exitRoutrine;   
			  } 
	    // HOTELNAME, ROOMNAME, BEDTYPE, CATEGORIES, KEYWORDS ONLY ENDS		
		
	 
	 // LEVEL 5 ENDS 
	 
	 
	  // LEVEL 6 STARTS 
		    // FOR ALL PARAMATERS EXCEPT ROOMID AND KEYWORDS STARTS
				if($hotelCheckbox==1 && $roomNameCheckbox==1 && $bedTypeCheckbox==1 && $roomCategoriesCheckbox==1
				&& $maxOccupancyCheckbox==1 && $roomCodeCheckbox==1 && $roomIdCheckbox==0 && $roomDescriptionCheckbox==0)
				{      	   
				$queryString = $this->getDefaultEntityManager()->createQueryBuilder();  	
				$queryString->select('m.id,m.code,m.name,m.description,m.bedType,m.maxOccupancy,m.category,m.featured,m.status')
							->from('ContentFilter\Entity\PhoenixContentFilter', 'm')
							->where('m.property = :par1') 
							->andwhere('m.name = :par2')
							->andwhere('m.bedType = :par3')
							->andwhere('m.category = :par4')
							->andwhere('m.maxOccupancy = :par5')
							->andwhere('m.code = :par6')							
							->setParameter('par1', $hotel)
							->setParameter('par2', $roomName)
							->setParameter('par3', $bedType)
							->setParameter('par4', $roomCategories)
							->setParameter('par5', $maxOccupancy)
							->setParameter('par6', $roomCode);									
				$result = $queryString->getQuery()->getResult();	
				goto exitRoutrine;   
				} 
		    // FOR ALL PARAMATERS EXCEPT ROOMID AND KEYWORDS ENDS
			
			// FOR ALL PARAMATERS EXCEPT ROOMCODE AND KEYWORDS STARTS
				if($hotelCheckbox==1 && $roomNameCheckbox==1 && $bedTypeCheckbox==1 && $roomCategoriesCheckbox==1
				&& $maxOccupancyCheckbox==1 && $roomCodeCheckbox==0 && $roomIdCheckbox==1 && $roomDescriptionCheckbox==0)
				{      	   
				$queryString = $this->getDefaultEntityManager()->createQueryBuilder();  	
				$queryString->select('m.id,m.code,m.name,m.description,m.bedType,m.maxOccupancy,m.category,m.featured,m.status')
							->from('ContentFilter\Entity\PhoenixContentFilter', 'm')
							->where('m.property = :par1') 
							->andwhere('m.name = :par2')
							->andwhere('m.bedType = :par3')
							->andwhere('m.category = :par4')
							->andwhere('m.maxOccupancy = :par5')
							->andwhere('m.id = :par7')
							->setParameter('par1', $hotel)
							->setParameter('par2', $roomName)
							->setParameter('par3', $bedType)
							->setParameter('par4', $roomCategories)
							->setParameter('par5', $maxOccupancy)
							->setParameter('par7', $roomId);			
				$result = $queryString->getQuery()->getResult();	
				goto exitRoutrine;   
				} 
		    // FOR ALL PARAMATERS EXCEPT ROOMCODE AND KEYWORDS ENDS
			
			// FOR ALL PARAMATERS EXCEPT MAXOCCUPANCY AND KEYWORDS STARTS
				if($hotelCheckbox==1 && $roomNameCheckbox==1 && $bedTypeCheckbox==1 && $roomCategoriesCheckbox==1
				&& $maxOccupancyCheckbox==0 && $roomCodeCheckbox==1 && $roomIdCheckbox==1 && $roomDescriptionCheckbox==0)
				{      	   
				$queryString = $this->getDefaultEntityManager()->createQueryBuilder();  	
				$queryString->select('m.id,m.code,m.name,m.description,m.bedType,m.maxOccupancy,m.category,m.featured,m.status')
							->from('ContentFilter\Entity\PhoenixContentFilter', 'm')
							->where('m.property = :par1') 
							->andwhere('m.name = :par2')
							->andwhere('m.bedType = :par3')
							->andwhere('m.category = :par4')
							->andwhere('m.code = :par6')
							->andwhere('m.id = :par7')
							->setParameter('par1', $hotel)
							->setParameter('par2', $roomName)
							->setParameter('par3', $bedType)
							->setParameter('par4', $roomCategories)
							->setParameter('par6', $roomCode)
							->setParameter('par7', $roomId);								
				$result = $queryString->getQuery()->getResult();	
				goto exitRoutrine;   
				} 
		    // FOR ALL PARAMATERS EXCEPT MAXOCCUPANCY AND KEYWORDS ENDS
			
			// FOR ALL PARAMATERS EXCEPT ROOMCAREGORY AND KEYWORDS STARTS
				if($hotelCheckbox==1 && $roomNameCheckbox==1 && $bedTypeCheckbox==1 && $roomCategoriesCheckbox==0
				&& $maxOccupancyCheckbox==1 && $roomCodeCheckbox==1 && $roomIdCheckbox==1 && $roomDescriptionCheckbox==0)
				{      	   
				$queryString = $this->getDefaultEntityManager()->createQueryBuilder();  	
				$queryString->select('m.id,m.code,m.name,m.description,m.bedType,m.maxOccupancy,m.category,m.featured,m.status')
							->from('ContentFilter\Entity\PhoenixContentFilter', 'm')
							->where('m.property = :par1') 
							->andwhere('m.name = :par2')
							->andwhere('m.bedType = :par3')
							->andwhere('m.maxOccupancy = :par5')
							->andwhere('m.code = :par6')
							->andwhere('m.id = :par7')
							->setParameter('par1', $hotel)
							->setParameter('par2', $roomName)
							->setParameter('par3', $bedType)
							->setParameter('par5', $maxOccupancy)
							->setParameter('par6', $roomCode)
							->setParameter('par7', $roomId);							
				$result = $queryString->getQuery()->getResult();	
				goto exitRoutrine;   
				} 
		    // FOR ALL PARAMATERS EXCEPT ROOMCATEGORY AND KEYWORDS ENDS
			
			// FOR ALL PARAMATERS EXCEPT BEDTYPE AND KEYWORDS STARTS
				if($hotelCheckbox==1 && $roomNameCheckbox==1 && $bedTypeCheckbox==0 && $roomCategoriesCheckbox==1
				&& $maxOccupancyCheckbox==1 && $roomCodeCheckbox==1 && $roomIdCheckbox==1 && $roomDescriptionCheckbox==0)
				{      	   
				$queryString = $this->getDefaultEntityManager()->createQueryBuilder();  	
				$queryString->select('m.id,m.code,m.name,m.description,m.bedType,m.maxOccupancy,m.category,m.featured,m.status')
							->from('ContentFilter\Entity\PhoenixContentFilter', 'm')
							->where('m.property = :par1') 
							->andwhere('m.name = :par2')							
							->andwhere('m.category = :par4')
							->andwhere('m.maxOccupancy = :par5')
							->andwhere('m.code = :par6')
							->andwhere('m.id = :par7')							
							->setParameter('par1', $hotel)
							->setParameter('par2', $roomName)							
							->setParameter('par4', $roomCategories)
							->setParameter('par5', $maxOccupancy)
							->setParameter('par6', $roomCode)
							->setParameter('par7', $roomId);							
				$result = $queryString->getQuery()->getResult();	
				goto exitRoutrine;   
				} 
		    // FOR ALL PARAMATERS EXCEPT BEDTYPE AND KEYWORDS ENDS
			
			// FOR ALL PARAMATERS EXCEPT ROOMNAME AND KEYWORDS STARTS
				if($hotelCheckbox==1 && $roomNameCheckbox==0 && $bedTypeCheckbox==1 && $roomCategoriesCheckbox==1
				&& $maxOccupancyCheckbox==1 && $roomCodeCheckbox==1 && $roomIdCheckbox==1 && $roomDescriptionCheckbox==0)
				{      	   
				$queryString = $this->getDefaultEntityManager()->createQueryBuilder();  	
				$queryString->select('m.id,m.code,m.name,m.description,m.bedType,m.maxOccupancy,m.category,m.featured,m.status')
							->from('ContentFilter\Entity\PhoenixContentFilter', 'm')
							->where('m.property = :par1') 
							->andwhere('m.bedType = :par3')
							->andwhere('m.category = :par4')
							->andwhere('m.maxOccupancy = :par5')
							->andwhere('m.code = :par6')
							->andwhere('m.id = :par7')
							->setParameter('par1', $hotel)
							->setParameter('par3', $bedType)
							->setParameter('par4', $roomCategories)
							->setParameter('par5', $maxOccupancy)
							->setParameter('par6', $roomCode)
							->setParameter('par7', $roomId);							
				$result = $queryString->getQuery()->getResult();	
				goto exitRoutrine;   
				} 
		    // FOR ALL PARAMATERS EXCEPT ROOMNAME AND KEYWORDS ENDS
			
			// FOR ALL PARAMATERS EXCEPT HOTELNAME AND KEYWORDS STARTS
				if($hotelCheckbox==0 && $roomNameCheckbox==1 && $bedTypeCheckbox==1 && $roomCategoriesCheckbox==1
				&& $maxOccupancyCheckbox==1 && $roomCodeCheckbox==1 && $roomIdCheckbox==1 && $roomDescriptionCheckbox==0)
				{      	   
				$queryString = $this->getDefaultEntityManager()->createQueryBuilder();  	
				$queryString->select('m.id,m.code,m.name,m.description,m.bedType,m.maxOccupancy,m.category,m.featured,m.status')
							->from('ContentFilter\Entity\PhoenixContentFilter', 'm')
							->andwhere('m.name = :par2')
							->andwhere('m.bedType = :par3')
							->andwhere('m.category = :par4')
							->andwhere('m.maxOccupancy = :par5')
							->andwhere('m.code = :par6')
							->andwhere('m.id = :par7')
							->setParameter('par2', $roomName)
							->setParameter('par3', $bedType)
							->setParameter('par4', $roomCategories)
							->setParameter('par5', $maxOccupancy)
							->setParameter('par6', $roomCode)
							->setParameter('par7', $roomId);								
				$result = $queryString->getQuery()->getResult();	
				goto exitRoutrine;   
				} 
		    // FOR ALL PARAMATERS EXCEPT HOTELNAME AND KEYWORDS ENDS
			
			// FOR ALL PARAMATERS EXCEPT ROOMID AND ROOMCODE STARTS
				if($hotelCheckbox==1 && $roomNameCheckbox==1 && $bedTypeCheckbox==1 && $roomCategoriesCheckbox==1
				&& $maxOccupancyCheckbox==1 && $roomCodeCheckbox==0 && $roomIdCheckbox==0 && $roomDescriptionCheckbox==1)
				{      	   
				$queryString = $this->getDefaultEntityManager()->createQueryBuilder();  	
				$queryString->select('m.id,m.code,m.name,m.description,m.bedType,m.maxOccupancy,m.category,m.featured,m.status')
							->from('ContentFilter\Entity\PhoenixContentFilter', 'm')
							->where('m.property = :par1') 
							->andwhere('m.name = :par2')
							->andwhere('m.bedType = :par3')
							->andwhere('m.category = :par4')
							->andwhere('m.maxOccupancy = :par5')						
							->andwhere('m.description like :par8')
							->setParameter('par1', $hotel)
							->setParameter('par2', $roomName)
							->setParameter('par3', $bedType)
							->setParameter('par4', $roomCategories)
							->setParameter('par5', $maxOccupancy)												
							->setParameter('par8', '%'.$roomDescription.'%');			
				$result = $queryString->getQuery()->getResult();	
				goto exitRoutrine;   
				} 
		    // FOR ALL PARAMATERS EXCEPT ROOMID AND ROOMCODE ENDS
			
			// FOR ALL PARAMATERS EXCEPT ROOMID AND MAXOCCUPANCY STARTS
				if($hotelCheckbox==1 && $roomNameCheckbox==1 && $bedTypeCheckbox==1 && $roomCategoriesCheckbox==1
				&& $maxOccupancyCheckbox==0 && $roomCodeCheckbox==1 && $roomIdCheckbox==0 && $roomDescriptionCheckbox==1)
				{      	   
				$queryString = $this->getDefaultEntityManager()->createQueryBuilder();  	
				$queryString->select('m.id,m.code,m.name,m.description,m.bedType,m.maxOccupancy,m.category,m.featured,m.status')
							->from('ContentFilter\Entity\PhoenixContentFilter', 'm')
							->where('m.property = :par1') 
							->andwhere('m.name = :par2')
							->andwhere('m.bedType = :par3')
							->andwhere('m.category = :par4')
							->andwhere('m.code = :par6')
							->andwhere('m.description like :par8')
							->setParameter('par1', $hotel)
							->setParameter('par2', $roomName)
							->setParameter('par3', $bedType)
							->setParameter('par4', $roomCategories)
							->setParameter('par6', $roomCode)
							->setParameter('par8', '%'.$roomDescription.'%');			
				$result = $queryString->getQuery()->getResult();	
				goto exitRoutrine;   
				} 
		    // FOR ALL PARAMATERS EXCEPT ROOMID AND MAXOCCUPANCY ENDS
			
			// FOR ALL PARAMATERS EXCEPT ROOMID AND ROOMCATEGORY STARTS
				if($hotelCheckbox==1 && $roomNameCheckbox==1 && $bedTypeCheckbox==1 && $roomCategoriesCheckbox==0
				&& $maxOccupancyCheckbox==1 && $roomCodeCheckbox==1 && $roomIdCheckbox==0 && $roomDescriptionCheckbox==1)
				{      	   
				$queryString = $this->getDefaultEntityManager()->createQueryBuilder();  	
				$queryString->select('m.id,m.code,m.name,m.description,m.bedType,m.maxOccupancy,m.category,m.featured,m.status')
							->from('ContentFilter\Entity\PhoenixContentFilter', 'm')
							->where('m.property = :par1') 
							->andwhere('m.name = :par2')
							->andwhere('m.bedType = :par3')
							->andwhere('m.maxOccupancy = :par5')
							->andwhere('m.code = :par6')
							->andwhere('m.description like :par8')
							->setParameter('par1', $hotel)
							->setParameter('par2', $roomName)
							->setParameter('par3', $bedType)
							->setParameter('par5', $maxOccupancy)
							->setParameter('par6', $roomCode)
							->setParameter('par8', '%'.$roomDescription.'%');			
				$result = $queryString->getQuery()->getResult();	
				goto exitRoutrine;   
				} 
		    // FOR ALL PARAMATERS EXCEPT ROOMID AND ROOMCATEGORY ENDS
			
			// FOR ALL PARAMATERS EXCEPT ROOMID AND BEDTYPE STARTS
				if($hotelCheckbox==1 && $roomNameCheckbox==1 && $bedTypeCheckbox==0 && $roomCategoriesCheckbox==1
				&& $maxOccupancyCheckbox==1 && $roomCodeCheckbox==1 && $roomIdCheckbox==0 && $roomDescriptionCheckbox==1)
				{      	   
				$queryString = $this->getDefaultEntityManager()->createQueryBuilder();  	
				$queryString->select('m.id,m.code,m.name,m.description,m.bedType,m.maxOccupancy,m.category,m.featured,m.status')
							->from('ContentFilter\Entity\PhoenixContentFilter', 'm')
							->where('m.property = :par1') 
							->andwhere('m.name = :par2')
							->andwhere('m.category = :par4')
							->andwhere('m.maxOccupancy = :par5')
							->andwhere('m.code = :par6')
							->andwhere('m.description like :par8')
							->setParameter('par1', $hotel)
							->setParameter('par2', $roomName)
							->setParameter('par4', $roomCategories)
							->setParameter('par5', $maxOccupancy)
							->setParameter('par6', $roomCode)
							->setParameter('par8', '%'.$roomDescription.'%');			
				$result = $queryString->getQuery()->getResult();	
				goto exitRoutrine;   
				} 
		    // FOR ALL PARAMATERS EXCEPT ROOMID AND BEDTYPE ENDS
			
			// FOR ALL PARAMATERS EXCEPT ROOMID AND ROOMNAME STARTS
				if($hotelCheckbox==1 && $roomNameCheckbox==0 && $bedTypeCheckbox==1 && $roomCategoriesCheckbox==1
				&& $maxOccupancyCheckbox==1 && $roomCodeCheckbox==1 && $roomIdCheckbox==0 && $roomDescriptionCheckbox==1)
				{      	   
				$queryString = $this->getDefaultEntityManager()->createQueryBuilder();  	
				$queryString->select('m.id,m.code,m.name,m.description,m.bedType,m.maxOccupancy,m.category,m.featured,m.status')
							->from('ContentFilter\Entity\PhoenixContentFilter', 'm')
							->where('m.property = :par1') 
							->andwhere('m.bedType = :par3')
							->andwhere('m.category = :par4')
							->andwhere('m.maxOccupancy = :par5')
							->andwhere('m.code = :par6')
							->andwhere('m.description like :par8')
							->setParameter('par1', $hotel)
							->setParameter('par3', $bedType)
							->setParameter('par4', $roomCategories)
							->setParameter('par5', $maxOccupancy)
							->setParameter('par6', $roomCode)
							->setParameter('par8', '%'.$roomDescription.'%');			
				$result = $queryString->getQuery()->getResult();	
				goto exitRoutrine;   
				} 
		    // FOR ALL PARAMATERS EXCEPT ROOMID AND ROOMNAME ENDS
			
			// FOR ALL PARAMATERS EXCEPT ROOMID AND HOTELNAME STARTS
				if($hotelCheckbox==0 && $roomNameCheckbox==1 && $bedTypeCheckbox==1 && $roomCategoriesCheckbox==1
				&& $maxOccupancyCheckbox==1 && $roomCodeCheckbox==1 && $roomIdCheckbox==0 && $roomDescriptionCheckbox==1)
				{      	   
				$queryString = $this->getDefaultEntityManager()->createQueryBuilder();  	
				$queryString->select('m.id,m.code,m.name,m.description,m.bedType,m.maxOccupancy,m.category,m.featured,m.status')
							->from('ContentFilter\Entity\PhoenixContentFilter', 'm')
							->where('m.name = :par2')
							->andwhere('m.bedType = :par3')
							->andwhere('m.category = :par4')
							->andwhere('m.maxOccupancy = :par5')
							->andwhere('m.code = :par6')
							->andwhere('m.description like :par8')
							->setParameter('par2', $roomName)
							->setParameter('par3', $bedType)
							->setParameter('par4', $roomCategories)
							->setParameter('par5', $maxOccupancy)
							->setParameter('par6', $roomCode)											
							->setParameter('par8', '%'.$roomDescription.'%');			
				$result = $queryString->getQuery()->getResult();	
				goto exitRoutrine;   
				} 
		    // FOR ALL PARAMATERS EXCEPT ROOMID AND HOTELNAME ENDS
			
			// FOR ALL PARAMATERS EXCEPT ROOMCODE AND MAXOCCUPANCY STARTS
				if($hotelCheckbox==1 && $roomNameCheckbox==1 && $bedTypeCheckbox==1 && $roomCategoriesCheckbox==1
				&& $maxOccupancyCheckbox==0 && $roomCodeCheckbox==0 && $roomIdCheckbox==1 && $roomDescriptionCheckbox==1)
				{      	   
				$queryString = $this->getDefaultEntityManager()->createQueryBuilder();  	
				$queryString->select('m.id,m.code,m.name,m.description,m.bedType,m.maxOccupancy,m.category,m.featured,m.status')
							->from('ContentFilter\Entity\PhoenixContentFilter', 'm')
							->where('m.property = :par1') 
							->andwhere('m.name = :par2')
							->andwhere('m.bedType = :par3')
							->andwhere('m.category = :par4')							
							->andwhere('m.id = :par7')
							->andwhere('m.description like :par8')
							->setParameter('par1', $hotel)
							->setParameter('par2', $roomName)
							->setParameter('par3', $bedType)
							->setParameter('par4', $roomCategories)					
							->setParameter('par7', $roomId)					
							->setParameter('par8', '%'.$roomDescription.'%');			
				$result = $queryString->getQuery()->getResult();	
				goto exitRoutrine;   
				} 
		    // FOR ALL PARAMATERS EXCEPT ROOMCODE AND MAXOCCUPANCY ENDS
			
			// FOR ALL PARAMATERS EXCEPT ROOMCODE AND ROOMCATEGORY STARTS
				if($hotelCheckbox==1 && $roomNameCheckbox==1 && $bedTypeCheckbox==1 && $roomCategoriesCheckbox==0
				&& $maxOccupancyCheckbox==1 && $roomCodeCheckbox==0 && $roomIdCheckbox==1 && $roomDescriptionCheckbox==1)
				{      	   
				$queryString = $this->getDefaultEntityManager()->createQueryBuilder();  	
				$queryString->select('m.id,m.code,m.name,m.description,m.bedType,m.maxOccupancy,m.category,m.featured,m.status')
							->from('ContentFilter\Entity\PhoenixContentFilter', 'm')
							->where('m.property = :par1') 
							->andwhere('m.name = :par2')
							->andwhere('m.bedType = :par3')							
							->andwhere('m.maxOccupancy = :par5')						
							->andwhere('m.id = :par7')
							->andwhere('m.description like :par8')
							->setParameter('par1', $hotel)
							->setParameter('par2', $roomName)
							->setParameter('par3', $bedType)							
							->setParameter('par5', $maxOccupancy)						
							->setParameter('par7', $roomId)					
							->setParameter('par8', '%'.$roomDescription.'%');			
				$result = $queryString->getQuery()->getResult();	
				goto exitRoutrine;   
				} 
		    // FOR ALL PARAMATERS EXCEPT ROOMCODE AND ROOMCATEGORY ENDS
			
			// FOR ALL PARAMATERS EXCEPT ROOMCODE AND BEDTYPE STARTS
				if($hotelCheckbox==1 && $roomNameCheckbox==1 && $bedTypeCheckbox==0 && $roomCategoriesCheckbox==1
				&& $maxOccupancyCheckbox==1 && $roomCodeCheckbox==0 && $roomIdCheckbox==1 && $roomDescriptionCheckbox==1)
				{      	   
				$queryString = $this->getDefaultEntityManager()->createQueryBuilder();  	
				$queryString->select('m.id,m.code,m.name,m.description,m.bedType,m.maxOccupancy,m.category,m.featured,m.status')
							->from('ContentFilter\Entity\PhoenixContentFilter', 'm')
							->where('m.property = :par1') 
							->andwhere('m.name = :par2')						
							->andwhere('m.category = :par4')
							->andwhere('m.maxOccupancy = :par5')						
							->andwhere('m.id = :par7')
							->andwhere('m.description like :par8')
							->setParameter('par1', $hotel)
							->setParameter('par2', $roomName)					
							->setParameter('par4', $roomCategories)
							->setParameter('par5', $maxOccupancy)						
							->setParameter('par7', $roomId)					
							->setParameter('par8', '%'.$roomDescription.'%');			
				$result = $queryString->getQuery()->getResult();	
				goto exitRoutrine;   
				} 
		    // FOR ALL PARAMATERS EXCEPT ROOMCODE AND BEDTYPE ENDS
			
			// FOR ALL PARAMATERS EXCEPT ROOMCODE AND ROOMNAME STARTS
				if($hotelCheckbox==1 && $roomNameCheckbox==0 && $bedTypeCheckbox==1 && $roomCategoriesCheckbox==1
				&& $maxOccupancyCheckbox==1 && $roomCodeCheckbox==0 && $roomIdCheckbox==1 && $roomDescriptionCheckbox==1)
				{      	   
				$queryString = $this->getDefaultEntityManager()->createQueryBuilder();  	
				$queryString->select('m.id,m.code,m.name,m.description,m.bedType,m.maxOccupancy,m.category,m.featured,m.status')
							->from('ContentFilter\Entity\PhoenixContentFilter', 'm')
							->where('m.property = :par1') 
							->andwhere('m.bedType = :par3')
							->andwhere('m.category = :par4')
							->andwhere('m.maxOccupancy = :par5')
							->andwhere('m.id = :par7')
							->andwhere('m.description like :par8')
							->setParameter('par1', $hotel)
							->setParameter('par3', $bedType)
							->setParameter('par4', $roomCategories)
							->setParameter('par5', $maxOccupancy)							
							->setParameter('par7', $roomId)					
							->setParameter('par8', '%'.$roomDescription.'%');			
				$result = $queryString->getQuery()->getResult();	
				goto exitRoutrine;   
				} 
		    // FOR ALL PARAMATERS EXCEPT ROOMCODE AND ROOMNAME ENDS
			
			// FOR ALL PARAMATERS EXCEPT ROOMCODE AND HOTELNAME STARTS
				if($hotelCheckbox==0 && $roomNameCheckbox==1 && $bedTypeCheckbox==1 && $roomCategoriesCheckbox==1
				&& $maxOccupancyCheckbox==1 && $roomCodeCheckbox==0 && $roomIdCheckbox==1 && $roomDescriptionCheckbox==1)
				{      	   
				$queryString = $this->getDefaultEntityManager()->createQueryBuilder();  	
				$queryString->select('m.id,m.code,m.name,m.description,m.bedType,m.maxOccupancy,m.category,m.featured,m.status')
							->from('ContentFilter\Entity\PhoenixContentFilter', 'm')						 
							->where('m.name = :par2')
							->andwhere('m.bedType = :par3')
							->andwhere('m.category = :par4')
							->andwhere('m.maxOccupancy = :par5')
							->andwhere('m.id = :par7')
							->andwhere('m.description like :par8')
							->setParameter('par2', $roomName)
							->setParameter('par3', $bedType)
							->setParameter('par4', $roomCategories)
							->setParameter('par5', $maxOccupancy)
							->setParameter('par7', $roomId)					
							->setParameter('par8', '%'.$roomDescription.'%');			
				$result = $queryString->getQuery()->getResult();	
				goto exitRoutrine;   
				} 
		    // FOR ALL PARAMATERS EXCEPT ROOMCODE AND HOTELNAME ENDS
			
			// FOR ALL PARAMATERS EXCEPT MAXOCCUPANCY AND ROOMCATEGORIES STARTS
				if($hotelCheckbox==1 && $roomNameCheckbox==1 && $bedTypeCheckbox==1 && $roomCategoriesCheckbox==0
				&& $maxOccupancyCheckbox==0 && $roomCodeCheckbox==1 && $roomIdCheckbox==1 && $roomDescriptionCheckbox==1)
				{      	   
				$queryString = $this->getDefaultEntityManager()->createQueryBuilder();  	
				$queryString->select('m.id,m.code,m.name,m.description,m.bedType,m.maxOccupancy,m.category,m.featured,m.status')
							->from('ContentFilter\Entity\PhoenixContentFilter', 'm')
							->where('m.property = :par1') 
							->andwhere('m.name = :par2')
							->andwhere('m.bedType = :par3')						
							->andwhere('m.code = :par6')
							->andwhere('m.id = :par7')
							->andwhere('m.description like :par8')
							->setParameter('par1', $hotel)
							->setParameter('par2', $roomName)
							->setParameter('par3', $bedType)							
							->setParameter('par6', $roomCode)
							->setParameter('par7', $roomId)					
							->setParameter('par8', '%'.$roomDescription.'%');			
				$result = $queryString->getQuery()->getResult();	
				goto exitRoutrine;   
				} 
		    // FOR ALL PARAMATERS EXCEPT MAXOCCUPANCY AND ROOMCATEGORIES ENDS
			
			// FOR ALL PARAMATERS EXCEPT MAXOCCUPANCY AND BEDTYPE STARTS
				if($hotelCheckbox==1 && $roomNameCheckbox==1 && $bedTypeCheckbox==0 && $roomCategoriesCheckbox==1
				&& $maxOccupancyCheckbox==0 && $roomCodeCheckbox==1 && $roomIdCheckbox==1 && $roomDescriptionCheckbox==1)
				{      	   
				$queryString = $this->getDefaultEntityManager()->createQueryBuilder();  	
				$queryString->select('m.id,m.code,m.name,m.description,m.bedType,m.maxOccupancy,m.category,m.featured,m.status')
							->from('ContentFilter\Entity\PhoenixContentFilter', 'm')
							->where('m.property = :par1') 
							->andwhere('m.name = :par2')
							->andwhere('m.category = :par4')
						    ->andwhere('m.code = :par6')
							->andwhere('m.id = :par7')
							->andwhere('m.description like :par8')
							->setParameter('par1', $hotel)
							->setParameter('par2', $roomName)						
							->setParameter('par4', $roomCategories)						
							->setParameter('par6', $roomCode)
							->setParameter('par7', $roomId)					
							->setParameter('par8', '%'.$roomDescription.'%');			
				$result = $queryString->getQuery()->getResult();	
				goto exitRoutrine;   
				} 
		    // FOR ALL PARAMATERS EXCEPT MAXOCCUPANCY AND BEDTYPE ENDS
			
			// FOR ALL PARAMATERS EXCEPT MAXOCCUPANCY AND ROOMNAME STARTS
				if($hotelCheckbox==1 && $roomNameCheckbox==0 && $bedTypeCheckbox==1 && $roomCategoriesCheckbox==1
				&& $maxOccupancyCheckbox==0 && $roomCodeCheckbox==1 && $roomIdCheckbox==1 && $roomDescriptionCheckbox==1)
				{      	   
				$queryString = $this->getDefaultEntityManager()->createQueryBuilder();  	
				$queryString->select('m.id,m.code,m.name,m.description,m.bedType,m.maxOccupancy,m.category,m.featured,m.status')
							->from('ContentFilter\Entity\PhoenixContentFilter', 'm')
							->where('m.property = :par1') 						
							->andwhere('m.bedType = :par3')
							->andwhere('m.category = :par4')							
							->andwhere('m.code = :par6')
							->andwhere('m.id = :par7')
							->andwhere('m.description like :par8')
							->setParameter('par1', $hotel)					
							->setParameter('par3', $bedType)
							->setParameter('par4', $roomCategories)							
							->setParameter('par6', $roomCode)
							->setParameter('par7', $roomId)					
							->setParameter('par8', '%'.$roomDescription.'%');			
				$result = $queryString->getQuery()->getResult();	
				goto exitRoutrine;   
				} 
		    // FOR ALL PARAMATERS EXCEPT MAXOCCUPANCY AND ROOMNAME ENDS
			
			// FOR ALL PARAMATERS EXCEPT MAXOCCUPANCY AND HOTELNAME STARTS 
				if($hotelCheckbox==0 && $roomNameCheckbox==1 && $bedTypeCheckbox==1 && $roomCategoriesCheckbox==1
				&& $maxOccupancyCheckbox==0 && $roomCodeCheckbox==1 && $roomIdCheckbox==1 && $roomDescriptionCheckbox==1)
				{      	   
				$queryString = $this->getDefaultEntityManager()->createQueryBuilder();  	
				$queryString->select('m.id,m.code,m.name,m.description,m.bedType,m.maxOccupancy,m.category,m.featured,m.status')
							->from('ContentFilter\Entity\PhoenixContentFilter', 'm')
							->where('m.name = :par2')
							->andwhere('m.bedType = :par3')
							->andwhere('m.category = :par4')					
							->andwhere('m.code = :par6')
							->andwhere('m.id = :par7')
							->andwhere('m.description like :par8')							
							->setParameter('par2', $roomName)
							->setParameter('par3', $bedType)
							->setParameter('par4', $roomCategories)						
							->setParameter('par6', $roomCode)
							->setParameter('par7', $roomId)					
							->setParameter('par8', '%'.$roomDescription.'%');			
				$result = $queryString->getQuery()->getResult();	
				goto exitRoutrine;   
				} 
		    // FOR ALL PARAMATERS EXCEPT MAXOCCUPANCY AND HOTELNAME ENDS
			
		    // FOR ALL PARAMATERS EXCEPT ROOMCATEGORIES AND BEDTYPE STARTS 
				if($hotelCheckbox==1 && $roomNameCheckbox==1 && $bedTypeCheckbox==0 && $roomCategoriesCheckbox==0
				&& $maxOccupancyCheckbox==1 && $roomCodeCheckbox==1 && $roomIdCheckbox==1 && $roomDescriptionCheckbox==1)
				{      	   
				$queryString = $this->getDefaultEntityManager()->createQueryBuilder();  	
				$queryString->select('m.id,m.code,m.name,m.description,m.bedType,m.maxOccupancy,m.category,m.featured,m.status')
							->from('ContentFilter\Entity\PhoenixContentFilter', 'm')
							->where('m.property = :par1') 
							->andwhere('m.name = :par2') 					
							->andwhere('m.maxOccupancy = :par5')
							->andwhere('m.code = :par6')
							->andwhere('m.id = :par7')
							->andwhere('m.description like :par8')
							->setParameter('par1', $hotel)
							->setParameter('par2', $roomName)					
							->setParameter('par5', $maxOccupancy)
							->setParameter('par6', $roomCode)
							->setParameter('par7', $roomId)					
							->setParameter('par8', '%'.$roomDescription.'%');			
				$result = $queryString->getQuery()->getResult();	
				goto exitRoutrine;   
				} 
		    // FOR ALL PARAMATERS EXCEPT ROOMCATEGORIES AND BEDTYPE ENDS 
			
			// FOR ALL PARAMATERS EXCEPT ROOMCATEGORIES AND ROOMNAME STARTS 
				if($hotelCheckbox==1 && $roomNameCheckbox==0 && $bedTypeCheckbox==1 && $roomCategoriesCheckbox==0
				&& $maxOccupancyCheckbox==1 && $roomCodeCheckbox==1 && $roomIdCheckbox==1 && $roomDescriptionCheckbox==1)
				{      	   
				$queryString = $this->getDefaultEntityManager()->createQueryBuilder();  	
				$queryString->select('m.id,m.code,m.name,m.description,m.bedType,m.maxOccupancy,m.category,m.featured,m.status')
							->from('ContentFilter\Entity\PhoenixContentFilter', 'm')
							->where('m.property = :par1') 
							->andwhere('m.bedType = :par3')
							->andwhere('m.maxOccupancy = :par5')
							->andwhere('m.code = :par6')
							->andwhere('m.id = :par7')
							->andwhere('m.description like :par8')
							->setParameter('par1', $hotel)
							->setParameter('par3', $bedType)							
							->setParameter('par5', $maxOccupancy)
							->setParameter('par6', $roomCode)
							->setParameter('par7', $roomId)					
							->setParameter('par8', '%'.$roomDescription.'%');			
				$result = $queryString->getQuery()->getResult();	
				goto exitRoutrine;   
				} 
		    // FOR ALL PARAMATERS EXCEPT ROOMCATEGORIES AND ROOMNAME ENDS 
			
			// FOR ALL PARAMATERS EXCEPT ROOMCATEGORIES AND HOTELNAME STARTS 
				if($hotelCheckbox==0 && $roomNameCheckbox==1 && $bedTypeCheckbox==1 && $roomCategoriesCheckbox==0
				&& $maxOccupancyCheckbox==1 && $roomCodeCheckbox==1 && $roomIdCheckbox==1 && $roomDescriptionCheckbox==1)
				{      	   
				$queryString = $this->getDefaultEntityManager()->createQueryBuilder();  	
				$queryString->select('m.id,m.code,m.name,m.description,m.bedType,m.maxOccupancy,m.category,m.featured,m.status')
							->from('ContentFilter\Entity\PhoenixContentFilter', 'm')							
							->where('m.name = :par2')     
							->andwhere('m.bedType = :par3')							
							->andwhere('m.maxOccupancy = :par5')
							->andwhere('m.code = :par6')
							->andwhere('m.id = :par7')
							->andwhere('m.description like :par8')						
							->setParameter('par2', $roomName)
							->setParameter('par3', $bedType)							
							->setParameter('par5', $maxOccupancy)
							->setParameter('par6', $roomCode)
							->setParameter('par7', $roomId)					
							->setParameter('par8', '%'.$roomDescription.'%');			
				$result = $queryString->getQuery()->getResult();	
				goto exitRoutrine;   
				} 
		    // FOR ALL PARAMATERS EXCEPT ROOMCATEGORIES AND HOTELNAME ENDS 
			
			// FOR ALL PARAMATERS EXCEPT BEDTYPE AND ROOMNAME STARTS 
				if($hotelCheckbox==1 && $roomNameCheckbox==0 && $bedTypeCheckbox==0 && $roomCategoriesCheckbox==1
				&& $maxOccupancyCheckbox==1 && $roomCodeCheckbox==1 && $roomIdCheckbox==1 && $roomDescriptionCheckbox==1)
				{      	   
				$queryString = $this->getDefaultEntityManager()->createQueryBuilder();  	
				$queryString->select('m.id,m.code,m.name,m.description,m.bedType,m.maxOccupancy,m.category,m.featured,m.status')
							->from('ContentFilter\Entity\PhoenixContentFilter', 'm')
							->where('m.property = :par1') 							
							->andwhere('m.category = :par4')
							->andwhere('m.maxOccupancy = :par5')
							->andwhere('m.code = :par6')
							->andwhere('m.id = :par7')
							->andwhere('m.description like :par8')
							->setParameter('par1', $hotel)							
							->setParameter('par4', $roomCategories)
							->setParameter('par5', $maxOccupancy)
							->setParameter('par6', $roomCode)
							->setParameter('par7', $roomId)					
							->setParameter('par8', '%'.$roomDescription.'%');			
				$result = $queryString->getQuery()->getResult();	
				goto exitRoutrine;   
				} 
		    // FOR ALL PARAMATERS EXCEPT BEDTYPE AND ROOMNAME ENDS
			
			// FOR ALL PARAMATERS EXCEPT BEDTYPE AND HOTELNAME STARTS 
				if($hotelCheckbox==0 && $roomNameCheckbox==1 && $bedTypeCheckbox==0 && $roomCategoriesCheckbox==1
				&& $maxOccupancyCheckbox==1 && $roomCodeCheckbox==1 && $roomIdCheckbox==1 && $roomDescriptionCheckbox==1)
				{      	   
				$queryString = $this->getDefaultEntityManager()->createQueryBuilder();  	
				$queryString->select('m.id,m.code,m.name,m.description,m.bedType,m.maxOccupancy,m.category,m.featured,m.status')
							->from('ContentFilter\Entity\PhoenixContentFilter', 'm')
							->where('m.name = :par2')     
							->andwhere('m.category = :par4')
							->andwhere('m.maxOccupancy = :par5')
							->andwhere('m.code = :par6')
							->andwhere('m.id = :par7')
							->andwhere('m.description like :par8')							
							->setParameter('par2', $roomName)							
							->setParameter('par4', $roomCategories)
							->setParameter('par5', $maxOccupancy)
							->setParameter('par6', $roomCode)
							->setParameter('par7', $roomId)					
							->setParameter('par8', '%'.$roomDescription.'%');			
				$result = $queryString->getQuery()->getResult();	
				goto exitRoutrine;   
				} 
		    // FOR ALL PARAMATERS EXCEPT BEDTYPE AND HOTELNAME ENDS
			
			// FOR ALL PARAMATERS EXCEPT ROOMNAME AND HOTELNAME STARTS 
				if($hotelCheckbox==0 && $roomNameCheckbox==0 && $bedTypeCheckbox==1 && $roomCategoriesCheckbox==1
				&& $maxOccupancyCheckbox==1 && $roomCodeCheckbox==1 && $roomIdCheckbox==1 && $roomDescriptionCheckbox==1)
				{      	   
				$queryString = $this->getDefaultEntityManager()->createQueryBuilder();  	
				$queryString->select('m.id,m.code,m.name,m.description,m.bedType,m.maxOccupancy,m.category,m.featured,m.status')
							->from('ContentFilter\Entity\PhoenixContentFilter', 'm')							    
							->where('m.bedType = :par3')
							->andwhere('m.category = :par4')
							->andwhere('m.maxOccupancy = :par5')
							->andwhere('m.code = :par6')
							->andwhere('m.id = :par7')
							->andwhere('m.description like :par8')							
							->setParameter('par3', $bedType)
							->setParameter('par4', $roomCategories)
							->setParameter('par5', $maxOccupancy)
							->setParameter('par6', $roomCode)
							->setParameter('par7', $roomId)					
							->setParameter('par8', '%'.$roomDescription.'%');			
				$result = $queryString->getQuery()->getResult();	
				goto exitRoutrine;   
				} 
		    // FOR ALL PARAMATERS EXCEPT ROOMNAME AND HOTELNAME ENDS		
	  // LEVEL 6 ENDS 
	 	 
	  // LEVEL 7 STARTS 
			// FOR ALL THE PARAMATERS EXCEPT KEYWORDS STARTS 
            	if($hotelCheckbox==1 && $roomNameCheckbox==1 && $bedTypeCheckbox==1 && $roomCategoriesCheckbox==1
			    && $maxOccupancyCheckbox==1 && $roomCodeCheckbox==1 && $roomIdCheckbox==1 && $roomDescriptionCheckbox==0)
			    {      	   
			    $queryString = $this->getDefaultEntityManager()->createQueryBuilder();  	
			    $queryString->select('m.id,m.code,m.name,m.description,m.bedType,m.maxOccupancy,m.category,m.featured,m.status')
							->from('ContentFilter\Entity\PhoenixContentFilter', 'm')
							->where('m.property = :par1') 
							->andwhere('m.name = :par2')
							->andwhere('m.bedType = :par3')
							->andwhere('m.category = :par4')
							->andwhere('m.maxOccupancy = :par5')
							->andwhere('m.code = :par6')
							->andwhere('m.id = :par7')							
							->setParameter('par1', $hotel)
							->setParameter('par2', $roomName)
							->setParameter('par3', $bedType)
							->setParameter('par4', $roomCategories)
							->setParameter('par5', $maxOccupancy)
							->setParameter('par6', $roomCode)
							->setParameter('par7', $roomId);									
			    $result = $queryString->getQuery()->getResult();	
			    goto exitRoutrine;   
			    }  
	        // FOR ALL THE PARAMATERS EXCEPT KEYWORDS ENDS
			
			// FOR ALL THE PARAMATERS EXCEPT ROOMID STARTS 
            	if($hotelCheckbox==1 && $roomNameCheckbox==1 && $bedTypeCheckbox==1 && $roomCategoriesCheckbox==1
			    && $maxOccupancyCheckbox==1 && $roomCodeCheckbox==1 && $roomIdCheckbox==0 && $roomDescriptionCheckbox==1)
			    {      	   
			    $queryString = $this->getDefaultEntityManager()->createQueryBuilder();  	
			    $queryString->select('m.id,m.code,m.name,m.description,m.bedType,m.maxOccupancy,m.category,m.featured,m.status')
							->from('ContentFilter\Entity\PhoenixContentFilter', 'm')
							->where('m.property = :par1') 
							->andwhere('m.name = :par2')
							->andwhere('m.bedType = :par3')
							->andwhere('m.category = :par4')
							->andwhere('m.maxOccupancy = :par5')
							->andwhere('m.code = :par6')						
							->andwhere('m.description like :par8')
							->setParameter('par1', $hotel)
							->setParameter('par2', $roomName)
							->setParameter('par3', $bedType)
							->setParameter('par4', $roomCategories)
							->setParameter('par5', $maxOccupancy)
							->setParameter('par6', $roomCode)											
							->setParameter('par8', '%'.$roomDescription.'%');			
			    $result = $queryString->getQuery()->getResult();	
			    goto exitRoutrine;   
			    }  
	        // FOR ALL THE PARAMATERS EXCEPT ROOMID ENDS
				 
			// FOR ALL THE PARAMATERS EXCEPT ROOMCODE STARTS 
            	if($hotelCheckbox==1 && $roomNameCheckbox==1 && $bedTypeCheckbox==1 && $roomCategoriesCheckbox==1
			    && $maxOccupancyCheckbox==1 && $roomCodeCheckbox==0 && $roomIdCheckbox==1 && $roomDescriptionCheckbox==1)
			    {      	   
			    $queryString = $this->getDefaultEntityManager()->createQueryBuilder();  	
			    $queryString->select('m.id,m.code,m.name,m.description,m.bedType,m.maxOccupancy,m.category,m.featured,m.status')
							->from('ContentFilter\Entity\PhoenixContentFilter', 'm')
							->where('m.property = :par1') 
							->andwhere('m.name = :par2')
							->andwhere('m.bedType = :par3')
							->andwhere('m.category = :par4')
							->andwhere('m.maxOccupancy = :par5')							
							->andwhere('m.id = :par7')
							->andwhere('m.description like :par8')
							->setParameter('par1', $hotel)
							->setParameter('par2', $roomName)
							->setParameter('par3', $bedType)
							->setParameter('par4', $roomCategories)
							->setParameter('par5', $maxOccupancy)							
							->setParameter('par7', $roomId)					
							->setParameter('par8', '%'.$roomDescription.'%');			
			    $result = $queryString->getQuery()->getResult();	
			    goto exitRoutrine;   
			    }  
	        // FOR ALL THE PARAMATERS EXCEPT ROOMCODE ENDS
			
			// FOR ALL THE PARAMATERS EXCEPT MAXOCCUPANCY STARTS 
            	if($hotelCheckbox==1 && $roomNameCheckbox==1 && $bedTypeCheckbox==1 && $roomCategoriesCheckbox==1
			    && $maxOccupancyCheckbox==0 && $roomCodeCheckbox==1 && $roomIdCheckbox==1 && $roomDescriptionCheckbox==1)
			    {      	   
			    $queryString = $this->getDefaultEntityManager()->createQueryBuilder();  	
			    $queryString->select('m.id,m.code,m.name,m.description,m.bedType,m.maxOccupancy,m.category,m.featured,m.status')
							->from('ContentFilter\Entity\PhoenixContentFilter', 'm')
							->where('m.property = :par1') 
							->andwhere('m.name = :par2')
							->andwhere('m.bedType = :par3')
							->andwhere('m.category = :par4')
							->andwhere('m.code = :par6')
							->andwhere('m.id = :par7')
							->andwhere('m.description like :par8')
							->setParameter('par1', $hotel)
							->setParameter('par2', $roomName)
							->setParameter('par3', $bedType)
							->setParameter('par4', $roomCategories)
							->setParameter('par6', $roomCode)
							->setParameter('par7', $roomId)					
							->setParameter('par8', '%'.$roomDescription.'%');			
			    $result = $queryString->getQuery()->getResult();	
			    goto exitRoutrine;   
			    }  
	        // FOR ALL THE PARAMATERS EXCEPT MAXOCCUPANCY ENDS
			
			// FOR ALL THE PARAMATERS EXCEPT ROOMCATEGORY STARTS 
            	if($hotelCheckbox==1 && $roomNameCheckbox==1 && $bedTypeCheckbox==1 && $roomCategoriesCheckbox==0
			    && $maxOccupancyCheckbox==1 && $roomCodeCheckbox==1 && $roomIdCheckbox==1 && $roomDescriptionCheckbox==1)
			    {      	   
			    $queryString = $this->getDefaultEntityManager()->createQueryBuilder();  	
			    $queryString->select('m.id,m.code,m.name,m.description,m.bedType,m.maxOccupancy,m.category,m.featured,m.status')
							->from('ContentFilter\Entity\PhoenixContentFilter', 'm')
							->where('m.property = :par1') 
							->andwhere('m.name = :par2')
							->andwhere('m.bedType = :par3')							
							->andwhere('m.maxOccupancy = :par5')
							->andwhere('m.code = :par6')
							->andwhere('m.id = :par7')
							->andwhere('m.description like :par8')
							->setParameter('par1', $hotel)
							->setParameter('par2', $roomName)
							->setParameter('par3', $bedType)							
							->setParameter('par5', $maxOccupancy)
							->setParameter('par6', $roomCode)
							->setParameter('par7', $roomId)					
							->setParameter('par8', '%'.$roomDescription.'%');			
			    $result = $queryString->getQuery()->getResult();	
			    goto exitRoutrine;   
			    }  
	        // FOR ALL THE PARAMATERS EXCEPT ROOMCATEGORY ENDS
			
			// FOR ALL THE PARAMATERS EXCEPT BEDTYPE STARTS 
            	if($hotelCheckbox==1 && $roomNameCheckbox==1 && $bedTypeCheckbox==0 && $roomCategoriesCheckbox==1
			    && $maxOccupancyCheckbox==1 && $roomCodeCheckbox==1 && $roomIdCheckbox==1 && $roomDescriptionCheckbox==1)
			    {      	   
			    $queryString = $this->getDefaultEntityManager()->createQueryBuilder();  	
			    $queryString->select('m.id,m.code,m.name,m.description,m.bedType,m.maxOccupancy,m.category,m.featured,m.status')
							->from('ContentFilter\Entity\PhoenixContentFilter', 'm')
							->where('m.property = :par1') 
							->andwhere('m.name = :par2')							
							->andwhere('m.category = :par4')
							->andwhere('m.maxOccupancy = :par5')
							->andwhere('m.code = :par6')
							->andwhere('m.id = :par7')
							->andwhere('m.description like :par8')
							->setParameter('par1', $hotel)
							->setParameter('par2', $roomName)							
							->setParameter('par4', $roomCategories)
							->setParameter('par5', $maxOccupancy)
							->setParameter('par6', $roomCode)
							->setParameter('par7', $roomId)					
							->setParameter('par8', '%'.$roomDescription.'%');			
			    $result = $queryString->getQuery()->getResult();	
			    goto exitRoutrine;   
			    }  
	        // FOR ALL THE PARAMATERS EXCEPT BEDTYPE ENDS
			
			// FOR ALL THE PARAMATERS EXCEPT ROOMNAME STARTS 
            	if($hotelCheckbox==1 && $roomNameCheckbox==0 && $bedTypeCheckbox==1 && $roomCategoriesCheckbox==1
			    && $maxOccupancyCheckbox==1 && $roomCodeCheckbox==1 && $roomIdCheckbox==1 && $roomDescriptionCheckbox==1)
			    {      	   
			    $queryString = $this->getDefaultEntityManager()->createQueryBuilder();  	
			    $queryString->select('m.id,m.code,m.name,m.description,m.bedType,m.maxOccupancy,m.category,m.featured,m.status')
							->from('ContentFilter\Entity\PhoenixContentFilter', 'm')
							->where('m.property = :par1') 						
							->andwhere('m.bedType = :par3')
							->andwhere('m.category = :par4')
							->andwhere('m.maxOccupancy = :par5')
							->andwhere('m.code = :par6')
							->andwhere('m.id = :par7')
							->andwhere('m.description like :par8')
							->setParameter('par1', $hotel)							
							->setParameter('par3', $bedType)
							->setParameter('par4', $roomCategories)
							->setParameter('par5', $maxOccupancy)
							->setParameter('par6', $roomCode)
							->setParameter('par7', $roomId)					
							->setParameter('par8', '%'.$roomDescription.'%');			
			    $result = $queryString->getQuery()->getResult();	
			    goto exitRoutrine;   
			    }  
	        // FOR ALL THE PARAMATERS EXCEPT ROOMNAME ENDS
			
			// FOR ALL THE PARAMATERS EXCEPT HOTELNAME STARTS 
            	if($hotelCheckbox==0 && $roomNameCheckbox==1 && $bedTypeCheckbox==1 && $roomCategoriesCheckbox==1
			    && $maxOccupancyCheckbox==1 && $roomCodeCheckbox==1 && $roomIdCheckbox==1 && $roomDescriptionCheckbox==1)
			    {      	   
			    $queryString = $this->getDefaultEntityManager()->createQueryBuilder();  	
			    $queryString->select('m.id,m.code,m.name,m.description,m.bedType,m.maxOccupancy,m.category,m.featured,m.status')
							->from('ContentFilter\Entity\PhoenixContentFilter', 'm')							 
							->andwhere('m.name = :par2')
							->andwhere('m.bedType = :par3')
							->andwhere('m.category = :par4')
							->andwhere('m.maxOccupancy = :par5')
							->andwhere('m.code = :par6')
							->andwhere('m.id = :par7')
							->andwhere('m.description like :par8')							
							->setParameter('par2', $roomName)
							->setParameter('par3', $bedType)
							->setParameter('par4', $roomCategories)
							->setParameter('par5', $maxOccupancy)
							->setParameter('par6', $roomCode)
							->setParameter('par7', $roomId)					
							->setParameter('par8', '%'.$roomDescription.'%');			
			    $result = $queryString->getQuery()->getResult();	
			    goto exitRoutrine;   
			    }  
	        // FOR ALL THE PARAMATERS EXCEPT HOTELNAME ENDS
			
	  // LEVEL 7 ENDS 
     
	 
	  // LEVEL 8 STARTS 
	        // FOR ALL PARAMATERS SELECTED BY THE USER STARTS
			   if($hotelCheckbox==1 && $roomNameCheckbox==1 && $bedTypeCheckbox==1 && $roomCategoriesCheckbox==1
			   && $maxOccupancyCheckbox==1 && $roomCodeCheckbox==1 && $roomIdCheckbox==1 && $roomDescriptionCheckbox==1)
			   {      	   
			   $queryString = $this->getDefaultEntityManager()->createQueryBuilder();  	
			   $queryString->select('m.id,m.code,m.name,m.description,m.bedType,m.maxOccupancy,m.category,m.featured,m.status')
							->from('ContentFilter\Entity\PhoenixContentFilter', 'm')
							->where('m.property = :par1') 
							->andwhere('m.name = :par2')
							->andwhere('m.bedType = :par3')
							->andwhere('m.category = :par4')
							->andwhere('m.maxOccupancy = :par5')
							->andwhere('m.code = :par6')
							->andwhere('m.id = :par7')
							->andwhere('m.description like :par8')
							->setParameter('par1', $hotel)
							->setParameter('par2', $roomName)
							->setParameter('par3', $bedType)
							->setParameter('par4', $roomCategories)
							->setParameter('par5', $maxOccupancy)
							->setParameter('par6', $roomCode)
							->setParameter('par7', $roomId)					
							->setParameter('par8', '%'.$roomDescription.'%');			
			   $result = $queryString->getQuery()->getResult();	
			   goto exitRoutrine;   
			  } 
			// FOR ALL PARAMATERS SELECTED BY THE USER STARTS
	  // LEVEL 8 ENDS 
	  
	  exitRoutrine:		
	  return $result;	
	// QUERY FIRES BASED ON USER SELECTIONS ENDS 
			
		
		 
	}

}

