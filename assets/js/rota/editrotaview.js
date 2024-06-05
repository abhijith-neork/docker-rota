$(document).ready(function() {
  var datesWithData =[];
  var role = jobe_roles;
  $(".select2").select2();
  if(role.length > 0){
    $('.user_role').select2().val(role).trigger("change") 
  }
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
    dates.push(endDate)
    return dates;
  }
  function getArray(){
    if(rota_dates.length > 0){
      var dates_array = [];
      var array = [];
      for(var i=0;i<rota_dates.length;i++){
        var start_date = rota_dates[i].start_date;
        var end_date = rota_dates[i].end_date;
        var date_array = findDatesBetnDates(start_date,end_date);
        for(var j=0;j<date_array.length;j++){
          dates_array.push(date_array[j]);
        }
      }
      for(var i=0;i<dates_array.length;i++){
        var weekday = ["Su","Mo","Tu","We","Th","Fr","Sa"];
        var new_date = new Date(dates_array[i]);
        var day = dates_array[i].split('-')[2];
        var str = Number(day).toString();
        var day_name = weekday[new_date.getDay()];
        array.push(day_name+" "+str);
      }
      var result = {
        'dates_array' : dates_array,
        'array' : array
      }
      return result;
    }else{
      var result = {
        'dates_array' : [],
        'array' : []
      }
      return result;
    }
  }
    $(".complex-colorpicker").asColorPicker({
        mode: 'complex'
    });
    function findShiftCat(shift_id){
      var shift_category = '' ;
      $.each(shift_cats, function(key,value) {
        if(key == shift_id){
          shift_category = value;
        }        
      });
      return shift_category;
    }
  function returnUnits(id){
    var units = '' ;
    $.each(unit_ids, function(key,value) {
      if(key == id){
        units = value;
      }        
    });
    return units;
  }
  function getUnique(array){
    var uniqueArray = [];
    // Loop through array values
    for(i=0; i < array.length; i++){
      if(uniqueArray.indexOf(array[i]) === -1) {
        uniqueArray.push(array[i]);
      }
    }
    return uniqueArray;
  }
  function findShiftCount(date_array){ 
    var date_array = getUnique(date_array); 
    var selected_unit = $("#unit option:selected").val();
    var unit_array = returnUnits(selected_unit);
    var final_array = [];
    var count = 0;
    for(var j=0;j<date_array.length;j++){
      var early_shift_count = 0;
      var late_shift_count = 0;
      var earlylate_shift_count = 0;
      var night_shift_count = 0;
      count++;
      $('span.show_count[data-date="'+date_array[j]+'"]').each(function() { 
        var shift_name = $(this).attr('data-shiftname'); 
        var shift_id = parseInt($(this).attr('data-shiftid'));
        var part_number = parseInt($(this).attr('data-partnumber'));
        var shift_type = parseFloat($(this).attr('data-shifttype'));
        var unit_id = parseFloat($(this).attr('data-unitid'));
        if (Object.values(unit_array).indexOf(unit_id) > -1) {  
          if(part_number == 1){    
            var shift_category = parseInt(findShiftCat(shift_id)); 

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
          //console.log(early_shift_count);
        }
      });  
      showCount(date_array[j],
        early_shift_count,
        late_shift_count,earlylate_shift_count,
        night_shift_count,count
      );
    }    
  }
  function showCount(date,early_shift_count,late_shift_count,earlylate_shift_count,night_shift_count,i){
    $(".change-color-"+i).find(".early_shift").html("E-"+early_shift_count);
    $(".change-color-"+i).find(".late_shift").html("L-"+late_shift_count);
    $(".change-color-"+i).find(".earlylate_shift").html("EL-"+earlylate_shift_count);
    $(".change-color-"+i).find(".night_shift").html("N-"+night_shift_count);
  }
  function addButtons(date_values){  
    if(weekEvents.length > 0){
      $("#show-print-button").show();
      var result = getArray();
      var array = result.array;

      var dates_array = [];
      if(date_values.length > 0)
      {
      dates_array = date_values;
      }
      else
      {
      dates_array = result.dates_array;
      }

      var html = '';
      var count = 0;
      html += '<div><span class="early_shift" style="font-weight: bold;"></span></div>';
      html += '<div><span class="late_shift" style="font-weight: bold;"></span></div>';
      /*html += '<div><span class="earlylate_shift" style="font-weight: bold;"></span></div>';*/
      html += '<div><span class="night_shift" style="font-weight: bold;"></span></div>';
      $('.fc-widget-header').each(function(){
        var text = $(this).find($('.fc-cell-text')).text();  
        var array_status = array.includes(text);
        if(array_status == true){
          count++;
          $(this).find($('.fc-cell-text')).addClass('change-color-'+count);
          $(this).find($('.fc-cell-text')).prepend(html);
        }
      });
      findShiftCount(dates_array);
    }else{
      $("#show-print-button").hide();
    }
  }
  
  $('#calendar').fullCalendar({
    resourceGroupField: 'designation_id',
    resourceOrder : 'title',
    height: 1200, //changed from auto
    width:900,
    slotWidth:100,
    resourceAreaWidth: 350,  
    editable: false,
    eventLimit: true,filterResourcesWithEvents:true,refetchResourcesOnNavigate:true,
    aspectRatio: 3,
    scrollTime: '00:00',
    header: {
      left: 'prev,next today',
      center: 'title',
      right: 'customWeek,timelineWeek'   
    },
    titleFormat: ' ',
    eventRender: function( event, element, view ) {  
      // $('.fc-prev-button').hide();
      // $('.fc-next-button').hide();

      var calendar = $('#calendar').fullCalendar('getCalendar');
      var view = calendar.view;
      var start = view.start._d; 
      let date = JSON.stringify(start);
      sdate = date.slice(1,11); 
      var month=$('#month').val(); //selected month

      var newDate = new Date(sdate); 
      newDate.setDate(newDate.getDate() - 7); //callendar week -7
      newstartdate=formatDate(newDate); 
      var date_array = newstartdate.split('-'); //find month 
      if(month!=date_array[1])
      { //if selected month != date month
        $('.fc-prev-button').hide();
        $('.fc-next-button').show();
      }
      else
      {
        $('.fc-prev-button').show();
        $('.fc-next-button').show();
      }

      
      var title = element.find( '.fc-title' );
      title.html( title.text() ); 
       if(event.tooltip!=''){
    	var str = event.tooltip;   //console.log(str);
    	var regex = /<br\s*[\/]?>/gi;
    	var etooltip = str.replace(regex, "\n");
    	   
        element.append('<span title="'+etooltip+'" class="dot"></span>');
        var tooltip=$(view.el).tooltip({ 
            title: event.tooltip,
            placement: 'top',
            trigger: 'hover',
            container: 'body' }); 
       }
       else
       {
             element.append('<span class="dot0"></span>');
       }
     
    
    },
    eventAfterRender: function( event, element, view ) {
      //Added by chinchu 
      var from_unit = event.from_unit;
      var unit_id = event.unit_id;
      var user_unit_id = event.user_unit_id;
      var selected_unit = $("#unit option:selected").val();
      if(from_unit != null){
        if(from_unit != unit_id){
          if(selected_unit != user_unit_id){
            if(event.shift_id < 5){
              var user_id = event.resourceId;
              var date = event.start._i;
              $('.shift_'+user_id+"_"+date).parent('span').parent('div').parent('a').remove();
            }
          }
        }
      }
    },
    eventAfterAllRender:function(){
      var events = $('#calendar').fullCalendar('clientEvents');
          datesWithData = [];
          
          // Iterate through events to extract dates
          events.forEach(function(event) {
              var eventDate = event.start.format('YYYY-MM-DD');
              if (!datesWithData.includes(eventDate)) {
                  datesWithData.push(eventDate);
              }
          });
          showHolidayAndTrainings();
          initResourceHours();
    },
    defaultDate: default_date, 
    events: weekEvents, 
    firstDay: 0,
    defaultView: 'customWeek',
    views: {
      customWeek: {
        type: 'timeline',
        duration: { weeks: week },
        slotDuration: {days: 1},
        buttonText: 'Month'
      },
      timelineWeek: {
        type: 'timeline',
        duration: { weeks: week },
        slotDuration: {days: 1},
        buttonText: 'Week'
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
    resources: resources,
    resourceRender: function (resourceObj, labelTds) {
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
  user_shift_array = []; 
  $.each(resources, function(index,jsonObject){
    var user_weeklyhours = 0;
    var lengthOfSpans = $('.ind_shift_'+jsonObject.user_id).length;
    if($('.user_totalhours_'+jsonObject.user_id).attr('data-hours'))
    {
      user_weeklyhours = convertUserWeeklyHours(parseFloat($('.user_totalhours_'+jsonObject.user_id).attr('data-hours').replace(':', '.')).toFixed(2));
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
    for (i = 0; i < datesWithData.length; i++) {
      if($('.ind_shift_'+jsonObject.user_id+'_'+datesWithData[i]).attr('data-hours') != undefined){
        suminit = parseFloat($('.ind_shift_'+jsonObject.user_id+'_'+datesWithData[i]).attr('data-hours').replace(':', '.')).toFixed(2); 
        unpaid_breakhours = parseFloat($('.ind_shift_'+jsonObject.user_id+'_'+datesWithData[i]).attr('data-breakhours').replace(':', '.')).toFixed(2);
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
        /*if(overtime > user_weeklyhours )
        {
          var shift_id = $('.ind_shift_'+jsonObject.user_id+'_'+datesWithData[i]).attr('data-shiftid');
          if(zeroTargetedHoursShifts(shift_id)){
            $('.ind_shift_'+jsonObject.user_id+'_'+datesWithData[i]).css("background"," #ffc87a");
          }else{
            $('.ind_shift_'+jsonObject.user_id+'_'+datesWithData[i]).css("background","none");
          }
        } 
        else
        {
          $('.ind_shift_'+jsonObject.user_id+'_'+datesWithData[i]).css("background","none");
        }*/
      }
    }
    if(newSum2==0){
      $('#totalhours_'+jsonObject.user_id).html(newSum2);
      $('#totalhours_'+jsonObject.user_id).css("color","red");
    }
    else
    {
      deducted_hours = newSum2 - total_break_hours2; 
      $('#totalhours_'+jsonObject.user_id).html(deducted_hours.toFixed(1)); 
      $('#totalhours_'+jsonObject.user_id).css("color","#54667a");
    }
    if(newSum2<user_weeklyhours)
    {
      $('#totalhours_'+jsonObject.user_id).css("color","red");
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
// initResourceHours();
/*      date = moment("2019-12-26", "YYYY-MM-DD");
      $("#calendar").fullCalendar( 'gotoDate', date );*/
function showOffdays() {
      $.each(user_offday, function( index, value ) {
        var offday = value.offday;
        for(var i=0;i<offday.length;i++) {
          $('.select_offday_'+value.user_id+'_'+offday[i]).html('X');
        }
      });
    }
  function getAbsentShiftIds(absent_shifts){
    var shifts_array = [];
    for(var i=0;i<absent_shifts.length;i++) {
      shifts_array.push(absent_shifts[i].id);
    }
    return shifts_array;
  }
  function showHolidayAndTrainings(){
    var all_holiday_dates=[];
    if(holidayDates && holidayDates.length >0){
      $.each(holidayDates, function( index, value ) {
        var dates = findDatesBetnDates(value.from_date,value.to_date);
        for(var i=0;i<dates.length;i++) {
          var shift_id = $('.shift_'+value.user_id+'_'+dates[i]).attr('data-shiftid');
          if(shift_id == 0){
            $('.shift_'+value.user_id+'_'+dates[i]).removeClass('select_user');
            $('.shift_'+value.user_id+'_'+dates[i]).removeClass('select_shift');
            $('.shift_'+value.user_id+'_'+dates[i]).html('H');
          }
        }            
      });
    }
    var shifts_array = getAbsentShiftIds(absent_shifts);
    var all_training_dates=[];
    if(trainingDates && trainingDates.length >0){
      $.each(trainingDates, function( index, value ) {
        var dates = findDatesBetnDates(value.date_from,value.date_to);
        for(var i=0;i<dates.length;i++) {
          var shift_id = $('.shift_'+value.user_id+'_'+dates[i]).attr('data-shiftid');
          if($.inArray(shift_id,shifts_array) == -1){
            $('.shift_'+value.user_id+'_'+dates[i]).attr("data-shiftname",training_shift_details[0].shift_name);
            $('.shift_'+value.user_id+'_'+dates[i]).attr("data-shiftid",training_shift_details[0].id);
            $('.shift_'+value.user_id+'_'+dates[i]).attr("data-partnumber",training_shift_details[0].part_number);
            $('.shift_'+value.user_id+'_'+dates[i]).attr("data-shifttype",training_shift_details[0].shift_category);
            $('.shift_'+value.user_id+'_'+dates[i]).html('T');
          }
          // $('.shift_'+value.user_id+'_'+dates[i]).removeClass('select_user');
          // $('.shift_'+value.user_id+'_'+dates[i]).removeClass('select_shift');
        }            
      });
    }

    var otherunitshifts=[];

    if(UserOtherUnits && UserOtherUnits.length >0){
      $.each(UserOtherUnits, function( index, value ) {
        
          var shift_id = $('.shift_'+value.user_id+'_'+value.date).attr('data-shiftid');
            $new_shift = value.unit_shortname+'-'+value.shift_shortcut;
            $('.shift_'+value.user_id+'_'+value.date).removeClass('select_user');
            $('.shift_'+value.user_id+'_'+value.date).removeClass('select_shift');
            $('.shift_'+value.user_id+'_'+value.date).html($new_shift);          
      });
    }
  }
  function corectDateFormat(date){
    var date_array = date.split("-");
    var date_with_slash = date_array[2]+"/"+date_array[1]+"/"+date_array[0];
    return date_with_slash;
  }
  $(document).on('click', '.showPopup', function(e) { 
    $( "#frmViewRota" ).submit();
  });

  $(document).on('click', '.fc-customWeek-button', function(e) { 
        date = moment(default_date, "YYYY-MM-DD");
        $("#calendar").fullCalendar( 'gotoDate', date );
       // window.location.reload();

        $('.fc-prev-button').hide();
        $('.fc-next-button').hide();
        addButtons([]);
        showDesigationName();
  });

  $(document).on('click', '.fc-timelineWeek-button', function(e) { 
       var calendar = $('#calendar').fullCalendar('getCalendar');
       var month=$('#month').val(); //selected month from dropdown
        var view = calendar.view;
        var start = view.start._d; 
        let date = JSON.stringify(start);
        sdate = date.slice(1,11);   //start date

          var date_array = sdate.split('-'); //start date month

        var newDate = new Date(sdate); 
        newDate.setDate(newDate.getDate() + 7); //adding 7 days with start date
        newstartdate=formatDate(newDate);

         date = moment(newstartdate, "YYYY-MM-DD");
         if(month!=date_array[1])
         { //if month from start date and dropdown not equal go to new date
          $("#calendar").fullCalendar( 'gotoDate', date );
         }


          var calendar = $('#calendar').fullCalendar('getCalendar');
          var view = calendar.view;
          var start = view.start._d; 
          let date_new = JSON.stringify(start);
          sdate_new = date_new.slice(1,11); 
          var month=$('#month').val(); //selected month

          var newDate = new Date(sdate_new); 
          newDate.setDate(newDate.getDate() - 7); //callendar week -7
          newstartdate=formatDate(newDate); 
          var date_array = newstartdate.split('-'); //find month 
          if(month!=date_array[1])
          { //if selected month != date month
            $('.fc-prev-button').hide();
            $('.fc-next-button').show();
          }
          else
          {
            $('.fc-prev-button').show();
            $('.fc-next-button').show();
          }

        
 
       addButtons([]);
       showDesigationName();
  });

  function formatDate(date) {
    var d = new Date(date),
        month = '' + (d.getMonth() + 1),
        day = '' + d.getDate(),
        year = d.getFullYear();

    if (month.length < 2) 
        month = '0' + month;
    if (day.length < 2) 
        day = '0' + day;

    return [year, month, day].join('-');
}

 $(document).on('click', '.fc-prev-button', function(e) { 
    var calendar = $('#calendar').fullCalendar('getCalendar');
    var view = calendar.view;
    var start = view.start._d; 
    let date = JSON.stringify(start);
    sdate = date.slice(1,11); 
    var month=$('#month').val(); //selected month

    var newDate = new Date(sdate); 
    newDate.setDate(newDate.getDate() - 7); //callendar week -7
    newstartdate=formatDate(newDate); 
    var date_array = newstartdate.split('-'); //find month 
    if(month!=date_array[1])
    { //if selected month != date month
      $('.fc-prev-button').hide();
      $('.fc-next-button').show();
    }
    else
    {
      $('.fc-prev-button').show();
      $('.fc-next-button').show();
    }

    var end = view.end._d; 
    let date1 = JSON.stringify(end);
    edate = date1.slice(1,11);
    var dates_array = findDatesBetnDates(sdate,edate);
    addButtons(dates_array);
    showDesigationName();
});

$(document).on('click', '.fc-next-button', function(e) {
    var calendar = $('#calendar').fullCalendar('getCalendar');
    var view = calendar.view;
    var start = view.start._d; 
    let date = JSON.stringify(start);
    sdate = date.slice(1,11);

    var month=$('#month').val();

    var newDate = new Date(sdate); 
    newDate.setDate(newDate.getDate() + 7);
    newstartdate=formatDate(newDate); 
    var date_array = newstartdate.split('-');
    if(month!=date_array[1])
    {
      $('.fc-prev-button').show();
      $('.fc-next-button').hide();
    }
    else
    {
      $('.fc-prev-button').show();
      $('.fc-next-button').show();
    }

    var end = view.end._d; 
    let date1 = JSON.stringify(end);
    edate = date1.slice(1,11);
    var dates_array = findDatesBetnDates(sdate,edate);
    // $('.fc-prev-button').show();
    // $('.fc-next-button').show();
    addButtons(dates_array);
    showDesigationName();
});

  $(document).on('click', '.fc-timeline-event', function(e) { //console.log($(this).set);
    console.log(3)
    var class_attributes = $(this).find('span').children().attr('class').split(' '); //console.log($(this).find('span').children().attr('data-shiftid','521'));
    var user_id = $(this).find('span').children().attr('data-resourceid');
    var unit_id = $(this).find('span').children().attr('data-unitid');
    var date = $(this).find('span').children().attr('data-date');
    var old_shiftid = $(this).find('span').children().attr('data-shiftid');
    var shift_name = $(this).find('span').children().attr('data-shiftname');
    var rota_id = $(this).find('span').children().attr('data-rotaid');
    var rota_status = parseInt($(this).find('span').children().attr('data-rstatus'));
    var datas = $(this);//newly added on nov19 by swaraj
    var datas_attributes = $(this).find('span').children();
    var params = {
      user_id : user_id,
      unit_id : unit_id,
      date    : date,
      old_shiftid : old_shiftid,
      shift_name : shift_name,
      rota_id : rota_id,
      rota_status : rota_status,
      class_name : class_attributes[0],
      datas : datas,
      datas_attributes : datas_attributes
    }
    if($.inArray('select_user',class_attributes) >= 0){
      selectAgencyStaff(params,);
    }
    if($.inArray('select_shift',class_attributes) >= 0){
      selectShift(params);
    }
  });
  function selectShift(params){  
    var new_class = params.datas_attributes.attr('class').replace("select_shift", "select_user"); //newly added on nov19 by swaraj

    var user_id = params.user_id;
    var unit_id = params.unit_id;
    var date = params.date;
    var old_shiftid = params.old_shiftid;
    var shift_name = params.shift_name;
    var rota_status = params.rota_status;
    var html = '';
    if(login_user_designation == 10 || login_user_designation == 5){
      if(rota_status == 0){
        swal({
          title: "",
          text: "You don't have permission to mark sick or absent in an unpublished rota.",
        });
      }else{
        $.ajax({
          url: baseURL+"admin/rota/getUserName",
          type: "POST",
          async: false,
          data: {
              user_id : user_id
          },
          success: function (data) {
            html += '<html><body><form action="#">'+
            '<label for="shift"><h4>Employee Name  : '+data.name+'</h4></label></br>'+
            '<label for="shift"><h4>Date  : '+corectDateFormat(date)+'</h4></label></br>'+
            '<label for="shift"><h4>Select Shift</h4></label> : &nbsp'+
            '<select class="custom-select form-control required" id="shift" name="shift">';
            for( var i = 0; i < absent_shifts.length; i++ ){
              html += '<option data-hours="'+absent_shifts[i].hours+'" value="'+absent_shifts[i].id+'">'+absent_shifts[i].shift_shortcut+'</option>';
            }
            html += '</select></form></body></html>';
            swal({
                title: "<h3 style='text-decoration: underline;'>Mark Employee As Sick Or Absent </h3>",
                text: html,
                html: true,
                customClass: 'swal-wide',
                showCancelButton: true,
              }, function (isConfirm) {
              if (isConfirm) {
                var shift_id = $("#shift option:selected").val();
                var leave_type = $("#shift option:selected").text();
                $.ajax({
                  url: baseURL+"admin/rota/markSickOrAbsent",
                  type: "POST",
                  async: false,
                  data: {
                      user_id : user_id,
                      date    : date,
                      unit_id : unit_id,
                      shift_id:shift_id,
                      old_shiftid : old_shiftid,
                      shift_name : shift_name,
                      leave_type : leave_type
                  },
                  success: function (data) { console.log(data); 
                    //event.preventDefault();
                    if(data.status == 1){ //newly added on nov19 by swaraj

                      document.getElementsByClassName(params.class_name)[0].innerHTML = data.shift_name;

                      params.datas_attributes.attr('data-shiftid',data.shift_id);
                      params.datas_attributes.attr('data-shiftname',data.shift_name);
                      params.datas_attributes.attr('data-partnumber',data.part_number);
                      params.datas_attributes.attr('data-shifttype',data.shift_type);
                      params.datas_attributes.attr('class',new_class);

                      if(data.shift_id==93)
                      {
                          params.datas[0].style.backgroundColor='rgb(240, 160, 160)';
                          params.datas[0].style.borderColor='rgb(240, 160, 160)';
                      }
                      else if(data.shift_id==4)
                      {
                          params.datas[0].style.backgroundColor='rgb(239, 12, 12)';
                          params.datas[0].style.borderColor='rgb(239, 12, 12)';
                      }
                      else
                      {
                          params.datas[0].style.backgroundColor='rgb(135, 255, 0)';
                          params.datas[0].style.borderColor='rgb(135, 255, 0)';
                      }


                      //$( "#frmViewRota" ).submit();
                    }else{
                      $('.shiftallocate').show().html(data.message).delay(1000).fadeOut();
                    }
                  }   
                });
              }
            });
          }   
        });
      }
    }else{
      $.ajax({
        url: baseURL+"admin/rota/getUserName",
        type: "POST",
        async: false,
        data: {
            user_id : user_id
        },
        success: function (data) {
          html += '<html><body><form action="#">'+
          '<label for="shift"><h4>Employee Name  : '+data.name+'</h4></label></br>'+
          '<label for="shift"><h4>Date  : '+corectDateFormat(date)+'</h4></label></br>'+
          '<label for="shift"><h4>Select Shift</h4></label> : &nbsp'+
          '<select class="custom-select form-control required" id="shift" name="shift">';
          for( var i = 0; i < absent_shifts.length; i++ ){
            html += '<option data-hours="'+absent_shifts[i].hours+'" value="'+absent_shifts[i].id+'">'+absent_shifts[i].shift_shortcut+'</option>';
          }
          html += '</select></form></body></html>';
          swal({
              title: "<h3 style='text-decoration: underline;'>Mark Employee As Sick Or Absent </h3>",
              text: html,
              html: true,
              customClass: 'swal-wide',
              showCancelButton: true,
            }, function (isConfirm) {
            if (isConfirm) {
              var shift_id = $("#shift option:selected").val();
              var leave_type = $("#shift option:selected").text();
              $.ajax({
                url: baseURL+"admin/rota/markSickOrAbsent",
                type: "POST",
                async: false,
                data: {
                    user_id : user_id,
                    date    : date,
                    unit_id : unit_id,
                    shift_id:shift_id,
                    old_shiftid : old_shiftid,
                    shift_name : shift_name,
                    leave_type : leave_type
                },
                success: function (data) { console.log(data);
                    //event.preventDefault();
                  if(data.status == 1){  //newly added on nov19 by swaraj

                      document.getElementsByClassName(params.class_name)[0].innerHTML = data.shift_name;

                      params.datas_attributes.attr('data-shiftid',data.shift_id);
                      params.datas_attributes.attr('data-shiftname',data.shift_name);
                      params.datas_attributes.attr('data-partnumber',data.part_number);
                      params.datas_attributes.attr('data-shifttype',data.shift_type);
                      params.datas_attributes.attr('class',new_class);

                      if(data.shift_id==93)
                      {
                          params.datas[0].style.backgroundColor='rgb(240, 160, 160)';
                          params.datas[0].style.borderColor='rgb(240, 160, 160)';
                      }
                      else if(data.shift_id==4)
                      {
                          params.datas[0].style.backgroundColor='rgb(239, 12, 12)';
                          params.datas[0].style.borderColor='rgb(239, 12, 12)';
                      }
                      else
                      {
                          params.datas[0].style.backgroundColor='rgb(135, 255, 0)';
                          params.datas[0].style.borderColor='rgb(135, 255, 0)';
                      }

                    //$( "#frmViewRota" ).submit();
                  }else{
                    $('.shiftallocate').show().html(data.message).delay(1000).fadeOut();
                  }
                }   
              });
            }
          });
        }   
      });
    }
  };
  function selectAgencyStaff(params){
  // $(document).on('click', '.select_user', function(e) {

    var new_class = params.datas_attributes.attr('class').replace("select_shift", ""); //newly added on nov19 by swaraj


    var user_id = params.user_id;
    var unit_id = params.unit_id;
    var date = params.date;
    var rota_id = params.rota_id;
    $.ajax({
      url: baseURL+"admin/agency/getAgencyStaff",
      type: "POST",
      async: false,
      data: {
          user_id : user_id,
          date    : date,
          unit_id : unit_id
      },
      success: function (data) {
        $.ajax({
          url: baseURL+"admin/rota/getUserName",
          type: "POST",
          async: false,
          data: {
              user_id : user_id
          },
          success: function (result) {
            var agency_staffs = data.result;
            if(agency_staffs.length > 0){
               var html = '';
               html += '<html><body><form action="#">'+
                '<label for="shift"><h4>Employee Name  : '+result.name+'</h4></label></br>'+
                '<label for="shift"><h4>Date  : '+corectDateFormat(date)+'</h4></label></br>'+
                 '<label for="shift"><h4>Select Employee</h4></label> : &nbsp'+
                 '<select class="custom-select form-control required" id="shift" name="shift">';
                 for( var i = 0; i < agency_staffs.length; i++ ){
                   html += '<option data-unitid="'+agency_staffs[i].unit_id+'" value="'+agency_staffs[i].user_id+'">'+agency_staffs[i].fname+' '+agency_staffs[i].lname+'</option>';
                 }
                 html += '</select></form></body></html>';
               $.ajax({
                 url: baseURL+"admin/agency/getShiftDetailsOfAbsentUser",
                 type: "POST",
                 async: false,
                 data: {
                     user_id : user_id,
                     date    : date,
                     unit_id : unit_id
                 },
                 success: function (data) {
                   var shift_details = data.result;
                   if(shift_details.length > 0){
                     swal({
                         title: "<h3 style='text-decoration: underline;'>Select agency employee</h3>",
                         text: html,
                         html: true,
                         customClass: 'swal-wide',
                         showCancelButton: true,
                       }, function (isConfirm) {
                       if (isConfirm) {
                         var new_user_id = $("#shift option:selected").val();
                         var new_user_unitid = $("#shift option:selected").attr('data-unitid');
                         var shift_id = shift_details[0].id;
                         var shift_hours = shift_details[0].targeted_hours;
                         var shift_category = shift_details[0].shift_category;
                         $.ajax({
                           url: baseURL+"admin/agency/addAgencyUserDetails",
                           type: "POST",
                           async: false,
                           data: {
                               user_id : new_user_id,
                               old_user_id : user_id,
                               date    : date,
                               unit_id : unit_id,
                               rota_id : rota_id,
                               shift_id : shift_id,
                               shift_hours : shift_hours,
                               shift_category : shift_category,
                               new_user_unitid : new_user_unitid
                           },
                           success: function (data) {
                            if(data.status == 2){
                              $('.shiftallocate').show().html(data.message).delay(1000).fadeOut();
                            }else{

                              params.datas[0].style.backgroundColor='rgb(0, 128, 0)';
                              params.datas[0].style.borderColor='rgb(0, 128, 0)';
                              params.datas_attributes.attr('class','');

                              //$( "#frmViewRota" ).submit();
                            }
                           }
                         });
                       }
                     });
                   }
                 }   
               });    
            }else{
              swal({
                title : "",
                text: "No agency employee",
                icon: "warning",
                button: "ok",
              });
            }
          }
        });
      }
    });
  };
  function isNumber(x) {
  return parseFloat(x) == x
};

  function onLoad(){

          date = moment(default_date, "YYYY-MM-DD");
        $("#calendar").fullCalendar( 'gotoDate', date );
       // window.location.reload();

        $('.fc-prev-button').hide();
        $('.fc-next-button').hide();
   
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
  function showDesigationName(){  
    $(".fc-divider").each(function() {
      var designation_id = $(this).find('span.fc-cell-text').text(); 
      if(designation_id){   
        if($(this).find('span.fc-cell-text').text() == designation_id){ 
          if (isNumber(designation_id)) 
          {
             var des_name = findDesName(designation_id);
          }
          else
          {
            var des_name = designation_id;
          } 
          $(this).find('span.fc-cell-text').text(des_name)
        }
      }
    });
  }
  addButtons([]);
  showDesigationName();
  onLoad(default_date);
  
  
/*  $('select').on('change', function() {
	 if($(this).find(':selected').attr('data-parent')==1){
		 $('#unit').prop('selectedIndex',0);
		 alert("Please select a sub unit.");
	 }
	});*/
  
  
});