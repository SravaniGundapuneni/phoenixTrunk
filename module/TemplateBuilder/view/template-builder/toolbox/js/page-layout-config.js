/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/*jslint browser: true*/
/*globals escape, alert, confirm*/

(function ($, window, document) {

  var exitWithoutSaveFlag = false,
    editFlag,
    subTmpl,
    tmplName,
    tmplURL,
    saveTemplateURL,
    saveWidgetFilesURL,
    //getWidgetURL,
    addWidgetURL,
    deleteWidgetURL,
    checkForExistingWidgetURL,
    widgetConfigURL,
    _siteroot,
    sidebarPosition,
    sidebarData,
    sideBarClass;
    //showPreviewLink,
    //backToPageLayoutsURL;
    
    var widgetImages = {};

  //  saving widget files
  function saveWidgetFiles(templateName, widgetUniqId, widgetName) {
    var widgetNameWithDashes = widgetName.replace(/([a-z])([A-Z])/g, '$1-$2').toLowerCase();
    var widgetData = {
      'sub-tmpl-name': encodeURIComponent(templateName),
      'widget-id': encodeURIComponent(widgetUniqId),
      'widget-name': encodeURIComponent(widgetName),
      'widget-name-with-dashes': encodeURIComponent(widgetNameWithDashes),
      'html-data': escape($("#panel2-1 textarea").val()),
      'css-data': escape($("#panel2-2 textarea").val()),
      'js-data': escape($("#panel2-3 textarea").val()),
      'php-config-data': encodeURI($("#panel2-4 textarea").val()),
      'current-template': tmplName
    };

    showLoader(_notifications.widgetSave);
    $.ajax({
      type: 'POST',
      url: saveWidgetFilesURL,
      data: widgetData,
      dataType: "JSON"
    }).done(function () {
      hideLoader();
      //  close the modal
      $('#widgetConfigModal').foundation('reveal', 'close');
    });
  }

  //  loading widgets after save or  edit
  function loadWidgetFiles(templateName, widgetUniqId, widgetName) {
    //  adding the widget css file
    var widgetId = widgetName + widgetUniqId;
    var widgetNameWithDashes = widgetName.replace(/([a-z])([A-Z])/g, '$1-$2').toLowerCase();

    var _phtml = _siteroot + "templates/main/core-templates/" + templateName + "/widgets/" + widgetId + "/" + widgetNameWithDashes + "-" + tmplName + ".phtml";
    var _href = _siteroot + "templates/main/core-templates/" + templateName + "/widgets/" + widgetId + "/css/" + widgetNameWithDashes + "-" + tmplName + ".css";    
    var _src = _siteroot + "templates/main/core-templates/" + templateName + "/widgets/" + widgetId + "/js/" + widgetNameWithDashes + "-" + tmplName + ".js";
    
    var widgetFilesArray = [_phtml, _href, _src, widgetConfigURL];
    
    var widgetConfigData = {
      'sub-tmpl-name': encodeURIComponent(templateName),
      'widget-id': encodeURIComponent(widgetUniqId),
      'widget-name': encodeURIComponent(widgetName),
    };
    var _fileStatus = _checkForExistingWidgetFile(widgetFilesArray);
    if(_fileStatus === true){
        showLoader(_notifications.loadingWidgetFiles);
        //  adding the css and phtml files into the config tabs
        $.when(
          $.ajax({
            url : _phtml,
            dataType: "html",
          }),
          $.ajax({
            url : _href,
            dataType: "text",
          }),
          //  adding the js file into the config tabs
          $.ajax({
            url : _src,
            dataType: "script",
          }),
          //  adding the widget.config.php file into the config tabs
          $.ajax({
            type: 'POST',
            url : widgetConfigURL,
            data: widgetConfigData,
            dataType: "html",
          })
        ).then(function (data1, data2, data3, data4) {

          $("#panel2-1 textarea").val(data1[0]);
          $("#panel2-2 textarea").val(data2[0]);
          $("#panel2-3 textarea").val(data3[0]);
          var dt4 = JSON.parse(data4[0]);
          $("#panel2-4 textarea").html(dt4['data']);
          hideLoader();
        });
    }
    else{
        alert("Widget folder is missing the following file! \n\n" +_fileStatus);
        return false;
    }
  }

  function editWidgetHandler(e) {
    e.preventDefault();

    $('#widgetConfigModal').foundation('reveal', 'open');

    $('#thisHolderTNAME').val($(this).attr('data-templatename'));
    $('#thisHolderWID').val($(this).attr('data-widgetuniqid'));
    $('#thisHolderWNAME').val($(this).attr('data-widgetname'));

    var widgetUniqId = $('#thisHolderWID').val();
    var templateName = $('#thisHolderTNAME').val();
    var widgetName = $('#thisHolderWNAME').val();

    $("#widgetConfigModal").find("h2").text("Widget - " + widgetName + widgetUniqId);

    loadWidgetFiles(templateName, widgetUniqId, widgetName);

    $("#save-config").unbind().click(function () {
      saveWidgetFiles(templateName, widgetUniqId, widgetName);
    });
    $("#cancel-save-config").unbind().click(function () {
      $('#widgetConfigModal').foundation('reveal', 'close');
    });
  }

  function deleteWidget(templateName, widgetUniqId, widgetName, current) {
    var templateData = {
      'sub-tmpl-name': encodeURIComponent(subTmpl),
      'uniqueWidgetId': widgetUniqId,
      'widgetName': widgetName
    };

    showLoader(_notifications.deleteWidget);

    $.ajax({
      type: 'POST',
      url: deleteWidgetURL,
      data: templateData,
      dataType: "JSON"
    }).done(function () {
      $('#widgetDeleteModal').foundation('reveal', 'close');
      $(current).parent().removeAttr("data-draggedwidget data-draggedwidgetid").addClass("callout panel").removeClass('dragged-widget').find(".temp-image").remove();
      $(current).parent().find(".widget-options").remove().empty().text(widgetName);
      hideLoader();
    });
  }

  function deleteWidgetHandler(e) {
    e.preventDefault();

    $('#widgetDeleteModal').foundation('reveal', 'open');
    $('#thisHolderTNAME').val($(this).attr('data-templatename'));
    $('#thisHolderWID').val($(this).attr('data-widgetuniqid'));
    $('#thisHolderWNAME').val($(this).attr('data-widgetname'));

    var widgetUniqId = $('#thisHolderWID').val();
    var templateName = $('#thisHolderTNAME').val();
    var widgetName = $('#thisHolderWNAME').val();

    $("#widgetDeleteModal").find(".delete-widget-title").text(widgetName + widgetUniqId);
    var current = $(this).parent();
    $("#deleteWidget").unbind().click(function (e) {
      e.preventDefault();

      deleteWidget(templateName, widgetUniqId, widgetName, current);
    });

  }

  function addWidget(widgetName, uniqueWidgetId, curr_object) {
    // Checking if widget is already exist or not
    $.ajax({
      type: 'POST',
      url: checkForExistingWidgetURL,
      data: {'sub-tmpl-name': encodeURIComponent(subTmpl), 'widgetName': widgetName, 'uniqueWidgetId': uniqueWidgetId},
      dataType: "json",
      cache: false,
      success: function (data) {
        if (data.status === true) {
          // adding widgets and widget directory to the core-template folder
          $.ajax({
            type: 'POST',
            url: addWidgetURL,
            data: {'sub-tmpl-name': encodeURIComponent(subTmpl), 'widgetName': widgetName, 'uniqueWidgetId': uniqueWidgetId},
            dataType: "json",
            cache: false,
            // success: function () {
            //   console.log('widget added');
            // },
            error: function () {
              alert("Fail Widget");
            }
          }).done(function () {
            
            $(curr_object).find(".loading-widget").remove();
            $('.config-buttons').css('visibility', 'visible');
            var widgetList = ["HeroImageWidget","CorporateLogo","Booking","SpecialOffers"];
            if($.inArray( widgetName, widgetList ) > -1)
            {
                $(curr_object).append('<img class="temp-image" src="'+widgetImages[tmplName][widgetName]+'" />');
            }
            // if(widgetName == "Slider")
            //     $(curr_object).append('<img class="temp-image" src="'+tmplURL+'widget/'+widgetName+'/view/'+widgetNameWithDashes+'/helper/img/'+widgetName.toLowerCase()+'.gif" />');
            // else
            //     $(curr_object).append('<img class="temp-image" src="'+tmplURL+'widget/'+widgetName+'/view/'+widgetNameWithDashes+'/helper/img/'+widgetName.toLowerCase()+'.png" />');

            //alert("Ajax Completed");
          });
        }
      },
      error: function () {
        alert("Fail Widget");
      }
    });

  }

  function addDroppableClass() {
    $(".content-data .column").each(function () {
      var _current_column = $(this);

      if (!_current_column.hasClass('non-droppable')) {
        _current_column.addClass('drop-widget draggable-columns');
      }
    });

    $(function () {

      /*jslint unparam: true*/
      $(".draggable-widget").draggable({
        appendTo: '.tab-bar',
        helper: 'clone',
        greedy: true,
        cursor: "move",
        opacity: 0.70,
        start: function (e, ui) {
            $('.config-buttons').css('visibility', 'hidden');
            $(document).find('.main-section').css('zIndex', '0');
            $(document).find(".drop-widget").each(function () {
                $(this).addClass('ui-droppable');
            });
            ui.helper.animate({
                width: 80,
                height: 80,
                zIndex: 10000,
            });
        },
        stop: function (e, ui) {
            $(document).find(".drop-widget").each(function () {
                $(this).removeClass('ui-droppable');
            });
        }
      });

      $(".drop-widget").droppable({
        hoverClass: "active-content-content",
        greedy: true,
        over: function () {
            var _currentDroppableColumn = $(this);

            _currentDroppableColumn.addClass("glowing-border");
            if (_currentDroppableColumn.hasClass('panel')) {
                _currentDroppableColumn.removeClass('callout');
            }
        },
        out: function () {
            var _currentDroppableColumn = $(this);

            _currentDroppableColumn.removeClass("glowing-border");
            if (_currentDroppableColumn.hasClass('panel')) {
                _currentDroppableColumn.addClass('callout');
            }
        },
        drop: function (event, ui) {
          
            exitWithoutSaveFlag = true;

            var widgetName = $.trim(ui.draggable[0].textContent);
            //var widgetNameWithDashes = widgetName.replace(/([a-z])([A-Z])/g, '$1-$2').toLowerCase();
            var uniqueWidgetId = Math.floor((Math.random() * 100) + 1);
            var tname = subTmpl.replace('.phtml', ' ').trim();
            var _currentDroppableColumn = $(this);

            _currentDroppableColumn.removeClass("callout").removeClass("panel");
            _currentDroppableColumn.empty();

            _currentDroppableColumn.attr('data-draggedwidget', widgetName);
            _currentDroppableColumn.attr('data-draggedwidgetid', uniqueWidgetId);
            _currentDroppableColumn.addClass('dragged-widget');

            _currentDroppableColumn.append('<div class="widget-options"></div>');
            var _widgetOptions = _currentDroppableColumn.find(".widget-options");

            _widgetOptions.append("<p>!+" + widgetName + "," + uniqueWidgetId + "," + tname + "+!</p>");
            _widgetOptions.append('<a href="#" class="edit-widget" data-widgetuniqid="' + uniqueWidgetId + '" data-widgetname="' + widgetName + '" data-templatename="' + tname + '"><i class="icon icon-setting-1"></i></a>');
            _widgetOptions.append('<a href="#" class="delete-widget" data-widgetuniqid="' + uniqueWidgetId + '" data-widgetname="' + widgetName + '" data-templatename="' + tname + '"><i class="icon icon-trash-bin"></i></a>');
            _widgetOptions.append('<div class="loading-widget"><img class="temp-image" src="' + tmplURL + 'module/TemplateBuilder/view/template-builder/toolbox/img/loading-animation.gif"/></div>');
            _widgetOptions.find(".edit-widget").click(editWidgetHandler);
            _widgetOptions.find(".delete-widget").click(deleteWidgetHandler);

            addWidget(widgetName, uniqueWidgetId, this);
            _currentDroppableColumn.removeClass("glowing-border");
        }
      });
    });
    /*jslint unparam: false*/

  }

  //  function for the column re-roder
  function reOrderWidgets(isOn) {
    var arrayDiv = [];
    var i = 0;
    if (isOn) {
        exitWithoutSaveFlag = true;
        $(".re-order-options-div").slideDown(500);
        $(".draggable-columns").each(function () {
          $(this).removeClass("drop-widget").removeClass("ui-droppable");
        });
        $(function () {
          $(".draggable-columns").draggable({
            disabled: false,
            greedy: true,
            opacity: 0.70,
            appendTo: "body",
            zIndex: 10000,
            containment: "#containment-wrapper",
            scroll: false,
            stop: function () {
              $(this).css("z-index", 10000);
              if (!$(this).attr('data-index')) {
                  $(this).attr("data-index", i);
                  arrayDiv.push(i);
                  console.log(arrayDiv);
                  i++;
              }
            }
          });
          $(".draggable-columns").droppable({ disabled: true });
          $(".draggable-columns").droppable({ revert: true });
          $(".draggable-columns").draggable({ revertDuration: 200 });
        });
    } else {
        $(function () {
          $(".draggable-columns").draggable({ disabled: true });
          $(".draggable-columns").droppable({ disabled: false });
          $(".draggable-columns").droppable({ revert: false });
          $(".draggable-columns").draggable({ revertDuration: 200 });
        });

        $(".draggable-columns").each(function () {
          $(this).addClass("drop-widget").addClass("ui-droppable");
        });
        $(".re-order-options-div").slideUp();
        i = 0;
        arrayDiv = [];
    }
    return arrayDiv;
  }

  function loadPageTemplate(ef) {
      
    //  reading the content from the sub template file
    editFlag = ef;
    var currentURL = _siteroot + "templates/main/layouts/" + subTmpl;
    $(".preview-page-template").hide();
    if (editFlag === true) {
        currentURL = _siteroot + "templates/main/edit-templates/" + subTmpl;
        $(".preview-page-template").show();
    }
    if (subTmpl === "header.phtml" || subTmpl === "footer.phtml") {
        $(".preview-page-template").hide();
    }

    showLoader(_notifications.loadingLayouts);

    $.ajax({
      url: currentURL,
      dataType: "html"
    }).done(function (data) {
        hideLoader();

        $(".content-data").find(".temp").remove();
        $(".content-data").html("<div class='temp'></div>");
        $(".content-data").find(".temp").html(data);

        var temp = (editFlag === true) ? $(".content-data").find(".temp").html() : $(".content-data").find(".container").html();

        $(".content-data").append(temp);
        $(".content-data").find(".column").each(function () {
          $(this).addClass('callout panel');
        });
        $(".content-data").find(".temp").remove();

        // checking sidebar class onload
        sideBarClass = $(".content-data .row:first").attr("class").match(/sidebar-[\w-]*\b/);

        addDroppableClass();

        $(".edit-widget").show().on('click', editWidgetHandler);
        $(".delete-widget").show().on('click', deleteWidgetHandler);
        $(".temp-image").show();

        // load sidebar data if sidebar.phtml exists
        $.get(_siteroot + "templates/main/edit-templates/sidebar.phtml").done(function(data,statusText, xhr){
            if(xhr.status == 200){
                sidebarData = data;
                $('[name=sidebar-select] option').filter(function() { 
                    return ($(this).attr("value") == sideBarClass);
                }).prop('selected', true);
                $("#sidebar-select").change();
            }
        });
      
    });
    //  reading the content from the sub template file end
  }
  
  function _checkForExistingWidgetFile(files){
    for(var i=0;i < files.length; i++){
        if(files[i]){
          $.get(files[i]).done(function(data, statusText, xhr){
            var status = xhr.status;
            if(!(status==200)){
                return files[i];
            }
          });
        } else {
            return false;
        }
    }
    return true;
  }
  
  function checkForExistingPageTemplate() {
    $.get(_siteroot + "templates/main/edit-templates/" + subTmpl)
        .done(function (data) {
            if(data === '')
                loadPageTemplate(false);
            else
                loadPageTemplate(true);
        }).fail(function(){
            loadPageTemplate(false);
        });
    }

    function addSideBar(sbPosition){
        
        var sbColumn = '<div class="column large-3 medium-3 small-3 sidebar">'+sidebarData+'</div>';
        $("#containment-wrapper").find(".content-data").removeClass("large-12").addClass("large-9");
        $("#containment-wrapper").find(".sidebar").remove();
        $("#containment-wrapper").find(".content-data .row:first").removeClass("sidebar-left sidebar-right sidebar-none");
        $("#containment-wrapper").find(".content-data .row:first").addClass(sbPosition);
        if(sbPosition == "sidebar-left"){
            $("#containment-wrapper").prepend(sbColumn);
        }
        else if(sbPosition == "sidebar-right"){
            $("#containment-wrapper").append(sbColumn);
        }
        else{
            $("#containment-wrapper").find(".content-data").addClass("large-12").removeClass("large-9");
        }
    }
  
  $(document).ready(function () {
    // grab all the data parsed to the page
    
    editFlag = $('[data-edit-flag]').data('edit-flag');
    subTmpl = $('[data-sub-tmpl]').data('sub-tmpl');
    tmplName = $('[data-tmpl-name]').data('tmpl-name');
    tmplURL = $('[data-tmpl-url]').data('tmpl-url');
    saveTemplateURL = $('[data-save-template-url]').data('save-template-url');
    saveWidgetFilesURL = $('[data-save-widget-files-url]').data('save-widget-files-url');
    //getWidgetURL = $('[data-get-widget-url]').data('get-widget-url');
    addWidgetURL = $('[data-add-widget-url]').data('add-widget-url');
    deleteWidgetURL = $('[data-delete-widget-url]').data('delete-widget-url');
    checkForExistingWidgetURL = $('[data-check-for-existing-widget-url]').data('check-for-existing-widget-url');
    widgetConfigURL = $('[data-widget-config-url]').data('widget-config-url');
    _siteroot = $('[data-siteroot]').data('siteroot');
    
    //showPreviewLink = $('[data-show-preview-link]').data('show-preview-link');
    //backToPageLayoutsURL = $('[data-back-to-page-layouts-url]').data('back-to-page-layouts-url');

    //console.log(tmplURL+"module/TemplateBuilder/view/template-builder/toolbox/img/header-slider_template1.png");
    widgetImages = {
        'template1': {
            'HeroImageWidget': tmplURL+"module/TemplateBuilder/view/template-builder/toolbox/img/header-slider_template2.png",
            'Booking': tmplURL+"module/TemplateBuilder/view/template-builder/toolbox/img/header-booking_template1.png",
            'CorporateLogo': tmplURL+"module/TemplateBuilder/view/template-builder/toolbox/img/header-logo_template1.png"
        },
        'template2': {
            'HeroImageWidget': tmplURL+"module/TemplateBuilder/view/template-builder/toolbox/img/header-slider_template2.png",
            'Booking': tmplURL+"module/TemplateBuilder/view/template-builder/toolbox/img/header-booking_template2.png",
            'CorporateLogo': tmplURL+"module/TemplateBuilder/view/template-builder/toolbox/img/header-logo_template2.png",
            'SpecialOffers': tmplURL+"module/TemplateBuilder/view/template-builder/toolbox/img/header-special-offers_template2.png",
        }
    };
    
    var addGlobalFlag = false;

    //  undo/lock/reset for column -reorder
    var indexArray;
    $(".draggable-columns").draggable({ disabled: false });
    
    $("#sidebar-select").change(function(){
        sidebarPosition = $(this).val();
        addSideBar(sidebarPosition);
    });
    
    $("#reorderSwitch").click(function () {
      var isOn = $(this).is(':checked');
      $(".widget-drawer").click();
      indexArray = reOrderWidgets(isOn);
    });
    $(".reorder-menu").hide();
    $("#lockReOrder").click(function () {
      $("#reorderSwitch").click();
      $(".draggable-columns").each(function () {
        $(this).removeAttr("data-index");
      });
    });

    $("#undoReOrder").click(function () {
      console.log(indexArray);
      var t = indexArray.pop();
      $(".draggable-columns").each(function () {
        if ($(this).attr('data-index') === t) {
          $(this).removeAttr("style");
          $(this).removeAttr("data-index");
        }
      });
    });

    $("#revert").click(function () {
      $(".draggable-columns").each(function () {
        $(this).removeAttr("style");
      });
    });
    //  undo/lock/reset end

    //  include header and footer
    $('#includehf').change(function () {
      if ($(this).is(":checked")) {
        addGlobalFlag = true;
        showLoader(_notifications.loadingHeaderFooter);
        $.when(
          $.ajax({
            url: _siteroot + "templates/main/edit-templates/header.phtml",
            dataType: "text"
          }),
          $.ajax({
            url: _siteroot + "templates/main/edit-templates/footer.phtml",
            dataType: "text"
          })
        ).done(function (header, footer) {
          hideLoader();
          $(".header-data").empty().html(header[0]);
          $(".header-data").show();
          $('.header-data .edit-widget').hide();
          $('.header-data .delete-widget').hide();

          $(".footer-data").empty().html(footer[0]);
          $(".footer-data").show();
          $('.footer-data .edit-widget').hide();
          $('.footer-data .delete-widget').hide();
        });

      } else {
        addGlobalFlag = false;
        $(".header-data").empty();
        $(".footer-data").empty();

        $(".header-data").hide();
        $(".footer-data").hide();
      }
    });
    //  include header and footer end

    //  save template with widget
    $(".save-tmpl").click(function (e) {
      e.preventDefault();

      $(".edit-widget").hide();
      $(".delete-widget").hide();
      $(".temp-image").hide();
      $(".content-data .column").each(function () {
        $(this).removeClass('callout panel');
      });
      var templateData = {
        'add-global-flag': addGlobalFlag,
        'sub-tmpl-name': encodeURIComponent(subTmpl),
        'sub-tmpl-data': encodeURIComponent($(".content-data").html())
      };
      if($("#sidebar-select").length > 0)
      {
          templateData["sidebar-position"] = sidebarPosition;
      }

      showLoader(_notifications.saveLayouts);

      $.ajax({
        type: 'POST',
        url: saveTemplateURL,
        data: templateData,
        dataType: "JSON",
        success: function () {
          alert('Templates created successfully!');
        },
        error: function () {
          alert('Failed to config template!');
        }
      }).done(function () {
        hideLoader();

        exitWithoutSaveFlag = false;
        $.get(_siteroot + "templates/main/edit-templates/" + subTmpl)
          .done(function (data) {
            if(data === '')
                loadPageTemplate(false);
            else
                loadPageTemplate(true);
          }).fail(function(){
            loadPageTemplate(false);
        });
      });
    });
    //  save template with widget end

    //  exit from template builder
    $(".exit").click(function () {
      if (exitWithoutSaveFlag) {
        if (confirm("Are you sure you want to exit without saving the template?")) {
          window.location.href = _siteroot + 'toolbox';
        }
        return false;
      }

      window.location.href = _siteroot + 'toolbox';
    });

    // load the selected page template
    checkForExistingPageTemplate();

    //  slick(Slide Widgets) display for the widgets
    $('.widget-list-row div').removeClass("ui-draggable-handle");

    $(".widget-list-row div").click(function () {
      $(".widget-slick div").css("background-color", "");
      $(this).css("background-color", "grey");
      $("#selected-template").attr("value", $(this).attr("data-tmplId"));
    });

    //  widget filter by text(Auto complete)
    //  search widget autocomplete
    var availableTags = [];
    $('.draggable-widget').each(function () {
      availableTags.push($(this).attr("data-widget"));
    });

    $(".search-widget").autocomplete({
      source: availableTags,
      autoFocus: true
    });
    $('.ui-autocomplete').addClass('f-dropdown');// required for jquery autocomplete and foundation issue
    $('.ui-helper-hidden-accessible').hide();

    $(".search-widget").keyup(function () {
      var srch = $(this).attr("value").toUpperCase();
      $('.widget-list-row .draggable-widget').each(function () {
        var widget = $(this).attr("data-widget").toUpperCase();
        if (widget.indexOf(srch) > -1) {
          $(this).show();
        } else {
          $(this).hide();
        }
      });
    });
    // end widget filter

    //  search widget autocomplete end

    $('.left-off-canvas-toggle').click(function () {
        $('.tab-bar, .main-section').addClass('large-10');
    });
    $('.exit-off-canvas').click(function () {
        $('.tab-bar, .main-section').removeClass('large-10');
    });

    $('.cancel-modal').click(function (e) {
      e.preventDefault();
      
      $('#' + $(this).closest('.reveal-modal').attr('id')).foundation('reveal', 'close');
    });

    $(document).foundation();
  });
}(window.jQuery, window, document));