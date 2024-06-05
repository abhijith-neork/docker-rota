$(document).ready(function() {
  // showStaffLimitMessage();
  var user_shift_array = [];
    function showStaffLimitMessage(){
        var staff_count = weekEvents.length;
        var staff_limit = staff_limit;
        var messsage = "";
        if(unit && staff_count > 0){
          if(staff_count > staff_limit){
            messsage = "This unit is overstaffed";
          }else{
            messsage = "This unit is understaffed";
          }
          $(".staff_limit_message").addClass("alert-warning");
          $('.staff_limit_message').html(messsage);
        }
    } 
   
    var date_last_clicked = null;  
    $('#calendar').fullCalendar({
    	resourceGroupField: 'designation_id',
    height: "auto",
    resourceAreaWidth: 350,  
    editable: false,
    eventLimit: true,
    aspectRatio: 3,
    scrollTime: '00:00',
    header: {
      left: 'promptResource today ',
      center: 'title',
      right: ' ',   
    },
    titleFormat: '[Schedule for week] - ddd D/MM/YYYY [: '+unit+']', 
    eventRender: function( event, element, view ) {
     element.attr( 'id', event.id );
              
            var eventStart = moment(event.start);
     
              //add id
            var titleSelect =event.title;
            var resourceID = event.resourceId;
            var replaceString = 'shift_'+resourceID;
            var weekDay = eventStart.weekday();
            var replaceWith = 'shift_'+resourceID+'_'+weekDay;
            var et = event.title.replace(replaceString, replaceWith);
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
              var dates = findDatesBtCalendarDates();
              var resourceID = event.resourceId;
              var days = ["1","2","3","4","5","6","7"];
              var weekdaynames = ["Su","Mo","Tu","We","Th","Fr","Sa"];
              var replaceWith = 'shift_'+resourceID+'_'+weekDay;
              element.find('#'+replaceWith).addClass("shift_"+resourceID+"_"+dates[weekDay]);
              element.find('#'+replaceWith).addClass("select_color_"+days[weekDay]);
              element.find('#'+replaceWith).attr("data-count",days[weekDay]);
              element.find('#'+replaceWith).attr("data-dayname",weekdaynames[weekDay]);
              element.find('#'+replaceWith).attr("data-dayname",weekdaynames[weekDay]);
              element.find('#'+replaceWith).val(event.default_shift_user);
              $("#img-loader-data").hide();
              // console.log("eventAfterRender");  
            }, 
    events: weekEvents,
    /*customButtons: {
      promptResource: {
        text:unit,
      }
    },*/
    firstDay: 0,
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
      //labelTds.css("background-color", "#CCC");
      //labelTds.css("color", textColor);
      var labelName = labelTds[0];
      $(labelName).css("background-color","#efecec");
      $(labelName).css("color", resourceObj.unit_color);
      $(labelName).css("font-size", "11px");
      $(labelName).css("font-weight", "bold");
      
  
      var label = labelTds[1];
      var str = $(label).find('.fc-cell-text').text();
        if (resourceObj.totalhours!='')
        {
          $(label).find('.fc-cell-text').addClass("totalhours");
          $(label).find('.fc-cell-text').addClass("user_totalhours_"+resourceObj.id);
          $(label).find('.fc-cell-text').attr("data-hours",resourceObj.user_weeklyhours);
          $(label).find('.totalhours').append('&nbsp;&nbsp;<strong id=\"totalhours_'+resourceObj.id+'\">0</strong>');
        }
    }

   });
    var date = moment($("#end_date").val(), "YYYY-MM-DD");
    $("#calendar").fullCalendar( 'gotoDate', date );
    function addButtons(){
      var array = getArray();
      var html = '';
      var count = 0;
      /*html += '<br><label style="height:10px;margin-left:6px;" class="male-count" title="Day"></label>';
      html += '<br><label style="height:10px;margin-left:6px;" class="female-count" title="Day"></label><br>';*/
      html += '<button style="height:10px;" class="day-color" disabled="" title="Day"></button>';
      html += '<button style="height:10px;" class="night-color" disabled="" title="Night"></button>';
      html += '<button style="height:10px;margin-left:6px;" class="day-nurse-color" disabled="" title="Day Shift Nurse Count"></button>';
      html += '<button style="height:10px;margin-left:2px;" class="night-nurse-color" disabled="" title="Night Shift Nurse Count"></button>';
      $('.fc-widget-header').each(function(){         
        var text = $(this).find($('.fc-cell-text')).text();
        var array_status = array.includes(text);
        if(array_status == true){
          count++;
          $(this).find($('.fc-cell-text')).addClass('change-color-'+count);
          $(this).find($('.fc-cell-text')).append(html);
        }
      });
      findDayNightCount();
    }
    function findShiftCat(shift_id){
      var shift_category = '' ;
      $.each(shift_cats, function(key,value) {
        if(key == shift_id){
          shift_category = value;
        }        
      });
      return shift_category;
    }
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
    function findDayNightCount(){      
      for(var i=1;i<=7;i++){
        var day_count = 0;
        var night_count = 0;
        var nurse_day_count = 0;
        var nurse_night_count = 0;
        var male_nurse_count = 0;
        var female_nurse_count = 0;
        var shift_category = '';
        $('.select_color_'+i).each(function(){
          // var count_status = $(this).attr('data-status');
          var des_code = $(this).attr('data-descode');
          var sort_order = parseInt($(this).attr('data-sortoder'));
          var gender = $(this).attr('data-gender');
          var shift_code = $(this).attr('data-shiftcode');
          var part_number = parseInt($(this).find("option:selected").attr('data-partnumber'));
          if(part_number == 1){
            var shift_id = $(this).val();
            shift_category = findShiftCat(shift_id);
            if(shift_category == 1){
              day_count++;
              if(shift_code == "EL"){
                day_count++;
              }
            }else if(shift_category == 2){
              night_count++;
            }else{
              //do nothing
            }
            if(sort_order == 3){
              if(shift_category == 1){
                nurse_day_count++;
              }else if(shift_category == 2){
                nurse_night_count++;
              }else{
                //do nothing
              }
            }
            if(gender == "M"){
              male_nurse_count++;
            }else{
              female_nurse_count++;
            }
          }
        });
        showMaleAndFemaleNurseCount(male_nurse_count,female_nurse_count,i);
        changeNurseCountColor(nurse_day_count,nurse_night_count,i);
        changeColor(day_count,night_count,i);
      }
    }
    function showMaleAndFemaleNurseCount(male_nurse_count,female_nurse_count,i){
      $(".change-color-"+i).find(".male-count").html('Male : '+male_nurse_count);
      $(".change-color-"+i).find(".female-count").html('Female : '+female_nurse_count);
    }
    function changeNurseCountColor(day_count,night_count,i)
    {      
      var nurse_day_count = $("#nurse_day_count").val();
      var nurse_night_count = $("#nurse_night_count").val();

      if(day_count < nurse_day_count){
        $(".change-color-"+i).find(".day-nurse-color").css({backgroundColor: '#FF0000'});
      }else if(day_count == nurse_day_count){
        $(".change-color-"+i).find(".day-nurse-color").css({backgroundColor: '#006400'});
      }else if(day_count > nurse_day_count){
        $(".change-color-"+i).find(".day-nurse-color").css({backgroundColor: '#0000FF'});
      }else{
        //nothing
      }

      if(night_count < nurse_night_count){
        $(".change-color-"+i).find(".night-nurse-color").css({backgroundColor: '#FF0000'});
      }else if(night_count == nurse_night_count){
        $(".change-color-"+i).find(".night-nurse-color").css({backgroundColor: '#006400'});
      }else if(night_count > nurse_night_count){
        $(".change-color-"+i).find(".night-nurse-color").css({backgroundColor: '#0000FF'});
      }else{
        //nothing
      }
    }
    function changeColor(day_count,night_count,i){      
      var day_shift_min = $("#day_shift_min").val();
      var day_shift_max = $("#day_shift_max").val();
      var night_shift_min = $("#night_shift_min").val();
      var night_shift_max = $("#night_shift_max").val();
      var designation = $("#designation").val();
      var num_patients = $("#num_patients").val();
      /*console.log("day_count",day_count);
      console.log("night_count",night_count);
      console.log("day_shift_min",day_shift_min);
      console.log("day_shift_max",day_shift_max);
      console.log("night_shift_min",night_shift_min);
      console.log("night_shift_max",night_shift_min);*/
              //7         2                7           4
      if(day_count > day_shift_min && day_count < day_shift_max){
        $(".change-color-"+i).find(".day-color").css({backgroundColor: '#7CFC00'});
      }else if(day_count < day_shift_min){
        $(".change-color-"+i).find(".day-color").css({backgroundColor: '#FF0000'});
      }else if(day_count == day_shift_min){
        $(".change-color-"+i).find(".day-color").css({backgroundColor: '#ffbf00'});
      }else if(day_count > day_shift_max){
        $(".change-color-"+i).find(".day-color").css({backgroundColor: '#0000FF'});
      }else if(day_count == day_shift_max){
        $(".change-color-"+i).find(".day-color").css({backgroundColor: '#006400'});
      }else{
        //Nothing
      }
      //Night  shift
      if(night_count > night_shift_min && night_count < night_shift_max){
        $(".change-color-"+i).find(".night-color").css({backgroundColor: '#7CFC00'});
      }else if(night_count < night_shift_min){
        $(".change-color-"+i).find(".night-color").css({backgroundColor: '#FF0000'});
      }else if(night_count == night_shift_min){
        $(".change-color-"+i).find(".night-color").css({backgroundColor: '#ffbf00'});
      }else if(night_count > night_shift_max){
        $(".change-color-"+i).find(".night-color").css({backgroundColor: '#0000FF'});
      }else if(night_count == night_shift_max){
        $(".change-color-"+i).find(".night-color").css({backgroundColor: '#006400'});
      }else{
        //Nothing
      }
    }
    function getArray(){
      var calendar = $('#calendar').fullCalendar('getCalendar');
      var view = calendar.view;
      var start = view.start._d; 
      let date = JSON.stringify(start);
      sdate = date.slice(1,11);
      var end = view.end._d;  
      let date1 = JSON.stringify(end);
      edate = date1.slice(1,11);
      var date_array = findDatesBetnDates(sdate,edate);
      var weekday = ["Su","Mo","Tu","We","Th","Fr","Sa"];
      var array = [];
      for(var i=0;i<date_array.length;i++){
        var new_date = new Date(date_array[i]);
        var day = date_array[i].split('-')[2];
        var str = Number(day).toString();
        var day_name = weekday[new_date.getDay()];
        array.push(day_name+" "+str);
      }
      return array;
    }
    function changeColorOnSelectboxChange(i){
      var day_count = 0;
      var night_count = 0;
      var nurse_day_count = 0;
      var nurse_night_count = 0;
      var male_nurse_count = 0;
      var female_nurse_count = 0;
      var shift_category = '';
      $('.select_color_'+i).each(function(){
        // var count_status = $(this).attr('data-status');
        var des_code = $(this).attr('data-descode');
        var sort_order = parseInt($(this).attr('data-sortoder'));
        var gender = $(this).attr('data-gender');
        var shift_code = $(this).attr('data-shiftcode');
        var part_number = parseInt($(this).find("option:selected").attr('data-partnumber'));
        if(part_number == 1){
          var shift_id = $(this).val();
          shift_category = findShiftCat(shift_id);
          if(shift_category == 1){
            day_count++;
            if(shift_code == "EL"){
              day_count++;
            }
          }else if(shift_category == 2){
            night_count++;
          }else{
            //do nothing
          }
          if(sort_order == 3){
            if(shift_category == 1){
              nurse_day_count++;
            }else if(shift_category == 2){
              nurse_night_count++;
            }else{
              //do nothing
            }
          }
          if(gender == "M"){
            male_nurse_count++;
          }else{
            female_nurse_count++;
          }
        }
      });
      showMaleAndFemaleNurseCount(male_nurse_count,female_nurse_count,i);
      changeNurseCountColor(nurse_day_count,nurse_night_count,i);
      changeColor(day_count,night_count,i);
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
    function addDate(date){
    var date = new Date(date),
    days = 1;
    date.setDate(date.getDate() + days);
    let next_date = JSON.stringify(date)
    next_date = next_date.slice(1,11);
    return(next_date);
  }
    function checkOverworking(params){
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

      var select_id = $(this).attr('data-count');
      var shift_category = findShiftCat($(this).val()); 
      $(this).attr("data-cat",shift_category);
      changeColorOnSelectboxChange(select_id);
      var id = this.id;
      var splitArr =  id.split("_");
      var userId = splitArr[1];
      var Index = splitArr[2];
      var sum=0,suminit=0;sum2=0;
      var unpaid_breakhours = 0;
      var deducted_hours;
      var totalH=0;
      var totalM=0;
      var newSum=0;
      var newSum2=0;
      var user_weeklyhours = parseFloat($('.user_totalhours_'+userId).attr('data-hours').replace(':', '.')); 
      for (i = 0; i <= 6; i++) {
        $('#shift_'+userId+'_'+i).css("background","none");
      }
      for (i = 0; i <= 6; i++) {
        if($('#shift_'+userId+'_'+i). length){
     
     
     suminit = parseFloat($('option:selected', $('#shift_'+userId+'_'+i)).attr('data-hours').replace(':', '.')).toFixed(2); 
           // First simply adding all of it together, total hours and total minutes
     var sumtime = suminit.toString().split('.');  

        totalH += parseInt(sumtime[0]);
        totalM += parseInt(sumtime[1]);
 
     // If the minutes exceed 60
    if (totalM >= 60) {
        // Divide minutes by 60 and add result to hours
        totalH += Math.floor(totalM / 60);
        // Add remainder of totalM / 60 to minutes
        totalM = totalM % 60;
    }
    sum=  totalH + "." + totalM;
    sum2 = parseFloat(sum);
    newSum = (totalH * 60 + totalM)/60;
    newSum2 = parseFloat(newSum);
     if(newSum2 > user_weeklyhours ){
 
            $('#shift_'+userId+'_'+i).css("background","#FFA07A");
     } 
     else{
            $('#shift_'+userId+'_'+i).css("background","none");
     }
 
          unpaid_breakhours += parseFloat($('option:selected', $('#shift_'+userId+'_'+i)).attr('data-breakhours').replace(':', '.'));
         
        }
      } 


 

      if(newSum2==0){
        $('#totalhours_'+userId).html(newSum2.toFixed(1));

          $('#totalhours_'+userId).css("color","red");
        }
        else{  
          deducted_hours = newSum2 - unpaid_breakhours;  
          $('#totalhours_'+userId).html(deducted_hours.toFixed(1));
          $('#totalhours_'+userId).css("color","");
          $('#totalhours_'+userId).css("color","#54667a");
           
        }
        if(newSum2<user_weeklyhours)
        {
           $('#totalhours_'+userId).css("color","red");
        }
     });
  initResourceHours();
  addButtons();
  function findDatesBetnDates(startDate,endDate){
    var dates = [];
    dates.push(startDate,endDate);
    var currDate = moment(startDate).startOf('day');
    var lastDate = moment(endDate).startOf('day');
    while(currDate.add(1, 'days').diff(lastDate) < 0) {
      var cur_date = currDate.clone().toDate();
      date = moment(cur_date).format('YYYY-MM-DD');
      dates.push(date);
    }
    return dates;
  }
  function initResourceHours(){
    user_shift_array = [];    
    $.each(weekEvents, function(index,jsonObject){
      var user_weeklyhours = parseFloat($('.user_totalhours_'+jsonObject.resourceId).attr('data-hours').replace(':', '.')); 
      var sum = 0; suminit=0;sum2=0;
      var unpaid_breakhours = 0;
      var deducted_hours;
      var totalH=0;
      var totalM=0;
      var newSum=0;
      var newSum2=0;
      for (i = 0; i <= 6; i++) {
        suminit = parseFloat($('option:selected', $('#shift_'+jsonObject.resourceId+'_'+i)).attr('data-hours').replace(':', '.')).toFixed(2); 
        unpaid_breakhours += parseFloat($('option:selected', $('#shift_'+jsonObject.resourceId+'_'+i)).attr('data-breakhours'));
        var sumtime = suminit.toString().split('.');  
        totalH += parseInt(sumtime[0]);
        totalM += parseInt(sumtime[1]);
        // If the minutes exceed 60
        if (totalM >= 60) {
          // Divide minutes by 60 and add result to hours
          totalH += Math.floor(totalM / 60);
          // Add remainder of totalM / 60 to minutes
          totalM = totalM % 60;
        }
        sum=  totalH + "." + totalM;
        sum2 = parseFloat(sum);
        newSum = (totalH * 60 + totalM)/60;
        newSum2 = parseFloat(newSum);
        // if(sum2 > user_weeklyhours )
        if(newSum2 > user_weeklyhours )
        {
          $('#shift_'+jsonObject.resourceId+'_'+i).css("background","#FFA07A");
        } 
        else
        {
          $('#shift_'+jsonObject.resourceId+'_'+i).css("background","none");
        }
      }
      // if(sum==0){
      if(newSum==0){
        // $('#totalhours_'+jsonObject.resourceId).html(sum);
        $('#totalhours_'+jsonObject.resourceId).html(newSum);
        $('#totalhours_'+jsonObject.resourceId).css("color","red");
      }
      else
      {
        // deducted_hours = sum - unpaid_breakhours; 
        deducted_hours = newSum - unpaid_breakhours; 
        $('#totalhours_'+jsonObject.resourceId).html(deducted_hours.toFixed(1)); 
        $('#totalhours_'+jsonObject.resourceId).css("color","#54667a");
      }
    });
    $(".eventcls").each(function() {
      var class_name = $(this).attr('class').split(' ')[1];
      var shift_id = $(this).find('option:selected').val();
      var data_object = {
        class_name : class_name,
        shift_id : shift_id
      };
      user_shift_array.push(data_object);
    });
  } 
  $(document).on('click', '.pu-button', function (e) {
    event.preventDefault();
    var $this = $(this);
    var weekShifts = [];
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
      event.shift_dayname = $('#shift_'+value.resourceId+'_'+increment).attr('data-dayname');
      event.shiftcat = findShiftCat(event.shift_id);
      event.user_id = value.resourceId;            
      event.start = startDate;
      event.unit_id = value.unit_id; 
      event.from_unit = value.from_unit;
      weekShifts.push(event);
      increment++;
    });    

    wShifts = JSON.stringify(weekShifts);       
    var rota=$('#rota_settings').val();  console.log(rota);  
    $("#img-loader-data").show();
    if(weekShifts.length > 0){
      var rota_id = $("#rota_id").val();
      $('#img-loader-data').show();
      $.ajax({
        url:baseURL+'admin/rota/saveShift',
        type:'post',
        dataType:'json',
        delay: 250,
        data: {
          shiftDetails : wShifts,
          rota_id : rota_id,
          rota:rota
        },
        success: function (data) {
          $("#img-loader-data").hide();
          swal({
            title : "",
            text: "Rota is saved and pubished",
            icon: "warning",
            button: "ok",
          });
        }
      });
    }else{
      //Do nothing
    } 
  });
  $(document).on('click', '.un-button', function (e) {
    event.preventDefault();
    var $this = $(this);
    var weekShifts = [];
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
      event.shift_dayname = $('#shift_'+value.resourceId+'_'+increment).attr('data-dayname');
      event.shiftcat = findShiftCat(event.shift_id);
      event.user_id = value.resourceId;            
      event.start = startDate;
      event.unit_id = value.unit_id; 
      event.from_unit = value.from_unit;
      weekShifts.push(event);
      increment++;
    });             
    wShifts = JSON.stringify(weekShifts);   
    var rota=$('#rota_settings').val(); 
    $("#img-loader-data").show();
    if(weekShifts.length > 0){
      var rota_id = $("#rota_id").val();
      $('#img-loader-data').show();
      $.ajax({
        url:baseURL+'admin/rota/saveShift',
        type:'post',
        dataType:'json',
        delay: 250,
        data: {
          shiftDetails : wShifts,
          rota_id : rota_id,
          rota:rota
        },
        success: function (data) {
          $("#img-loader-data").hide();
          swal({
            title : "",
            text: "Rota is saved you can publish it by clicking the publish button",
            icon: "warning",
            button: "ok",
          });
          $('.un-button').hide();
          $('.un-Publish').show();
        }
      });
    }else{
      //Do nothing
    } 
  });
  $(document).on('click','.un-Publish', function (e) {
    event.preventDefault();
    var rota_id = $("#rota_id").val();
    $("#img-loader-data").show();
    $.ajax({
      url:baseURL+'admin/rota/publishSavedRota',
      type:'post',
      dataType:'json',
      delay: 250,
      data: {
        unit_id : unitID,
        rota_id : rota_id
      },
      success: function (value) {
        // $this.button('reset');
        $("#img-loader-data").hide();
        $(".shift_status_message").addClass("alert-success");
        $(".shift_status_message").removeClass("alert-warning");
        messsage = "This rota is published";
        $('.shift_status_message').html(messsage);
        swal({
          title : "",
          text: "Published rota",
          icon: "warning",
          button: "ok",
        });
        $('.un-Publish').hide();
        $('.pu-button').show();
      }
    });
  });
  function findDesName(designation_id){
    var designaton_name = '' ;
    $.each(designaton_names, function(key,value) {
      if(key == designation_id){
        designaton_name = value;
      }        
    });
    return designaton_name;
  }
  function showDesigationName(){
    $(".fc-divider").each(function() {
      var designation_id = $(this).find('span.fc-cell-text').text();
      if(designation_id){
        if($(this).find('span.fc-cell-text').text() == designation_id){
          var des_name = findDesName(designation_id);
          $(this).find('span.fc-cell-text').text(des_name)
        }
      }
    });
  }
  showDesigationName();
});
