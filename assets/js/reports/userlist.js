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
                                "buttons": ['copy', 'excel', 'pdf', 'print'],   
                                "processing": true,
                                "serverSide": true, 
                                "iDisplayLength": 25,
                                "aLengthMenu": [[25, 50, 75, 100, 0], [25, 50, 75, 100, "All"]],  
                                "ajax":{
                                 url: baseURL+'admin/Reports/employeelistreport',
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
                                            { "data": "address1" },
                                            { "data": "address2" },
                                            { "data": "city" },
                                            { "data": "postcode" },
                                            { "data": "mobile_number" },
                                            { "data": "status" },
                                            { "data": "enrolled_status" },
                                            { "data": "password_enabled" },
                                      ] 
                                                        
                                });
                        
                            
                        });

                 $(document).on('click', '.search', function(e) {   
                                var unit=$('#unitdata').val();  
                                var jobrole=$('#jobrole').val(); 
                                var enrolled_status=$('#enrolled_status').val(); //alert(enrolled_status);
                                var status=$('#status').val(); 
                                var password_enabled=$('#pass_enable').val(); 
                                if(unit==0 && jobrole==0){
                                    swal("Please select unit or jobrole");
                                }
                                else
                                {
                                    table.clear(); 
                                   // table.data('dt_params', {unit:$('#unitdata').val(),jobrole:$('#jobrole').val()} );
                                        $('#myTable').data('dt_params', { unit: unit, jobrole: jobrole, status:status, enrolled_status: enrolled_status,pass_enable:password_enabled});

                                    table.draw(); 
                                }
                           
                              
                                });

            });


 