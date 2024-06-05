$(document).ready(function() {
  $("#from-datepicker").datepicker({ 
    dateFormat: 'dd/mm/yy'
  });
  $("#from-datepicker").on("change", function () {
    var fromdate = $(this).val();
    var date_array = $(this).val().split('/');
    var date = date_array[2]+'-'+date_array[1]+'-'+date_array[0];
    $("#calendar").fullCalendar( 'gotoDate', date );
    onLoad();
  }); 
  var shift_array = [];
  var user_shift_array = [];
  var request_array = [];
  var avlrequest_array = [];
  var user_request_shift = [];
  var user_avl_shift = [];
  var published_rotas = [];
    // $("#rota_id").val(unitID);
    var date_last_clicked = null;  
    $('#calendar').fullCalendar({
      // schedulerLicenseKey: 'GPL-My-Project-Is-Open-Source',
    height: "auto",
    resourceAreaWidth: 0,  
    editable: false,
    eventLimit: true,
    aspectRatio: 3,
    scrollTime: '00:00',
    header: {
      left: 'promptResource today prev,next',
      center: 'title',
      right: ' ',   
    },
    titleFormat: '[Schedule for week] - ddd D/MM/YYYY [: '+unit+']', 
    eventRender: function( event, element, view ) {
      element.attr( 'id', event.id );       
      var eventStart = moment(event.start);
      var titleSelect =event.title;
      var resourceID = event.resourceId;
      var user_id = event.resourceId;
      var from_unit =event.from_unit; 
      var replaceString = 'shift_'+resourceID;
      var weekDay = eventStart.weekday();
      var replaceWith = 'shift_'+resourceID+'_'+weekDay;
      var et = event.title.replace(replaceString, replaceWith);
      var cdate = eventStart.format('YYYY-MM-DD');
      element.find('#'+replaceString).remove();
      element.append(et);
      if(eventStart.weekday()==event.offDay){
        var offSelect=element.find('#'+replaceWith);
        var avoid = 'selected="selected"';
        offSelect = et.replace(avoid,'');
        var remv = 'offselect="1"';
        offSelect =  et.replace(remv, 'selected="selected"');
        element.find('#'+replaceWith).remove();
        element.append(offSelect);
      }     
    },eventAfterRender: function( event, element, view ) {
      var eventStart = moment(event.start);
      var weekDay = eventStart.weekday();
      var days = ["1","2","3","4","5","6","7"];
      var weekdaynames = ["Su","Mo","Tu","We","Th","Fr","Sa"];
      var weekdayfullnames = ["sunday","monday","tuesday","wednesday","thursday","friday","saturdy"];
      var resourceID = event.resourceId;
      var replaceWith = 'shift_'+resourceID+'_'+weekDay;
      var dates = findDatesBtCalendarDates();
      element.find('#'+replaceWith).addClass("shift_"+resourceID+"_"+dates[weekDay]);
      element.find('#'+replaceWith).addClass("select_off_"+weekdaynames[weekDay]);
      element.find('#'+replaceWith).addClass("select_offday_"+weekdayfullnames[weekDay]);
      // element.find('#'+replaceWith).addClass("select_color_"+days[weekDay]);
      // element.find('#'+replaceWith).attr("data-count",days[weekDay]);
      element.find('#'+replaceWith).attr("data-date",dates[weekDay]);
      var shift_time = element.find('option:selected').attr('data-shifttime');
      var shift_shortname = element.find('option:selected').text();
      var shift_id = element.find('#'+replaceWith).val();          
    },
    eventAfterAllRender:function(){ //console.log('time delay');

    $(".fc-resource-area").hide();
    },
    events: weekEvents,
    defaultView: 'customWeek',
    views: {
      customWeek: {
        type: 'timeline',
        duration: { weeks: 1 },
        slotDuration: {days: 1},
        buttonText: 'Custom Week'
      }
    },
    resourceColumns: [
      {
        labelText: 'Employees',
        field: 'title'
      },
      {
        labelText: 'Total Hours',
        field: 'totalhours'
      }
    ],
    resources:rotabc,
    resourceRender: function (resourceObj, labelTds) {
      var labelName = labelTds[0];
      $(labelName).css("background-color","");
      $(labelName).css("background-color","#E0E0E0");
      $(labelName).css("color", resourceObj.unit_color);
      $(labelName).css("font-size", "16px");
      $(labelName).css("font-weight", "regular");
      var label = labelTds[1];
      var str = $(label).find('.fc-cell-text').text();
      if (resourceObj.totalhours!='')
      {
        $(label).find('.fc-cell-text').addClass("totalhours");
        $(label).find('.fc-cell-text').attr("data-hours",resourceObj.user_weeklyhours);
        $(label).find('.totalhours').append('&nbsp;&nbsp;<strong style="font-size:14px;" id=\"totalhours_'+resourceObj.id+'\">0</strong>');
      }      
    }
  });
  function findDatesBtCalendarDates(){
    var calendar = $('#calendar').fullCalendar('getCalendar');
    var view = calendar.view;
    var start = view.start._d; 
    let date = JSON.stringify(start);
    sdate = date.slice(1,11);
    var end = view.end._d;  
    let date1 = JSON.stringify(end);
    edate = date1.slice(1,11);

    var date_obj = new Date(sdate); // today!
    date_obj.setDate(date_obj.getDate() - 1);        
    let s_date = JSON.stringify(date_obj);
    var start_date = s_date.slice(1,11);
    var dates = [];

    var currDate = moment(start_date).startOf('day');
    var lastDate = moment(edate).startOf('day');
    while(currDate.add(1, 'days').diff(lastDate) < 0) {
      var cur_date = currDate.clone().toDate();
      date = moment(cur_date).format('YYYY-MM-DD');
      dates.push(date);
    }
    return dates;
  }
  function findTimeDifference(start_time,end_time){
    var hours = parseInt(end_time.split(':')[0], 10) - parseInt(start_time.split(':')[0], 10);
    if(hours < 0) hours = 24 + hours;
    return hours;
  }
    function checkZeroHours(starttime,endtime){
    if(starttime.split(':')[0] == "00" || endtime.split(':')[0] == "00"){
      return true;
    }else{
      return false;
    }
  }
  function checkTimeDifference(start_time,end_time){
    // Get your start and end date/times
    var start = moment().format(start_time);
    var end= moment().format(end_time);
    // pass in hours as the second parameter to the diff function
    //const differenceInHours = moment(end).diff(start, 'hours');
    var  differenceInMin = moment(end).diff(start, 'minutes');
    var hours = Math.floor(differenceInMin / 60);   
    //console.log(`${differenceInHours} hours difference`);
    // console.log(`${differenceInMin} mins difference`);
    // 11 hours is 660 mins
    return hours;
  }
  function showTrainingDays(){
      var all_holiday_dates=[];
      if(trainingDates && trainingDates.length >0){
        $.each(trainingDates, function( index, value ) {
          var dates = findDatesBetnDates(value.date_from,value.date_to);
          for(var i=0;i<dates.length;i++) {
            $('.shift_'+value.user_id+'_'+dates[i]).val(2);
          }            
        });
      }
      
      //show holidays
      if(holidayDates && holidayDates.length >0){ 
          $.each(holidayDates, function( index, value ) {
            var dates = findDatesBetnDates(value.from_date,value.to_date);
            for(var i=0;i<dates.length;i++) { 
              $('.shift_'+value.user_id+'_'+dates[i]).val(1).val(1);

            }            
          });
        }
    }
    function addDate(date){
    var date = new Date(date),
    days = 1;
    date.setDate(date.getDate() + days);
    let next_date = JSON.stringify(date)
    next_date = next_date.slice(1,11);
    return(next_date);
  }
 

  function getOffday(offday){
      if(!offday){
        offday = 0;
      }
      var weekdaynames = ["Su","Mo","Tu","We","Th","Fr","Sa"];
      return weekdaynames[offday];
    }
     function convertUserWeeklyHours(user_hours){
  var regex = /^\d{2}.\d{2}$/;
  var totalH = 0;
  var totalM = 0;
  if(regex.test(user_hours) == true){
    var user_hours_array = user_hours.toString().split('.');
    totalH += parseInt(user_hours_array[0]);
    totalM += parseInt(user_hours_array[1]);
    if (totalM >= 60) 
    {
      // Divide minutes by 60 and add result to hours
      totalH += Math.floor(totalM / 60);
      // Add remainder of totalM / 60 to minutes
      totalM = totalM % 60;
    }
    new_user_hours = (totalH * 60 + totalM)/60;
    return new_user_hours;
    
  }else{
    return user_hours;
  }
}
    function initResourceHours(sobj){
      // var offday = getOffday(user_offday);
      user_shift_array = [];
      shift_array = [];
      request_array = [];
      avlrequest_array = [];
      user_request_shift = [];
      user_avl_shift = [];
    var user_weeklyhours = convertUserWeeklyHours(parseFloat($('.totalhours').attr('data-hours').replace(':', '.')).toFixed(2)); 

    var arrays = getRequestArray();
    var month_diff = changeButtonStatus();
    request_array = arrays.request_array;
    avlrequest_array = arrays.avl_array;
    var month_diff = changeButtonStatus();
    if(month_diff >= 2 ){
      for(var i=0;i<request_array.length;i++){
        $('.shift_'+user_id+"_"+request_array[i]).attr('disabled', false);
      }
    }else{
      for(var i=0;i<request_array.length;i++){
        $('.shift_'+user_id+"_"+request_array[i]).attr('disabled', true);
      }
    }
    $(".eventcls").each(function() {
      var html = '';
      var shift_name = $(this).children("option:selected").text();
      var shift_id =  $(this).children("option:selected").val();
      var shift_prefix =  shift_name.split('-')[0];
      var data_hours =  $(this).children("option:selected").attr('data-hours');
      var data_stime = $(this).children("option:selected").attr('data-stime');
      var data_etime = $(this).children("option:selected").attr('data-etime');
      var data_date = $(this).attr('data-date');
      // console.log(data_date);
      var rstatus;
      var month_diff = changeButtonStatus();
      //get user shift of this date
      $.ajax({
          url:baseURL+'staffs/rota/getDayRota',
          type:'post',
          async: false,
          dataType:'json',
          delay: 250,
          data: {
            user_id : user_id, 
            date: data_date,
            month_diff : month_diff
          },
          success: function (data) {
            if(data.dayrotastatus==1){
                // console.log(data.shiftdata[0]['shift_id']);
   
                   
                      rshift_id = data.shiftdata[0]['shift_id'];
               
            }
            else{
            	rshift_id=0;
            }
            
      // console.log(" RSTATUS");
           rstatus=data.dayrotastatus;
           }
        });
      var month_diff = changeButtonStatus();
      // console.log("shift_id="+shift_id);
      var data_breakhours =  $(this).children("option:selected").attr('data-breakhours');
      //Making Training dropdown disabled
      if(shift_id == 2){
        $(this).attr('disabled', true);
        // console.log("disable1");
      }
      // console.log("month_diff="+month_diff);
      if(month_diff < 2 && shift_id < 100){
    	    $(this).attr('disabled', true);  
    	    // console.log("disable2");
      }
      // console.log(data_date+"777777777777="+request_array.indexOf(data_date));
      // console.log(request_array);
      if(request_array.indexOf(data_date) != -1 && shift_id >1){
    	  
    	  // console.log(shift_id+" 222  "+data_date);
    	if(shift_id>1){
    		
		       // if(shift_id == user_default_shift[0].id){
    		   if(shift_id){
		          $('.shift_'+user_id+"_"+data_date).find('option[value="'+shift_id+'"]').attr("selected","selected");
		        }
		        
		        if(month_diff < 2 ){
			         $(this).attr('disabled', true);  
			         // console.log("disable3");
		        }
		        else{
		        	$(this).attr('disabled', false);  
		        	   // console.log("enable0");
		        }
		        	
        }
    	else{
 
             html+= getHtml();
             html+='<option data-stime="00:00:00" data-etime="00:00:00" data-hours="0" value="1" data-breakhours="0">H</option>';
             

         }
 
      }else if(shift_id == 1 || shift_id == 0 || shift_id >= 1000){
        // console.log(shift_id+" 111  "+data_date);
    	if(shift_id == 1){
        
          html+= getHtml();
          $(this).attr('disabled', false); 
          // console.log("enable1");
          html+='<option data-stime="00:00:00" data-etime="00:00:00" data-hours="0" selected="selected" value="1" data-breakhours="0">H</option>';
        }
        else if(shift_id >= 1000){
          html+= getHtml();
          var class_array = $(this).attr('class').split(' ')[3];
          if(jQuery.inArray(class_array.split('_')[2], user_offday) !== -1){
            html+='<option data-stime="00:00:00" data-etime="00:00:00" data-hours="0" value="1" data-breakhours="0">X</option>';
          }else{
        	  $(this).attr('disabled', false); 
        	    // console.log("enable2");
            html+='<option data-stime="00:00:00" data-etime="00:00:00" data-hours="0" value="0" data-breakhours="0">H</option>';
          }
        }
        else if(shift_id == 0){
        	
         if(rstatus==0){ //no shift
        	 if(month_diff < 2 ){
        		 html+= getHtml();
                 html+='<option data-stime="00:00:00" data-etime="00:00:00" data-hours="0" value="0" data-breakhours="0">X</option>';

        		 $(this).attr('disabled', false); 
        		    // console.log("enable3");
        		    
        	 }else{
        		 rhtml =getAllShiftsHtml(data_date,user_id,sobj); 
                 //if(rhtml==0){
               //	 html+= getHtml();
               	  $(this).attr('disabled', false); 
                  // console.log("enable4");
                    // html+='<option data-stime="00:00:00" data-etime="00:00:00" data-hours="0" value=""  data-breakhours="0">X</option>';
              
                // }else{
               	//  html+=rhtml;
                // }
                   html+=rhtml; 
                     //html+=html;

           // console.log("getAllShiftsHtml"); 
        	 }
             
          }
          else{  //if there is rota for a day load shift dropdow and select that
        	  //html+= getHtml();
        	  //html ='<option data-stime="00:00:00" data-etime="00:00:00" data-hours="0" value="0"  data-breakhours="0">X9</option>';
        	  if(rshift_id==0 || rshift_id==1){
              if(month_diff >= 2){
                html+= getAllShiftsHtml2(data_date,user_id,rshift_id);
                $(this).attr('disabled', false);
              }else{
                html+= getHtml();
                html+='<option data-stime="00:00:00" data-etime="00:00:00" data-hours="0" value="0" data-breakhours="0">X</option>';
                $(this).attr('disabled', false); 
              }
        			 // html+= getHtml();
                     
        	  }else{
        		  html +=getAllShiftsHtml2(data_date,user_id,rshift_id); 
            	  $(this).attr('disabled', true);  
        	  }
        	 
        	   /* console.log("enable5");
        	    console.log(data_date);
        	    console.log(user_id);
        	    console.log(sobj);*/
              
       
           }
       } 
        else{
          //nothing
        }
        $(this).children("option").remove();
        $(this).append(html);
        // console.log("SHIFT ID"+shift_id+"----- "+$('.shift_'+user_id+"_"+data_date).find('option[value="'+shift_id+'"]'));
        // console.log($('.shift_'+user_id+"_"+data_date).find('option[value="'+shift_id+'"]'));
        if(shift_id){ //last fix on Apri1
        	if($('.shift_'+user_id+"_"+data_date).find('option[value="'+shift_id+'"]')){
  	          $('.shift_'+user_id+"_"+data_date).find('option[value="'+shift_id+'"]').attr("selected","selected");

        	}
        	else{
        		// console.log("NOTHING"+data_date);
        	}
        		
	        }
        
       // $('.shift_'+user_id+"_"+data_date).find('option[value="'+shift_id+'"]').attr("selected","selected");
      }else{
          // console.log(shift_id+" 333  "+data_date);

        shift_array.push(shift_name);
      }
      var class_name = $(this).attr('class').split(' ')[1];
      var shift_id = $(this).find('option:selected').val();
      var data_object = {
        class_name : class_name,
        shift_id : shift_id
      };
      user_shift_array.push(data_object);
    });
    for (var i = 0 ; i < request_array.length; i++) {
      var new_html = '';
      var shift_id = $('.shift_'+user_id+"_"+request_array[i]).find('option:selected').val();
      user_request_shift.push(shift_id);
    }

    for (var i = 0 ; i < avlrequest_array.length; i++) {
      var new_html = '';
      var shift_id = $('.shift_'+user_id+"_"+avlrequest_array[i]).find('option:selected').val();
      user_avl_shift.push(shift_id);
    }
  }
  function getRequestArray(){
    var month_diff = changeButtonStatus();
    if(month_diff >= 2){
      var request_array = [];
      var avl_array = [];
      var date_array = findDatesBtCalendarDates();
      for(var i=0;i<date_array.length;i++){
        request_array.push(date_array[i]);
      }
      var new_object = {
        avl_array : [],
        request_array : request_array
      }
      return new_object;
    }else{
    if(published_rotas.length > 0){
      var request_array = [];
      var avl_array = [];
      for(var i=0;i<published_rotas.length;i++){
        if(published_rotas[i].shift_id == 0 || published_rotas[i].shift_id == 1){
          avl_array.push(published_rotas[i].date)
        }else{
          request_array.push(published_rotas[i].date);
        }
      }
      var new_object = {
        avl_array : avl_array,
        request_array : request_array
      }
      return new_object;
    }else{
      var dates_array = findDatesBtCalendarDates();
      var user_h_dates = [];
      var user_t_dates = [];
      if(holidayDates && holidayDates.length >0){
        $.each(holidayDates, function( index, value ) {
          var dates = findDatesBetnDates(value.from_date,value.to_date);
          for(var i=0;i<dates.length;i++) {
            if(user_h_dates.indexOf(dates[i]) == -1){
              user_h_dates.push(dates[i]);
            }
          }            
        });
      }
      if(trainingDates && trainingDates.length >0){
        $.each(trainingDates, function( index, value ) {
          var dates = findDatesBetnDates(value.date_from,value.date_to);
          for(var i=0;i<dates.length;i++) {
            if(user_h_dates.indexOf(dates[i]) == -1){
              user_t_dates.push(dates[i]);
            }
          }            
        });
      }
      var array_without_duplicate = returnArrayWithoutDuplicate(dates_array,user_h_dates);
      // var offday = getOffday(user_offday);
      if(user_offday.length > 0){
        for(var i=0;i<user_offday.length;i++){
          var remove_item = $('.select_offday_'+user_offday[i]).attr('data-date');
          array_without_duplicate = $.grep(array_without_duplicate, function(value) {
            return value != remove_item;
          });
        }
      }
      var request_array = returnArrayWithoutDuplicate(array_without_duplicate,user_t_dates);
      new_avl_array = returnArrayWithoutDuplicate(dates_array,request_array);
      // console.log("----------AVL----------------");
      // console.log(new_avl_array);  console.log(user_t_dates);
      // console.log("----------AVL----------------");
      var avl_array = returnArrayWithoutDuplicate(new_avl_array,user_t_dates);
      var new_object = {
        avl_array : avl_array,
        request_array : request_array
      }
      return new_object;
    }}
  }
  function returnArrayWithoutDuplicate(array1,array2){
    array1 = array1.filter(val => !array2.includes(val));
    return array1;
  }
  function checkAvailability(){
    $(".eventcls").each(function() {
      var html = '';
      var shift_name = $(this).children("option:selected").text();
      var shift_id =  $(this).children("option:selected").val();
      var element = $(this);
      // console.log("shift_id==="+shift_id);
      if(!shift_id){
        var user_data = $(this).attr('class').split(" ")[1];
        var user_data_array = user_data.split('_');
        $.ajax({
          url:baseURL+'staffs/rota/checkAvailability',
          type:'post',
          async: false,
          dataType:'json',
          delay: 250,
          data: {
            user_id : user_data_array[1], 
            unit_id:unitID,
            date: user_data_array[2]
          },
          success: function (data) {
            var user_shiftid = data[0];
            for( var i = 0; i < available_shift.length; i++ ){
              if(available_shift[i].id == user_shiftid){
            	 
                html+='<option selected data-stime="'+available_shift[i].start_time+'" data-etime="'+available_shift[i].end_time+'" data-hours="'+available_shift[i].targeted_hours+'" value="'+available_shift[i].id+'" data-breakhours="'+available_shift[i].unpaid_break_hours+'">'+available_shift[i].shift_shortcut+'</option>';
              }else{
            	 
                html+='<option data-stime="'+available_shift[i].start_time+'" data-etime="'+available_shift[i].end_time+'" data-hours="'+available_shift[i].targeted_hours+'" value="'+available_shift[i].id+'" data-breakhours="'+available_shift[i].unpaid_break_hours+'">'+available_shift[i].shift_shortcut+'</option>';
              }
            }
            element.children("option").remove();
            element.append(html);
          }
        });
      }
    });
  }
  function getHtml(){
    var html = '';
    for( var i = 0; i < available_shift.length; i++ ){
      html+='<option data-stime="'+available_shift[i].start_time+'" data-etime="'+available_shift[i].end_time+'" data-hours="'+available_shift[i].targeted_hours+'" value="'+available_shift[i].id+'" data-breakhours="'+available_shift[i].unpaid_break_hours+'">'+available_shift[i].shift_shortcut+'</option>';
    }
   
    return html;
  }
  function getAllShiftsHtml(sdate,user_id,ssobj){
	    var htmlshifts = '';
	    var sel='';
	    var selstatus=0;
	    // console.log(ssobj);
	    for( var i = 0; i < all_shift_array.length; i++ ){
	    	
	    		
	    	
	    		inx = ssobj["shift_"+user_id+"_"+sdate];
	    	
	    		if(inx!=undefined || inx!=0){
	    			 // console.log("shift_"+user_id+"_"+sdate+" ----->inx==="+inx);
	    			if(all_shift_array[i].id==inx){
	    			//	document.getElementById('shift_'+user_id+'_'+i).selectedIndex = -1; 
	    				//$("#shift_"+user_id+"_"+i option[value='"+ui.value+"']").prop("selected", false);" +
	    				// console.log($('.shift_'+user_id+'_'+sdate+' option[value="0"]'));
	    			//	$('.shift_'+user_id+'_'+sdate+' option[value="0"]').prop("selected", false);
	    				//$('.shift_'+user_id+'_'+sdate+' option[value="0"]').removeAttr("selected", false);
	    				//previous=0;
	    				//  $('.shift_'+user_id+'_'+sdate).val(previous).find("option[value=" + previous +"]").attr('selected', false);
//
	    				  $('option:selected', '.shift_'+user_id+'_'+sdate).removeAttr('selected');
	    				//$("#deployselect option[value='"+ui.value+"']").prop("selected", false);
	    				
	    				// console.log("-----uuuuu-----");
	    				sel= 'selected="selected"';
	    				selstatus=1;
	    			}
	    			else{
		    			sel='';
		    			 
		    		}
	    		}
	    		 
	    		
	    	htmlshifts+='<option '+sel+' data-stime="'+all_shift_array[i].start_time+'" data-etime="'+all_shift_array[i].end_time+'" data-hours="'+all_shift_array[i].targeted_hours+'" value="'+all_shift_array[i].id+'" data-breakhours="'+all_shift_array[i].unpaid_break_hours+'">'+all_shift_array[i].shift_shortcut+'</option>';
	    
	    	 
	    }
	    // console.log(selstatus+"==selstatus"+ssobj.length);
	    // console.log("HHHHHHHHHHHHHHHHHHHHHHHH"+inx)
	    if(inx==0 || inx==undefined){
	    	xselect  = 'selected="selected"';
		    htmlshifts +='<option '+xselect+'data-stime="00:00:00" data-etime="00:00:00" data-hours="0" value="0"  data-breakhours="0">X</option>';

	    }
	    /*else{
	    	xselect  = '';
		    htmlshifts +='<option '+xselect+'data-stime="00:00:00" data-etime="00:00:00" data-hours="0" value="0"  data-breakhours="0">X</option>';

	    }*/
		 /* if(selstatus==0){
			  console.log("ZERO");
			  return 0;
		  
    		//htmlshifts+='<option selected="selected" data-stime="00:00:00" data-etime="00:00:00" data-hours="0" value="0"  data-breakhours="0">X3</option>';
		  }else{*/
	    		//htmlshifts+='<option data-stime="00:00:00" data-etime="00:00:00" data-hours="0" value="0"   data-breakhours="0">X4</option>';

		 // }
 

	    return htmlshifts;
  }
  function getAllShiftsHtml2(sdate,user_id,ssobj){
	    var htmlshifts = '';
	    var sel='';
	    var selstatus=0;
	    // console.log(ssobj);
	    for( var i = 0; i < all_shift_array.length; i++ ){
	    	
	    		
	    	
	    		inx = ssobj;
	    	
	    		if(inx!=undefined || inx!=0){
	    			//console.log("shift_"+user_id+"_"+sdate+" ----->inx==="+inx);
	    			if(all_shift_array[i].id==inx){
	    				sel= 'selected="selected"';
	    				selstatus=1;
	    			}
	    			else{
		    			sel='';
		    			 
		    		}
	    		}
	    		 
	    		
	    	htmlshifts+='<option '+sel+' data-stime="'+all_shift_array[i].start_time+'" data-etime="'+all_shift_array[i].end_time+'" data-hours="'+all_shift_array[i].targeted_hours+'" value="'+all_shift_array[i].id+'" data-breakhours="'+all_shift_array[i].unpaid_break_hours+'">'+all_shift_array[i].shift_shortcut+'</option>';
	    
	    	 
	    }
	    // console.log(selstatus+"==selstatus2222");
	    if(ssobj==0){
	    	xselect  = 'selected="selected"';
		    htmlshifts +='<option '+xselect+'data-stime="00:00:00" data-etime="00:00:00" data-hours="0" value="0"  data-breakhours="0">X</option>';

	    }
	    else{
	    	xselect  = '';
		    htmlshifts +='<option '+xselect+'data-stime="00:00:00" data-etime="00:00:00" data-hours="0" value=""  data-breakhours="0">X</option>';

	    }
	    // console.log("ssobj"+ssobj);

	    return htmlshifts;
}
  $(document).on('click', '.mark', function (event) {
    event.preventDefault();
    html = '';
    html += '<html><body><form action="#">'+
            '<label for="start_date">Start Date</label> : &nbsp'+
            '<input class="form-control" required type="text" name="start_date" id="start_date"><br>'+
            '<label for="end_date">End Date</label> : &nbsp'+
            '<input class="form-control" required type="text" name="end_date" id="end_date"><br>'+
            '<label for="shift">Select Shift</label> : &nbsp'+
            '<select class="custom-select form-control required" id="shift" name="shift">';  
            for( var i = 0; i < available_shift.length; i++ ){ 
              html += '<option data-stime="'+available_shift[i].start_time+'" data-etime="'+available_shift[i].end_time+'" data-hours="'+available_shift[i].targeted_hours+'" value="'+available_shift[i].id+'">'+available_shift[i].shift_shortcut+'</option>';
            }
            html += '</select></form></body></html>';
    Swal.fire({
      title: 'Mark Availability',
      html: html,
      showConfirmButton: true,
      showCancelButton: true,
      customClass: 'custom-swal-class',
      onOpen: function() {
        var year = (new Date).getFullYear();
        $('#start_date').datepicker({
          minDate: 0,
          dateFormat: 'dd/mm/yy'
        });
        $('#end_date').datepicker({
          minDate: 0,
          dateFormat: 'dd/mm/yy'
        });
      },
    }).then(function(result) {
      if (result.value) {
        var start_date = $("#start_date").val();
        var end_date = $("#end_date").val();
        var shift_id = $('#shift option:selected').val();
        var shift_hours = $('#shift option:selected').attr('data-hours');
        var new_start_date = start_date.split('/');
        var n_start_date = new_start_date[2]+'-'+new_start_date[1]+'-'+new_start_date[0];
        var new_end_date = end_date.split('/');
        var n_end_date = new_end_date[2]+'-'+new_end_date[1]+'-'+new_end_date[0];
        if(start_date && end_date && shift_id){
          if(new Date(n_end_date) >= new Date(n_start_date)){
            var start_day = new Date(n_start_date).getDay();
            var end_day = new Date(n_end_date).getDay();
            var dates = findDatesBetnDates(n_start_date,n_end_date);
            $.ajax({
              url:baseURL+'staffs/rota/markAvailability',
              type:'post',
              dataType:'json',
              delay: 250,
              data: {
                dates : dates, 
                shift_id:shift_id,
                shift_hours: shift_hours,
                unit_id:unitID,
                user_id:user_id,
                start_date: n_start_date,
                end_date:n_end_date
              },
              success: function (data) {
                var dates = data.avl_dates;
                var user_id = data.user_id;
                for(var i=0;i<dates.length;i++) {
                  $('.shift_'+user_id+'_'+dates[i]).val(shift_id);
                }
                Swal.fire({
                  title : "",
                  text: "Availability saved",
                  icon: "warning",
                  button: "ok",
                });
              }
            });
          }else{
            Swal.fire("End date must be greater than start date");
          }
        }else{
          Swal.fire("Enter start and end dates");
        }
      }
    });
  });
  function findDatesBetnDates(startDate,endDate){
    var dates = [];
    dates.push(startDate);
    var currDate = moment(startDate).startOf('day');
    var lastDate = moment(endDate).startOf('day');
    while(currDate.add(1, 'days').diff(lastDate) < 0) {
      var cur_date = currDate.clone().toDate();
      date = moment(cur_date).format('YYYY-MM-DD');
      dates.push(date);
    }
    if(startDate != endDate){
      dates.push(endDate);
    }    
    return dates;
  }
  function arr_diff (a1, a2) {

    var a = [], diff = [];

    for (var i = 0; i < a1.length; i++) {
        a[a1[i]] = true;
    }

    for (var i = 0; i < a2.length; i++) {
        if (a[a2[i]]) {
            delete a[a2[i]];
        } else {
            a[a2[i]] = true;
        }
    }

    for (var k in a) {
        diff.push(k);
    }

    return diff;
}
function returnArrayDiff(array1,array2){
  var count = 0;
  for (var i = 0; i < array1.length; i++) {
    if(array1[i] != array2[i]){
      count++;
    }
  }
  return count;
}
function compareArrays(arrayA, arrayB){
  jQuery.extend({
    compare: function (arrayA, arrayB) {
      if (arrayA.length != arrayB.length) { return false; }
      // sort modifies original array
      // (which are passed by reference to our method!)
      // so clone the arrays before sorting
      var a = jQuery.extend(true, [], arrayA);
      var b = jQuery.extend(true, [], arrayB);
      a.sort(); 
      b.sort();
      for (var i = 0, l = a.length; i < l; i++) {
          if (a[i] !== b[i]) { 
              return false;
          }
      }
      return true;
    }
  });
  return jQuery.compare(arrayA, arrayB);
}

  $(document).on('click', '.save_shift', function (e) {
    var user_limit = user_availability_request;
    event.preventDefault();
    var $this = $(this);
    var new_shift_array = [];
    var new_avl_shift_array = [];
    var available_shift_details = [];
    var request_shift_details = [];
    var weekShifts = [];
    var user_default_shift_array = [];
    for (var i = 0 ; i < request_array.length; i++) {
      var shift_id = $('.shift_'+user_id+"_"+request_array[i]).find('option:selected').val();
      new_shift_array.push(shift_id);
      user_default_shift_array.push(user_default_shift_id);
    }
    for (var i = 0 ; i < avlrequest_array.length; i++) {
      var shift_id = $('.shift_'+user_id+"_"+avlrequest_array[i]).find('option:selected').val();
      new_avl_shift_array.push(shift_id);
    }
    // console.log(user_default_shift_array);
    // console.log(new_shift_array);
    //alert("d");
    var difference = returnArrayDiff(user_default_shift_array,new_shift_array);
    var eventsFromCalendar = $('#calendar').fullCalendar( 'clientEvents');
    var increment=0;
      $.each(eventsFromCalendar, function(index,value) {
        var event = new Object();
        var sdate = value.start.toDate();
        var startDate =  moment(sdate).format('YYYY-MM-DD');
        if(index % 7 === 0){
          increment=0;
        }
        event.shift_id = $('option:selected', $('#shift_'+value.resourceId+'_'+increment)).val();
        event.shift_hours = $('option:selected', $('#shift_'+value.resourceId+'_'+increment)).attr('data-hours'); 
        event.user_id = value.resourceId;            
        event.start = startDate;
        event.unit_id = value.unitId;  
        event.from_unit = value.from_unit;
        weekShifts.push(event);
        increment++;
      });
      var unit_id = weekShifts[0].unit_id;
      var cs_date = weekShifts[0].start;
      var ce_date = weekShifts[6].start;
      wShifts = JSON.stringify(weekShifts);
      var month_diff = changeButtonStatus();
      for(var i=0;i<avlrequest_array.length;i++){
        var object = {
          shift_id : $(".shift_"+user_id+"_"+avlrequest_array[i]).find('option:selected').val(),
          shift_hours: $(".shift_"+user_id+"_"+avlrequest_array[i]).find('option:selected').attr('data-hours'),
          date: avlrequest_array[i],
          unit_id :unitID
        }
        available_shift_details.push(object);
      }
      for(var i=0;i<request_array.length;i++){
        var object = {
          shift_id : $(".shift_"+user_id+"_"+request_array[i]).find('option:selected').val(),
          shift_hours: $(".shift_"+user_id+"_"+request_array[i]).find('option:selected').attr('data-hours'),
          date: request_array[i],
          unit_id :unitID
        }
        request_shift_details.push(object);
      }
      //alert(month_diff);
      if(month_diff >= 2){
        var date_array = findDatesBetnDates(cs_date,ce_date);
        var shift_details_array = [];
        var count = 0;
        for(var i=0;i<date_array.length;i++){
          var shift_id = $(".shift_"+user_id+"_"+date_array[i]).find('option:selected').val();
          if(shift_id > 0){
            count++;
          }
          var object = {
            shift_id : $(".shift_"+user_id+"_"+date_array[i]).find('option:selected').val(),
            shift_hours: $(".shift_"+user_id+"_"+date_array[i]).find('option:selected').attr('data-hours'),
            date: date_array[i],
            unit_id :unitID
          }
          shift_details_array.push(object);
        }
        if(count > user_limit){
          Swal.fire({
            title : "",
            text: "Your request exceeded the defined limit. Please check",
            icon: "warning",
            button: "ok",
          });
        }else{
          $("#img-loader-data").show();
          $.ajax({
            url:baseURL+'staffs/rota/saveAvailability',
            type:'post',
            async : false,
            dataType:'json',
            delay: 250,
            data: {
              shiftDetails : JSON.stringify(shift_details_array)
            },
            success: function (data) {
              user_request_shift = [];
              user_request_shift = new_shift_array;
              user_avl_shift = [];
              user_avl_shift = new_avl_shift_array;
              $("#img-loader-data").hide();
              Swal.fire({
                title : "",
                text: "Your request is saved.",
                icon: "warning",
                button: "ok",
              });
            }
          });
        }
    	  // console.log("1");
    	  // console.log("difference="+difference);
    	  // console.log("user_limit="+user_limit);
        // if(difference > user_limit){
        //   $.ajax({
        //     url: baseURL+"staffs/rota/getUserRequestCount",
        //     type: "POST",
        //     async: false,
        //     data: {
        //       user_id: user_id,
        //       shift_id:user_default_shift_id,
        //       request_array : request_array,
        //       cs_date : cs_date,
        //       ce_date : ce_date
        //     },
        //     success: function (data) {
        //       var saved_dates = data.result;
        //       if(saved_dates.length > 0){
        //         for(var i=0;i<saved_dates.length;i++){
        //           $('.shift_'+user_id+'_'+saved_dates[i].date).find('option[value="'+user_default_shift_id+'"]').attr("selected","selected");
        //         }
        //       }else{
        //         for(var i=0;i<request_array.length;i++){
        //           $('.shift_'+user_id+'_'+request_array[i]).find('option[value="'+user_default_shift_id+'"]').attr("selected","selected");
        //         }
        //       }
        //       Swal.fire({
        //         title : "",
        //         text: "Your request exceeded the defined limit. Please check",
        //         icon: "warning",
        //         button: "ok",
        //       });
        //     }
        //   });          
        // }else{
        //   var avl_difference = compareArrays(user_avl_shift,new_avl_shift_array);
        //   // console.log(user_avl_shift);
        //   // console.log(new_avl_shift_array);
        //   var req_difference = compareArrays(user_request_shift,new_shift_array);
        //   // console.log("-----------");
        //   // console.log(user_request_shift);
        //   // console.log(new_shift_array);
        //   if(avl_difference == true && req_difference == true){
        //     Swal.fire({
        //       title : "",
        //       text: "Please change your request or availability and save.",
        //       icon: "warning",
        //       button: "ok",
        //     });
        //   }else if(avl_difference == false && req_difference == false){
        //     $("#img-loader-data").show();
        //     $.ajax({
        //       url:baseURL+'staffs/rota/saveBeforePublish',
        //       type:'post',
        //       dataType:'json',
        //       async : false,
        //       delay: 250,
        //       data: {
        //         shiftDetails : wShifts
        //       },
        //       success: function (data) {
        //         user_request_shift = [];
        //         user_request_shift = new_shift_array;
        //         user_avl_shift = [];
        //         user_avl_shift = new_avl_shift_array;
        //         $("#img-loader-data").hide();
        //         Swal.fire({
        //           title : "",
        //           text: "Your Request and Availability Saved",
        //           icon: "warning",
        //           button: "ok",
        //         });
        //       }
        //     });
        //   }else if(avl_difference == false && req_difference == true){
        //     var avl_array = JSON.stringify(available_shift_details);
        //     $("#img-loader-data").show();
        //     // console.log("Save1");
        //     $.ajax({
        //       url:baseURL+'staffs/rota/saveAvailability',
        //       type:'post',
        //       async : false,
        //       dataType:'json',
        //       delay: 250,
        //       data: {
        //         shiftDetails : avl_array
        //       },
        //       success: function (data) {
        //         user_request_shift = [];
        //         user_request_shift = new_shift_array;
        //         user_avl_shift = [];
        //         user_avl_shift = new_avl_shift_array;
        //         $("#img-loader-data").hide();
        //         Swal.fire({
        //           title : "",
        //           text: "Your request is saved.",
        //           icon: "warning",
        //           button: "ok",
        //         });
        //       }
        //     });
        //   }else if(avl_difference == true && req_difference == false){
        //     var req_array = JSON.stringify(request_shift_details);
        //     $("#img-loader-data").show();   //console.log("Save2");
        //     $.ajax({
        //       url:baseURL+'staffs/rota/saveAvailability',
        //       type:'post',
        //       async : false,
        //       dataType:'json',
        //       delay: 250,
        //       data: {
        //         shiftDetails : req_array
        //       },
        //       success: function (data) {
        //         user_request_shift = [];
        //         user_request_shift = new_shift_array;
        //         user_avl_shift = [];
        //         user_avl_shift = new_avl_shift_array;
        //         $("#img-loader-data").hide();
        //         Swal.fire({
        //           title : "",
        //           text: "Your availability is saved.",
        //           icon: "warning",
        //           button: "ok",
        //         });
        //       }
        //     });
        //   }else{
        //     //do nothing
        //   }
        // }
      }else{
    	  // console.log("2");
    	  // console.log(user_avl_shift);
    	  // console.log(new_avl_shift_array);
        var avl_difference = compareArrays(user_avl_shift,new_avl_shift_array);
        var avl_array = JSON.stringify(available_shift_details)
       // console.log(avl_difference);
       // console.log(avl_array);
        if(avl_difference == false){   //console.log("Save3");
          $("#img-loader-data").show();
          $.ajax({
            url:baseURL+'staffs/rota/saveAvailability',
            type:'post',
            async : false,
            dataType:'json',
            delay: 250,
            data: {
              shiftDetails : avl_array
            },
            success: function (data) {
              user_avl_shift = [];
              user_avl_shift = new_avl_shift_array;
              $("#img-loader-data").hide();
              Swal.fire({
                title : "",
                text: "Your availability is saved.",
                icon: "warning",
                button: "ok",
              });
            }
          });
        }/*else{
          $("#img-loader-data").hide();
          Swal.fire({
            title : "",
            text: "You cannot set request for this week, you can set avilability only",
            icon: "warning",
            button: "ok",
          });
        }*/
      }
  });
  function changeButtonStatus(start_date){
    if(start_date){
      var date_array = start_date.split('/');
      var date = date_array[2]+'-'+date_array[1]+'-'+date_array[0];//"2020-01-03";//start_date;
    }else{
      var calendar = $('#calendar').fullCalendar('getCalendar');
      var view = calendar.view;
      var end = view.end._d;  
      let date1 = JSON.stringify(end);
      edate = date1.slice(1,11);
      var new_edate = new Date(edate);
      new_edate.setDate(new_edate.getDate() - 1);
      var date = new Date(new_edate).toISOString().slice(0,10);
    }
    
    var sdate = getFirstWeekDay(0);
    // console.log(sdate+" SD < ED "+date);
    
    GivenSDate = new Date(sdate);
    GivenEDate = new Date(date);
    
    if(GivenSDate < GivenEDate){
    	return 2;
    }
    else{
    	return 1;
    }
  /*  var current_date   = new Date();
    var calendar_date  = new Date(date);
    var month_diff = calendar_date.getMonth() - current_date.getMonth() + 
     (12 * (calendar_date.getFullYear() - current_date.getFullYear()));
    return month_diff;*/
  }

  function getFirstWeekDay(dayOfWeek) {
    
      var now = new Date();
      
      var month;
      if (now.getMonth() == 11) {
          var current = new Date(now.getFullYear() + 1, 0, 1);
          month=current.getMonth()+2;
      } else {
          var current = new Date(now.getFullYear(), now.getMonth() + 1, 1);
          var month = current.getMonth()+2 ;
          
      }
   //changes by swaraj from
     var new_year;
     var new_month;
     if(month==13)
     {
       new_year=current.getFullYear()+1;
       new_month='1';
     }
     else
     {
       new_year=current.getFullYear();
       new_month=month;
     }

     var dateString = new_year+"-"+new_month+"-01";
     //changes by swaraj to

     //var dateString = current.getFullYear()+"-"+month+"-01";
     var date = moment(dateString, "YYYY-MM-DD");//console.log(date);

      var day = date.day();
      var diffDays = 0;

      if (day > dayOfWeek) {
        diffDays = 7 - (day - dayOfWeek);
      } else {
        diffDays = dayOfWeek - day
      }

      return (date.add(diffDays, 'day').format("YYYY-MM-DD"));

  }

    
  $(document).on('click', '.fc-next-button', function(e) {
    $("#from-datepicker").val('');
    published_rotas = [];
    var calendar = $('#calendar').fullCalendar('getCalendar');
    var view = calendar.view;
    var start = view.start._d; 
    let date = JSON.stringify(start);
    sdate = date.slice(1,11);
    var end = view.end._d;  
    let date1 = JSON.stringify(end);
    edate = date1.slice(1,11);
    var unit=unitID;
    if(user_offday.length > 0){
      for(var i=0;i<user_offday.length;i++){
        $('.select_offday_'+user_offday[i]).find('option[value=0]').attr("selected","selected");
      }
    }
    var month_diff = changeButtonStatus();
    if(month_diff >= 2){
      for(var i=0;i<7;i++){
        $('#shift_'+user_id+'_'+i).find('option[value=0]').attr("selected","selected");
      }
    }
    showHolidayAndTrainings();
    $.ajax({
      url: baseURL+"staffs/rota/getScheduleData",
      type: "POST",
      async: false,
      data: {
          unit_id: unit,start_date:sdate,end_date:edate
      },
      success: function (data) {
        var off_dates = [];
        user_offday = data.offday;
        var obj = data.selectedShifts;
        $.each(obj, function(key,value) {
        	// console.log("."+key+" Value"+value);
          $("."+key).val(value);
        });
        var month_diff = changeButtonStatus();
        if(month_diff < 2 ){
          off_dates = data.offday_dates;
        }
        // console.log("off_dates="+off_dates);
        if(off_dates.length > 0){
          published_rotas = off_dates;
          // user_offday = [];
          for(var i=0;i<off_dates.length;i++){
            if(off_dates[i].shift_id == 0 || off_dates[i].shift_id == 1){
              var avl_shift_id = getShiftId(obj,off_dates[i].date);
              // console.log("avl_shift_id="+avl_shift_id);
              if(avl_shift_id){
                $('.shift_'+user_id+"_"+off_dates[i].date).val(avl_shift_id);
              }else{
                var day_name = getDayName(off_dates[i].date);
                // console.log("off_dates1="+off_dates[i].shift_id);
                if(off_dates[i].shift_id == 0){
                  user_offday.push(day_name);
                }
                $('.shift_'+user_id+"_"+off_dates[i].date).val(off_dates[i].shift_id);
              }
            }else{
                // console.log("off_dates2="+off_dates[i].shift_id);

                $('.shift_'+user_id+"_"+off_dates[i].date).val(off_dates[i].shift_id);
            }
          }
        }
        //showTrainingDays();
        checkAvailability();
        initResourceHours(obj);
        showMessage();
      }   
    });
  });
  function getShiftId(obj,date){
    var avl_shift_id = '';
    $.each(obj, function(key,value) {
      if(key.split('_')[2] == date){
        if(value >= 1000)
        {avl_shift_id = value;}
      }
    });
    return avl_shift_id;
  }
  $(document).on('click', '.fc-prev-button', function(e) {
    $("#from-datepicker").val('');
    var calendar = $('#calendar').fullCalendar('getCalendar');
    var view = calendar.view;
    var start = view.start._d; 
    let date = JSON.stringify(start);
    sdate = date.slice(1,11);
    var end = view.end._d;  
    let date1 = JSON.stringify(end);
    edate = date1.slice(1,11);
    var unit=unitID;
    if(user_offday.length > 0){
      for(var i=0;i<user_offday.length;i++){
        $('.select_offday_'+user_offday[i]).find('option[value=0]').attr("selected","selected");
      }
    }
    var month_diff = changeButtonStatus();
    if(month_diff >= 2){
      for(var i=0;i<7;i++){
        $('#shift_'+user_id+'_'+i).find('option[value=0]').attr("selected","selected");
      }
    }
    showHolidayAndTrainings();
    $.ajax({
      url: baseURL+"staffs/rota/getScheduleData",
      type: "POST",
      async: false,
      data: {
          unit_id: unit,start_date:sdate,end_date:edate
      },
      success: function (data) {
        var off_dates = [];
        user_offday = data.offday;
        var obj = data.selectedShifts;
        $.each(obj, function(key,value) {
          $("."+key).val(value);
        });
        var month_diff = changeButtonStatus();
        if(month_diff < 2 ){
          off_dates = data.offday_dates;
        }
        if(off_dates.length > 0){
          published_rotas = off_dates;
          // user_offday = [];
          for(var i=0;i<off_dates.length;i++){
            if(off_dates[i].shift_id == 0 || off_dates[i].shift_id == 1){
              var avl_shift_id = getShiftId(obj,off_dates[i].date);
              if(avl_shift_id){
                $('.shift_'+user_id+"_"+off_dates[i].date).val(avl_shift_id);
              }else{
                var day_name = getDayName(off_dates[i].date);
                if(off_dates[i].shift_id == 0){
                  user_offday.push(day_name);
                }
                $('.shift_'+user_id+"_"+off_dates[i].date).val(off_dates[i].shift_id);
              }
            }else{
              $('.shift_'+user_id+"_"+off_dates[i].date).val(off_dates[i].shift_id);
            }
          }
        }
        //showTrainingDays();
        checkAvailability();
        initResourceHours(obj);
        showMessage();
      }   
    });
  });
  function showHolidayAndTrainings(){
    var all_holiday_dates=[];
    var all_training_dates=[];
    if(holidayDates && holidayDates.length >0){
      $.each(holidayDates, function( index, value ) {
        var dates = findDatesBetnDates(value.from_date,value.to_date);
        for(var i=0;i<dates.length;i++) {
          $('.shift_'+value.user_id+'_'+dates[i]).val(1);
        }            
      });
    }
    if(trainingDates && trainingDates.length >0){
      $.each(trainingDates, function( index, value ) {
        var dates = findDatesBetnDates(value.date_from,value.date_to);
        for(var i=0;i<dates.length;i++) {
          $('.shift_'+value.user_id+'_'+dates[i]).val(2);
        }            
      });
    }
  }
  function getUnique(arr, comp) {

      const unique = arr
           .map(e => e[comp])

         // store the keys of the unique objects
        .map((e, i, final) => final.indexOf(e) === i && i)

        // eliminate the dead keys & store unique objects
        .filter(e => arr[e]).map(e => arr[e]);

       return unique;
    }
    function getDayName(date){
      var weekday = ["sunday","monday","tuesday","wednesday","thursday","friday","saturdy"];
      var date = new Date(date);
      return weekday[date.getDay()];
    }
    function showMessage(){
      var month_diff = changeButtonStatus();
      if(month_diff >= 2){
        $('.warning_message').hide();
      }else{
        $('.warning_message').show();
      }
    }
  function onLoad(){
    published_rotas = [];
    var calendar = $('#calendar').fullCalendar('getCalendar');
    var view = calendar.view;
    var start = view.start._d; 
    let date = JSON.stringify(start);
    sdate = date.slice(1,11);
    var end = view.end._d;  
    let date1 = JSON.stringify(end);
    edate = date1.slice(1,11);
    var unit=unitID;
    if(user_offday.length > 0){
      for(var i=0;i<user_offday.length;i++){
        $('.select_offday_'+user_offday[i]).find('option[value=0]').attr("selected","selected");
      }
    }
    var month_diff = changeButtonStatus();
    if(month_diff >= 2){
      for(var i=0;i<7;i++){
        $('#shift_'+user_id+'_'+i).find('option[value=0]').attr("selected","selected");
      }
    }
    showHolidayAndTrainings();
    $.ajax({
      url: baseURL+"staffs/rota/getScheduleData",
      type: "POST",
      async: false,
      data: {
        unit_id: unit,start_date:sdate,end_date:edate
      },
      success: function (data) {
        var off_dates = [];
        user_offday = data.offday;
        var obj = data.selectedShifts;
        $.each(obj, function(key,value) {
          $("."+key).val(value);
        });
        var month_diff = changeButtonStatus();
        if(month_diff < 2 ){
          off_dates = data.offday_dates;
        }
        if(off_dates.length > 0){
          published_rotas = off_dates;
          // user_offday = [];
          for(var i=0;i<off_dates.length;i++){
            if(off_dates[i].shift_id == 0 || off_dates[i].shift_id == 1){
              var avl_shift_id = getShiftId(obj,off_dates[i].date);
              if(avl_shift_id){
                $('.shift_'+user_id+"_"+off_dates[i].date).val(avl_shift_id);
              }else{
                var day_name = getDayName(off_dates[i].date);
                if(off_dates[i].shift_id == 0){
                  user_offday.push(day_name);
                }
                $('.shift_'+user_id+"_"+off_dates[i].date).val(off_dates[i].shift_id);
              }
            }else{
              $('.shift_'+user_id+"_"+off_dates[i].date).val(off_dates[i].shift_id);
            }
          }
        }
        //showTrainingDays();
        checkAvailability();
        initResourceHours(obj);
        showMessage();
      }     
    });
  }
  function zeroTargetedHoursShifts(id){
    if(zero_targeted_hours_shifts.length > 0){
      if(zero_targeted_hours_shifts.indexOf(id) == -1){
        return true;
      }else{
        return false;
      }
    }else{
      return true;
    }
  }
  onLoad();
  var previous;
  $(document).on('focus', '.eventcls', function(event) {

  // Store the current value on focus and on change
  previous = this.value;
 // $("select option[value='"+previous+"']").attr("selected","");
});
  
 $(document).on('change', '.eventcls', function(event) {
	  // console.log(this.id);
	  var id =  this.id;
	  var val2 = this.value;
	   
	  $('#'+id).val(previous).find("option[value=" + previous +"]").attr('selected', false);
	  $('option:selected', this).removeAttr('selected');
	 // $("select option[value='"+val+"']").attr("selected","selected");
	 //$('#'+id).val(val);
	  $('#'+id).val(val2).find("option[value=" + val2 +"]").attr('selected', true);
	  
  }); 
/*  var previous;
	  $(document).on('focus', '.eventcls', function(event) {

      // Store the current value on focus and on change
      previous = this.value;
	  $("select option[value='"+previous+"']").attr("selected","");
  });
	  $(document).on('change', '.eventcls', function(event) {
		  console.log(this.id);
		  var id =  this.id;
		  var val2 = this.value;
		  $('option:selected', this).addAttr('selected');
		//  $("select option[value='"+val+"']").attr("selected","selected");
		 // $('#'+id).val(val);
		  $('#'+id).val(val2).find("option[value=" + val2 +"]").attr('selected', true);
		  
	  });*/
  

  
  
});