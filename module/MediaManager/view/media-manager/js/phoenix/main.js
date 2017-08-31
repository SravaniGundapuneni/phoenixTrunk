$(function() {
	if (app === undefined) {
		throw new Error('App is undefined');
	}

	app.mediaManager = (function() {

		function launch() {
			var $deferred = $.Deferred();

			app.filetree.init()
				.done(function() {
					app.search.init();
					app.search.openOffCanvas();
					app.explorer.init();
					_initializeUploader();
					app.filetree.initializeTree();
					app.contextMenu.init();
					app.flexbox.init();	
					$deferred.resolve();
				})
				.fail($deferred.reject);

			return $deferred;
		}

		function loadFiles() {
			var $deferred = new $.Deferred();
			app.filetree.loadFiles($deferred);
			return $deferred;
		}

		function _initializeUploader() {
			if (app.readOnly === 'false') {
				app.uploader.init();
			}
		}

		return {
			launch: launch,
			loadFiles: loadFiles
		};
	})();

	if (app.isAttachments === 'false') {
		app.mediaManager.launch().done(function() {
			if (!app.flexbox.hasFlexbox()) {
				app.flexbox.setItems($('.fileViewerImageContainerInner'));
				app.flexbox.setHeights();
			}
			$(document).foundation();
		});
	} else {
		app.mediaManager.loadFiles()
			.done(function() {
				$('#addMediaSpinner').hide();
				$('#add-media').css({ display: 'block' });
			});
	}
});
