// Widget class IMAGE
tcWidgets.prototype.image = function() {
    this.cap = 'Image';
    this.getClassName = function() {return 'image'};

    // this is a list of properties to be exposed in the property box
    this.properties = {
        id:      {caption:'ID', type:'text', get:function($obj){return $obj.find('img').attr('id')}, set:function($obj, val){$obj.attr({title:val}).find('img').attr({id:val})}},
        class:   {caption:'Class', type:'text', get:function($obj){return $obj.find('img').attr('class')}, set:function($obj, val){$obj.find('img').attr('class', val)}},
        width:   {caption:'Width', type:'text', get:function($obj){return $obj.css('width')}, set:function($obj, val){$obj.css('width', val)}},
        height:  {caption:'Height', type:'text', get:function($obj){return $obj.css('height')}, set:function($obj, val){$obj.css('height', val)}},
        src:     {caption:'Source', type:'text', get:function($obj){return $obj.find('img').css('src')}, set:function($obj, val){$obj.find('img').css('src', val)}},
        opacity: {caption:'Opacity', type:'text', get:function($obj){return $obj.find('img').css('opacity')}, set:function($obj, val){$obj.find('img').css('opacity', val)}}
    };

    // this method returns default 'image' tag
    this.getElement = function() {
        return $("<img style='width:250px; height:auto' src='"+designerUrl+"img/flower.jpg'>");
    };
}
