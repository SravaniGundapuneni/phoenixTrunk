$(function() {
	if (app === undefined) {
		throw new Error('Undefined app');
	}

	if (_ === undefined) {
		throw new Error('Underscore dependency');
	}

	if (Handlebars === undefined) {
		throw new Error('Handlebars dependency');	
	}

	app.contextMenu.editor = (function() {
		var DIALOG_WIDTH = 700,
			openGraphTemplate;

		function init() {
			_initializeTemplates();
			_initializeEventHandlers();	
		}

		function _initializeTemplates() {
			openGraphTemplate = Handlebars.compile($('#openGraphTemplate').html());
		}

		function openDialog($item, $deferred) {
			var fileId = $item.attr('data-fileid'),
				openGraphInputs;

			// clear dialog
			$('.openGraphItem').remove();

			// add initial blank fields row
			$('#openGraphList').prepend(openGraphTemplate([{}]));

			$.when(_loadAltText(fileId), _loadOpenGraphInfo(fileId), _loadSchemaDotOrgInfo(fileId))
				.done(function(altTextResponse, ogResponse, sdoResponse) {

					// set alt text
					$('#editDialogAltText').val(altTextResponse[0].data);

					// set og inputs
					openGraphInputs = openGraphTemplate(ogResponse[0].data).trim();
					$('#openGraphList').prepend(openGraphInputs);

					// set schema.org itemprop
					$('.schemaDotOrgProperty').val(sdoResponse[0].data);

					$('#editDialog')
						.data('fileId', $item.attr('data-fileid')) 
						.dialog('open');

					$deferred.resolve();
				});

			return $deferred;
		}

		function _closeDialog() {
			$('#editDialog').dialog('close');
		}

		function _saveAltText() {
			var options = _getSaveAltTextOptions();
			return $.ajax(options);
		}

		function _saveImage($dialog) {

			// toggle cursor
			$('body').css('cursor', 'wait');

			$.when(_saveAltText(), _saveOpenGraph(), _saveSchemaDotOrg())
				.done(function(altRes, ogRes) {

					// toggle cursor
					$('body').css('cursor', 'default');

					_closeDialog();
					app.notifications.success('Item updated');
				})
				.fail(function(response) {
					// toggle cursor
					$('body').css('cursor', 'default');

					app.notifications.success('Item could not be completely updated at this time.');
				})
		}

		function _saveOpenGraph() {
			return $.ajax(_getSaveOpenGraphOptions());
		}

		function _saveSchemaDotOrg() {
			return $.ajax(_getSaveSchemaDotOrgOptions());
		}

		function _getSaveAltTextOptions() {
			return {
				url: app.SOCKET_PATH + 'editImage',
				type: 'POST',
				data: {
					fileId: $('#editDialog').data('fileId'),
					alt: $('#editDialogAltText').val()
				}	
			};
		}

		function _getSaveOpenGraphOptions() {
			return {
				url: app.SOCKET_PATH + 'saveOpenGraph',
				type: 'POST',
				data: {
					fileId: $('#editDialog').data('fileId'),
					tagData: _getOpenGraphTagData()
				}
			};
		}

		function _getSaveSchemaDotOrgOptions() {
			return {
				url: app.SOCKET_PATH + 'saveSchemaDotOrg',
				type: 'POST',
				data: {
					fileId: $('#editDialog').data('fileId'),
					itemprop: $('.schemaDotOrgProperty').val()
				}
			};	
		}

		function _getOpenGraphTagData() {
			var openGraphTags = $('.openGraphItem'),
				tagData = [];

			_.each(openGraphTags, function(tag) {
				tagData.push({
					property: $(tag).find('.opengraphProperty').val(), 
					content: $(tag).find('.opengraphContent').val()
				});
			});

			return tagData;
		}

		function _loadAltText(fileId) {
			return $.ajax({
				url: app.SOCKET_PATH + 'loadAltText',
				type: 'GET',
				data: {
					fileId: fileId
				}
			});
		}

		function _loadOpenGraphInfo(fileId) {
			return $.ajax({
				url: app.SOCKET_PATH + 'loadOpenGraph',
				type: 'GET',
				data: {
					fileId: fileId
				}
			});
		}

		function _loadSchemaDotOrgInfo(fileId) {
			return $.ajax({
				url: app.SOCKET_PATH + 'loadSchemaDotOrg',
				type: 'GET',
				data: {
					fileId: fileId	
				}
			});
		}

		function _initializeEditDialog() {
			$('#editDialog').dialog({
				autoOpen: false,
				buttons: {
					'Cancel': {
						'class': 'redButton',
						'click': _closeDialog,
						'text': 'Cancel'
					},
					'Save': {
						'class': 'blueButton',
						'click': _saveImage,
						'text': 'Save'
					}
				},
				dialogClass: 'noTitleBar',
				height: 'auto',
				hide: {
					direction: 'up',
					effect: 'drop',
					duration: 500
				},
				modal: true,
				show: {
					direction: 'up',
					effect: 'drop',
					duration: 500
				},
				width: DIALOG_WIDTH
			});
		}

		function _initializeRepeater() {
			$('#openGraphForm').repeater();
		}

		function _initializeEventHandlers() {
			_initializeEditDialog();	
			_initializeRepeater();
		}

		return {
			openDialog: openDialog,
			init: init
		};
	})();
});
