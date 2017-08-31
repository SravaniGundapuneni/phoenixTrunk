$(function() {
	if (app === undefined) {
		throw new Error('App is undefined');
	}

	if ($.Jcrop === undefined) {
		throw new Error('Jcrop dependency');
	}

	app.contextMenu.cropper = (function() {
		var jCropApi = null,
			$image = null,
			BASE_URL = location.protocol + '//' + location.host + _site_root, // _site_root is a phoenix JS global
			DIALOG_HEIGHT = 487,
			DIALOG_WIDTH = 766,
			IMAGE_CONTAINER_HEIGHT = 344,
			PREVIEW_FOLDER = '__thumbs_90_90_crop/',
			is_msie = /msie/.test(navigator.userAgent.toLowerCase());

		function init() {
			if (app.uploader === undefined) {
				throw new Error('Uploaded dependency in Cropper');
			}

			_initializeEventHandlers();			
		}

		function openDialog($cropImage) {
			_setCropImage($cropImage);
			_initCrop().done(_launchDialog);
		}


		function _checkInitialDimensions() {
			if (!jCropApi.tellScaled().x) {
				_setDefaultDimensions();
			}
		}

		function _clearDimensions() {
			//
		}

		function _closeDialog() {
			$('#cropDialog').dialog('close');
		}

		function _cropImage() {
			app.notifications.cursorWait();	
			$.ajax({
				data: _getCropData(),
				url: app.SOCKET_PATH + 'crop',
				type: 'POST'
			}).done(function(response) {
				if (response.status === 'error') {
					app.notifications.error('Could not save crop at this time');
				} else {
					app.uploader.insertIntoViewer([response.data]);
					app.uploader.insertIntoExplorer(response.data);
					app.uploader.updateFileList(response.data);
					$('#fileViewer').trigger('append-viewer-items', $('.fileViewerImageContainerInner'));
					app.notifications.success('Image successfully cropped');
				}	
				app.notifications.cursorDefault();
				_closeDialog();
			}).fail(function() {
				app.notifications.error('Could not save crop at this time');	
				app.notifications.cursorDefault();
				_closeDialog();
			});
		}

		function _destroyJCropApi() {
			jCropApi.destroy();
		}

		function _getCropData() {
			var cropStringSanitizer = new RegExp("[^a-z0-9_ -\\\/]+", "ig");

			return {
				x: parseInt($('#x').val(), 10),
				y: parseInt($('#y').val(), 10),
				w: parseInt($('#w').val(), 10),
				h: parseInt($('#h').val(), 10),
				imageName: _getImageName().replace(cropStringSanitizer, ''),
				imagePath: _getPathName().replace(cropStringSanitizer, ''), // sometimes, this has a /originals appended to it; not sure if we will need this
				srcPath: _getPathName().replace(cropStringSanitizer, '')
			};
		}

		function _getFolderFromImagePath() {
			var imagePath = $image[0].src,
				folder = imagePath.replace(BASE_URL, ''),	
				previewFolderIndex = folder.indexOf(PREVIEW_FOLDER);

			return folder.substring(0, previewFolderIndex);
		}

		function _getImageInfo() {
			var imageInfo = {
				source : $image.attr('src').replace(PREVIEW_FOLDER, '')
			},
			unloadedImage = new Image();

			$unloadedImage = $(unloadedImage);

			unloadedImage.onload = function() {
				var height,
					width;

				$unloadedImage.css({
					position: 'absolute',
					visibility: 'hidden',
					display: 'block'
				});

				height = $(unloadedImage).height();
				width = $(unloadedImage).width();

				$('#originalX').text(width);
				$('#originalY').text(height);
				$('#dialogImage').css('margin-top', _getImageMarginTop(height));
				$unloadedImage.remove();
			}

			$('body').append($unloadedImage);
			unloadedImage.src = imageInfo.source;

			return imageInfo;
		}

		function _getImageMarginTop(height) {
			var marginTop = 0;

			if (height < IMAGE_CONTAINER_HEIGHT) {
				marginTop = Math.floor((IMAGE_CONTAINER_HEIGHT - height) / 2);
			}

			return marginTop;
		}

		function _getImageName() {
			var source = $image[0].src;
			return source.substring(source.lastIndexOf('/') + 1);
		}


		function _getPathName() {
			return _getFolderFromImagePath();
		}

		function _initializeButtonPane() {
			$('.ui-dialog-buttonpane').on('click', function(event) {
				var $target = $(event.target);
				if (!$target.is(':button')) {
					_resetDialog();
				}
			});
		}

		function _initCrop() {
			var $deferred = $.Deferred();

			// FUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUU
			if (is_msie) {
				jCropApi = $.Jcrop('#cropbox', {
					aspectRatio: 0,
					maxSize: 0,
					boxWidth: 744,
					boxHeight: 342,
					onSelect: _setCoordinates,
					onChange: _showCoordinates,
					onRelease: _clearDimensions
				});
				$deferred.resolve();
			} else {
				$('#cropbox').Jcrop({
					aspectRatio: 0,
					maxSize: 0,
					boxWidth: 744,
					boxHeight: 342,
					onSelect: _setCoordinates,
					onChange: _showCoordinates,
					onRelease: _clearDimensions
				}, function() {
					jCropApi = this;
					$deferred.resolve();
				});
			}

			return $deferred.promise();
		}

		function _initializeCropDialog() {
			$('#cropDialog').dialog({
				autoOpen: false,
				beforeClose: _destroyJCropApi,
				buttons: {
					'Cancel': {
						'class': 'cropButton redButton',
						'click': _closeDialog,
						'text': 'Cancel'
					},
					'Crop': {
						'class': 'cropButton blueButton',
						'click': _cropImage,
						'text': 'Crop'
					}
				},
				dialogClass: 'noTitleBar',
				height: DIALOG_HEIGHT,
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

		function _initializeEventHandlers() {
			_initializeCropDialog();
			_initializeButtonPane();
			_initializeRatioDropdown();
			_initializePresetDropdown();
			_setTriangle();
		}

		function _initializePresetDropdown() {
			$('#cropPresetDropdown').selectmenu({
				change: function(event, ui) {
					_checkInitialDimensions();
					jCropApi.animateTo([
						0,
						0,
						ui.item.element.data('width'),
						ui.item.element.data('height')
					]);
					_resetRatioDropdown();
				}	
			});
		}

		function _initializeRatioDropdown() {
			$('#cropRatioDropdown').selectmenu({
				change: function(event, ui) {
					_checkInitialDimensions();
					jCropApi.setOptions({
						aspectRatio: ui.item.value
					});
					_resetPresetDropdown();
				}
			});
		}

		function _launchDialog() {
			var imageInfo = _getImageInfo();
			jCropApi.setImage(imageInfo.source);
			$('#cropDialog').dialog('open');
		}

		function _resetDialog() {
			_resetRatioDropdown();
			_resetPresetDropdown();
			_resetTargetDisplay();
			_setDefaultDimensions();
			jCropApi.release();
		}

		function _resetPresetDropdown() {
			$('#cropPresetDropdown').val(0);
			$('#cropPresetDropdown').selectmenu('refresh', true);
		}

		function _resetRatioDropdown() {
			$('#cropRatioDropdown').val(0);
			$('#cropRatioDropdown').selectmenu('refresh', true);
		}

		function _resetTargetDisplay() {
			$('#targetX').text(0);
			$('#targetY').text(0);
		}

		function _setCoordinates(coordinates) {
			$('#x').val(coordinates.x);
			$('#y').val(coordinates.y);
			$('#w').val(coordinates.w);
			$('#h').val(coordinates.h);
		}

		function _setCropImage($cropImage) {
			$image = $cropImage;
		}

		function _setDefaultDimensions() {
			jCropApi.setSelect([0,0,80,80]);
		}

		function _setTriangle() {
			$('.ui-icon-triangle-1-s').css('background-image', 'url(' + app.IMAGES_PATH + 'downArrow.png)');	
		}

		function _showCoordinates(coordinates) {		
			if (!isNaN(coordinates.w)) {
				$('#targetX').text(Math.round(coordinates.w));
				$('#targetY').text(Math.round(coordinates.h));
			}
		}

		return {
			init: init,
			openDialog: openDialog
		};
	})();
});
