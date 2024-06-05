$(document).ready(function() {
  var otherunitschedule;
 
  function getRotadetails(start_date,rotabc)
  {
    var sdata;
    $.ajax({
                        url:baseURL+'admin/rota/getRotadetails',
                        type:'post',
                        dataType:'json',
                        async:false,
                      
                        data: {
                           date:start_date,
                           unit_id:unitID,
                           rotabc:rotabc
                        },
                        success: function (data) {
                          sdata=data;
                          // showHolidayAndTrainings();
                          
                         
                        }
                      });
    return sdata;
  }

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
      resourceOrder : 'gender,title',
    height: 1200,
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
              var weekdayfullnames = ["sunday","monday","tuesday","wednesday","thursday","friday","saturdy"];
              var replaceWith = 'shift_'+resourceID+'_'+weekDay;
              element.find('#'+replaceWith).addClass("shift_"+resourceID+"_"+dates[weekDay]);
              element.find('#'+replaceWith).addClass("select_color_"+days[weekDay]);
              element.find('#'+replaceWith).attr("data-count",days[weekDay]);
              element.find('#'+replaceWith).attr("data-dayname",weekdaynames[weekDay]);
              element.find('#'+replaceWith).addClass("select_offday_"+resourceID+"_"+weekdayfullnames[weekDay]);
              $("#img-loader-data").hide();
            },
            eventAfterAllRender:function(view){
              
                otherunitschedule='';
                otherunitschedule=getRotadetails(start_date,rotabc);
                if(otherunitschedule!=null)
                { 
                  var other_i;
                  var text;

                  for (other_i = 0; other_i< otherunitschedule.length; other_i++) {
                  text = otherunitschedule[other_i];
                  var replaceTo='shift_'+otherunitschedule[other_i]['id']+'_'+otherunitschedule[other_i]['date']; 

                 // view.el.find('.'+replaceTo).after('<h4 style="margin-top: 10px;">'+otherunitschedule[other_i]['title']+'</h4>');
                  view.el.find('.'+replaceTo).after('<h4 class="shift_'+otherunitschedule[other_i]['date']+'_'+otherunitschedule[other_i]['id']+'" data-shiftid="'+otherunitschedule[other_i]['shift_id']+'" data-start="'+otherunitschedule[other_i]['start_time']+'" data-end="'+otherunitschedule[other_i]['end_time']+'" style="margin-top: 10px;">'+otherunitschedule[other_i]['title']+'</h4>');

                  view.el.find('.'+replaceTo).remove(); 
                  } 
                  
                //    view.el.find('.'+replaceTo).after('<h4 style="margin-top: 10px;">'+otherunitschedule[other_i]['title']+'</h4>');
                //    view.el.find('.'+replaceTo).remove();
              }
              showHolidayAndTrainings();
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
      $(labelName).css("font-size", "16px");
      $(labelName).css("font-weight", "regular");
      
  
      var label = labelTds[1];
      var str = $(label).find('.fc-cell-text').text();
        if (resourceObj.totalhours!='')
        {
          $(label).find('.fc-cell-text').addClass("totalhours");
          $(label).find('.fc-cell-text').addClass("user_totalhours_"+resourceObj.id);
          $(label).find('.fc-cell-text').attr("data-hours",resourceObj.user_weeklyhours);
          $(label).find('.totalhours').append('&nbsp;&nbsp;<strong style="font-size:14px;"  id=\"totalhours_'+resourceObj.id+'\">0</strong>');
        }
    }

   });
    var date = moment($("#end_date").val(), "YYYY-MM-DD"); 
    var start_date=$("#start_date").val();
    $("#calendar").fullCalendar( 'gotoDate', date );
    function addButtons(){
      var array = getArray();
      var html = '';
      var count = 0;
      /*html += '<br><label style="height:10px;margin-left:6px;" class="male-count" title="Day"></label>';
      html += '<br><label style="height:10px;margin-left:6px;" class="female-count" title="Day"></label><br>';*/
      html += '<br><span class="early_shift" style="font-weight: bold;"></span>';
      html += '<br><span class="late_shift" style="font-weight: bold;"></span>';
      html += '<br><span class="night_shift" style="font-weight: bold;"></span><br>';
      html += '<button style="width: 18%;font-size: 10px;color: white;height:20px;" class="day-color" disabled="" title="Day"></button>';
      html += '<button style="width: 18%;font-size: 10px;color: white;height:20px;" class="night-color" disabled="" title="Night"></button>';
      html += '<button style="width: 18%;font-size: 10px;color: white;height:20px;" class="day-nurse-color" disabled="" title="Day Shift Nurse Count"></button>';
      html += '<button style="width: 18%;font-size: 10px;color: white;height:20px;" class="night-nurse-color" disabled="" title="Night Shift Nurse Count"></button>';
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
        var early_shift_count = 0;
        var late_shift_count = 0;
        var earlylate_shift_count = 0;
        var night_shift_count = 0;
        $('.select_color_'+i).each(function(){
          // var count_status = $(this).attr('data-status');
          var des_code = $(this).attr('data-descode');
          var sort_order = parseInt($(this).attr('data-sortoder'));
          var gender = $(this).attr('data-gender');
          var shift_code = $(this).attr('data-shiftcode');
          var part_number = parseInt($(this).find("option:selected").attr('data-partnumber'));
          var shift_type = parseFloat($(this).find("option:selected").attr('data-shifttype'));
          if(part_number == 1){
            var shift_id = $(this).val();
            shift_category = parseInt(findShiftCat(shift_id));
            if(shift_category == 1 || shift_category == 3 || shift_category == 4 ){
              day_count = day_count + shift_type;
            }else if(shift_category == 2){
              night_count = night_count + shift_type;
            }else{
              //do nothing
            }
            if(sort_order == 1){
              if(shift_category == 1 || shift_category == 3 || shift_category == 4 ){
                nurse_day_count = nurse_day_count + shift_type;
              }else if(shift_category == 2){
                nurse_night_count = nurse_night_count + shift_type;
              }else{
                //do nothing
              }
            }
            if(gender == "M"){
              male_nurse_count++;
            }else{
              female_nurse_count++;
            }

            if(shift_category == 1){
              early_shift_count++;
              late_shift_count++;
            }else if(shift_category == 2){
              night_shift_count++;
            }else if(shift_category == 3){
              early_shift_count++;
            }else if(shift_category == 4){
              late_shift_count++;
            }else{
              //nthng
            }
          }
        });
        showMaleAndFemaleNurseCount(male_nurse_count,female_nurse_count,i);
        changeNurseCountColor(nurse_day_count,nurse_night_count,i);
        changeColor(day_count,night_count,i);
        showELNCount(
          early_shift_count,
          late_shift_count,
          night_shift_count,i
        );
      }
    }
    function showELNCount(early_shift_count,late_shift_count,night_shift_count,i){
      $(".change-color-"+i).find(".early_shift").html("E-"+early_shift_count);
      $(".change-color-"+i).find(".late_shift").html("L-"+late_shift_count);
      $(".change-color-"+i).find(".night_shift").html("N-"+night_shift_count);
    }
    function showMaleAndFemaleNurseCount(male_nurse_count,female_nurse_count,i){
      $(".change-color-"+i).find(".male-count").html('Male : '+male_nurse_count);
      $(".change-color-"+i).find(".female-count").html('Female : '+female_nurse_count);
    }
    function changeNurseCountColor(day_count,night_count,i)
    {      
      var nurse_day_count = $("#nurse_day_count").val();
      var nurse_night_count = $("#nurse_night_count").val();
      $(".change-color-"+i).find(".day-nurse-color").text(day_count);
      $(".change-color-"+i).find(".night-nurse-color").text(night_count);
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
      $(".change-color-"+i).find(".day-color").text(day_count);
      $(".change-color-"+i).find(".night-color").text(night_count);
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
      var early_shift_count = 0;
      var late_shift_count = 0;
      var earlylate_shift_count = 0;
      var night_shift_count = 0;
      $('.select_color_'+i).each(function(){
        // var count_status = $(this).attr('data-status');
        var des_code = $(this).attr('data-descode');
        var sort_order = parseInt($(this).attr('data-sortoder'));
        var gender = $(this).attr('data-gender');
        var shift_code = $(this).attr('data-shiftcode');
        var part_number = parseInt($(this).find("option:selected").attr('data-partnumber'));
        var shift_type = parseFloat($(this).find("option:selected").attr('data-shifttype'));
        if(part_number == 1){
          var shift_id = $(this).val();
          shift_category = parseInt(findShiftCat(shift_id));
          if(shift_category == 1 || shift_category == 3 || shift_category == 4 ){
            day_count = day_count + shift_type;
          }else if(shift_category == 2){
            night_count = night_count + shift_type;
          }else{
            //do nothing
          }
          if(sort_order == 1){
            if(shift_category == 1 || shift_category == 3 || shift_category == 4 ){
              nurse_day_count = nurse_day_count + shift_type;
            }else if(shift_category == 2){
              nurse_night_count = nurse_night_count + shift_type;
            }else{
              //do nothing
            }
          }
          if(gender == "M"){
            male_nurse_count++;
          }else{
            female_nurse_count++;
          }

          if(shift_category == 1){
            early_shift_count++;
            late_shift_count++;
          }else if(shift_category == 2){
            night_shift_count++;
          }else if(shift_category == 3){
            early_shift_count++;
          }else if(shift_category == 4){
            late_shift_count++;
          }else{
            //nthng
          }
        }
      });
      showMaleAndFemaleNurseCount(male_nurse_count,female_nurse_count,i);
      changeNurseCountColor(nurse_day_count,nurse_night_count,i);
      changeColor(day_count,night_count,i);
      showELNCount(
        early_shift_count,
        late_shift_count,
        night_shift_count,i
      );
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
      var start = moment().format(start_time); //console.log(start);
      var end= moment().format(end_time); //console.log(end);
      // pass in hours as the second parameter to the diff function
      //const differenceInHours = moment(end).diff(start, 'hours');
      var  differenceInMin = moment(end).diff(start, 'minutes');
      var hours = Math.floor(differenceInMin / 60);
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
    function checkOverworking(params){ //console.log(params);
      moment().tz("Europe/London").format();
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
      var messsage = "There should be a "+$shift_gap+" hour difference between shifts."
      if(params.index == 0){
        if(params.previous_day_message){
          messsage = messsage + params.previous_day_message;
        }
      }else if(params.index == 6){
        if(params.next_day_message){
          messsage = messsage + params.next_day_message;
        }
      }
      if(p_status == true && n_status==true){
      }else if(p_status == false && n_status == false){
        if(p_day_difference < $shift_gap || n_day_difference < $shift_gap){
          alert(messsage);
          $(".shift_"+params.c_user_id+"_"+params.current_date).val(default_shift);
        }
      }else if(p_status == false && n_status == true){
        if(p_day_difference < $shift_gap){
          alert(messsage);
          $(".shift_"+params.c_user_id+"_"+params.current_date).val(default_shift);
        }
      }else if(n_status == false && p_status == true){
        if(n_day_difference < $shift_gap){
          alert(messsage);
          $(".shift_"+params.c_user_id+"_"+params.current_date).val(default_shift);
        }
      }else{
        //Nothing
      }
      
      /*if(params.index == 0){
        var m_time = moment(params.p_shift_endtime, 'hh:mm');
        console.log("----index0start-----");
        console.log("previous day shift end time="+params.c_shift_endtime);
        console.log(m_time);
        console.log("----index0end-----");
        console.log("twelve="+twelve);
        if(m_time.isBefore(twelve))
        {
        	console.log("isBefore12");
          var c_day_endtime_date = addDate(params.p_date)+" "+params.p_shift_endtime;
        }
        else
        {
        	console.log("isAfter12");
          var c_day_endtime_date = params.p_date+" "+params.p_shift_endtime;
        }
        var n_day_starttime_date = params.n_date+" "+params.n_shift_starttime;
        
        var n_day_difference = checkTimeDifference(c_day_endtime_date,n_day_starttime_date); //console.log("diff-"+n_day_difference);
        n_day_difference=n_day_difference+1;
        var status = checkZeroHours(params.p_shift_endtime,params.n_shift_starttime);
        if(status == false)
        {if(n_day_difference < $shift_gap){
          	console.log("1 "+" n_day_difference="+n_day_difference+" shift_gap="+$shift_gap) 

                alert("There should be a "+$shift_gap+" hour difference between shifts.(1)");
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
              { 
            	  if(p_day_difference < $shift_gap){
                	console.log("2 "+" n_day_difference="+n_day_difference+" shift_gap="+$shift_gap) 

                      alert("There should be a "+$shift_gap+" hour difference between shifts.(2)");
                      $(".shift_"+params.c_user_id+"_"+params.current_date).val(default_shift);
                   }
            }
      }else{
        
      }*/
    }
    function getShiftTimigs(date,user_id){
      var result = {};
      for (var i = 0; i < prev_and_next_shifts.length; i++) {
        if(prev_and_next_shifts[i].user_id == user_id){
          if(prev_and_next_shifts[i].date == date){
            result = {
              'start_time' : prev_and_next_shifts[i].start_time,
              'end_time' : prev_and_next_shifts[i].end_time,
              'shift_shortcut' : prev_and_next_shifts[i].shift_shortcut
            }

          }
        }
      }
      return result;
    }
    $(document).on('change', 'select.eventcls', function (e) { //console.log(e);
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

      //newly added on  feb 4th 2021
      let cur_date = JSON.stringify(current_date);
      cur_date = cur_date.slice(1,11);

      var new_date=CheckdaylightIssue(cur_date,n_date); 


        
      // if(n_date=='2021-03-28' && cur_date=='2021-03-28')
      // {
      //   n_date='2021-03-29';
      // }
      // else
      // {
      //   n_date=n_date;
      // } 

      n_date=new_date; 

      let p_date = JSON.stringify(prev_day)
      p_date = p_date.slice(1,11); 
      var selected_shift = $(this).find('option:selected').val();
      var c_date = JSON.stringify(current_date);
      c_date = c_date.slice(1,11);
      var previous_day_message = '';
      var next_day_message = '';
      var c_shift_starttime = $(this).find('option:selected').attr('data-stime');
      var c_shift_endtime = $(this).find('option:selected').attr('data-etime');
      var p_shift_starttime = $(".shift_"+c_user_id+"_"+p_date).find('option:selected').attr('data-stime');
      var p_shift_endtime = $(".shift_"+c_user_id+"_"+p_date).find('option:selected').attr('data-etime');
      //Getting value in case of shift from other unit. In this case took value
      //from the span attributes
      if(p_shift_starttime == undefined){
        p_shift_starttime = $(".shift_"+p_date+"_"+c_user_id).attr('data-start');
      }
      if(p_shift_endtime == undefined){
        p_shift_endtime = $(".shift_"+p_date+"_"+c_user_id).attr('data-end');
      }
      var n_shift_starttime = $(".shift_"+c_user_id+"_"+n_date).find('option:selected').attr('data-stime');
      var n_shift_endtime = $(".shift_"+c_user_id+"_"+n_date).find('option:selected').attr('data-etime');
      //Getting value in case of shift from other unit. In this case took value
      //from the span attributes
      if(n_shift_starttime == undefined){
        n_shift_starttime = $(".shift_"+n_date+"_"+c_user_id).attr('data-start');
      }
      if(n_shift_endtime == undefined){
        n_shift_endtime = $(".shift_"+n_date+"_"+c_user_id).attr('data-end');
      }
      //When we try to get the values for the dropdown in 0 index position
      //we have to take it from the previous week rota. So here we call a 
      //function getShiftTimigs which loop through an array and return values
      //if the user have a previous week rota
      if(p_shift_starttime == undefined)
      {
        var result = getShiftTimigs(p_date,c_user_id);
        if(result.start_time != undefined){
          previous_day_message = "Your previous day("+corectDateFormat(p_date)+") shift is "+result.shift_shortcut;
          p_shift_starttime = result.start_time;
        }else{
          p_shift_starttime = "00:00:00";
        }
        
      }

      if(p_shift_endtime == undefined)
      {
        var result = getShiftTimigs(p_date,c_user_id);
        if(result.end_time != undefined){
          previous_day_message = "Your previous day("+corectDateFormat(p_date)+") shift is "+result.shift_shortcut;
          p_shift_endtime = result.end_time;
        }else{
          p_shift_endtime = "00:00:00";
        }
      }
      //When we try to get the values for the dropdown in 7 index position
      //we have to take it from the next week rota. So here we call a 
      //function getShiftTimigs which loop through an array and return values
      //if the user have a next week rota
      if(n_shift_starttime == undefined)
      {
       // console.log("if(n_shift_starttime == undefined)");
        var result = getShiftTimigs(n_date,c_user_id);
       // console.log(result.start_time)
        if(result.start_time != undefined)
        {
          next_day_message = "Your next day("+corectDateFormat(n_date)+") shift is "+result.shift_shortcut;
          n_shift_starttime=result.start_time;}else{
          n_shift_starttime = "00:00:00";
        }
      }
      if(n_shift_endtime == undefined)
      {
        var result = getShiftTimigs(n_date,c_user_id);
        if(result.end_time != undefined)
        {
          next_day_message = "Your next day("+corectDateFormat(n_date)+") shift is "+result.shift_shortcut;
          n_shift_endtime=result.end_time;}else{
          n_shift_endtime = "00:00:00";
        }
      }
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
        n_shift_endtime : n_shift_endtime,
        next_day_message : next_day_message,
        previous_day_message : previous_day_message
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
      var t_break_hour = 0;
      var t_break_minutes = 0;
      var total_break_hours = 0;
      var total_break_hours2 = 0;
      var overtime = 0;
      //Added by chinchu on 19th feb 2020
      var user_weeklyhours = 0; //console.log(jsonObject.resourceId);
      if($('.user_totalhours_'+userId).attr('data-hours'))
      {
        user_weeklyhours = convertUserWeeklyHours(parseFloat($('.user_totalhours_'+userId).attr('data-hours').replace(':', '.')).toFixed(2));
      }
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
            sum =  totalH + "." + totalM;
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
  function corectDateFormat(date){
    var date_array = date.split("-");
    var date_with_slash = date_array[2]+"/"+date_array[1]+"/"+date_array[0];
    return date_with_slash;
  }

function CheckdaylightIssue(changed_date,n_date)
{
    
    var c_arr = changed_date.split('-'); //changed date
    var d = new Date(c_arr[0], c_arr[1], 0); 
    var last_sunady_day=d.getDate() - (d.getDay() - 0);  // finiding last sunday of a month 


    var t= new Date(changed_date);
    var last_day=(new Date(t.getFullYear(), t.getMonth() + 1, 0, 23, 59, 59)); // last day of month
 



    if(last_sunady_day==c_arr[2])
    {
        if(c_arr[1]=='03')
        {
            if(last_day==c_arr[2])
            {
              $month=+c_arr[1] + 1;   
              $day=+c_arr[2] + 1; 
            }
            else
            {
              $month=c_arr[1];
              $day=+c_arr[2] +  1; 
            }

           $new_date=c_arr[0]+'-'+$month+'-'+$day; //console.log($new_date);

        }
        else
        {
            $new_date=n_date;
        }
    }
    else
    {
        $new_date=n_date;
    }

   // console.log($new_date);

    return $new_date;
  
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
  function showColorOnSundays(){
    $(".eventcls").each(function() {
      var target_id = $(this).attr('id');
      if(target_id){
        var index = target_id.split('_')[2];
        var user_id = target_id.split('_')[1];
        if(index == 0){
          var target_class = $(this).attr('class');
          var target_class_array = target_class.split(' ');
          var date = target_class_array[1].split('_')[2];
          var d = new Date(date);
          d.setDate(d.getDate() - 1);
          var prev_date = d.toISOString().slice(0,10);
          var result = getPreviousShift(prev_date,user_id);
          if(result.shift_id){
            $(".shift_"+user_id+"_"+date).parent().find('div.fc-content').addClass('color-dot');
          }
        }
      }
    });
  }
  function getPreviousShift(date,user_id){
    var result = {};
    for (var i = 0; i < all_previous_shifts.length; i++) {
      if(all_previous_shifts[i].user_id == user_id){
        if(all_previous_shifts[i].date == date){
          result = {
            'start_time' : all_previous_shifts[i].start_time,
            'end_time' : all_previous_shifts[i].end_time,
            'shift_shortcut' : all_previous_shifts[i].shift_shortcut,
            'shift_id' : all_previous_shifts[i].shift_id
          }
        }
      }
    }
    return result;
  }
  function initResourceHours(){
    user_shift_array = [];    
    $.each(weekEvents, function(index,jsonObject){ //console.log(jsonObject.resourceId);
      //Added by chinchu on 19th feb 2020
      var user_weeklyhours = 0;
      if($('.user_totalhours_'+jsonObject.resourceId).attr('data-hours'))
      {
        user_weeklyhours = convertUserWeeklyHours(parseFloat($('.user_totalhours_'+jsonObject.resourceId).attr('data-hours').replace(':', '.')).toFixed(2));
      } 
      var sum = 0; suminit=0;sum2=0;
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
      // if(sum==0){
      if(newSum2==0){
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
  $(document).on('click', '.pu-button', function (e) {//console.log('save and publish rota');
  event.preventDefault();
    
    var rotaId = $("#rota_id").val();
    var unitId = $("#unit_id").val();
    var sDate = $("#start_date").val();
    var eDate = $("#end_date").val();
    var sessionId = $("#session_id").val();
    var saveStatus=0;
    //check rota if exist
    $.ajax({
        url:baseURL+'admin/rota/validateRota',
        type:'post',
        dataType:'json',
        async:false,
      
        data: {
           sDate:sDate,eDate:eDate,
           unitID:unitID,
           rotaId:rotaId
        },
        success: function (data) {  //console.log(data);
        	saveStatus=data;
        	if(saveStatus==0 || saveStatus==2){
        		 swal({
     	            title : "",
     	            text: "Please try again. Error saving rota.",
     	            icon: "warning",
     	            button: "ok",
     	          });
        	}         
        }
      });
    
    if(rotaId!='' && unitId!='' && sDate!='' && eDate!='' && saveStatus==1 && sessionId > 0){
    
	    var $this = $(this);
	    var weekShifts = [];
	    var eventsFromCalendar = $('#calendar').fullCalendar( 'clientEvents');
	    var increment=0;
	   //console.log(eventsFromCalendar);
	    $.each(eventsFromCalendar, function(index,value) {
	      var event = new Object();
	
	     // console.log(index+' index');
	
	      var sdate = value.start.toDate();
	      var startDate =  moment(sdate).format('YYYY-MM-DD'); 
	
	            //console.log(sdate);
	                  //console.log(startDate);
	      if(index % 7 === 0){
	        increment=0;
	      } //console.log(increment+' increment');
        if($('option:selected', $('#shift_'+value.resourceId+'_'+increment)).val()){
          event.c_shift_id = $('option:selected', $('#shift_'+value.resourceId+'_'+increment)).val();
        }else{
          event.c_shift_id = $('.shift_'+startDate+'_'+value.resourceId).attr('data-shiftid');
        }
        event.shift_id = $('option:selected', $('#shift_'+value.resourceId+'_'+increment)).val();
	      event.shift_hours = $('option:selected', $('#shift_'+value.resourceId+'_'+increment)).attr('data-hours'); 
	      event.shift_dayname = $('#shift_'+value.resourceId+'_'+increment).attr('data-dayname');
	      event.shiftcat = findShiftCat(event.shift_id);
	      event.user_id = value.resourceId;            
	      event.start = startDate;
	      event.unit_id = value.unit_id; 
	      event.from_unit = value.from_unit;
	      event.saved_unitid = $('#shift_'+value.resourceId+'_'+increment).attr('data-sunitid');
	      weekShifts.push(event);
	      increment++;
	    });    
	
	    wShifts = JSON.stringify(weekShifts);   //console.log(wShifts);    
	    var rota=$('#rota_settings').val();
      user_shift_array = [];
	    $("#img-loader-data").show();
	    if(weekShifts.length > 0){
	      var rota_id = $("#rota_id").val();
	      var session_id = $("#session_id").val();
        var current_date = moment().format("YYYY-MM-DD");
	      $('#img-loader-data').show();
	      $.ajax({
	        url:baseURL+'admin/rota/saveShift',
	        type:'post',async:false,
	        dataType:'json',
	        delay: 250,
	        data: {
	          shiftDetails : wShifts,
	          rota_id : rota_id,
	          rota:rota,
	          session_id : session_id,
            unit_id : unitId,
            current_date : current_date,
            sDate : sDate,
            eDate : eDate
	        },
	        success: function (data) {
            $(".eventcls").each(function() {
              var class_name = $(this).attr('class').split(' ')[1];
              var shift_id = $(this).find('option:selected').val();
              var data_object = {
                class_name : class_name,
                shift_id : shift_id
              };
              user_shift_array.push(data_object);
            });
           // console.log(user_shift_array)
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
    
    }
  });
  $(document).on('click', '.un-button', function (e) { //console.log('save');
  event.preventDefault();
    
    var rotaId = $("#rota_id").val();
    var unitId = $("#unit_id").val();
    var sDate = $("#start_date").val();
    var eDate = $("#end_date").val();
    var saveStatus=0;
    //check rota if exist
    $.ajax({
        url:baseURL+'admin/rota/validateRota',
        type:'post',
        dataType:'json',
        async:false,
      
        data: {
           sDate:sDate,eDate:eDate,
           unitID:unitID,
           rotaId:rotaId
        },
        success: function (data) {  //console.log(data);
        	saveStatus=data;
        	if(saveStatus==0 || saveStatus==2){
        		 swal({
     	            title : "",
     	            text: "Please try again. Error saving rota.",
     	            icon: "warning",
     	            button: "ok",
     	          });
        	}         
        }
      });
    if(rotaId!='' && unitId!='' && sDate!='' && eDate!='' && saveStatus==1){

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
        if($('option:selected', $('#shift_'+value.resourceId+'_'+increment)).val()){
          event.c_shift_id = $('option:selected', $('#shift_'+value.resourceId+'_'+increment)).val();
        }else{
          event.c_shift_id = $('.shift_'+startDate+'_'+value.resourceId).attr('data-shiftid');
        }
        event.shift_id = $('option:selected', $('#shift_'+value.resourceId+'_'+increment)).val();
	      event.shift_hours = $('option:selected', $('#shift_'+value.resourceId+'_'+increment)).attr('data-hours'); 
	      event.shift_dayname = $('#shift_'+value.resourceId+'_'+increment).attr('data-dayname');
	      event.shiftcat = findShiftCat(event.shift_id);
	      event.user_id = value.resourceId;            
	      event.start = startDate;
	      event.unit_id = value.unit_id; 
	      event.from_unit = value.from_unit;
	      event.saved_unitid = $('#shift_'+value.resourceId+'_'+increment).attr('data-sunitid');
	      weekShifts.push(event);
	      increment++;
	    });             
	    wShifts = JSON.stringify(weekShifts);   //console.log(wShifts);
	    var rota=$('#rota_settings').val();
      user_shift_array = [];
	    var status=$('#status').val();
	    $("#img-loader-data").show();
	    if(weekShifts.length > 0){
	      var rota_id = $("#rota_id").val();
	      var session_id = $("#session_id").val();
	      $('#img-loader-data').show();
        var current_date = moment().format("YYYY-MM-DD");
	      $.ajax({
	        url:baseURL+'admin/rota/saveShift',
	        type:'post',async:false,
	        dataType:'json',
	        delay: 250,
	        data: {
	          shiftDetails : wShifts,
	          rota_id : rota_id,
	          rota:rota,
	          session_id : session_id,
            unit_id : unitId,
            current_date : current_date,
            sDate : sDate,
            eDate : eDate
	        },
	        success: function (data) {
            $(".eventcls").each(function() {
              var class_name = $(this).attr('class').split(' ')[1];
              var shift_id = $(this).find('option:selected').val();
              var data_object = {
                class_name : class_name,
                shift_id : shift_id
              };
              user_shift_array.push(data_object);
            });
	          $("#img-loader-data").hide();
	          swal({
	            title : "",
	            text: "Rota is saved you can publish it by clicking the publish button",
	            icon: "warning",
	            button: "ok",
	          });
	          if(status==0)
	          {
	            $('.pu-button').hide();
	            $('.un-Publish').show();
	          }
	          else
	          {
	            $('.pu-button').show();
	            $('.un-Publish').hide();
	          }
	          
	        }
	      });
	    }else{
	      //Do nothing
	    } 
    }
  });

      $(document).on('click', '.save-and-publish', function (e) {
      event.preventDefault();
      var $this = $(this);
      var weekShifts = [];
      var deletedWeeks= [];
      user_shift_array = [];
      var eventsFromCalendar = $('#calendar').fullCalendar( 'clientEvents');
      var increment=0;
      $.each(eventsFromCalendar, function(index,value) {
        var event = new Object();
        var sdate = value.start.toDate();
        var startDate =  moment(sdate).format('YYYY-MM-DD');
        if(index % 7 === 0){
          increment=0;
        }
        if($('option:selected', $('#shift_'+value.resourceId+'_'+increment)).val()){
          event.c_shift_id = $('option:selected', $('#shift_'+value.resourceId+'_'+increment)).val();
        }else{
          event.c_shift_id = $('.shift_'+startDate+'_'+value.resourceId).attr('data-shiftid');
        }
        event.shift_id = $('option:selected', $('#shift_'+value.resourceId+'_'+increment)).val();
        var saved_unitid = $('#shift_'+value.resourceId+'_'+increment).attr('data-sunitid');
        event.shift_hours = $('option:selected', $('#shift_'+value.resourceId+'_'+increment)).attr('data-hours'); 
        event.shift_dayname = $('#shift_'+value.resourceId+'_'+increment).attr('data-dayname'); 
        event.shiftcat = findShiftCat(event.shift_id);
        event.user_id = value.resourceId;            
        event.start = startDate;
        event.unit_id = value.unitId;  
        event.from_unit = value.from_unit;
        if(saved_unitid){
          event.saved_unitid = saved_unitid;
        }else{
          event.saved_unitid = null;
        }
        weekShifts.push(event);
        increment++;
      });
      //var unit_id = weekShifts[0].unit_id;
      var unit_id = unitID; //added by siva on dec 11
      var cs_date = weekShifts[0].start;
      var ce_date = weekShifts[6].start;
      var rota=$('#rota_settings').val();
      var new_status=$('#new_status').val();
      var selected_month = parseInt(new Date(ce_date).getMonth())+1;
      var selected_year = new Date(ce_date).getFullYear();
      wShifts = JSON.stringify(weekShifts);
      var session_id = $('#session_id').val();
      var userid_array_fordelete = [];
      // $("#img-loader-data").show();
      $.ajax({
        url:baseURL+'admin/rota/saveBeforePublish',
        type:'post',async:false,
        dataType:'json',
        delay: 250,
        data: {
          shiftDetails : wShifts, 
          rota:rota,unit_id:unit_id,
          new_status:new_status,
          selected_month : selected_month,
          selected_year: selected_year,
          userid_array_fordelete : userid_array_fordelete,
          session_id : session_id
        },
        beforeSend: function () { showLoader(); },
        success: function (data) {  
          $(".eventcls").each(function() {
            var class_name = $(this).attr('class').split(' ')[1];
            var shift_id = $(this).find('option:selected').val();
            var data_object = {
              class_name : class_name,
              shift_id : shift_id
            };
            user_shift_array.push(data_object);
          });
          var staff_limit = data.staff_limit;
          var details = data.result; 
          var scheduled_user_count = data.scheduled_user_count;
          // $this.button('reset');
          // $("#img-loader-data").hide();
          hideLoader();
          setHiddenVariables(details,staff_limit,scheduled_user_count);
          swal({
            title : "",
            text: "Updated and published rota for this week",
            icon: "warning",
            button: "ok",
          });        
        }
      });
    });

    $(document).on('click', '.publish', function (e) {
      event.preventDefault();
      var $this = $(this);
      showLoader();
      var session_id = $('#session_id').val();
      // $("#img-loader-data").show();
      $.ajax({
        url:baseURL+'admin/rota/getAllUnPublishedRota',
        type:'post',
        dataType:'json',
        delay: 250,
        data: {
          unit_id : unitID
        },
        success: function (data) { 
          hideLoader();
          // $("#img-loader-data").hide();
          var rota_data = data.result;
          if(rota_data.length){
            var message = htmlFormatMessage(rota_data);
            swal({
              title: "",
              text: message,
              html: true,
              customClass: 'swal-wide',
              showCancelButton: true,
              closeOnCancel : true,
              closeOnConfirm: false
            }, function (isConfirm) {
            if(isConfirm){
              var rota_ids =[];
              var end_date_array = [];
              var dates = [];
              $("input:checked").each(function () {
                rota_ids.push($(this).val());
                end_date_array.push($(this).attr("data-date"));
                dates.push($(this).attr("data-week"))
                /*if(dates ==''){
                  dates += $(this).attr("data-week");
                }else{
                  dates += ','+$(this).attr("data-week");
                }*/  
              }); 
              if(rota_ids.length > 0){
                swal.close();
                showLoader();
                // $("#img-loader-data").show();
                $.ajax({
                  url:baseURL+'admin/rota/publishData',
                  type:'post',
                  dataType:'json',
                  delay: 250,
                  data: {
                    unit_id : unitID,
                    rota_ids : rota_ids
                  },
                  success: function (value) {
                    var current_calendar_date = findCalendarStartDate(); console.log(current_calendar_date); console.log(end_date_array);console.log(end_date_array.indexOf(current_calendar_date));
                    hideLoader(); 
                    // $("#img-loader-data").hide();
                    if(end_date_array.indexOf(current_calendar_date) != -1){
                      $(".shift_status_message").addClass("alert-success");
                      $(".shift_status_message").removeClass("alert-warning");
                      messsage = "This week rota is published";
                      $('.shift_status_message').html(messsage);
                        $('.save_shift').hide();
                        $('.publish').hide();
                        $('.save-and-publish').show();
                    }
                    var message_html = '';
                    message_html += '<label>Published rota for the following week(s)</label></br>';
                    for(var i=0 ; i<dates.length;i++){
                      message_html += '<label>'+dates[i]+'</label></br>';
                    }
                    swal({
                      title : "",
                      text: message_html,
                      html: true,
                      icon: "warning",
                      button: "ok",
                    });
                  }
                });
              }else{
                swal.showInputError("Please select week");
              }
            }
            });
          }else{
            swal({
              title : "",
              text: "No saved rota found, please save before publish.",
              icon: "warning",
              button: "ok",
            });
          }
        }
      });
    });

     $(document).on('click', '.save_shift', function (e) {
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
        if($('option:selected', $('#shift_'+value.resourceId+'_'+increment)).val()){
          event.c_shift_id = $('option:selected', $('#shift_'+value.resourceId+'_'+increment)).val();
        }else{
          event.c_shift_id = $('.shift_'+startDate+'_'+value.resourceId).attr('data-shiftid');
        }
        event.shift_id = $('option:selected', $('#shift_'+value.resourceId+'_'+increment)).val();
        var saved_unitid = $('#shift_'+value.resourceId+'_'+increment).attr('data-sunitid');
        event.shift_hours = $('option:selected', $('#shift_'+value.resourceId+'_'+increment)).attr('data-hours'); 
        event.shift_dayname = $('#shift_'+value.resourceId+'_'+increment).attr('data-dayname'); 
        event.shiftcat = findShiftCat(event.shift_id);
        event.user_id = value.resourceId;            
        event.start = startDate;
        event.unit_id = value.unitId;  
        event.from_unit = value.from_unit;
        if(saved_unitid){
          event.saved_unitid = saved_unitid;
        }else{
          event.saved_unitid = null;
        }
        weekShifts.push(event);
        increment++;
      });
      //var unit_id = weekShifts[0].unit_id;
      var unit_id = unitID; //added by siva on dec 11
      user_shift_array = [];
      var cs_date = weekShifts[0].start;
      var ce_date = weekShifts[6].start;
      var rota=$('#rota_settings').val();
      var new_status=$('#new_status').val();
      var selected_month = parseInt(new Date(ce_date).getMonth())+1;
      var selected_year = new Date(ce_date).getFullYear();
      wShifts = JSON.stringify(weekShifts);
      var session_id = $('#session_id').val();
      var userid_array_fordelete = [];
      showLoader();
      // $("#img-loader-data").show();
      $.ajax({
        url:baseURL+'admin/rota/saveBeforePublish',
        type:'post',async:false,
        dataType:'json',
        delay: 250,
        data: {
          shiftDetails : wShifts, 
          rota:rota,unit_id:unit_id,
          new_status:new_status,
          selected_month : selected_month,
          selected_year: selected_year,
          userid_array_fordelete : userid_array_fordelete,
          session_id : session_id
        },
        beforeSend: function () { showLoader(); 
        },
        success: function (data) {  
          $(".eventcls").each(function() {
            var class_name = $(this).attr('class').split(' ')[1];
            var shift_id = $(this).find('option:selected').val();
            var data_object = {
              class_name : class_name,
              shift_id : shift_id
            };
            user_shift_array.push(data_object);
          });
          var staff_limit = data.staff_limit;
          var details = data.result;   
          var scheduled_user_count = data.scheduled_user_count;
          hideLoader();
          // $("#img-loader-data").hide();
          setHiddenVariables(details,staff_limit,scheduled_user_count);
          if(data.status == 1){
              swal({
                title : "",
                text: "Updated rota for this week but not published",
                icon: "warning",
                button: "ok",
              });
                     
          }else{
            if(details.length > 0){
              var published_status = details[0].published;
              if(parseInt(published_status) == 0){
                  swal({
                    title : "",
                    text: "Updated rota for this week but not published",
                    icon: "warning",
                    button: "ok",
                  });
              }else{
                swal({
                  title : "",
                  text: "Already saved and published rota",
                  icon: "warning",
                  button: "ok",
                });
              } 
            }
          }
        }
      });
    });


function htmlFormatMessage(rota){
      var html = '';
      var count=1;
      html += '<html><head></head><body><h5>You are going to publish rota for the following weeks</h5>'+
      '<table style="width:100%;border: 1px solid black;border-collapse: collapse;">'+
        '<tr>'+
          '<th style="border: 1px solid black;border-collapse: collapse;padding: 5px;text-align: left">Week</th>'+
          '<th style="border: 1px solid black;border-collapse: collapse;padding: 5px;text-align: left">From Date</th>'+
          '<th style="border: 1px solid black;border-collapse: collapse;padding: 5px;text-align: left">To Date</th>'+
          '<th style="border: 1px solid black;border-collapse: collapse;padding: 5px;text-align: left">Select Weeks</th>'+
        '</tr>';
        for(var i=0;i<rota.length;i++){
          html += '<tr>'+
          '<td style="border: 1px solid black;border-collapse: collapse;padding: 5px;text-align: left">'+count+'</td>'+
          '<td style="border: 1px solid black;border-collapse: collapse;padding: 5px;text-align: left">'+corectDateFormat(rota[i].start_date)+'</td>'+
          '<td style="border: 1px solid black;border-collapse: collapse;padding: 5px;text-align: left">'+corectDateFormat(rota[i].end_date)+'</td>'+
          '<td style="border: 1px solid black;border-collapse: collapse;padding: 5px;text-align: left"><label><input type="checkbox" name="select_week" data-week="'+corectDateFormat(rota[i].start_date)+" to "+corectDateFormat(rota[i].end_date)+'" data-date="'+rota[i].end_date+'" value="'+rota[i].id+'" class="select_week"><span class="check-box-effect select_weeks"></span></label></td>'+
          '</tr>';
          count++;
        }
      html += '</table></body></html>';
      return html;
    }

     function showStaffLimitMessage(staff_limit){
      var staff_count = parseInt(weekEvents.length);
      var staff_limit = parseInt(staff_limit);
      var messsage = "";
      if(staff_count > staff_limit){
        messsage = "This unit is overstaffed";
      }else{
        messsage = "This unit is understaffed";
      }
      $(".staff_limit_message").addClass("alert-warning");
      $('.staff_limit_message').html(messsage);
    }
    function showShiftStatusMessage(scheduled_user_count){
      var published_status = parseInt($("#shift_published_status").val());
      var messsage = "";
      var new_scheduled_user_count =[];
      if(published_status == 0){
        $('.save_shift').show();
        $('.publish').show();
        $('.save-and-publish').hide();
        $(".shift_status_message").addClass("alert-warning");
        $(".shift_status_message").removeClass("alert-success");

          if(scheduled_user_count.length != new_scheduled_user_count.length){
            messsage = "You have modified this week rota please save before publishing";
          }else{
            var array1 = scheduled_user_count;
            var array2 = new_scheduled_user_count;
            var difference = [];

            jQuery.grep(array2, function(el) {
                    if (jQuery.inArray(el, array1) == -1) difference.push(el);
            });
            if(difference.length){
              messsage = "You have modified this week rota please save before publishing";
            }else{
              messsage = "This week rota is saved. Not published yet.";
            }
          }
      
      }else{

          $('.save_shift').hide();
          $('.publish').hide();
          $('.save-and-publish').show();
          if(scheduled_user_count.length != new_scheduled_user_count.length){
            $(".shift_status_message").addClass("alert-warning");
            $(".shift_status_message").removeClass("alert-success");
            messsage = "You have modified this week rota please save and publish";
          }else{
            var array1 = scheduled_user_count;
            var array2 = new_scheduled_user_count;
            var difference = [];

            jQuery.grep(array2, function(el) {
                    if (jQuery.inArray(el, array1) == -1) difference.push(el);
            });
            if(difference.length){
              $(".shift_status_message").addClass("alert-warning");
              $(".shift_status_message").removeClass("alert-success");
              messsage = "You have modified this week rota please save and publish";
            }else{
              $(".shift_status_message").addClass("alert-success");
              $(".shift_status_message").removeClass("alert-warning");
              messsage = "This week rota is published";
            }
          }
      }
      $('.shift_status_message').html(messsage);
      $('.shift_status_message').show();
    }

    function showShiftStatusMessage(scheduled_user_count){ //console.log(scheduled_user_count.length); console.log(new_scheduled_user_count);
      var published_status = parseInt($("#shift_published_status").val());
      var messsage = "";
      var new_scheduled_user_count =[];
      if(published_status == 0){
        $('.save_shift').show();
        $('.publish').show();
        $('.save-and-publish').hide();
        $(".shift_status_message").addClass("alert-warning");
        $(".shift_status_message").removeClass("alert-success");
          
        messsage = "This week rota is saved. Not published yet.";

      }else{
          $('.save_shift').hide();
          $('.publish').hide();
          $('.save-and-publish').show();

              $(".shift_status_message").addClass("alert-success");
              $(".shift_status_message").removeClass("alert-warning");
              messsage = "This week rota is published";
      }
      $('.shift_status_message').html(messsage);
      $('.shift_status_message').show();
    }

    function showRotaStatusMessage(){
      var calendar = $('#calendar').fullCalendar('getCalendar');
      var view = calendar.view;
      var start = view.start._d;


      start.setDate(start.getDate()+6); 
      let date1 = JSON.stringify(start);
      edate = date1.slice(1,11);       

      var d = new Date(edate);
      var date = d.getDate();
      var day = d.getDay();
      var weekOfMonth = Math.ceil((date - 1 - day) / 7)+1;
      var now = new Date(edate);
      next_month = new Date(now.getFullYear(), now.getMonth()+1, 1);
      var month_names = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
      var month = parseInt(next_month.getMonth()+1);
      var year  = next_month.getFullYear();
      var sat_count = getSaturdayCount(year,month-1);
      var month_name = month_names[month-1];
      $.ajax({
        url:baseURL+'admin/rota/getNextMonthRota',
        type:'post',
        dataType:'json',
        async:true,
        data: {
          month : month,
          year  : year,
          unit_id:unitID
        },
        success: function (data) { console.log(data);
          var rota_count = data.result.length;
          var saved_rota_count = 0;
          var status;
          var pending_rota;
          var published_count = 0;
          var un_published_count = 0;
          if(rota_count > 0){
            if(sat_count > rota_count){
              status = false;
              pending_rota = parseInt(sat_count)-parseInt(rota_count);
            }else{
              status = true;
              pending_rota = rota_count;
            }
            var details = data.result;
            for(var i=0;i<rota_count;i++){
              if(details[i].published == 0){
                un_published_count++;
              }
            }
            if(status == true){
              $(".rota_status_message").hide();
            }else{
              $(".rota_status_message").addClass("alert-warning");
              $(".rota_status_message").show().html("You have "+ pending_rota +" unsaved rota in next("+month_name+") month");
            }
            if(un_published_count > 0){
              if(weekOfMonth == 2){
                $(".rota_publish_message").addClass("alert-warning");
                if(un_published_count == rota_count){
                  $(".rota_publish_message").show().html("Your next month saved rota is not published yet.");
                }else{
                  $(".rota_publish_message").show().html("Your have "+un_published_count+" unpublished rota in next("+month_name+") month.");
                }
                
              }else{
                $(".rota_publish_message").hide();
              }
            }
          }else{
            // if(weekOfMonth == 1){
              $(".rota_status_message").addClass("alert-warning");
              $(".rota_status_message").show().html("Next month rota is not saved yet.");
            /*}else{
              $(".rota_status_message").hide();
            }  */         
          }
        }
      });
    }

function setHiddenVariables(details,staff_limit,scheduled_user_count){  //console.log(details);
      if(details.length > 0){
        // if(action == 'update'){}
        var published_status = details[0].published;
        $("#shift_published_status").val(published_status);
        localStorage.removeItem("rota_id");
        localStorage.setItem("rota_id", details[0].id);
        if(parseInt(published_status) == 0){
          $("#check_copied_shift").val("true");
        }else{
          $("#check_copied_shift").val("false");
        }
        showShiftStatusMessage(scheduled_user_count);
      }else{
        $('.save_shift').show();
        $('.publish').show();
        $('.save-and-publish').hide();
        $(".shift_status_message").addClass("alert-warning");
        $(".shift_status_message").removeClass("alert-success");
        $('.shift_status_message').html("This week rota is not saved yet");
        $('.shift_status_message').show();
        $("#check_copied_shift").val("false");
        $("#shift_published_status").val(0);
      }
      // showStaffLimitMessage(staff_limit);
    }

function showLoader() {
       if($("#gressbar").is(":visible")){
        //console.log("visible"); 
         $("#progressbar").css("display", "none");
       }
       else{
              $("#progressbar").css("display", "");

       }
    }

    function hideLoader() {
        setTimeout(function () {
            $("#progressbar").css("display", "none");
        }, 1000);
    }
 function findCalendarStartDate(){
      var calendar = $('#calendar').fullCalendar('getCalendar'); //console.log(calendar);
      var view = calendar.view;
      var start = view.start._d; 
      let date = JSON.stringify(start);
      sdate = date.slice(1,11); //console.log(sdate);
      var end = view.end._i; 
      let date1 = JSON.stringify(end);
      edate = date1.slice(1,11); //console.log(edate);
      return edate;
    }
  function findDesName(designation_id){
    var designaton_name = '' ;
    $.each(designaton_names, function(key,value) {
      if(key == designation_id){
        designaton_name = value;
      }        
    });
    return designaton_name;
  }
      function getSaturdayCount(year, month){
      var day, counter, date;
      day = 1;
      counter = 0;
      date = new Date(year, month, day);
      while (date.getMonth() === month) {
        if (date.getDay() === 6) { // Sun=0, Mon=1, Tue=2, etc.
          counter += 1;
        }
        day += 1;
        date = new Date(year, month, day);
      }
      return counter;
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
  function showOffdays() {
      $.each(user_offday, function( index, value ) {
        var offday = value.offday;
        for(var i=0;i<offday.length;i++) {
          $('.select_offday_'+value.user_id+'_'+offday[i]).val(0);
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
  function getAbsentShiftIds(absent_shifts){
    var shifts_array = [];
    for(var i=0;i<absent_shifts.length;i++) {
      shifts_array.push(absent_shifts[i].id);
    }
    return shifts_array;
  }
  function showHolidayAndTrainings(){
    var all_training_dates=[];
    var all_holiday_dates=[];
    if(holidayDates && holidayDates.length >0){ 
      $.each(holidayDates, function( index, value ) {
        var dates = findDatesBetnDates(value.from_date,value.to_date);
        for(var i=0;i<dates.length;i++) {
          var shift_id = $('.shift_'+value.user_id+'_'+dates[i]).val();
          if(shift_id == undefined){
            shift_id = $('.shift_'+dates[i]+'_'+value.user_id).attr('data-shiftid');
          }
          if(shift_id == 0){
            $('.shift_'+value.user_id+'_'+dates[i]).val(1);
            $('.shift_'+dates[i]+'_'+value.user_id).text('H');
          }
        }            
      });
    }
    var shifts_array = getAbsentShiftIds(absent_shifts);
    if(trainingDates && trainingDates.length >0){
      $.each(trainingDates, function( index, value ) {
        var dates = findDatesBetnDates(value.date_from,value.date_to);
        for(var i=0;i<dates.length;i++) {
          var shift_id = $('.shift_'+value.user_id+'_'+dates[i]).val();
          if($.inArray(shift_id,shifts_array) == -1){
            $('.shift_'+value.user_id+'_'+dates[i]).val(2);
            $('.shift_'+dates[i]+'_'+value.user_id).text('T');
          }
        }            
      });
    }
  }
  showDesigationName();
  showColorOnSundays();
  showShiftStatusMessage('');
  showRotaStatusMessage();
});
