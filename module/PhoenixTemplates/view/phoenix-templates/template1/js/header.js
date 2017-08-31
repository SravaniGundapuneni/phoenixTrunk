/*jslint browser: true*/
/*globals window*/

(function ($, window) {
  var headerDockingBreakpoint = 10, // !! change the docking breakpoint here !!
    headerElementSelector = '#header-template1', // !! change the selector for the header here !!
    docked = {
      navclass: 'small-6',
      bookingclass: 'small-3'
    },
    undocked = {
      navclass: 'small-9',
      bookingclass: 'small-3'
    },
    navWidget,
    corporateLogoWidget,
    bookingWidget,
    bookingWidgetParent,
    bookingWidgetPos;

  function initializeWidgetSelectors() {
    navWidget = $('[class*="top-menu"]').closest('[class*="column"]');
    corporateLogoWidget = $('[data-draggedwidget="CorporateLogo"]');
    bookingWidget = $('[data-draggedwidget="Booking"]');
    bookingWidgetParent = bookingWidget.closest('.row');
    bookingWidgetPos = {
      top: bookingWidget.css('top'),
      left: bookingWidget.css('left')
    };
  }

  /**
   * Dock the corporate logo
   */
  function dockCorporateLogo() {
    corporateLogoWidget.addClass('docked');
  }

  /**
   * Undock the corporate logo
   */
  function undockCorporateLogo() {
    corporateLogoWidget.removeClass('docked');
  }

  /**
   * Determines whether or not the booking widget is docked
   * 
   * @return bool True if docked; false if not
   */
  function isBookingWidgetDocked() {
    return bookingWidget.find('#booking-widget').hasClass('docked');
  }

  /**
   * Dock the booking widget
   */
  function dockBookingWidget() {
    navWidget
      .removeClass(undocked.navclass)
      .addClass(docked.navclass);

    bookingWidget
      .css({ 'top': 0, 'left': 0, 'position': 'relative' })
      .removeClass(undocked.bookingclass)
      .addClass(docked.bookingclass)
      .detach()
      .insertAfter(navWidget)
      .find('#booking-widget')
      .addClass('docked');
  }

  /**
   * Undock the booking widget
   */
  function undockBookingWidget() {
    navWidget
      .removeClass(docked.navclass)
      .addClass(undocked.navclass);

    bookingWidget
      .css({ 'top': bookingWidgetPos.top, 'left': bookingWidgetPos.left, 'position': 'absolute' })
      .removeClass(docked.bookingclass)
      .addClass(undocked.bookingclass)
      .detach()
      .appendTo(bookingWidgetParent)
      .find('#booking-widget')
      .removeClass('docked');
  }

  /**
   * Initializes the docking behavior for the header on scroll.
   * 
   * @param top    int      The distance from the top to dock the header
   * @param dock   function The function to execute to dock the header
   * @param undock function The function to execute to undock the header
   */
  function initializeHeaderDocking(top, dockWidgets, undockWidgets) {
    // docking and undocking
    $(window).scroll(function () {
      var scrolltop = $(window).scrollTop();

      if (scrolltop >= top) {
        dockWidgets();
      } else if (scrolltop < top) {
        undockWidgets();
      }
    });
  }

  /**
   * Ensures the submenu fills the entire header and
   * not just it's own column
   */
  function initializeSubmenuSizing() {
    var submenu = $('#secondBar'),
      header = $(headerElementSelector);

    // ensure the menu remains sized properly on resize
    $(window).resize(function () {
      var right = header.hasClass('docked') ? -(bookingWidget.outerWidth()) : 0;

      submenu.css({
        'right': right,
        'width': header.outerWidth(),
        'z-index': -1
      });
    });

    // initialize submenu sizing
    $(window).trigger('resize');
  }

  /**
   * Initializes the header container and contained widgets
   * for proper docking styles and spatial arrangement
   */
  function initializeHeaderHeight() {
    var header = $(headerElementSelector);

    header.height(navWidget.height());
    bookingWidget.height('100%');

  }

  /**
   * Moves the template down to avoid concealing content by the toolbox menu
   * 
   * @param  {int} toolboxHeight The height, in pixels, of the toolbox header menu
   */
  function initTemplatePreview(toolboxHeight) {
    $('body').css('top', toolboxHeight);
  }

  /**
   * Adjust the fixed header so it is not concealed by the toolbox menu
   * 
   * @param  {int} toolboxHeight The height of the toolbox menu
   */
  function adjustFixedHeader(toolboxHeight) {
    $('.contain-to-grid.fixed').css('top', toolboxHeight);
  }

  /**
   * Document Ready
   */
  $(function () {
    var adminHeader = $('.toolboxAdminToolbar.header'),
      adminHeaderHeight;

    // add fixed class to prevent issues with fixed styling in the template builder
    $(headerElementSelector).closest('.contain-to-grid').addClass('fixed');

    initializeWidgetSelectors();

    initializeHeaderDocking(headerDockingBreakpoint, function () {
      var header = $(headerElementSelector);

      if (!header.hasClass('docked')) {
        /* !! place docking code here !! */

        header.addClass('docked');

        // corporate logo
        dockCorporateLogo();

        // booking widget
        if (!isBookingWidgetDocked()) {
          dockBookingWidget();
        }

        // topmenu
        $(window).trigger('resize');
      }
    }, function () {
      var header = $(headerElementSelector);

      if (header.hasClass('docked')) {
        /* !! place undocking code here !! */

        header.removeClass('docked');

        // corporate logo
        undockCorporateLogo();

        // booking widget
        if (isBookingWidgetDocked()) {
          undockBookingWidget();
        }

        // topmenu
        $(window).trigger('resize');
      }
    });

    initializeHeaderHeight();
    initializeSubmenuSizing();

    // if the toolbox is on the page, move the page down so it's visible
    if (adminHeader.length > 0) {
      adminHeaderHeight = adminHeader.outerHeight();

      adjustFixedHeader(adminHeaderHeight);
      initTemplatePreview(adminHeaderHeight);
    }

  });

}(window.jQuery, window));