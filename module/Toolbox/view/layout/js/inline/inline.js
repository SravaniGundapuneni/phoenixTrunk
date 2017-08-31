/*this is the inline js file
The majority of your inline code that isnt page specific should be contained here.
This is executed at the footer of the page, right before the closing body tag.
*/

   $(document).foundation();
		
		function handler() { 
			
			$('#myModalColumn').foundation('reveal', 'open');
			$('#thisHolder').val($( this ).attr('id'));
			var templateName = $('#templateName').val();
		}
		 
		$(".createColumn").click(function() {
			
			var templateName = $('#templateName').val();
			var gridCount = $('#columnSizeCreate').val();
			var gridID = $('#columnNameCreate').val();
			var offSetStyling = '';
			var responsiveClass = '';
			// grid off set information
			var gridOffset =  $('#gridOffset').val();
			if(gridOffset != 0) {
				var offSetStyling = 'large-offset-'+gridOffset;
			}
			
			// grid responsive information			
			if($('#columndesktopcheckbox').is(':checked')) {
				// 
				var responsiveClass = 'hide-for-medium-up ';
			}
			
			if($('#columntabletcheckbox').is(':checked')) {
				var responsiveClass = 'show-for-large-up ';
			}
			
			if($('#columnmobilecheckbox').is(':checked')) {
				var responsiveClass = 'show-for-medium-up ';
			}			
			
			var theDivToAppend = $('#thisHolder').val();
			if(gridCount.length != 0 && gridID.length != 0) {
				$( '#'+theDivToAppend ).append('<div class="large-'+gridCount+' '+offSetStyling+' columns '+responsiveClass+'" id="'+gridID+'_'+templateName+'"><div class="callout panel"><p>'+gridCount+' '+gridID+'</p></div></div>');
			}	
			$('#myModalColumn').foundation('reveal', 'close');	 
			
			// reset column form
			$('#columnNameCreate').val('');
			$('#columnSizeCreate').val('1');
			$('#gridOffset').val('0');
			
		 });
		 
		 
		$( ".savePage" ).click(function() {
			
			$('.columns').each(function(i){
				var currentColumnId = $(this).attr('id');
				$(this).attr('id', currentColumnId + ' droppable');
			});
			
			alert($('.container').get(0).outerHTML);
			$('.container-regions').append($('.container').get(0).outerHTML);
			
			//jquery ui drag and drop
			$( ".draggable" ).draggable({helper: 'clone', greedy: true, opacity: 0.70,
       zIndex:10000, start: function (e, ui) {
				ui.helper.animate({
					width: 120,
					height: 120,
					zIndex:10000
				});
			},});
			$( ".columns" ).droppable({
				
				drop: function( event, ui ) {
				console.log(event);
				console.log(ui);
				if(ui.draggable.attr('id') == 'draggable-navigation') {
				$( this ).html( '<nav class="top-bar" data-topbar> <ul class="title-area"> <li class="name"> <h1><a href="#">My Site</a></h1> </li> <!-- Remove the class "menu-icon" to get rid of menu icon. Take out "Menu" to just have icon alone --> <li class="toggle-topbar menu-icon"><a href="#"><span>Menu</span></a></li> </ul> <section class="top-bar-section"> <!-- Right Nav Section --> <ul class="right"> <li class="active"><a href="#">Right Button Active</a></li> <li class="has-dropdown"> <a href="#">Right Button Dropdown</a> <ul class="dropdown"> <li><a href="#">First link in dropdown</a></li> </ul> </li> </ul> <!-- Left Nav Section --> <ul class="left"> <li><a href="#">Left Nav Button</a></li> </ul> </section> </nav>' );
				}
				if(ui.draggable.attr('id') == 'draggable-accordion') {
					$( this ).html( '<dl class="accordion" data-accordion><dd class="accordion-navigation"><a href="#panel1b">Accordion 1</a><div id="panel1b" class="content active">Panel 1. Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</div></dd><dd class="accordion-navigation"><a href="#panel2b">Accordion 2</a><div id="panel2b" class="content">Panel 2. Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</div></dd><dd class="accordion-navigation"><a href="#panel3b">Accordion 3</a><div id="panel3b" class="content">Panel 3. Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</div></dd></dl>' );
				}
			},
			greedy: true
			});
			

			
		});

		$( ".createRow" ).click(function() {
			var rowName = $('#rowNameCreate').val();
			var responsiveClass = '';
			// grid responsive information			
			if($('#rowdesktopcheckbox').is(':checked')) {
				// 
				var responsiveClass = 'hide-for-medium-up ';
			}
			
			if($('#rowtabletcheckbox').is(':checked')) {
				var responsiveClass = 'show-for-large-up ';
			}
			
			if($('#rowmobilecheckbox').is(':checked')) {
				var responsiveClass = 'show-for-medium-up ';
			}	
			
			if(rowName.length  != 0) {
				// generate random integer to keep ID's unique ALWAYS
				var randomNumber = Math.floor(Math.random() * 600) + 1;
				var rowCSS = $('<div class="row '+responsiveClass+'" id="'+rowName+'_'+randomNumber+'"></div>');
				
				$('.container').append(rowCSS);
				rowCSS.click(handler);
				
				$('#myModalRow').foundation('reveal', 'close');
				
				// reset the modal row name
				$('#rowNameCreate').val('');
				$('input:checkbox').removeAttr('checked');
			}
		});