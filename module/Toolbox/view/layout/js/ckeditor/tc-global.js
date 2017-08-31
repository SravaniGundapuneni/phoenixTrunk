/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


( function() {
    $(document).ready(function(){

    //  Responsive Images
        $(".sectionwrap-content").find("img").each(function(){
            if(!($(this).attr("class") == "containerImage"))
            {
                if(!$(this).hasClass("imagelib"))
                    $(this).addClass("containerImage");
                    //$(this).removeClass("addthis_shareable");}
            }
            {
                if(!$(this).hasClass("vm")){
                    $(this).removeClass("containerImage");
                }
            }
        });

        $(".ad-gallery .ad-controls").hide();

         // Responsive Slider Images
        $(window).resize(function() {
          $(".ad-gallery").find(".ad-image-wrapper").each(function(){
             $(this).css("max-height","");
             // $(this).find(".ad-image .ad-image-description").css("margin-bottom","9px");
          });
        });

        //  Image Library
        $(".sectionwrap-content").find(".imagelib").each(function(){
            if(!($(this).parent().is("a")))
                $(this).wrap("<a class='fancyboxImage'></a>");
        });

        $(".sectionwrap-content").find(".fancyboxImage").each(function(){
            $(this).attr("href",$(this).find(".imagelib").attr("src"));
            $(this).attr("rel","gallery");
        });

        //  Video Library

        $(".sectionwrap-content").find(".vm").each(function(){
            $(this).wrap("<a class='fancyboxVideo fancybox.iframe'></a>");
        });

        $(".sectionwrap-content").find(".vm").each(function(){
            if(!($(this).parent().is("a")) && !($(this).parent().is("a").hasClass("fancyboxVideo")))
            {
                $(this).wrap("<a class='fancyboxVideo fancybox.iframe'></a>");
            }
        });
        
        $(".sectionwrap-content").find(".fancyboxVideo").each(function(){
                if($(this).find(".vm").hasClass("videoWrapperSmall") && !($(this).parent().hasClass("video-library")))
                {
//                    if(!($(this).parent().hasClass("video-library") && $(this).parent().hasClass("video-library-large")))
                        $(this).wrap("<div class='video-library' />");
                }
//                else
//                {
//                    if(!($(this).parent().hasClass("video-library") && $(this).parent().hasClass("video-library-large")))
//                        $(this).wrap("<div class='video-library-large' />");
//                }
        });

//        $(".sectionwrap-content").find(".fancyboxVideo").each(function(){
//                if($(this).find(".vm").hasClass("videoWrapperSmall") && !($(this).parent().hasClass("video-library")))
//                {
//                    if(!($(this).parent().hasClass("video-library") && $(this).parent().hasClass("video-library-large")))
//                        $(this).wrap("<div class='video-library' />");
//                }
//                else
//                {
//                    if(!($(this).parent().hasClass("video-library") && $(this).parent().hasClass("video-library-large")))
//                        $(this).wrap("<div class='video-library-large' />");
//                }
//        });

        $(".sectionwrap-content").find(".fancyboxVideo").each(function(){
            $(this).attr("href",$(this).find(".vm img").attr("data-video")+'?autoplay=1');

        });

        $(".sectionwrap-content").find(".video-library").each(function(){
            if($(this).find('span').length > 0)
            {
                //$(this).append('<div class="vdo-title"><a class="fancyboxVideo fancybox.iframe" href='+$(this).find('a.fancyboxVideo:first').attr("href")+'>'+$(this).find('span').text()+'</a></div>');
                $(this).append('<div class="vdo-title">'+$(this).find('span').text()+'</div>');
                $(this).find('span').remove();
            }
        });

        $(".sectionwrap-content").find(".video-library-large").each(function(){
            if($(this).find('span').length > 0)
            {
                //$(this).append('<div class="vdo-title"><a class="fancyboxVideo fancybox.iframe" href='+$(this).find('a.fancyboxVideo:first').attr("href")+'>'+$(this).find('span').text()+'</a></div>');
                $(this).append('<div class="vdo-title">'+$(this).find('span').text()+'</div>');
                $(this).find('span').remove();
            }
        });

        var thumndata = [];
        $(".sectionwrap-content").find(".fancyboxVideo").each(function(index){
            thumndata[index] = '';
            if($(this).find(".vm img").attr("data-hasthumbnail") == "false")
            {
                $.ajax({
                    url: 'http://www.vimeo.com/api/v2/video/' + $(this).find(".vm img").attr("data-vdoid") + '.json?callback=?',
                    dataType: 'json',
                    type: 'POST',
                    async: false,
                    success: function(data) {
                        thumndata[index] = data[0].thumbnail_medium;
                    }
                }).done(function(){
                    $(".sectionwrap-content").find(".fancyboxVideo").each(function(index){
                        if($(this).find(".vm img").attr("data-hasthumbnail") == "false")
                        {
                            if(thumndata[index] != '')
                                $(this).find(".vm img").attr("src",thumndata[index]);
                        }
                    });
                });
            }
        });

    });
} )();