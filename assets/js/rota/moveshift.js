$(function() {
                                
      $(document).ready(function() {
                                    
                $("#from-datepicker").datepicker({ 
                      dateFormat: 'dd/mm/yy'
                 });

                $("#from-datepicker").on("change", function () { //find rota details using changed date and user_id
                        
                        var start_date = $(this).val();
                        var user = $('#user').val(); 
                        $.ajax({
                          type: "post",
                          dataType: "json",
                          url: baseURL+"admin/moveShift/findrotadetails",
                          data : { user : user, date:start_date },
                          success:function(data) {  

                            $('#abc').remove();
                            $('#myTable').append(data.rota);
                            //$('#rota_unit').val(data.status);

                          }
                    });
 
                }); 

      });

  });


  $('#unitdata').change(function(){  //find users of a unit

    var unitID = $('#unitdata').val();
     var status = $('#status').val();
            if(unitID) { $('select[name="user"]').empty();
                $.ajax({
                    type: "post",
                    dataType: "json",
                    url: baseURL+"admin/moveShift/finduserdataforallforreport",
                    data : { unit_id : unitID, status:status },
                    success:function(data) {  

                        if(data['parent']!=0)
                        {
                            swal("Sorry, You cannot select a unit having sub units.");
                            event.preventDefault();
                        }
                        else
                        {
                          $('select[name="user"]').append('<option value="0">--Select user--</option>');
                          $.each(data['unit_data'], function(key, value) {  
                              $('select[name="user"]').append('<option value="'+ value.user_id +'" >'+ value.fname+' '+value.lname +'</option>');
                          });
                        }
                        
                    }
                });
            }else{
                $('select[name="user"]').empty();
            }
        });

    $('#user').change(function(){   //find rota details using changed user_id and date

              var user = $('#user').val();
              var start_date = $('#from-datepicker').val();
              $.ajax({
                          type: "post",
                          dataType: "json",
                          url: baseURL+"admin/moveShift/findrotadetails",
                          data : { user : user, date:start_date },
                          success:function(data) {  console.log(data.rota);
                            $('#abc').remove();
                            $('#myTable').append(data.rota);
                             //$('#rota_unit').val(data.status);

                          }
                    });

    });

     $('#unitdatafor').change(function(){   //find rota details using changed user_id and date
              $('#message').val("");
              var user = $('#user').val();
              var start_date = $('#from-datepicker').val();
              var unitID = $('#unitdatafor').val();
              $.ajax({
                          type: "post",
                          dataType: "json",
                          url: baseURL+"admin/moveShift/findrotadetailsforAlert",
                          data : { user : user, date:start_date, unit:unitID },
                          success:function(data) {  console.log(data['message']);
                           $('#message').val(data['message']);
                          }
                    });

    });



$(document).on('click', '#move_shift', function(e) { 
      var unit_id = $('#unitdata').val(); 
      var start_date = $('#from-datepicker').val(); 
      var user = $('#user').val(); //alert(user);
      var unitfor = $('#unitdatafor').val();
      var shift = $('#shift').val();
      var shift_gap = $('#shift_gap').val();
      var new_message = $('#message').val();

      var message = new_message;
       event.preventDefault();
      if(unit_id=='')
      {
                    swal("Please select a unit");
                    event.preventDefault();

      }
      else if(start_date=='')
      {
                    swal("Please select a date");
                    event.preventDefault();
      }
      else if(user==0 || user=='' || user==null)
      {
                    swal("Please select a user");
                    event.preventDefault();
      }
      else if(unitfor=='')
      {
                    swal("Please select a to unit");
                    event.preventDefault();
      }
      else if(shift=='')
      {
                    swal("Please select a shift");
                    event.preventDefault();
      }
      else
      {  event.preventDefault();
                    $.ajax({
                                      type: "post",
                                      dataType: "json",
                                      url: baseURL+"admin/moveShift/findShiftGapHours",
                                      data : { 
                                                user : user, 
                                                date:start_date, 
                                                unitfor:unitfor, 
                                                shift:shift, 
                                                shift_gap:shift_gap 
                                              },
                                      success:function(data) {  

                                             $status=GetShiftHourStatus(data,shift_gap);  //alert($status);

                                             if($status==true)
                                             { //alert('hiii');
                                                $message="There should be a "+shift_gap+" hour difference between shifts."
                                                swal($message);
                                                event.preventDefault();
                                             }
                                             else
                                             {  //alert('hello');

                                                      swal({
                                                              title: "",
                                                              text: message,
                                                              html: true,
                                                              customClass: 'swal-wide',
                                                              showCancelButton: true,
                                                              closeOnCancel : true,
                                                              closeOnConfirm: false
                                                            }, function (isConfirm) {  
                                                      if(isConfirm)
                                                      {
                                                          event.preventDefault();
                                                          $( "#frmmoveshift" ).submit();

                                                      }
                                                      else
                                                      {


                                                      }
                                                    });
                                               

                                             }

                                      }
                          }); 
                   }
  });

function formatDate(date){
  var date_array = date.split('/');
  var new_date = date_array[2]+"-"+date_array[1]+"-"+date_array[0];
  return new_date;
}

function GetShiftHourStatus(data,shift_gap)
{  
    twelve = moment('12:00', 'hh:mm');
    
    var moment_p_time = moment(data.previous['p_shift_endtime'], 'hh:mm');
    console.log(data.previous);
    if(moment_p_time.isBefore(twelve))
    {
        var p_day_endtime_date = addDate(data.previous['p_date'])+" "+data.previous['p_shift_endtime'];
    }
    else
    {
        var p_day_endtime_date = data.previous['p_date']+" "+data.previous['p_shift_endtime'];
    } console.log(p_day_endtime_date);

    var c_day_starttime_date = data.current['c_date']+" "+data.current['c_shift_starttime']; console.log(c_day_starttime_date);

    var moment_c_time = moment(data.current['c_shift_endtime'], 'hh:mm'); console.log(moment_c_time);

    if(moment_c_time.isBefore(twelve))
    {
        var c_day_endtime_date = addDate(data.current['c_date'])+" "+data.current['c_shift_endtime'];
    }
    else
    {
        var c_day_endtime_date = data.current['c_date']+" "+data.current['c_shift_endtime'];
    } console.log(c_day_endtime_date);

      var n_day_starttime_date = data.next['n_date']+" "+data.next['n_shift_starttime']; console.log(n_day_starttime_date);
      var p_day_difference = checkTimeDifference(p_day_endtime_date,c_day_starttime_date); console.log(p_day_difference);
      var n_day_difference = checkTimeDifference(c_day_endtime_date,n_day_starttime_date); console.log(n_day_difference);
      var p_status = checkZeroHours(data.previous['p_shift_endtime'],data.current['c_shift_starttime']); console.log(p_status);
      var n_status = checkZeroHours(data.current['c_shift_endtime'],data.next['n_shift_starttime']); console.log(n_status);
      
//alert('hii');
      if(p_status == true && n_status==true)
      {
          return false;
      }
      else if(p_status == false && n_status == false)
      {
        if(p_day_difference < shift_gap || n_day_difference < shift_gap)
        {
          return true;
        }
        else
        {
          return false;
        }
      }
      else if(p_status == false && n_status == true)
      {
        if(p_day_difference < shift_gap)
        {
          return true;
        }
        else
        {
          return false;
        }
      }
      else if(n_status == false && p_status == true)
      {
        if(n_day_difference < shift_gap)
        {
          return true;
        }
        else
        {
          return false;
        }
      }
      else
      {
        return false;
        //Nothing
      }
}

function addDate(date)
{
    var date = new Date(date),
    days = 1;
    date.setDate(date.getDate() + days);
    let next_date = JSON.stringify(date)
    next_date = next_date.slice(1,11);
    return(next_date);
}

function checkZeroHours(starttime,endtime)
{
      if(starttime.split(':')[0] == "00" || endtime.split(':')[0] == "00"){
        return true;
      }else{
        return false;
      }
}
    
function checkTimeDifference(start_time,end_time)
{ 
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


 
  
  