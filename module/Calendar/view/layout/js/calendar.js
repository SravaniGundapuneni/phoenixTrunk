/*jslint browser: true*/
/*globals window*/

(function ($, _, ko, moment) {

  var GET_ACTION, SAVE_ACTION, DELETE_ACTION;

  /**
   * Class CalendarDate
   *
   * date:    The day of the month
   * enabled: True if part of the current month, false if part of next or previous month
   * events:  Observable array of events taking place during this date
   */
  function CalendarDate(date) {
    this.date = date;
    this.enabled = date !== '';
    this.events = ko.observableArray([]);
  }

  /**
   * Class CalendarEvent
   * 
   * id:      The database ID of the event
   * title:   The event title
   * details: The event description
   * loading: Observable used to show AJAX loading indicators
   */
  function CalendarEvent(event) {
    this.id = event.eventId;
    this.title = event.title;
    this.details = event.description;
    this.loading = ko.observable(false);
  }

  /**
   * Class ScheduledEvent
   *
   * eventId:        The database ID of the event
   * calendarItemID: The database ID of the calendar instance of the event
   * eventtitle:     The title of the event
   * startDate:      The start timestamp for the event
   * endDate:        The end timestamp for the event
   * loading:        Observable to show AJAX loading indicators
   */
  function ScheduledEvent(event) {
    this.eventId = event.eventId;
    this.calendarItemId = event.calendarItemId;
    this.eventtitle = event.eventtitle;
    this.startDate = event.startDate.split(' ')[0];
    this.endDate = event.endDate.split(' ')[0];
    this.loading = ko.observable(false);
  }

  /**
   * Generate a new calendar month
   * 
   * @param  {object} date The moment datetime object for the given month/year
   * @return {array}       Array of CalendarDate objects
   */
  function generateCalendarMonth(date) {
    var padStart = parseInt(date.format('E'), 10),
      lastDay = date.add(1, 'months').subtract(1, 'days').date(),
      padEnd = (padStart + lastDay) % 7,
      days = [],
      i;

    if (padStart > 6) { padStart = 0; }
    if (padEnd > 0) { padEnd = 7 - padEnd; }

    for (i = 1; i <= padStart; i++) { days.push(new CalendarDate('')); } // add previous month days
    for (i = 1; i <= lastDay; i++) { days.push(new CalendarDate(i)); } // add current month days
    for (i = 1; i <= padEnd; i++) { days.push(new CalendarDate('')); } // add next month days

    return days;
  }

  /**
   * Get todays date
   * 
   * @return {object} The moment datetime object for the current date
   */
  function getCurrentDate() {
    return moment(moment().format('MMMM') + ', ' + moment().format('YYYY'), 'MMMM, YYYY');
  }

  /**
   * Set the calendar view model calendar data; must be invoked with .call() in
   * order to pass the context of the view model
   * 
   * @param {object} date The moment datetime object to build the calendar from
   */
  function setCalendar(date) {
    this.calendarMonth(date.format('MMMM'));
    this.calendarYear(date.year());
    this.calendarDays(generateCalendarMonth(date));
    this.scheduledEvents.valueHasMutated();
  }

  /**
   * Filter a list of events by a given date
   * 
   * @param  {array}  events The list of events to filter
   * @param  {int}    date   The day of the month to filter against
   * @param  {string} month  The full name of the month to filter by
   * @param  {itn}    year   The 4 digit year to filter by
   * @return {array}         The filtered list of events
   */
  function filterEventsByDate(events, date, month, year) {
    return _.filter(events, function (event) {
      var startDate = (event.hasOwnProperty('startDate')) ? moment(event.startDate, 'YYYY-M-D') : false,
        dateMatches = startDate && startDate.date() === date,
        monthMatches = startDate && startDate.format('MMMM') === month,
        yearMatches = startDate && startDate.year() === year;

      return dateMatches && monthMatches && yearMatches;
    });
  }

  /**
   * Get a list of configured events
   * @return {$.deferred} The jQuery AJAX deferred object
   */
  function getEventData() {
    return $.ajax({
      type: 'post',
      dataType: 'json',
      url: GET_ACTION
    });
  }

  /**
   * Save an event to the calendar
   * 
   * @param  {int}        eventId The event ID to save
   * @param  {string}     date    The calendar date to save the event to in YYYY-M-D format
   * @return {$.deferred}         The jQuery AJAX deferred object
   */
  function saveEventToDate(eventId, date) {
    return $.ajax({
      type: 'post',
      dataType: 'json',
      url: SAVE_ACTION,
      data: { event: eventId, date: date }
    });
  }

  /**
   * Remove an event from the calendar
   * 
   * @param  {int}        calendarEventId The ID of the event to remove from the calendar
   * @return {$.deferred}                 The jQuery AJAX deferred object
   */
  function removeEventFromDate(calendarEventId) {
    return $.ajax({
      type: 'post',
      dataType: 'json',
      url: DELETE_ACTION,
      data: { id: calendarEventId }
    });
  }

  /**
   * Calendar ViewModel
   */
  function CalendarViewModel() {
    var self = this, // cache this so we aren't constantly passing context to jquery/underscore
      currMonth = getCurrentDate();

    // calendar data
    self.calendarMonth = ko.observable();
    self.calendarYear = ko.observable();
    self.calendarDays = ko.observableArray([]);
    self.calendarEvents = ko.observable([]);
    self.scheduledEvents = ko.observableArray([]);
    self.selectedDate = ko.observable();

    // interface data
    self.loading = ko.observable(true);
    self.error = ko.observable(null);

    // subscribe to events to keep calendar in sync when they are updated
    self.scheduledEvents.subscribe(function (newEvents) {
      var filteredEvents, i, j;

      for (i = 0, j = self.calendarDays().length; i < j; i++) {
        filteredEvents = filterEventsByDate(newEvents, self.calendarDays()[i].date, self.calendarMonth(), self.calendarYear());
        self.calendarDays()[i].events(filteredEvents);
      }
    });

    /**
     * Navigate to the previous month
     */
    self.previousMonth = function () {
      var previousMonth = moment(self.calendarMonth() + ', ' + self.calendarYear(), 'MMMM, YYYY').subtract(1, 'month');

      setCalendar.call(self, previousMonth);
    };

    /**
     * Navigate to the next month
     */
    self.nextMonth = function () {
      var nextMonth = moment(self.calendarMonth() + ', ' + self.calendarYear(), 'MMMM, YYYY').add(1, 'month');

      setCalendar.call(self, nextMonth);
    };

    /**
     * Navigate to today's month
     */
    self.currentMonth = function () {
      setCalendar.call(self, getCurrentDate());
    };

    /**
     * Remove an event from the calendar
     * 
     * @param  {object} calendarEvent The standard object calendar event to remove from the calendar
     * @return {void}
     */
    self.removeEvent = function (calendarEvent) {
      calendarEvent.loading(true);

      removeEventFromDate(calendarEvent.calendarItemId).done(function (response) {
        if (response.success) {
          self.scheduledEvents.destroy(calendarEvent);
        } else {
          calendarEvent.loading(false);
          self.error('Could not delete event from calendar');
        }
      });
    };

    /**
     * Open the 'add events' modal
     * 
     * @param  {function} calendarDate The knockout CalendarDate observable to select as the current date
     * @return {void}
     */
    self.openAddEventsModal = function (calendarDate) {
      self.selectedDate(calendarDate.date);
      // TODO: Update event-specific indicators in the modal
      $('#addEventModal').foundation('reveal', 'open');
    };

    /**
     * Close the 'add event' modal
     */
    self.closeAddEventsModal = function () {
      $('#addEventModal').foundation('reveal', 'close');
    };

    /**
     * Save an event to the selected date on the calendar
     * 
     * @param  {object} calendarEvent The CalendarEvent object to save
     * @return {void}
     */
    self.saveEventToDate = function (calendarEvent) {
      var dateStr = self.calendarMonth() + ' ' + self.selectedDate() + ', ' + self.calendarYear(),
        date = moment(dateStr, 'MMMM D, YYYY').format('YYYY-M-D');

      calendarEvent.loading(true);

      saveEventToDate(calendarEvent.id, date).done(function (response) {
        if (response.success) {
          response.event.eventtitle = calendarEvent.title;
          self.scheduledEvents.push(new ScheduledEvent(response.event));
        } else {
          self.error('Could not save new event to calendar');
        }
        calendarEvent.loading(false);
      });
    };

    /**
     * Close the error alert box manually
     */
    self.clearError = function () {
      self.error(null);
    };

    // generate the calendar
    setCalendar.call(self, currMonth);

    // populate the calendar
    getEventData().done(function (events) {
      self.scheduledEvents(events.scheduled.map(function (event) {
        return new ScheduledEvent(event);
      }));

      self.calendarEvents(events.all.map(function (event) {
        return new CalendarEvent(event);
      }));

      self.loading(false);

      $(document).foundation('reflow', 'tooltip');
    });
  } /* End of Calendar ViewModel */

  $(function () {
    GET_ACTION =  $('[data-calendar-socket-get]').data('calendar-socket-get');
    SAVE_ACTION = $('[data-calendar-socket-save]').data('calendar-socket-save');
    DELETE_ACTION = $('[data-calendar-socket-delete]').data('calendar-socket-delete');

    ko.applyBindings(new CalendarViewModel());

    $(document).foundation();
  });

}(window.jQuery, window._, window.ko, window.moment));