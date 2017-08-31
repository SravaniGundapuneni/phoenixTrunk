$(function() {
	if (app === undefined) {
		throw new Error('undefined app');
	}

	app.notifications = (function() {
		var successTemplate,
			errorTemplate,
			message,
			duration,
			ALERT_ERROR = 'alert-error',
			ALERT_SUCCESS = 'alert-success',
			DEFAULT_DURATION = 15000;

		function init() {
			_initTemplates();
		}

		function clear() {
			_fadeOutAndRemove('alert-success');
			_fadeOutAndRemove('alert-error');
		}

		function cursorDefault() {
			$(document.body).css({ 'cursor': 'default' });		
		}

		function cursorWait() {
			$(document.body).css({ 'cursor': 'wait' });
		}

		function error(message, duration) {
			_setDuration(duration);
			_setMessage(message);
			_showNotification(errorTemplate);
			_setTimeout(ALERT_ERROR);
		}

		function success(message, duration) {
			_setDuration(duration);
			_setMessage(message);
			_showNotification(successTemplate);		
			_setTimeout(ALERT_SUCCESS);
		}

		function _fadeOutAndRemove(className) {
			$('.' + className).fadeOut('normal', function() { $(this).remove(); });	
		}

		function _initTemplates() {
			successTemplate = successTemplate || Handlebars.compile($('#successNotificationTemplate').html());
			errorTemplate   = errorTemplate   || Handlebars.compile($('#errorNotificationTemplate').html());
		}

		function _setDuration(newDuration) {
			duration = newDuration ? newDuration : DEFAULT_DURATION;
		}

		function _setMessage(newMessage) {
			message = newMessage;
		}

		function _setTimeout(alertClass) {
			window.setTimeout(function() { _fadeOutAndRemove(alertClass); }, duration);		
		}

		function _showNotification(template) {
			$('#notifications').append(template({ alertMessage: message }));
		}

		return {
			init: init,
			clear: clear,
			error: error,
			success: success,
			cursorWait: cursorWait,
			cursorDefault: cursorDefault
		};
	})();

	app.notifications.init();
});