$(function() {
	if (app === undefined) {
		throw new Error('app is undefined');
	}

	app.explorer = (function() {

		var ROOT = '/';

		function init() {
			_initializeEventHandlers();
		}

		function _containContextMenuToFileExplorer() {
			$('#fileExplorer').on('contextmenu','.explorerFile', function(event) {
				event.stopPropagation();
			});
		}

		function _initializeEventHandlers() {
			_initializeFileExplorer();
			_containContextMenuToFileExplorer();
			_initializeFileExplorerListeners();
		}

		function _initializeFileExplorer() {
			$('#fileExplorer').fileTree({
				root: ROOT,
				expandSpeed: 250,
				collapseSpeed: 250
			});
		}

		function _initializeFileExplorerListeners() {
			$('#fileExplorer')
				.on('filetreeexpand', _fileTreeExpandHandler)
				.on('filetreecollapse', _fileTreeCollapseHandler)
				.on('filetreeclicked', _fileTreeClickedHandler);
		}

		function _fileTreeClickedHandler(e, data) {}

		function _fileTreeExpandHandler(e, data) {
			$('#openedFolderName').fadeOut('slow', function() {
				$(this).html(data.value).fadeIn('slow');
				$(this).attr('data-rel', data.rel);
			});
		}

		function _fileTreeCollapseHandler(e, data) {
			var parentPath = _getParentPath(data.rel);
			$('#openedFolderName').fadeOut('slow', function() {
				$(this).html(_getParentFolderName(data.rel)).fadeIn('slow');
				$(this).attr('data-rel', parentPath);
			});
		}

		function _getParentFolderName(path) {
			var parentPath = _getParentPath(path),
				parentFolderName = 'FILE BROWSER';

			if (parentPath) {
				parentFolderName = _getParentFolder(parentPath).text();
			}

			return parentFolderName;
		}

		function _getParentFolder(parentPath) {
			return $("a[rel='" + parentPath + "']");
		}

		function _getParentPath(path) {
			path = path.substring(0, path.length -1);
			path = path.substring(0, path.lastIndexOf('/') + 1);
			return path;	
		}

		return {
			init: init
		};
	})();
});
