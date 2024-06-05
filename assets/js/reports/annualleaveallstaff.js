$(function() {      
var table;
                $(document).ready(function () {
                            var role = jobe_roles;
                                      $(".select2").select2();
                                      if(role.length > 0){
                                        $('.jobrole').select2().val(role).trigger("change") 
                                      }

                                      $("#start_time").datepicker({ 
                                        dateFormat: 'dd/mm/yy'
                                    });
                                    $("#start_time").on("change", function () {
                                        var fromdate = $(this).val();
                                        //alert(fromdate);
                                    }); 

                                    $("#end_time").datepicker({ 
                                        dateFormat: 'dd/mm/yy'
                                    });
                                    $("#end_time").on("change", function () {
                                        var fromdate = $(this).val();
                                        //alert(fromdate);
                                    });

                            var unit=$('#unitdata').val(); 
                            var jobrole=$('#jobrole').val(); //alert(unit);
                            var start_time=$('#start_time').val(); //console.log(start_time);
                            var end_time=$('#end_time').val();  

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
                                 url: baseURL+'admin/Reports/annualleaveallstaffreport',
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
                                                { "data": "hr_ID" },
                                                { "data": "name" },
                                                { "data": "unit_name" },
                                                { "data": "from_date" },
                                                { "data": "to_date" },
                                                { "data": "days" },  
                                                { "data": "annual_holiday_allowance" },
                                                { "data": "leave_status" },
                                                { "data": "leave" },
                                                { "data": "status" },
                                      ] 
                                                        
                                });
                        
                            
                        });

                 $(document).on('click', '.search', function(e) {   
                                var unit=$('#unitdata').val();  
                                var jobrole=$('#jobrole').val(); //console.log(jobrole.length);
                                var status=$('#user_status').val();
                                var start_time=$('#start_time').val(); //alert(start_time);
                                var end_time=$('#end_time').val();  
                                if(unit==0 ){
                                    swal("Please select unit");
                                    e.preventDefault();
                                }
                                else if(start_time == ""){
                                    swal("Please select from date");
                                    e.preventDefault();
                                }
                                else if(end_time == ""){
                                    swal("Please select to date");
                                    e.preventDefault();
                                }
                                else
                                {
                                    table.clear(); 
                                   // table.data('dt_params', {unit:$('#unitdata').val(),jobrole:$('#jobrole').val()} );
                                    $('#myTable').data('dt_params', { unit: unit, jobrole: jobrole, status:status, start_time:start_time, end_time:end_time});

                                    table.draw(); 
                                }
                           
                              
                                });

            });
