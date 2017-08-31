var app = app || {};

if (_ === undefined) {
	throw new Error('Underscore dependency');
}

app.flexbox = (function() {
	var isInitialized = true,
		$items;

	function hasFlexbox() {
		var hasFlexbox = false,
			s = document.body || document.documentElement,
			style = s.style;

		if (style.webkitFlexWrap === '' || style.msFlexWrap === '' || style.flexWrap === '') {
			hasFlexbox = true;
		}

		return hasFlexbox;
	}

	function init($items) {
		$('#fileViewer').on('append-viewer-items', function() {
			var $items = [].slice.call(arguments, 1); // first argument is an event
			setItems($items);
			setHeights();
		});
	}

	function setHeights() {
		_setItemHeights(_findMaxHeight());
	}

	function setItems($theItems) {
		$items = $theItems;
	}

	function _findMaxHeight() {
		var maxHeight = 0,
			itemHeight;

		_.each($items, function(item) {
			itemHeight = parseInt($(item).outerHeight());

			if (itemHeight > maxHeight) {
				maxHeight = itemHeight;	
			}
		});

		return maxHeight;
	}

	function _setItemHeights(maxHeight) {
		_.each($items, function(item) {
			$(item).css('height', maxHeight);
		});
	}

	return {
		hasFlexbox: hasFlexbox,
		init: init,
		setHeights: setHeights,
		setItems: setItems
	};
})();
