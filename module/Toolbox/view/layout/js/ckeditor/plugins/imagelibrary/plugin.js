/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


CKEDITOR.plugins.add( 'imagelibrary',
{
	init: function( editor )
	{
                var iniVal = "";
                function setDefaultValue(val){
                    this.iniVal = val;
                }
                function getDefaultValue(){
                    return this.iniVal;
                }
		editor.ui.addButton( 'ImageLibrary',
		{
			label: 'Insert an image to library',
			command: 'imageLibraryDialog',
			icon: this.path + 'images/image-library.png'
		} );
                
                editor.addCommand( 'imageLibraryDialog', new CKEDITOR.dialogCommand( 'imageLibraryDialog' ) );
                
                editor.on( 'doubleclick', function( evt )
                {
                      var element = evt.data.element;
                      if ( element.is( 'img' ) )
                      {
                        if(element.hasClass("imagelib")) 
                            setDefaultValue(element.getAttribute( 'src' ));
                        else
                            setDefaultValue(element.getAttribute( 'data-vdoid' ));
                      }
                      if(element.hasClass("imagelib"))
                         evt.data.dialog = 'imageLibraryDialog';
                     else if(element.hasClass("thumbnail"))
                        evt.data.dialog = 'MediaEmbedDialog';
                    else
                        return;
                });
                
                CKEDITOR.dialog.add( 'imageLibraryDialog', function( editor )
                {
                        var ti = prompt("Enter number of images that you want to upload","1");
                        var jd = getImageData();
                        if(ti)
                         {
                             var totalImages = parseInt(ti);
                             if(totalImages == '' || totalImages == 'undefined' || totalImages == 0 || totalImages == '0')
                                 totalImages = 1;

                             if(!this.iniVal)
                             {
                                 for(var i=0; i < totalImages; i++)
                                 {
                                        jd.contents[0].elements.push({
                                                id: "txtUrl"+i,
                                                type: "text",
                                                label: editor.lang.common.url,
                                                required: !0
                                        });
                                        jd.contents[0].elements.push({
                                            type: "button",
                                            id: "browse"+i,
                                            filebrowser: {
                                                action: "Browse",
                                                target: "Upload:txtUrl"+i,
                                                url:    editor.config.filebrowserImageBrowseUrl
                                            },
                                            style: "float:right",
                                            label: editor.lang.common.browseServer
                                        });
                                 }
                             }
                             else
                             {
                                        jd.contents[0].elements.push({
                                                id: "txtUrl",
                                                type: "text",
                                                label: editor.lang.common.url,
                                                default:this.iniVal,
                                                required: !0
                                        });
                                        jd.contents[0].elements.push({
                                            type: "button",
                                            id: "browse",
                                            filebrowser: {
                                                action: "Browse",
                                                target: "Upload:txtUrl",
                                                url:    editor.config.filebrowserImageBrowseUrl
                                            },
                                            style: "float:right",
                                            label: editor.lang.common.browseServer
                                        });
                             }
                         }                

                        return jd;

                        function getImageData(){
                            return {
                                title : 'Image Library',
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
                                                        }
                                                ]
                                        }
                                ],
                                onOk: function() {
                                    
                                    $(".cke_dialog_ui_input_text").each(function(){
                                        if(!($(this).val() == ''))
                                        {
                                            var parts = $(this).val().split('.');
                                            if(!(parts[parts.length-1] == 'pdf'))
                                            {
                                                var img = editor.document.createElement('img');
                                                img.setAttribute("src", $(this).val());
                                                img.setAttribute("width", "100%");
                                                img.setAttribute("height", "auto");
                                                img.setAttribute("rel", "gallery");
                                                img.setAttribute("class", "imagelib");
                                                editor.insertElement(img);
                                            }
                                            else
                                            {
                                                alert("Only images link are allowed!");
                                            }
                                        }

                                    });
                                    
                                    $(".imagelib").each(function(){
                                        if(!($(this).parent().attr("class") == "imageLibWrapper"))
                                        {
                                            $(this).wrap("<div class='imageLibWrapper'></div>");
                                        }
                                    });
                                }
                        };
                        }
                });
	}
} );
