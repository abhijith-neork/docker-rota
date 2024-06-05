$(document).ready(function() {
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
      $(labelName).css("background-color","#efecec");
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
    function addDate(date){
    var date = new Date(date),
    days = 1;
    date.setDate(date.getDate() + days);
    let next_date = JSON.stringify(date)
    next_date = next_date.slice(1,11);
    return(next_date);
  }
  function checkOverworking(params){
    $shift_gap=shift_gap[0]['shift_gap'];
    var default_shifts = user_shift_array,
      current_class_name = "shift_"+params.c_user_id+"_"+params.current_date,
    next_day_shift,
    default_shift,
    prev_day_shift,
    twelve = moment('12:00', 'hh:mm');
    $.each(default_shifts, function( key, value ) {
      if(value.class_name == current_class_name){
        default_shift = value.shift_id;
      }
    });
    if(params.index == 0){
      var m_time = moment(params.c_shift_endtime, 'hh:mm');
      if(m_time.isBefore(twelve)){
        var c_day_endtime_date = addDate(params.c_date)+" "+params.c_shift_endtime;
      }else{
        var c_day_endtime_date = params.c_date+" "+params.c_shift_endtime;
      }
      var n_day_starttime_date = params.n_date+" "+params.n_shift_starttime;
      
      var n_day_difference = checkTimeDifference(c_day_endtime_date,n_day_starttime_date);
      var status = checkZeroHours(params.c_shift_endtime,params.n_shift_starttime);
      if(status == false)
      {if(n_day_difference < $shift_gap){
              alert("There should be a "+$shift_gap+" hour difference between shifts");
              $(".shift_"+params.c_user_id+"_"+params.current_date).val(default_shift);
            }}
    }else if(params.index == 6){
      var m_time = moment(params.p_shift_endtime, 'hh:mm');
      if(m_time.isBefore(twelve)){
        var p_day_endtime_date = addDate(params.p_date)+" "+params.p_shift_endtime;
      }else{
        var p_day_endtime_date = params.p_date+" "+params.p_shift_endtime;
      }
      var c_day_starttime_date = params.c_date+" "+params.c_shift_starttime;
      
      var p_day_difference = checkTimeDifference(p_day_endtime_date,c_day_starttime_date);

      var status = checkZeroHours(params.p_shift_endtime,params.c_shift_starttime);
            if(status == false)
            {if(p_day_difference < $shift_gap){
                    alert("There should be a "+$shift_gap+" hour difference between shifts");
                    $(".shift_"+params.c_user_id+"_"+params.current_date).val(default_shift);
                  }}
    }else{
      var moment_p_time = moment(params.p_shift_endtime, 'hh:mm');
      if(moment_p_time.isBefore(twelve)){
        var p_day_endtime_date = addDate(params.p_date)+" "+params.p_shift_endtime;
      }else{
        var p_day_endtime_date = params.p_date+" "+params.p_shift_endtime;
      }
      var c_day_starttime_date = params.c_date+" "+params.c_shift_starttime;
      
      var moment_c_time = moment(params.c_shift_endtime, 'hh:mm');
      if(moment_c_time.isBefore(twelve)){
        var c_day_endtime_date = addDate(params.c_date)+" "+params.c_shift_endtime;
      }else{
        var c_day_endtime_date = params.c_date+" "+params.c_shift_endtime;
      }
      var n_day_starttime_date = params.n_date+" "+params.n_shift_starttime;
      var p_day_difference = checkTimeDifference(p_day_endtime_date,c_day_starttime_date);
      var n_day_difference = checkTimeDifference(c_day_endtime_date,n_day_starttime_date);
      var p_status = checkZeroHours(params.p_shift_endtime,params.c_shift_starttime);
      var n_status = checkZeroHours(params.c_shift_endtime,params.n_shift_starttime);
      if(p_status == true && n_status==true){
      }else if(p_status == false && n_status == false){
        if(p_day_difference < $shift_gap || n_day_difference < $shift_gap){
          alert("There should be a "+$shift_gap+" hour difference between shifts");
          $(".shift_"+params.c_user_id+"_"+params.current_date).val(default_shift);
        }
      }else if(p_status == false && n_status == true){
        if(p_day_difference < $shift_gap){
          alert("There should be a "+$shift_gap+" hour difference between shifts");
          $(".shift_"+params.c_user_id+"_"+params.current_date).val(default_shift);
        }
      }else if(n_status == false && p_status == true){
        if(n_day_difference < $shift_gap){
         alert("There should be a "+$shift_gap+" hour difference between shifts");
          $(".shift_"+params.c_user_id+"_"+params.current_date).val(default_shift);
        }
      }else{
      }
    }
  }
  $(document).on('change', 'select.eventcls', function (e) {
    var class_name = $(this).attr('class').split(' ')[1];
    var current_element_date = class_name.split('_')[2];
    var c_user_id = class_name.split('_')[1];
    var current_date = new Date(current_element_date);
    var next_day = new Date(current_date);
    next_day.setDate(current_date.getDate()+1);
    var prev_day = new Date(current_date);
    prev_day.setDate(current_date.getDate()-1);
    let n_date = JSON.stringify(next_day)
    n_date = n_date.slice(1,11);
    let p_date = JSON.stringify(prev_day)
    p_date = p_date.slice(1,11);
    var selected_shift = $(this).find('option:selected').val();
    var c_date = JSON.stringify(current_date);
    c_date = c_date.slice(1,11);
    var c_shift_starttime = $(this).find('option:selected').attr('data-stime');
    var c_shift_endtime = $(this).find('option:selected').attr('data-etime');
    var p_shift_starttime = $(".shift_"+c_user_id+"_"+p_date).find('option:selected').attr('data-stime');
    var p_shift_endtime = $(".shift_"+c_user_id+"_"+p_date).find('option:selected').attr('data-etime');
    var n_shift_starttime = $(".shift_"+c_user_id+"_"+n_date).find('option:selected').attr('data-stime');
    var n_shift_endtime = $(".shift_"+c_user_id+"_"+n_date).find('option:selected').attr('data-etime');
    var index = $(this).attr('id').split('_')[2];
    var params = {
      c_user_id : c_user_id,
      current_date : current_element_date,
      c_date : c_date,
      n_date : n_date,
      p_date : p_date,
      selected_shift : selected_shift,
      index : index,
      c_shift_starttime : c_shift_starttime,
      c_shift_endtime : c_shift_endtime,
      p_shift_starttime : p_shift_starttime,
      p_shift_endtime : p_shift_endtime,
      n_shift_starttime : n_shift_starttime,
      n_shift_endtime : n_shift_endtime
    }
    checkOverworking(params);

    var user_weeklyhours = convertUserWeeklyHours(parseFloat($('.totalhours').attr('data-hours').replace(':', '.')).toFixed(2));
    var id = this.id;
    var splitArr =  id.split("_");
    var userId = splitArr[1];
    var Index = splitArr[2];
    var sum=0;suminit=0;sum2=0;
    var unpaid_breakhours = 0;
    var deducted_hours;
    var totalH=0;
    var totalM=0;
    var newSum=0;
    var newSum2=0;
    var t_break_hour = 0;
    var t_break_minutes = 0;
    var total_break_hours = 0;
    var total_break_hours2 = 0;
    var overtime = 0;
    for (i = 0; i <= 6; i++) {
      $('#shift_'+userId+'_'+i).css("background","none");
    }
    for (i = 0; i <= 6; i++) {
      if($('#shift_'+userId+'_'+i). length){
        if($('option:selected', $('#shift_'+userId+'_'+i)).attr('data-hours') != undefined){
          suminit = parseFloat($('option:selected', $('#shift_'+userId+'_'+i)).attr('data-hours').replace(':', '.')).toFixed(2); 
          unpaid_breakhours = parseFloat($('option:selected', $('#shift_'+userId+'_'+i)).attr('data-breakhours').replace(':', '.')).toFixed(2);
           // First simply adding all of it together, total hours and total minutes
          var sumtime = suminit.toString().split('.');  
          var unpaid_breakhours_sum = unpaid_breakhours.toString().split('.');
          totalH += parseInt(sumtime[0]);
          totalM += parseInt(sumtime[1]);
          t_break_hour += parseInt(unpaid_breakhours_sum[0]);
          t_break_minutes += parseInt(unpaid_breakhours_sum[1]);
     // If the minutes exceed 60
          if (totalM >= 60) {
              // Divide minutes by 60 and add result to hours
              totalH += Math.floor(totalM / 60);
              // Add remainder of totalM / 60 to minutes
              totalM = totalM % 60;
          }
          if (t_break_minutes >= 60) 
          {
            // Divide minutes by 60 and add result to hours
            t_break_hour += Math.floor(t_break_minutes / 60);
            // Add remainder of totalM / 60 to minutes
            t_break_minutes = t_break_minutes % 60;
          }
          sum=  totalH + "." + totalM;
          sum2 = parseFloat(sum);
          newSum = (totalH * 60 + totalM)/60;
          newSum2 = parseFloat(newSum);
          total_break_hours = (t_break_hour * 60 + t_break_minutes)/60;
          total_break_hours2 = parseFloat(total_break_hours);
          overtime = newSum2 - total_break_hours2;
          if(overtime > user_weeklyhours ){
            var shift_id = $('#shift_'+userId+'_'+i).find('option:selected').val();
            if(zeroTargetedHoursShifts(shift_id)){
              $('#shift_'+userId+'_'+i).css("background"," #ffc87a");
            }else{
              $('#shift_'+userId+'_'+i).css("background","none");
            }
          } 
          else{
            $('#shift_'+userId+'_'+i).css("background","none");
          }
        }
      }
    }
    if(newSum2==0){
      $('#totalhours_'+userId).html(newSum2.toFixed(1));
      $('#totalhours_'+userId).css("color","red");
    }
    else{
      deducted_hours = newSum2 - total_break_hours2;
      $('#totalhours_'+userId).html(deducted_hours.toFixed(1));
      $('#totalhours_'+userId).css("color","");
      $('#totalhours_'+userId).css("color","#54667a");
    }
    if(newSum2<user_weeklyhours)
    {
      $('#totalhours_'+userId).css("color","red");
    }
  });
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
    function initResourceHours(){
      // var offday = getOffday(user_offday);
      if(user_offday.length > 0){
        for(var i=0;i<user_offday.length;i++){
          $('.select_offday_'+user_offday[i]).find('option[value=0]').attr("selected","selected");
        }
      }
      user_shift_array = [];
      shift_array = [];
      request_array = [];
      avlrequest_array = [];
      user_request_shift = [];
      user_avl_shift = [];
    var user_weeklyhours = convertUserWeeklyHours(parseFloat($('.totalhours').attr('data-hours').replace(':', '.')).toFixed(2)); 
    $.each(weekEvents, function(index,jsonObject){
      var sum = 0;suminit=0;sum2=0;
      var unpaid_breakhours = 0;
      var deducted_hours; var totalH=0;
      var totalM=0;
      var newSum=0;
      var newSum2=0;
      var t_break_hour = 0;
      var t_break_minutes = 0;
      var total_break_hours = 0;
      var total_break_hours2 = 0;
      var overtime = 0;
        for (i = 0; i <= 6; i++) {
          if($('option:selected', $('#shift_'+jsonObject.resourceId+'_'+i)).attr('data-hours') != undefined){
            suminit = parseFloat($('option:selected', $('#shift_'+jsonObject.resourceId+'_'+i)).attr('data-hours').replace(':', '.')).toFixed(2); 
            unpaid_breakhours = parseFloat($('option:selected', $('#shift_'+jsonObject.resourceId+'_'+i)).attr('data-breakhours').replace(':', '.')).toFixed(2);
            var sumtime = suminit.toString().split('.');
            var unpaid_breakhours_sum = unpaid_breakhours.toString().split('.');
            totalH += parseInt(sumtime[0]);
            totalM += parseInt(sumtime[1]);
            t_break_hour += parseInt(unpaid_breakhours_sum[0]);
            t_break_minutes += parseInt(unpaid_breakhours_sum[1]);
            // If the minutes exceed 60
            if (totalM >= 60) 
            {
              // Divide minutes by 60 and add result to hours
              totalH += Math.floor(totalM / 60);
              // Add remainder of totalM / 60 to minutes
              totalM = totalM % 60;
            }
            if (t_break_minutes >= 60) 
            {
              // Divide minutes by 60 and add result to hours
              t_break_hour += Math.floor(t_break_minutes / 60);
              // Add remainder of totalM / 60 to minutes
              t_break_minutes = t_break_minutes % 60;
            }
            sum=  totalH + "." + totalM;
            sum2 = parseFloat(sum); 
            newSum = (totalH * 60 + totalM)/60;
            newSum2 = parseFloat(newSum);
            total_break_hours = (t_break_hour * 60 + t_break_minutes)/60;
            total_break_hours2 = parseFloat(total_break_hours);
            overtime = newSum2 - total_break_hours2;
            // if(sum2 > user_weeklyhours )
            if(overtime > user_weeklyhours )
            {
              var shift_id = $('#shift_'+jsonObject.resourceId+'_'+i).find('option:selected').val();
              if(zeroTargetedHoursShifts(shift_id)){
                $('#shift_'+jsonObject.resourceId+'_'+i).css("background"," #ffc87a");
              }else{
                $('#shift_'+jsonObject.resourceId+'_'+i).css("background","none");
              }
            } 
            else
            {
              $('#shift_'+jsonObject.resourceId+'_'+i).css("background","none");
            }
          }
        }
        // if(sum==0)
        if(newSum2==0)
        {
          // $('#totalhours_'+jsonObject.resourceId).html(sum); 
          $('#totalhours_'+jsonObject.resourceId).html(newSum2); 
          $('#totalhours_'+jsonObject.resourceId).css("color","red");
        }
        else
        {
          // deducted_hours = sum - unpaid_breakhours; 
          deducted_hours = newSum2 - total_break_hours2; 
          $('#totalhours_'+jsonObject.resourceId).html(deducted_hours.toFixed(1)); 
          $('#totalhours_'+jsonObject.resourceId).css("color","#54667a");
        }
        if(newSum2<user_weeklyhours)
        {
          $('#totalhours_'+jsonObject.resourceId).css("color","red");
        }
      });
    var arrays = getRequestArray();
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
      var data_breakhours =  $(this).children("option:selected").attr('data-breakhours');
      //Making Training dropdown disabled
      if(shift_id == 2){
        $(this).attr('disabled', true);
      }
      if(request_array.indexOf(data_date) != -1){
        if(shift_id == user_default_shift[0].id){
          $('.shift_'+user_id+"_"+data_date).find('option[value="'+user_default_shift[0].id+'"]').attr("selected","selected");
        }
        /*if(shift_id == 0){
          html+='<option data-stime="'+user_default_shift[0].start_time+'" data-etime="'+user_default_shift[0].end_time+'" data-hours="'+user_default_shift[0].targeted_hours+'" value="'+user_default_shift[0].id+'" data-breakhours="'+user_default_shift[0].unpaid_break_hours+'">'+user_default_shift[0].shift_shortcut+'</option>';
          html+='<option data-stime="00:00:00" selected="selected" data-etime="00:00:00" data-hours="0" value="0" data-breakhours="0">X</option>';
        }else{
          html+='<option selected="selected" data-stime="'+user_default_shift[0].start_time+'" data-etime="'+user_default_shift[0].end_time+'" data-hours="'+user_default_shift[0].targeted_hours+'" value="'+user_default_shift[0].id+'" data-breakhours="'+user_default_shift[0].unpaid_break_hours+'">'+user_default_shift[0].shift_shortcut+'</option>';
          html+='<option data-stime="00:00:00" data-etime="00:00:00" data-hours="0" value="0" data-breakhours="0">X</option>';
        }
        $(this).children("option").remove();
        $(this).append(html);      */
      }else if(shift_id == 1 || shift_id == 0 || shift_id >= 100){
        if(shift_id == 1){
          html+= getHtml();
          // html+='<option data-stime="'+available_shift[0].start_time+'" data-etime="'+available_shift[0].end_time+'" data-hours="'+available_shift[0].targeted_hours+'" value="'+available_shift[0].id+'" data-breakhours="'+available_shift[0].unpaid_break_hours+'">'+available_shift[0].shift_shortcut+'</option>';
          html+='<option data-stime="00:00:00" data-etime="00:00:00" data-hours="0" selected="selected" value="1" data-breakhours="0">H</option>';
        }
        else if(shift_id >= 100){
          html+= getHtml();
          // html+='<option data-stime="'+available_shift[0].start_time+'" data-etime="'+available_shift[0].end_time+'" data-hours="'+available_shift[0].targeted_hours+'" value="'+available_shift[0].id+'" data-breakhours="'+available_shift[0].unpaid_break_hours+'">'+available_shift[0].shift_shortcut+'</option>';
          var class_array = $(this).attr('class').split(' ')[3];
          if(jQuery.inArray(class_array.split('_')[2], user_offday) !== -1){
            html+='<option data-stime="00:00:00" data-etime="00:00:00" data-hours="0" value="1" data-breakhours="0">X</option>';
          }else{
            html+='<option data-stime="00:00:00" data-etime="00:00:00" data-hours="0" value="0" data-breakhours="0">H</option>';
          }
        }
        else if(shift_id == 0){
          html+= getHtml();
          // html+='<option data-stime="'+available_shift[0].start_time+'" data-etime="'+available_shift[0].end_time+'" data-hours="'+available_shift[0].targeted_hours+'" value="'+available_shift[0].id+'" data-breakhours="'+available_shift[0].unpaid_break_hours+'">'+available_shift[0].shift_shortcut+'</option>';
          html+='<option data-stime="00:00:00" data-etime="00:00:00" data-hours="0" value="0" selected="selected" data-breakhours="0">X</option>';
        }else{
          //nothing
        }
        // html += getHtml();
        $(this).children("option").remove();
        $(this).append(html);
        $('.shift_'+user_id+"_"+data_date).find('option[value="'+shift_id+'"]').attr("selected","selected");
      }else{
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
      var avl_array = returnArrayWithoutDuplicate(new_avl_array,user_t_dates);
      var new_object = {
        avl_array : avl_array,
        request_array : request_array
      }
      return new_object;
    }
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
      if(!shift_id){
        var user_data = $(this).attr('class').split(" ")[1];
        var user_data_array = user_data.split('_');
        $.ajax({
          url:baseURL+'manager/rota/checkAvailability',
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
            var save_status = changeButtonStatus(end_date);
            // if(save_status == true){
              var dates = findDatesBetnDates(n_start_date,n_end_date);
              $.ajax({
                url:baseURL+'manager/rota/markAvailability',
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
            /*}else{
              Swal.fire("You are not able to set availability on this selected dates");
            }*/
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
    var request_shift_details = [];
    var available_shift_details = [];
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
      if(month_diff >= 2){
        if(difference > user_limit){
          $.ajax({
            url: baseURL+"manager/rota/getUserRequestCount",
            type: "POST",
            async: false,
            data: {
              user_id: user_id,
              shift_id:user_default_shift_id,
              request_array : request_array
            },
            success: function (data) {
              var saved_dates = data.result;
              if(saved_dates.length > 0){
                for(var i=0;i<saved_dates.length;i++){
                  $('.shift_'+user_id+'_'+saved_dates[i].date).find('option[value="'+user_default_shift_id+'"]').attr("selected","selected");
                }
              }else{
                for(var i=0;i<request_array.length;i++){
                  $('.shift_'+user_id+'_'+request_array[i]).find('option[value="'+user_default_shift_id+'"]').attr("selected","selected");
                }
              }
              Swal.fire({
                title : "",
                text: "Your request exceeded the defined limit. Please check",
                icon: "warning",
                button: "ok",
              });
            }
          });  
        }else{
          var avl_difference = compareArrays(user_avl_shift,new_avl_shift_array);
          var req_difference = compareArrays(user_request_shift,new_shift_array);
          if(avl_difference == true && req_difference == true){
            Swal.fire({
              title : "",
              text: "Please change your request or availability and save.",
              icon: "warning",
              button: "ok",
            });
          }else if(avl_difference == false && req_difference == false){
            $("#img-loader-data").show();
            $.ajax({
              url:baseURL+'manager/rota/saveBeforePublish',
              type:'post',
              dataType:'json',
              async : false,
              delay: 250,
              data: {
                shiftDetails : wShifts
              },
              success: function (data) {
                user_request_shift = [];
                user_request_shift = new_shift_array;
                user_avl_shift = [];
                user_avl_shift = new_avl_shift_array;
                $("#img-loader-data").hide();
                Swal.fire({
                  title : "",
                  text: "Your Request and Availability Saved",
                  icon: "warning",
                  button: "ok",
                });
              }
            });
          }else if(avl_difference == false && req_difference == true){
            var avl_array = JSON.stringify(available_shift_details);
            $("#img-loader-data").show();
            $.ajax({
              url:baseURL+'manager/rota/saveAvailability',
              type:'post',
              async : false,
              dataType:'json',
              delay: 250,
              data: {
                shiftDetails : avl_array
              },
              success: function (data) {
                user_request_shift = [];
                user_request_shift = new_shift_array;
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
          }else if(avl_difference == true && req_difference == false){
            var req_array = JSON.stringify(request_shift_details);
            $("#img-loader-data").show();
            $.ajax({
              url:baseURL+'manager/rota/saveAvailability',
              type:'post',
              async : false,
              dataType:'json',
              delay: 250,
              data: {
                shiftDetails : req_array
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
          }else{
            //do nothing
          }
        }
      }else{
        var avl_difference = compareArrays(user_avl_shift,new_avl_shift_array);
        var avl_array = JSON.stringify(available_shift_details)
        if(avl_difference == false){
          $("#img-loader-data").show();
          $.ajax({
            url:baseURL+'manager/rota/saveAvailability',
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
                text: "Your availability is saved and you are not able to save your requests in this week",
                icon: "warning",
                button: "ok",
              });
            }
          });
        }else{
          $("#img-loader-data").hide();
          Swal.fire({
            title : "",
            text: "You cannot set request for this week, you can set avilability only",
            icon: "warning",
            button: "ok",
          });
        }
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
    console.log(sdate+" SD"+date);
    
    GivenSDate = new Date(sdate);
    GivenEDate = new Date(date);
    
    if(GivenSDate < GivenEDate){
    	return 2;
    }
    else{
    	return 1;
    }
 /*   var current_date   = new Date();
    var calendar_date  = new Date(date);
    var month_diff = calendar_date.getMonth() - current_date.getMonth() + 
     (12 * (calendar_date.getFullYear() - current_date.getFullYear()));
    return month_diff;*/
  }


  function getFirstWeekDay(dayOfWeek) {
    
      var now = new Date();
      var g_month=now.getMonth()+1;
      
      var month;
      if (g_month == 11) 
      {  
          var current = new Date(now.getFullYear() + 1, 0, 1);
          month=current.getMonth()+2;
      } 
      else if (g_month == 12) 
      {
          var current = new Date(now.getFullYear() + 1, 1, 1);
          month=current.getMonth()+2;
      }
      else
      {  
          var current = new Date(now.getFullYear(), g_month+1, 1);
          var month = current.getMonth()+2 ;
          
      }

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
     //var dateString = current.getFullYear()+"-"+month+"-01";
     var dateString = new_year+"-"+new_month+"-01";
     var date = moment(dateString, "YYYY-MM-DD"); //console.log(date);

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
    showHolidayAndTrainings();
    $.ajax({
      url: baseURL+"manager/rota/getScheduleData",
      type: "POST",
      async: false,
      data: {
          unit_id: unit,start_date:sdate,end_date:edate
      },
      success: function (data) {
        user_offday = data.offday;
        var obj = data.selectedShifts;
        $.each(obj, function(key,value) {
          $("."+key).val(value);
        });
        var off_dates = data.offday_dates;        
        if(off_dates.length > 0){
          published_rotas = off_dates;
          user_offday = [];
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
        showTrainingDays();
        checkAvailability();
        initResourceHours();
        showMessage();
      }   
    });
  });
  function getShiftId(obj,date){
    var avl_shift_id = '';
    $.each(obj, function(key,value) {
      if(key.split('_')[2] == date){
        if(value >= 100)
        {avl_shift_id = value;}
      }
    });
    return avl_shift_id;
  }
  $(document).on('click', '.fc-prev-button', function(e) {
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
    showHolidayAndTrainings();
    $.ajax({
      url: baseURL+"manager/rota/getScheduleData",
      type: "POST",
      async: false,
      data: {
          unit_id: unit,start_date:sdate,end_date:edate
      },
      success: function (data) {
        user_offday = data.offday;
        var obj = data.selectedShifts;
        $.each(obj, function(key,value) {
          $("."+key).val(value);
        });
        var off_dates = data.offday_dates;        
        if(off_dates.length > 0){
          published_rotas = off_dates;
          user_offday = [];
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
        showTrainingDays();
        checkAvailability();
        initResourceHours();
        showMessage();
      }   
    });
  });
  function showHolidayAndTrainings(){``
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
    showHolidayAndTrainings();
    $.ajax({
      url: baseURL+"manager/rota/getScheduleData",
      type: "POST",
      async: false,
      data: {
        unit_id: unit,start_date:sdate,end_date:edate
      },
      success: function (data) {
        user_offday = data.offday;
        var obj = data.selectedShifts;
        $.each(obj, function(key,value) {
          $("."+key).val(value);
        });
        var off_dates = data.offday_dates;
        if(off_dates.length > 0){
          published_rotas = off_dates;
          user_offday = [];
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
        showTrainingDays();
        checkAvailability();
        initResourceHours();
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
});