$(function() {
    $(document).ready(function() {
      var datePicker = $("#from-datepicker").datepicker({ 
         beforeShow: function(input, inst) {
        var widget = $(inst).datepicker('widget');
        widget.css('margin-right', $(input).outerWidth() - widget.outerWidth());
     },
         dateFormat: 'dd/mm/yy'
 });
                                    $("#from-datepicker").on("change", function () {
                                        var fromdate = $(this).val();
                                        //alert(fromdate);
                                    }); 
      var user_from_other_unit = [];
 $(".fix-header").scroll(function () {
    datePicker.datepicker('hide');
    $('#from-datepicker').blur();             
});
      // // var now = new Date();

      // //   var day = ("0" + now.getDate()).slice(-2);
      // //   var month = ("0" + (now.getMonth() + 1)).slice(-2);

      // //   var today = now.getFullYear()+"-"+(month)+"-"+(day) ;

      // //   $('#end_date').attr('max', today);
      //   //$('#end_date').val(today);  
        
      // $('#end_date').change(function(e) {

      //       var d = new Date(e.target.value); console.log(d.getDay() );
      //       if(d.getDay() != 0 ) {
      //         $('#end_date').attr('disabled',true) 

      //       } else { 
      //        $('#end_date').attr('disabled',false);
      //     }
      // })

      $('.shift_select').click(function() {
        var shift_id = $(event.currentTarget).attr('data-shiftid');
        $("input[name=shift_user_"+shift_id+"]").each(function () {
          if($(".shift_check_"+shift_id).is(':checked')){
            $(".shift_check_"+shift_id).prop("checked", false);
            $(".checkusers_"+shift_id).prop("checked", false);
          }else{
            if($(".no_staff_"+shift_id).is(":visible")){

            }else{
              $(".shift_check_"+shift_id).prop("checked", true);
              $(".checkusers_"+shift_id).prop("checked", true);
            }            
          }
        });
      });
      $('.users_not_assigned').click(function() {
        $("input[name=non_assignstaff_check]").each(function () {
          if($(".non_assignuser_check").is(':checked')){
            $(".non_assignuser_check").prop("checked", false);
            $(".user_not_assigned").prop("checked", false);
          }else{
            if($(".no_staffs_area").is(":visible")){

            }else{
              $(".non_assignuser_check").prop("checked", true);
              $(".user_not_assigned").prop("checked", true);
            }            
          }
        });
      });
      var main_unit_id = $( "#wunit2 option:selected" ).val();
      if(assigned_users && assigned_users.length){
              var assigned_users_length =  assigned_users.length;  
              var users_frm_other = [];
              var nonsigneduser_other = [];
              for(var i=0 ; i<assigned_users_length; i++){
                if($(".shiftstaff_"+assigned_users[i].user_id).length > 0){
                  $(".shiftstaff_"+assigned_users[i].user_id).attr("checked","checked");
                }
                var checked_length = $('.checkusers_'+assigned_users[i].shift_id+':checked').length;
                var total_checkbox = $('.checkusers_'+assigned_users[i].shift_id).length;
                if(checked_length !=0 && total_checkbox!=0){
                  if(checked_length == total_checkbox){
                    $(".shift_check_"+assigned_users[i].shift_id).prop("checked", true);
                  }else{
                    $(".shift_check_"+assigned_users[i].shift_id).prop("checked", false);
                  }                  
                }else{
                  $(".shift_check_"+assigned_users[i].shift_id).prop("checked", false);
                }
                if($(".non_staffs_"+assigned_users[i].user_id).length > 0){
                  $(".non_staffs_"+assigned_users[i].user_id).attr("checked","checked");
                }
                var checked_length = $('.user_not_assigned:checked').length;
                var total_checkbox = $('.user_not_assigned').length;
                if(checked_length !=0 && total_checkbox!=0){
                  if(checked_length == total_checkbox){
                    $(".non_assignuser_check").prop("checked", true);
                  }else{
                    $(".non_assignuser_check").prop("checked", false);
                  }
                }else{
                  $(".non_assignuser_check").prop("checked", false);
                }

                
                if($(".shiftstaff_"+assigned_users[i].user_id).length == 0 && $(".non_staffs_"+assigned_users[i].user_id).length == 0){
                  var object = {
                    'user_id' : assigned_users[i].user_id,
                    'from_unit': assigned_users[i].from_unit
                  };
                  users_frm_other.push(object);
                }
              }
              if(users_frm_other.length > 0)
              { 
                users_frm_other_unit = JSON.stringify(users_frm_other); 
                $.ajax({
                url:baseURL+'admin/rota/findUsersOnOtherUnit',
                type:'post',
                dataType:'json',
                delay: 250,
                data: {
                  users_frm_other_unit : users_frm_other_unit,
                },
                success: function (data) 
                { 
                  var count = data.final_result.length; 
                  var user_details = data.final_result;  
                  var unit_color = data.unit_color;  
                  for(var i=0 ; i<count; i++)
                  {
                    var user_data = user_details[i].result; 
                    var unit_color = user_details[i].color_unit;
                    var from_unit = user_details[i].from_unit;
                    var random_string = Math.random().toString(36).substring(7); 
                    if(user_data[0]['default_shift'] != null)
                    {  
                      if(unit_color!=null){
                        ucolor=unit_color.color_unit;
                      }
                      else
                      {
                        ucolor='';
                      }
                      var html = '';
                      html += '<td class="shift_data_'+user_data[0]['default_shift']+'">'+
                      '<label class="" style="color:'+ucolor+'";>'+
                      '&nbsp;<input type="checkbox" name="usercheck_'+random_string+'" checked="checked" class="checkItem checkusers_'+user_data[0]['default_shift']+' shiftstaff_'+user_data[0]['id']+' staff_'+user_data[0]['id']+'_'+user_data[0]['default_shift']+'_'+user_data[0]['fname']+' '+user_data[0]['lname']+'_'+from_unit+'_'+user_data[0]['designation_code']+'_'+user_data[0]['shift_name']+'" id="chkProdTomove" value="'+user_data[0]['id']+'_'+user_data[0]['default_shift']+'_'+user_data[0]['fname']+' '+user_data[0]['lname']+'_'+main_unit_id+'_'+from_unit+'_'+user_data[0]['designation_code']+'_'+user_data[0]['shift_shortcut']+'_'+user_data[0]['gender']+'">'+
                      '<span class="check-box-effect"></span>&nbsp;'+user_data[0]['fname']+' '+user_data[0]['lname']+'</label>'
                      '</td>';
                      if($(".no_staff_"+user_data[0]['default_shift']))
                      {
                        $(".no_staff_"+user_data[0]['default_shift']).hide();
                      }
                      $('.shift_'+user_data[0]['default_shift']).last().append(html);

                      var checked_length = $('.checkusers_'+user_data[0]['default_shift']+':checked').length;
                      var total_checkbox = $('.checkusers_'+user_data[0]['default_shift']).length;
                      if(checked_length !=0 && total_checkbox!=0){
                        if(checked_length == total_checkbox){
                          $(".shift_check_"+user_data[0]['default_shift']).prop("checked", true);
                        }else{
                          $(".shift_check_"+user_data[0]['default_shift']).prop("checked", false);
                        }
                      }else{
                        $(".shift_check_"+user_data[0]['default_shift']).prop("checked", false);
                      }
                    }
                    else
                    {
                      var html = '';
                      html += '<td>'+
                      '<label style="color:'+unit_color.color_unit+'";>'+
                      '&nbsp<input type="checkbox" checked="checked" name="usercheck_'+random_string+'" class="checkItem user_not_assigned non_staffs_'+user_data[0]['id']+'" id="'+user_data[0]['id']+'" value="'+user_data[0]['id']+'_'+'0_'+user_data[0]['fname']+' '+user_data[0]['lname']+'_'+main_unit_id+'_'+from_unit+'_'+user_data[0]['designation_code']+'_'+user_data[0]['shift_name']+'_'+user_data[0]['gender']+'">'+
                      '<span class="check-box-effect"></span>&nbsp;'+user_data[0]['fname']+' '+user_data[0]['lname']+'</label>'+
                      '</td>';
                      if($(".no_staffs_area"))
                      {
                        $(".no_staffs_area").hide();
                      }
                      $('.non_staff').last().append(html);
                      var checked_length = $('.user_not_assigned:checked').length;
                      var total_checkbox = $('.user_not_assigned').length;
                      if(checked_length !=0 && total_checkbox!=0){
                        if(checked_length == total_checkbox){
                          $(".non_assignuser_check").prop("checked", true);
                        }else{
                          $(".non_assignuser_check").prop("checked", false);
                        }
                      }else{
                        $(".non_assignuser_check").prop("checked", false);
                      }
                    }

                  }//end for loop 


                }
              });
              }
            }
localStorage.removeItem("rota_id");
$(window).resize(function(){
  $(".sweet-alert").css("margin-top",-$(".sweet-alert").outerHeight()/2);
});
$(document).on('change', '.isSelect', function(e) { 
  $('form').submit();
});
 $(".complex-colorpicker").asColorPicker({
        mode: 'complex'
    }); 

function isEmpty(obj) {
    for(var key in obj) {
        if(obj.hasOwnProperty(key))
            return false;
    }
    return true;
}
$('#add').click(function(event){
  //console.log(user_from_other_unit);
  var new_users_array = [];
  var userid_array_fordelete = [];
  var action = $(event.currentTarget).attr('data-action');
  var from_unit = $("#from_unit").val();
  var unit=$('#wunit2').val();
  var nurse_day_count=$('#nurse_day_count').val();
  var nurse_night_count=$('#nurse_night_count').val();
  var day_shift_min=$('#day_shift_min').val();
  var day_shift_max=$('#day_shift_max').val();
  var night_shift_min=$('#night_shift_min').val();
  var night_shift_max=$('#night_shift_max').val();
  var num_patients=$('#num_patients').val();
  var designation=$('#designation').val();
  var rota_settings=$('#rota_settings').val();
  var up_rota_id =$('#up_rota_id').val();
  var new_status=$('#new_status').val();  
  var checkItem=$('.checkItem').val();
  var end_date=$('#from-datepicker').val();
  var session_id = $("#session_id").val();
  var serializedObj = {};
  $("form table input:checkbox").each(function(){
    if(this.checked==true){
      serializedObj[this.name]=this.value;
      //creating an array with userids of cheched users
      new_users_array.push(this.value.split('_')[0]);
    }
  });
  //in case of update users we are passing all userids which are alreay in rotaschedule under
  // the specified unit in assigned_users array. So we are using this array for creating
  //new array for user deletion 
  if(assigned_users.length > 0){
    for(var i=0 ; i<assigned_users.length; i++){
      if (new_users_array.indexOf(assigned_users[i].user_id) == -1){
        userid_array_fordelete.push(assigned_users[i].user_id);
      }
    }
  }
  if(unit){
    if(isEmpty(serializedObj)) {
      $('body, html').stop().animate({ scrollTop: 0 }, 100);
      $(".error_check").html("Please select users");
    } else {
      if(day_shift_min && 
        day_shift_max && 
        night_shift_min && 
        night_shift_max && 
        num_patients && 
        designation &&
        nurse_day_count &&
        nurse_night_count){
        if($.isNumeric(day_shift_min) && $.isNumeric(day_shift_max) && 
        $.isNumeric(night_shift_min) && $.isNumeric(night_shift_max) && 
        $.isNumeric(num_patients) && $.isNumeric(designation) &&  
        $.isNumeric(nurse_day_count) && $.isNumeric(nurse_night_count))
        {
           if(parseInt(day_shift_min) < parseInt(day_shift_max))
          {  
            if (day_shift_min == Math.floor(day_shift_min) && day_shift_max == Math.floor(day_shift_max) ) 
            {
              if(Math.sign(day_shift_min) !==-1 && Math.sign(day_shift_max) !==-1)
              {
                if(parseInt(night_shift_min) < parseInt(night_shift_max))
                {
                  if (night_shift_min == Math.floor(night_shift_min) && night_shift_max == Math.floor(night_shift_max) ) 
                  {
                      if(Math.sign(night_shift_min) !==-1 && Math.sign(night_shift_max) !==-1)
                      {
                        if (nurse_day_count == Math.floor(nurse_day_count) && nurse_night_count == Math.floor(nurse_night_count) &&
                            num_patients == Math.floor(num_patients) && designation == Math.floor(designation) ) 
                        {
                          if(Math.sign(nurse_day_count) !==-1 && Math.sign(nurse_night_count) !==-1)
                          {
                              if(Math.sign(num_patients) !==-1 && Math.sign(designation) !==-1)
                            {
                                // $.redirect(baseURL+'admin/Test_rota/rota',
                                $.redirect(baseURL+'admin/Rota/rota',
                                {
                                  'unit_id': unit, 
                                  'nurse_day_count':nurse_day_count,
                                  'nurse_night_count':nurse_night_count,
                                  'day_shift_min': day_shift_min,
                                  'day_shift_max': day_shift_max,
                                  'night_shift_min': night_shift_min,
                                  'night_shift_max': night_shift_max,
                                  'num_patients': num_patients,
                                  'designation': designation,
                                  'users': serializedObj,
                                  'from_unit': from_unit,
                                  'rota_settings':rota_settings,
                                  'new_status':new_status,
                                  'action' : action,
                                  'up_rota_id' : up_rota_id,
                                  'end_date' : end_date,
                                  'userid_array_fordelete' : userid_array_fordelete,
                                  'user_from_other_unit' : user_from_other_unit,
                                  'session_id' : session_id
                                });
                      }
                            else
                            {
                            $('body, html').stop().animate({ scrollTop: 0 }, 100);
                            $(".error_check").html("Please enter a value that is greater than -1");

                            }
                          }
                          else
                          {
                          $('body, html').stop().animate({ scrollTop: 0 }, 100);
                          $(".error_check").html("Nurse count must be higher than -1");
                          }
                        }
                        else
                        {
                          $('body, html').stop().animate({ scrollTop: 0 }, 100);
                          $(".error_check").html("Please enter an interger value");
                        }
                      }
                      else
                      {
                        $('body, html').stop().animate({ scrollTop: 0 }, 100);
                        $(".error_check").html("Night shift count must be higher than -1");                
                      }
                    }
                    else
                    {
                      $('body, html').stop().animate({ scrollTop: 0 }, 100);
                      $(".error_check").html("Night shift count must be an integer value");
                    }
                  }
                else
                {
                  $('body, html').stop().animate({ scrollTop: 0 }, 100);
                  $(".error_check").html("Maximum night shift count must be higher than minimum night shift count");
                }
              }
              else
              {
                 $('body, html').stop().animate({ scrollTop: 0 }, 100);
                 $(".error_check").html("Day shift count must be higher than -1");

              }
            }
            else
            {
              $('body, html').stop().animate({ scrollTop: 0 }, 100);
              $(".error_check").html("Day shift count must be an integer value");
            }
          }
          else
          {
            $('body, html').stop().animate({ scrollTop: 0 }, 100);
            $(".error_check").html("Maximum day shift count must be higher than minimum day shift count");
          }
        }
        else
        {
          $('body, html').stop().animate({ scrollTop: 0 }, 100);
          $(".error_check").html("Please enter numeric values");
        }
      }
      else
      {
        $('body, html').stop().animate({ scrollTop: 0 }, 100);
        $(".error_check").html("Please fill all mandatory fields");
      }

        
    }
  }else{
    $('body, html').stop().animate({ scrollTop: 0 }, 100);
    $(".error_check").html("Please select the unit");
  }
});

$(document).on('click', '.staff_close', function() {
  var shift_id = $(this).attr('id');
  $(this).parent().remove();
  var count = $("table tr.shift_"+shift_id).children("td.shift_data_"+shift_id).length;
  if(count == 0){
    $(".shift_check_"+shift_id).prop("checked", false);
    $(".no_staff_"+shift_id).show();
  }
});

$(document).on('click', '.non_staff_close', function() {
  $(this).parent().remove();
  var count = $("table.non_asign_staff tr").children("td").length - 1;
  if(count == 0){
    $(".non_assignuser_check").prop("checked", false);
    $(".no_staffs_area").show();
  }
});


$('#addShift').click(function(){
  
  var unit_id = $( "#units option:selected" ).val(),
      main_unit_id = $( "#wunit2 option:selected" ).val(),
      html = '',
      non_staff_details,
      shifts_details_array;
  if(unit_id){
    var checkbox_count = $('input[type="checkbox"]').length; 
    $.ajax({
      url: baseURL+"admin/rota/findUsersOnOtherLocation",
      type: "POST",
      async: false,
      data: {
          unit_id: unit_id
      },
      success: function (shifts) {
        if(shifts.length > 0){
          for (var key in shifts) {
            shifts_details_array = shifts[key];
          }
        }
      }
    });
    $.ajax({
      url: baseURL+"admin/rota/getNonAssignedUnitStaffs",
      type: "POST",
      async: false,
      data: {
          unit_id: unit_id
      },
      success: function (staffs) { 
        if(staffs.length > 0){
          for (var key in staffs) {
            non_staff_details = staffs[key]; 
          }
        }
      }
    }); 
    createHtml(shifts_details_array,unit_id,non_staff_details,checkbox_count,main_unit_id);
  }else{
    swal({
      title : "",
      text: "Please select unit!",
      icon: "warning",
      button: "ok",
    });
  }
});

function createHtml(shifts_details_array,unit_id,non_staff_details,checkbox_count,main_unit_id) {
  var html = '',
      shifts_data;
  html +=  '<div class="row"><div class="col-md-12">';
  //array for keeping all unit ids from other location
  if(user_from_other_unit.indexOf(unit_id) == -1){
    user_from_other_unit.push(unit_id);
  }
  for( var i = 0; i < shifts_details_array.length; i++ ) {
    shifts_data = shifts_details_array[ i ]; 
    html += '<label for="wshiftname2" value="'+shifts_data['id']+'" style="color: '+shifts_data['color_unit']+'";> <b> '+shifts_data['shift_name']+' </b> </label></br>';
    $.ajax({
      url: baseURL+"admin/rota/findStaffOnShift",
      type: "POST",
      async: false,
      data: {
          unit_id  : unit_id,
          shift_id : shifts_data['id']
      },
      success: function (staffs) {
        staffs.forEach(function (staff_details_array) {
          html += '<div class="table-responsive m-t-40">'+
                  '<table id="myTable" class="table table-bordered table-striped">'+
                  '<tbody><tr>';
          if(staff_details_array.length >0)
          {

            $c = 0; // Our counter
            $n = 6;
            for( var i = 0; i < staff_details_array.length; i++ ) 
            {
              if($c % $n == 0 && $c != 0)  
              {
                html += '<tr> </tr>';
              }
              $c++; 

              var staff_data = staff_details_array[ i ]; 
              html += '<td class="shift_data_'+shifts_data['id']+'">'+
                    '<label class="">'+
                    '&nbsp;<input type="checkbox" name="staff_check" class="checkItem checkusers_'+shifts_data['id']+' shiftstaff_'+staff_data['user_id']+' staff_'+staff_data['user_id']+'_'+shifts_data['id']+'_'+staff_data['fname']+'_'+staff_data['lname']+'_'+staff_data['designation_code']+'_'+staff_data['shift_shortcut']+'" id="'+staff_data['user_id']+'" value="'+staff_data['user_id']+'_'+shifts_data['id']+'_'+staff_data['fname']+' '+staff_data['lname']+'_'+main_unit_id+'_'+unit_id+'_'+staff_data['designation_code']+'_'+staff_data['shift_shortcut']+'_'+staff_data['gender']+'">'+
                   '<span class="check-box-effect"></span>&nbsp;'+staff_data['fname']+' '+staff_data['lname']+' '+'('+staff_data['designation_code']+')'+'</label>'
                    '</td>';
            }
          }
          else
          {
            html += '<td>No employees</td>';
          }          
          html += '<input type="hidden" id="from_unit" name="custId" value="'+unit_id+'"></tr></tbody></table></div><br>';
        });
      }
    });
  }
  html += '<div class="table-responsive m-t-40">'+
          '<label for="wshiftname22"  style="color: #009efb";> <b> Not assigned </b></label>'+
          '<table id="myTable" class="table table-bordered table-striped"><tbody><tr>';
  if(non_staff_details.length >0){
    $c = 0; // Our counter
    $n = 6;
    for( var i = 0; i < non_staff_details.length; i++ ) {
      if($c % $n == 0 && $c != 0)  
      {
      html += '<tr></tr>';
      }
      $c++; 
      html += '<td>'+
            '<label>'+
            '&nbsp<input type="checkbox" name="non_staff_check" class="checkItem user_not_assigned non_staffs_'+non_staff_details[i]['user_id']+'" id="'+non_staff_details[i]['user_id']+'" value="'+non_staff_details[i]['user_id']+'_'+'0_'+non_staff_details[i]['fname']+' '+non_staff_details[i]['lname']+'_'+main_unit_id+'_'+unit_id+'_'+non_staff_details[i]['designation_code']+'_'+'offday'+'_'+non_staff_details[i]['gender']+'">'+
            '<span class="check-box-effect"></span>&nbsp;'+non_staff_details[i]['fname']+' '+non_staff_details[i]['lname']+'</label>'+
            '</td>';
    }
  }else{
    html += '<td>No employees</td>';
  }
  html += '</tr></tbody></table></div>';
  html += '</div></div>';
  swal({
      title: "Available employees",
      text: html,
      html: true,
      customClass: 'swal-wide',
      showCancelButton: true,
      closeOnCancel : true,
      closeOnConfirm: true
    }, function (isConfirm) {
    if (isConfirm) {
      checkbox_count = parseInt(checkbox_count) + 1;
      $("input[name='staff_check']:checked").each(function () {
        var value,
            value_array,
            html = '',
            html1 = '',
            shift_id;
        value = $(this).val().trim(); 
        value_array = value.split("_");
        shift_id = value_array[1];
        var user_id = $(this).attr('id');
        if($('.shiftstaff_'+user_id).length == 1){
          html += '<button type="button" id="'+shift_id+'" class="staff_close close" aria-label="Close">'+
                  '<span aria-hidden="true">&times;</span>'+
                  '</button>'; 
          $(this).removeAttr("name"); 
          $(this).attr( "name","usercheck_"+checkbox_count);
          $(this).attr( "checked","checked");
          var element = $(this).parent().parent().append(html);  
          if($(".no_staff_"+shift_id)){
            $(".no_staff_"+shift_id).hide();
          }
          $(".shift_check_"+shift_id).prop("checked", true);
          $('.shift_'+shift_id).last().append(element);
          checkbox_count++;
        }
      });

      $("input[name='non_staff_check']:checked").each(function () {
        var random_string = Math.random().toString(36).substring(7);
        var user_id = $(this).attr('id');
        var html1 = '';
        html1 += '<button type="button" class="non_staff_close close" aria-label="Close">'+
                  '<span aria-hidden="true">&times;</span>'+
                  '</button>';
        if($('.non_staffs_'+user_id).length == 1){
          $(this).removeAttr("name");
          $(this).attr( "name","usercheck_"+random_string);
          $(this).attr( "checked","checked");
          var element1 = $(this).parent().parent().append(html1);
          if($(".no_staffs_area")){
            $(".no_staffs_area").hide();
          }
          $(".non_assignuser_check").prop("checked", true);
          $('.non_staff').last().append(element1);
        }
      });
    }
  });
}
$(document).on('click', '.approve', function (e) {
  var id = event.target.id;
  var user_id = id.split("_")[0];
  var unit_id = id.split("_")[1];
  var from_unit = id.split("_")[2];
  $.ajax({
    url:baseURL+'admin/rota/manageShift',
    type:'post',
    dataType:'json',
    delay: 250,
    data: {
      user_id : user_id,
      unit_id : unit_id,
      from_unit : from_unit,
      status : 1
    },
    success: function (data) {
      if(data.status == 1){
        swal({
          title : "",
          text: "Shift allocation approved",
          icon: "warning",
          button: "ok",
        });
      }
    }
  });
});
$(document).on('click', '.reject', function (e) {
  var id = event.target.id;
  var user_id = id.split("_")[0];
  var unit_id = id.split("_")[1];
  var from_unit = id.split("_")[2];
  $.ajax({
    url:baseURL+'admin/rota/manageShift',
    type:'post',
    dataType:'json',
    delay: 250,
    data: {
      user_id : user_id,
      unit_id : unit_id,
      from_unit : from_unit,
      status : 2
    },
    success: function (data) {
      if(data.status == 1){
        swal({
          title : "",
          text: "Shift allocation rejected",
          icon: "warning",
          button: "ok",
        });
      }
    }
  });
});
});
});
