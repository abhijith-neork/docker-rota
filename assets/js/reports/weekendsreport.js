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
                                    [0, 'asc'],[3,'desc']
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
                                        } ,

                                processing: true,
                                language: {
                                  processing: '<div class="spinner-border text-primary" role="status"></div>'
                                },
                                serverSide: true,
                                ajax: {
                                  url: baseURL + 'admin/Reports/weekendsreportData',
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
                                  
                                    { "data": "fname" },
                                    { "data": "unit_name" },
                                    { "data": "designation_name" },
                                    { "data": "date" },
                                    { "data": "shift_name" },
                                ],
                                drawCallback: function () {
                                    var totalCount = table.page.info().recordsTotal;
                                    if(totalCount>0)
                                        $('#dataCount').html('Number of weekends worked : ' + totalCount);
                                    else
                                        $('#dataCount').html('');
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


                                    $('#search').on('click',function(event){  

                                           var userID = $('#user').val();   
                                           var unit_id = $('#unitdata').val();  
                                           var from_date = $('#from-datepicker').val();  
                                           var to_date = $('#to-datepicker').val(); 
                                           var status = $('#status').val();
                                           var date1 = new Date(formatDate(from_date)); 
                                           var date2 = new Date(formatDate(to_date)); 
                                           if(unit_id=='none')
                                           {
                                               swal("Please select a unit");
                                               event.preventDefault();

                                           }
                                           else if(from_date=="")
                                           {  
                                              swal("Please select from date");
                                              event.preventDefault();
                                           }
                                           else if(to_date=="")
                                           {  
                                              swal("Please select to date");
                                              event.preventDefault();
                                           }
                                           else if(date1 > date2)
                                           {
                                             swal("Start date should be smaller than or equal to end date");
                                               event.preventDefault();
                                           }
                                           else
                                           { 
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
 
  
  $('#unitdata').change(function(){  

    var unitID = $('#unitdata').val();
     var status = $('#status').val();
        if(unitID=='none')
        {
           $('select[name="user"]').empty();
        }
        else 
        { 
          $('select[name="user"]').empty();
                    $.ajax({
                        type: "post",
                        dataType: "json",
                        url: baseURL+"admin/Reports/finduserdataforall",
                        data : { unit_id : unitID, status:status },
                        success:function(data) {  

                       
                            $('select[name="user"]').append('<option value="none">--Select user--</option>');
                            $.each(data, function(key, value) {  
                                $('select[name="user"]').append('<option value="'+ value.user_id +'">'+ value.fname+' '+value.lname +'</option>');
                            });
                        }
                    });
        }
  });

   $('#status').change(function(){  

    var unitID = $('#unitdata').val();
    var status = $('#status').val();
            if(unitID) { $('select[name="user"]').empty();
                $.ajax({
                    type: "post",
                    dataType: "json",
                    url: baseURL+"admin/Reports/finduserdataforall",
                    data : { unit_id : unitID, status:status },
                    success:function(data) {  

                   
                        $('select[name="user"]').append('<option value="none">--Select user--</option>');
                        $.each(data, function(key, value) {  
                            $('select[name="user"]').append('<option value="'+ value.user_id +'">'+ value.fname+' '+value.lname +'</option>');
                        });
                    }
                });
            }else{
                $('select[name="user"]').empty();
            }
        });