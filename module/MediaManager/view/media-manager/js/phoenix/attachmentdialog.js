var app = app || {};

app.attachmentdialog = (function() {

	var isInitialized = false,
		isAttachmentPage = false,
		parentItemId;

	function init() {
		_initAttachmentDialogHandler();	
		_setParentItemId();
		getParentItemId();
		_initRemoveAttachment();
	}

	function isNewItem() {
		return !parentItemId;
	}

	function getParentItemId() {
		return parentItemId;
	}

	function _deselectAttachment($attachment) {
		$attachment.removeClass('selectedAttachment');
	}

	function _getRemoveAttachmentOptions($attachment) {
		return {
			url: app.SOCKET_PATH + 'removeAttachment', 
			data: {
				parentModule: $attachment.data('parentmodule'),
				parentItemId: $attachment.data('parentitemid'),
				attachments: [$attachment.data('fileid')]
			},
			type: 'POST'
		};
	}

	function _initializeDialogHandlers() {
		_initAttachmentDialog();
		_initCloseDialog();
		_initToggleAttachment();
	}

	function _initAttachmentDialog() {
		$('#attachmentDialog').dialog({
			autoOpen: false,
			dialogClass: 'noTitleBar',
			height: 703,
			hide: {
				direction: 'up',
				effect: 'drop',
				duration: 400
			},
			modal: true,
			open: function() {
				$(this).parent().promise().done(function() {
					if (!app.flexbox.hasFlexbox()) {
						app.flexbox.setItems($('.fileViewerImageContainerInner'));
						app.flexbox.setHeights();
					}
				});
			},
			show: {
				direction: 'down',
				effect: 'drop',
				duration: 400
			},
			width: 1160,
			within: 'window'	
		});
	}

	function _initAttachmentDialogHandler() {
		$(document).on('click', '#add-media', function() {
			if (!isInitialized) {
				_initializeDialogHandlers();
				isInitialized = true;
			}
			app.mediaManager.launch().done(function() {
				$('#attachmentDialog').dialog('open');
			});
		});
	}

	function _initCloseDialog() {
		$('.closeFileManager').on('click', function() {
			app.contextMenu.closeAttachmentDialog();
		});
	}

	function _initRemoveAttachment() {
		$(document).on('click', '.removeAttachment', function(event) {
			var isSure;

			event.preventDefault();
			isSure = confirm('Are you sure you want to remove this attachment?');
			_removeAttachmentIfSure.call(this, isSure);
		});
	}

	function _initToggleAttachment() {
		$('#fileViewer').on('click', '.fileViewerImageContainerInner', function(event) {
			var $me = $(this);

			if (!_clickedOnContextMenu(event)) {
				if (_isAttachmentSelected($me)) {
					_deselectAttachment($me);
				} else {
					_selectAttachment($me);
				}
			}
		});
	}

	function _clickedOnContextMenu(event) {
		return $(event.target).hasClass('contextMenuImageLink');
	}

	function _isAttachmentSelected($attachment) {
		return $attachment.hasClass('selectedAttachment');
	}

	function _onRemoveAttachmentSuccess(response) {
		if (response.status === 'success') {
			$('*[data-fileid="' + response.data.fileId + '"]').fadeOut('normal', function() { $(this).remove(); });
			app.attachmentitems.refreshMediaList();
			app.notifications.success('Attachment successfully removed');
		} else {
			app.notifications.error('Could not remove attachment at this time.');	
		}
	}

	function _removeAttachment($attachment) {
		if (isNewItem()) {
			_removeFromNewItem($attachment);
		} else {
			_removeFromExistingItem($attachment);
		}
	}

	function _removeAttachmentIfSure(isSure) {
		if (isSure) {
			_removeAttachment($(this));
		}
	}

	function _removeFromNewItem($attachment) {
		var fileId = $attachment.attr('data-fileid');

		$('input[name="mediaAttachments[' + fileId + ']"').remove();
		$attachment.parent().fadeOut('normal', function() {
			$(this).remove();
			app.attachmentitems.refreshMediaList();
		});
	}

	function _removeFromExistingItem($attachment) {
		$.ajax(_getRemoveAttachmentOptions($attachment))
			.done(_onRemoveAttachmentSuccess)
			.fail(function() { 
				app.notifications.error('Could not remove attachment at this time');
			});
	}

	function _selectAttachment($attachment) {
		$attachment.addClass('selectedAttachment');
	}

	function _setParentItemId() {
		parentItemId = $('#editBox form input[name=id]').val();
	}

	return {
		init: init,
		isNewItem: isNewItem,
		getParentItemId: getParentItemId
	};
})();

$(function() {
	app.attachmentdialog.init();
});
