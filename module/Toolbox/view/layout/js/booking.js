/*jslint browser: true*/
/*globals app*/

$(function () {

  if (app === undefined) {
    throw new Error('app is undefined in ihotelier.js');
  }

  var SOCKET_PATH = 'sockets/IHotelier/dailyRate';

  $.ajax({
    type : "GET",
    url : app.constants.siteroot + SOCKET_PATH,
    data : {
      callType : 'dailyRate'
    }
  }).done(function (response) {
    $(window).trigger({
      type: 'daily-rate',
      rateInfo: response.rateInfo
    });
  });
});