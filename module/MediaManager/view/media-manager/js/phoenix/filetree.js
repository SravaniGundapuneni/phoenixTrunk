$(function() {
	// Make sure we have our phoenix configuration
	if (app === undefined) {
		throw new Error('app is undefined');
	}

	if (_ === undefined) {
		throw new Error('underscore dependency');
	}

	if (Handlebars === undefined) {
		throw new Error('Handlebars dependency');
	}

	app.filetree = (function() {
		var explorerTemplate,
			viewerTemplate,
			$mainElement = null,
			filesFromDirectory,
			unshownFiles,
			isExplorerActive = false,
			LAZYLOAD_THRESHOLD = 30,
			options,	
			stateMap = {
				$anchor: null,
				$parent: null,
				data: null,
				isActive: false,
				files: null
			};

		function appendFiles(files) {
			$('#fileViewerInner').append(viewerTemplate(files.splice(0, LAZYLOAD_THRESHOLD)));
		}

		function init() {
			var $deferred = new $.Deferred();

			$.extend($.fn, {
				fileTree: function(userOptions) {
					_initializeTemplates();
					_setOptions(userOptions);
					_setMainElement.call(this);
					_initializeEventHandlers();
				}
			});

			if (app.files === undefined) {
				loadFiles($deferred);
			} else {
				$deferred.resolve();
			}

			return $deferred.promise();
		}

		function initializeTree() {
			$mainElement.html('<ul id="explorerRoot" class="jqueryFileTree"><li id="treeRoot" class="directory expanded folderRootMenu"><a href="#" rel="' + app.BASE_FOLDER + '">Media Folder</a></li></ul>');
			$('#fileExplorer').append($mainElement);
			$rootLink = $mainElement.find('li:first');
			_showTree($rootLink, encodeURI(options.root));
			$mainElement.show();
			if (app.isAttachments === 'true') {
				_reinitializeFoundation();
			}
			_initializeScrollHandler();
			$('#fileManagerLoading').hide();
			$('#fileManagerWrapper').fadeIn();
		}

		function _addSpinner() {
			stateMap.$parent.addClass('wait');
		}

		function _bindTree($element) {
			$element.on('click', 'li a', function() {
				_setStateMap.call(this);

				if (_isFolder()) {
					_toggleTree();
				} else {
					_toggleFile();
				}

				return false;
			});
		}

		function _clearFileViewer() {
			$('#fileViewerInner').html('');	
		}

		function _collapseFolders() {
			$folders = stateMap.$parent.find('ul');
			$folders.slideUp({
				duration: options.collapseSpeed
			});

			$folders.parent().removeClass('expanded').addClass('collapsed');
		}

		function _collapseTree() {
			var relativePath = _getRelativePath(),
				parentPath = _getParentPath(relativePath);

			_addSpinner();
			_trigger(stateMap.$anchor, 'filetreecollapse', stateMap.data);
			_collapseFolders();
			_setFilesFromDirectory(parentPath);
			_updateFileViewer();
			_triggerIEFlexbox();
			_trigger(stateMap.$anchor, 'filetreecollapsed', stateMap.data);
			_setAsInactive();
			_removeSpinner();
		}

		function _expandTree() {
			_trigger(stateMap.$anchor, 'filetreeexpand', stateMap.data);
			stateMap.$parent.removeClass('collapsed').addClass('expanded');

			if (_isFolderEmpty()) {
				_showTree(stateMap.$parent, _getRelativePath());
				_triggerIEFlexbox();
			} else {
				_setFilesFromDirectory(_getRelativePath());
				_updateFileViewer();
				_triggerIEFlexbox();
				stateMap.$parent.children('ul').slideDown();
				_setAsInactive();
			}
		}

		function _getData() {
			var data = {};
			data.li = stateMap.$anchor.closest('li');
			data.type = data.li.hasClass('directory') ? 'directory' : 'file';
			data.value = stateMap.$anchor.text();
			data.rel = stateMap.$anchor.prop('rel');
			return data;
		}

		function _folderFilter(folder) {
			return (
				(folder.indexOf(this.formattedDirectory) === 0) &&
				((folder.indexOf(this.SEPARATOR, this.formattedDirectory.length)) === folder.lastIndexOf(this.SEPARATOR))
			);
		}

		function _folderMap(folder) {
			var temp = _rtrim(folder, 1),
				indexOfLastSlash = temp.lastIndexOf(this.SEPARATOR) + 1;

			return {
				currentPath: temp.substring(0, indexOfLastSlash),
				title: temp.substring(indexOfLastSlash)
			};	
		}

		function _getFoldersFromDirectory(dir) {
			var folderOptions;

			if (!dir || dir === '/') {
				dir = app.BASE_FOLDER;
			}

			folderOptions = {
				formattedDirectory: dir,
				SEPARATOR: '/'
			};

			return _.chain(app.folders) 
				.filter(_folderFilter, folderOptions)
				.map(_folderMap, folderOptions)
				.value();
		}

		function _getMoreResults() {
			if (_isBottomOfScroll.call(this)) {
				appendFiles(unshownFiles);
				_triggerIEFlexbox();
			}
		}

		function _getParentFolder(folderAnchor) {
			return $(folderAnchor).closest('ul').parent().find('a:first');
		}

		function _getParentPath(path) {
			path = path.substring(0, path.length -1);
			path = path.substring(0, path.lastIndexOf('/') + 1);
			if (!path) {
				path = '/';
			}
			return path;	
		}

		function _getRelativePath() {
			var relativePath;

			if (stateMap.$anchor) {
				relativePath = encodeURI(stateMap.$anchor.attr('rel').match(/.*\//));
			} else {
				relativePath = app.BASE_FOLDER;	
			}
			return relativePath;
		}

		function _initializeEventHandlers() {
			$('#fileManagerWrapper').bind('deleted-folder', function(event) {
				var $parentFolder = _getParentFolder(event.target);
				_setStateMap.call($parentFolder);
				_resetOpenDirectoryIndicators();
				$parentFolder.click();
			});

			$(document).on('click', '#treeRoot', function() {
				// this is super hackysack
				$folders = $(this).parent().find('ul ul');
				$folders.slideUp({
					duration: options.collapseSpeed,
				});
				$('#openedFolderName').fadeOut(function() {
					var $openedFolder = $(this);
					$openedFolder.text('File Browser').fadeIn();
					$openedFolder.attr('data-rel', 'd/');
				});
				$('.folderFullMenu').removeClass('expanded').addClass('collapsed');
				_setFilesFromDirectory();
				_updateFileViewer();
				_triggerIEFlexbox();	
			});
		}

		function _initializeScrollHandler() {
			$('#fileViewer').bind('scroll', function() {
				_getMoreResults.call(this);
			});
		}

		function _initializeTemplates() {
			Handlebars.registerHelper("isReadOnly", function() {
				if (app.readOnly === 'true' && app.isAttachments === 'false') {
					return ' isReadOnly ';	
				}
			});
			explorerTemplate = Handlebars.compile($('#fileExplorerTemplate').html()),
			viewerTemplate   = Handlebars.compile($('#fileViewImageContainerTemplate').html());
		}

		function _isBottomOfScroll() {
			var $me = $(this);
			return (($me.scrollTop() + $me.innerHeight()) >= this.scrollHeight);
		}

		function _isCollapsed() {
			return stateMap.$parent.hasClass('collapsed');
		}

		function _isFolder() {
			return stateMap.data.type === 'directory';
		}

		function _isFolderEmpty() {
			return stateMap.$parent.find('ul li').length === 0;
		}

		function _inProgress() {
			if (stateMap.isActive) {
				return true;
			} else {
				_setAsActive();
				return false;
			}
		}

		function loadFiles($deferred) {
			$.ajax({
				url : app.SOCKET_PATH + 'loadFiles',
				type : "GET"	
			})
			.done(function(response) {
				_onLoadFilesSuccess(response);
				$deferred.resolve();
			})
			.fail(function() { $deferred.reject(); });
		}

		function _onLoadFilesSuccess(response) {
			app.files = response.data.files;	
			app.folders = response.data.folders;
		}

		function _reinitializeFoundation() {
			$(document).foundation();
		}

		function _removeSpinner() {
			stateMap.$parent.removeClass('wait');	
		}

		function _resetOpenDirectoryIndicators() {
			$('#openedFolderName').attr('data-rel', stateMap.data.rel);
		}

		function _rtrim(someString, length) {
			return someString.substr(0, someString.length - length);
		}

		function _setAsActive() {
			stateMap.isActive = true;
			$('body').css('cursor', 'wait');
		}

		function _setAsInactive() {
			stateMap.isActive = false;
			$('body').css('cursor', 'default');
		}

		function _setFilesFromDirectory(dir) {
			if (!dir) {
				directory = app.BASE_FOLDER;
			} else {
				directory = decodeURIComponent(dir);
			}

			filesFromDirectory = _.chain(app.files)
				.filter(function(file) {
					return (app.BASE_FOLDER + file.currentPath) === directory;
				})
				.sortBy(function(file) {
					return file.title.toLowerCase();
				})
				.value();

			unshownFiles = _.map(filesFromDirectory, _.clone);
		}

		function _setMainElement() {
			$mainElement = $(this);
		}

		function _setOptions(userOptions) {
			if( userOptions.root			=== undefined ) userOptions.root			= '/';
			if( userOptions.expandSpeed		=== undefined ) userOptions.expandSpeed		= 500;
			if( userOptions.collapseSpeed	=== undefined ) userOptions.collapseSpeed	= 500;

			options = userOptions;
		}

		function _setStateMap() {
			stateMap.$anchor = $(this);
			stateMap.$parent = stateMap.$anchor.parent();	
			stateMap.data = _getData();
		}

		function _showTree(element, dir) {
			var $element = $(element),
				folders = _getFoldersFromDirectory(dir);
				
			_setFilesFromDirectory(_getRelativePath());

			$element.addClass('wait');

			_updateFileExplorer.call(this, $element, dir, {
				folders: folders,
				files: filesFromDirectory
			});
			_updateFileViewer();
			_setAsInactive();
		}

		function _toggleFile() {
			_trigger(stateMap.$anchor, 'filetreeclicked', stateMap.data);
		}

		function _toggleFolder($element, dir) {
			if (options.root === dir) {
				$element.find('UL:hidden').show(); 
			} else {
				$element.find('UL:hidden').slideDown({
					duration: options.expandSpeed
				});
			}
		}

		function _toggleTree() {
			if (_inProgress()) return false;

			if (_isCollapsed()) {
				_expandTree();
			} else {
				_collapseTree();
			}
		}

		function _trigger(element, eventType, data) {
			data.trigger = eventType;
			element.trigger(eventType, data);
		}

		function _triggerIEFlexbox() {
			if (!app.flexbox.hasFlexbox()) {

			}
			$('#fileViewer').trigger('append-viewer-items', $('.fileViewerImageContainerInner')); // used for flexbox ie workaround
		}

		function _updateFileExplorer($element, dir, templateData) {
			var explorerHTML = explorerTemplate(templateData);

			$element.removeClass('wait').append(explorerHTML);
			_toggleFolder($element, dir);
			_bindTree($element);
			$(this).parent().removeClass('collapsed').addClass('expanded');
		}

		function _updateFileViewer() {
			if (_.isEmpty(filesFromDirectory)) {
				$('#fileViewerInner').html('<div class="emptyFolder"><p>This folder is currently empty.</p></div>');
			} else {
				_clearFileViewer();
				appendFiles(unshownFiles);
			}
		}

		return {
			appendFiles: appendFiles,
			init : init,
			initializeTree : initializeTree,
			loadFiles : loadFiles
		};
	})();
});
