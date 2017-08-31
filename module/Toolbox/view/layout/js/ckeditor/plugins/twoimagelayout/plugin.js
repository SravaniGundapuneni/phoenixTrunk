/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


CKEDITOR.plugins.add( 'twoimagelayout',
{
	init: function( editor )
	{
                editor.editMode = false;
                editor.imgUrl1 = '';
                editor.imgUrl2 = '';
                editor.btnTxt1 = 'VIEW OUR ROOMS';
                editor.btnTxt2 = 'VIEW OUR ROOMS';
                function setTwoImageVars(imArr, btArr){
                    editor.editMode = true;
                    editor.imgUrl1 = imArr[0];
                    editor.imgUrl2 = imArr[1];
                    editor.btnTxt1 = btArr[0];
                    editor.btnTxt2 = btArr[1];
                }
                
		editor.ui.addButton( 'TwoImageLayout',
		{
			label: 'Two Image Layout',
			command: 'twoImageLayoutDialogDialog',
			icon: this.path + 'images/twoimage.png'
		} );
                
                editor.addCommand( 'twoImageLayoutDialogDialog', new CKEDITOR.dialogCommand( 'twoImageLayoutDialogDialog' ) );
                
                editor.on( 'doubleclick', function( evt )
                {
                      var element = evt.data.element;
                      var imgArray = [];
                      var btnArray = [];
                      if ( element.is( 'img' ) || element.is( 'a' ) )
                      {
                        if(element.hasClass("twoColImg") || element.hasClass("twoColAnchor")) 
                        {
                            $(".containerTwoImageColumn").children().each(function(){
                               $(this).find('img').each(function(){
                                   imgArray.push($(this).attr('src'));
                               });
                               $(this).find('a').each(function(){
                                   btnArray.push($(this).text());
                               });
                            });
                            setTwoImageVars(imgArray,btnArray);
                            evt.data.dialog = 'twoImageLayoutDialogDialog';
                        }
                      }
                });
                
                CKEDITOR.dialog.add( 'twoImageLayoutDialogDialog', function( editor )
                {
                            return {
                                title : 'Two Image Layout',
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
                                                                id: "txtButton1",
                                                                type: "text",
                                                                label: "Button Text1:",
                                                                'default': editor.btnTxt1,
                                                                required: !0
                                                        },
                                                        {
                                                                id: "txtUrl1",
                                                                type: "text",
                                                                label: editor.lang.common.url,
                                                                'default': editor.imgUrl1,
                                                                required: !0
                                                        },
                                                        {
                                                                type: "button",
                                                                id: "browse",
                                                                filebrowser: {
                                                                    action: "Browse",
                                                                    target: "Upload:txtUrl1",
                                                                    url:    editor.config.filebrowserImageBrowseUrl_twoimagelayout
                                                                },
                                                                style: "float:right",
                                                                label: editor.lang.common.browseServer
                                                        },
                                                        {
                                                                id: "txtButton2",
                                                                type: "text",
                                                                label: "Button Text2:",
                                                                'default': editor.btnTxt2,
                                                                required: !0
                                                        },
                                                        {
                                                                id: "txtUrl2",
                                                                type: "text",
                                                                label: editor.lang.common.url,
                                                                'default': editor.imgUrl2,
                                                                required: !0
                                                        },
                                                        {
                                                                type: "button",
                                                                id: "browse",
                                                                filebrowser: {
                                                                    action: "Browse",
                                                                    target: "Upload:txtUrl2",
                                                                    url:    editor.config.filebrowserImageBrowseUrl_twoimagelayout
                                                                },
                                                                style: "float:right",
                                                                label: editor.lang.common.browseServer
                                                        }
                                                ]
                                        }
                                ],
                                onOk: function() {            
                                    
                                    var btntext1 = this.getContentElement('Upload', 'txtButton1').getValue();
                                    var btntext2 = this.getContentElement('Upload', 'txtButton2').getValue();
                                    var imgurl1 = this.getContentElement('Upload', 'txtUrl1').getValue();
                                    var imgurl2 = this.getContentElement('Upload', 'txtUrl2').getValue();
                                    
                                    if(editor.editMode)
                                    {
                                        $('.containerTwoImageColumn').find('.imageColumn_1').find('img').attr('src',imgurl1);
                                        $('.containerTwoImageColumn').find('.imageColumn_1').find('img').attr('data-cke-saved-src',imgurl1);
                                        $('.containerTwoImageColumn').find('.imageColumn_1').find('a').text(btntext1);
                                        $('.containerTwoImageColumn').find('.imageColumn_2').find('img').attr('src',imgurl2);
                                        $('.containerTwoImageColumn').find('.imageColumn_2').find('img').attr('data-cke-saved-src',imgurl2);
                                        $('.containerTwoImageColumn').find('.imageColumn_2').find('a').text(btntext2);
                                    }
                                    else
                                    {
                                        //var img = '<img sharingclass="addthis_shareable" editable="true" addthis:url="http://loews.t1.devsite-1.com/d/Santa Monica/Accommodations/LSMB_53743023_Double_Guestroom_3570x2752.jpg" addthis:title="Loews Luxury Hotels | Official Site" class="containerImage textSwitchHolder version_1 addthis_shareable" alt="Loews Hotels!" src="http://loews.t1.devsite-1.com/d/Santa Monica/Accommodations/LSMB_53743027_King_Room_with_Balcony_5569x3535.jpg" style="max-width: 1600px; max-height: 1016px;">';
                                        var btn1 = '<a class="blueLinkBrownArrow twoColAnchor" href="#">'+btntext1+'</a>';
                                        var btn2 = '<a class="blueLinkBrownArrow twoColAnchor" href="#">'+btntext2+'</a>';

                                        var img1 = '<img src="'+imgurl1+'">';
                                        var img2 = '<img  src="'+imgurl2+'">';
                                        var str = '<div class="containerTwoImageColumn"><div class="imageColumn_1" id="column1_img">'+img1+btn1+'</div><div class="imageColumn_2" id="column2_img">'+img2+btn2+'</div></div>';
                                        editor.insertHtml( str );
                                    }
                                    editor.editMode = false;
                                }
                        };
                });
	}
} );
