$(function() {
                                
                                    $(document).ready(function() {

                                         $("#from-datepicker").datepicker({ 
                                        dateFormat: 'dd/mm/yy'
                                    });
                                    $("#from-datepicker").on("change", function () {
                                        var fromdate = $(this).val();
                                        //alert(fromdate);
                                    }); 

                                     $("#to-datepicker").datepicker({ 
                                        dateFormat: 'dd/mm/yy'
                                    });
                                    $("#to-datepicker").on("change", function () {
                                        var fromdate = $(this).val();
                                        //alert(fromdate);
                                    }); 


                                    var table = $('#myTable').DataTable({
                                    
                                    "order": [
                                    [0, 'asc'],[2,'desc']
                                    ],
                                    columnDefs: [
                                        { orderable: false, targets: [4,5] }, // Disable sorting for the 3rd column (index 2)
                                    ],
                                      dom: 'lBfrtip',
                                    buttons: [
                                       {
                                           extend: 'copy',
                                           footer: true,
                                           exportOptions: {
                                                columns: [0,1,2,3,4]
                                            }
                                           
                                       },
                                       {
                                           extend: 'excel', 
                                           footer: true,
                                           exportOptions: {
                                                columns: [0,1,2,3,4]
                                            }
                                       },
                                       {
                                           extend: 'print',
                                           footer: true,
                                           exportOptions: {
                                                columns: [0,1,2,3,4]
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
                                         $(".mymsel").select2();
                                        },  drawCallback: function() {
                                            $('[data-toggle="popover"]').popover();

                                            $('.View').on('click', function (e) {
                                                    $('.View').not(this).popover('hide');
                                                });
                                        } ,
                                processing: true,
                                language: {
                                  processing: '<div class="spinner-border text-primary" role="status"></div>'
                                },
                                serverSide: true,
                                ajax: {
                                  url: baseURL + 'admin/Reports/trainingreportData',
                                  dataType: 'json',
                                  type: "POST",
                                  data: function (d) {
                                      // Include custom filters in the Ajax request data
                                      d.from_date = $('#from-datepicker').val();
                                      d.to_date = $('#to-datepicker').val();
                                      d.unit = $('#unitdata').val();
                                      d.user = $('#user').val();
                                      d.status = $('#status').val();
                                  },
                                },
                                columns: [
                                  
                                    { "data": "title" },
                                    { "data": "description" },
                                    { "data": "date_from" },
                                    { "data": "date_to" },
                                    { "data": "unit" },
                                    { "data": "view" },
                                ]
                                        
                                    });

                                     $(document).on('click', '.search', function(e) {  
                                        var unit = $("#unitdata").val();
                                        var status = $('#status').val();  
                                        var from_date = $('#from-datepicker').val(); //alert(from_date);
                                        var to_date = $('#to-datepicker').val(); //alert(to_date);
                                        var user = $('#user').val();
                                        if(from_date=='')
                                        {
                                            swal("Please select from date");
                                        }
                                        
                                        else if(to_date=='')
                                        {
                                            swal("Please select to date");
                                        }
                                         
                                        else if(unit=='none')
                                         {
                                            swal("Please select the unit");
                                         }
                                         else if(new Date(formatDate(from_date)).getTime() > new Date(formatDate(to_date)).getTime())
                                         { 
                                            swal("From date should be smaller than to date"); 
                                            event.preventDefault();
                                         }
                                         else
                                         {
                                            table.draw();
                                         }
                                       
                                        //        $.ajax({ 
                                        //                       type: "post",dataType: "json",
                                        //                       url: baseURL+"admin/Reports/findtraining",
                                        //                       data: {  
                                        //                         unit:unit,
                                        //                         status:status,
                                        //                         user:user,
                                        //                         from_date:from_date,
                                        //                         to_date:to_date
                                        //                          },
                                        //                       success: function(data) { 
                                        //                           table.clear(); 
                                        //                           table.rows.add(data).draw(); 
                                        //                       } 
                                        //                 });      
                                        //  }
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

  function formatDate(date){
  var date_array = date.split('/');
  var new_date = date_array[2]+"-"+date_array[1]+"-"+date_array[0];
  return new_date;
}


 $('#unitdata').change(function(){  

    var unitID = $('#unitdata').val(); 
            if(unitID!='none') {  $('select[name="user"]').empty();
                $.ajax({
                    type: "post",
                    dataType: "json",
                    url: baseURL+"admin/Reports/finduserdatanew",
                    data : { unit_id : unitID },
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
 