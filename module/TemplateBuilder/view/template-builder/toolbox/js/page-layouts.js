/*jslint browser: true*/
/*globals window, document, confirm*/

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

(function ($, document) {

  function capitalize(s) {
    return s && s[0].toUpperCase() + s.slice(1);
  }

  function showTemplatePage(page) {
    $('.page-layouts [data-page="' + page + '"]').show().siblings(':not([data-page="' + page + '"])').hide();
  }

  $(function () {

    var editFlag = false,
      tmplURL = $('[data-tmpl-url]').data('tmpl-url'),
      subTmplDeleteURL = $('[data-sub-tmpl-delete-url]').data('sub-tmpl-delete-url'),
      widgetsConfig = $('[data-widgets-config]').data('widgets-config'),
      current_tmpl = $('[data-current-tmpl]').data('current-tmpl');

    showTemplatePage(1);

    //  pagination
    $('ul.pagination li').click(function (e) {
      var btn = $(this),
        currentPage = btn.data('page');

      e.preventDefault();
      e.stopPropagation();

      btn.addClass('current').siblings('.current').removeClass('current');

      showTemplatePage(currentPage);
    });


    $('.cancel-overwrite-template').click(function () {
      $('#myModalOverwrite').foundation('reveal', 'close');
    });

    $('.overwrite-template').click(function () {
      $('#myModalOverwrite').foundation('reveal', 'close');
    });

    $(".add-page-template, .edit-page-template").click(function (e) {
      var btn = $(this);

      e.preventDefault();
      e.stopPropagation();

      if (btn.hasClass('edit-page-template')) {
        if (confirm('Are you sure you want to modify this template?')) {
          editFlag = true;
        } else {
          return false;
        }
      }

      var formData = '<input type="hidden" id="selected-template" name="selected-template" value="' + current_tmpl + '" />';
      formData += '<input type="hidden" id="sub-template" name="sub-template" value="' + btn.attr("data-tmpl-file") + '" />';
      formData += '<input type="hidden" id="edit-flag" name="edit-flag" value=' + editFlag + ' />';

      $('<form>', {
        "id": "modifyHeader",
        "html": formData,
        "action": widgetsConfig,
        "method": 'post'
      }).appendTo(document.body).submit();

    });

    $(".delete-page-template").click(function (e) {
      e.preventDefault();
      e.stopPropagation();

      var tmpl = $(this).data("tmpl-file"),
        modal = $('#deleteTemplateModal');

      modal.find('.delete-template').data('template', tmpl);
      modal.foundation('reveal', 'open');
    });

    $('.delete-template').click(function (e) {
      e.preventDefault();

      var template = $(this).data('template');

      showLoader(_notifications.deleteTemplate);

      $.ajax({
        type: 'POST',
        url: subTmplDeleteURL,
        data: { 'sub-tmpl-name': template },
        dataType: "JSON"
      }).done(function () {
        //  close the modal
        $('#deleteTemplateModal').foundation('reveal', 'close');

        // show the add control
        $('li[data-tmpl-file]').filter(function () {
          return $(this).data('tmpl-file') === template;
        }).find('.add-page-template').closest('li').show().siblings().hide();

        // if the header or footer was deleted, hide page templates
        if (template === 'header.phtml' || template === 'footer.phtml') {
          $('.page-layouts-wrap').slideUp();
        }

        hideLoader();
      });
    });

    $('.cancel-delete-template').click(function (e) {
      e.preventDefault();

      $('#myModal').foundation('reveal', 'close');
    });

    $(".page-layouts .template-block-wrap, .global-layouts .template-block-wrap").click(function () {
      var block = $(this),
        tmplName = block.data('tmpl-name'),
        fileName = block.data('tmpl-file'),
        fieldset = $("fieldset");

      if (block.hasClass('tmpl-options-div')) {
        block.find(".tmpl-options-div").show();
      }

      block.addClass('selected').closest('li').siblings().find('.selected').removeClass('selected');
      fieldset.find("legend").text(block.text());
      fieldset.load(block.attr("data-tmpl-file"));
      $("#sub-template").attr("value", fileName);
      $(".cur-tmpl h1").text(capitalize(fileName.replace('.phtml', '')));

      $.ajax({
        url: tmplURL + "/module/PhoenixTemplates/view/phoenix-templates/" + tmplName + "/" + fileName,
        dataType: "html"
      }).done(function (data) {
        fieldset.find(".temp").remove();
        fieldset.html("<div class='temp'></div>");
        fieldset.find(".temp").html(data);
        var temp = fieldset.find(".container").html();
        fieldset.append(temp);
        fieldset.find(".temp").remove();
      });
    });

    $(document).foundation();
  });
}(window.jQuery, document, window));