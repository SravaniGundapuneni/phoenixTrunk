// Widget class VIDEO
tcWidgets.prototype.video = function() {
    this.cap = 'Video';
    this.getClassName = function() {return 'video'};

    // this is a list of properties to be exposed in the property box
    this.properties = {
        id:       {caption:'ID', type:'text', selector:"attr('id'{})"},
        class:    {caption:'Class', type:'text', selector:"attr('class'{})"},
        width:    {caption:'Width', type:'text', selector:"css('width'{})"},
        height:   {caption:'Height', type:'text', selector:"css('height'{})"},
        cntr:     {caption:'Show Controls', type:'checkbox', selector:"attr('controls'{})"},
        src:      {caption:'Source', type:'text', selector:"find('source').attr('src'{})"},
        autoplay: {caption:'Autoplay', type:'checkbox', selector:''}
    };

    // this method returns default 'video' tag
    this.getElement = function() {
        return $("<video style='width:430px; height:240px' controls='0'><source src='http://localhost/condor/branches/feature-phoenixtng-dev/module/designer/view/designer/toolbox/img/test.mp4' type='video/mp4'></video>");
    };
}
