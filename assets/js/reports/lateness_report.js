$(function() {      
var table;
                $(document).ready(function () {

                                    $("#start_date").datepicker({ 
                                        dateFormat: 'dd/mm/yy'
                                    });
                                    $("#start_date").on("change", function () {
                                        var fromdate = $(this).val();
                                        //alert(fromdate);
                                    }); 

                                    $("#end_date").datepicker({ 
                                        dateFormat: 'dd/mm/yy'
                                    });
                                    $("#end_date").on("change", function () {
                                        var fromdate = $(this).val();
                                        //alert(fromdate);
                                    });

                            var unit=$('#unitdata').val(); 
                            var jobrole=$('#jobrole').val(); 
                            var user_status = $('#user_status').val();  
                            // var password_status = $('#password_status').val();   

                              var table = $('#myTable').DataTable({
                                    "order": [
                                    [0, 'asc']
                                    ],
                                    dom: 'lBfrtip',
                                    buttons: [
                                    'copy', 'excel', 'pdf', 'print'
                                    ],
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

                              // Order by the grouping
                                    $('#myTable tbody').on('click', 'tr.user', function() {
                                    var currentOrder = table.order()[0];
                                    if (currentOrder[0] === 2 && currentOrder[1] === 'asc') {
                                    table.order([2, 'desc']).draw();
                                    } else {
                                    table.order([2, 'asc']).draw();
                                    }
                                    });
                             
                            
                        });
});

                      

                $(document).on('click', '.search', function(event) { 
                    start_date=$('#start_date').val();
                    end_date=$('#end_date').val();
                    var unit_id = $('#unitdata').val();  console.log(unit_id);
                    var date1 = new Date(formatDate(start_date)); 
                    var date2 = new Date(formatDate(end_date)); 
                    if(unit_id==0 || unit_id=='none')
                    {
                        swal("Please select a unit");
                        event.preventDefault();
                    }
                    else if(start_date=='')
                    {
                        swal("Please select from date");
                        event.preventDefault();
                    }
                    
                    else if(end_date=='')
                    {
                        swal("Please select to date");
                        event.preventDefault();
                    }
                    else if(date1 > date2)
                    {
                        swal("Start date should be smaller than end date");
                        event.preventDefault();
                    }
                    else
                    {
                        event.preventDefault();

                     $("#img-loader-data").show();

                        $( "#frmpayrollReport" ).submit();
                     }
                  });



                  $('#unitdata').change(function(){  

                     var unitID = $('#unitdata').val();
                     var status = $('#status').val();
                            if(unitID) { $('select[name="user"]').empty();
                                $.ajax({
                                    type: "post",
                                    dataType: "json",
                                    url: baseURL+"admin/Reports/finduserdataforallforreport",
                                    data : { unit_id : unitID, status:status },
                                    success:function(data) {  

                                   
                                        $('select[name="user"]').append('<option value="0">--Select user--</option>');
                                        $.each(data, function(key, value) {  
                                            $('select[name="user"]').append('<option value="'+ value.user_id +'" >'+ value.fname+' '+value.lname +'</option>');
                                        });
                                    }
                                });
                            }else{
                                $('select[name="user"]').empty();
                            }
                        });

                   $('#status').change(function(){  

                    var unitID = $('#unitdata').val();
                    var status = $('#status').val();
                            if(unitID) { $('select[name="user"]').empty();
                                $.ajax({
                                    type: "post",
                                    dataType: "json",
                                    url: baseURL+"admin/Reports/finduserdataforallforreport",
                                    data : { unit_id : unitID, status:status },
                                    success:function(data) {  

                                   
                                        $('select[name="user"]').append('<option value="0">--Select user--</option>');
                                        $.each(data, function(key, value) {  
                                            $('select[name="user"]').append('<option value="'+ value.user_id +'">'+ value.fname+' '+value.lname +'</option>');
                                        });
                                    }
                                });
                            }else{
                                $('select[name="user"]').empty();
                            }
                        });

                   function formatDate(date){
                      var date_array = date.split('/');
                      var new_date = date_array[2]+"-"+date_array[1]+"-"+date_array[0];
                      return new_date;
                    }



 