<?php
/**
 * GooglePlaces ToolboxController
 *
 * The ToolboxController for the GooglePlaces Module
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

class ToolboxController extends \Toolbox\Mvc\Controller\AbstractToolboxController
{
    /**
     * The base admin level for actions. Each action (or task within an action)
     * can require a higher adminLevel, but this is the lowest allowed.
     * 
     * @var string
     */    
    protected $baseAdminLevel = \Users\Service\Acl::PERMISSIONS_RESOURCE_ADMIN;
        
    public function indexAction() 
    {
        $blockId = "loews";
        $mrgdConf = $this->getServiceLocator()->get('MergedConfig');
        $toolboxIncludeUrl = $mrgdConf->get(array('paths', 'toolboxIncludeUrl'), '');
/*
        $hotels = array(
             1 => array('name'=>"Loews Hollywood Hotel", 'address'=>"1755 North Highland Ave", 'city'=>"Los Angelos", 'state'=>"California", 'zip'=>"90028", 'country'=>"USA", 'latitude'=>'34.103186', 'longitude'=>'-118.338974', 'text'=>"Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco...", 'reservations'=>'1.800.000.0000', 'phone'=>'1.999.999.9999', 'site'=>"http://www.hotelsite.com", 'offers'=>"http://www.hoteloffers.com", 'availability'=>'http://www.hotelavailability.com', 'photo'=>"{$toolboxIncludeUrl}module/GooglePlaces/view/google-places/toolbox/img/hotel-1.jpg"),
             2 => array('name'=>"Loews Hotel Le Concorde, Quebec", 'address'=>"1225 Cours du General-De Montcalm", 'city'=>"Quebec", 'state'=>"Quebec", 'zip'=>"G1R 4W6", 'country'=>"Canada", 'latitude'=>'46.805251', 'longitude'=>'-71.217384', 'text'=>"Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco...", 'reservations'=>'1.800.000.0000', 'phone'=>'1.999.999.9999', 'site'=>"http://www.hotelsite.com", 'offers'=>"http://www.hoteloffers.com", 'availability'=>'http://www.hotelavailability.com', 'photo'=>"{$toolboxIncludeUrl}module/GooglePlaces/view/google-places/toolbox/img/hotel-2.jpg"),
             3 => array('name'=>"Loews Hotel Vogue - Montreal", 'address'=>"1425 de La Montagne", 'city'=>"Montreal", 'state'=>"Quebec", 'zip'=>"H3G 1Z3", 'country'=>"Canada", 'latitude'=>'45.498322', 'longitude'=>'-73.575732', 'text'=>"Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco...", 'reservations'=>'1.800.000.0000', 'phone'=>'1.999.999.9999', 'site'=>"http://www.hotelsite.com", 'offers'=>"http://www.hoteloffers.com", 'availability'=>'http://www.hotelavailability.com', 'photo'=>"{$toolboxIncludeUrl}module/GooglePlaces/view/google-places/toolbox/img/hotel-3.jpg"),
             4 => array('name'=>"Loews Madison Hotel", 'address'=>"1177 Fifteenth St. NW", 'city'=>"Washington DC", 'state'=>"District of Columbia", 'zip'=>"20005", 'country'=>"USA", 'latitude'=>'38.905377', 'longitude'=>'-77.034068', 'group'=>'a', 'X'=>'92', 'Y'=>'10', 'text'=>"Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco...", 'reservations'=>'1.800.000.0000', 'phone'=>'1.999.999.9999', 'site'=>"http://www.hotelsite.com", 'offers'=>"http://www.hoteloffers.com", 'availability'=>'http://www.hotelavailability.com', 'photo'=>"{$toolboxIncludeUrl}module/GooglePlaces/view/google-places/toolbox/img/hotel-4.jpg"),
             5 => array('name'=>"Loews Miami Beach Hotel", 'address'=>"1601 Collins Ave", 'city'=>"Miami Beach", 'state'=>"Florida", 'zip'=>"33139", 'latitude'=>'25.789182', 'longitude'=>'-80.129221', 'text'=>"Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco...", 'reservations'=>'1.800.000.0000', 'phone'=>'1.999.999.9999', 'site'=>"http://www.hotelsite.com", 'offers'=>"http://www.hoteloffers.com", 'availability'=>'http://www.hotelavailability.com', 'photo'=>"{$toolboxIncludeUrl}module/GooglePlaces/view/google-places/toolbox/img/hotel-5.jpg"),
             6 => array('name'=>"Loews New Orleans Hotel", 'address'=>"300 Poydras St", 'city'=>"New Orleans", 'state'=>"Louisiana", 'zip'=>"70130", 'latitude'=>'29.948394', 'longitude'=>'-90.066109', 'text'=>"Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco...", 'reservations'=>'1.800.000.0000', 'phone'=>'1.999.999.9999', 'site'=>"http://www.hotelsite.com", 'offers'=>"http://www.hoteloffers.com", 'availability'=>'http://www.hotelavailability.com', 'photo'=>"{$toolboxIncludeUrl}module/GooglePlaces/view/google-places/toolbox/img/hotel-6.jpg"),
             7 => array('name'=>"Loews Philadelphia Hotel", 'address'=>"1200 Market St", 'city'=>"Philadelphia", 'state'=>"Pennsylvania", 'zip'=>"19107", 'latitude'=>'39.951784', 'longitude'=>'-75.160194', 'group'=>'A', 'text'=>"Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco...", 'reservations'=>'1.800.000.0000', 'phone'=>'1.999.999.9999', 'site'=>"http://www.hotelsite.com", 'offers'=>"http://www.hoteloffers.com", 'availability'=>'http://www.hotelavailability.com', 'photo'=>"{$toolboxIncludeUrl}module/GooglePlaces/view/google-places/toolbox/img/hotel-7.jpg"),
             8 => array('name'=>"Loews Portofino Bay Hotel at Universal Orlando", 'address'=>"5601 Universal Blvd", 'city'=>"Orlando", 'state'=>"Florida", 'zip'=>"32819", 'latitude'=>'28.480456', 'group'=>'b', 'longitude'=>'-81.460001', 'text'=>"Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco...", 'reservations'=>'1.800.000.0000', 'phone'=>'1.999.999.9999', 'site'=>"http://www.hotelsite.com", 'offers'=>"http://www.hoteloffers.com", 'availability'=>'http://www.hotelavailability.com', 'photo'=>"{$toolboxIncludeUrl}module/GooglePlaces/view/google-places/toolbox/img/hotel-8.jpg"),
             9 => array('name'=>"Loews Regency Hotel, NY", 'address'=>"540 Park Ave", 'city'=>"New York", 'state'=>"New York", 'zip'=>"10065", 'latitude'=>'40.764614', 'longitude'=>'-73.969358', 'group'=>'a', 'text'=>"Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco...", 'reservations'=>'1.800.000.0000', 'phone'=>'1.999.999.9999', 'site'=>"http://www.hotelsite.com", 'offers'=>"http://www.hoteloffers.com", 'availability'=>'http://www.hotelavailability.com', 'photo'=>"{$toolboxIncludeUrl}module/GooglePlaces/view/google-places/toolbox/img/hotel-9.jpg"),
            10 => array('name'=>"Loews Royal Pacific Resort at Universal Orlando", 'address'=>"6300 Hollywood Way", 'city'=>"Orlando", 'state'=>"Florida", 'zip'=>"32819", 'latitude'=>'28.467548', 'longitude'=>'-81.467015', 'group'=>'b', 'X'=>'-7', 'Y'=>'20', 'text'=>"Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco...", 'reservations'=>'1.800.000.0000', 'phone'=>'1.999.999.9999', 'site'=>"http://www.hotelsite.com", 'offers'=>"http://www.hoteloffers.com", 'availability'=>'http://www.hotelavailability.com', 'photo'=>"{$toolboxIncludeUrl}module/GooglePlaces/view/google-places/toolbox/img/hotel-10.jpg"),
            11 => array('name'=>"Loews Santa Monica Beach Hotel", 'address'=>"1700 Ocean Ave", 'city'=>"Santa Monica", 'state'=>"California", 'zip'=>"90401", 'latitude'=>'34.00926', 'longitude'=>'-118.492702', 'X'=>'73', 'Y'=>'10', 'text'=>"Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco...", 'reservations'=>'1.800.000.0000', 'phone'=>'1.999.999.9999', 'site'=>"http://www.hotelsite.com", 'offers'=>"http://www.hoteloffers.com", 'availability'=>'http://www.hotelavailability.com', 'photo'=>"{$toolboxIncludeUrl}module/GooglePlaces/view/google-places/toolbox/img/hotel.jpg"),
            12 => array('name'=>"Loews Coronado Bay - San Diego", 'address'=>"4000 Loews Coronado Bay Rd", 'city'=>"San Diego", 'state'=>"California", 'zip'=>"92118", 'latitude'=>'32.631058', 'longitude'=>'-117.135771', 'text'=>"Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco...", 'reservations'=>'1.800.000.0000', 'phone'=>'1.999.999.9999', 'site'=>"http://www.hotelsite.com", 'offers'=>"http://www.hoteloffers.com", 'availability'=>'http://www.hotelavailability.com', 'photo'=>"{$toolboxIncludeUrl}module/GooglePlaces/view/google-places/toolbox/img/hotel.jpg"),
            13 => array('name'=>"Loews Boston Hotel", 'address'=>"350 Stuart Street", 'city'=>"Boston", 'state'=>"Massachusetts", 'zip'=>"02116", 'latitude'=>'42.349143', 'longitude'=>'-71.07228', 'text'=>"Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco...", 'reservations'=>'1.800.000.0000', 'phone'=>'1.999.999.9999', 'site'=>"http://www.hotelsite.com", 'offers'=>"http://www.hoteloffers.com", 'availability'=>'http://www.hotelavailability.com', 'photo'=>"{$toolboxIncludeUrl}module/GooglePlaces/view/google-places/toolbox/img/hotel.jpg"),
            14 => array('name'=>"Loews Atlanta Hotel", 'address'=>"1065 Peachtree St NE", 'city'=>"Atlanta", 'state'=>"Georgia", 'zip'=>"30309", 'latitude'=>'33.783366', 'longitude'=>'-84.383596', 'text'=>"Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco...", 'reservations'=>'1.800.000.0000', 'phone'=>'1.999.999.9999', 'site'=>"http://www.hotelsite.com", 'offers'=>"http://www.hoteloffers.com", 'availability'=>'http://www.hotelavailability.com', 'photo'=>"{$toolboxIncludeUrl}module/GooglePlaces/view/google-places/toolbox/img/hotel.jpg"),
            15 => array('name'=>"Loews Annapolis Hotel", 'address'=>"126 West St", 'city'=>"Annapolis", 'state'=>"Maryland", 'zip'=>"21401", 'latitude'=>'38.978329', 'longitude'=>'-76.498242', 'group'=>'a', 'text'=>"Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco...", 'reservations'=>'1.800.000.0000', 'phone'=>'1.999.999.9999', 'site'=>"http://www.hotelsite.com", 'offers'=>"http://www.hoteloffers.com", 'availability'=>'http://www.hotelavailability.com', 'photo'=>"{$toolboxIncludeUrl}module/GooglePlaces/view/google-places/toolbox/img/hotel.jpg"),
            16 => array('name'=>"Loews Vanderbilt Hotel", 'address'=>"2100 West End Ave", 'city'=>"Nashville", 'city'=>"Tennessee", 'zip'=>"37203", 'latitude'=>'36.150658', 'longitude'=>'-86.802246', 'X'=>'58', 'Y'=>'10', 'text'=>"Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco...", 'reservations'=>'1.800.000.0000', 'phone'=>'1.999.999.9999', 'site'=>"http://www.hotelsite.com", 'offers'=>"http://www.hoteloffers.com", 'availability'=>'http://www.hotelavailability.com', 'photo'=>"{$toolboxIncludeUrl}module/GooglePlaces/view/google-places/toolbox/img/hotel.jpg"),
            17 => array('name'=>"Loews Ventana Canyon Resort", 'address'=>"7000 North Resort Dr", 'city'=>"Tucson", 'state'=>"Arizona", 'zip'=>"85750", 'latitude'=>'32.330104', 'longitude'=>'-110.850638', 'text'=>"Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco...", 'reservations'=>'1.800.000.0000', 'phone'=>'1.999.999.9999', 'site'=>"http://www.hotelsite.com", 'offers'=>"http://www.hoteloffers.com", 'availability'=>'http://www.hotelavailability.com', 'photo'=>"{$toolboxIncludeUrl}module/GooglePlaces/view/google-places/toolbox/img/hotel.jpg"),
            18 => array('name'=>"Loews Hard Rock Hotel", 'address'=>"5800 Universal Blvd", 'city'=>"Orlando", 'state'=>"Florida", 'zip'=>"32819", 'latitude'=>'28.477198', 'longitude'=>'-81.464852', 'group'=>'B', 'text'=>"Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco...", 'reservations'=>'1.800.000.0000', 'phone'=>'1.999.999.9999', 'site'=>"http://www.hotelsite.com", 'offers'=>"http://www.hoteloffers.com", 'availability'=>'http://www.hotelavailability.com', 'photo'=>"{$toolboxIncludeUrl}module/GooglePlaces/view/google-places/toolbox/img/hotel-18.jpg"),
            19 => array('name'=>"Loews Don CeSar", 'address'=>"3400 Gulf Blvd, St", 'city'=>"Pete Beach", 'state'=>"Florida", 'zip'=>"33706", 'latitude'=>'27.709174', 'longitude'=>'-82.737159', 'X'=>'61', 'Y'=>'10', 'text'=>"Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco...", 'reservations'=>'1.800.000.0000', 'phone'=>'1.999.999.9999', 'site'=>"http://www.hotelsite.com", 'offers'=>"http://www.hoteloffers.com", 'availability'=>'http://www.hotelavailability.com', 'photo'=>"{$toolboxIncludeUrl}module/GooglePlaces/view/google-places/toolbox/img/hotel.jpg")
        );
 * 
 */
        $mapOptions = array(
            'mapTypeId' => 'LOEWS_MAP',  // google.maps.MapTypeId.ROADMAP
            'zoom' => 4,
            'disableDefaultUI' => true,
            'scrollwheel' => false,
            'draggable' => false
        );
        $featureOpts = array(
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
        );
        $mapSettings = array(
//            'nameSpace' => $blockId,
//            'hotels' => $hotels,
            'hotelMarker' => "{$toolboxIncludeUrl}module/GooglePlaces/view/google-places/toolbox/img/marker.png",
            'hotelGroupMarker' => "{$toolboxIncludeUrl}module/GooglePlaces/view/google-places/toolbox/img/group-marker.png",
            'placeMarker' => null,
            'mapMarker' => null,
            'noShowPlaces' => array(),
            'center' => array(38, -93),
            'mapOptions' => $mapOptions,
            'featureOpts' => $featureOpts
        );

        // Get viewManager
        $viewManager = $this->getServiceLocator()->get('view-manager');
        $view = new \Zend\View\Model\ViewModel;
        $view->setVariables($viewManager->getViewModel()->getVariables());

        $json = json_encode($mapSettings);
        // This is the unfortunate part that has to be done on the server to prevent JS name conflicts in a browser.
        // This has to be done in each 'block' extending module (at least the first line bellow
        // that wraps all the settings with a uniquely named object
        $view->jScript = "var $blockId=$json;";
        // Following lines is an additional client-side initialization that may
        // or may not be needed dependind on the 'block' implementation details.
        $view->jScript .= //"$blockId.mapOptions.center = new google.maps.LatLng({$mapSettings['center'][0]}, {$mapSettings['center'][1]});"
            "createBubble($blockId);
            displayMap($blockId);
            displayTable($blockId);";
        $view->blockId = $blockId;
/*
        $con = mysqli_connect('localhost','root','','htb_phoenixtng');
        $json = $con->real_escape_string($json);
        $con->query("UPDATE settings SET parameters='$json' WHERE id=1");
*/
        return $view;
    }
}
