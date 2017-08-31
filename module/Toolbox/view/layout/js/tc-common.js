//==============================================================================================================
function populateElement(elm, dataRow)
{
    var $elm = $(elm);
    if ($elm.hasClass('template')) return;
    
    var fmap = $elm.attr('field-map');

    if (!fmap) return;

    fmap = JSON.parse(fmap);

    if (!fmap) return;

    if ($elm.hasClass('clone'))
        $elm.removeAttr('field-map').removeAttr('phoenix-data');

    if (!fmap)
        return;
    var fld, val, key;
    for (key in fmap) {
        fld = fmap[key];
        val = dataRow[fld];
//        if (val)
//            key == 'text' ? $elm.text(val) : $elm.attr(key, val);

        if (val)
        {
            switch(key){
                case "text":
                    if(fld == 'description')
                    {
                        var description = $(val).html();
                        if(description.length > 120)
                            description=description.substring(0,120)+"...";
                        else
                            description=description.substring(0,120);
                        
                        $elm.text(description);
                    }
                    else
                        $elm.text(val);
                    break;
                case "src":
                    //$elm.attr(key, 'http://loews.t1.devsite-1.com'+val);
                    $elm.attr(key, val);
                    break;
                case "href":
                    //$elm.attr(key, 'http://loews.t1.devsite-1.com/'+val);
                    $elm.attr(key, _SITEROOT+val);
                    
                default:
                    $elm.attr(key, val);
            }
        }
    }
}

//==============================================================================================================
// populate all data-dependent elements
function populateWithData(data)
{
    $('*[phoenix-data]').each(function() {
        var $this = $(this),
            srcName = $this.attr('phoenix-data'),
            dataset = data ? JSON.parse(JSON.stringify(data[srcName])) : null;

        if (!dataset)
            return;

        dataset.sort(function(a, b) {
            if (true) return (a['city'] > b['city']) ? 1 : ((a['city'] < b['city']) ? -1 : 0);
            else return (b['city'] > a['city']) ? 1 : ((b['city'] < a['city']) ? -1 : 0);
        });

        var item = {};
        var modified_dataset = [];
        for(var i = 0; i < dataset.length; i++)
        {
            item = {
                'name':dataset[i].name,
                'address':dataset[i].address,
                'city':dataset[i].city,
                'country':dataset[i].country,
                'description':dataset[i].description,
                'group':dataset[i].group,
                'id':dataset[i].id,
                'labelX':dataset[i].labelX,
                'labelY':dataset[i].labelY,
                'latitude':dataset[i].latitude,
                'longitude':dataset[i].longitude,
                'phoneNumber':dataset[i].phoneNumber,
                'tollfreeNumber':dataset[i].tollfreeNumber,
                'photo':dataset[i].photo,
                'state':dataset[i].state,
                'url':dataset[i].url,
                'zip':dataset[i].zip,
                'coordinates':dataset[i].latitude+','+dataset[i].longitude,
                'viewwebsite': _SITEROOT+dataset[i].url,
                'specialoffers': _SITEROOT+dataset[i].url+'/specials',
                'checkavailability': _SITEROOT+'reservations/dates.html?htld='+dataset[i].code
            };
            modified_dataset.push(item);
            //dataset[i].push({'coordinates':dataset[i].latitude+','+dataset[i].longitude});
        }
        // if the item is a template itself
        if ($this.hasClass('template')) {
            var dataRow, $new, $last = $this;
            $this.css({display:'none'});
            for (var i in modified_dataset) {
                dataRow = modified_dataset[i];
                // create a new element from template and clean it up
                $new = $this
                    .clone()
                    .addClass('clone')
                    .css({position:'relative', top:'0px', left:'0px', display:'inherit'})
                    .attr('id', null)
                    .removeClass('widget panel layout-element template')
                    .removeAttr('type');

                populateElement($new, dataRow);

                $new.insertAfter($last);
                $last = $new;
            }
        } else {
            // assign a value to itself from the first dataset record
            populateElement(this, dataset[0] || modified_dataset);
        }

        // ...then look for a template and populate data using it (if exists)
        $this.find('.template').each(function() {
            var dataRow, $new, $last, $this = $last = $(this);
            $this.css({display:'none'});
            for (var i in modified_dataset) {
                dataRow = modified_dataset[i];
                console.log(dataRow);
                // create a new element from template and clean it up
                $new = $this
                    .clone()
                    .addClass('clone')
                    .css({position:'relative', top:'0px', left:'0px', display:'inherit'})
                    .attr('id', null)
                    .removeClass('widget panel layout-element template')
                    .removeAttr('type');

                if ($new.attr('field-map')) {
                    alert("Here");
                    populateElement($new, dataRow);
                }

                $new.find('*[field-map]').each(function() {
                    populateElement(this, dataRow);
                });

                $new.insertAfter($last);
                $last = $new;
            }
           // removed the original html template
           $(this).remove();
        });
/*
        // ...then fill all stand-alone elements from the first dataset record
        $this.find('*[field-map]').each(function() {
            if (!$(this).hasClass('template'))
                populateElement(this, dataset[0] || dataset);
        });
*/
    });
}

//=========================================================================================================================================
// Check for the images if they are loaded. If not replace image sources with standard 'no-image' image from the system repository
function fixImages() {
     $('img').each(function() {
        if (!this.complete) {
            //$(this).attr({src:toolboxIncludeUrl+'module/Toolbox/view/layout/img/no-image.jpg'});
        }
    });
}

function initializeTcApp() {
    fixImages();

    // initialize all widjets requiring initialization
    $('.layout-element').each(function() {
        var $this = $(this);        
        if ($this.hasClass('googleMap')) {
            var mapParams = tcWidgetsParameters[$this.attr('id')];
            if (!mapParams.mapOptions)
                return;
            (new widgets.googleMap()).setParameters(mapParams).insertMap($this);
        }
    });

    // hide all data template elements
//    $('*').removeClass("widget panel layout-element");

    // remove all init-script tags
//    $('.init-script').remove();
}


$(document).ready( function() {
    initializeTcApp();
});

