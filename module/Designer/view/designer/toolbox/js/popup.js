sizeConst = 60;

// blocks previous popup if exists
// or the entire body otherwise
function block() {
    $('<div/>').css({top:0, left:0, height:'100%', width:'100%'}).attr('class', 'overlay').appendTo($(".popup:last").length ? $(".popup:last") : $('body'));
}

function popupCenter($popup) {
    if ( ! $popup )
        $popup = $('.popup:last');
    if ( ! $popup.length )
        return;
    var $parent = $('body');
    $popup.css({left:($parent.outerWidth()-$popup.outerWidth())/2, top:($parent.outerHeight()-$popup.outerHeight())/2});
}

function popupSizeV($popup) {
    if ( ! $popup )
        $popup = $('.popup:last');
    if ( ! $popup.length )
        return;
    var headHeight = $popup.find('.interview_header').outerHeight();
    var footHeight = $popup.find('.interview_footer').outerHeight();
    var $body = $popup.find('.interview_body');
    if ( ! $body.length )
        return;
    $body.css({height:'auto'});
    $popup.css({height:'auto'});
    var scrollHeight = $body[0].scrollHeight;
    var maxHeight = $('body').outerHeight() - sizeConst*2;
    var fullHeight = scrollHeight + headHeight + footHeight + sizeConst;
    var height = maxHeight < fullHeight ? maxHeight - headHeight - footHeight - sizeConst : scrollHeight;
//  $('.jspContainer').css({height:height});
    $body.css('height', height);
//  $body.data('jsp').reinitialise();
}

function popupSizeH($popup) {
    // widen scroll-area by the width of the
    // V scroll bar if the scroll bar is visible
    var $area = $popup.find(".interview_body");
    if ( ! $area.hasClass('no_width_adjust') && $area[0] && $area[0].scrollHeight > $area.height() )
        $area.css({width:parseInt($area.css('width')) + 20 + 'px'});
}

function createPopup(id, content) {
    var $old = $('.popup#'+id);
    if ( $old.length ) {
        var $body = $old.find('.body');
        $body.empty();
        $body.append(content);
        popupSizeV($old);
        return $old;
    }
    block();
    var $popup = $('<div/>', {id:id, 'class':'popup'});
    $popup
        .css('position', 'absolute')
        .append("<div class='body'>"+content+"</div>")
        .appendTo($('body'))
        .fadeIn('fast');
    $(document).keydown(function(e) {
        if ( e.keyCode == 27 ) { // esc
            deletePopup();
            return false;
        }
        if ( e.keyCode != 9 ) // not tab
            return true;
    });
    popupSizeV($popup);
    popupSizeH($popup);
    popupCenter($popup);
    return $popup.draggable();
//    $($popup).onPopupCreate();
//    return $popup;
}

function deletePopup() {
    $(".popup:last, .overlay:last").remove();
}

function closePopup() {
    $(".popup:last").fadeOut('fast', deletePopup);
}

function msgBox(title, message, buttons, image) {
    block();  // blocks active background
    // compile the content
    var html = '<table style="width:10px" cellspacing="0" cellpadding="0" border="0">';
    html += '<tr><td class="title" colspan="2">'+title+'</td></tr>';
    html += '<tr><td class="image">'+(image?'<img src="'+image+'" />':'')+'</td><td class="content">'+message+'</td></tr>';
    var footer = '';
    for (var key in buttons)
        footer += '<button type="button" key="'+key+'">'+key+'</button>';
    html += '<tr><td class="footer" colspan="2">'+footer+'</td></tr></table>';
    var $msgBox = $("<div id='msg_box' style='position:absolute' />"); // create the container
    $msgBox.html(html); // put the content inside the container
    $msgBox.draggable({handle:".title"}); // make it draggable by the title bar
    $('body').append($msgBox); // add it to the end of the body
    popupCenter($msgBox);
    // bind a click event handlers to the buttons
    $('#msg_box button').each( function() {
        $(this).click( function() {
            // removes the message box and the blocking overlay
            $("#msg_box, .overlay:last").remove();
            buttons[$(this).attr('key')]();
        });
    });
}
/*
function bindPopupEvents() {
    $(document).bind("taconite-rawdata-notify", function(e, type, parsed, raw) {
        if ( ! parsed.popups )
            return;
        for ( var i=0; i<parsed.popups.length; ++i ) {
//          var content = parsed.popups[i].content;
            var $popup = createPopup(parsed.popups[i].id, parsed.popups[i].content);
            $focusable = $popup.find('*:focusable');
            $focusable.keypress( function(e) {
                if ( e.keyCode != 9 ) // not tab
                    return true;
                var $all = $(".popup:last *:focusable");
                var i = $.inArray($(e.target)[0], $all);
                return ! ( i == - 1 || ! e.shiftKey && i == $all.length-1 || e.shiftKey && i == 0 );
            });
/*
                $popup.change(e, function() {
                        setChanged(this);
                });

                $popup.find('.triggered').change( function() {
                        showElement(this.name);
                });
*//*
        }
    });
}
$(document).ready(function() {
    bindPopupEvents();
});
*/