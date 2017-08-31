/*jslint browser: true*/
/*globals window, _site_root*/

(function ($, window) {

  function enableSubmit() {
    $('input[type="submit"]').prop('disabled', false);
  }

  function disableSubmit() {
    $('input[type="submit"]').prop('disabled', true);
  }

  function getFormValues($form) {
    return {
      bookingChannelType : $form.find('#bookingchannel_type').val(),
      companyNameCode : $form.find('#company_name_code').val(),
      dataSource : $form.find('[name="data_source"]:checked').val(),
      hotelId : $form.find('#hotel_id').val(), // TODO: make hotelIDs multiple
      requestorIDID : $form.find('#requestorid_id').val(),
      requestorIDType : $form.find('#requestorid_type').val(),
      fallbackRate: $form.find('#fallback_rate').val(),
      lookaheadDays: $form.find('#lookahead_days').val(),
      messageID: $form.find('#message_id').val(),
      headerUsername: $form.find('#header_username').val(),
      headerPassword: $form.find('#header_password').val()
    };
  }

  function saveSettings(settings) {
    return $.ajax({
      url : window.location.origin + _site_root + 'sockets/IHotelier/settings',
      type : 'post',
      data : settings
    });
  }

  $(function () {
    $('#ihotelier_settings_form').submit(function (e) {
      e.preventDefault();

      // TODO: validation //
      var $form = $(this);

      disableSubmit();

      saveSettings(getFormValues($form))
        .done(function (data) {
          enableSubmit();
        })
        .fail(function () {
        });
    });
  });

}(window.jQuery, window));