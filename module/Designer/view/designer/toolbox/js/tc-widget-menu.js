// Widget class MENU
tcWidgets.prototype.menu = function() {
    this.cap = 'Menu';
    this.getClassName = function() {return 'menu'};

    // this is a list of properties to be exposed in the property box
    this.properties = {
        id:      {caption:'ID', type:'text', selector:"attr('id'{})"},
        class:   {caption:'Class', type:'text', selector:"attr('class'{})"},
        width:   {caption:'Width', type:'text', selector:"css('width'{})"},
        height:  {caption:'Height', type:'text', selector:"css('height'{})"},
    };

    // this method returns default template of 'video' tag
    this.getElement = function() {
        return $("<div>");
    };
}
