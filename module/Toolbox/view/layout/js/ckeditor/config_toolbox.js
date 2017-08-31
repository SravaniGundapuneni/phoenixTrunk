/**
 * @license Copyright (c) 2003-2013, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */
 
var CKEDITOR_BASEPATH = app.constants.toolboxIncludeUrl+'module/Toolbox/view/layout/js/ckeditor/';
//var CKEDITOR_BASEPATH = 'localhost/loews/templates/main/libs/ckeditor/';

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
config.extraPlugins = "imagebrowser,templates,image,mediaembed,autogrow,sourcedialog,showblocks,accordion,imagelibrary";
config.startupOutlineBlocks = true;
config.skin = 'kama';
config.tcMaxWidth = 1300;
config.imageBrowser_listUrl = "image_list.json";

config.toolbar = 'MyToolbar';
config.allowedContent = true;

config.format_h1 = { element: 'h1', attributes: { 'class': 'pgtitle' } };
config.format_h2 = { element: 'h2', attributes: { 'class': 'pgsubtitle' } };
 
config.toolbar_MyToolbar =
[

	{ name: 'basicstyles', items : [ 'Bold','Italic','Underline' ] },
	{ name: 'paragraph', items : ['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock' ] },
	{ name: 'links', items : [ 'Link','Unlink' ] }
];
};
