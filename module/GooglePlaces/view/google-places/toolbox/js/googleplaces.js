function createBubble(block)
{
    block.bubble = new InfoBubble({
        content: '<div style="width:200px">Some label</div>',
        shadowStyle: 0,
        padding: 10,
        backgroundColor: '#FFF',
        borderRadius: 15,
        arrowSize: 30,
        borderWidth: 3,
        borderColor: '#7B7B7B',
        disableAutoPan: true,
        hideCloseButton: false,
        arrowPosition: 75,
        backgroundClassName: 'info-holder',
        arrowStyle: 1,
        maxWidth: 500,
        maxHeight: 140
    });
}

//===========================================================================================================
function changeViewStyle(block)
{
    var bvs = $('#button-view-style');
    if (bvs.text() == 'List View') {
        $('#map-view-panel').css({display:'none'});
        var $lvp = $('#list-view-panel');
        if (!$lvp.html()) {
            for (var i in block.hotels) {
                $lvp.append(buildHotelInfo(block, i));
            }
        }
        $lvp.css({display:'inherit'});
    } else {
        $('#map-view-panel').css({display:'inherit'});
        $('#list-view-panel').css({display:'none'});
    }
    bvs.text(bvs.text()=='List View'?'Map View':'List View');
}

//===========================================================================================================
function zoom(block, marker)
{
    var pos = marker.getPosition();
    var zoom = block.map.getZoom();
    if (zoom == 4) {
        block.featureOpts[0].stylers[0].visibility = 'on';
        block.mapOptions.scrollwheel = block.mapOptions.draggable = true;
        displayMap(block);
        block.map.setCenter(pos);
        block.map.setZoom(8);
    } else if (zoom == 8) {
        addPlaces(block, pos);
        block.map.setZoom(15);
    } else {
        block.bubble.content = buildHotelInfo(block, marker.hotelId);
        block.bubble.open(block.map, marker);
    }
}

//===========================================================================================================
function addHotelMarker(block, i, nInGroup)
{
    var hotel = block.hotels[i];
    var pos = new google.maps.LatLng(parseFloat(hotel.lat), parseFloat(hotel.lon));
    // City name
    var marker = new MarkerWithLabel({
        position: pos,
        draggable: false,
//        raiseOnDrag: false,
        map: block.map,
        labelContent: hotel.city,
        labelAnchor: hotel.X ? new google.maps.Point(parseInt(hotel.X), parseInt(hotel.Y)) : new google.maps.Point(-3, 10),
        labelClass: "cityLabel",
        icon: nInGroup ? block.hotelGroupMarker : block.hotelMarker,
//        icon: 'http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld='+i+'|000000|FFFFFF'
        labelStyle: {opacity: (block.featureOpts[0].stylers[0].visibility == 'on' ? 0 : 1)}
    });
    marker.hotelId = i;
    google.maps.event.addListener(marker, 'click', function() {
        zoom(block, marker);
    });

    if (nInGroup) {
        var mark = new MarkerWithLabel({
            position: pos,
            draggable: false,
//            raiseOnDrag: false,
            map: block.map,
            labelContent: nInGroup,
            labelAnchor: new google.maps.Point(2, 45),
            labelClass: "hotels-in-group",
            icon: null,
//            icon: 'http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld='+i+'|000000|FFFFFF'
            labelStyle: {opacity: (block.featureOpts[0].stylers[0].visibility == 'on' ? 0 : 1)}
        });
        mark.hotelId = i;
        google.maps.event.addListener(mark, 'click', function() {
            zoom(block, mark);
        });
    }
/*
    var icon = new google.maps.MarkerImage(
        'http://localhost/orlando.png',
        null, 
        // The origin for this image is 0,0.
        new google.maps.Point(0, 0),
        // The anchor for this image is the base of the flagpole at 0,32.
        hotel.X ? new google.maps.Point(parseInt(hotel.X), parseInt(hotel.Y)) : new google.maps.Point(-3, 7)
        );
    new google.maps.Marker({
        position: pos,
        map: map,
        icon: icon
    });
*/
}

//===========================================================================================================
function buildHotelInfo(block, i) {
    var hotel = block.hotels[i];
    //Using a template to create InfoWindow content
    var $info = $('#info-box').clone();
    $info.find('.info-box').attr({id:i});
    $info.find('img').attr({src:hotel.photo});
    $info.find('.hotel-name').text(hotel.name);
    $info.find('.info-text').text(hotel.text);
    $info.find('.info-phones').html('<b>Reservations:</b> '+hotel.reservations+'<br/><b>Phone:</b> '+hotel.phone);
    $info.find('.info-address').html(hotel.address+'<br/>'+addressLine2(hotel));
    return $info.html();
}

//===========================================================================================================
function addPlaces(block, pos)
{
//    pos = new google.maps.LatLng(-33.8665433, 151.1956316);
    block.map.setCenter(pos);
    var request = {
        location: pos,
        radius: 2000,
        types: ['store']
    };
    var plSvc = new google.maps.places.PlacesService(block.map);
    plSvc.nearbySearch(request, function(results, status) {
        if (status == google.maps.places.PlacesServiceStatus.OK)
            for (var i=0; i<results.length; ++i)
                createPlaceMarker(block, results[i]);
    });
}

//===========================================================================================================
// Creates a single place marker for surrounding Google Places
function createPlaceMarker(block, place) {
    // Check if no 'blacklist' word occurs in the place name
    for (var j=0; j<block.noShowPlaces.length; ++j) {
        if (place.name.indexOf(block.noShowPlaces[j]) > -1)
            return;
    }
    var marker = new google.maps.Marker({
        map: block.map,
        position: place.geometry.location,
        title: place.name,
        icon: 'http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=A|FF0000|FFFFFF'
    });
    google.maps.event.addListener(marker, 'click', function() {
        block.infowindow.setContent(place.name);
        block.infowindow.open(block.map, this);
    });
}

//===========================================================================================================
function addressLine2(hotel)
{
    return hotel.city+', '+hotel.state+' '+hotel.zip;
}

//===========================================================================================================
// This function is to display latitude and longitude of a hotel.
// It does not get called in a usual cource of business
function geocode(hotel)
{
    if (!geocoder)
        geocoder = new google.maps.Geocoder();
    geocoder.geocode({'address': hotel.address+', '+addressLine2(hotel)}, function(results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
            alert(hotel.name+'  '+results[0].geometry.location.toString());
        }
        alert('Geocode was not successful for the following reason: ' + status);
    });
}

//===========================================================================================================
function displayMap(block) {
    block.mapOptions.center = new google.maps.LatLng(block.center[0], block.center[1]);
    block.map = new google.maps.Map($('#map-canvas-'+block.nameSpace)[0], block.mapOptions);
    var styledMapOptions = {name:block.mapOptions.mapTypeId};
    var customMapType = new google.maps.StyledMapType(block.featureOpts, styledMapOptions);
    block.map.mapTypes.set(block.mapOptions.mapTypeId, customMapType);
    var grp, groups = {};
    // Add markers with no groupping first
    for (var i in block.hotels) {
        // This 'if' block is for groupping markers like LOEWS maps
        if (!block.mapOptions.scrollwheel && block.hotels[i].group) {
            grp = block.hotels[i].group.toLowerCase();
            if (!groups[grp]) {
                groups[grp] = [0,0];
            }
            // If this is the actual hotel to place the marker on
            if (block.hotels[i].group == block.hotels[i].group.toUpperCase()) {
                groups[grp][0] = i;
            }
            groups[grp][1]++;
            continue;
        }
        addHotelMarker(block, i);
    }
    // Add group markers
    for (grp in groups)
        addHotelMarker(block, groups[grp][0], groups[grp][1]);
}

//===========================================================================================================
function displayTable(block)
{
    var $tbl = $('#hotels-table');
    var hotel, $elm;
    for (var i in block.hotels) {
        hotel = block.hotels[i];
        $elm = $('<tr><td>'+hotel.city+', '+hotel.state+'</td><td>'+hotel.name+'</td><td><a href="">Visit site</a> | <a href="">Check availability</a></td></tr>');
        $tbl.append($elm);
    }
}
/*
//===========================================================================================================
// Peforms all necessary initialization
$(document).ready(function() {
//    $.get('/phoenixtng/googlePlaces/sockets/getSettings', function(serverPassedData) {
        hotels = serverPassedData.hotels;
        noShowPlaces = serverPassedData.noShowPlaces;
        featureOpts = serverPassedData.featureOpts;
        mapOptions = serverPassedData.mapOptions;
        mapOptions.center = new google.maps.LatLng(38, -93);  // Kansass - center of US
//       mapOptions.mapTypeControlOptions = {mapTypeIds:[google.maps.MapTypeId.ROADMAP, MY_MAPTYPE_ID]}
        hotelMarker = serverPassedData.marker;
        hotelGroupMarker = serverPassedData.groupMarker;
        displayMap();
        displayTable();
//    });
});
*/