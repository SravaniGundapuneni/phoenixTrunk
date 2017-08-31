CKFinder.addPlugin('attachments', function(api) {
	var fileContextMenuOptions = {
			label: 'Add attachment(s)'
//			command : "myCommand" // what does this do?
		},
		SEPARATOR = '/',
		FILE_ROOT = 'http://localhost/loews/d/';
		THUMBS_FOLDER = 'Images/',
		BASE_FOLDER = '/d/';

		// save file to db
		// save thumb to db

	/*
		attachment=1
		moduleName=phoenixRates
		parentItemId=100	

		socket wants a fileId
			- where does it get this from?
			- why is it important?
			- can I attach images without a fileId?

		Stuff that happens in sockets:

	 */

	// We should check mode before we do attachments here
	// We should do whatever sockety stuff needs to happen to attach the image here.
	CKFinder.dialog.add('attachments', function(api) {
		var dialogDefinition = {
				title : 'Attachment definition title',
				minWidth : 800,
				minHeight : 500,
				onCancel : function() {
					api.refreshOpenedFolder();
					return true;
				},
				onOk : function() {
					api.refreshOpenedFolder();
					return true;
				},
				contents : [{
					id : 'tab1',
					label : 'foo label',
					title : 'Attachment contents title',
					expand : true,
					elements : [{
						type : 'html',
						html : getHtml()
					}]
				}],
				buttons : [ CKFinder.dialog.okButton, CKFinder.dialog.cancelButton ]
			};

		return dialogDefinition;
	});

	api.addFileContextMenuOption(fileContextMenuOptions, function(api, file) {
		var html = '',
			selectedFile,
			selectedFiles; 

		if (!file.isImage()) {
		   api.openMsgDialog('Attachment context title', 'This feature is only available for attaching images.');
		   return;
		}

		// TODO: pinpoint where the fileIds come into the picture
		// // As well as db saving

		selectedFiles = api.getSelectedFiles();

		for (selectedFile in selectedFiles) {
			html += getNewImageHtml(selectedFiles[selectedFile]);
		}

		$('#media-list', window.parent.document).append(html);
		api.closePopup();
	},
	function(file) {

		// TODO: refactor below, docs are kosher

		// Disable for files other than images.
		if (!file.isImage() || !api.getSelectedFolder().type) {
			return false;
		}

		if (file.folder.acl.fileDelete && file.folder.acl.fileUpload) {
			return true;
		} else {
			return -1;
		}

			/* api.connector.sendCommand( 'FileSize', { fileName : api.getSelectedFile().name }, function( xml ) {
				if ( xml.checkError() )
					return;

				var size = xml.selectSingleNode( 'Connector/FileSize/@size' );
				var msg = xml.selectSingleNode( 'Connector/MyMessage/@message' );
				api.openMsgDialog( "", "The exact size of a file is: " + size.value + " bytes");
				//api.openMsgDialog( "", "my message is : " + msg.value + "!");
			});*/
	});

	function getHtml() {
		return '<iframe id="iframe_imageNotation" width="100%" style="height:100%" height="100%" src="#"></iframe>';
	}

	function getNewImageHtml(file) {
		var folderPath = getFolderPath(file.folder);

		return '<div class="media-attachment-item" ref="#">' // mediaAttachmentItem->getId()
				+ '<div><img src="'
					+ api.config.thumbsUrl
					+ THUMBS_FOLDER
					+ folderPath
					+ file.name
				+ '"/></div>'
				+ '<div class="media-item-name">'
					+ file.name
				+ '</div>'
				+ '<div class="remove-attachment">X</div>' // removeURL
			+ '</div>'
			+ '<input type="hidden" name="attachments[]" value="'
				+ BASE_FOLDER + folderPath + file.name
			+ '"/>';
	}

	// TODO: promote this in a ckfinder utility class
	function getFolderPath(folder) {
		if (folder.parent && folder.parent.name !== 'Images') {
			return getFolderPath(folder.parent) + folder.name + SEPARATOR;
		} else {
			return folder.name + SEPARATOR; 
		}
	}
});