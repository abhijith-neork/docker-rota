$(document).ready(function() { 
  
  $('#calendar').fullCalendar({
    height: "auto",
    resourceAreaWidth: 350,  
    editable: false,
    eventLimit: true,filterResourcesWithEvents:true,refetchResourcesOnNavigate:true,
    aspectRatio: 2,
    slotWidth:'64' , 
    selectOverlap: false,
    eventOverlap: false,
    scrollTime: '00:00',
    header: {
      left: '',
      center: 'title',
      right: ' ',   
    },
    titleFormat: ' ',
    eventRender: function( event, element, view ) {
      var title = element.find( '.fc-title' );
      title.html( title.text() );
    },
    eventAfterRender: function( event, element, view ) {
      
    },
    eventAfterAllRender:function( event, element, view ) {
       showcount();
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
        buttonText: 'Custom Week'
      
      }
    },
    resourceColumns: [
      {
        labelText: 'Units',
        field: 'title'
      }
    ],
    resources: resources,
    
  });
  function findShiftCat(shift_id){ 
    var shift_category = '' ;
    $.each(shift_cats, function(key,value) {
      if(key == shift_id){  
      if(value==null)
        {
          shift_category=0;
        }
        else
        { 
          shift_category = value;
        }
       
      }        
    });
    return shift_category;
  }
  function showcount(){
    if(shift_data.length > 0){
      for(var i=0;i<shift_data.length;i++){
        var shifts = shift_data[i];
        var day_count = 0;
        var night_count = 0;
        var nurse_day_count = 0;
        var nurse_night_count = 0;
        var shift_category = '';
        var rotta_settings = {};
        shifts.forEach(function(element) {
          var unit_id = element.parent_unit;
          if(unit_id == 0){
            unit_id = element.unit_id;
          } 
          var date = element.date;
          var user_id = element.user_id;
          var shift_id = element.shift_id;
          var des_code = element.designation_code;
          var shift_code = element.shift_shortcut;
          var part_number = element.part_number;
          var sort_order = element.sort_order;
          var shift_type = parseFloat(element.shift_type);
          rotta_settings = {
            /*"day_shift_min" : element.day_shift_min,
            "day_shift_max" : element.day_shift_max,
            "night_shift_min" : element.night_shift_min,
            "night_shift_max" : element.night_shift_max,
            "nurse_day_count" : element.nurse_day_count,
            "nurse_night_count" : element.nurse_night_count,*/
            "date" : date,
            "unit_id" : unit_id, 
          };  
          //console.log(unit_id+'-'+part_number);
          if(part_number == 1)
          {
            shift_category = findShiftCat(shift_id);
            if(shift_category == 1 || shift_category == 3 || shift_category == 4 )
            {
              day_count = day_count + shift_type;
            }
            else if(shift_category == 2)
            {
              night_count = night_count + shift_type;
            }
            else
            {
              //do nothing
            } 
            if(sort_order == 1)
            { 
              if(shift_category == 1 || shift_category == 3 || shift_category == 4 )
              {
                nurse_day_count = nurse_day_count + shift_type;
              }
              else if(shift_category == 2)
              {
                nurse_night_count = nurse_night_count + shift_type;
              }
              else
              {
                //do nothing
              }
            }
          }
          else
          {
            //do nothing
          }
        });
        // console.log(day_count+'-'+night_count+'-'+rotta_settings);        
        changeColor(day_count,night_count,rotta_settings);
        changeNurseCountColor(nurse_day_count,nurse_night_count,rotta_settings);
      }
    }
  }
  function getUnitId(id){  
    var unit_id = '';
    for (var i = 0; i < all_units.length; i++) {
      if(all_units[i].id == id){
        if(all_units[i].parent_unit == 0){
          unit_id = all_units[i].id;
        }else{
          unit_id = all_units[i].parent_unit;
        }
      }
    }
    return unit_id;
  }
  function changeNurseCountColor(day_count,night_count,rotta_settings)
  {      
    var date = rotta_settings.date;
    var element_unit_id = rotta_settings.unit_id; //console.log(unit_name);
    // if(unit_name==1)
    // {
    var unit_id=element_unit_id;
    var data_settings = $(".shift_"+date+"_"+unit_id).attr('data_settings');
    var data_settings_array = data_settings.split('_');
    var nurse_day_count = data_settings_array[4];   
    var nurse_night_count = data_settings_array[5];
    // }
    // else
    // {
    //   var unit_id = getUnitId(element_unit_id);
    // }
     //console.log(unit_id);
    $(".shift_"+date+"_"+unit_id).find(".day-nurse-color").text(day_count);
    $(".shift_"+date+"_"+unit_id).find(".night-nurse-color").text(night_count);

    if(day_count < nurse_day_count){
      $(".shift_"+date+"_"+unit_id).find(".day-nurse-color").css({backgroundColor: '#FF0000'});
    }else if(day_count == nurse_day_count){
      $(".shift_"+date+"_"+unit_id).find(".day-nurse-color").css({backgroundColor: '#006400'});
    }else if(day_count > nurse_day_count){
      $(".shift_"+date+"_"+unit_id).find(".day-nurse-color").css({backgroundColor: '#0000FF'});
    }else{
      //nothing
    }

    if(night_count < nurse_night_count){
      $(".shift_"+date+"_"+unit_id).find(".night-nurse-color").css({backgroundColor: '#FF0000'});
    }else if(night_count == nurse_night_count){
      $(".shift_"+date+"_"+unit_id).find(".night-nurse-color").css({backgroundColor: '#006400'});
    }else if(night_count > nurse_night_count){
      $(".shift_"+date+"_"+unit_id).find(".night-nurse-color").css({backgroundColor: '#0000FF'});
    }else{
      //nothing
    }
  }

  function changeColor(day_count,night_count,rotta_settings){
    var date = rotta_settings.date;
    var element_unit_id = rotta_settings.unit_id; 
    //console.log(unit_name);
    // if(unit_name==1)
    // {
    var unit_id=element_unit_id;
    var data_settings = $(".shift_"+date+"_"+unit_id).attr('data_settings');
    var data_settings_array = data_settings.split('_');
    var day_shift_min = data_settings_array[0];   
    var day_shift_max = data_settings_array[1];
    var night_shift_min = data_settings_array[2];
    var night_shift_max = data_settings_array[3];

    // }
    // else
    // {
    //   var unit_id = getUnitId(element_unit_id);
    // } 
    //console.log(unit_id); 
    $(".shift_"+date+"_"+unit_id).find(".day-color").text(day_count);
    $(".shift_"+date+"_"+unit_id).find(".night-color").text(night_count);
    if(day_count > day_shift_min && day_count < day_shift_max){ 
      $(".shift_"+date+"_"+unit_id).find(".day-color").css({backgroundColor: '#7CFC00'});
    }else if(day_count < day_shift_min){
      $(".shift_"+date+"_"+unit_id).find(".day-color").css({backgroundColor: '#FF0000'});
    }else if(day_count == day_shift_min){
      $(".shift_"+date+"_"+unit_id).find(".day-color").css({backgroundColor: '#ffbf00'});
    }else if(day_count > day_shift_max){
      $(".shift_"+date+"_"+unit_id).find(".day-color").css({backgroundColor: '#0000FF'});
    }else if(day_count == day_shift_max){
      $(".shift_"+date+"_"+unit_id).find(".day-color").css({backgroundColor: '#006400'});
    }else{
      //Nothing
    }
    //Night  shift
    if(night_count > night_shift_min && night_count < night_shift_max){
      $(".shift_"+date+"_"+unit_id).find(".night-color").css({backgroundColor: '#7CFC00'});
    }else if(night_count < night_shift_min){
      $(".shift_"+date+"_"+unit_id).find(".night-color").css({backgroundColor: '#FF0000'});
    }else if(night_count == night_shift_min){
      $(".shift_"+date+"_"+unit_id).find(".night-color").css({backgroundColor: '#ffbf00'});
    }else if(night_count > night_shift_max){
      $(".shift_"+date+"_"+unit_id).find(".night-color").css({backgroundColor: '#0000FF'});
    }else if(night_count == night_shift_max){
      $(".shift_"+date+"_"+unit_id).find(".night-color").css({backgroundColor: '#006400'});
    }else{
      //Nothing
    }
  }
});