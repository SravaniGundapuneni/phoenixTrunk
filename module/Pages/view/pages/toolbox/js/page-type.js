$(document).ready(function () {
  var editItemContent = $('.editItemContent');

  if ($("input[name='isContent']").val() === true) {
    editItemContent.find('.startDate').hide();
    editItemContent.find('.autoExpire').hide();
    editItemContent.find('.additionalParams').hide();
    editItemContent.find('.pageRates').hide();
    editItemContent.find('.eventName').hide();
    editItemContent.find('.selectRates').hide();
  }

  $("select[name='pageType']").change(function () {
    switch ($(this).val()) {
    case 'contentpage':
      editItemContent.find('.startDate').hide();
      editItemContent.find('.autoExpire').hide();
      editItemContent.find('.additionalParams').hide();
      editItemContent.find('.pageRates').hide();
      editItemContent.find('.eventName').hide();
      editItemContent.find('.selectRates').hide();
      break;
    case 'landingpage':
      editItemContent.find('.startDate').show();
      editItemContent.find('.autoExpire').show();
      editItemContent.find('.additionalParams').show();
      editItemContent.find('.pageRates').show();
      editItemContent.find('.eventName').show();
      editItemContent.find('.selectRates').show();
      break;
    }
  });
});

