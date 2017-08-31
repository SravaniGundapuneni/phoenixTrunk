$(function() {
	if (app === undefined) {
		throw new Error('app is undefined');
	}

	if ($.Jcrop === undefined) {
		throw new Error('Jcrop dependency');
	}

	if (_ === undefined) {
		throw new Error('underscore dependency');
	}

	if (Handlebars === undefined) {
		throw new Error('Handlebars dependency');	
	}

	app.contextMenu = (function() {
		var attachmentOptions,
			basicFolderOptions,
			basicOptions,
			fullFolderOptions,
			imageOptions,
			folderTemplate,
			imageItems,
			docItems,
			attachmentItemTemplate,
			zipItems;

		function addAttachments() {
			var attachmentOptions = _getAttachmentOptions();

			if (_hasAttachments(attachmentOptions)) {

				// if new item
				if (!attachmentOptions.data.parentItemId) {
					_addAttachmentFormInputs(attachmentOptions.data.attachments);
				}
				_sendAttachmentRequest(attachmentOptions);
			}
		}

		function addNewFolder(anchor) {
			var newFolderName = prompt('Please enter the new folder name'),
				relValue,
				keepAlphaNumericUnderscoreSpace = new RegExp("[^a-z0-9_ ]+", "ig"),
				$theLink;

			if (newFolderName) {
				$.ajax({
					url: app.SOCKET_PATH + 'addFolder',
					type: 'POST',
					data: {
						name: newFolderName.replace(keepAlphaNumericUnderscoreSpace, ''),	
						path: anchor.attr('rel')
					}
				})
				.done(function(response) {
					if (response.status === 'success') {
						newFolder = folderTemplate(response.data); 
						$theLink = $(anchor);

						if ($theLink.parent().hasClass('collapsed')) {
							$theLink.click();
						}

						$list = $theLink.next();
						$('li.directory', $list).add($(newFolder.trim()).fadeIn(500)).sort(_sortAlpha).prependTo($list);
						app.folders.push(app.BASE_FOLDER + response.data.title + '/');
						app.notifications.success('Folder successfully added.');
					} else {
						app.notifications.error('Could not add folder at this time');
					}
				})
				.fail(function() {
					app.notifications.error('Could not add folder at this time.');
				});
			}
		}

		function closeAttachmentDialog() {
			$('#attachmentDialog').dialog('close');
			_removeSelectedAttachments();
		}

		function cropImage($image) {
			this.cropper.openDialog($image);
		}

		function deleteFolder($folderLink) {
			var $deferred = $.Deferred(),
				isSure = confirm('Are you sure you want to delete this folder?');

			if (isSure) {
				app.notifications.cursorWait();
				$.ajax({
					url: app.SOCKET_PATH + 'deleteFolder',
					type: 'POST',
					data: {
						path: $folderLink.attr('rel')
					}
				})
				.done(function(response) {
					if (response.status === 'success') {
						var index = app.folders.indexOf(app.BASE_FOLDER + response.data);
						if (index > -1) {
							app.folders.splice(index, 1);
						}

						$folderLink.parent().fadeOut(500, function() { $(this).remove(); });

						$folderLink.trigger('deleted-folder');
						$deferred.resolve('success');
						app.notifications.success('Successfully deleted folder');
					} else {
						$deferred.reject('failure');
						app.notifications.error('Could not delete folder at this time');
					}

					app.notifications.cursorDefault();
				})
				.fail(function() {
					$deferred.reject('failure');
					app.notifications.cursorDefault();
					app.notifications.error('Could not delete folder at this time');
				});		
			} else {
				$deferred.reject('user decided not to delete');
			}

			return $deferred.promise();
		}

		function deleteItem($item) {
			app.notifications.cursorWait();
			$.ajax(_getDeleteOptions($item))
				.done(function(response) {
					console.log('deleted item!');
					if (response.status === 'success') {
						_deletionSuccess($item);
						app.notifications.success('Item successfully deleted');
					} else {
						app.notifications.error('We could not delete item at this time');
					}
					app.notifications.cursorDefault();
				})
				.fail(function(data) { 
					app.notifications.error('We could not delete item at this time');
					app.notifications.cursorDefault();
				});
		}

		function editItem($item) {
			var $deferred = new $.Deferred();
			app.notifications.cursorWait();
			this.editor.openDialog($item, $deferred).done(app.notifications.cursorDefault);
		}

		function getRootFolderItems() {
			var menuItems = _sortMenuItems(basicFolderOptions.concat(basicFolderOptions));
			return _getMenuItemsObj(menuItems);
		}

		function getFullFolderItems() {
			var menuItems = _sortMenuItems(basicFolderOptions.concat(fullFolderOptions));
			return _getMenuItemsObj(menuItems);
		}

		function getImageItems() {
			var menuItems = _sortMenuItems(basicOptions.concat(imageOptions));
			return _getMenuItemsObj(menuItems);
		}

		function getImageItemsWithAttachments() {
			var menuItems = _sortMenuItems(basicOptions.concat(attachmentOptions, imageOptions));
			menuItems = _readOnlyFilter(menuItems);	
			return _getMenuItemsObj(menuItems);
		}

		function getItems() {
			return _getMenuItemsObj(basicOptions);
		}

		function getItemsWithAttachments() {
			var menuItems = _sortMenuItems(basicOptions.concat(attachmentOptions));
			menuItems = _readOnlyFilter(menuItems);	
			return _getMenuItemsObj(menuItems);
		};

		function getZipItems() {
			var menuItems = _sortMenuItems(basicOptions.concat(zipOptions));	
			return _getMenuItemsObj(menuItems);
		}

		function getZipItemsWithAttachments() {
			var menuItems = _sortMenuItems(basicOptions.concat(attachmentOptions, zipOptions));	
			menuItems = _readOnlyFilter(menuItems);	
			return _getMenuItemsObj(menuItems);
		}

		function init() {
			options = app.contextMenuOptions;
			basicOptions = options.basic;
			imageOptions = options.image;
			attachmentOptions = options.attachments;
			basicFolderOptions = options.folderBasic;
			fullFolderOptions = options.folderFull;
			zipOptions = options.zip;
			folderTemplate = Handlebars.compile($('#fileExplorerFolderTemplate').html());

			if (app.isAttachments === 'true') {
				attachmentItemTemplate = Handlebars.compile($('#mediaAttachmentItemTemplate').html());
			}

			if (this.cropper === undefined) {
				throw new Error('Cropper is undefined');
			} else {
				this.cropper.init();
			}

			if (this.editor === undefined) {
				throw new Error('Editor is undefined');
			} else {
				this.editor.init();
			}

			_setImageItems();
			_setDocItems();
			_setZipItems();
			_setContextMenus();
			_setContextMenuHovers();
		}

		function unzipFiles($item) {
			var fileId = $item.attr('data-fileid');
			app.notifications.cursorWait();
			$.ajax({
				url: app.SOCKET_PATH + 'unzipFiles',
				data: { fileId: fileId },
				type: 'POST'
			})
			.done(function(response) {
				var unzippedFiles = _.toArray(response.data);

				app.uploader.insertIntoViewer(unzippedFiles);
				_.each(unzippedFiles, function(file) {
					app.uploader.insertIntoExplorer(file);
					app.files.push(file); // update JSON
				});

				_deletionSuccess($item);

				app.notifications.cursorDefault();
			})
			.fail(app.notifications.cursorDefault);
		}

		function _addAttachmentFormInputs(attachmentIds) {
			_.each(attachmentIds, function(orderNumber, fileId) {
				$('<input>').attr({
					type: 'hidden',
					name: 'mediaAttachments[' + fileId + ']',
					value: orderNumber
				}).appendTo('#editBox form');
			});		
		}

		function _readOnlyFilter(items) {
			var filteredItems;

			if (app.readOnly === 'true') {
				filteredItems = _.filter(items, function(item) {
					if (item.id === 'quit' || item.id === 'attachments') {
						return item;
					}
				});
			} else {
				filteredItems = items;
			}

			return filteredItems;
		}

		function _sendAttachmentRequest(attachmentOptions) {
			app.notifications.cursorWait();
			$.ajax(attachmentOptions)
				.done(_onAttachSuccess)
				.fail(_onAttachFailure);
		}

		function _setImageItems() {
			imageItems = app.isAttachments === 'true' ? getImageItemsWithAttachments() : getImageItems();
		}

		function _setDocItems() {
			docItems = app.isAttachments === 'true' ? getItemsWithAttachments() : getItems();
		}

		function _setZipItems() {
			zipItems = app.isAttachments === 'true' ? getZipItemsWithAttachments() : getZipItems();	
		}

		function _setContextMenus() {
			$.contextMenu({
				selector: '.contextMenuImageLink', 
				items: imageItems,
				trigger: 'left'
			});

			$.contextMenu({
				selector: '.contextMenuLink',
				items: docItems,
				trigger: 'left'
			});

			if (app.readOnly === 'false') {
				$.contextMenu({
					selector: '.folderFullMenu', 
					items: getFullFolderItems(),
					trigger: 'right'
				});

				$.contextMenu({
					selector: '.folderRootMenu',
					items: getRootFolderItems(),
					trigger: 'right'
				});	

				$.contextMenu({
					selector: '.contextMenuLinkZip',
					items: zipItems,
					trigger: 'left'
				});
			}
		}

		function _setContextMenuHovers() {
			var CLOSE_ITEM = 0,
				FIRST_ITEM = 1;	

			$('.context-menu-list').on('contextmenu:focus', '.context-menu-item', function(e) {
				var index = $(this).index(),
					$list = $(this).parent();

				if (index === CLOSE_ITEM || index === FIRST_ITEM) {
					$list.find('li:eq(0)').addClass('contextMenuItemHover');
					$list.find('li:eq(1)').addClass('contextMenuItemHover');
				} else {
					$(this).addClass('contextMenuItemHover');
				}
			});

			$('.context-menu-list').on('contextmenu:blur', '.context-menu-item', function(e) {
				var index = $(this).index(),
					$list = $(this).parent();

				if (index === CLOSE_ITEM || index === FIRST_ITEM) {
					$list.find('li:eq(0)').removeClass('contextMenuItemHover');
					$list.find('li:eq(1)').removeClass('contextMenuItemHover');
				} else {
					$(this).removeClass('contextMenuItemHover');
				}
			});
		}

		function _deletionSuccess($item) {
			var anchorPath = $('#openedFolderName').attr('data-rel').replace(app.BASE_FOLDER, ''),
				fileName = $item.find('.fileName').text(),
				filePath = anchorPath + fileName;

			_removeItemInViewer($item);
			_removeItemInExplorer(filePath);
			_updateJSONFiles(anchorPath, fileName);
		}

		function _getAttachments() {
			// data-fileid is on the parent: .fileViewerImageContainer
			var attachments = $('#fileViewer').find('.selectedAttachment').parent(),
				formattedAttachments = {},
				totalNumberOfAttachments = $('#media-list li').length;

			_.each(attachments, function(attachment) {
				// totalNumberOfAttachments represents orderNumber
				formattedAttachments[$(attachment).attr('data-fileid')] = totalNumberOfAttachments++;
			});

			return formattedAttachments;
		};

		function _getAttachmentOptions() {
			return {
				url: app.SOCKET_PATH + 'addAttachments', 
				type: 'POST',
				data: {
					parentModule: _getParentModule(),
					parentItemId: parseInt($('#add-media').attr('data-parentitemid'), 10),
					attachments: _getAttachments()
				}
			};
		}

		function _getDeleteOptions($item) {
			return {
				url : app.SOCKET_PATH + 'deleteFile',
				type : "POST",	
				data : {
					fileId : $item.data('fileid')
				}
			};
		}

		function _getMenuItemsObj(menuItems) {
			return _.object(_.map(menuItems, function(menuItem) {
				return [menuItem.id, menuItem.options];
			}));
		}

		function _getParentModule() {
			var formattedModuleName;
			// module_name is defined someplace in the global namespace
			if (!module_name) {
				formattedModuleName = 'mediaManager';
			} else {
				formattedModuleName = module_name.charAt(0).toLowerCase() + module_name.slice(1);
				formattedModuleName = formattedModuleName.replace(/ /g, '-');
			}

			return formattedModuleName;
		}

		function _hasAttachments(attachmentOptions) {
			return !_.isEmpty(attachmentOptions.data.attachments);
		}

		function _onAttachFailure(response) {
			app.notifications.error('No attachments saved at this time.');
			app.notifications.cursorDefault();
			closeAttachmentDialog();
		}

		function _onAttachSuccess(response) {
			if (response.status === 'success' && !_.isEmpty(response.data)) {
				$('#media-list').append(attachmentItemTemplate(response.data).trim());
				app.attachmentitems.refreshMediaList();
				app.notifications.success('Attachments saved.');
			} else {
				app.notifications.error('No attachments saved at this time.')
			}
			app.notifications.cursorDefault();
			closeAttachmentDialog();
		}

		function _removeItemInExplorer(filePath) {
			$("a[rel='" + filePath + "']").parent().fadeOut(500, function() { $(this).remove(); });	
		}

		function _removeItemInViewer($item) {
			$item.fadeOut().remove();
		}

		function _removeSelectedAttachments() {
			var items = $('.fileViewerImageContainer');
			_.each(items, function(item) {
				$(item).removeClass('selectedAttachment');
			});
		}

		function _sortAlpha(a,b) {
			return $('a', a).text().toLowerCase() > $('a', b).text().toLowerCase() ? 1 : -1;
		};

		function _sortMenuItems(menuItems) {
			return _.sortBy(menuItems , function(menuItem) {
				return menuItem.menuOrder;
			});
		}

		function _updateJSONFiles(anchorPath, fileName) {
			console.log('update json files');
			console.log(arguments);

			var anchorPath = $('#openedFolderName').attr('data-rel').replace(app.BASE_FOLDER, '');

			app.files = _.reject(app.files, function(file) {
				return (file.currentPath === anchorPath && file.title === fileName);
			}); 
		}

		return {
			addAttachments: addAttachments,
			addNewFolder: addNewFolder,
			closeAttachmentDialog: closeAttachmentDialog,
			cropImage: cropImage,
			deleteFolder: deleteFolder,
			deleteItem: deleteItem,
			editItem: editItem,
			init: init,
			unzipFiles: unzipFiles
		};
	})();
});


