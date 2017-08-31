//================================================================================================================================
contextMenu = [
    {Properties: {
        onclick: function(menuItem, menu) { showProperties(this); },
        title: 'Open properties',
        icon: 'images/settings.png'
        }
    },
    {'Lock/Unlock': {
        onclick: function(menuItem, menu) { lockBlock(this); },
        title: 'Lock/Unlock element',
        icon: 'images/lock.png'
        }
    },
    {'Save Block': {
        onclick: function(menuItem, menu) { saveBlock(this); },
        title: 'Save block',
        icon: 'images/save.png'
        }
    },
    {'Send to Background': {
        onclick: function(menuItem, menu) { toBackground(this); },
        title: 'Send to background',
        icon: ''
        }
    },
    {'Bring to Front': {
        onclick: function(menuItem, menu) { toFront(this); },
        title: 'Bring to front',
        icon: ''
        }
    },
    {'Delete': {
        onclick: function(menuItem, menu) {
            select(this);
            msgBox('Think about it!', 'Are you sure?', {Delete:function(){$('.selected').remove()}, Cancel:function(){}}, 'images/warning.png');
        },
        title: 'Delete item',
        icon: 'images/delete.png'
        }
    }
];

//================================================================================================================================
function addPanelHandlers($elm) {
    return $elm
        .draggable({snap: "#design-canvas, #design-canvas .panel"})
        .droppable({
            accept: ".layout-element",
            greedy: true,
            drop: function(evt, ui) {
                placeElement(this, ui);
            }
        })
        // Automatically extend size on doubleclick
        .dblclick(function(evt) {
            if (evt.ctrlKey) {
                var h = $(this).parent().height();
                $(this).css({height:h, top:0});
            } else {
                var w = $(this).parent().width();
                $(this).css({width:w, left:0});
            }
        });
}

//================================================================================================================================
function placeElement(target, ui) {
    if (!ui.draggable.hasClass('tool-item')) {
        ui.draggable.appendTo(target);
        return;
    }

    var $elm;
    var left = ui.helper.offset().left - $(target).offset().left;
    var top = ui.helper.offset().top - $(target).offset().top;
    if (ui.draggable.hasClass('panel')) {
        $elm = widgets.getWidget('panel');
        addPanelHandlers($elm);
    }
    else if (ui.draggable.hasClass('dynamic')) {
        var $elm = widgets.getWidgetElement(new widgets.dynamic()).draggable();
    }
    else if (ui.draggable.hasClass('video')) {
        $elm = getWrapperElement(target, ui);
        var $obj = new widgets.video();
        var $vid = widgets.getWidgetElement($obj);
//        widgets.items[$vid.attr('id')];
        $vid.appendTo($elm);
        $elm.resize(function() {
            var $this = $(this);
            $this.find('video').css({width:$this.width(), height:$this.height()});
        });
    }
    else if (ui.draggable.hasClass('image')) {
        $elm = getWrapperElement(target, ui);
        var $img = widgets.getWidget('image');
        $img.appendTo($elm);
        $elm.resizable().resize(function() {
            var $this = $(this);
            $this.find('img').css({width:$this.width(), height:$this.height()});
        });
    }
    else if (ui.draggable.hasClass('googleMap')) {
        var $elm = widgets.getWidgetElement(new widgets.googleMap());
        var id = $elm.attr('id');
        // create a special drag handle for map
        $("<div class='drag-handle' style='position:absolute; top:0; left:0; width:20px; height:20px; cursor:move; z-index:10000; background-color:yellow' id='handle-"+id+"'>").appendTo($elm);
        $elm
            .css({position:'absolute', top:top, left:left, border:'1px dotted #BBB'})
            .draggable({handle:'#handle-'+id})
            .resizable();
    }
    else if (ui.draggable.hasClass('rtfEditor')) {
        $elm = widgets.getWidget('rtf').draggable();
    }
    $elm
        .addClass('layout-element')
        .resizable()
        .bind("contextmenu", function(e){
            select(this);
        })
        .contextMenu(contextMenu, {theme:'vista'})
        .click(function(){toggleSelection(this); return false});
    $elm.mouseover(function() {
        $('<div class="tooltip">'+$(this).attr('id')+'</div>').appendTo(this);
    })
    .mouseout(function() {
        $('.tooltip').remove();
    });
    $elm.appendTo(target);
}

//================================================================================================================================
function getWrapperElement(target, ui)
{
    var left = ui.helper.offset().left - $(target).offset().left;
    var top = ui.helper.offset().top - $(target).offset().top;
    return $('<div>')
        .addClass('layout-element wrapper')
        .css({position:'absolute', top:top, left:left})
        .draggable(ui.helper.hasClass('panel') ? {snap: "#design-canvas, #design-canvas .layout-element"} : {});
}

//================================================================================================================================
function showProperties(elm) {
    var self = $(elm).hasClass('wrapper') ? $(elm).find('*:first-child') : $(elm);
    var type = self.attr('type');
    var props = (new widgets[type]()).properties;
    var $tbl = $('#property-box > table').html('');
    $('#active-block-name').text(self.attr('id'));
    var pro, val;
    for (var id in props) {
        pro = props[id];
        if (!pro.get)
            continue;
        val = pro.get($(elm));
        $tbl.append($('<tr>').append($('<td class="pro-cap">').text(pro.caption)).append($('<td>').html('<input type="' + pro.type + '" id="' + id + '" ' + (pro.type=='text' ? 'value' : 'checked') + '="' + val + '" />')));
}
    $('#property-box input').change(function() {
        var $this = $(this);
        var propId = $this.attr('id');
        var val =  $this.val();
        var objId = $('#active-block-name').text();
        widgets.update(objId, propId, val);
        if (propId == 'id') {
            $('#active-block-name').text(val);
        }
    });
    $('#property-box').css({display:'inherit'});
};

//================================================================================================================================
function toggleSelection(elm) {
    $(elm).hasClass('selected') ? select(elm, 1) : select(elm);
}

//================================================================================================================================
function select(elm, on) {
    var $elm = $(elm);
    if (on===undefined) {
        if (!$elm.hasClass('selected')) {
            $elm.addClass('selected');
            $('<img class="red-dot" src="images/red-dot.png" />').appendTo(elm);
        }
    } else {
        $elm.removeClass('selected');
        $elm.find(".red-dot").remove();
    }
}

//================================================================================================================================
function savePage()
{
    createPopup('save', "Save as:<br/><form><input type='text' id='page-name' name='page-name' value='"+$('#pageName').val()+"' /><br/><button type='button' onclick=\"savePageByName($('#page-name').val()); deletePopup()\">Ok</button><button type='button' onclick='deletePopup()'>Cancel</button></form>");
}

//================================================================================================================================
function savePageByName(pageName)
{
    var $pName = $('#pageName');
    if (!$pName.length) {
        $pName = $('<input type="hidden" id="pageName" />').appendTo('#design-canvas');
    }
    $pName.val(pageName);
    var $canvas = $('#design-canvas').clone();
    $canvas.find('.ui-resizable-handle, .drag-handle').remove();
    $canvas.find('.ui-draggable, .ui-resizable, .ui-droppable').removeClass('ui-draggable ui-resizable ui-droppable selected');
//    var $script = $canvas.find('#auto-script');
//   if (!$script.length)
//        $script = $('<script id="auto-script">').appendTo($canvas);
//    $script.text("var widgets = "+JSON.stringify(widgets));

    var php = '';
    $('*[data-src]').each(function() {
        php += '"'+$(this).attr('data-src')+'",';
    });
    $("#map-canvas").html('');
    $("#php-script-wrapper").remove();
    if (php!='')
        $('<span id="php-script-wrapper">').text("<?php \\Blocks\\Service\\Blocks::setDynamicData(array("+php+")) ?>").appendTo($canvas);
    $.post(document.location.pathname+'/sockets/savePage', {page:pageName, html:$canvas.html()});
}

//================================================================================================================================
function pullPage(id)
{
    $('#design-canvas').load('designer/sockets/loadPage', {id:id}, function(data, status) {
        if (status != 'success')
            return;

        deletePopup();

        var $this;
        $('#design-canvas .layout-element').each(function() {
            $this = $(this);
            if ($this.hasClass('panel')) {
                if (!$this.hasClass('locked'))
                    addPanelHandlers($this);
            }

            else if ($this.hasClass('googleMap')) {
                var id = $this.attr('id');
                new widgets.googleMap().insertMap($this);
                $("<div class='drag-handle' style='position:absolute; top:0; left:0; width:20px; height:20px; cursor:move; z-index:10000; background-color:yellow' id='handle-"+id+"'>").appendTo($this);
                if (!$this.hasClass('locked'))
                    $this.draggable({handle:'#handle-'+id});
            }

            if ($this.hasClass('locked'))
                $('<img class="lock" src="images/lock.png" />').appendTo($this);
            else
                $this.resizable();

            $this
                .click(function(){toggleSelection(this)})
                .bind("contextmenu", function(e){
                    select(this);
                })
                .contextMenu(contextMenu, {theme:'vista'})
                .mouseover(function() {
                    $('<div class="tooltip">'+$(this).attr('id')+'</div>').appendTo(this);
                })
                .mouseout(function() {
                    $('.tooltip').remove();
                });
        });
        populateWithData();
    });
}

//================================================================================================================================
function showInBrowser()
{
    window.open($('#curPageKey').attr('value'), '_blank');
}

//================================================================================================================================
function insertBlock(id)
{
    var $wrapper = $('<div class="layout-element dynamic">');
    $wrapper.load('designer/sockets/getBlock', {id:id}, function(data, status) {
        if (status != 'success')
            return;

        deletePopup();

        $(this).appendTo('#design-canvas')
            .draggable()
            .resizable();
    });
}

//================================================================================================================================
function showItemList(category)
{
    $.getJSON('designer/sockets/getItems', {category:category}, function(data) {
        var tbl = '<table>';
        for (var i in data)
            tbl += "<tr><td onclick='" + (category==1?"pullPage":"insertBlock")+"("+data[i].id+")'>"+data[i].name+'</td></tr>';
        createPopup('item-list', tbl+'</table>');
    });
}

//================================================================================================================================
function lockBlock(elm)
{
    var $elm = $(elm);
    if ($elm.hasClass('locked'))
        $elm.removeClass('locked').draggable().resizable().find('img.lock').remove();
    else
        $('<img class="lock" src="images/lock.png" />').appendTo($elm.draggable('destroy').resizable('destroy').addClass('locked'));
}

//================================================================================================================================
function buildWidgetList() {
    // build widjets list in the left-side tools panel
    var obj;
    for (var prop in widgets) {
        // it will throw an exception if 'name' is not a constructor
        if (prop=='update' || prop.substr(0,3)=='get')
            continue;
        try {obj = new widgets[prop]();}
        catch (ex) {continue;}
        $("<div class='tool-item layout-element "+prop+"'><img src='images/"+prop+".png'/><div>"+obj.cap+"</div></div><br/>")
            .insertAfter('#widgets-head')
            .draggable({helper: "clone", scroll: true});
    }
    $('.layout-element').draggable({
          helper: "clone",
          scroll: true
    });
}

//================================================================================================================================
$(document).ready(function() {
    setTimeout(buildWidgetList, 200);
    $('#property-box').draggable();
    $("#tool-panel").resizable();

    $('#design-canvas')
        .droppable({
            accept: ".layout-element",
            drop: function(evt, ui) {
                placeElement(this, ui);
            }
    });

    $('body').click(function() {
       $('.selected').removeClass('selected');
       $('.red-dot').remove();
    });
});