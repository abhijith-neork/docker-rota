$(function() {          
                                    $(document).ready(function() { $("#img-loader-data").hide();

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
                                   
                                    "order": [[ 7, "desc" ], [8, "asc" ]],
                                    "displayLength": 25,
                                    "lengthMenu": [[25, 50, 75, 100, -1], [25, 50, 75, 100, "All"]],
                                  //   initComplete: function() {
                                  //   this.api().columns(8).every(function() {
                                  //     var column = this;  
                                  //     if (column.index() !== 0) 
                                  //       {  
                                  //          $(column.header()).append("<br>");   
                                  //     //added class "mymsel"           
                                  //     var select = $('<select class="mymsel" multiple="multiple"><option value=""></option></select>')
                                  //       .appendTo($(column.header()))
                                  //       .on('change', function() {  
                                  //         var vals = $('option:selected', this).map(function(index, element) {
                                  //           return $.fn.dataTable.util.escapeRegex($(element).val());
                                  //         }).toArray().join('|');

                                  //         column
                                  //           .search(vals.length > 0 ? '^(' + vals + ')$' : '', true, false)
                                  //           .draw();
                                  //       });

                                  //     column.data().unique().sort().each(function(d, j) {
                                  //       select.append('<option style="width:120px;height:2px;" value="' + d + '">' + d + '</option>')
                                  //     });
                                  //   }
                                  //   });
                                  //   //select2 init for .mymsel class
                                  //   $(".mymsel").select2();
                                  // },  drawCallback: function() {
                                  //     $('[data-toggle="popover"]').popover();
                                  // }   

 

                                        
                                    });

                                    $("#filter_status").on('change', function() {
                                       var filter_status = $(this).val();
                                       var from_date = $("#from-datepicker").val();   
                                       var to_date = $("#to-datepicker").val();  
                                       var jobrole=$('#jobrole').val();  
                                       var unit=$('#unitdata').val();
                                       var status=$('#status').val(); 
                                       $.ajax({ 
                                                    type: "post",dataType: "json",
                                                    url: baseURL+"admin/holiday/findholiday",
                                                    data: {  
                                                      from_date:from_date,
                                                      to_date:to_date, 
                                                      jobrole:jobrole,
                                                      unit:unit,
                                                      status:status,
                                                      filter_status:filter_status },
                                                    success: function(data) {  
                                                        table.clear(); 
                                                        table.rows.add(data).draw(); 
                                                        $("#img-loader-data").hide();
                                                    } 
                                              });  
                                              $('#myInput').on( 'keyup', function () {
                                              table.search( this.value ).draw();
                                          } );
                                           

                                     });

                                    
                                     $(document).on('click', '.search', function(e) {  
                                     var from_date = $("#from-datepicker").val();   
                                     var to_date = $("#to-datepicker").val();  
                                     var jobrole=$('#jobrole').val();  
                                     var unit=$('#unitdata').val();
                                     var status=$('#status').val(); 
                                     var filter_status='';
                                     
                                     if(from_date=='' && to_date=='')
                                     { 
                                      swal("Please select dates");
         
                                     }
                                     else if(from_date=='')
                                     {
                                       swal("Please select from date");
                                     }
                                     else if(to_date=='')
                                     {
                                       swal("Please select to date");
                                     }
                                     else if(new Date(corectDateFormatbydate(from_date)).getTime() > new Date(corectDateFormatbydate(to_date)).getTime())
                                     {
                                       swal("Invalid dates.");
                                     }
                                     else
                                     {
                                      $("#img-loader-data").show();
                                     $.ajax({ 
                                                    type: "post",dataType: "json",
                                                    url: baseURL+"admin/holiday/findholiday",
                                                    data: {  
                                                      from_date:from_date,
                                                      to_date:to_date, 
                                                      jobrole:jobrole,
                                                      unit:unit,
                                                      status:status,
                                                      filter_status:filter_status },
                                                    success: function(data) { 
                                                        table.clear(); 
                                                        table.rows.add(data).draw(); 
                                                        $("#img-loader-data").hide();
                                                    } 
                                              }); 
                                     }
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
function approveFunction(id,from_date,to_date,unit,designation,$user_id,$year,$annual,$new_year,$hour) { 
    var user_id = $user_id;
    var year = $year;
    var annual = $annual;
    var hour_edit= $hour.replace(".", ":");
    var html = '';
    //ajax call
    $.ajax({
      url: baseURL+"admin/holiday/findholidaydetailsforapproval",
      type: "POST",
      data: {
          from_date: from_date,
          to_date : to_date,
          unit : unit,
          designation:designation,
          user_id : user_id
      },
       success: function (data) {
        if(data.status == 1)
        {
          var leave_details = data.result;
          var count = 1;
          if(leave_details.length > 0){
            html += '<div style="color: red";>';
            html += '<p style="color: red";><strong>The following '+leave_details[0].designation_name+' has taken leave on this dates.</strong></p>';
            for (var i = 0 ; i < leave_details.length; i++) {
              if(leave_details[i].from_date == leave_details[i].to_date){
                html += '<label>'+count+':'+leave_details[i].username+' : '+corectDateFormat(leave_details[i].to_date)+'</label>';
              }else{
                html += '<label>'+count+':'+leave_details[i].username+' : '+corectDateFormat(leave_details[i].from_date)+' to '+corectDateFormat(leave_details[i].to_date)+'</label>';
              }
              count++;
            }
            html += '</div>';
          }
          html += '<textarea class="form-control" rows="1" cols="40" maxlength="30" Placeholder="Enter hours(hh:mm)" id="hours" style="padding-top:2px;;margin:2px;width:88%;">'+hour_edit+'</textarea><br>';
          html += "<textarea rows='4' cols='40' maxlength='50' id='holiday_comment'></textarea>";
          swal({
              title: "Enter your comments!",
              text: html,
              // --------------^-- define html element with id
              html: true,
              showCancelButton: true,
              cancelButtonText: "Close",
              closeOnCancel : true,
              closeOnConfirm: false
          }, function (isConfirm) {  
              if(isConfirm){
                  var comment = $("#holiday_comment").val();  
                  var hours = $("#hours").val(); 

                    var regex = /^\d{2,3}:\d{2}$/;
                    $s=hours.split(':'); 

                    if(comment=='' && hours=='')
                    {
                          swal.showInputError("Please enter hour & comment");
                          event.preventDefault();
                    }
                    else if(comment=='')
                    {
                          swal.showInputError("Please enter comment");
                          event.preventDefault();
                    }
                    else if(hours=='')
                    {
                          swal.showInputError("Please enter hour");
                          event.preventDefault();
                    }
                    else if(regex.test(hours) == false) 
                    {
                      swal.showInputError("Please correct hours format");
                      event.preventDefault();
                    }
                    else if($s[1]>=60)
                    {
                      swal.showInputError("Minutes should be smaller than 60");
                      event.preventDefault();
                    }
                    else
                    {
                      $.ajax({
                          url: baseURL+"admin/holiday/approveholiday",
                          type: "POST",dataType: "json",
                          data: {
                              user_id: user_id,
                              comment : comment,
                              holiday_id : id,
                              unit_id : unit,
                              from_date : from_date,
                              to_date : to_date,
                              hour : hours

                          },
                          success: function (data) {  
                             // if(annual=='')
                             // {
                                 //$leave=getLeave(user_id,year); alert($leave);
                             // }
                             // else
                             // {
                             //     $leave=annual;
                             // } 
                             $new_year= $new_year;
                             $days= data['days'];
                             $leave_year=data['total']+' '+'('+$new_year+')'; console.log($leave_year);
                              $('#status_'+id).html(" ");
                              $('#leaves_'+id).html(" ");
                              $('#days_'+id).html(" ");
                              $('#status_'+id).html("Approved");
                              $('#leaves_'+id).html($leave_year);
                              $('#days_'+id).html($days); //console.log('#days'+id);
                              $('.Approve_'+id).hide(); 
                              $('.Reject_button_'+id).show();
                              //event.preventDefault();
                              swal("Holiday applicaton approved");
                              //window.location.reload();
                          },
                          error: function (xhr, ajaxOptions, thrownError) {
                              event.preventDefault();
                              swal("Bad attempt");
                              //window.location.reload();
                          }
                      });
                  }
                  
                    
            }
            else
            { 

            }
          });
        }
        else
        {
          window.location.reload();
        }
      }
    });
}
function getLeave(user_id,year)
{
   $.ajax({
      url: baseURL+"admin/holiday/getLeave",
      type: "POST",
      data: {

        user_id:user_id,
        year:year

      },success: function (data) { 

      }
    });
}
function rejectFunction(id,$user_id,$status,$hour,$remaining,$year,$annual,$new_year) {
   //alert($remaining); 
    var year = $year;
    var annual = $annual;
    $.ajax({
      url: baseURL+"admin/holiday/checkSessionExpired",
      type: "POST",
      data: {

      },success: function (data) {
        if(data.status == 1)
        {
          var user_id = $user_id;
          swal({
              title: "Enter your comments!",
              text: "<textarea rows='4' cols='40' maxlength='50' id='holiday_comment'></textarea>",
              // --------------^-- define html element with id
              html: true,
              showCancelButton: true,
              cancelButtonText: "Close",
              closeOnCancel : true,
              closeOnConfirm: true
          }, function (isConfirm) { 
              if(isConfirm){ 
                  var comment = $("#holiday_comment").val();
                  if(comment){
                      $.ajax({
                          url: baseURL+"admin/holiday/rejectholiday",
                          type: "POST",dataType: "json",
                          data: {
                              user_id: user_id,
                              comment : comment,
                              holiday_id : id,
                              status : $status,
                              hour : $hour,
                              remaining: $remaining,
                          },
                          success: function (data) {
                             // if(annual=='')
                             // {
                             //     $leave=getLeave(user_id,year);
                             // }
                             // else
                             // {
                             //     $leave=annual;
                             // } 
                             $new_year=$new_year;
                             $leave_year=data+' '+'('+$new_year+')'; 
                              $('#status_'+id).html(" ");
                              $('#leaves_'+id).html(" ");
                              $('#status_'+id).html("Rejected");
                              $('#leaves_'+id).html($leave_year); 
                              $('.Reject_'+id).hide();
                              $('.Accept_button_'+id).show();
                              event.preventDefault();
                              swal("Holiday applicaton rejected");
                              //window.location.reload();
                          },
                          error: function (xhr, ajaxOptions, thrownError) {
                              event.preventDefault();
                              swal("Bad attempt");
                              //window.location.reload();
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
        else
        {
          window.location.reload();
        }
      }
    });
}
function corectDateFormatbydate(date){
      var date_array = date.split("/");
      var date_with_slash = date_array[2]+"-"+date_array[1]+"-"+date_array[0];
      return date_with_slash;
    }

function corectDateFormat(date){
      var date_array = date.split("-");
      var date_with_slash = date_array[2]+"/"+date_array[1]+"/"+date_array[0];
      return date_with_slash;
    }


    $(document).ajaxSuccess(function () {
    $("[data-toggle=popover]").popover();
    $("[data-toggle=tooltip]").tooltip();
    // any other code
});