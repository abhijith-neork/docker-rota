    $(document.body).on('focusout',unpaid_break_hours, function(event) {
        var hour=$('#targeted_hours').val();
        var unpaid_break_hours=$('#unpaid_break_hours').val();  

        if(unpaid_break_hours=='' || unpaid_break_hours==0) 
                {
                    
                    targeted_hours=hour; 
                }
                else
                {  
                    if(!isNaN(Number(unpaid_break_hours))){  
                        var unpaid_break_hours = parseFloat(unpaid_break_hours).toFixed(2);
                        unpaid_break_hours = unpaid_break_hours.replace(".", ":");
                        } 
                                            
                    var sumtime = hour.split(':');  console.log(sumtime[0]);
                    var breakhour=unpaid_break_hours.split(':');   
                    // if(sumtime[0]<breakhour[0])
                    // {
                    //     alert("unpaid break hours should be less than the targeted hours"); 
                    // }
                    // else
                    // {
                        var hours1 = parseInt(sumtime[0], 10), 
                        hours2 = parseInt(breakhour[0], 10),
                        mins1 = parseInt(sumtime[1], 10),
                        mins2 = parseInt(breakhour[1], 10);
                        var hours = hours1 - hours2, mins = 0;
                         if(hours < 0) hours = 24 + hours;
                         if(mins1 >= mins2) 
                         {
                             mins = mins1 - mins2;
                         }
                         else 
                         {
                             mins = (mins1 + 60) - mins2;
                             hours--;
                         }
                         mins = mins / 100; // take percentage in 60
                         hours += mins;
                         hours = hours.toFixed(2); 
                         targeted_hours=hours.replace('.', ':');  
                     // }
                }  
 
         $("#total_targeted_hours").val(targeted_hours);
     });

     $(document).on('change', 'select.start_time_hour', function (e) {  
      
      var start_hour=$('#wstart_time2').val();  
      var start_min=$('#wstart_time12').val();  
      var end_hour=$('#wend_time2').val();  
      var end_min=$('#wend_time12').val();   
      var hours1 = parseInt(start_hour, 10), 
          hours2 = parseInt(end_hour, 10),
          mins1 = parseInt(start_min, 10),
          mins2 = parseInt(end_min, 10);
         var hours = hours2 - hours1, mins = 0;
         if(hours < 0) hours = 24 + hours;
         if(mins2 >= mins1) 
         {
             mins = mins2 - mins1;
         }
         else 
         {
             mins = (mins2 + 60) - mins1;
             hours--;
         }
         mins = mins / 100; // take percentage in 60
         hours += mins;
         hours = hours.toFixed(2); 
         hour=hours.replace('.', ':'); 

          var unpaid_break_hours=$('#unpaid_break_hours').val();
         if(unpaid_break_hours=='') 
        {
            
            targeted_hours=hour;
        }
        else
        {  
            if(!isNaN(Number(unpaid_break_hours))){  
                var unpaid_break_hours = parseFloat(unpaid_break_hours).toFixed(2);
                unpaid_break_hours = unpaid_break_hours.replace(".", ":") 
            } 
            var sumtime = hour.split(':');  
            var breakhour=unpaid_break_hours.split(':'); 
            var hours1 = parseInt(sumtime[0], 10), 
            hours2 = parseInt(breakhour[0], 10),
            mins1 = parseInt(sumtime[1], 10),
            mins2 = parseInt(breakhour[1], 10);
            var hours = hours1 - hours2, mins = 0;
             if(hours < 0) hours = 24 + hours;
             if(mins1 >= mins2) 
             {
                 mins = mins1 - mins2;
             }
             else 
             {
                 mins = (mins1 + 60) - mins2;
                 hours--;
             }
             mins = mins / 100; // take percentage in 60
             hours += mins;
             hours = hours.toFixed(2); 
             targeted_hours=hours.replace('.', ':');  
        } 
       //console.log(targeted_hours); 
     $("#targeted_hours").val(hour);
     $("#total_targeted_hours").val(targeted_hours);
 

     }); 
     
     $(document).on('change', 'select.start_time_min', function (e) {  
      
      var start_hour=$('#wstart_time2').val();  
      var start_min=$('#wstart_time12').val();  
      var end_hour=$('#wend_time2').val();  
      var end_min=$('#wend_time12').val();  

         var hours1 = parseInt(start_hour, 10), 
             hours2 = parseInt(end_hour, 10),
             mins1 = parseInt(start_min, 10),
             mins2 = parseInt(end_min, 10);
         var hours = hours2 - hours1, mins = 0;
         if(hours < 0) hours = 24 + hours;
         if(mins2 >= mins1) {
             mins = mins2 - mins1;
         }
         else {
             mins = (mins2 + 60) - mins1;
             hours--;
         }
         mins = mins / 100; // take percentage in 60
         hours += mins;
         hours = hours.toFixed(2); 
         hour=hours.replace('.', ':');  
         var unpaid_break_hours=$('#unpaid_break_hours').val();
        if(unpaid_break_hours=='') 
        {
            
            targeted_hours=hour;
        }
        else
        {  
            if(!isNaN(Number(unpaid_break_hours))){  
                var unpaid_break_hours = parseFloat(unpaid_break_hours).toFixed(2);
                unpaid_break_hours = unpaid_break_hours.replace(".", ":") 
            } 
            var sumtime = hour.split(':');  
            var breakhour=unpaid_break_hours.split(':'); 
            var hours1 = parseInt(sumtime[0], 10), 
            hours2 = parseInt(breakhour[0], 10),
            mins1 = parseInt(sumtime[1], 10),
            mins2 = parseInt(breakhour[1], 10);
            var hours = hours1 - hours2, mins = 0;
             if(hours < 0) hours = 24 + hours;
             if(mins1 >= mins2) 
             {
                 mins = mins1 - mins2;
             }
             else 
             {
                 mins = (mins1 + 60) - mins2;
                 hours--;
             }
             mins = mins / 100; // take percentage in 60
             hours += mins;
             hours = hours.toFixed(2); 
             targeted_hours=hours.replace('.', ':');  
        } 
       //console.log(targeted_hours); 
     $("#targeted_hours").val(hour);
     $("#total_targeted_hours").val(targeted_hours);
 

     }); 

     $(document).on('change', 'select.end_time_hour', function (e) {  
      
      var start_hour=$('#wstart_time2').val();  
      var start_min=$('#wstart_time12').val();  
      var end_hour=$('#wend_time2').val();  
      var end_min=$('#wend_time12').val();  

         var hours1 = parseInt(start_hour, 10), 
             hours2 = parseInt(end_hour, 10),
             mins1 = parseInt(start_min, 10),
             mins2 = parseInt(end_min, 10);
         var hours = hours2 - hours1, mins = 0;
         if(hours < 0) hours = 24 + hours;
         if(mins2 >= mins1) {
             mins = mins2 - mins1;
         }
         else {
             mins = (mins2 + 60) - mins1;
             hours--;
         }
         mins = mins / 100; // take percentage in 60
         hours += mins;
         hours = hours.toFixed(2); 
         hour=hours.replace('.', ':'); 
         var unpaid_break_hours=$('#unpaid_break_hours').val();
        if(unpaid_break_hours=='') 
        {
            
            targeted_hours=hour;
        }
        else
        {  
            if(!isNaN(Number(unpaid_break_hours))){  
                var unpaid_break_hours = parseFloat(unpaid_break_hours).toFixed(2);
                unpaid_break_hours = unpaid_break_hours.replace(".", ":") 
            } 
            var sumtime = hour.split(':');  
            var breakhour=unpaid_break_hours.split(':'); 
            var hours1 = parseInt(sumtime[0], 10), 
            hours2 = parseInt(breakhour[0], 10),
            mins1 = parseInt(sumtime[1], 10),
            mins2 = parseInt(breakhour[1], 10);
            var hours = hours1 - hours2, mins = 0;
             if(hours < 0) hours = 24 + hours;
             if(mins1 >= mins2) 
             {
                 mins = mins1 - mins2;
             }
             else 
             {
                 mins = (mins1 + 60) - mins2;
                 hours--;
             }
             mins = mins / 100; // take percentage in 60
             hours += mins;
             hours = hours.toFixed(2); 
             targeted_hours=hours.replace('.', ':');  
        } 
       //console.log(targeted_hours); 
     $("#targeted_hours").val(hour);
     $("#total_targeted_hours").val(targeted_hours);
 

     }); 
     $(".complex-colorpicker").asColorPicker({
        mode: 'complex'
    }); 


     $(document).on('change', 'select.end_time_min', function (e) {  
      
      var start_hour=$('#wstart_time2').val();  
      var start_min=$('#wstart_time12').val();  
      var end_hour=$('#wend_time2').val();  
      var end_min=$('#wend_time12').val();  

         var hours1 = parseInt(start_hour, 10), 
             hours2 = parseInt(end_hour, 10),
             mins1 = parseInt(start_min, 10),
             mins2 = parseInt(end_min, 10);
         var hours = hours2 - hours1, mins = 0;
         if(hours < 0) hours = 24 + hours;
         if(mins2 >= mins1) {
             mins = mins2 - mins1;
         }
         else {
             mins = (mins2 + 60) - mins1;
             hours--;
         }
         mins = mins / 100; // take percentage in 60
         hours += mins;
         hours = hours.toFixed(2); 
         hour=hours.replace('.', ':'); 
        
        var unpaid_break_hours=$('#unpaid_break_hours').val();
        if(unpaid_break_hours=='') 
        {
            
            targeted_hours=hour;
        }
        else
        {  
            if(!isNaN(Number(unpaid_break_hours))){  
                var unpaid_break_hours = parseFloat(unpaid_break_hours).toFixed(2);
                unpaid_break_hours = unpaid_break_hours.replace(".", ":") 
            }
            var sumtime = hour.split(':');  
            var breakhour=unpaid_break_hours.split(':'); 
            var hours1 = parseInt(sumtime[0], 10), 
            hours2 = parseInt(breakhour[0], 10),
            mins1 = parseInt(sumtime[1], 10),
            mins2 = parseInt(breakhour[1], 10);
            var hours = hours1 - hours2, mins = 0;
             if(hours < 0) hours = 24 + hours;
             if(mins1 >= mins2) 
             {
                 mins = mins1 - mins2;
             }
             else 
             {
                 mins = (mins1 + 60) - mins2;
                 hours--;
             }
             mins = mins / 100; // take percentage in 60
             hours += mins;
             hours = hours.toFixed(2); 
             targeted_hours=hours.replace('.', ':');  
        } 
       //console.log(targeted_hours); 
     $("#targeted_hours").val(hour);
     $("#total_targeted_hours").val(targeted_hours);

     }); 

     $('#partofnumber').change(function() {
        if(this.checked) {
            $("#p_number").val(0);
        }else{
            $("#p_number").val(1);
        }
        
    });