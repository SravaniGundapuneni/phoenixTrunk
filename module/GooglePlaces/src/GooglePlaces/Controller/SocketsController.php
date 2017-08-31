<?php

/**
 * GooglePlaces SocketsController
 *
 * The SocketsController for the GooglePlaces Module
 *
 * If it is a toolbox action for the GooglePlaces module, it goes here.
 *
 * @category    Toolbox
 * @package     GooglePlaces
 * @subpackage  Controllers
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.5
 * @since       File available since release 13.4
 * @author      Igor Sorokin <isorokin@travelclick.com>
 * @filesource
 */

namespace GooglePlaces\Controller;

use Zend\View\Model\JsonModel;
use Zend\Session\Container;

/**
 * GooglePlaces SocketsController
 *
 * The SocketsController for the GooglePlaces Module
 *
 * If it is a toolbox action for the GooglePlaces module, it goes here.
 *
 * @category    Toolbox
 * @package     GooglePlaces
 * @subpackage  Controllers
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.5
 * @since       File available since release 13.4
 * @author      Igor Sorokin <isorokin@travelclick.com> 
 */

//============================================================================================================================
class SocketsController extends \ListModule\Controller\SocketsController
{
    //========================================================================================================================
    /**
     * getSettingsAction function
     * 
     * @access public
     * @return JsonModel $mapSettings
    */
    public function getSettingsAction($clientId=null)
    {
        $mrgdConf = $this->getServiceLocator()->get('MergedConfig');
        $toolboxIncludeUrl = $mrgdConf->get(array('paths', 'toolboxIncludeUrl'), '');
        $hotels = array(
             1 => array('name'=>"Loews Hollywood Hotel", 'address'=>"1755 North Highland Ave", 'city'=>"Los Angelos", 'state'=>"California", 'zip'=>"90028", 'country'=>"USA", 'lat'=>'34.103186', 'lon'=>'-118.338974', 'text'=>"Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco...", 'reservations'=>'1.800.000.0000', 'phone'=>'1.999.999.9999', 'site'=>"http://www.hotelsite.com", 'offers'=>"http://www.hoteloffers.com", 'availability'=>'http://www.hotelavailability.com', 'photo'=>"{$toolboxIncludeUrl}module/GooglePlaces/view/google-places/toolbox/img/hotel-1.jpg"),
             2 => array('name'=>"Loews Hotel Le Concorde, Quebec", 'address'=>"1225 Cours du General-De Montcalm", 'city'=>"Quebec", 'state'=>"Quebec", 'zip'=>"G1R 4W6", 'country'=>"Canada", 'lat'=>'46.805251', 'lon'=>'-71.217384', 'text'=>"Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco...", 'reservations'=>'1.800.000.0000', 'phone'=>'1.999.999.9999', 'site'=>"http://www.hotelsite.com", 'offers'=>"http://www.hoteloffers.com", 'availability'=>'http://www.hotelavailability.com', 'photo'=>"{$toolboxIncludeUrl}module/GooglePlaces/view/google-places/toolbox/img/hotel-2.jpg"),
             3 => array('name'=>"Loews Hotel Vogue - Montreal", 'address'=>"1425 de La Montagne", 'city'=>"Montreal", 'state'=>"Quebec", 'zip'=>"H3G 1Z3", 'country'=>"Canada", 'lat'=>'45.498322', 'lon'=>'-73.575732', 'text'=>"Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco...", 'reservations'=>'1.800.000.0000', 'phone'=>'1.999.999.9999', 'site'=>"http://www.hotelsite.com", 'offers'=>"http://www.hoteloffers.com", 'availability'=>'http://www.hotelavailability.com', 'photo'=>"{$toolboxIncludeUrl}module/GooglePlaces/view/google-places/toolbox/img/hotel-3.jpg"),
             4 => array('name'=>"Loews Madison Hotel", 'address'=>"1177 Fifteenth St. NW", 'city'=>"Washington DC", 'state'=>"District of Columbia", 'zip'=>"20005", 'country'=>"USA", 'lat'=>'38.905377', 'lon'=>'-77.034068', 'group'=>'a', 'X'=>'92', 'Y'=>'10', 'text'=>"Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco...", 'reservations'=>'1.800.000.0000', 'phone'=>'1.999.999.9999', 'site'=>"http://www.hotelsite.com", 'offers'=>"http://www.hoteloffers.com", 'availability'=>'http://www.hotelavailability.com', 'photo'=>"{$toolboxIncludeUrl}module/GooglePlaces/view/google-places/toolbox/img/hotel-4.jpg"),
             5 => array('name'=>"Loews Miami Beach Hotel", 'address'=>"1601 Collins Ave", 'city'=>"Miami Beach", 'state'=>"Florida", 'zip'=>"33139", 'lat'=>'25.789182', 'lon'=>'-80.129221', 'text'=>"Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco...", 'reservations'=>'1.800.000.0000', 'phone'=>'1.999.999.9999', 'site'=>"http://www.hotelsite.com", 'offers'=>"http://www.hoteloffers.com", 'availability'=>'http://www.hotelavailability.com', 'photo'=>"{$toolboxIncludeUrl}module/GooglePlaces/view/google-places/toolbox/img/hotel-5.jpg"),
             6 => array('name'=>"Loews New Orleans Hotel", 'address'=>"300 Poydras St", 'city'=>"New Orleans", 'state'=>"Louisiana", 'zip'=>"70130", 'lat'=>'29.948394', 'lon'=>'-90.066109', 'text'=>"Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco...", 'reservations'=>'1.800.000.0000', 'phone'=>'1.999.999.9999', 'site'=>"http://www.hotelsite.com", 'offers'=>"http://www.hoteloffers.com", 'availability'=>'http://www.hotelavailability.com', 'photo'=>"{$toolboxIncludeUrl}module/GooglePlaces/view/google-places/toolbox/img/hotel-6.jpg"),
             7 => array('name'=>"Loews Philadelphia Hotel", 'address'=>"1200 Market St", 'city'=>"Philadelphia", 'state'=>"Pennsylvania", 'zip'=>"19107", 'lat'=>'39.951784', 'lon'=>'-75.160194', 'group'=>'A', 'text'=>"Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco...", 'reservations'=>'1.800.000.0000', 'phone'=>'1.999.999.9999', 'site'=>"http://www.hotelsite.com", 'offers'=>"http://www.hoteloffers.com", 'availability'=>'http://www.hotelavailability.com', 'photo'=>"{$toolboxIncludeUrl}module/GooglePlaces/view/google-places/toolbox/img/hotel-7.jpg"),
             8 => array('name'=>"Loews Portofino Bay Hotel at Universal Orlando", 'address'=>"5601 Universal Blvd", 'city'=>"Orlando", 'state'=>"Florida", 'zip'=>"32819", 'lat'=>'28.480456', 'group'=>'b', 'lon'=>'-81.460001', 'text'=>"Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco...", 'reservations'=>'1.800.000.0000', 'phone'=>'1.999.999.9999', 'site'=>"http://www.hotelsite.com", 'offers'=>"http://www.hoteloffers.com", 'availability'=>'http://www.hotelavailability.com', 'photo'=>"{$toolboxIncludeUrl}module/GooglePlaces/view/google-places/toolbox/img/hotel-8.jpg"),
             9 => array('name'=>"Loews Regency Hotel, NY", 'address'=>"540 Park Ave", 'city'=>"New York", 'state'=>"New York", 'zip'=>"10065", 'lat'=>'40.764614', 'lon'=>'-73.969358', 'group'=>'a', 'text'=>"Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco...", 'reservations'=>'1.800.000.0000', 'phone'=>'1.999.999.9999', 'site'=>"http://www.hotelsite.com", 'offers'=>"http://www.hoteloffers.com", 'availability'=>'http://www.hotelavailability.com', 'photo'=>"{$toolboxIncludeUrl}module/GooglePlaces/view/google-places/toolbox/img/hotel-9.jpg"),
            10 => array('name'=>"Loews Royal Pacific Resort at Universal Orlando", 'address'=>"6300 Hollywood Way", 'city'=>"Orlando", 'state'=>"Florida", 'zip'=>"32819", 'lat'=>'28.467548', 'lon'=>'-81.467015', 'group'=>'b', 'X'=>'-7', 'Y'=>'20', 'text'=>"Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco...", 'reservations'=>'1.800.000.0000', 'phone'=>'1.999.999.9999', 'site'=>"http://www.hotelsite.com", 'offers'=>"http://www.hoteloffers.com", 'availability'=>'http://www.hotelavailability.com', 'photo'=>"{$toolboxIncludeUrl}module/GooglePlaces/view/google-places/toolbox/img/hotel-10.jpg"),
            11 => array('name'=>"Loews Santa Monica Beach Hotel", 'address'=>"1700 Ocean Ave", 'city'=>"Santa Monica", 'state'=>"California", 'zip'=>"90401", 'lat'=>'34.00926', 'lon'=>'-118.492702', 'X'=>'80', 'Y'=>'10', 'text'=>"Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco...", 'reservations'=>'1.800.000.0000', 'phone'=>'1.999.999.9999', 'site'=>"http://www.hotelsite.com", 'offers'=>"http://www.hoteloffers.com", 'availability'=>'http://www.hotelavailability.com', 'photo'=>"{$toolboxIncludeUrl}module/GooglePlaces/view/google-places/toolbox/img/hotel.jpg"),
            12 => array('name'=>"Loews Coronado Bay - San Diego", 'address'=>"4000 Loews Coronado Bay Rd", 'city'=>"San Diego", 'state'=>"California", 'zip'=>"92118", 'lat'=>'32.631058', 'lon'=>'-117.135771', 'text'=>"Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco...", 'reservations'=>'1.800.000.0000', 'phone'=>'1.999.999.9999', 'site'=>"http://www.hotelsite.com", 'offers'=>"http://www.hoteloffers.com", 'availability'=>'http://www.hotelavailability.com', 'photo'=>"{$toolboxIncludeUrl}module/GooglePlaces/view/google-places/toolbox/img/hotel.jpg"),
            13 => array('name'=>"Loews Boston Hotel", 'address'=>"350 Stuart Street", 'city'=>"Boston", 'state'=>"Massachusetts", 'zip'=>"02116", 'lat'=>'42.349143', 'lon'=>'-71.07228', 'text'=>"Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco...", 'reservations'=>'1.800.000.0000', 'phone'=>'1.999.999.9999', 'site'=>"http://www.hotelsite.com", 'offers'=>"http://www.hoteloffers.com", 'availability'=>'http://www.hotelavailability.com', 'photo'=>"{$toolboxIncludeUrl}module/GooglePlaces/view/google-places/toolbox/img/hotel.jpg"),
            14 => array('name'=>"Loews Atlanta Hotel", 'address'=>"1065 Peachtree St NE", 'city'=>"Atlanta", 'state'=>"Georgia", 'zip'=>"30309", 'lat'=>'33.783366', 'lon'=>'-84.383596', 'text'=>"Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco...", 'reservations'=>'1.800.000.0000', 'phone'=>'1.999.999.9999', 'site'=>"http://www.hotelsite.com", 'offers'=>"http://www.hoteloffers.com", 'availability'=>'http://www.hotelavailability.com', 'photo'=>"{$toolboxIncludeUrl}module/GooglePlaces/view/google-places/toolbox/img/hotel.jpg"),
            15 => array('name'=>"Loews Annapolis Hotel", 'address'=>"126 West St", 'city'=>"Annapolis", 'state'=>"Maryland", 'zip'=>"21401", 'lat'=>'38.978329', 'lon'=>'-76.498242', 'group'=>'a', 'text'=>"Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco...", 'reservations'=>'1.800.000.0000', 'phone'=>'1.999.999.9999', 'site'=>"http://www.hotelsite.com", 'offers'=>"http://www.hoteloffers.com", 'availability'=>'http://www.hotelavailability.com', 'photo'=>"{$toolboxIncludeUrl}module/GooglePlaces/view/google-places/toolbox/img/hotel.jpg"),
            16 => array('name'=>"Loews Vanderbilt Hotel", 'address'=>"2100 West End Ave", 'city'=>"Nashville", 'city'=>"Tennessee", 'zip'=>"37203", 'lat'=>'36.150658', 'lon'=>'-86.802246', 'X'=>'58', 'Y'=>'10', 'text'=>"Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco...", 'reservations'=>'1.800.000.0000', 'phone'=>'1.999.999.9999', 'site'=>"http://www.hotelsite.com", 'offers'=>"http://www.hoteloffers.com", 'availability'=>'http://www.hotelavailability.com', 'photo'=>"{$toolboxIncludeUrl}module/GooglePlaces/view/google-places/toolbox/img/hotel.jpg"),
            17 => array('name'=>"Loews Ventana Canyon Resort", 'address'=>"7000 North Resort Dr", 'city'=>"Tucson", 'state'=>"Arizona", 'zip'=>"85750", 'lat'=>'32.330104', 'lon'=>'-110.850638', 'text'=>"Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco...", 'reservations'=>'1.800.000.0000', 'phone'=>'1.999.999.9999', 'site'=>"http://www.hotelsite.com", 'offers'=>"http://www.hoteloffers.com", 'availability'=>'http://www.hotelavailability.com', 'photo'=>"{$toolboxIncludeUrl}module/GooglePlaces/view/google-places/toolbox/img/hotel.jpg"),
            18 => array('name'=>"Loews Hard Rock Hotel", 'address'=>"5800 Universal Blvd", 'city'=>"Orlando", 'state'=>"Florida", 'zip'=>"32819", 'lat'=>'28.477198', 'lon'=>'-81.464852', 'group'=>'B', 'text'=>"Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco...", 'reservations'=>'1.800.000.0000', 'phone'=>'1.999.999.9999', 'site'=>"http://www.hotelsite.com", 'offers'=>"http://www.hoteloffers.com", 'availability'=>'http://www.hotelavailability.com', 'photo'=>"{$toolboxIncludeUrl}module/GooglePlaces/view/google-places/toolbox/img/hotel-18.jpg"),
            19 => array('name'=>"Loews Don CeSar", 'address'=>"3400 Gulf Blvd, St", 'city'=>"Pete Beach", 'state'=>"Florida", 'zip'=>"33706", 'lat'=>'27.709174', 'lon'=>'-82.737159', 'X'=>'68', 'Y'=>'10', 'text'=>"Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco...", 'reservations'=>'1.800.000.0000', 'phone'=>'1.999.999.9999', 'site'=>"http://www.hotelsite.com", 'offers'=>"http://www.hoteloffers.com", 'availability'=>'http://www.hotelavailability.com', 'photo'=>"{$toolboxIncludeUrl}module/GooglePlaces/view/google-places/toolbox/img/hotel.jpg")
        );
        $mapSettings = array(
            'hotels' => $hotels,
            'marker' => "{$toolboxIncludeUrl}module/GooglePlaces/view/google-places/toolbox/img/marker.png",
            'groupMarker' => "{$toolboxIncludeUrl}module/GooglePlaces/view/google-places/toolbox/img/group-marker.png",
            'noShowPlaces' => array(),
            'mapOptions' => array(
                'zoom' => 4,
                'mapTypeId' => 'LOEWS_MAP',  // google.maps.MapTypeId.ROADMAP
                'disableDefaultUI' => true,
                'scrollwheel' => false,
                'draggable' => false
            ),
            'featureOpts' => array(
                array(
                    'elementType' => 'labels',
                    'stylers' => array(array('visibility' => 'off'))
                ),
/*                array(
                    'featureType' => 'all',
                    'stylers' => array(array('color' => '#E7DFD6'))
                ),
*/
                array(
                    'featureType' => 'water',
                    'stylers' =>array(array('color' => '#76B4EF'))
                ),
                array(
                    'featureType' => 'landscape',
                    'stylers' => array(array('color' => '#E7DFD6'))
                ),
/*                array(
                    'featureType' => 'road.highway',
                    'stylers' => array(array('color' => '#F8EFE7'))
                )
*/
            )
        );
        return new JsonModel($mapSettings);
    }

    //========================================================================================================================
    /**
     * getDesignerViewAction function
     * 
     * @access public
     * @return mixed $view
    */
    public function getDesignerViewAction()
    {
        $view = new \Zend\View\Model\ViewModel;
        $view->setTerminal(true);
        $view->setTemplate('google-places/toolbox/designer');
        $view->toolboxIncludeUrl = $this->getServiceLocator()->get('MergedConfig')->get(array('paths', 'toolboxIncludeUrl'), '');
        return $view;
    }
}