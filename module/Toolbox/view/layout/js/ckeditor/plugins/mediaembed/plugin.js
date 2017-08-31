/*
* Embed Media Dialog based on http://www.fluidbyte.net/embed-youtube-vimeo-etc-into-ckeditor
*
* Plugin name:      mediaembed
* Menu button name: MediaEmbed
*
* Youtube Editor Icon
* http://paulrobertlloyd.com/
*
* @author Fabian Vogelsteller [frozeman.de]
* @version 0.5
*/
( function() {
    CKEDITOR.plugins.add( 'mediaembed',
    {
        icons: 'mediaembed', // %REMOVE_LINE_CORE%
        hidpi: true, // %REMOVE_LINE_CORE%
        init: function( editor )
        {
            var iniVal = "";
            function setDefaultValue(val){
                this.iniVal = val;
            }
            function getDefaultValue(){
                return this.iniVal;
            }
           var me = this;
           
           CKEDITOR.dialog.add( 'MediaEmbedDialog', function (instance)
           {
               var tvs = prompt("Enter no of videos:\n(Maximum 5 videos)","1");
               var jd = getData();
               if(tvs > 5)
               {
                   alert("Maximum 5 videos are allowed at a time! Please try again!");
                   CKEDITOR.dialog.getCurrent().hide();
                   return false;
               }
               
               if(tvs)
                {
                    var totalVideos = parseInt(tvs);
                    if(totalVideos == '' || totalVideos == 'undefined' || totalVideos == 0 || totalVideos == '0')
                        totalVideos = 1;
                    
                    if(!this.iniVal)
                    {
                        for(var i=0; i < totalVideos; i++)
                        {
                           jd.contents[0].elements.push({id:'embedArea'+i,type:'textarea',label:'Paste Youtube Embed Code OR insert Vimeo ID'});
                        }
                    }
                    else
                    {
                           jd.contents[0].elements.push({id:'embedArea'+0,type:'textarea',label:'Paste Youtube Embed Code OR insert Vimeo ID',default: this.iniVal});
                    }
                }                
                
               return jd;
               
               function getData(){
                   
                 return  {
                 title : 'Embed Media',
                 minWidth : 550,
                 minHeight : 200,
                 contents :
                       [
                          {
                             id : 'iframe',
                             expand : true,
                             elements :[
                                 {
                                    type : 'select',
                                    id : 'videoSize',
                                    style : 'width:150px;',
                                    label : 'Select Video Size:',
                                    items : [ [ "Large", "0" ], [ "Small","1" ]],
                                    'default' : "1",
                                    onChange : function() {
                                            // this = CKEDITOR.ui.dialog.select
                                            //alert( 'Current value: ' + this.getValue() );
                                    }
                                 }
                              ]
                          }
                       ],
                       onLoad : function(){
                            $(".cke_dialog_contents").find("TEXTAREA.cke_dialog_ui_input_textarea").attr("rows","3");
                       },
                       onOk: function() {
                           
                        var vs = this.getContentElement('iframe', 'videoSize').getValue();
                        
                        $("TEXTAREA.cke_dialog_ui_input_textarea").each(function(){
                            var str = $(this).val();
                            var ifrme = instance.document.createElement('iframe');
                            ifrme.setAttribute("width", "420px");
                            ifrme.setAttribute("height", "315px");
                            ifrme.setAttribute("frameborder", "0");
                            ifrme.setAttribute("allowfullscreen");
                            var intRegex = /^\d+$/;
                            if(intRegex.test(str)) 
                            {
                                ifrme.setAttribute("src", "//player.vimeo.com/video/"+str);
                            }
                            else
                            {
                                str = str.replace('https:','');
                                str = str.replace('http:','');
                                str = str.replace('watch?v=','embed/');
                                ifrme.setAttribute("src", str);
                            }
                            
                            
                            instance.insertElement(ifrme);
                            
                            $("iframe").each(function(){
                                $(this).wrap("<div class='flex-video'></div>");
                                $(this).parent().append("<span>Video Title</span>");
                            }); 
                        });                        
                  }
              };
               }
           } );
           
            editor.on( 'doubleclick', function( evt )
            {
               var element = evt.data.element;
                  if ( element.is( 'img' ) )
                    {
                      if(element.hasClass("video-thumbnail")) 
                      {
                          setMediaEmbedData(element.getAttribute( 'data-vdoid' ));
                          evt.data.dialog = 'MediaEmbedDialog';
                      }
                    }
                    if(element.hasClass("imagelib"))
                       evt.data.dialog = 'imageLibraryDialog';
                   else if(element.hasClass("thumbnail"))
                       evt.data.dialog = 'MediaEmbedDialog';
                   else
                       return;
            });
            editor.addCommand( 'MediaEmbed', new CKEDITOR.dialogCommand( 'MediaEmbedDialog',
                { allowedContent: 'iframe[*]' }
            ) );

            editor.ui.addButton( 'MediaEmbed',
            {
                label: 'Embed Media',
                command: 'MediaEmbed',
                toolbar: 'mediaembed'
            });
        }
    } );
} )();
