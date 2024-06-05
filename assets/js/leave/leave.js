$(function() {
	           
    $(document).ready(function() {
    	var date_select=selected_date; //console.log(date_select);
    	var arr = date_select.split('/'); 
    	var year_new=new Date().getFullYear();
    	var d = new Date();
    	var month_new=d.getMonth()+1; //alert(month_new);
    //	var maxDate = moment('15/02/2020').format("dd/mm/yy");
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
   var arr1 = date_select.split('/');  
   new_arr1=arr1[0]+'-'+arr1[1]+'-'+arr1[2]; //alert(new_arr1);
   var date = new Date(new_arr1); 
   date.setFullYear(date.getFullYear() + 1);  
   new_maxDate = date.toLocaleDateString('en-GB'); 
   //alert(new_maxDate);

    	 $("#start_date").datepicker('option', {minDate: minDate, maxDate: new_maxDate,dateFormat: 'dd/mm/yy'});
    	 $("#end_date").datepicker('option', {minDate: minDate, maxDate: new_maxDate,dateFormat: 'dd/mm/yy'});
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
				[3, 'desc']
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
$(document).on('click', '#cancel-button', function(e) {
	if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) )
	{
	    event.preventDefault();
	    window.location = baseURL+"manager/leave/listleaves";
	}
	else
	{
	    event.preventDefault();
	    window.location = baseURL+"manager/leave";

	}
});
function formatDate(date){
	var date_array = date.split('/');
	var new_date = date_array[2]+"-"+date_array[1]+"-"+date_array[0];
	return new_date;
}
function calculateDays(){
	if(annual_allowance_type==2)
	{ 
		var start_date = $('#start_date').val();  
		var first_date=start_date.split('/'); 
		start_date_month=first_date[1];
		start_date_year=first_date[2]; //console.log(start_date_year);
		var end_date = $('#end_date').val();  //console.log(end_date);
		var last_date=end_date.split('/');
		end_date_month=last_date[1]; 
		end_date_year=last_date[2]; //console.log(end_date_year);
		var user_id = $('#user_id').val(); //alert(start_date);

		var formattedstartMonth = moment(start_date_month, 'MM').format('MMMM');
		var formattedendMonth = moment(end_date_month, 'MM').format('MMMM');

		if(start_date_year==end_date_year && start_date_month<='08' && end_date_month>='09')
		{ //console.log('hii');
	           swal("You cannot apply leave starting from"+' '+start_date_year+'-'+formattedstartMonth+' '+"to"+' '+' '+end_date_year+'-'+formattedendMonth);
	           // $('#start_date').val("");
	            $('#end_date').val("");
	            $('#show_hours').hide();
		}
	    else if(start_date_year!=end_date_year && end_date_month>='08')
		{ //console.log('hello');
               swal("You cannot apply leave starting from"+' '+start_date_year+'-'+formattedstartMonth+' '+"to"+' '+' '+end_date_year+'-'+formattedendMonth);
	           // $('#start_date').val("");
	            $('#end_date').val("");
	            $('#show_hours').hide();

		}
		else
		{
			if(start_date && end_date){ //alert('hii');
				var total=$("#total_days").val();  
				var new_start_date = formatDate(start_date);
				var new_end_date = formatDate(end_date);
				var leave_date_array = findDatesBetnDates(new_start_date,new_end_date);
				var new_date_array = returnDateWithoutHolidayAndOffday(new_start_date,new_end_date);
				if(leave_date_array.length != new_date_array.length){
					$('.div-warning-msg').show();
				}else{
					$('.div-warning-msg').hide();
				}
				$.ajax({ 
					type: "post",dataType: "json",
					//url:baseURL+'manager/Leave/findshifthours',
					url:baseURL+'manager/Leave/findremainingleave',
					data: { 
						user_id:user_id,
						start_date:start_date 
					},
					success: function(data) { //console.log(data);
						if(leave_date_array.length != new_date_array.length){
				                     //$('#show_hours').html('Calculated hours is '+data+' excluding holidays and offdays').show();
				                    $('#show_hours').html('Leave remaining is '+data['leave_remaining']).show();
                                    $('#calc_hours').val(data['leaves']);
				                    $('#show_error').hide();
				                }else{
				                    //$('#show_hours').html('Calculated hours is '+data).show();
				                    $('#show_hours').html('Leave remaining is '+data['leave_remaining']).show();
                                    $('#calc_hours').val(data['leaves']);
				                    $('#show_error').hide();
				                }
						if(total==''){ $("#total_days").val("00:00"); } else { $("#total_days").val(total);}
						//$total_days=data; 
						// if($total_days) 
						// {  
						// 	var total=$("#total_days").val();  //console.log(total);
						// 	var annual=$("#annual_holliday_allowance").val(); // console.log(annual);
						// 	var totaldays=$("#total_leave_applied").val();
						// 	var num=(Math.round(totaldays * 100) / 100).toFixed(2); 
						// 	var res = num.replace(".", ":"); 
						// 	annual = annual.replace(":", ".");
						// 	remaining=annual-num;   

						// 	total = total.replace(":", ".");
						// 	 console.log(remaining);
						// 	if(total > remaining)
						// 	{
						// 		$("#lop").val(1); 
						// 	} 
						// 	else
						// 	{
						// 		$("#lop").val(2); 
						// 	}

						// }
					} 
		 		});
			}
		}
	}
	else
	{
		var start_date = $('#start_date').val();
		var end_date = $('#end_date').val();
		if(start_date && end_date)
		{
			start_date = formatDate(start_date);
			end_date = formatDate(end_date);
			var new_date_array = returnDateWithoutHolidayAndOffday(start_date,end_date);
			var startdate_new = new Date(start_date),
			enddate_new   = new Date(end_date),
			difference_date = new Date(enddate_new - startdate_new),
			total_days = new_date_array.length;
			$("#total_days").val(total_days);
			var total=$("#total_days").val();
			var annual=$("#annual_holliday_allowance").val();  
			var totaldays=$("#total_leave_applied").val();  
			remaining=annual-totaldays;
			if(total > remaining)
			{
			  $("#lop").val(1); 
			} 
			else
			{
			  $("#lop").val(2); 
			}
		}
	}
}
function returnDateWithoutHolidayAndOffday(start_date,end_date){
	var leave_date_array = findDatesBetnDates(start_date,end_date);
	var leave_date_array_without_holiday = [];
	var l_array_without_offday = [];
	var holiday_array = [];
	if(holidayDates.length > 0){
		$.each(holidayDates, function( index, value ) {
			var dates = findDatesBetnDates(value.from_date,value.to_date);
			for(var i=0;i<dates.length;i++) {
			  if(holiday_array.indexOf(dates[i]) == -1){
			    holiday_array.push(dates[i]);
			  }
			}
		});
	}
	leave_date_array_without_holiday = returnArrayWithoutDuplicate(leave_date_array,holiday_array);
	l_array_without_offday = leave_date_array_without_holiday;
	if(leave_date_array_without_holiday.length > 0){
	  for(var i=0;i<leave_date_array_without_holiday.length;i++){
	  	var day_name = getDayName(leave_date_array_without_holiday[i]);
	  	if(user_offday.indexOf(day_name) != -1){
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
  					var total=$("#total_days").val(); 

  					abc=total.split(":");
  					minute=abc[1]; 
					var annual=$("#annual_holliday_allowance").val(); // console.log(annual);
					var totaldays=$("#total_leave_applied").val();
					var num=(Math.round(totaldays * 100) / 100).toFixed(2); 
					var res = num.replace(".", ":"); 
					annual = annual.replace(":", ".");
					var regex = /^\d{2,3}:\d{2}$/;
             		if(regex.test(total) == true)
             		{
					//console.log(annual);console.log(num);
					  if(minute >= 60)
					  {
                            swal('Please enter valid hour');
							event.preventDefault();
					  }
					  else
					  {

						  	remaining=annual-num;   
							total = total.replace(":", ".");
							 //console.log(remaining);
							if(total > remaining)
							{
								$("#lop").val(1); 
							} 
							else
							{
								$("#lop").val(2); 
							}

					   }
						
					}
					else
					{
						swal('Please correct your hours format to hh:mm');
						event.preventDefault(); 
					}
});

$("#message").focusout(function(){ 
	var total=$("#total_days").val();//alert(total);
	var totaldays = total.replace(":", ".");  

	abc=total.split(":");
  	minute=abc[1]; 
	//console.log('0.00'); 
	var regex = /^\d{2,3}:\d{2}$/;
	if(regex.test(total) == true)
    {
    	if(minute >= 60)
		{
	        swal('Please enter valid hour');
			event.preventDefault();
		}
		else
		{
			if(totaldays == '0.00' || totaldays == '00.00')
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
			abc=total.split(":");
  			minute=abc[1]; 
			var totaldays = total.replace(":", "."); //alert(totaldays);
			var regex = /^\d{2,3}:\d{2}$/;
			if(regex.test(total) == true)
		    {
		    	if(minute >= 60)
				{
			        swal('Please enter valid hour');
					event.preventDefault();
				}
				else
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
							//alert("leave_remaining is greater");


						}
						
					}
				}
			}
			else
			{
				swal('Please correct your hours format to hh:mm');
				 event.preventDefault();
			}
	});

$(document).ready(function(){
  $('[data-toggle="tooltip"]').tooltip();   
});
function lopWarning() {
	if($("#add")[0].checkValidity()) 
	{ console.log('lop');
		event.preventDefault();// prevent form submit
        var lop = parseInt($('#lop').val());
        var total_days = $('#total_days').val();
        var annual=$("#annual_holliday_allowance").val(); // console.log(annual);
		var totaldays=$("#total_leave_applied").val();
		var num=(Math.round(totaldays * 100) / 100).toFixed(2); 
		var res = num.replace(".", ":"); 
		annual = annual.replace(":", ".");
		var remaining=annual-num;
		remaining=Math.floor(remainings * 100) / 100; console.log(remaining);
		if(parseInt(remainings) <= 0){
			remainings = 0;
		}
        if(lop == 1){      console.log(lop);   	 
        	swal({
        		title: 'You have only'+' '+remainings+' '+'hours remaining', 
        		type: "warning", 
        		confirmButtonText: "OK",
        		closeOnConfirm: true,
        	},
        	function(isConfirm){
        		if (isConfirm) {
        			$("#add").submit();
        			// event.preventDefault(); // prevent form submit
          			// window.location = baseURL+"manager/Leave/applyLeave";
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
function calcelFeedBackPAge(){
	if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) )
	{
	    event.preventDefault();
	    window.location = baseURL+"staffs/training/listtraining";
	}
	else
	{
	    event.preventDefault();
	    window.location = baseURL+"staffs/training";

	}
}
function approveFunction(id) {
	var user_id = event.currentTarget.value;
	swal({
		title: "Enter your comments!",
		text: "<textarea rows='4' cols='40' maxlength='50' id='holiday_comment'></textarea>",
		// --------------^-- define html element with id
		html: true,
		showCancelButton: true,
		closeOnCancel : true,
		closeOnConfirm: false
	}, function (isConfirm) {
		if(isConfirm){
			var comment = $("#holiday_comment").val();
			if(comment){
				$.ajax({
				    url: baseURL+"manager/holiday/approveHoliday",
				    type: "POST",
				    data: {
				        user_id: user_id,
				        comment : comment,
				        holiday_id : id
				    },
				    success: function () {
				        swal("Holiday applicaton approved");
				        // window.location.reload();
				    },
				    error: function (xhr, ajaxOptions, thrownError) {
				    	swal("Bad attempt");
				    	// window.location.reload();
				    }
				});
			}else{
				swal.showInputError("Please enter comment");
			}
		}else{
			//do nothing
		}
	});
}

function editFeedback(id) {
        event.preventDefault(); // prevent form submit
         var dekstop="dekstop";
          window.location = baseURL+"staffs/training/editFeedback/"+id+'/'+dekstop;
     
    }


function rejectFunction(id, unitname) {
	var user_id = event.currentTarget.value;
	swal({
		title: "Enter your comments!",
		text: "<textarea rows='4' cols='40' maxlength='50' id='holiday_comment'></textarea>",
		// --------------^-- define html element with id
		html: true,
		showCancelButton: true,
		closeOnCancel : true,
		closeOnConfirm: false
	}, function (isConfirm) {
		if(isConfirm){
			var comment = $("#holiday_comment").val();
			if(comment){
				$.ajax({
				    url: baseURL+"manager/holiday/rejectHoliday",
				    type: "POST",
				    data: {
				        user_id: user_id,
				        comment : comment,
				        holiday_id : id
				    },
				    success: function () {
				        swal("Holiday applicaton rejected");
				        // window.location.reload();
				    },
				    error: function (xhr, ajaxOptions, thrownError) {
				    	swal("Bad attempt");
				    	// window.location.reload();
				    }
				});
			}else{
				swal.showInputError("Please enter comment");
			}
		}else{
			//do nothing
		}
	});
}
function deleteFunction(id,from_date,to_date,hour)
{
	var from_date=from_date;
	var to_date=to_date;
    	event.preventDefault(); // prevent form submit
    	//var form = event.target.form; // storing the form
    	        swal({
    	  title: 'Are you sure you want to cancel the leave from  '+from_date+' to '+to_date+'('+hour+' hours'+')'+'?', 
    	  type: "warning",
    	  showCancelButton: true,
    	  confirmButtonColor: "#DD6B55",
    	  confirmButtonText: "Yes, cancel it!",
    	  cancelButtonText: "No!",
    	  closeOnConfirm: true,
    	  closeOnCancel: true
    	},
    	function(isConfirm){
    	  if (isConfirm) {
    	   // form.submit();          // submitting the form when user press yes
    		  window.location = baseURL+"manager/Leave/cancelleave/web/"+id
    	  } else {
    		  return false;
     	  }
    	});
 
}