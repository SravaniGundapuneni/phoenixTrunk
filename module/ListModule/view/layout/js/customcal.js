
$(document).ready(function() {
    var date = new Date(),
        d = date.getDate(),
        m = date.getMonth(),
        y = date.getFullYear(),
        h = {},
        SOCKET_PATH = 'toolbox/tools/calendar/';
 
    $("#calendar1").fullCalendar({
        header: h,
        selectable: true,
        select: function( startDate, endDate, allDay, jsEvent, view ) {
            var newstart = $.fullCalendar.formatDate(startDate, "MMMM dd yyyy h:mm tt"),
                newend   = $.fullCalendar.formatDate(endDate, "MMMM dd yyyy h:mm tt"),
                sdate = $.fullCalendar.formatDate(startDate, "yyyy-MM-dd"),
                edate = $.fullCalendar.formatDate(endDate, "yyyy-MM-dd");
           
            $(document).find(".fc-day").each(function() {
                var maxNum,
                    randomNum,
                    newDiv;


                if(($(this).attr("data-date") >= sdate) && ($(this).attr("data-date") <= edate)) {
                    //$(this).append("<div class='test' id='droppable'><p>drop here</p></div>");
                   // $(this).find(".test").css({'background':'#2ba6cb','height':'80px'});
                   //dnt remove above lines
                    maxNum    = 100;
                    randomNum = (Math.floor(Math.random() * (maxNum + 1)));
                    // TODO: use a CSS class here instead of inline styles
                    newDiv    = $("<div/>")
                        .attr({'class':'test ui-droppable','id':'droppable_'+randomNum+''})
                        .css({'background':'#2ba6cb','height':'80px','font-size':'14px','color':'white'});
                  $(this).append(newDiv);
                  eventDrag();
                }
            });
        },
        editable: true,
        draggable: true,
        droppable: false
    });
    //function to drag event to dates
    function eventDrag(){
        $( "div.eventDiv>p:not(.myDiv)" ).draggable({
            helper: 'clone', 
            greedy: true, 
            opacity: 0.70,
            zIndex:100000, 
            start: function (e, ui) {
                console.log(e);
                console.log(ui);
                ui.helper.animate({
                    width: 60,
                    height: 20,
                    zIndex:10
                });
            }
        });

        $( ".test" ).droppable({   
            drop: function( event, ui ) {
                var selectedEventDate,
                    FormattedEventDate,
                    eventDivID,
                    cnt,
                    eventID,
                    eventName;


                $(this).append(ui.draggable[0].innerHTML);
                selectedEventDate = $(this).parent().data('date');
                FormattedEventDate = (new Date(selectedEventDate).getDate()+1)+"-"+(new Date(selectedEventDate).getMonth()+1)+"-"+new Date(selectedEventDate).getFullYear();
                eventDivID = $(this).attr('id');
                cnt = $("#"+eventDivID).find(':hidden#item').length;
                
                if (cnt === 1 ){
                    eventID=$("#"+eventDivID).find(':hidden#item').val();
                    eventName=$.trim($("#Event_"+eventID).text());
                } else {
                    eventID = $("#"+eventDivID).find(".eventItem:eq("+(cnt-1)+")" ).attr("value");
                    eventName=$.trim($("#Event_"+eventID).text());
                }
                saveEventDate(FormattedEventDate,eventName,eventID);
            }
        });
}
function saveEventDate(selectedEventDate,ename,eid) {
    var assignEvent1 = $.trim("addEvent_"+ename),
        assignEvent  = assignEvent1.replace(/\s+/g, '_'),
        eventDate    = $('<p>').attr({'id':'myDiv_'+eid+'','class':'myDiv row data-equalizer'}).text(selectedEventDate),
        e1           = $(this).attr('id');

    $('#'+assignEvent).append(eventDate);
    saveRecord(eid,selectedEventDate);
}

function saveRecord(eid,sdate){
    var socketAction = window.location.origin + _site_root + SOCKET_PATH + 'saveEvents'; 
    $.ajax({
        type:"post",
        url:socketAction,
        data:{
           eventid:eid,
           eventdate:sdate
        },
        success:function(data, textStatus, jqXHR){
        }
         
    });
}   
    
}); 
