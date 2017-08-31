/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

(function ($, window, document) {

var _siteroot,
    saveLayout,
    layoutDeleteURL,
    currentColumn,
    cssClassesArray = [],
    medClss='', 
    smClss='', 
    cssClass='', 
    edClss='', 
    vsClss='',
    temp;

$(document).foundation();
$(document).ready(function(){
    
    _siteroot = $('[data-siteroot]').data('siteroot');
    saveLayout = $('[data-save-layout-url]').data('save-layout-url');
    layoutDeleteURL = $('[data-delete-layout-url]').data('delete-layout-url');
    
   $("#layoutName").keyup(function(){
       $(this).attr('value',$(this).val());
   });
   
   $("#layout-canvas").gridmanager({
          /*
     General Options---------------
    */
        // Debug to console
        debug: 0,

        // URL to save to
        remoteURL: saveLayout,


  /*
     Control Bar---------------
  */

        // Array of buttons for row templates
        controlButtons: [[12], [6,6], [4,4,4], [3,3,3,3],   [2,8,2], [4,8], [8,4]],

        // Default control button class
        controlButtonClass: "tiny button",


   /*
     General editing classes---------------
  */

       // generic float left and right
        gmFloatLeft: "left",
        gmFloatRight: "right",
        gmBtnGroup:  "button-group",
        gmDangerClass: "alert",
         // Control bar RH dropdown markup
        controlAppend: "<div class='button-group right'><button title='Edit Source Code' type='button' class='button tiny gm-edit-mode'><span class='fa fa-code'></span></button><button title='Preview' type='button' class='button tiny gm-preview'><span class='fa fa-eye'></span></button><div class='button-group right gm-layout-mode'><a class='button tiny' data-width='auto' title='Desktop'><span class='fa fa-desktop'></span></a><a class='button tiny'  title='Tablet' data-width='768'><span class='fa fa-tablet'></span></a><a title='Phone' class='button tiny' data-width='640'><span class='fa fa-mobile-phone'></span></a></div><a  class='gm-save button tiny'  title='Save'  href='#'><span class='fa fa-save'></span></a><a  class='button tiny gm-resetgrid'  title='Reset Grid' href='#'><span class='fa fa-trash-o'></span></a>",



  /*
     Rows---------------
  */
        // class of background element when sorting rows
        rowSortingClass: "panel callout radius",

        // Buttons at the top of each row
        rowButtonsPrepend: [
                {
                   title:"Move",
                   element: "a",
                   btnClass: "gm-moveRow pull-left",
                   iconClass: "fa fa-arrows"
                },
                {
                   title:"New Column",
                   element: "a",
                   btnClass: "gm-addColumn left  ",
                   iconClass: "fa fa-plus"
                },
                 {
                   title:"Row Settings",
                   element: "a",
                   btnClass: "right gm-rowSettings",
                   iconClass: "fa fa-cog"
                }

            ],

        // Buttons at the bottom of each row
        rowButtonsAppend: [
                {
                 title:"Remove row",
                 element: "a",
                 btnClass: "right gm-removeRow",
                 iconClass: "fa fa-trash-o"
                }
            ],

        // CUstom row classes - add your own to make them available in the row settings
        rowCustomClasses: ["panel","callout","radius"],

  /*
     Columns--------------
  */
         // Adds any missing classes in columns for muti-device support.
        addResponsiveClasses: true,

          // Additional column class to add (foundation needs columns, bs3 doesn't)
        colAdditionalClass: "column",

       // Generic desktop size layout class
        colDesktopClass: "large-",

        // Generic tablet size layout class
        colTabletClass: "medium-",

        // Generic phone size layout class
        colPhoneClass: "small-",

        // Wild card column desktop selector
        colDesktopSelector: "div[class*=large]",

        // Wildcard column tablet selector
        colTabletSelector: "div[class*=medium]",

        // Wildcard column phone selector
        colPhoneSelector: "div[class*=small]",

        // Buttons to prepend to each column
        colButtonsPrepend: [
           {
                 title:"Move",
                 element: "a",
                 btnClass: "gm-moveCol left",
                 iconClass: "fa fa-arrows "
              },
               {
                 title:"Make Column Narrower",
                 element: "a",
                 btnClass: "gm-colDecrease left",
                 iconClass: "fa fa-minus"
              },
              {
               title:"Make Column Wider",
               element: "a",
               btnClass: "gm-colIncrease left",
               iconClass: "fa fa-plus"
              },
              {
                   title:"Column Settings",
                   element: "a",
                   btnClass: "right gm-colSettings",
                   iconClass: "fa fa-cog"
                }
            ],

        // Buttons to append to each column
        colButtonsAppend: [
                {
                 title:"Add Nested Row",
                 element: "a",
                 btnClass: "left gm-addRow",
                 iconClass: "fa fa-plus-square"
                },
                {
                 title:"Remove Column",
                 element: "a",
                 btnClass: "right gm-removeCol",
                 iconClass: "fa fa-trash-o"
                }
            ],

        // Maximum column span value: if you've got a 24 column grid via customised bootstrap, you could set this to 24.
        colMax: 12,

        // Column resizing +- value: this is also the colMin value, as columns won't be able to go smaller than this number (otherwise you hit zero and all hell breaks loose)
        colResizeStep: 1

    });
    
    $("#layout-canvas").find("#gm-canvas").addClass("row");
    $(".add-6-6").click();  //  default row and columns
    
    $(".gm-preview, .gm-save").click(function(){
        $("#gm-canvas .column").each(function(){
            $(this).toggleClass("callout panel");
        });
    });
    
    function editColumnHandler(curCol) 
    { 
        currentColumn = curCol;
        $('#myModalVisibility').foundation('reveal', 'open');
        if($(currentColumn).hasClass('editable'))
        {
            if(!($("#chkbox-editor").attr("checked")))
            {
                $("#chkbox-editor").attr("checked","checked");
            }
        }
    }
    
    $(document).on("click", ".cancel-column",function(){
        $('#myModalVisibility').foundation('reveal', 'close');
    });
    
    
    $(document).on("click", ".update-column",function(){
        
        for(var i = 0; i < cssClassesArray.length; i++)
        {
            var clss = cssClassesArray[i];
            if(clss.indexOf("-clsstmp") > -1 || clss.indexOf("show-for-") > -1 || clss.indexOf("hide-for-") > -1 || clss.indexOf("visible-for-") > -1 || clss.indexOf("hidden-for-") > -1)
                $(currentColumn).removeClass(clss);            
        }
        
        medClss = $("#medium-columns-classes option:selected").attr("value");
        smClss = $("#small-columns-classes option:selected").attr("value");
        
        vsClss = $('input[name=visibility-radio]:checked').val() ? $('input[name=visibility-radio]:checked').val() : '';
        edClss = $('input[name=chkbox-editor]').is(':checked') ? 'editable' : '';
        
        cssClass = medClss + " " + smClss + " " + vsClss + " " +edClss;
        
        $(currentColumn).addClass(cssClass);
        if(!(edClss == ''))
            $(currentColumn).attr('contenteditable','false');
        else
        {
            $(currentColumn).removeAttr('contenteditable');
            $(currentColumn).removeClass('editable');
        }
        
        $('#myModalVisibility').foundation('reveal', 'close');
    });
    
    $(document).on("change", "input[name=visibility-radio]", function() {
        vsClss = $('input[name=visibility-radio]:checked').val();
    });
    
    $(document).on("change", "input[name=chkbox-editor]", function() {
        if($(this).is(':checked'))
            edClss = 'editable';
        else
            edClss = '';
    });
    
    $(document).on("change", "#medium-columns-classes", function() {
        medClss = $("#medium-columns-classes option:selected").attr("value");
    });
    
    $(document).on("change", "#small-columns-classes", function() {
        smClss = $("#small-columns-classes option:selected").attr("value");
    });
    
    $(document).on("dblclick", "#gm-canvas .column",function(){
        
        var cssClasses = $(this).attr('class');
        cssClassesArray = cssClasses.split(" ");
        
        for(var i = 0; i < cssClassesArray.length; i++)
        {
            var clss = cssClassesArray[i];
            //clss = clss.replace('-clsstmp','');
            
            $("#medium-columns-classes > option").each(function() {
                if($(this).attr("value") == clss)
                {
                    $(this).attr("selected","selected");
                }
            }); 
            
            $("#small-columns-classes > option").each(function() {
                if($(this).attr("value") == clss)
                {
                    $(this).attr("selected","selected");
                }
            });
        }
        $(this).on("dblclick",editColumnHandler(this));
    });
    
    //  toggle existing button
    $(".existing-layouts").hide();
    $("#toggle-btn-existing-layout").click(function(){
        $(".existing-layouts").slideToggle();
        $("#delete-page-layout").hide();
    });
    //  reading the layout from the existing 
    $(".existing-layout-list li").click(function(){
        $("#layoutName").attr("value",$(this).find('a').text().toLowerCase());
        $("#delete-page-layout").show();
        $("#delete-page-layout").attr("data-layout-file",$(this).attr("data-layout"));
        $.ajax({
                url : _siteroot+"templates/main/layouts/"+$(this).attr("data-layout"),
                dataType: "html"
        }).done(function(data){
            $("#gm-canvas").find(".temp").remove();
            $("#gm-canvas").html("<div class='temp'></div>");
            $("#gm-canvas").find(".temp").html(data);
            temp = $("#gm-canvas").find(".container").html();
            $("#gm-canvas").append(temp);
            $("#gm-canvas .column").each(function(){
                $(this).removeClass("callout panel");
            });
            $("#gm-canvas").find(".temp").remove();
            $(".gm-preview").click();
            $(".gm-preview").click();
        });
        return false;
    });
    
    $("#delete-page-layout").hide();
    
    $("#delete-page-layout").click(function(){
        var layout = $(this).attr("data-layout-file");
        if(layout == '')
        {
            alert('Please select the layout!');
            return false;
        }
        $('#myModal').foundation('reveal', 'open');
        $('.cancel-delete-layout').click(function(){
            $('#myModal').foundation('reveal', 'close');
        });
        $('.delete-layout').click(function(){
            var dt = {'layout-name':layout};
            $.ajax({
                type: 'POST',
                url: layoutDeleteURL,
                data: dt,
                dataType: "JSON"
            }).done(function(data){
                //  close the modal
                $('#myModal').foundation('reveal', 'close');
                window.location.reload();
            }); 
        });
     });

});
}(window.jQuery, window, document));