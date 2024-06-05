$(document).ready(function() {    
	$(function() { // document ready
		   $('#calendar').fullCalendar({
		     header: {
		       left: 'prev,next today',
		       center: 'title',
		       right: 'month,agendaWeek,agendaDay'
		     },
		     eventColor: ' #FFF',
		     borderColor:'#d4c3c3',
		     defaultDate: default_date,
		     defaultView: $view,
		     firstDay:0,
		     editable: false,
		     eventLimit: false, // allow "more" link when too many events
		     events: function (start, end, timezone, callback) {
		     	
		 		var calendar = $('#calendar').fullCalendar('getCalendar');
		 		var view = calendar.view;
		 		var start = view.start._d; 
		 		let date = JSON.stringify(start);
		 		sdate = date.slice(1,11); console.log(sdate);
		 		var end = view.end._d;  
		 		let date1 = JSON.stringify(end);
		 		edate = date1.slice(1,11); console.log(edate);
		 		  
		 		var unit=$('#unit').val();
		         $.ajax({
		         	url: baseURL+"manager/rota/getRota",
		             type: "POST",
		             async: false,
		             data: {
		                 start_date:sdate,end_date:edate
		             }
		           }).done(function(response) {console.log(response);
		        	   var events = [];
		                if(!!response){
		                    $.map( response, function( r ) {
		                        events.push({
		                            id: r.id,
		                            title: r.title,
		                            start: r.start,
		                            end: r.end,
		                            title_color:r.title_color,
		                            text_back_color:r.text_back_color,
		                            unit_id: r.unit_id,
		                            unit_name: r.unit_name,
		                            unit_color: r.unit_color,
		                            stime: r.stime,
		                            etime: r.etime,
		                            time: r.time
		                        });
		                    });
		                }
		                //console.log(events);
		                callback(events);
		               // callback(response); //return  data to the calendar via the provided callback function
		           });
		         },
		     eventRender: function(event, element) {
		    	  element.find(".fc-title").remove();
		    	  element.find(".fc-time").remove();
                  element.append('<div class="event_body" style="background-color:'+event.text_back_color+'">'
                		 +'<table><tr><td class="tds">'
                		 +'<h5 style="color:'+event.title_color+'">'+event.title+'</h5></td><td class="tds"><p style="color:'+event.unit_color+'">'+event.unit_name
                		 +'</p></td></tr><tr><td colspan="2" style="color:#000;">'
                		 +event.time+'</td></tr></table></div>');

     		 
		     } 
		   }); 
		   $('#calendar').fullCalendar( 'refetchEvents' );
		/*   var view = calendar.view;
	   		console.log(view);
	   	 var start = view.start._d; 
	   	 console.log(start);*/
		});
 
	function GetCalendarDateRange() {
		   var calendar = $('#calendar').fullCalendar('getCalendar');
   		var view = calendar.view;
   		console.log(view);
   	    var start = view.start._d; 
   	    let date = JSON.stringify(start);
   	    sdate = date.slice(1,11); 
   	    var end = view.end._d;  
   	    let date1 = JSON.stringify(end);
   	    edate = date1.slice(1,11); 
        var dates = { start: sdate, end: edate };
        return dates;
    } 
	var date_start = $('#calendar').fullCalendar('getView');
	  console.log(date_start);
	  function getMonth(){
		  var date = $("#calendar").fullCalendar('getDate');
		  var month_int = date.getMonth();
		  console.log(month_int);
		  //you now have the visible month as an integer from 0-11
		}
	  var calendar = $('#calendar').fullCalendar('getCalendar');
	/*	var view1 = calendar.view;
	    var start1 = view1.start; 
	    console.log(start1);*/
	// getMonth();
	 var view = $('#calendar').fullCalendar('getView');
	 //alert("The view's title is " + view.title);
	 var calendar = $('#calendar').fullCalendar('getCalendar');

	 calendar.next();
	  //var month_int = date.getDate();
	  //alert(month_int);
	// console.log($('#calendar').fullCalendar('getView').start);
// pevious button click
    /*$(document).on('click', '.fc-prev-button', function(e) { 
    	$("#start").val("");
    	$("#end").val("");
        var calendar = $('#calendar').fullCalendar('getCalendar');
    	var view = calendar.view;
    	console.log(view);
        var start = view.start._d; 
        let date = JSON.stringify(start);
        sdate = date.slice(1,11); console.log(sdate);
        var end = view.end._d;  
        let date1 = JSON.stringify(end);
        edate = date1.slice(1,11); console.log(edate);
        $("#start").val(sdate);
        $("#end").val(edate);
        console.log("prev");

        var unit=$('#unit').val();
        $.ajax({
            url: baseURL+"admin/rota/getweekData",
            type: "POST",
            async: false,
            data: {
                unit_id: unit,start_date:sdate,end_date:edate
            },
             success: function (data) { 

		      $('#calendar').fullCalendar('removeEvents') //Hide all events
		      $('#calendar').fullCalendar('removeEventSource', weekEvents);
		      $('#calendar').fullCalendar('removeEventSource', data.events);
		      $('#calendar').fullCalendar('addEventSource', data.events);
 		    
		    	  
           }
              
      });
 
    });
// next button click
    $(document).on('click', '.fc-next-button ', function(e) { 
    	
    	$("#start").val("");
    	$("#end").val("");
        var calendar = $('#calendar').fullCalendar('getCalendar');
    	var view = calendar.view;
    	//console.log(view);
        var start = view.start._d; 
        let date = JSON.stringify(start);
        sdate = date.slice(1,11);  
        var end = view.end._d;  
        let date1 = JSON.stringify(end);
        edate = date1.slice(1,11); 
        $("#start").val(sdate);
        $("#end").val(edate);
    console.log("next");
        var unit=$('#unit').val();
        $.ajax({
            url: baseURL+"admin/rota/getweekData",
            type: "POST",
            async: false,
            data: {
                unit_id: unit,start_date:sdate,end_date:edate
            },
             success: function (data) { 

			      $('#calendar').fullCalendar('removeEvents') //Hide all events
			      $('#calendar').fullCalendar('removeEventSource', weekEvents);
			      $('#calendar').fullCalendar('removeEventSource', data.events);
			      $('#calendar').fullCalendar('addEventSource', data.events);
			   
			    	  
		      }
              
          });
     });*/
 
});
