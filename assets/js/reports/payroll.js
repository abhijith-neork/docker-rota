/*$(function() {
                                
                                    $(document).ready(function() {
$("#img-loader-data").hide();
                                    var role = jobe_roles;  
                                      $(".select2").select2();
                                      if(role.length > 0){
                                        $('.jobrole').select2().val(role).trigger("change") 
                                      }

                                    $("#start_date").datepicker({ 
                                        dateFormat: 'dd/mm/yy',
                                        beforeShowDay: function(date) {
                                    var day = date.getDay();
                                    return [(day != 1 && day != 2 && day != 3 && day != 4 && day != 5 && day !=6)];
                                    }
                                    });
                                    $("#start_date").on("change", function () {
                                        var fromdate = $(this).val();
                                        //alert(fromdate);
                                    }); 

                                    $("#end_date").datepicker({ 
                                        dateFormat: 'dd/mm/yy',
                                        beforeShowDay: function(date) {
                                      var day = date.getDay();
                                      return [(day != 0 && day != 1 && day != 2 && day != 3 && day != 4 && day != 5)];
                                      }
                                    });
                                    $("#end_date").on("change", function () {
                                        var fromdate = $(this).val();
                                        //alert(fromdate);
                                    }); 


                                    var table = $('#myTable').DataTable({
                                    "order": [[ 0, "asc" ]],
                                    // "order": [
                                    // [1, 'asc']
                                    // ],
                                      dom: 'lBfrtip',
                                      buttons: [
                                       {
                                           extend: 'copy',
                                           footer: true,
                                           exportOptions: {
                                                columns: [0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15]
                                            }
                                           
                                       },
                                       {
                                           extend: 'excel', 
                                           footer: true,
                                           exportOptions: {
                                                columns: [0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15]
                                            }
                                       },
                                       {
                                           extend: 'print',
                                           footer: true,
                                           exportOptions: {
                                                columns: [0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15]
                                            }
                                          
                                       },        
                                    ] ,
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

                                    
 
                                    });




  

  });

$(document).on('click', '.search', function(e) { 
      start_date=$('#start_date').val();
      end_date=$('#end_date').val();
      var date1 = new Date(formatDate(start_date)); 
      var date2 = new Date(formatDate(end_date)); 
      if(new Date(formatDate(start_date)).getTime() > new Date(formatDate(end_date)).getTime())
      {
        swal("Start date should be smaller than end date");
        event.preventDefault();
      }
      else if(new Date(formatDate(start_date)).getTime() == new Date(formatDate(end_date)).getTime())
      { 
      swal("Start date should be smaller than end date"); 
      event.preventDefault();
      }
      else
      {
           e.preventDefault();

       $("#img-loader-data").show();

          $( "#frmpayrollReport" ).submit();
       }
    });

function formatDate(date){
  var date_array = date.split('/');
  var new_date = date_array[2]+"-"+date_array[1]+"-"+date_array[0];
  return new_date;
}


function edit(user_id,month,year)
 {
 

  var comment_edit=document.getElementById("comment_"+user_id).innerText;   //console.log(comment_edit);
  var html = '';
 
   if(comment_edit==0)
   {
   html += "<textarea rows='4' cols='40' maxlength='100' Placeholder='Enter comment' id='text' style='padding-top=2px;margin:6px;'></textarea>";
   }
   else
   {  
    html += '<textarea rows="4" cols="40" maxlength="100" Placeholder="Enter comment" id="text" style="padding-top=2px;margin:6px;">'+comment_edit+'</textarea>';
   }
  

  swal({
              title: "Enter Comment",
              text: html,
              // --------------^-- define html element with id
              html: true,
              showCancelButton: true,
              closeOnConfirm: false, 
              inputPlaceholder: "Comment"
            }, function(isConfirm) { 

            if(isConfirm){

                        var comment=$('#text').val();  //console.log(comment);
                        
                        if(comment){ 
                     
                                  $.ajax({
                                      url: baseURL+"admin/Payroll/insertcomments",
                                      type: "POST",
                                      data: {

                                          comment:comment,
                                          user_id: user_id,
                                          month: month,
                                          year: year
                                       
                                      },
                                    success: function (data) {  
                                      if(data.status=='true')
                                      {
                                        swal("Updated successfully");
                                          //console.log($('#hours').text("hours"));
                                        var global_message1 = "Edit"; 
                                        var global_message2 = comment; 
                                        document.getElementById("comment_"+user_id).innerText = comment;
                                        document.getElementById("edit_"+user_id).innerText = "Edit";

                                      }
                                      else
                                      {
                                         swal("Bad attempt");
                                      }
                                        //window.location.reload();
                                     } 
                                  });
                            }
                            else if(comment=='')
                            {
                                swal.showInputError("Please enter comment");
                            }
                    
                  }
                  else
                  {

                  }
                    
                   
                });
 }*/
 
$(function() {
    $(document).ready(function() {
        var table;
        $("#img-loader-data").hide();
        var role = jobe_roles;  
        $(".select2").select2();
        if(role.length > 0){
            $('.jobrole').select2().val(role).trigger("change") 
        }
        $("#start_date").datepicker({ 
            dateFormat: 'dd/mm/yy',
            beforeShowDay: function(date) {
                var day = date.getDay();
                return [(day != 1 && day != 2 && day != 3 && day != 4 && day != 5 && day !=6)];
            },
            defaultDate: "today"
        });
        $("#start_date").on("change", function () {
            var fromdate = $(this).val();
        });
        $( "#start_date" ).datepicker( "setDate", new Date()); 
        $("#end_date").datepicker({ 
            dateFormat: 'dd/mm/yy',
            beforeShowDay: function(date) {
                var day = date.getDay();
                return [(day != 0 && day != 1 && day != 2 && day != 3 && day != 4 && day != 5)];
            }
        });
        $("#end_date").on("change", function () {
            var fromdate = $(this).val();
        }); 
        $( "#end_date" ).datepicker( "setDate", new Date()); 
        table = $('#myTable').DataTable({
            "ordering": true, 
            "order": [[ 0, "asc" ]],
            "columnDefs": [
                { orderable: false, targets: [2,3,4,5,6,7,8,9,10,11,12,13,14,15,16] }, // Disable sorting for the 3rd column (index 2)
            ],   
            "processing": true,
            "language": {
                processing: '<div class="spinner-border text-primary" role="status"></div>'
            },
            'paging': true,
            'searching': true,
            'serverSide': true,
            'dom': 'lBfrtip',
            'buttons': [
                {
                    extend: 'copy',
                    footer: true,
                    exportOptions: {
                        columns: [0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15]
                    }
                   
                },
                {
                    extend: 'excel', 
                    footer: true,
                    exportOptions: {
                        columns: [0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15]
                    }
                },
                {
                    extend: 'print',
                    footer: true,
                    exportOptions: {
                        columns: [0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15]
                    }
                },        
            ] ,
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
                        });
                    }
                });
            },
            "ajax":{
                url: baseURL+'admin/Splitpayroll/get_payrollreport',
                dataType: 'json',
                type: "POST", 
                data: function ( d ) {
                    // Retrieve dynamic parameters
                    var dt_params = $('#myTable').data('dt_params');
                    // Add dynamic parameters to the data object sent to the server
                    if(dt_params){ $.extend(d, dt_params); }
                    d.start_date = $('#start_date').val();
                    d.end_date = $('#end_date').val();
                    d.unit_id = $("#unitdata").val()
                    d.jobrole = $("#jobrole").val();
                    d.status = $("#status").val();
                }
            },
            columns: 
            [ 
                { "data": "payroll_id" },
                { "data": "name" },
                { "data": "days" },
                { "data": "days_sat" },
                { "data": "days_sun" },
                { "data": "weekday_nights" },
                { "data": "weekend_nights" }, 
                { "data": "paid_sickness" }, 
                { "data": "training" }, 
                { "data": "annual_leave" }, 
                { "data": "bank_holiday" }, 
                { "data": "total" }, 
                { "data": "contracted" }, 
                { "data": "salaried" }, 
                { "data": "overtime" }, 
                { "data": "comments" }, 
                { "data": "action" }, 
            ]
        });
        table.on('draw.dt', function () {
            $("#img-loader-data").hide();
        });
        $(document).on('click', '.search', function(event) {
            var start_date = $('#start_date').val();
            var end_date = $('#end_date').val();
            var unit_id = $("#unitdata").val()
            var jobrole = $("#jobrole").val();
            var status = $("#status").val();

            if(start_date=='')
            {
                swal("Please select from date");
                event.preventDefault();
            }
            
            else if(end_date=='')
            {
                swal("Please select to date");
                event.preventDefault();
            }
            else if(new Date(formatDate(start_date)).getTime() > new Date(formatDate(end_date)).getTime())
            {
                swal("Start date should be smaller than end date");
                event.preventDefault();
            }
            else if(new Date(formatDate(start_date)).getTime() == new Date(formatDate(end_date)).getTime())
            { 
                swal("Start date should be smaller than end date"); 
                event.preventDefault();
            }
            else
            {
                $("#img-loader-data").show();
                event.preventDefault();
                table.clear(); 
                $('#myTable').data('dt_params',{ 
                    unit: unit_id, 
                    start_date: start_date,
                    end_date: end_date,
                    unit_id: unit_id,
                    jobrole:jobrole
                });
                table.draw();
            }
        });                           
    });
});



function formatDate(date){
  var date_array = date.split('/');
  var new_date = date_array[2]+"-"+date_array[1]+"-"+date_array[0];
  return new_date;
}


function edit(user_id,month,year)
 {
 

  var comment_edit=document.getElementById("comment_"+user_id).innerText;   //console.log(comment_edit);
  var html = '';
 
   if(comment_edit==0)
   {
   html += "<textarea rows='4' cols='40' maxlength='100' Placeholder='Enter comment' id='text' style='padding-top=2px;margin:6px;'></textarea>";
   }
   else
   {  
    html += '<textarea rows="4" cols="40" maxlength="100" Placeholder="Enter comment" id="text" style="padding-top=2px;margin:6px;">'+comment_edit+'</textarea>';
   }
  

  swal({
              title: "Enter Comment",
              text: html,
              // --------------^-- define html element with id
              html: true,
              showCancelButton: true,
              closeOnConfirm: false, 
              inputPlaceholder: "Comment"
            }, function(isConfirm) { 

            if(isConfirm){

                        var comment=$('#text').val();  //console.log(comment);
                        
                        if(comment){ 
                     
                                  $.ajax({
                                      url: baseURL+"admin/Payroll/insertcomments",
                                      type: "POST",
                                      data: {

                                          comment:comment,
                                          user_id: user_id,
                                          month: month,
                                          year: year
                                       
                                      },
                                    success: function (data) {  
                                      if(data.status=='true')
                                      {
                                        swal("Updated successfully");
                                          //console.log($('#hours').text("hours"));
                                        var global_message1 = "Edit"; 
                                        var global_message2 = comment; 
                                        document.getElementById("comment_"+user_id).innerText = comment;
                                        document.getElementById("edit_"+user_id).innerText = "Edit";

                                      }
                                      else
                                      {
                                         swal("Bad attempt");
                                      }
                                        //window.location.reload();
                                     } 
                                  });
                            }
                            else if(comment=='')
                            {
                                swal.showInputError("Please enter comment");
                            }
                    
                  }
                  else
                  {

                  }
                    
                   
                });
 }
  