/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

CKEDITOR.plugins.add( 'pdflink',
{
	init: function( editor )
	{
                editor.editMode = false;
                editor.hrefText = "";
                editor.linkText = "";
                editor.linkindex = "";
                editor.targetText = "Select Target";
                function setPDFVars(hrf,trg,lnk, ind){
                    editor.editMode = true;
                    editor.hrefText = hrf;
                    editor.targetText = trg;
                    editor.linkText = lnk;
                    editor.linkindex = ind;
                }
                
		editor.ui.addButton( 'PdfLink',
		{
			label: 'Insert PDF Link',
			command: 'pdfLinkDialog',
			icon: this.path + 'images/pdf.png'
		} );
                
                editor.addCommand( 'pdfLinkDialog', new CKEDITOR.dialogCommand( 'pdfLinkDialog' ) );
                
                editor.on( 'doubleclick', function( evt )
                {
                      var element = evt.data.element;
                      var ind = '';
                      if ( element.is( 'a' ) )
                      {
                        if(element.hasClass("pdflink")) 
                        {
                            $('.pdflink').each(function(){
                               if(element.getAttribute( 'data-pdf-text' ) === $(this).attr('data-pdf-text'))
                                   ind = $(this).index();
                            });
                            setPDFVars(element.getAttribute( 'href' ),element.getAttribute( 'target' ), element.getAttribute( 'data-pdf-text' ), ind);
                            evt.data.dialog = 'pdfLinkDialog';
                        }
                      }
                });
                
                CKEDITOR.dialog.add( 'pdfLinkDialog', function( editor )
                {
                        return {
                            title : 'PDF Link',
                            minWidth : 400,
                            minHeight : 200,
                            contents :
                            [
                                {
                                    id : 'Upload',
                                    hidden : true,
                                    filebrowser : 'uploadButton',
                                    label : editor.lang.common.browseServer,
                                    elements :
                                            [
                                                    {
                                                            type : 'file',
                                                            id : 'upload',
                                                            label : editor.lang.common.browseServer,
                                                            style: 'height:40px',
                                                            size : 38,
                                                            hidden: true
                                                    },
                                                    {
                                                            type : 'fileButton',
                                                            id : 'uploadButton',
                                                            filebrowser : 'txtUrl',
                                                            label : editor.lang.common.browseServer,
                                                            hidden: true,
                                                            'for' : [ 'Upload', 'upload' ]
                                                    },
                                                    {
                                                        id: "txtLinkText",
                                                        type: "text",
                                                        label: "Link Text:",
                                                        'default':editor.linkText ? editor.linkText :'PDF Link Text',
                                                        required: !0
                                                    },
                                                    {
                                                            type : 'select',
                                                            id : 'selectTarget',
                                                            style : 'width:150px;',
                                                            label : 'Target:',
                                                            items : [ [ "Select Target", "0" ], [ "New Window (_blank)", "_blank" ], [ "Topmost Window (_top)","_top" ], [ "Same Window (_self)","_self" ], [ "Parent Window (_parent)","_parent" ]],
                                                            'default' : editor.targetText,
                                                            onChange : function() {
                                                                return this.getValue();
                                                            }
                                                    },
                                                    {
                                                            id: "txtUrl",
                                                            type: "text",
                                                            label: editor.lang.common.url,
                                                            'default':editor.hrefText,
                                                            required: !0
                                                    },
                                                    {
                                                            type: "button",
                                                            id: "browse",
                                                            filebrowser: {
                                                                action: "Browse",
                                                                target: "Upload:txtUrl",
                                                                url:editor.config.imageBrowser_pdflistUrl
                                                            },
                                                            style: "float:right",
                                                            label: editor.lang.common.browseServer
                                                    }

                                            ]
                                    }
                            ],
                            onLoad: function(){

                            },
                            onOk: function() {
                                
                                var linktxt = this.getContentElement('Upload', 'txtLinkText').getValue();
                                var target = this.getContentElement('Upload', 'selectTarget').getValue();
                                var pdfURL = this.getContentElement('Upload', 'txtUrl').getValue();
                                
                                var parts = pdfURL.split('.');
                                var extenstion = (parts[parts.length-1]).toLowerCase();
                                if(extenstion == 'pdf')
                                {
                                    if(editor.editMode)
                                    {
                                        $('.pdflink').each(function(){
                                            if($(this).index() === editor.linkindex)
                                            {
                                                $(this).attr('href', pdfURL);
                                                $(this).attr('target', target);
                                                $(this).attr('data-pdf-text', linktxt);
                                                $(this).attr('data-cke-saved-href', pdfURL);
                                                $(this).html($(this).attr("data-pdf-text")) ;
                                            }
                                         });
                                    }
                                    else
                                    {
                                        var pdf_a = editor.document.createElement('a');
                                        pdf_a.setAttribute("href", pdfURL);
                                        pdf_a.setAttribute("target", target);
                                        pdf_a.setAttribute("class", "pdflink");
                                        pdf_a.setAttribute("data-pdf-text", linktxt);
                                        editor.insertElement(pdf_a);
                                        $(".pdflink").each(function(){
                                           $(this).html($(this).attr("data-pdf-text")) ;
                                        });
                                    }
                                }
                                else
                                {
                                    alert("Only .pdf extension URL is allowed!");
                                }
                                
                                editor.editMode = false;
                            }
                    };
                });
	}
} );
