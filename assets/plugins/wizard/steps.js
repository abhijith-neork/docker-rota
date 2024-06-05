
var form = $(".validation-wizard").show();  

// form.validate({
//     errorPlacement: function errorPlacement(error, element) {
//         element.before(error);
//     }
// });
 $('#session-expire-confirmBtn').on('click', function() {
    var url = $("#session_url").val();
    window.location.href = url;
 })
$(".validation-wizard").steps({
    headerTag: "h6"
    , bodyTag: "section"
    ,enableAllSteps: true
    , transitionEffect: "fade"
    , titleTemplate: '<span class="step" id="step">#index#</span> #title#'
    , labels: {
        finish: "Save",next: "Save & Next"
    }
    , onStepChanging: function (event, currentIndex, newIndex) {  
          $("#msgalert").css("color", "green"); 
          $("#msgerroralert").css("color", "red");
           
           if (currentIndex == 0) {  
            // form.validate().settings.ignore = ":disabled,:hidden";
            $('#msgalert').text('');
            var id=$('#id').val(); 
            var fname=$('#wfirstName2').val(); 
            var lname=$('#wlastName2').val(); 
            var email=$('#wemailAddress2').val();
            var mobile_number=$('#wphoneNumber2').val();
            // var new_phone_number = '' ;
            // var number = mobile_number.substring(3);
            // var num_without_zero = Number(number).toString();
            // var leading_char = num_without_zero.substring(0, 2);
            // if(leading_char == "44"){
            //     var new_num = num_without_zero.substring(2);
            //     new_phone_number = "+44"+new_num;
            // }else{
            //     new_phone_number = "+44"+num_without_zero;
            // }
            var dob=$('#from-datepicker').val();
            var address1=$('#waddress12').val(); 
            var address2=$('#waddress22').val(); 
            var country=$('#wcountry2').val(); 
            var city=$('#wcity2').val(); 
            var postcode=$('#wpostcode2').val(); 
           // var status=$('#wstatus2').val();  //console.log(status);
            var ethnicity=$('#ethnicity').val();
            var visa_status=$('#wvisastatus2').val(); //console.log(visa_status);
            var kin_name=$('#wkin_name2').val();  
            var kin_phone=$('#wkin_phone2').val(); 
            var kin_address=$('#wkin_address2').val();  
            var gender=$('#wgender2').val();  
            //var edate=$('#end-datepicker').val(); 
            if(id!='' && ethnicity!='' && fname!='' && lname!='' && email!='' && mobile_number!='' && address1!='' && country!=''  && postcode!='' && kin_name!='' && kin_phone!='' && kin_address!='' && gender!='' ){
                  $.ajax({ 
                       type: "post", async: false,
                       url: baseURL+"admin/user/insertdata",
                       data: { 
                          user_id:id,fname : fname, lname: lname, email: email,mobile_number: mobile_number,dob: dob,address1: address1,address2: address2,country: country,city: city,postcode: postcode,kin_name:kin_name,kin_phone:kin_phone,kin_address:kin_address,gender:gender,ethnicity:ethnicity,visa_status:visa_status,
                         },
                         success: function(data) {
                        //alert(data);
                            var getarray = jQuery.parseJSON(data);
                            var error = getarray.error;   
                            if(error==" ")
                            {
                                $('#msgalert').text("Personal details updated successfully"); 
                            }
                            else
                            {
                                $('#msgerroralert').text(error);   
                                // return form.valid();
 
                                 form.steps("previous");  
    
                            }
                  
                    }
                    
                  
                   }); 
            }
            
            
         
        
         }
        else if(currentIndex == 1){
            $('#msgalert').text('');
            $('#msgerroralert').text(''); 
             var id=$('#id').val();
             var remaining_holiday_allowance = '';
             var group=$('#wgroup2').val(); 
             var hours=$('#whours2').val(); 
             var allowance=$('#wallowance2').val(); 
             var annual_allowance_type=$('#wannual_allowance_type2').val();
             var actual_allowance=$('#wactualallowance2').val(); 
             var prev_anual_allowance_value=$('#prev_anual_allowance_value').val();
             var prev_remaining_holiday_allowance=$('#prev_remaining_holiday_allowance').val();
             if(prev_anual_allowance_value == ""){
                remaining_holiday_allowance = allowance;
             }else{
                var new_annual_allowance = allowance - prev_anual_allowance_value;
                remaining_holiday_allowance = parseFloat(prev_remaining_holiday_allowance) + parseFloat(new_annual_allowance);
             }
             if(parseInt(remaining_holiday_allowance) < 0){
                remaining_holiday_allowance = 0;
             }
             var actual_allowance_type=$('#wactual_holiday_allowance_type2').val(); 
             var designation=$('#wdesignation2').val(); 
             var sdate=$('#start-datepicker').val(); 
             //var edate=$('#end-datepicker').val(); 
             var notes=$('#wnotes2').val();
             var payroll=$('#wpayroll2').val();
             var shift=$('#wshift2').val();
             var payment_type=$('#wpayment2').val();
             var hr_id=$('#whrID2').val();

             var exit_interview=$('#wexit_interview2').val();
             var exit_reason=$('#wexit_reason2').val();

             var status=$('#wstatus2').val();
             var edate=$('#end-datepicker').val(); 
             var user_id = $('.status_change_select').attr('data-id');

             var day_rate=$('#wday_rate2').val(); 
             var night_rate=$('#wnight_rate2').val();
             var saturday_rate=$('#wday_saturday_rate2').val();
             var sunday_rate=$('#wday_sunday_rate2').val();
             var weekend_night_rate=$('#weekend_night_rate').val();
             var regex = /^\d{2}:\d{2}$/;
             var regex1 = /^\d{2,3}\.\d{2}$/;
             abc=hours.split(":");
             minute=abc[1];
             
             if(regex.test(hours) == true)
             {
                if(minute >= 60)
                {
                    $(document).scrollTop(0);
                    $('#msgerroralert').text('Please correct your weekly hours format'); 
                    form.steps("previous"); 
                }
                else if(sdate=='00/00/0000')
                {
                    $(document).scrollTop(0);
                    $('#msgerroralert').text('Please select the start date.');
                    form.steps("previous");
                }
                else
                {
                    if(regex1.test(allowance) == true)
                    {
                            if(regex1.test(actual_allowance) == true)
                            {
                                // if(status==2 && edate==' ')
                                // {
                                //        $(document).scrollTop(0);
                                //         $('#msgerroralert').text('Please select final working date'); 
                                //         form.steps("previous");
                                // }
                                // else
                                // {

                                    if(id!='' && group!='' && hours!='' && allowance!='' && annual_allowance_type!='' && actual_allowance!='' && actual_allowance_type!='' && designation!='' && sdate!='' && payroll!='' && shift!='' && payment_type!='' && day_rate!='' && exit_interview!=''){

                                         $.ajax({ 
                                               type: "post", 
                                               url: baseURL+"admin/user/insertdatas",
                                               data: { 
                                                  user_id:id,
                                                  group_id:group,
                                                  weekly_hours : hours,
                                                  status:status,
                                                  final_date: edate, 
                                                  annual_holliday_allowance: allowance, 
                                                  annual_allowance_type: annual_allowance_type, 
                                                  actual_holiday_allowance: actual_allowance, 
                                                  remaining_holiday_allowance:remaining_holiday_allowance,
                                                  actual_holiday_allowance_type: actual_allowance_type,
                                                  designation_id: designation,
                                                  start_date: sdate,
                                                  notes: notes,
                                                  payroll_id:payroll,
                                                  default_shift:shift,
                                                  payment_type:payment_type,
                                                  day_rate:day_rate,
                                                  night_rate:night_rate,
                                                  saturday_rate:saturday_rate,
                                                  sunday_rate:sunday_rate,
                                                  weekend_night_rate:weekend_night_rate,
                                                  exit_interview:exit_interview,
                                                  exit_reason:exit_reason,
                                                  hr_id:hr_id
                                                },
                                                success: function(data) {
                                                    if(data){
                                                        $(document).scrollTop(0);
                                                        $('#msgerroralert').text('Please select final working date'); 
                                                        form.steps("previous");
                                                        return;
                                                    }
                                                      if(status == 2){
                                                                        $.ajax({
                                                                            url:baseURL+'admin/user/userStatusChange',
                                                                            type:'post',
                                                                            dataType:'json',
                                                                            data: {
                                                                                user_id:user_id,
                                                                                status : status
                                                                            },
                                                                            success: function (data) {
                                                                            }
                                                                        });
                                                                    }

                                                    $('#prev_anual_allowance_value').val(allowance);
                                                    $('#prev_remaining_holiday_allowance').val(remaining_holiday_allowance);
                                                    $('#msgalert').text('Employee details updated successfully');
                                                }
                                         });  
                                    }
                                //}
                            }
                            else
                            {
                                $(document).scrollTop(0);
                                $('#msgerroralert').text('Please correct your Annual Leave Entitlement format'); 
                                form.steps("previous"); 
                            }
                    }
                    else
                    {
                             $(document).scrollTop(0);
                             $('#msgerroralert').text('Please correct your Annual Leave Allowance format'); 
                             form.steps("previous"); 
                    }

                }
                
            }
            else
            {
                $(document).scrollTop(0);
                $('#msgerroralert').text('Please correct your weekly hours format'); 
                form.steps("previous"); 
            }
             
        }
        else if(currentIndex == 2){
            if(newIndex==3){
                var user_unit=$('#unit_id').val(); 
                var unit=$('#wunit2 option:selected').val();
                if(unit && user_unit!=unit)
                {
                    swal({
                        title: "Are you sure you want to move this employee to other unit. This will remove all Rota from today onwards!",
                        showCancelButton: true,
                        closeOnCancel : true,
                        closeOnConfirm: true
                    },function (isConfirm) {
                        if(isConfirm){
                            $(".sweet-overlay, .sweet-alert").remove();
                            $("#img-loader-data").show();
                            $('#msgerroralert').text(''); 
                            $('#msgalert').text('');
                            var id=$('#id').val(); 
                            if(id!='' && unit!=''){
                                $.ajax({ 
                                    type: "post", 
                                    // async : false,
                                    url: baseURL+"admin/user/insertunit",
                                    data: { 
                                        user_id:id,unit_id:unit
                                    },
                                    success: function(data) {
                                        $("#img-loader-data").hide();
                                        var getarray = jQuery.parseJSON(data);
                                        if (getarray.status && getarray.status === 'error' && getarray.message === 'Session expired') {
                                            $("#session_url").val(getarray.url);
                                            $("#session_modal").modal('show');
                                        }else{
                                            var error = getarray.error;  
                                            if(error=='')
                                            {   
                                                $('#msgalert').text('Unit details updated successfully'); 
                                            }
                                            else
                                            {  
                                                $('#msgerroralert').text(error); 
                                                form.steps("previous"); 
                                            }
                                        }
                                    }
                                });   
                            } 
                        }
                        else
                        {     
                            form.steps("previous"); 
                            $("#steps-uid-0-t-3").parent().removeClass("done");
                        }
                    });
                }
                var to_unit = $('#to_unit option:selected').val();
                var unit_change_date = $('#unit_change_date').val();
                var id = $('#id').val(); 
                if(to_unit && unit_change_date){
                    var date_array = unit_change_date.split('/');
                    var actual_date = date_array[2]+'-'+date_array[1]+'-'+date_array[0];
                    $.ajax({ 
                        type: "post", 
                        url: baseURL+"admin/user/insertNewUnitDatas",
                        data: { 
                            user_id:id,
                            to_unit:to_unit,
                            unit_change_date : actual_date
                        },
                        success: function(data) {
                            var getarray = jQuery.parseJSON(data);
                            if (getarray.status && getarray.status === 'error' && getarray.message === 'Session expired') {
                                $("#session_url").val(getarray.url);
                                $("#session_modal").modal('show');
                            }else{
                                $('#msgalert').text('Unit scheduler details updated successfully');
                            }
                        }
                    });
                }
            }
        }
        return currentIndex > newIndex || !(3 === newIndex && Number($("#age-2").val()) < 18) && (currentIndex < newIndex && (form.find(".body:eq(" + newIndex + ") label.error").remove(), form.find(".body:eq(" + newIndex + ") .error").removeClass("error")), form.validate().settings.ignore = ":disabled,:hidden", form.valid());
    }   
    , onFinishing: function (event, currentIndex) { 
        if (currentIndex == 3)
        {
            $('#msgalert').text('');
            $('#msgerroralert').text(''); 
            var id=$('#id').val(); 
            var sunday=$('#wsunday2').val();  
            var monday=$('#wmonday2').val(); 
            var tuesday=$('#wtuesday2').val();
            var wednesday=$('#wwednesday2').val();
            var thursday=$('#wthursday2').val();
            var friday=$('#wfriday2').val();
            var saturday=$('#wsaturday2').val();
            if (sunday==0&&monday==0&&tuesday==0&&wednesday==0&&thursday==0&&friday==0&&saturday==0){
               $('#msgalert').text('Please select atleast one day off'); 

             }
            else
            { 
                var status=0;
                if(sunday==1){status=1;}
                if(monday==1){status=status+1;}
                if(tuesday==1){status=status+1;}
                if(wednesday==1){status=status+1;}
                if(thursday==1){status=status+1;}
                if(friday==1){status=status+1;}
                if(saturday==1){status=status+1;}
                /*if(status>1){
                     $('#msgalert').text('Select only one day off'); 
                }
                else
                { */
                    if(id!=''){
                        $.ajax({ 
                            type: "post", 
                            url: baseURL+"admin/user/insertworkschedule",
                            data: { 
                               user_id:id,sunday:sunday,monday:monday,tuesday:tuesday,wednesday:wednesday,thursday:thursday,friday:friday,saturday:saturday
                             },
                             success: function(data) {
                                 $('#msgalert').text('Work schedule updated successfully');
                             }
                        }); 
                    }
        // }
}
        }
        return form.validate().settings.ignore = ":disabled", form.valid()
    }
    , onFinished: function (event, currentIndex) {
         // swal("Updation completed!");  

    }
}), $(".validation-wizard").validate({
      errorClass: "text-danger"
    , successClass: "text-success"
    , highlight: function (element, errorClass) {
        $(element).removeClass(errorClass)
    }
    , unhighlight: function (element, errorClass) {
        $(element).removeClass(errorClass)
    }
    , errorPlacement: function (error, element) {
        error.insertAfter(element)
    },
    anchorClickable         :   true, // Enable/Disable anchor navigation
        enableAllAnchors        :   true, // Activates all anchors clickable all times
        markDoneStep            :   true, // add done css
        enableAnchorOnDoneStep  :   true 

    , rules: {
        email: {
            email: !0
        }
    }
})
