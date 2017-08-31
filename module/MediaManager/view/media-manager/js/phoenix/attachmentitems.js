$(function() {
	app = app || {};

	// this handles sortability
	app.attachmentitems = (function() {

		var itemData = {},
			keepAlphaNumericUnderscoreSpaceDash = new RegExp("[^a-z0-9_ -]+", "ig");

		function init() {
			try {
				_initializeEventHandlers();
				_configureMediaList();
				_formatModuleNameGlobal();
			} catch(err) {
				// module name is not defined				
			}
		}

		function refreshMediaList() {
			$('#media-list').sortable('refresh');
			$('#media-list').sortable('option', 'update');
			_updateItemData();
			_updateHiddenInputs();
			if (!app.attachmentdialog.isNewItem()) {
				_disableMediaList();
				_saveOrder().done(_saveOrderDone);
			}
		}

		function _configureMediaList() {
			$( "#media-list" ).sortable({ disabled: false });
			$( "#media-list" ).disableSelection();
		}

		function _disableMediaList() {
			$('#media-list').sortable('disable');
		}

		function _enableMediaList() {
			$('#media-list').sortable('enable');	
		}

		function _formatModuleNameGlobal() {
			if (module_name === "Hotels") {
				module_name = "phoenixProperties";
			}
		}

		function _getFormattedModuleName() {
			var formattedModuleName = module_name.charAt(0).toLowerCase() + module_name.slice(1);
			return formattedModuleName.replace(/ /g, '-');
		}

		function _getOpenAltTextOptions(attachId, altText) {
			return {
				url: app.SOCKET_PATH + 'saveModuleAltText',
				type: 'POST',
				data: {
					attachId: attachId,
					altText: altText
				}
			};
		}

		function _getSaveOptions() {
			_validateGlobals();
			return {
				type: "POST",
				url: app.SOCKET_PATH + 'setAttachmentOrder',
				data: _getSaveData(),
				type: "POST",
				success: app.notifications.success.bind(null, 'The items have been reordered'),
				error: app.notifications.error.bind(null, 'A problem has occurred while reordering items')
			};
		}

		function _getSaveData() {
			return {
				'orderedItems': itemData,
				'parentItemId': app.attachmentdialog.getParentItemId(),
				'parentModule': _getFormattedModuleName()
			};
		}

		function _initializeEventHandlers() {
			_initializeMediaList();
			_initializeAltTextSetters();
		}

		function _initializeAltTextSetters() {
			$('.mediaAttachmentItem.previouslySaved').on('mousedown', function(event) {
				if (event.which === 3) {
					_openAltTextPrompt($(this).attr('data-fileid'));
				}
			});
		}

		function _initializeMediaList() {
			$('#media-list').sortable({
				change: _onSortableChange,
				start: _onSortableStart,
				update: _onSortableUpdate
			});

			$('#media-list').sortable('refresh');
		}

		function _saveOrder() {
			$deferred = $.Deferred();
			$.ajax(_getSaveOptions()).done($deferred.resolve);
			return $deferred;			
		}

		function _saveOrderDone() {
			_enableMediaList();
		}

		function _onSortableChange(event, ui) {
			var start_pos = ui.item.data('start_pos'),
				index = ui.placeholder.index();

			if (start_pos < index) {
				$('#media-list li:nth-child(' + index + ')').addClass('highlighted');
			} else {
				$('#media-list li:eq(' + (index + 1) + ')').addClass('highlighted');
			}
		}

		function _onSortableStart(event, ui) {
			var start_pos = ui.item.index();
			ui.item.data('start_pos', start_pos);
			app.notifications.clear();
		}

		function _onSortableUpdate(event, ui) {
			_removeHighlighting();
			_updateItemData();
			_updateHiddenInputs();

			if (!app.attachmentdialog.isNewItem()) {
				_disableMediaList();
				_saveOrder().done(_saveOrderDone);
			} 
		}

		function _openAltTextPrompt(attachId) {
			var altText = prompt('Enter module-specific altText for this attachment');

			if (altText) {
				altText = altText.replace(keepAlphaNumericUnderscoreSpaceDash, '');

				_sendOpenAltText(attachId, altText);
			}
		}

		function _removeHighlighting() {
			$('#media-list li').removeClass('highlighted');
		}

		function _sendOpenAltText(attachId, altText) {
			return $.ajax(_getOpenAltTextOptions(attachId, altText));
		}

		function _updateHiddenInputs() {
			if (app.attachmentdialog.isNewItem()) {
				$('#media-list li.media-attachment-item').each(function(){
					var fileId = $(this).attr('data-fileId'),
						index = $(this).index();

					$('input[name="mediaAttachments[' + fileId + ']"]').val(index);	
				});
			}
		}

		function _updateItemData() {
			$('#media-list li.media-attachment-item').each(function(){
				itemData[$(this).attr("data-fileId")] = $(this).index();
			});
		}

		function _validateGlobals() {
			if (_site_root === undefined || module_name === undefined) {
				throw new Error('Undefined global in attachment item');
			}	
		}

		return {
			init: init,
			refreshMediaList: refreshMediaList
		};
	})();

	app.attachmentitems.init();
});
