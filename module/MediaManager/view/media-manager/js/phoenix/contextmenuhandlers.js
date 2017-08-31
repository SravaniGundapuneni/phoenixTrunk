$(function() {
	if (app === undefined) {
		throw new Error('app is undefined');
	}

	app.contextMenuOptions = {
		basic: [
			{
				id: "quit", 
				menuOrder: 20,
				options : {
					callback: function(key, opt) {
						return true;
					},
					className: 'contextMenuClose',
					name: "<i class='fa fa-close'></i>"
				}
			},
			/*
			{
				id: "download", 
				menuOrder: 60,
				options: {
					name: "<i class='fa std-fa fa-download'></i>Download"
				}
			},
			*/
			{
				id: "delete", 
				menuOrder: 80,
				options: {
					callback: function(key, opt) {
						var isSure;
						opt.$menu.hide();
						isSure = confirm('Are you sure you wish to delete this item?');
						if (isSure) {
							app.contextMenu.deleteItem($(this).closest('.fileViewerImageContainer'));
						}
					},
					name: "<i class='fa std-fa fa-trash-o'></i>Delete"
				}
			}
		],
		image: [
			{
				id: "crop",
				menuOrder: 70,
				options: {
					callback: function(key, opt) {
						app.contextMenu.cropImage($(this).next());
					},
					name: "<i class='fa std-fa fa-crop'></i>Crop"
				}
			},
			{
				id: "edit", 
				menuOrder: 75,
				options: {
					callback: function(key, opt) {
						app.contextMenu.editItem($(this).closest('.fileViewerImageContainer'));
					},
					name: "<i class='fa std-fa fa-pencil-square'></i>Edit"
				}
			}
		],
		zip: [
			{
				id: "zip",
				menuOrder: 100,
				options: {
					callback: function(key, opt) {
						app.contextMenu.unzipFiles($(this).closest('.fileViewerImageContainer'));
						opt.$menu.hide();
					},
					name: "<i class='fa std-fa fa-file-zip-o'></i>Unzip"
				}
			}
		],
		attachments: [
			{
				id: "attachments", 
				menuOrder: 40,
				options : {
					callback: function(key, opt) {
						app.contextMenu.addAttachments();
						opt.$menu.hide();
					},
					name: "<i class='fa std-fa fa-plus-square'></i>Attach"
				}
			}
		],
		folderBasic: [
			{
				id: "quit_folder", 
				menuOrder: 1020,
				options : {
					callback: function(key, opt) {
						return true;
					},
					className: 'contextMenuClose',
					name: "<i class='fa fa-close'></i>"
				}
			},
			{
				id: "add_folder",
				menuOrder: 1040,
				options: {
					callback: function(key, opt) {
						var anchor = $(this).children().first();
						app.contextMenu.addNewFolder(anchor);
						opt.$menu.hide();
					},
					name: "<i class='fa std-fa fa-plus-square'></i>Add Folder"
				}
			}
		],
		folderFull: [
		/*
			{
				id: "rename_folder",
				menu: 1060,
				options: {
					callback: function(key, opt) {
						var $folderLink = $(this).children().first(),
							me = this;

						app.contextMenu.editFolder($folderLink)
							.done(function(response) {
								if (response && response[0] === 'success') {
									console.log('change the folder name in file viewer yo!');
									console.log('change image source!');
								}
								opt.$menu.hide();
							})
							.fail(function() {
								opt.$menu.hide();
							});
						console.log('rename folder');
						console.log(key);
						console.log(opt);
						console.log(this);
						return true;
					},
					name: "<i class='fa std-fa fa-pencil-square'></i>Rename Folder"
				}
			},
			*/
			{
				id: "delete_folder",
				menu: 1080,
				options: {
					callback: function(key, opt) {
						var $folderLink = $(this).children().first(),
							me = this;

						app.contextMenu.deleteFolder($folderLink)
							.done(function(response) {
								if (response && response[0] === 'success') {
									$(me).remove();
								}
								opt.$menu.hide();
							})
							.fail(function() {
								opt.$menu.hide();
							});
					},
					name: "<i class='fa std-fa fa-trash-o'></i>Delete Folder"
				}
			}
		]
	};
});
