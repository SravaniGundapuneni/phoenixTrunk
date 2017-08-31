var widgets;
var cMenu = [
	{'Properties': {
		onclick: function(menuItem, menu) { showProperties(this); },
		title: 'This is the hover title'
		}
	},
	{'Delete': {
		onclick: function(menuItem, menu) { if (confirm('Are you sure?')) {$(this).remove();} },
		icon: 'delete.png'
	  }
	}
  ];
  
function showProperties(elm) {
	select(elm);
	var type = $(elm).attr('type');
	var props = (new widgets[type]()).properties;
	var $tbl = $('#property-box > table').html('');
	$('#active-block-name').text('"'+elm.id+'"');
	var pro, expr, val;
	for (var id in props) {
		pro = props[id];
		if (pro.selector === '')
			continue;
		expr = "$(elm)." + pro.selector.replace('{}', '') + ';';
		val = eval(expr);
		$tbl.append($('<tr>').append($('<td>').text(pro.caption)).append($('<td>').html('<input type="' + pro.type + '" id="' + id + '" ' + (pro.type=='text' ? 'value' : 'checked') + '="' + val + '" />')));
	}
	$('#property-box input').change(function() {
		var $this = $(this);
		var propId = $this.attr('id');
		var val =  $this.val();
		var objId = $('.selected').attr('id');
		widgets.update(objId, propId, val);
	});
	$('#property-box').css({display:'inherit'});
   };

function select(elm) {
	$('#active-block-name').text();
	$('.selected').removeClass('selected');
	$(elm).addClass('selected');
}

$(document).ready(function() {
	widgets = new tcWidgets();
	var type = 'video';
	var obj = (new widgets[type]()).getElement().insertBefore('#property-box');
	$('#'+ obj.attr('id')).contextMenu(cMenu, {theme:'vista'}).draggable().resizable().click(function(){select(this);});
	$('#property-box').draggable();
});