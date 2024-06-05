$(document).ready(function() {  
  


   $("#from-datepicker").datepicker({ 
                                        dateFormat: 'dd/mm/yy'
                                    });
                                    $("#from-datepicker").on("change", function () {
                                        var fromdate = $(this).val();
                                        //alert(fromdate);
                                    }); 

  var otherunitschedule;
 
  function getRotadetails(date,resources)
  { //console.log(resources);
    var sdata;
    var abc=$('#date_status').val(); //alert(abc);
    if(abc==1)
    {  
          date1=$('#start_new').val();  
    }
    else
    {
          date1=date;
    } //alert(date1);
 
    var unit=$('#unit').val(); //console.log(unit);
    $.ajax({
                        url:baseURL+'admin/rota/getRotadetailsV2',
                        type:'post',
                        dataType:'json',
                        async:false,
                      
                        data: {
                           date:date1,
                           unit_id:unit,
                           //Added by chinchu for fixing issue --
                           //Staff on their own rota show as Avenue but when adding to a different rota show as Day off.
                           //Staff Naem – Sandhya Tamang for the 18th.Not getting full elements on controller when passing
                           //it as an array. So applying JSON.stringify.
                           rotabc:JSON.stringify(resources)

                        },
                        success: function (data) {  //console.log(data);
                          sdata=data;
                          /*if(data==null)
                          showHolidayAndTrainings();*/
                          
                         
                        }
                      });
    return sdata;
  }

    $('#calendar').fullCalendar({
      resourceGroupField: 'designation_id',
      resourceOrder : 'title',
      height: 1200,
    resourceAreaWidth: 350,  
    editable: false,
    eventLimit: true,
    filterResourcesWithEvents:true,
    refetchResourcesOnNavigate:true,
    aspectRatio: 3,
    scrollTime: '00:00',
    header: {
      left: 'promptResource today prev,next',
      center: 'title',
      right: ' ',   
    },
    titleFormat: '[Schedule for week] - ddd D/MM/YYYY [: '+unit+']', 
    eventRender: function( event, element, view ) { 
      $(element).css("background-color",event.color);
            // $(element).tooltip({title: event.title});
            //  var eventStart = moment(event.start); 
            //   var titleSelect =event.title; 
            //   var resourceID = event.resourceId; 
            //   var user_id = event.resourceId; 
            //   var from_unit =event.from_unit;  
            // var replaceString = 'shift_'+resourceID; 
            // var weekDay = eventStart.weekday(); 
            // var replaceWith = 'shift_'+resourceID+'_'+weekDay;
            // var et = event.title.replace(replaceString, replaceWith);
            // var cdate = eventStart.format('YYYY-MM-DD');
            // element.find('#'+replaceString).remove();
            // element.append(et);
            
           
            //   if(eventStart.weekday()==event.offDay){
           
            //     var offSelect=element.find('#'+replaceWith);
            //     var avoid = 'selected="selected"';
            //     offSelect = et.replace(avoid,'');
            //     var remv = 'offselect="1"';
            //     offSelect =  et.replace(remv, 'selected="selected"');
            //     element.find('#'+replaceWith).remove();
            //     element.append(offSelect);
 
            //     } 
              
            //   if(parseInt(from_unit)>0){
            //     //check if user is already assigned to this date in any other units
                
            //       $.ajax({
            //             url:baseURL+'admin/rota/checkassigned',
            //             type:'post',
            //             dataType:'json',async:false,
                      
            //             data: {
            //               user_id : resourceID,_date:cdate
            //             },
            //             success: function (data) {               
            //              if(parseInt(data.status)>=1 && parseInt(event.unitId)!=parseInt(data.unit_id)){
            //              element.find('#'+replaceWith).remove();
            //              element.append('<h4 style="margin-top: 10px;">'+data.unit_name+'</h4>');
            //             }
            //             }
            //           });
                
            //   }
              
            var title = element.find( '.fc-title' );
      title.html( title.text() ); 
              
     // console.log("event Render");  
            
            },

    //  eventAfterRender: function( event, element, view ) {   
    //       //     console.log("eventAfterRender");  
    //           //text_color:event.text_color;
    //           //$('.fc-title').attr('style','height:40px');
    // }, 
    // viewRender: function( event, element, view ) { 
    //   console.log("viewRender");  
    // }, 
    eventAfterAllRender: function(view ) { 
       otherunitschedule='';
       otherunitschedule=getRotadetails(sdate,resources); console.log(otherunitschedule);
       if(otherunitschedule!=null)
            { 
                var other_i;
                var text;

                for (other_i = 0; other_i< otherunitschedule.length; other_i++) { 

                     var replaceTo='shift_'+otherunitschedule[other_i]['id']+'_'+otherunitschedule[other_i]['date']; 

                      view.el.find('.'+replaceTo).after('<span class="shift_'+otherunitschedule[other_i]['date']+'_'+otherunitschedule[other_i]['id']+'" style="margin-top: 10px;">'+otherunitschedule[other_i]['title']+'</span>');
                       view.el.find('.'+replaceTo).remove(); 

                    } 
                  
                //    view.el.find('.'+replaceTo).after('<h4 style="margin-top: 10px;">'+otherunitschedule[other_i]['title']+'</h4>');
                //    view.el.find('.'+replaceTo).remove();
            }  
            showHolidayAndTrainings();
        $("#img-loader-data").hide();
        $("#progressbar").css("display", "none");
    },
    events: weekEvents, 
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
    loading: function (bool) { 
        if (bool) {

        }
          // console.log("loading");
          // $('#loadingImg').show(); 
         else {
           // console.log("done");
         }
          // $('#loadingImg').hide(); 
           //Possibly call you feed loader to add the next feed in line
      },
    resourceColumns: [
        {
          labelText: 'Employees',
          field: 'title'
        }
      ],
    resources:function (callback) {
      
    // var calendar = $('#calendar').fullCalendar('getCalendar');
    // var view = calendar.view;
    // var start = view.start._d; 
    // let date = JSON.stringify(start);
    // sdate = date.slice(1,11);
    // var end = view.end._d;  
    // //Added by chinchus. march7
    // end.setDate(end.getDate()-1); 
    // let date1 = JSON.stringify(end);
    // edate = date1.slice(1,11);
    
    //  $("#start").val(sdate);
    //  $("#end").val(edate);
    var abc=$('#date_status').val();
    if(abc==1)
    {  
          sdate=$('#start_new').val();  
          edate=$('#end_new').val();  
    }
    else
    {  


          var start_date=$('#from-datepicker').val(); 
          var start=new Date(corectDateFormat(start_date));
           
          var lastday = start.getDate() - (start.getDay() - 1) + 6;
          enddate=new Date(start.setDate(lastday)); 
          var diff = start.getDate() - start.getDay() + (start.getDay() === 0 ? -7 : 0);
          startdate=new Date(start.setDate(diff)); 
          edate = enddate.getFullYear() + "-" + (enddate.getMonth()+1) + "-" + enddate.getDate();
          var d1 = new Date(edate);
          d1.setDate(d1.getDate() - 1);
          end_date=formatDate(d1); 
          sdate = startdate.getFullYear() + "-" + (startdate.getMonth()+1) + "-" + startdate.getDate();
          var d2 = new Date(sdate);
          d2.setDate(d2.getDate());
          start_date=formatDate(d2); 
          $("#start").val(start_date);
          $("#end").val(end_date);


    }

    var date_array = findDatesBetnDates(sdate,edate);
    if(date_array.length > 7){
      var new_edate = new Date(edate);
      new_edate.setDate(new_edate.getDate() - 1);
      edate = new Date(new_edate).toISOString().slice(0,10);
    }

 // console.log("RES");
    var unit=$('#unit').val(); //console.log(unit);
        $.ajax({
          url: baseURL+"admin/rota/getweekResources",
            type: "POST",
            async: false,
            data: {
                unit_id: unit,start_date:sdate,end_date:edate
            }
          }).done(function(response) {   //console.log(response);
            
            if(response.resources=='')
            {
              
              if($('#user_type').val()=='3' || $('#user_type').val()=='9')
              {
                 document.getElementById('update_rota').style.visibility = 'hidden';
              }
              else
              {
                document.getElementById('edit').style.visibility = 'hidden';
                document.getElementById('update_users').style.visibility = 'hidden';
              }
              
            }
            else
            {
              
              if($('#user_type').val()=='3' || $('#user_type').val()=='9')
              {
                 document.getElementById('update_rota').style.visibility = 'visible';
              }
              else
              {
                document.getElementById('edit').style.visibility = 'visible';
                document.getElementById('update_users').style.visibility = 'visible';
              }

            }
               callback(response.resources); //return resource data to the calendar via the provided callback function
          });
        },
    resourceRender: function (resourceObj, labelTds) {
      
    }
   });
    
/*    go to a date start*/
    var startDate=$('#from-datepicker').val(); 
    if(startDate==""){
      
      var d = new Date();

      var month = d.getMonth()+1;
      var day = d.getDate();

      var startDate =   (day<10 ? '0' : '') + day + '-' +(month<10 ? '0' : '') + month + '-' +d.getFullYear();
    }
 
    sMdate = moment(startDate, "DD-MM-YYYY");
    $("#calendar").fullCalendar( 'gotoDate', sMdate);
 /*    go to a date end*/   
    
    
    
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
 //select dropdown of unit
    // $(document).on('change', '.SelectUnit', function(e) {  
     
    //     var item=$(this);
    //     var calendar = $('#calendar').fullCalendar('getCalendar');
    //     var view = calendar.view;
    //     console.log(view);
    //     console.log(view.end._d);
    //       var start = view.start._d; 
    //       let date = JSON.stringify(start);
    //       sdate = date.slice(1,11); 
    //       //alert(sdate);
    //       var end = view.end._d; 
    //       //Added by chinchus march7.
    //       end.setDate(end.getDate()-1); 
    //       let date1 = JSON.stringify(end);
    //       edate = date1.slice(1,11);  
    //       $("#start").val(sdate);
    //       $("#from-datepicker").val(sdate);
    //       $("#end").val(edate);
    //       $("#unit_id").val(item.val());
    //       var unit=item.val();
    //     $.ajax({
    //       url: baseURL+"admin/rota/getRotaId",
    //         type: "POST",
    //         async: false,
    //         data: {
    //             unit_id: unit,start_date:sdate,end_date:edate
    //         }
    //       }).done(function(response) {
    //            $("#rota_id").val(response); //return resource data to the calendar via the provided callback function
    //       });
    //  $( "#frmViewRota" ).submit();
    // });

  $(document).on('click', '.search', function(e) { 
     
        var item=$(this);  
        var calendar = $('#calendar').fullCalendar('getCalendar');
        var start_date=$('#from-datepicker').val(); 
          var start=new Date(corectDateFormat(start_date));
           
          var lastday = start.getDate() - (start.getDay() - 1) + 6;
          enddate=new Date(start.setDate(lastday)); 
          var diff = start.getDate() - start.getDay() + (start.getDay() === 0 ? -7 : 0);
          startdate=new Date(start.setDate(diff)); 
          edate = enddate.getFullYear() + "-" + (enddate.getMonth()+1) + "-" + enddate.getDate();
          var d = new Date(edate);
          d.setDate(d.getDate() - 1);
          end_date=formatDate(d); 
          sdate = startdate.getFullYear() + "-" + (startdate.getMonth()+1) + "-" + startdate.getDate(); 
          var unit=$("#unit").val();  
          $("#start").val(sdate);
          $("#end").val(end_date);
          $("#unit_id").val(unit);
          
        $.ajax({
          url: baseURL+"admin/rota/getRotaId",
            type: "POST",
            async: false,
            data: {
                unit_id: unit,start_date:sdate,end_date:edate
            }
          }).done(function(response) {
               $("#rota_id").val(response); //return resource data to the calendar via the provided callback function
          });
     $( "#frmViewRota" ).submit();
    });

  function corectDateFormat(date){
      var date_array = date.split("/");
      var date_with_slash = date_array[2]+"-"+date_array[1]+"-"+date_array[0];
      return date_with_slash;
    }
    
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

    
// previous button click
    $(document).on('click', '.fc-prev-button', function(e) {
      showLoader(); 
      
      $("#date_status").val(1);

      var startdate=$("#start").val();  
      var d1=new Date(startdate); 
      var a=d1.setDate(d1.getDate() - 7);  
      startdate1=formatDate(a);// alert(startdate1);

      var enddate=$("#end").val();  
      var d2=new Date(enddate); 
      var b=d2.setDate(d2.getDate() - 8); 
      enddate1=formatDate(b); //alert(enddate1);
      
      $("#start_new").val(startdate1);
      $("#end_new").val(enddate1);


      $("#start").val("");
      $("#end").val("");
     // $("#img-loader-data").show();
        var calendar = $('#calendar').fullCalendar('getCalendar');
        $('#calendar').fullCalendar('refetchResources')
       
        var view = calendar.view;
        var start = view.start._d; 
        let date = JSON.stringify(start);
        sdate = date.slice(1,11); 
        var end = view.end._d;
        //Added by chinchu.
        //end.setDate(end.getDate()-1); 
        end.setDate(end.getDate()); // march7 chinjus
        let date1 = JSON.stringify(end);
        edate = date1.slice(1,11); 
        var d = new Date(edate);
        d.setDate(d.getDate() - 1);
        end_date=formatDate(d);  // erota issue,unit supervisor rota saving issue,end date-1 , edited by swaraj on june 3
        $("#start").val(sdate);
        $("#end").val(end_date);
        var unit=$('#unit').val();
        $.ajax({
            url: baseURL+"admin/rota/getweekSchedule",
            type: "POST",
            async: false,
            data: {
                unit_id: unit,start_date:sdate,end_date:end_date
            },
             success: function (data) {  console.log(data);
          $('#calendar').fullCalendar('removeEvents'); //Hide all events
          $('#calendar').fullCalendar('removeEventSource', weekEvents);
          $('#calendar').fullCalendar('removeEventSource', data.events);
          $('#calendar').fullCalendar('addEventSource', data.events);
          
         // $('#calendar').fullCalendar('refetchResources'); 
         //console.log(data.supervisor);
            //if(data.supervisor == "true") //changed by swaraj june5 from here
            //{
              if(data.status==0)
              {
                if(data.permission=='true')
                {

                   
                    if($('#user_type').val()=='3' || $('#user_type').val()=='9')
                    {
                       document.getElementById('update_rota').disabled = false;
                    }
                    else
                    {
                      document.getElementById('edit').disabled = false;
                      document.getElementById('update_users').disabled = false;
                    }
                   

                }
                else
                {
                    
                    if($('#user_type').val()=='3' || $('#user_type').val()=='9')
                    {
                       document.getElementById('update_rota').disabled = true;
                    }
                    else
                    {
                      document.getElementById('edit').disabled = true;
                      document.getElementById('update_users').disabled = true;
                    }
                    
                }
              }
              else
              {
                
                if($('#user_type').val()=='3' || $('#user_type').val()=='9')
                {
                    document.getElementById('update_rota').disabled = false;
                }
                else
                {
                    document.getElementById('edit').disabled = false;
                    document.getElementById('update_users').disabled = false;
                }
                
              }
           // } //to here
          if(data.events=='')
            {
              
              if($('#user_type').val()=='3' || $('#user_type').val()=='9')
              {
                    document.getElementById('update_rota').style.visibility = 'hidden';
              }
              else
              {
                    document.getElementById('edit').style.visibility = 'hidden';
                    document.getElementById('update_users').style.visibility = 'hidden';
              }

            }
            else
            {
              
              if($('#user_type').val()=='3' || $('#user_type').val()=='9')
              {
                    document.getElementById('update_rota').style.visibility = 'visible';
              }
              else
              {
                    document.getElementById('edit').style.visibility = 'visible';
                    document.getElementById('update_users').style.visibility = 'visible';
              }

            }
          //resources =  data.resources;
          showDesigationName();
          if(data.rotaId)
            $("#rota_id").val(data.rotaId);
          else
            $("#rota_id").val("");
            hideLoader() ;
           }
        
      });
 
    });
    
// next button click
    $(document).on('click', '.fc-next-button ', function(e) { 
      showLoader(); 

      $("#date_status").val(1);

      var startdate=$("#start").val();   
      var d1=new Date(startdate); 
      var a=d1.setDate(d1.getDate() + 7);  
      startdate1=formatDate(a);  

      var enddate=$("#end").val();  
      var d2=new Date(enddate); 
      var b=d2.setDate(d2.getDate() + 6); 
      enddate1=formatDate(b);  
      
      $("#start_new").val(startdate1);
      $("#end_new").val(enddate1);

      $("#start").val("");
      $("#end").val("");
      // console.log("next loading");
      
        var calendar = $('#calendar').fullCalendar('getCalendar');
        $('#calendar').fullCalendar('refetchResources')
        var view = calendar.view;
        var start = view.start._d; 
        let date = JSON.stringify(start);
        sdate = date.slice(1,11);  
        var end = view.end._d;
        //Added by chinchu.
        //end.setDate(end.getDate()-1); 
        end.setDate(end.getDate()); // march7 chinjus
        let date1 = JSON.stringify(end);
        edate = date1.slice(1,11);
        var d = new Date(edate);
        d.setDate(d.getDate() - 1);
        end_date=formatDate(d);   // erota issue,unit supervisor rota saving issue,end date-1 , edited by swaraj on june 3

        $("#start").val(sdate);
        $("#end").val(end_date);
        var unit=$('#unit').val();
       // setTimeout(function () {
            //AJAX REQUEST CODE
          
          // console.log(sdate);
          // console.log(edate);
        
        $.ajax({
            url: baseURL+"admin/rota/getweekSchedule",
            type: "POST",
            async: false,
            data: {
                unit_id: unit,start_date:sdate,end_date:end_date
            },
            beforeSend: function () { },
            
            success: function (data) {  

            $('#calendar').fullCalendar('removeEvents') //Hide all events
            $('#calendar').fullCalendar('removeEventSource', weekEvents);
            $('#calendar').fullCalendar('removeEventSource', data.events);
            $('#calendar').fullCalendar('addEventSource', data.events);
           // $('#calendar').fullCalendar('refetchResources');
          
            //resources =  data.resources;
            
            showDesigationName(); 
            //if(data.supervisor == "true") //changed by swaraj june5 from here 
           // {
              if(data.status==0)
              {
                if(data.permission=='true')
                {

                   
                   if($('#user_type').val()=='3' || $('#user_type').val()=='9')
                    {
                       document.getElementById('update_rota').disabled = false;
                    }
                    else
                    {
                      document.getElementById('edit').disabled = false;
                      document.getElementById('update_users').disabled = false;
                    }
                   

                }
                else
                {
                    
                    if($('#user_type').val()=='3' || $('#user_type').val()=='9')
                    {
                       document.getElementById('update_rota').disabled = true;
                    }
                    else
                    {
                      document.getElementById('edit').disabled = true;
                      document.getElementById('update_users').disabled = true;
                    }

                }
              }
              else
              {
                
                if($('#user_type').val()=='3' || $('#user_type').val()=='9')
                {
                       document.getElementById('update_rota').disabled = false;
                }
                else
                {
                      document.getElementById('edit').disabled = false;
                      document.getElementById('update_users').disabled = false;
                }
              }
            //} //to here

            if(data.events=='')
            {
              
              if($('#user_type').val()=='3' || $('#user_type').val()=='9')
              {
                      document.getElementById('update_rota').style.visibility = 'hidden';
              }
              else
              {
                      document.getElementById('edit').style.visibility = 'hidden';
                      document.getElementById('update_users').style.visibility = 'hidden';
              }
              
            }
            else
            {
              
              if($('#user_type').val()=='3' || $('#user_type').val()=='9')
              {
                      document.getElementById('update_rota').style.visibility = 'visible';
              }
              else
              {
                      document.getElementById('edit').style.visibility = 'visible';
                      document.getElementById('update_users').style.visibility = 'visible';
              }
               
            }
            if(data.rotaId)
              $("#rota_id").val(data.rotaId);
            else
              $("#rota_id").val("");
            // console.log("next done");
            //$("#img-loader-data").hide();
            hideLoader() ;
          }
              
          });
     
      //},10);
     });
    function showLoader() {
        $("#progressbar").css("display", "");
        // console.log("start");
    }

    function hideLoader() {
        setTimeout(function () {
            $("#progressbar").css("display", "none");
            // console.log("end");
        }, 1000);
    }
    //edit schedule 
    $(document).on('click', '.btn-edit', function (e) { // edit rota
        event.preventDefault();
        showLoader();
        var rotaId = $("#rota_id").val();
        var unitId = $("#unit_id").val(); //console.log(unitId);
        var start = $("#start").val();
        var end = $("#end").val();
        var session_id = $("#session_id").val();
        //Added by chinchu
        var date_object = new Date(start);
        date_object.setDate(date_object.getDate() + 6);
        var endDate = new Date(date_object).toISOString().split('T')[0];
        if(end=='')
        {
          endDate=endDate;
        }
        else
        {
          endDate=end;
        }

         if(rotaId!=''){
          window.location=baseURL+'admin/rota/editrotaviewbysettings/'+unitId+"/"+start+"/"+endDate+"/"+rotaId+"/"+session_id;
         }
    });

      $(document).on('click', '.btn-update', function (e) { //update rota
        event.preventDefault();
        showLoader();
        var rotaId = $("#rota_id").val();
        var unitId = $("#unit_id").val(); //console.log(unitId);
        var start = $("#start").val();
        var end = $("#end").val();
        var session_id = $("#session_id").val();
        //Added by chinchu
        var date_object = new Date(start);
        date_object.setDate(date_object.getDate() + 6);
        var endDate = new Date(date_object).toISOString().split('T')[0];
        if(end=='')
        {
          endDate=endDate;
        }
        else
        {
          endDate=end;
        }

         if(rotaId!=''){
          window.location=baseURL+'admin/rota/updaterotabyuser/'+unitId+"/"+start+"/"+endDate+"/"+rotaId+"/"+session_id;
         }
    });


    $(document).on('click', '#update_users', function (e) { // update users
        event.preventDefault();
        var rotaId = $("#rota_id").val();
        var unitId = $("#unit_id").val();
        var session_id = $("#session_id").val();
        // console.log(rotaId)
        // console.log(unitId)
        if(rotaId!=''){
            window.location=baseURL+'admin/rota/updateUsers/'+rotaId+"/"+unitId+"/"+session_id;
        }
    });
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
        // console.log("dd");
        var designation_id = $(this).find('span.fc-cell-text').text();
        // console.log(designation_id+"ooo");
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
          $('.select_offday_'+value.user_id+'_'+offday[i]).html('X');
        }
      });
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
                  //If the user has offday then only we need to show as holiday
                  //othervise shows rota value itself
                  if(shift_id == 0){
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
                    $('.shift_'+value.user_id+'_'+dates[i]).removeClass('select_user');
                    $('.shift_'+value.user_id+'_'+dates[i]).removeClass('select_shift');
                    $('.shift_'+value.user_id+'_'+dates[i]).html('T');
                    $('.shift_'+dates[i]+'_'+value.user_id).html('T');
                  }
                }            
              });
            }
          }
    showDesigationName();
});