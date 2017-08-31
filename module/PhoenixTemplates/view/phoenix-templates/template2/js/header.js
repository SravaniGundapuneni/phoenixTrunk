/*jslint browser: true*/
/*globals window*/

(function ($) {

  var pageKey;

  /**
   * Move the template down if the toolbox menu is on the page
   * 
   * @param  {int} toolboxHeight The height of the toolbox menu
   */
  function initTemplatePreview(toolboxHeight) {
    $('body').css('top', toolboxHeight);
  }

  /**
   * Adjust the template's fixed header if the toolbox menu is on the page
   * 
   * @param  {int} toolboxHeight The height of the toolbos menu
   */
  function adjustFixedHeader(toolboxHeight) {
    $('.contain-to-grid.fixed').css('top', toolboxHeight);
  }

  /**
   * Initialize the fixed page header
   */
  function initFixedHeader() {
    $('.contain-to-grid').addClass('fixed');
  }

  /**
   * Initialize the sub menu positioning and responsive adjustments
   */
  function initSubmenuSizing() {
    var header = $('.contain-to-grid'),
      submenu = header.find('#secondBar');

    // ensure the menu remains sized properly on resize
    if (submenu.length > 0) {
      $(window).resize(function () {
        var topbar = submenu.closest('.top-bar'),
          rightPos = $(window).width() - (Math.round(topbar.offset().left) + topbar.outerWidth()),
          margin = (header.outerWidth() - topbar.outerWidth()) - rightPos;

        submenu.css({
          'margin-left': '-' + margin + 'px',
          'width': header.outerWidth()
        });
      });
    }

  }

  /**
   * Initialize the booking widget positioning
   */
  function initBookingPositioning() {
    var booking = $('[data-draggedwidget="Booking"]');

    if (pageKey !== 'home') {
      // wait for the event loop to initialize
      setTimeout(function () {
        booking.find('#minify-booking-widget').click();
      }, 0);
    }

    $(window).resize(function () {
      if (!booking.hasClass('minified')) {
        var margin = -(booking.outerWidth() / 2);

        booking.css('margin-left', margin);
      }
    }).on('bookingWidgetMinified', function () {
      booking.css('margin-left', 0).addClass('minified');
    });

    booking.find('#booking-widget').fadeIn('fast');
  }

  /**
   * DOM Ready
   */
  $(function () {
    var adminHeader = $('.toolboxAdminToolbar.header'),
      adminHeaderHeight;

    pageKey = $('[data-pageKey]').data('page-key');

    initFixedHeader();
    initSubmenuSizing();
    initBookingPositioning();

    // if the toolbox is on the page, move the page down so it's visible
    if (adminHeader.length > 0) {
      adminHeaderHeight = adminHeader.outerHeight();

      adjustFixedHeader(adminHeaderHeight);
      initTemplatePreview(adminHeaderHeight);
    }

    // initialize resize-dependant behavior
    $(window).trigger('resize');
  });

}(window.jQuery));