/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function showLoader(notificationMessage) {
    var loader = $('#ajax-loader-overlay');
    loader.fadeIn('fast');
    loader.find("p").html(notificationMessage);
}

function hideLoader() {
  var loader = $('#ajax-loader-overlay');
  loader.fadeOut('fast');
  loader.find("p").empty();
}

var _notifications = {
        loadingLayouts      :   'Layout is being loaded!',
        saveLayouts         :   'Template is being saved!',
        widgetSave          :   'Widget is being saved!',
        loadingWidgetFiles  :   'Widget files are being loaded!',
        loadingHeaderFooter :   'Header/Footer files are being loaded!',
        deleteWidget        :   'Widget is being deleted!',
        deleteTemplate      :   'Template is being deleted!',
};


