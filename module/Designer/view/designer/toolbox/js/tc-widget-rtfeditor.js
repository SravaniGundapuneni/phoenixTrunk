// Widget class RTFeditor
tcWidgets.prototype.rtfEditor = function() {
    this.cap = 'Text Block',
    this.getClassName = function() {return 'rtfEditor'};

    // this is a list of properties to be exposed in the property box
    this.properties = {
        id:      {caption:'ID', type:'text', selector:"find('img').attr('id'{})"},
        class:   {caption:'Class', type:'text', selector:"attr('class'{})"},
        width:   {caption:'Width', type:'text', selector:"css('width'{})"},
        height:  {caption:'Height', type:'text', selector:"css('height'{})"}
    };

    // this method returns default template of 'video' tag
    this.getElement = function() {
        return $("<div style='width:250px;height:200px' class='widget rtf' type='rtf' id='rtf-" + getAvailableId('rtfEditor') +"'>").click(function() {
            tinymce.init({selector:'#rtf-'+ids.rtf});
        });
    };
}
