// Widget class IMAGE
tcWidgets.prototype.adRotator = function() {
    this.cap = 'Ad Rotator';

    // this is a list of properties to be exposed in the property box
    this.properties = {
        id:      {caption:'ID', type:'text', get:function($obj){$obj.find('img').attr('id')}, set:function($obj, val){$obj.find('img').attr('id', val)}},
        class:   {caption:'ID', type:'text', selector:"find('img').attr('id'{})"},
        width:   {caption:'Width', type:'text', selector:"css('width'{})"},
        height:  {caption:'Height', type:'text', selector:"css('height'{})"},
        src:     {caption:'Source', type:'text', selector:"find('img').attr('src'{})"},
        opacity: {caption:'Opacity', type:'text', selector:"find('img').css('opacity'{})"}
    };

    // this method returns default 'image' tag
    this.getElement = function() {
        return $("<img style='width:250px; height:auto' src='"+designerUrl+"img/ad-rotator.png'>");
    };
}
