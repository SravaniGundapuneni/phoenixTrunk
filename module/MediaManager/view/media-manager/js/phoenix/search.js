$(function() {
	if (app === undefined) {
		throw new Error('Undefined app');
	}

	app.search = (function() {
		var filesFromSearch,
			QUICK = 750,
			MODERATE = 500,
			OPAQUE = 1,
			TRANSPARENT = 0.75;

		function init() {	
			_initializeEventHandlers();		
		}

		function openOffCanvas() {
			$('#fileExplorerTab').addClass('openExplorer');
			$('#fileExplorerTab').find('i').switchClass('fa-arrow-right', 'fa-arrow-left');
			$('.off-canvas-wrap').foundation('offcanvas', 'show', 'move-right');
		}

		function closeOffCanvas() {
			$('#fileExplorerTab').removeClass('openExplorer');
			$('#fileExplorerTab').find('i').switchClass('fa-arrow-left', 'fa-arrow-right');
			$('.off-canvas-wrap').foundation('offcanvas', 'hide', 'move-right');
		}

		function _doSearch(query) {
			_setFiles(query);
		}

		function _explorerTabHandlers() {
			_explorerTabToggleHandler();
			_explorerTabHoverHandler();
		}

		function _explorerTabHoverHandler() {
			$(document).on({
				mouseenter: function() {
					$(this).fadeTo(MODERATE, OPAQUE);
				},
				mouseleave: function() {
					$(this).fadeTo(MODERATE, TRANSPARENT);
				}
			}, '#fileExplorerTab');
		}

		function _explorerTabToggleHandler() {
			$(document).on('click', '#fileExplorerTab', function() {
				$tab = $(this);
				if ($tab.hasClass('openExplorer')) {
					closeOffCanvas();
				} else {
					openOffCanvas();
				}
			});
		}

		function _initializeEventHandlers() {
			_searchHandlers();
			_explorerTabHandlers();
		}

		function _searchButtonHandler() {
			$(document).on('focus', '#fileManagerSearchBox', closeOffCanvas);
		}

		function _searchHandlers() {
			_searchButtonHandler();	
			_searchInputHandler();
		}

		function _searchInputHandler() {
			$(document).on('keyup', '#fileManagerSearchBox', function() {
				var userInput = $(this).val().toLowerCase();
				_doSearch(userInput);
			});
		}

		function _setFiles(query) {
			$('#fileViewerInner').empty();
			_setFilesFromSearch(query);

			if (_.isEmpty(filesFromSearch)) {
				$('#fileViewerInner').html('<div class="emptyViewer"><p>Search returned no results.</p></div>');
			} else {
				app.filetree.appendFiles(filesFromSearch);
				$('#fileViewer').trigger('append-viewer-items', $('.fileViewerImageContainerInner')); // used for flexbox ie workaround
			}
		}

		function _setFilesFromSearch(query) {
			filesFromSearch = _.filter(app.files, function(file) {
				return file.title.indexOf(query) !== -1;
			});
		}

		return {
			init : init,
			openOffCanvas: openOffCanvas,
			closeOffCanvas: closeOffCanvas
		};
	})();
});
