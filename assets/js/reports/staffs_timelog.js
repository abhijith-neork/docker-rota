$(function() {
                                
                                    $(document).ready(function() {

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


                                    var table = $('#myTable').DataTable({
                                    "order": [[ 5, "asc" ]],
                                    // "order": [
                                    // [1, 'asc']
                                    // ],
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
                                     $('#myTable tbody').on('click', 'tr.user', function() {
                                    var currentOrder = table.order()[0];
                                    if (currentOrder[0] === 2 && currentOrder[1] === 'asc') {
                                    table.order([2, 'desc']).draw();
                                    } else {
                                    table.order([2, 'asc']).draw();
                                    }
                                    });
                                    
                                   $('#search').on('click',function(event){   
                                           
                                           var from_date = $('#start_date').val();  
                                           var to_date = $('#end_date').val(); 
                                           var date1 = new Date(formatDate(from_date)); 
                                           var date2 = new Date(formatDate(to_date)); 

                                        if(from_date=="")
                                          {  
                                             swal("Please select from date");
                                             event.preventDefault();
                                          }
                                          else if(to_date=="")
                                          {  
                                             swal("Please select to date");
                                             event.preventDefault();
                                          }
                                           if(date1 > date2)
                                           {
                                              alert("Start date should be smaller than end date");
                                              event.preventDefault();
                                           }
                                           else
                                           { 
                                                 $.ajax({
                                                      type :'POST',
                                                      dataType:'json',
                                                      data : { to_date:to_date,from_date:from_date},
                                                      url: baseURL+"manager/Reports_staff/timelogview",
                                                      success : function(result){  

                                                      }
                                                }); 
                                            }

                                    });

                                    });


  

  });
function formatDate(date){
  var date_array = date.split('/');
  var new_date = date_array[2]+"-"+date_array[1]+"-"+date_array[0];
  return new_date;
}
 
  
  