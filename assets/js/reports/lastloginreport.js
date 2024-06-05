$(function() {      
var table;
                $(document).ready(function () {
                            $('.status').select2();
                            $('.password_status').select2();
                            var unit=$('#unitdata').val(); 
                            var jobrole=$('#jobrole').val(); 
                            var user_status = $('#user_status').val();  
                            // var password_status = $('#password_status').val();   

                              table = $('#myTable').DataTable({
                                "ordering":false,
                                 "order": [
                                    [0, 'asc']
                                    ],
                                "stateSave": true, 
                                "autoWidth": true,
                                "dom": 'lBfrtip', 
                                "buttons": ['copy', 'excel', 'pdf', 'print'],   
                                "processing": true,
                                "serverSide": true, 
                                "iDisplayLength": 25,
                                "aLengthMenu": [[ 25, 50, 75, 100, 0], [ 25, 50, 75, 100, "All"]], 

                                "ajax":{
                                 url: baseURL+'admin/Reports/lastloginreport',
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
                                            { "data": "unit_name" },
                                            { "data": "designation_name" },
                                            { "data": "lastlogin_date" },
                                            { "data": "status" },  
                                            { "data": "account_accessed" },
                                            { "data": "user_status" },   
                                      ] 
                                                        
                                });

                               $("#status").on('change', function() {
                                       var status = $('#status').val(); console.log(status);
                                       var unit=$('#unitdata').val();  console.log(unit);
                            		       var jobrole=$('#jobrole').val(); console.log(jobrole);
                                       var user_status=$('#user_status').val(); console.log(user_status);
                                       var password_status = $('#password_status').val(); console.log(password_status);
                                       if(unit==0)
                                      {
                                          swal("Please select unit");
                                      }
                                      else
                                      {
                                       table.clear(); 
	                                   // table.data('dt_params', {unit:$('#unitdata').val(),jobrole:$('#jobrole').val()} );
	                                    $('#myTable').data('dt_params', { status: status,unit:unit,jobrole:jobrole,password_status:password_status,user_status:user_status});

	                                    table.draw();
                                      }        

                                     });

                                $("#password_status").on('change', function() {
                                       var status = $('#status').val(); 
                                       var unit=$('#unitdata').val();  
                                       var jobrole=$('#jobrole').val();
                                       var user_status=$('#user_status').val();
                                       var password_status = $('#password_status').val(); 
                                        if(unit==0 )
                                      {
                                          swal("Please select unit");
                                      }
                                      else
                                      {   
                                         table.clear(); 
                                       // table.data('dt_params', {unit:$('#unitdata').val(),jobrole:$('#jobrole').val()} );
                                        $('#myTable').data('dt_params', { status: status,unit:unit,jobrole:jobrole,password_status:password_status,user_status:user_status});

                                        table.draw();   
                                      }     

                                     });


                             
                            
                        });

                 $(document).on('click', '.search', function(e) {   
                                var unit=$('#unitdata').val();  
                                var jobrole=$('#jobrole').val(); 
                                var status = $('#status').val(); 
                                var user_status=$('#user_status').val();
                                var password_status = $('#password_status').val();   
                                if(unit==0)
                                {
                                    swal("Please select unit");
                                }
                                else
                                {
                                    table.clear(); 
                                   // table.data('dt_params', {unit:$('#unitdata').val(),jobrole:$('#jobrole').val()} );
                                    $('#myTable').data('dt_params', { unit: unit, jobrole: jobrole,status:status,password_status:password_status,user_status:user_status});

                                    table.draw(); 
                                }
                           
                              
                                });

                  
                        

            });


 