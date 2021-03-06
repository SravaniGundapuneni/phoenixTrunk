// Widget class IMAGE
tcWidgets.prototype.dynamic = function() {
    this.cap = 'Dynamic Data'
    this.getClassName = function() {return 'dynamic'};

    // this is a list of properties to be exposed in the property box
    this.properties = {
        id:      {caption:'ID', type:'text', get:function($obj){return $obj.attr('id')}, set:function($obj, val){$obj.attr({id:val})}},
        class:   {caption:'Class', type:'text', get:function($obj){return $obj.attr('class')}, set:function($obj, val){$obj.attr('class', val)}},
       'data-src': {caption:'Data Source', type:'text', get:function($obj){return $obj.attr('data-src')}, set:function($obj, val){$obj.attr('data-src', val)}},
        width:   {caption:'Width', type:'text', get:function($obj){return $obj.css('width')}, set:function($obj, val){$obj.css('width', val)}},
        height:  {caption:'Height', type:'text', get:function($obj){return $obj.css('height')}, set:function($obj, val){$obj.css('height', val)}},
        border:  {caption:'Border', type:'text', get:function($obj){return $obj.css('border')}, set:function($obj, val){$obj.css('border', val)}},
       'background-color': {caption:'Background color', type:'text', get:function($obj){return $obj.css('background-color')}, set:function($obj, val){$obj.css('background-color', val)}},
       'background-image': {caption:'Background image', type:'text', get:function($obj){var val=$obj.css('background-image'); val = val.replace("url('", '').replace("')", ''); return val}, set:function($obj, val){$obj.css('background-image', "url('"+val+"')")}},
        opacity: {caption:'Opacity', type:'text', get:function($obj){return $obj.css('opacity')}, set:function($obj, val){$obj.css('opacity', val)}}
    };

    // this method returns default 'dynamic' tag
    this.getElement = function() {
        return $("<div style='width:250px; height:250px'>");
    };
}
