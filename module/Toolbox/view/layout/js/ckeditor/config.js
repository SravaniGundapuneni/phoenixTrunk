/**
 * @license Copyright (c) 2003-2013, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

var CKEDITOR_BASEPATH = app.constants.toolboxIncludeUrl+'module/Toolbox/view/layout/js/ckeditor/';
//var CKEDITOR_BASEPATH = 'http://localhost/PhoenixFoundation/module/Toolbox/view/layout/js/ckeditor/';


CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
config.extraPlugins = "templates,image,mediaembed,autogrow,sourcedialog,showblocks,accordion,slideshow,pdflink,twoimagelayout";
config.startupOutlineBlocks = true;
config.skin = 'kama';
config.tcMaxWidth = 1300;

config.imageBrowser_pdflistUrl = app.constants.toolboxIncludeUrl+'module/Toolbox/view/layout/js/ckfinder/ckfinder.html?type=PDFs';

config['LicenseName'] = 'TravelClick';
config['LicenseKey'] = 'U6HH-WRWE-BEBG-VMS5-PAS8-ZX2N-Q5VU';

config.filebrowserImageBrowseUrl = app.constants.toolboxIncludeUrl+'module/Toolbox/view/layout/js/ckfinder/ckfinder.html?type=Images';
config.filebrowserImageBrowseUrl_slideshow = app.constants.toolboxIncludeUrl+'module/Toolbox/view/layout/js/ckfinder/ckfinder.html?type=Images';
config.filebrowserImageBrowseUrl_twoimagelayout = app.constants.toolboxIncludeUrl+'module/Toolbox/view/layout/js/ckfinder/ckfinder.html?type=Images';

config.toolbar = 'MyToolbar';
config.allowedContent = true;

config.format_h1 = { element: 'h1', attributes: { 'class': 'pgtitle' } };
config.format_h2 = { element: 'h2', attributes: { 'class': 'pgsubtitle' } };
config.format_ul = { element: 'ul', attributes: { 'class': 'pgli' } };

config.toolbar_MyToolbar =
[
	{ name: 'clipboard', items : [ 'Sourcedialog','-','Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo' ] },
	{ name: 'editing', items : [ 'Find','Replace','-','SelectAll','-','SpellChecker', 'Scayt' ] },
	{ name: 'important', items : [ 'Templates','-','Image','-','MediaEmbed','-','ShowBlocks','-','Accordion','-','ImageLibrary','-','Slideshow','-','PdfLink','-','TwoImageLayout'] },
	'/',
	{ name: 'basicstyles', items : [ 'Bold','Italic','Underline','Strike','Subscript','Superscript','-','RemoveFormat' ] },
	{ name: 'paragraph', items : [ 'NumberedList','BulletedList','-','Outdent','Indent','-','Blockquote','CreateDiv',
	'-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','-','BidiLtr','BidiRtl' ] },
	{ name: 'links', items : [ 'Link','Unlink','Anchor','HorizontalRule' ] },
	'/',
	{ name: 'styles', items : [ 'Styles','Format','Font','FontSize' ] },
	{ name: 'colors', items : [ 'TextColor','BGColor' ] }
];
};
