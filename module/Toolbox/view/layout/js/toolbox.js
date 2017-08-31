/*Phoenix Toolbox Main JS
/* 
    Created on : Aug 26, 2014, 2:46:08 PM
    Authors     : Trevor Niemi, Rizwan Vahora, Erik Vecchione
*/
//inline text edit function
  $(function() {
        
  $(".dblclick").editable("<?php print $url ?>echo.php", { 
      indicator : "<img src='img/indicator.gif'>",
      tooltip   : "Doubleclick to edit...",
      event     : "dblclick",
      style  : "inherit"
  });

});
// top drawer menu control code
 window.addEventListener('load', function() {
    new FastClick(document.body);
    }, false);
    $(' #widgets').click(function(e){
      $('.drawerMenu_widgets').toggleClass('active');
      e.preventDefault();
    });
    $(' #phoenix').click(function(e){
      $('.drawerMenu_phoenix').toggleClass('active');
      e.preventDefault();
    });
    $(' #templates').click(function(e){
      $('.drawerMenu_templates').toggleClass('active');
      e.preventDefault();
    });
    $(' .drawerMenu-btn').click(function(e){
      $('i.drawerMenu-icon').toggleClass('fa-angle-down fa-angle-up');
      e.preventDefault();
    });
// show/hide top menus based on section tab selection
	$(' a#widgetEditorLink').click(function(){
      $('a#widgets').show();
      $('a#templates').hide();
    });
    $(' a#templateEditorLink').click(function(){
      $('a#templates').show();
      $('a#widgets').hide();
    });

 //drag and drop jQuery interface 
    $(document).foundation();
            $(function() {
                $( ".sortable" ).sortable();
                $( ".sortable" ).disableSelection();
            });
            function handler() { 
      
      $('#myModalColumn').foundation('reveal', 'open');
      $('#thisHolder').val($( this ).attr('id'));
      var templateName = $('#templateName').val();
    }
            $( ".createRow" ).click(function() {
      var rowName = 'row';
                        
      var responsiveClass = '';
      // grid responsive information      
      if($('#rowdesktopcheckbox').is(':checked')) {
        // 
        var responsiveClass = 'hide-for-large-up ';
      }
      
      if($('#rowtabletcheckbox').is(':checked')) {
        var responsiveClass = 'show-for-medium-up ';
      }
      
      if($('#rowmobilecheckbox').is(':checked')) {
        var responsiveClass = 'show-for-small-up ';
      } 
      
                        // generate random integer to keep ID's unique ALWAYS
                        var randomNumber = Math.floor(Math.random() * 600) + 1;
                        var rowCSS = $('<div class="row '+responsiveClass+'" id="'+rowName+'_'+randomNumber+'"><a href="#" class="createColumn"><i class="fa fa-plus"></i>&nbsp;Column</a></div>');

                        $('.container').append(rowCSS);
                        //rowCSS.click(handler);
                        $(".createColumn").unbind().click(function() {
                            var cols = 0;
                            $( this ).parent().find(".columns").each(function(){
                               if($(this).hasClass("small-1")) 
                                   cols += 1;
                               if($(this).hasClass("small-2")) 
                                   cols += 2;
                               if($(this).hasClass("small-3")) 
                                   cols += 3;
                               if($(this).hasClass("small-4")) 
                                   cols += 4;
                            });
                            
                            if(cols == 4)
                            {
                                alert("You can't insert more than 4 columns to the row!");
                                return false;
                            }
                            var templateName = $('#templateName').val();
                            var gridCount = 1;
                            var gridID = '_column';
                            var offSetStyling = '';
                            var responsiveClass = '';
                            // grid off set information
                            var gridOffset =  0;
                            if(gridOffset != 0) {
                                    offSetStyling = 'small-offset-'+gridOffset;
                            }

                            // grid responsive information      
                            if($('#columndesktopcheckbox').is(':checked')) {
                                    responsiveClass = 'hide-for-medium-up ';
                            }

                            if($('#columntabletcheckbox').is(':checked')) {
                                    responsiveClass = 'show-for-small-up ';
                            }

                            if($('#columnmobilecheckbox').is(':checked')) {
                                    responsiveClass = 'show-for-medium-up ';
                            }     
                            
                            $( this ).parent().append('<div class="small-'+gridCount+' '+offSetStyling+' columns '+responsiveClass+'" id="'+gridID+'_'+randomNumber+'"><div class="callout panel"><p>'+gridCount+' '+gridID+'</p></div></div>');
                        });
    });
//end drag and drop jQuery
//misc scripts
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