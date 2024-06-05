$(function() {

	 //$('#hours').val(0);
                                
                                    $(document).ready(function() {

                                        $(document).on('click','.lock-rota-status', function (e) {
                                         event.preventDefault();
                                          var name = 'timesheet';
                                          var status = $(this).data("status");
                                          var unit_id = $('#unitdata option:selected').val();  
                                          var from_date = $('#from-datepicker').val();  
                                          var to_date = $('#to-datepicker').val(); 
                                          var message = 'Timesheet locked successfully';
                                          if(status == 0){
                                            message = 'Timesheet unlocked successfully';
                                          }
                                          var date1 = new Date(formatDate(from_date)); 
                                          var date2 = new Date(formatDate(to_date));
                                          if(from_date && to_date && date1 <= date2){
                                            $("#img-loader-data").show();
                                            $.ajax({
                                              url:baseURL+'admin/reports/updateTimeSheetLockStatus',
                                              type:'post',
                                              dataType:'json',
                                              delay: 250,
                                              data: {
                                                name : name,
                                                status : status,
                                                unit_id : unit_id,
                                                from_date : from_date,
                                                to_date : to_date
                                              },
                                              success: function (value) {
                                                $("#img-loader-data").hide();
                                                if(value.status == 1){
                                                  swal({
                                                    title: "",
                                                    text: message,
                                                    confirmButtonText: 'Ok',
                                                    closeOnConfirm: true,
                                                  },
                                                  function(isConfirm){
                                                    if (isConfirm){
                                                      event.preventDefault();
                                                      window.location.href = value.url;
                                                    } 
                                                  });
                                                }else if(value.status == 2){
                                                  swal({
                                                    title: "",
                                                    text: value.message,
                                                    confirmButtonText: 'Ok',
                                                    closeOnConfirm: true,
                                                  },
                                                  function(isConfirm){
                                                    if (isConfirm){
                                                      /*event.preventDefault();*/
                                                      /*window.location.href = value.url;*/
                                                    } 
                                                  });
                                                }else{
                                                  swal({
                                                    title : "",
                                                    text: 'An unexpected error occurred while processing your request. Please try again later.',
                                                    icon: "warning",
                                                    button: "ok",
                                                  });
                                                }
                                              }
                                            });
                                          }else{
                                            swal("Please enter a start and end date where the start date is before the end date.");
                                            event.preventDefault();
                                          }
                                          
                                        });

                                      var role = jobe_roles;  
                                      $(".select2").select2();
                                      if(role.length > 0){
                                        $('.jobrole').select2().val(role).trigger("change") 
                                      }

                                       $("#from-datepicker").datepicker({ 
                                        dateFormat: 'dd/mm/yy'
                                    });
                                    $("#from-datepicker").on("change", function () {
                                        var fromdate = $(this).val();
                                        //alert(fromdate);
                                    }); 

                                    $("#to-datepicker").datepicker({ 
                                        dateFormat: 'dd/mm/yy'
                                    });
                                    $("#to-datepicker").on("change", function () {
                                        var fromdate = $(this).val();
                                        //alert(fromdate);
                                    });


                                    var table = $('#myTable').DataTable({
                                     
                                    dom: 'lBfrtip',
                                    buttons: [
                                    'copy', 'excel', 'pdf', 'print'
                                    ],
                                    "displayLength": 25,
                                    "lengthMenu": [[25, 50, 75, 100, -1], [25, 50, 75, 100, "All"]],
                                    initComplete: function () {
                                        this.api().columns([]).every(function () {
                                        var column = this;
                                        if (column.index() !== 0) 
                                        {  
                                           $(column.header()).append("<br>")
                                           var select = $('<select><option value=""></option></select>')
                                                               .appendTo($(column.header()))
                                                               .on('change', function () {
                                                 var val = $.fn.dataTable.util.escapeRegex(
                                                     $(this).val()
                                                 );
                                                column.search(val ? '^' + val + '$' : '', true, false).draw();
                                             });

                                           column.data().unique().sort().each(function (d, j) {
                                             select.append('<option value="' + d + '">' + d + '</option>')
                                          } );
                                        }  
                                 
                                        });
                                        } 
                                        
                                    });

                                    

                                    // Order by the grouping
                                    $('#myTable tbody').on('click', 'tr.user', function() {
                                    var currentOrder = table.order()[0];
                                    if (currentOrder[0] === 2 && currentOrder[1] === 'asc') {
                                    table.order([2, 'desc']).draw();
                                    } else {
                                    table.order([2, 'asc']).draw();
                                    }
                                    });
                                    
                                   $('#search').on('click',function(event){  

                                           var userID = $('#user').val();   
                                           var unit_id = $('#unitdata').val();  
                                           var from_date = $('#from-datepicker').val();  
                                           var to_date = $('#to-datepicker').val(); 
                                           var jobrole = $('#jobrole').val();
                                           var status = $('#status').val();
                                           var date1 = new Date(formatDate(from_date)); 
                                           var date2 = new Date(formatDate(to_date)); 
                                           /*if(unit_id=='' || unit_id=='none')
                                           {
                                               swal("Please select a unit");
                                               event.preventDefault();
                                           }*/
                                           
                                            if(from_date=='')
                                            {
                                                swal("Please select from date");
                                                event.preventDefault();
                                            }
                                            
                                            else if(to_date=='')
                                            {
                                                swal("Please select to date");
                                                event.preventDefault();
                                            }
                                            else if(date1 > date2)
                                           {
                                             swal("Start date should be smaller than end date");
                                               event.preventDefault();
                                           }
                                           else
                                           { 
                                                 $.ajax({
                                                      type :'POST',
                                                      dataType:'json',
                                                      data : { user_id : userID ,to_date:to_date,unit_id:unit_id,from_date:from_date,jobrole:jobrole,status:status},
                                                      url: baseURL+"admin/Reports/payrollreport",
                                                      success : function(result){  
                                                       //$('#myTable').val(result); 
                                                      // if(result=='') { 
                                                      //         table.clear().draw();
                                                      //        }
                                                      //        else
                                                      //        { 
                                                      //         table.clear(); 
                                                      //         table.rows.add(result).draw(); 
                                                      //        }  


                                                      }
                                          		}); 
                                            }

                                    });

                                    });


  

  });
function formatDate(date){
  var date_array = date.split('/');
  var new_date = date_array[2]+"-"+date_array[1]+"-"+date_array[0];
  return new_date;
}
 
  
  $('#unitdata').change(function(){  

    var unitID = $('#unitdata').val();
    var status = $('#status').val();
            if(unitID) { $('select[name="user"]').empty();
                $.ajax({
                    type: "post",
                    dataType: "json",
                    url: baseURL+"admin/Reports/finduserdataforall",
                    data : { unit_id : unitID, status:status },
                    success:function(data) {  

                   
                        $('select[name="user"]').append('<option value="none">--Select user--</option>');
                        $.each(data, function(key, value) {  
                            $('select[name="user"]').append('<option value="'+ value.user_id +'" >'+ value.fname+' '+value.lname +'</option>');
                        });
                    }
                });
            }else{
                $('select[name="user"]').empty();
            }
        });
  
   $('#status').change(function(){  

    var unitID = $('#unitdata').val();
    var status = $('#status').val();
            if(unitID) { $('select[name="user"]').empty();
                $.ajax({
                    type: "post",
                    dataType: "json",
                    url: baseURL+"admin/Reports/finduserdataforall",
                    data : { unit_id : unitID, status:status },
                    success:function(data) {  

                   
                        $('select[name="user"]').append('<option value="none">--Select user--</option>');
                        $.each(data, function(key, value) {  
                            $('select[name="user"]').append('<option value="'+ value.user_id +'">'+ value.fname+' '+value.lname +'</option>');
                        });
                    }
                });
            }else{
                $('select[name="user"]').empty();
            }
        });





 function edit(user_id,date,shift_id,unit_id,hours,edit,comment,shitfHour,iVal,timelogid,decimal_hour)
 {

 //console.log($timelogid);
 	// console.log($date);
 	// console.log($shift_id);
 	// console.log($unit_id); 
  // console.log(shift_category)
  var hour_edit=document.getElementById(hours).innerText;    //console.log(hour_edit);
  var comment_edit=document.getElementById(comment).innerText;   //console.log(comment_edit);
	var html = '';
  var shift_category=document.getElementById('shiftcategory_'+iVal).innerText; //alert(shift_category);
  html += '<select class="form-control" name="shift-category" id="shift-category">';
  if(shift_category == 1 || shift_category == 3 || shift_category == ''){
    html += '<option selected="selected" value="1">Day</option><option value="2">Night</option>';
            
  }else{
    html += '<option value="1">Day</option><option selected="selected" value="2">Night</option>';
  }
           
          html += '</select>';
   html +="<label style='padding-left:314px;margin:0px;'>(hh:mm)</label>";
   if(hour_edit==0 && comment_edit==0)
   {
   
   html += "<textarea class='form-control' rows='1' cols='40' maxlength='30' Placeholder='Enter additional hours(hh:mm)' onfocusout='myFunction()' id='additional_hours' style='padding-top=2px;margin:2px;'></textarea><br>";
    html += '<label  id="decimal_hour" style="display:block;width:150px;font-size:14px;">'+' '+'</label>';
	 html += "<textarea class='form-control' rows='4' cols='40' maxlength='100' Placeholder='Enter comment' id='text' style='padding-top=2px;margin:6px;'></textarea>";
   }
   else
   {  
    html += '<textarea class="form-control" rows="1" cols="40" maxlength="30" Placeholder="Enter additional hours(hh:mm)" onfocusout="myFunction()" id="additional_hours" style="padding-top:2px;;margin:2px;">'+hour_edit+'</textarea><br>';
    html += '<label  id="decimal_hour" style="display:block;width:150px;font-size:14px;">'+'Decimal hour : '+''+decimal_hour+'</label>';
	  html += '<textarea class="form-control" rows="4" cols="40" maxlength="100" Placeholder="Enter comment" id="text" style="padding-top=2px;margin:6px;">'+comment_edit+'</textarea>';
   }
 	

 	swal({
						  title: "Enter Additional Hours",
						  text: html,
						  // --------------^-- define html element with id
						  html: true,
						  showCancelButton: true,
						  closeOnConfirm: false, 
						  inputPlaceholder: "Additional hours"
						}, function(isConfirm) { 

            if(isConfirm){
              var day_additional_hours = '';
              var night_additional_hours = '';
						  var hours=$('#additional_hours').val();  //console.log(hours);
						  var comment=$('#text').val();  //console.log(comment);
						  var shift_cat = $( "#shift-category option:selected" ).val();
              if(shift_cat == 1){
                day_additional_hours = hours;
              }else{
                night_additional_hours = hours;
              }
						  if(hours && comment){  
                var regex = /^-?\d{2}:\d{2}$/;
                $s=hours.split(':'); 

                    if(regex.test(hours) == false) 
                    {
                      swal.showInputError("Please correct hours format");
                    }
                    else if($s[1]>=60)
                    {
                      swal.showInputError("Minutes should be smaller than 60");
                    }
                    else
                    {
	                      $.ajax({
	                          url: baseURL+"admin/Reports/insertadditionalhours",
	                          type: "POST",
	                          data: {

	                              additional_hours: hours,
	                              comment:comment,
	                              user_id: user_id,
	                              date: date,
	                              shift_id: shift_id,
	                              unit_id: unit_id, 
                                shiftHour:shitfHour,
                                day_additional_hours : day_additional_hours,
                                night_additional_hours : night_additional_hours,
                                timelogid :timelogid
	                          },
		                      success: function (data) {     
                            console.log(data)
		                      	if(data.status=='true')
		                      	{
                              new_hour=getPayrollformat1(hours);//alert(new_hour);
		                      		swal("Updated successfully");
		                      		  //console.log($('#hours').text("hours"));
		                      		var global_message = hours;
		                      		var global_message1 = "Edit"; 
		                      		var global_message2 = comment;    
                              document.getElementById("totalhour_"+iVal).innerText = data.hours;
                              document.getElementById("hours1_"+iVal).innerText = new_hour;
                              document.getElementById("hours_"+iVal).innerText = hours;
                              document.getElementById("comment_"+iVal).innerText = comment;
                              document.getElementById("edit_"+iVal).innerText = "Edit";
                              if(day_additional_hours=='')
                              {
                                  document.getElementById('shiftcategory_'+iVal).innerText="2";
                              }
                              else
                              {
                                  document.getElementById('shiftcategory_'+iVal).innerText="1";
                              }
                              //location.reload();
                              //document.getElementById("edit"+iVal).innerText = "Edit";
                
               		//$('label[id*="edit"]').text('')
		                      		//console.log(document.getElementById($hours).innerText);

            									// var cb=document.getElementById(hours).innerText = global_message; 
            									// var cb1=document.getElementById(edit).innerText = global_message1;  
            									// var cb2=document.getElementById(comment).innerText = global_message2;  

		                      	}
		                      	else
		                      	{
		                      		 swal("Error inserting additional hours, please try again.");
		                      	}
	                            //window.location.reload();
	                         } 
						            });
	                  }
                  }
	                   else if(comment=='' && hours=='')
	                  {
                           swal.showInputError("Please enter hour & comment");
	                  }
	                  else if(comment=='')
	                  {
	                  	swal.showInputError("Please enter comment");
	                  }
	                  else if(hours=='')
	                  {
	                  	swal.showInputError("Please enter hour");
	                  }
                    
                  }
                  else
                  {

                  }
	                  
	                 
	              });

 }

function getPayrollformat1($time)
{  
  // $hms = "48.90";
  
  $hms = $time.split(':'); //alert($hms);
  var res = $time.replace(":", ".");  
  if(res>=0)
  { //alert('hii');
    $sum=(+$hms[0]) + (+($hms[1]/60));
    $hms_new=$sum.toFixed(2);
  }
  else
  {
    $time1=Math.abs($hms[0]);
    $sum=(+$time1) + (+($hms[1]/60)); 
    $hms_new=$sum.toFixed(2);
    $hms_new='-'+$hms_new;
    
  }
  return $hms_new;
}

function myFunction() { 
$("#decimal_hour").val(" ");
  var hour = $('textarea#additional_hours').val(); //console.log(hour);
  var regex = /^-?\d{2}:\d{2}$/;
                $s=hour.split(':'); 

                    if(regex.test(hour) == false) 
                    {
                      swal.showInputError("Please correct hours format");
                    }
                    else if($s[1]>=60)
                    {
                      swal.showInputError("Minutes should be smaller than 60");
                    }
                    else
                    { 
                        $.ajax({
                                      type: "post",
                                      dataType: "json",
                                      url: baseURL+"admin/Reports/changehourtodecimal",
                                      data : { hour : hour },
                                      success:function(data) {  //console.log(data); 
                                         document.getElementById('decimal_hour').innerHTML = "Decimal hour : " + data; 

                                      }
                                  });
                    }

}
 
