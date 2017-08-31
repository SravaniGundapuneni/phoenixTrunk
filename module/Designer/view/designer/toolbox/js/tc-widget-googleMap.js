document.write("<script src='https://maps.googleapis.com/maps/api/js?libraries=places&client=gme-loewshotels'><\/script>");
document.write("<script src='"+toolboxIncludeUrl+"module/GooglePlaces/view/google-places/toolbox/js/infobubble.js'><\/script>");
document.write("<script src='"+toolboxIncludeUrl+"module/GooglePlaces/view/google-places/toolbox/js/markerwithlabel.js'><\/script>");
document.write("<script src='"+toolboxIncludeUrl+"module/GooglePlaces/view/google-places/toolbox/js/markerclusterer.js'><\/script>");
// Widget class googleMap
var markerArray = [];
tcWidgets.prototype.googleMap = function()
{
	this.cap = 'Google map';
	this.getClassName = function() {return 'googleMap'};

	var block = {
//		hotelMarker: "{$toolboxIncludeUrl}module/GooglePlaces/view/google-places/toolbox/img/marker.png",
		center: [38, -93],
		mapOptions: {
			zoom: 4,
			mapTypeId: google.maps.MapTypeId.ROADMAP
		}
	};
		var markerID = '';
	var placeMarkers = {};

	this.getParameters = function() { return block; }
	this.setParameters = function(parameters) { block = parameters || block; return this }

	var wrapperId;

	// custom loews zoom
	$("#mapZoomControls .zoomOut").click(function(){
			var currentZoomLevel = block.map.getZoom();
			if(currentZoomLevel != 0 && currentZoomLevel > 4 ){ block.map.setZoom(currentZoomLevel - 1); }
	});
	$("#mapZoomControls .zoomIn").click(function(){
			var currentZoomLevel = block.map.getZoom();
			if(currentZoomLevel != 0){ block.map.setZoom(currentZoomLevel + 1); }
	});
	// RESET MAP
	$("a.resetMap").click(function(){
			$('.mapOverlay').css({'z-index':'0'});
			$('#loewsMap').addClass('fullwidth');
			$(".mapSearchResults,.mapOverlay .close").slideUp("fast");
			$(".placesWrapper").removeClass("withSearch");
			$(".mapOverlay input").val("");
			clearPlaces();
			widgets.items.loewsMap.insertMap($('#loewsMap'));
	});

	//===========================================================================================================
	// this is a list of properties to be exposed in the property box
	this.properties = {
			id: {caption:'ID', type:'text', get:function($obj){return $obj.attr('id')}, set:function($obj, val){$obj.attr({id:val})}},
			class: {caption:'Class', type:'text', get:function($obj){return $obj.attr('class')}, set:function($obj, val){$obj.attr('class', val)}},
			tooltip: {caption:'Tooltip', type:'text', get:function($obj){return $obj.attr('title')}, set:function($obj, val){$obj.attr('title', val)}},
			width: {caption:'Width', type:'text', get:function($obj){return $obj.css('width')}, set:function($obj, val){$obj.css('width', val)}},
			height: {caption:'Height', type:'text', get:function($obj){return $obj.css('height')}, set:function($obj, val){$obj.css('height', val)}}
	};

	//===========================================================================================================
	// this method returns default 'image' tag
	this.getElement = function() {
		wrapperId = 'googleMap1';//self.getAvailableId('googleMap');
		var $elm = $("<div class='map-canvas' style='height:300px; width:500px' id='"+wrapperId+"'></div>");
		createBubble();
		displayMap($elm);
		widgets.items[wrapperId] = this;
		return $elm;
	};

	//===========================================================================================================
	this.insertMap = function($elm) {
		createBubble();
		displayMap($elm);
		widgets.items[$elm.attr('id')] = this;
		return $elm;
	}

	//===========================================================================================================
	this.resetMap = function(id) {
		block.featureOpts[0].stylers[0].visibility = 'off';
		displayMap($('#'+id));
	}

	//===========================================================================================================
	this.showPlaces = function(types)
	{
		//clearPlaces();

		if (types == 'pipers-picks') {
			addFeaturedPlaces(); //ADD SOME PLACES DEFINED IN DATABASE
			return;
		}

		$(".placesWrapper").removeClass("withSearch");

		var search = {
			types: types,
			// isNearbySearch is used for the overlay buttons such as 'view top attractions'
			isNearbySearch: true
		}
		block.bubble.close();
				$(".mapOverlay .close").slideUp("fast");
		findAroundHere(search);
	};

	//===========================================================================================================
	function addPlaces(pos, types)
	{
		block.map.setCenter(pos);
		var request = {
			location: pos,
			radius: 2000,
			types: types //['store']
		};
		var plSvc = new google.maps.places.PlacesService(block.map);
		plSvc.nearbySearch(request, function(results, status) {
			if (status == google.maps.places.PlacesServiceStatus.OK)
				for (var i=0; i<results.length; ++i)
					createPlaceMarker(results[i]);
		});
	}

	//===========================================================================================================
	this.clickLocation = function(location)
	{
		// this function is used when you click the property in the search propery bar
		block.bubble.setMap(null); //close the open infobubbles
		var locationSplit=location.split(",");
		var position=new google.maps.LatLng(locationSplit[0], locationSplit[1]);
		var marker = new MarkerWithLabel({
			position: position,
			propSearch: true //this was used to tell zoom that it is being called from a location click, instead of somewhere else like clicking on the hotel marker
		});
		$(".mapSearchResults").html('');
		zoom(marker);
	};

	//===========================================================================================================
	// REMOVE ALL MARKERS BUT CLIENT HOTELS FROM MAP
	function clearPlaces()
	{
		block.bubble.close();
		for (var i in placeMarkers) {
			placeMarkers[i].setMap(null);
			delete placeMarkers[i];
		}
		placeMarkers = {};
	}

	//===========================================================================================================
	function createBubble()
	{
		block.bubble = new InfoBubble({
			minWidth:456,
						minHeight:182,
			maxWidth:456,
			maxHeight:182,
			content: '',
			padding: 7,
			backgroundColor: '#FFF',
			arrowSize: 7,
			borderRadius: 0,
			borderWidth: 0,
			disableAutoPan: true,
			hideCloseButton: false,
			arrowPosition: 50,
			backgroundClassName: 'info-holder',
			arrowStyle: 0,
			shadowStyle: 3,
			disableAnimation: 1
		});
	}

	//===========================================================================================================
	function changeViewStyle(style)
	{
		var bvs = $('#button-view-style');
		if (style == 'list') {
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
	function findAroundHere(search) {
		// console.log(search);
		//clearPlaces();

		// if it is a nearby search, google places is given a list of things to find around here, such as 'aquarium','zoo','museum','art_gallery'
		if (search.isNearbySearch==true) {
			var request = {
				location: block.map.getCenter(),
				radius: 2000,
				types: search.types //search is from the large overlay buttons ['store']
			};
			var service = new google.maps.places.PlacesService(block.map);
			service.nearbySearch(request, callbackfirst);
		} else { // else search using text from the search box in the map overlay
			var request = {
				location: block.map.getCenter(),
				radius: '2000',
				query: search
			};
			service = new google.maps.places.PlacesService(block.map);
			service.textSearch(request, callbackfirst);
		}

		// callbackfirst is to get basic information for a google place
		function callbackfirst(results, statusone) {
			//console.log(results);
			if (statusone != google.maps.places.PlacesServiceStatus.OK) {
				$(".notfound").slideDown(500).delay(3000).slideUp(1000);
				return;
			}

			$(".mapSearchResults").empty();
			//$(".mapSearchResults,.mapOverlay .close").slideDown("fast");
						$(".mapSearchResults").slideDown("fast");
			$(".placesWrapper").addClass("withSearch");
			$(".mapOverlay .close .clearResults").click(function() {
				$(".mapSearchResults,.mapOverlay .close").slideUp("fast");
				$(".placesWrapper").removeClass("withSearch");
				$(".mapOverlay input").val("");
				clearPlaces();
			});

			var ind = 0;
			function loop() {
				if (ind >= results.length)
								{
									$(".mapOverlay .close").slideDown("fast");
									return;
								}

				// console.log(results);
				//var markerReturn = createPlaceMarker(results[ind]);
				var result = results[ind++];
				var request = {
					reference: result.reference
				};
				service.getDetails(request, callbacksecond);
				setTimeout(loop, 300);
			}
			loop();
		}

		// callbacksecond is to more detailed information for a google place
		function callbacksecond(place, statustwo) {
			// WHEN YOU HOVER THE RESULTS ITEMS, OPEN THEIR ASSOCIATED MARKER
//			 $(".mapSearchResultsItem").click(function(event) {
//				var markerTag=$(this).attr("id");
//				var current_marker = '';
//				for(var i = 0; i < markerArray.length; i++)
//				{
//					if(markerArray[i].id == markerTag)
//						current_marker = markerArray[i];
//				}
//				google.maps.event.trigger(getPlaceMarker(current_marker), 'click');
//			});
			if (statustwo != google.maps.places.PlacesServiceStatus.OK) {
				console.log(status);
				return;
			}
			else
			{
				markerArray.push(place);
				createPlaceMarker(place);
			}

			// placeMarker=createPlaceMarker(place);
			var pid = '"'+place.id+'"';
			var html = "<div class='mapSearchResultsItem' id="+place.id+" onClick='openPlaceMarker("+pid+")'>";
			if (place.icon) {
				html += "<img style='float:left;width:30px;height:30px' src='" + place.icon + "'>";
			}
			html += "<div class='rightContent'>";
			if (place.name) {
				html += "<h3>" + place.name + "</h3>";
			}
			html += "<p>"
			if (place.formatted_address) {
				html += place.formatted_address + "<br>";
			}
			if (place.formatted_phone_number) {
				html += place.formatted_phone_number + "<br>";
			}
			if (place.website) {
				// simplify url
				var simpleUrl = place.website.replace("http://","");
				simpleUrl = simpleUrl.replace("www.","");
				simpleUrl = simpleUrl.replace(/\/$/, "");
				html += "<a href='" + place.website + "' target='_blank'>" + simpleUrl + "</a>"
			}
			html += "</div></div>";

			$(".mapSearchResults").append(html);
		}
	}

	//===========================================================================================================
	function zoom(marker)
	{
		// if (zoom == 4 || marker.propSearch==true) { //propsearch is a variable used when you are using the search properties box under the map
				console.log(marker);
		var pos = marker.position;
		var zoom = block.map.getZoom();
		block.featureOpts[0].stylers[0].visibility = "on";
		var styledMapOptions = {name:block.mapOptions.mapTypeId};
		var customMapType = new google.maps.StyledMapType(block.featureOpts, styledMapOptions);
		block.map.mapTypes.set(block.mapOptions.mapTypeId, customMapType);
		block.mapOptions.scrollwheel = block.mapOptions.draggable = true;
		block.map.setOptions(block.mapOptions);
		block.map.setCenter(pos);
		block.map.setZoom(15);

		block.bubble.content = buildHotelInfo(marker.hotelId);
		// block.bubble.setMaxWidth(456);
		// block.bubble.setMaxHeight(182);
		 block.bubble.setMinWidth(456);
		 block.bubble.setMinHeight(182);
		// block.bubble.setShadowStyle(3);

		if(currentPropertyLocation)
			block.bubble.close();
		else
			block.bubble.open(block.map, marker);


		// clear location
		currentPropertyLocation = '';

		$('.mapOverlay').css({'z-index':'1'}).show();
		$('#loewsMap').css({right:'0'}).removeClass("fullwidth");

		jQuery(window).ready(mapResize);
		jQuery(window).resize(mapResize);
		function mapResize (){
			$('#loewsMap').css({width:($("div.maps").width()-$(".mapOverlay").outerWidth())});
			var center = block.map.getCenter();
			google.maps.event.trigger(loewsMap, "resize");
			block.map.setCenter(center);
		}

		// TRIGGER FINDAROUNDHERE BY CLICKING THE SEARCH BUTTON...
		$("#mapSearchButton").click(function(event) {
			event.preventDefault();
			search=$(".mapOverlay input").val();
			block.bubble.close();
			findAroundHere(search);
		});

		// required for the piper's blog
		if( !currentPropertyLocation && block.hotels[marker.hotelId] ) {
			markerID = block.hotels[marker.hotelId].id;
		}

		// ..OR PRESSING THE ENTER BUTTON
		$(".mapOverlay input").keypress(function (e) {
			if (e.which == 13) {
				search=$(".mapOverlay input").val();
				block.bubble.close();
				findAroundHere(search);
			}
		});
	}

	//===========================================================================================================
	function addHotelMarker(i, nInGroup)
	{
		var hotel = block.hotels[i];
		// console.log(hotel);
		var pos = new google.maps.LatLng(parseFloat(hotel.latitude), parseFloat(hotel.longitude));
		var removeStr = ["at","Universal","Orlando","'s"];
				var property = hotel.name;
				if(!(hotel.group == null || hotel.group == ''))
				{
					for(var j=0; j < removeStr.length; j++)
					{
						property = property.replace(removeStr[j],'');
					}
					property = property.trim();
				}
		var marker = new MarkerWithLabel({
			position: pos,
			draggable: false,
			map: block.map,
			labelContent: hotel.group == null || hotel.group == '' ?  hotel.city : property,
			labelAnchor: hotel.labelX ? new google.maps.Point(parseInt(hotel.labelX), parseInt(hotel.labelY)) : new google.maps.Point(-3, 10),
			labelClass: "cityLabel prop"+i,
			icon: nInGroup ? block.hotelGroupMarker : block.hotelMarker,
			zIndex: nInGroup ? google.maps.Marker.MAX_ZINDEX + 1 : google.maps.Marker.MAX_ZINDEX
		});
		marker.hotelId = i;
				marker.groupId = hotel.group;
		console.log(marker);
		google.maps.event.addListener(marker, 'click', function() {
			console.log(marker.position);
			zoom(marker);
		});

		// on hover change the icon
		google.maps.event.addListener(marker, 'mouseover', function() {
			marker.setIcon(nInGroup ? block.groupMarkerHover : block.markerHover);

						$('.cityLabel').each(function(){
									var cl = $(this).attr("class");
									cl = cl.replace('cityLabel ','');
									if(cl == 'prop'+marker.hotelId)
									{
										$(this).addClass("cityLabelHover");
									}
								});
		});
		google.maps.event.addListener(marker, 'mouseout', function() {
			marker.setIcon(block.hotelMarker);
						$('.cityLabel').each(function(){
							$(this).removeClass("cityLabelHover");
						});
		});

		if (nInGroup) {
			var mark = new MarkerWithLabel({
				position: pos,
				draggable: false,
				map: block.map,
//				labelContent: nInGroup,
				labelAnchor: new google.maps.Point(0, 30),
				labelClass: "hotels-in-group",
				icon: 'http://chart.apis.google.com/chart?chst=&chld='+nInGroup+'|FFFFFF|FFFFFF',
				zIndex: google.maps.Marker.MAX_ZINDEX + 2,
				labelStyle: {opacity: (block.featureOpts[0].stylers[0].visibility == 'on' ? 0 : 1), color:'#FFF', 'font-size':'18px'}
			});
			mark.hotelId = i;
						mark.groupId = hotel.group;
						mark.setVisible(false);
			google.maps.event.addListener(mark, 'click', function() {
				zoom(mark);
			});

			// on hover change the icon
			google.maps.event.addListener(mark, 'mouseover', function() {
				marker.setIcon(nInGroup ? block.groupMarkerHover : block.markerHover);
			});
			google.maps.event.addListener(mark, 'mouseout', function() {
				marker.setIcon(nInGroup ? block.hotelGroupMarker : block.hotelMarker);
			});
		}
				return marker;
	}

	//===========================================================================================================
	function buildHotelInfo(i)
	{
		var hotel = block.hotels[i];
		if (!hotel)
			return;

		// website url
		var website = hotel.url ? hotel.url+'/' : '';

		//Using a template to create InfoWindow content

		var $info = $('#info-box').clone();
		$info.find('.info-box').attr({id:i});

		// get the correct image path
		if (hotel.photo != ""){
			// if there is a photo attached
			pathArray=hotel.photo.split("/");
			filename="__thumbs_marker_info"+"/"+pathArray[ pathArray.length - 1];
			pathArray[pathArray.length - 1] = filename;

			if (siteRoot == '/') {
				newHotelPhoto = pathArray.join("/");
			} else {
				newHotelPhoto=siteRoot+pathArray.join("/");
			}
		} else {
			newHotelPhoto=siteRoot+"d/loewsDefault.png";
		}

		// add image to info box
		$info.find('img').attr("src",newHotelPhoto);

		$info.find('.hotel-name').text(hotel.name);
		// check if there is a description, and limit the characters
		if (hotel.description) {
			var description ='';
			if(hotel.description.length > 120)
				description=hotel.description.substring(0,120)+"...";
			else
				description=hotel.description.substring(0,120);
			$info.find('.info-text').html(description);
		}
		$info.find('.info-address').html(hotel.address+'<br/>'+addressLine2(hotel));
		$info.find('.info-phones').html('<strong>'+mapMarkerPhoneText+'</strong> '+hotel.phoneNumber+'<br/><strong>'+mapMarkerReservationText+'</strong> '+hotel.tollfreeNumber);
                if(hotel.code == 'LVOG')
                {
                    if ( document.location.href.indexOf('-fr') > -1 || document.location.href.indexOf('/fr') > -1 )
                    {
                        $info.find('.visitSite').attr("href",siteRoot+website+'fr');
                        $info.find('.offers').attr("href",siteRoot+website+'specials/fr');
                        $info.find('.booking').attr("href",siteRoot+'reservations/dates-fr.html?htld='+hotel.code);
                    }
                    else
                    {
                        $info.find('.visitSite').attr("href",siteRoot+website);
                        $info.find('.offers').attr("href",siteRoot+website+'specials');
                        $info.find('.booking').attr("href",siteRoot+'reservations/dates.html?htld='+hotel.code);
                    }
                }
                else
                {
                    $info.find('.visitSite').attr("href",siteRoot+website);
                    $info.find('.offers').attr("href",siteRoot+website+'specials');
                    $info.find('.booking').attr("href",siteRoot+'reservations/dates.html?htld='+hotel.code);
                }
		return $info.html();
	}

	//===========================================================================================================
	// Creates a single place marker for surrounding Google Places
	this.getPlaceMarker = function(place)
	{
		for (var i in placeMarkers)
		{
			if(placeMarkers[i].title == place.name)
			{
				google.maps.event.trigger(placeMarkers[i], 'click');
				return;
			}
		}
	}

	//===========================================================================================================
	// Creates a single place marker for surrounding Google Places
	function createPlaceMarker(place)
	{
		var marker;
		// Check if no 'blacklist' word occurs in the place name
		// placeName=place.name;
		var newPlace = place.name.toLowerCase();
		for (var j=0; j<block.noShowPlaces.length; ++j) {
			var blocked = block.noShowPlaces[j].toLowerCase().trim();
			var ind = newPlace.indexOf(blocked);
			if (newPlace.indexOf(blocked) > -1)
				return;
		}

		if (place.photos) {
			var p = place.photos;
			var t = p[0].getUrl({'maxWidth': 75, 'maxHeight': 75});
			if(t == 'https://lh5.googleusercontent.com/-aly9Uswaalw/UuJpft_sPvI/AAAAAADN8eo/IA-oWARuRUs/w75-h75-s1600/photo.jpg')
				return;
		}

		var marker = new google.maps.Marker({
			map: block.map,
			position: place.geometry.location,
			title: place.name,
			icon: {
				url: place.icon,
				scaledSize: new google.maps.Size(23, 23)
			}
			// icon: 'http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=A|FF0000|FFFFFF'
		});

		//  console.log(marker);
		var html = "<div class='mapSearchResultsItem' id="+place.id+">";
//		if (place.icon) {
//			html += "<img style='float:left;width:30px;height:30px' src='" + place.icon + "'>";
//		}

		if (place.photos) {
			var p = place.photos;
			var t = p[0].getUrl({'maxWidth': 75, 'maxHeight': 75});
			html += "<img style='float:right;margin:15px 5px;width:75px;height:75px' src='" + t + "'>";
		}
		html += "<div class='rightContent'>";
		if (place.name) {
			html += "<h3>" + place.name + "</h3>";
		}
		html += "<p>"
		if (place.formatted_address) {
			html += place.formatted_address + "<br>";
		}
		if (place.formatted_phone_number) {
			html += place.formatted_phone_number + "<br>";
		}
		if (place.website) {
			// simplify url
			var simpleUrl = place.website.replace("http://","");
			simpleUrl = simpleUrl.replace("www.","");
			simpleUrl = simpleUrl.replace(/\/$/, "");
			html += "<a href='" + place.website + "' target='_blank'>" + simpleUrl + "</a>"
		}
		if (place.rating) {
			html += '<br>'+ (place.rating).toFixed(1) + '  <span class="stars"> '+place.rating + '</span>&nbsp;&nbsp;'+place.reviews.length+' reviews';
		}
		html += "</div></div>";

		placeMarkers[place.name] = marker;
		google.maps.event.addListener(marker, 'click', function() {
						block.bubble.setMinWidth();
						block.bubble.setMinHeight();
			block.bubble.content = html;
			block.bubble.open(block.map, marker);
			setTimeout(function() {
				$.fn.stars = function() {
					return $(this).each(function() {
						// Get the value
						var val = parseFloat($(this).html());
						// Make sure that the value is in 0 - 5 range, multiply to get width
						var size = Math.max(0, (Math.min(5, val))) * 12;
						// Create stars holder
						var $span = $("<span />").width(size);
						// Replace the numerical value with stars
						$(this).html($span);
					});
				}
				$(function() {
					$('span.stars').stars();
				});
			}, 600);
		});

		return marker;
	}

		function sortJsonData(a, b)
		{
			a = a.toLowerCase();
			b = b.toLowerCase();

			return (a < b) ? -1 : (a > b) ? 1 : 0;
		}

	//===========================================================================================================
	function addFeaturedPlaces()
	{
		$(".mapSearchResults").empty();

				block.featuredPlaces.sort(function(a, b) {
				  return sortJsonData(a.title, b.title);
				});

		for (var i in block.featuredPlaces)
		{
					if(block.featuredPlaces[i].propertyId === markerID || block.featuredPlaces[i].propertyId == currentPropertyId)
					{
							var html = "<div class='mapSearchResultsItem' id="+block.featuredPlaces[i].id+" onClick='openFeaturedPlaces("+i+")'>";
							html += "<img style='float:left;' src='http://chart.apis.google.com/chart?chst=d_map_pin_icon&chld=star|55A2DA'>";
							html += "<div class='rightContent'>";
							if (block.featuredPlaces[i].title) {
									html += "<h3>" + block.featuredPlaces[i].title + "</h3>";
							}
							html += "</div></div>";

							$(".mapSearchResults").append(html);
							createFeatureMarker(i);
					}
		}

	$(".mapSearchResults,.mapOverlay .close").slideDown("fast");
		$(".placesWrapper").addClass("withSearch");
		$(".mapOverlay .close .clearResults").click(function() {
			$(".mapSearchResults,.mapOverlay .close").slideUp("fast");
			$(".placesWrapper").removeClass("withSearch");
			$(".mapOverlay input").val("");
			clearPlaces();
		});
	}

	//===========================================================================================================
	this.getFeatureMarker = function (i)
	{
		var fp = block.featuredPlaces[i];
		var marker = new google.maps.Marker({
			map: block.map,
			position: new google.maps.LatLng(parseFloat(fp.latitude), parseFloat(fp.longitude)),
			title: fp.title,
			icon: 'http://chart.apis.google.com/chart?chst=d_map_pin_icon&chld=star|55A2DA'
			// icon: 'http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=A|FF0000|FFFFFF'
		});

		google.maps.event.addListener(marker, 'click', function() {
						block.bubble.setMinWidth();
						block.bubble.setMinHeight();
			block.bubble.content = "<h3>" + fp.title + "</h3>" + "<p>" + fp.description + "</p>";
			block.bubble.open(block.map, marker);
		});
		placeMarkers[fp.title] = marker;
		google.maps.event.trigger(marker, 'click');
	}

	// Creates a single place marker for surrounding Google Places
	function createFeatureMarker(i)
	{
		var fp = block.featuredPlaces[i];
		var marker = new google.maps.Marker({
			map: block.map,
			position: new google.maps.LatLng(parseFloat(fp.latitude), parseFloat(fp.longitude)),
			title: fp.title,
			icon: 'http://chart.apis.google.com/chart?chst=d_map_pin_icon&chld=star|55A2DA'
			// icon: 'http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=A|FF0000|FFFFFF'
		});

		google.maps.event.addListener(marker, 'click', function() {
						block.bubble.setMinWidth();
						block.bubble.setMinHeight();
			block.bubble.content = "<h3>" + fp.title + "</h3>" + "<p>" + fp.description + "</p>";
			block.bubble.open(block.map, marker);
		});

		placeMarkers[fp.title] = marker;
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
	function displayMap($wrapper)
	{
		block.mapOptions.center = new google.maps.LatLng(block.center[0], block.center[1]);
		block.mapOptions.scrollwheel = block.mapOptions.draggable = true;
		block.map = new google.maps.Map($wrapper[0], block.mapOptions);
		var styledMapOptions = {name:block.mapOptions.mapTypeId};
		var customMapType = new google.maps.StyledMapType(block.featureOpts, styledMapOptions);
		block.map.mapTypes.set(block.mapOptions.mapTypeId, customMapType);
		var grp, groups = {};
		// Add markers with no groupping first
		for (var i in block.hotels) {
			// This 'if' block is for groupping markers, specifically: LOEWS maps
			if (block.mapOptions.scrollwheel && ! block.hotels[i].group) {
				addHotelMarker(i);
				continue;
			}
			// this part of loop is for groupping markers, specifically: LOEWS maps
			grp = block.hotels[i].group.toLowerCase();
			if (!groups[grp]) {
				groups[grp] = [0,0];
			}
			// If this is the actual hotel to place the marker on
			if (block.hotels[i].group == block.hotels[i].group.toUpperCase()) {
					groups[grp][0] = i;
			}
			groups[grp][1]++;
		}

		// Add group markers
//		for (grp in groups)
//			addHotelMarker(groups[grp][0], groups[grp][1]);

		var markers = [];
		for (var i = 0; i < block.hotels.length; i++)
		{
			if(!(block.hotels[i].group == null || block.hotels[i].group == ''))
			{
				var marker = addHotelMarker(i,block.hotels[i].group);
				console.log(marker.position);
				markers.push(marker);
			}
		}
		var markerCluster = new MarkerClusterer(block.map, markers);
	}

	// Property Site on map load
	$(document).ready(function(){
		if(currentPropertyLocation)
		{
			widgets.items['loewsMap'].clickLocation(currentPropertyLocation);
			currentPropertyLocation = '';
		}
	});

}
function openFeaturedPlaces(t){
	widgets.items['loewsMap'].getFeatureMarker(t);
}

function openPlaceMarker(id){
	var current_marker = '';
	for(var i = 0; i < markerArray.length; i++)
	{
			if(markerArray[i].id == id)
					current_marker = markerArray[i];
	}
	widgets.items['loewsMap'].getPlaceMarker(current_marker);
}