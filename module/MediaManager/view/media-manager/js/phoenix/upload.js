$(function() {
	if (Handlebars === undefined) {
		throw new Error('Handlebars dependency');
	}

	app.uploader = (function() {
		var $uploadProgressContainer = $('#uploadMedia ul'),
			uploadCounter = 0,
			viewerTemplate,
			uploadProgressTemplate,
			fileExplorerFileTemplate,
			GIGABYTE = 1000000000,
			MEGABYTE = 1000000,
			KILOBYTE = 1000,
//			TYPES = {
//				IMAGES: 'jpg|jpeg|gif|png|bmp|tif|tiff',
//				SOUND: 'mp3|wma',
//				VIDEO: 'swf|flv|mp4|mpga|mpg|mpeg|wmv|mov',
//				DOC: 'pdf|xls|doc|docx|rtf|ods|txt|csv|ppt|zip'
//			},
			ACCEPT_FILE_TYPES = /(\.|\/)(gif|jpeg|jpg|png|pdf|xls|doc|docx|rtf|ods|txt|csv|ppt|zip|xlsx|xltx|potx|ppsx|pptx|sldx|dotx)$/i;

		function init() {
			_initializeTemplates();
			_initializeEventHandlers();
		}

		function insertIntoExplorer(item) {
			var currentPath = item.currentPath === '/' ? '' : item.currentPath,
				$list = $("a[rel='" + app.BASE_FOLDER + currentPath + "']").parent().children('ul:first');

			$('li.explorerFile', $list)
				.add($(fileExplorerFileTemplate(item).trim()).fadeIn(500))
				.sort(_sortFolderAlpha)
				.appendTo($list);
		}

		function insertIntoViewer(itemsArray) {
			$('.emptyFolder').remove();
			$('.fileViewerImageContainer', '#fileViewerInner')
				.add($(viewerTemplate(itemsArray).trim()).fadeIn(500))
				.sort(_sortFileAlpha)
				.prependTo('#fileViewerInner');
		}

		function updateFileList(fileData) {
			app.files.push(fileData);
		}

		function _initializeTemplates() {
			viewerTemplate = Handlebars.compile($('#fileViewImageContainerTemplate').html());
			uploadProgressTemplate = Handlebars.compile($('#uploadProgressTemplate').html());
			fileExplorerFileTemplate = Handlebars.compile($('#fileExplorerFileTemplate').html());	
		}

		function _initializeEventHandlers() {
			_preventGlobalDropping();
			_uploadClick();
			_stopInputPropagation();
			_initializeRemoveProgress();
			_initializeUploader();
			_initializeFileUploadSubmit();
		}

		function _initializeRemoveProgress() {
			$('#uploadMediaContainer').on('click', '.closeUploadProgress', function() {
				_slideUpParentLink.call(this);
			});
		}

		function _initializeFileUploadSubmit(e, data) {
			$('#uploadMedia').bind('fileuploadsubmit', function(e, data) {
				data.formData = {
					path: $('#openedFolderName').attr('data-rel')
				};
			});
		}

		function _slideUpParentLink() {
			$(this).closest('li').slideUp(function() { $(this).remove(); });
		}

		function _preventGlobalDropping() {
			$(document).bind('drop dragover', function(e) {
				e.preventDefault();
			});
		}

		function _uploadClick() {
			$('#uploadButton').click(function(event) {
				$(this).find('input').click();
			});
		}

		function _stopInputPropagation() {
			$('#uploadButton input').click(function(event) {
				event.stopPropagation();
			});
		}

		function _initializeUploader() {
			$('#uploadMedia').fileupload({
				dropZone: $('#drop'),
				drop: function(e, data) {},
				acceptFileTypes : ACCEPT_FILE_TYPES,
				add: function(e, data) {
					var canvasNumber = ++uploadCounter,
						tpl = uploadProgressTemplate({ canvasNumber : canvasNumber }),
						jqXHR,
						$me = $(this),
						validation = data.process(function() {
							return $me.fileupload('process', data);	
						}),
						$tpl;

					$tpl = $(tpl.trim());

					validation.done(function() {
						_addFormattedFileSizeText($tpl, data);
						_addTemplateToData($tpl, data);
						_startProgressCircle($tpl);
						_setProgressHandler($tpl, jqXHR);
						jqXHR = _submitData(data);
					})
					.fail(function(data) {
						app.notifications.error(data.files[0].error, 10000);
					});
				},
				error: function(xhr) {
					if (xhr.status == 404) {
						xhr.abort();
					}
				},
				disableValidation: false,
				maxFileSize: 100000000,
				minFileSize: undefined,
				progress: function(e, data) {
					var progress = parseInt(data.loaded / data.total * 100, 10);
					data.context.find('input').val(progress).change();
					if (progress === 100) {
						data.context.removeClass('working');
					}
				}
			});
		}

		function _submitData(data) {
			console.log('submitting data');
			return data.submit()
				.success(_handleUploadSuccess)
				.error(_handleUploadError);
		}

		function _startProgressCircle(tpl) {
			tpl.prepend('<div class="uploadStatusContainer column small-1"><i class="fa progressCircle fa-circle-o-notch fa-spin"></i></div>');
		}

		function _addTemplateToData(tpl, data) {
			data.context = tpl.appendTo($uploadProgressContainer);
		}

		function _setProgressHandler(tpl, jqXHR) {
			tpl.find('span').click(function() {
				if (tpl.hasClass('working')) {
					jqXHR.abort();
				}

				tpl.fadeOut(function() {
					tpl.remove();
				});
			});
		}

		function _addFormattedFileSizeText(tpl, data) {
			tpl
				.find('.uploaded_file_text')
				.text(data.files[0].name)
				.append('<i>' + _formatFileSize(data.files[0].size) + '</i>');	
		}

		function _handleUploadSuccess(response, textStatus, jqXHR) {
			var successCheckmark = $('<i class="fa fa-check successCheckmark"></i>'),
				item;

			if (response.data) {
				var $updatedFile = $('#fileViewer').find('.fileViewerImageContainer[data-fileid="' + response.data.fileId + '"]'),
					item = response.data;

				if ($updatedFile.length) {
					_removeOldFile($updatedFile);
				} else {
					insertIntoExplorer(item);
					updateFileList(item);
				}

				insertIntoViewer([item]);
				$('#fileViewer').trigger('append-viewer-items', $('.fileViewerImageContainerInner'));
				this.find('.uploadStatusContainer').append(successCheckmark);
			}

			_removeProgressCircle.call(this);
		}

		function _handleUploadError(jqXHR, textStatus, errorThrown) {
			var errorFrown = $('<i class="fa fa-frown-o errorFrown"></i>');
			_removeProgressCircle.call(this);
			this.find('div').append(errorFrown);
		}

		function _removeProgressCircle() {
			this.find('.progressCircle').remove();	
		}

		function _removeOldFile($updatedFile) {
			$updatedFile.remove();
		}

		function _sortFileAlpha(a, b) {
			return $(a).find('.fileName').text().toLowerCase() > $(b).find('.fileName').text().toLowerCase() ? 1 : -1;
		}

		function _sortFolderAlpha(a, b) {
			return $(a).children('a:first').text().toLowerCase() > $(b).children('a:first').text().toLowerCase() ? 1 : -1;
		}

		function _formatFileSize(bytes) {
			var formattedFileSize;

			if (typeof bytes !== 'number') {
				formattedFileSize = '';
			} else if (bytes >= GIGABYTE) {
				formattedFileSize = (bytes / GIGABYTE).toFixed(2) + ' GB';
			} else if (bytes >= MEGABYTE) {
				formattedFileSize = (bytes / MEGABYTE).toFixed(2) + ' MB';
			} else {
				formattedFileSize = (bytes / KILOBYTE).toFixed(2) + ' KB';	
			}

			return formattedFileSize;
		}

		return {
			init: init,
			insertIntoExplorer: insertIntoExplorer,
			insertIntoViewer: insertIntoViewer,
			updateFileList: updateFileList
		};
	})();
});
