CKFinder.addPlugin('cropresize', function(api) {
    var imageUrl,
        fileName,
        folderName,
        SEPARATOR = '/',
        fileContextMenuOptions = {
            label: 'Crop Image',
            command : "myCommand"
        };

    CKFinder.dialog.add('cropresize', function(api) {
        var file = api.getSelectedFile(),
            dialogDefinition;

        setFileInfo(file);

        dialogDefinition = {
            title : "Crop " + file.name,
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
                label : '',
                title : 'Crop ' + fileName,
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
        if (!file.isImage()) {
           api.openMsgDialog("Image cropping", "This feature is only available for editing images.");
           return;
        }

        api.openDialog('cropresize');
    },
    function(file) {
        // Disable for files other than images.
        if (!file.isImage() || !api.getSelectedFolder().type) {
            return false;
        }

        if ( file.folder.acl.fileDelete && file.folder.acl.fileUpload ) {
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
        return '<iframe id="iframe_imageNotation" width="100%" style="height:100%" height="100%" src="'
            + CKFinder.getPluginPath('cropresize')
            + 'dialog.php?imageUrl=' + imageUrl
            + '&fileName=' + fileName
            + '&folderName=' + folderName + '"></iframe>';
    }

    function setFileInfo(file) {
        imageUrl = file.getUrl();
        fileName = file.name;
        folderName = getFolderPath(file.folder);

        console.log(folderName);
    }

    function getFolderPath(folder) {
        if (folder.parent && folder.parent.name !== 'Images') {
            return getFolderPath(folder.parent) + folder.name + SEPARATOR;
        } else {
            return folder.name + SEPARATOR; 
        }
    }
});