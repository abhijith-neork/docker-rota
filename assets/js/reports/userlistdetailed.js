$(function() {      
var table;
                $(document).ready(function () {
                            var unit=$('#unitdata').val(); 
                            var jobrole=$('#jobrole').val();
                            var enrolled_status=$('#enrolled_status').val(); //alert(enrolled_status);
                            var status=$('#status').val();    

                              table = $('#myTable').DataTable({
                                "ordering":false,
                                "stateSave": true, 
                                "autoWidth": true,
                                "dom": 'lBfrtip', 
                                "buttons": [ 'copy', 'excel', 'pdf', 'print'],           
                                "processing": true,
                                "serverSide": true, 
                                "iDisplayLength": 25,
                                "aLengthMenu": [[25, 50, 75, 100, 0], [25, 50, 75, 100, "All"]],  
                                "ajax":{
                                 url: baseURL+'admin/Reports/employeelistdetailedreport',
                                 dataType: 'json',
                                 type: "POST", 
                                  data: function ( d ) { 
            
                                      // Retrieve dynamic parameters
                                      var dt_params = $('#myTable').data('dt_params');
                                      // Add dynamic parameters to the data object sent to the server
                                      if(dt_params){ $.extend(d, dt_params); }
                                   }
                            
                                },
                                columns: [ 
                                             { "data": "user_id" }, 
                                             { "data": "name" },
                                             { "data": "email" },
                                             { "data": "mobile_number" },
                                             { "data": "dob" },
                                             { "data": "gender" },
                                             { "data": "Ethnicity" },
                                             { "data": "Visa_status" },
                                             { "data": "address" },
                                             { "data": "country" }, 
                                             { "data": "city" },
                                             { "data": "postcode" },
                                             { "data": "next_of_kin" },
                                             { "data": "group" },
                                             { "data": "weekly_hours" }, 
                                             { "data": "annual_holiday_allowance" },
                                             { "data": "actual_holiday_allowance" },
                                             { "data": "shift_name" },
                                             { "data": "start_date" },
                                             { "data": "end_date" },
                                             { "data": "payrollid" }, 
                                             { "data": "payment_type" },
                                             { "data": "basic_pay_rate" },
                                             { "data": "unit_name" },
                                             { "data": "designation_name" }, 
                                             { "data": "status" },
                                             { "data": "enrolled_status" },  
                                      ] 
                                                        
                                });
                                                               
                            
                        });

                 $(document).on('click', '.search', function(e) {   
                                var unit=$('#unitdata').val();  
                                var jobrole=$('#jobrole').val();
                                var enrolled_status=$('#enrolled_status').val(); //alert(enrolled_status);
                                var status=$('#status').val();   
                                if(unit==0 ){
                                    swal("Please select unit");
                                }
                                else
                                {
                                    table.clear(); 
                                   // table.data('dt_params', {unit:$('#unitdata').val(),jobrole:$('#jobrole').val()} );
                                        $('#myTable').data('dt_params', { unit: unit, jobrole: jobrole, status:status, enrolled_status: enrolled_status});

                                    table.draw(); 
                                }
                           
                              
                                });

                 // $("#status").on('change', function() {
                 //                       var status = $(this).val();   
                 //                       var unit = $('#unit').val(); 
                 //                       $.ajax({ 
                 //                                    type: "post",dataType: "json",
                 //                                    url: baseURL+"admin/User/finduserbyunit",
                 //                                    data: {  status:status ,unit:unit},
                 //                                    success: function(data) {  
                 //                                        table.clear(); 
                 //                                        table.rows.add(data).draw(); 
                 //                                    } 
                 //                              });  
                 //                              $('#myInput').on( 'keyup', function () {
                 //                              table.search( this.value ).draw();
                 //                          } );
                                           

                 //                     });


            });


 

 