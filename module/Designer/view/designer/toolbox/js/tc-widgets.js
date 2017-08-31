function tcWidgets()
{
    this.items = {};

    this.getWidget = function(type)
    {
        return (new this[type]()).getElement().attr({id:getAvailableId(type), 'type':type}).addClass('widget '+type);
    };

    this.getWidgetElement = function($obj)
    {
        var type = $obj.getClassName();
        return $obj.getElement().attr({id:getAvailableId(type), 'type':type}).addClass('widget '+type);
    };

    this.update = function(objId, propId, val)
    {
        var $obj = $('#'+objId);
        (new widgets[$obj.attr('type')]()).properties[propId].set($obj, val);
    };

    function getAvailableId(type)
    {
        for (var i=1; document.getElementById(type+i); ++i); return type+i;
    }
};

widgets = new tcWidgets();