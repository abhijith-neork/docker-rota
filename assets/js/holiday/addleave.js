$(function() {
               
    $(document).ready(function() {
        var holiday_dates = [];
        var offdays = [];
        var date_select=selected_date; //console.log(date_select);
        var arr = date_select.split('/'); 
        var year_new=new Date().getFullYear();
        var d = new Date();
        var month_new=d.getMonth()+1; //alert(month_new);
    //  var maxDate = moment('15/02/2020').format("dd/mm/yy");
  // var maxDate = new Date(2020,02, 0);
//alert(maxDate.format("dd/mm/yy"));


   var month = month_new;//('getMonth');            
   var year = year_new;
    var minDate = new Date(year, month-1, 1);
   minDate = minDate.toLocaleDateString('en-GB');

   month = arr[1];
   year = arr[0];
   var maxDate = new Date(year,month, 0);
   maxDate = maxDate.toLocaleDateString('en-GB');

         $("#start_date").datepicker('option', {minDate: minDate, maxDate: maxDate,dateFormat: 'dd/mm/yy'});
         $("#end_date").datepicker('option', {minDate: minDate, maxDate: maxDate,dateFormat: 'dd/mm/yy'});
        var table = $('#myTable').DataTable({
            "bFilter": false,
            "bInfo": false,
            "bLengthChange": false,    
            "order": [
                [0, 'asc']
            ],
            "displayLength": 25,
            "lengthMenu": [[25, 50, 75, 100, -1], [25, 50, 75, 100, "All"]],
        });
        // Order by the grouping
        $('#myTable tbody').on('click', 'tr.unit', function() {
        var currentOrder = table.order()[0];
        if (currentOrder[0] === 2 && currentOrder[1] === 'asc') {
            table.order([2, 'desc']).draw();
        } else {
            table.order([2, 'asc']).draw();
        }
        });
    });
});
$(function() {                                
    $(document).ready(function() {
        var table = $('#myTable1').DataTable({
            "bFilter": false,
            "bInfo": false,
            "bLengthChange": false,
            "order": [
                [0, 'desc']
            ],
            "displayLength": 25
        });
        // Order by the grouping
        $('#myTable tbody').on('click', 'tr.unit', function() {
        var currentOrder = table.order()[0];
        if (currentOrder[0] === 2 && currentOrder[1] === 'asc') {
            table.order([2, 'desc']).draw();
        } else {
            table.order([2, 'asc']).draw();
        }
        });
    });
});
$("#start_date").datepicker({
    dateFormat: 'dd/mm/yy',
    onSelect: function(dateText) {
        var start_date = formatDate($('#start_date').val()); 
        var end_date = formatDate($('#end_date').val()); 
        start_date = new Date(start_date);
        end_date   = new Date(end_date);
        if(start_date && end_date){
            if(start_date > end_date){
                $('#end_date').val("");
                $('#show_error').html('Please select an end date greater than start date').show();
                $('#try').hide();
            }
            else
            {
                $('#try').show();
            }
        }
        calculateDays();
    }
});
$("#end_date").datepicker({
    dateFormat: 'dd/mm/yy',
    onSelect: function(dateText) {
        var start_date = formatDate($('#start_date').val());
        var end_date   = formatDate($('#end_date').val());
        start_date = new Date(start_date);
        end_date   = new Date(end_date);
        if(start_date && end_date){
            if(end_date < start_date){
                $('#end_date').val("");
                $('#show_error').html('Please select an end date greater than start date').show();
                $('#try').hide();
            }
            else
            {
                $('#try').show();
            }
        }
        calculateDays();
    }
});
$("#message").focusout(function(){ 
    var total=$("#total_days").val();//alert(total);
    var totaldays = total.replace(":", ".");  //console.log(totaldays); 
    //console.log('0.00'); 
    // abc=total.split(":");
    // minute=abc[1]; 

    var regex = /^\d{2,3}:\d{2}$/;
    if(regex.test(total) == true)
    {
        if(totaldays == '0.00' || totaldays == '00.00' )
        {
            swal({
                    title: 'Please enter valid hour',  
                    confirmButtonText: "OK",
                    closeOnConfirm: true,
                },
                function(isConfirm){
                    if (isConfirm) {
                         //$("#total_days").val("");
                        event.preventDefault(); // prevent form submit 
                    } else {
                        
                    }
                });
        }
        else
        {
            
        }
    }
    else
    {
        swal('Please correct your hours format to hh:mm'); 
        event.preventDefault();

    }
    });

$(document).on('click', '#try', function(e) { 
            var leave_remaining=$("#calc_hours").val();  
            var num=(Math.round(leave_remaining * 100) / 100).toFixed(2); //alert(num);
            var total=$("#total_days").val(); 

            // abc=total.split(":");
            // minute=abc[1]; 

            var totaldays = total.replace(":", "."); //alert(totaldays);
            var regex = /^\d{2,3}:\d{2}$/;
            console.log(parseFloat(totaldays));
            console.log(parseFloat(num));
         
            if(regex.test(total) == true)
            {
                if(totaldays == '0.00' || totaldays == '00.00')
                {
                     //$("#total_days").val("");
                     swal('Please enter a valid hour');
                     event.preventDefault(); // prevent form submit
                
                }
                else
                {
                  if(parseFloat(num)<parseFloat(totaldays))
                  { //alert("leave_remaining is less");
                        $('#show_hours').hide();
                        if(num == 0.00){
                            swal({
                                title: 'You only have'+' '+num+' '+'hours remaining.', 
                                type: "warning", 
                                showCancelButton: true,
                                showConfirmButton : false,
                                closeOnCancel : true
                            });
                        }else{
                            swal({
                                title: 'You have only'+' '+num+' '+'hours remaining, Do you want to apply leave?', 
                                type: "warning", 
                                confirmButtonText: "Yes",
                                showCancelButton: true,
                                closeOnCancel : true,
                                closeOnConfirm: false
                            },
                            function(isConfirm){ 
                                if (isConfirm) {
                                    event.preventDefault(); //prevent form submit
                                    $("#add").submit();
                                } else {
                                    
                                }
                            });
                        }
                         event.preventDefault(); 
                    }
                    else
                    {
                       


                    } 
                    
                }
            }
            else
            {
                swal('Please correct your hours format to hh:mm');
                 event.preventDefault();
            }
    });

function calculateDays(){
    var start_date = $('#start_date').val();  
        var first_date=start_date.split('/'); 
        start_date_month=first_date[1];
        start_date_year=first_date[2]; //console.log(start_date_year);
        var end_date = $('#end_date').val();  //console.log(end_date);
        var last_date=end_date.split('/');
        end_date_month=last_date[1]; 
        end_date_year=last_date[2]; //console.log(end_date_year);
        var user_id = $('#user').val(); 

        var formattedstartMonth = moment(start_date_month, 'MM').format('MMMM');
        var formattedendMonth = moment(end_date_month, 'MM').format('MMMM');

        if(start_date_year==end_date_year && start_date_month<='08' && end_date_month>='09')
        { //console.log('hii');
               swal("You cannot apply leave starting from"+' '+start_date_year+'-'+formattedstartMonth+' '+"to"+' '+' '+end_date_year+'-'+formattedendMonth);
               // $('#start_date').val("");
               $('#end_date').val(""); 
                $('#show_hours').hide();
        }
        else if(start_date_year!=end_date_year && end_date_month>='09')
        { //console.log('hello');
               swal("You cannot apply leave starting from"+' '+start_date_year+'-'+formattedstartMonth+' '+"to"+' '+' '+end_date_year+'-'+formattedendMonth);
               // $('#start_date').val("");
               $('#end_date').val(""); 
                $('#show_hours').hide();

        }
        else
        {
            if(start_date && end_date)
            { //alert('hii');
                var userdata = holidayDates(); 
                var total=$("#total_days").val();  
                var hdates = userdata.holiday_dates; 
                var fdates= userdata.offday;
                var new_start_date = formatDate(start_date);
                var new_end_date = formatDate(end_date);
                var leave_date_array = findDatesBetnDates(new_start_date,new_end_date);
                var new_date_array = returnDateWithoutHolidayAndOffday(new_start_date,new_end_date,hdates,fdates);
                $.ajax({ 
                    type: "post",dataType: "json",
                    //url:baseURL+'manager/Leave/findshifthours',
                    url:baseURL+'manager/Leave/findremainingleave',
                    data: { 
                        user_id:user_id,
                        start_date:start_date 
                    },
                    success: function(data) {
                        if(leave_date_array.length != new_date_array.length){
                            //$('#show_hours').html('Calculated hours is '+data+' excluding holidays and offdays').show();
                                    $('#show_hours').html('Leave remaining is '+data['leave_remaining']).show();
                                    $('#calc_hours').val(data['leaves']);
                                    $('#show_error').hide();
                            }
                            else
                            {
                                    //$('#show_hours').html('Calculated hours is '+data).show();
                                    $('#show_hours').html('Leave remaining is '+data['leave_remaining']).show();
                                    $('#calc_hours').val(data['leaves']);
                                    $('#show_error').hide();
                            }
                    },
                    error:function (xhr, ajaxOptions, thrownError) {
                        $('#start_date').val("");
                        $('#end_date').val("");                  
                        swal("Network error, please try again,"+' '+thrownError+' (Error#'+xhr.status+') ');

                      }

                });
                
                if(total==''){ $("#total_days").val("00:00"); } else { $("#total_days").val(total);}
            } 
        }
}
function formatDate(date){
    var date_array = date.split('/');
    var new_date = date_array[2]+"-"+date_array[1]+"-"+date_array[0];
    return new_date;
}
function returnDateWithoutHolidayAndOffday(start_date,end_date, hdate,fdate){ //console.log(start_date); console.log(end_date);
    var leave_date_array = findDatesBetnDates(start_date,end_date); 
    var leave_date_array_without_holiday = [];
    var l_array_without_offday = [];
    var holiday_array = []; 
    holiday_dates = hdate;
    offdays=fdate; //console.log(offdays);
    if(holiday_dates.length > 0){
        $.each(holiday_dates, function( index, value ) {
            var dates = findDatesBetnDates(value.from_date,value.to_date);
            for(var i=0;i<dates.length;i++) {
              if(holiday_array.indexOf(dates[i]) == -1){
                holiday_array.push(dates[i]);
              }
            }
        });
    }
    leave_date_array_without_holiday = returnArrayWithoutDuplicate(leave_date_array,holiday_array); //console.log(leave_date_array_without_holiday);
    l_array_without_offday = leave_date_array_without_holiday;
    if(leave_date_array_without_holiday.length > 0){
      for(var i=0;i<leave_date_array_without_holiday.length;i++){
        var day_name = getDayName(leave_date_array_without_holiday[i]); //console.log(offdays);
        if(offdays.indexOf(day_name) != -1){
            var remove_item = leave_date_array_without_holiday[i];
            l_array_without_offday = $.grep(l_array_without_offday, function(value) {
              return value != remove_item;
            });
        }
      }
    }

    $('#without_offday_holiday').val(l_array_without_offday);
    return l_array_without_offday;
}
function getDayName(date){
  var weekday = ["sunday","monday","tuesday","wednesday","thursday","friday","saturdy"];
  var date = new Date(date);
  return weekday[date.getDay()];
}
function returnArrayWithoutDuplicate(array1,array2){
    array1 = array1.filter(val => !array2.includes(val));
    return array1;
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
      if(startDate != endDate){
        dates.push(endDate);
      }    
      return dates;
    }

$("#total_days").focusout(function(){
    var hours=$('#total_days').val(); 
    var regex = /^\d{2,3}:\d{2}$/;  
    var remaining=$('#total_leave_applied').val();
    if(parseInt(remaining) < 0){
        remaining = 0;
    }
    if(regex.test(hours) == false) 
    {
        swal("Please correct your hours format to hh:mm");
    }
    else
    { 
        var res = hours.replace(":", ".");  //console.log('applied-'+parseFloat(res));
        var num=(Math.round(remaining * 100) / 100).toFixed(2);  
        var rem = num.replace(":","."); //console.log('remaining-'+parseFloat(rem));
        if(parseInt(remaining) < 0){
            remaining = 0;
        }
        if(parseFloat(res) > parseFloat(rem))
        { //console.log('applied greater than remaining');
        /*swal({
                title: 'You have only'+' '+remaining+' '+'hours remaining', 
                type: "warning", 
                confirmButtonText: "OK",
                closeOnConfirm: true,
            },
            function(isConfirm){
                if (isConfirm) {
                    event.preventDefault(); // prevent form submit
                    // window.location = baseURL+"admin/Holiday/addLeave";
                } else {
                    
                }
            });*/
        }
        else
        {
            //console.log('applied less than remaining');
        }

    }
});
function holidayDates(){

        var user_id = $('#user').val();
        var hdata;
    //alert(user_id);
    $.ajax({
        type: "post",
        dataType: "json",async:false,
        url: baseURL+"admin/Holiday/getUserHolidayAndOffday",
        data : { user_id : user_id },
        success:function(data) {
          /*  holiday_dates = data.holiday_dates;
            offdays = data.offday; */
          //console.log(data);

            hdata = data;
        }
        
    });
    return hdata;
}
$('#user').change(function(){
    var user_id = $('#user').val();
    //alert(user_id);
    $.ajax({
        type: "post",
        dataType: "json",
        url: baseURL+"admin/Holiday/getUserHolidayAndOffday",
        data : { user_id : user_id },
        success:function(data) {
            holiday_dates = data.holiday_dates;
            offdays = data.offday; 
        }
    });
});
$('#unitdata').change(function(){  

    var unitID = $('#unitdata').val();
            if(unitID) { $('select[name="user"]').empty();
                $.ajax({
                    type: "post",
                    dataType: "json",
                    url: baseURL+"admin/Holiday/finduserdata",
                    data : { unit_id : unitID },
                    success:function(data) {  

                   
                        $('select[name="user"]').append('<option value="0">--Select user--</option>');
                        $.each(data, function(key, value) {  
                            $('select[name="user"]').append('<option value="'+ value.user_id +'" >'+ value.fname+' '+value.lname +'</option>');
                        });
                    }
                });
            }else{
                $('select[name="user"]').empty();
            }
        });

$('#user').change(function(){  

    var user_id = $('#user').val();  
            if(user_id) { 
                $.ajax({
                    type: "post",
                    dataType: "json",
                    url: baseURL+"admin/Holiday/findallowance",
                    data : { user_id : user_id },
                    success:function(data) {  
                        if(data.actual_allowance==null)
                        {
                             $('#annual_holliday_allowance').val(data.actual_allowance);
                        }
                        else
                        {
                            $('#annual_holliday_allowance').val(data.actual_allowance);
                        }

                         if(data.leave_remaining==null)
                        {
                             $('#total_leave_applied').val(data.actual_allowance);
                            
                        }
                        else
                        {
                            $('#total_leave_applied').val(data.leave_remaining);

                        }

                   
                    }
                });
            }else{
                $('select[name="user"]').empty();
            }
        });


$(document).ready(function(){
  $('[data-toggle="tooltip"]').tooltip();   
});
function lopWarning() {
    if($("#add")[0].checkValidity()) {
        event.preventDefault();// prevent form submit
        var lop = parseInt($('#lop').val());
        var total_days = $('#total_days').val();
        if(lop == 1){            
            swal({
                title: 'You have already taken all of your leave allowances. By applying this will be consider as loss of pay. Do you want to continue?', 
                type: "warning",
                showCancelButton: true,
                // confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes",
                cancelButtonText: "No",
                closeOnConfirm: true,
                closeOnCancel: true
            },
            function(isConfirm){
                if (isConfirm) {
                    if(total_days == 0){
                        $('.div-warning-msg').show();
                    }else{
                        $('.div-warning-msg').hide();
                        $('#add').submit();
                    }
                } else {
                    
                }
            });
        }else{
            if(total_days == 0){
                $('.div-warning-msg').show();
            }else{
                $('.div-warning-msg').hide();
                $('#add').submit();
            }
        }
    }
    else {
    }
}

