$(function() {
                                
                                    $(document).ready(function() {

                                    var role = jobe_roles;  
                                      $(".select2").select2();
                                      if(role.length > 0){
                                        $('.jobrole').select2().val(role).trigger("change") 
                                      }

                                       $("#from-datepicker").datepicker({ 
                                        dateFormat: 'dd/mm/yy'
                                    });
                                    $("#from-datepicker").on("change", function () {
                                        var fromdate = $(this).val();
                                        //alert(fromdate);
                                    }); 


                                    var table = $('#myTable').DataTable({
                                    
                                    "order": [
                                    [0, 'asc']
                                    ],
                                    columnDefs: [
                                      { orderable: false, targets: [3,4] }, // Disable sorting for the 3rd column (index 2)
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
                                  url: baseURL + 'admin/Reports/workingreportData',
                                  dataType: 'json',
                                  type: "POST",
                                  data: function (d) {
                                      // Include custom filters in the Ajax request data
                                      d.from_date = $('#from-datepicker').val();
                                      d.unitdata = $('#unitdata').val();
                                      d.jobrole = $('#jobrole').val();
                                      d.shift_category = $('#shift_category').val();  
                                      d.part_of_number = $('#part_of_number').val();  
                                  },
                                },
                                columns: [
                                    { "data": "fname" },
                                    { "data": "unit_name" },
                                    { "data": "designation_name" },
                                    { "data": "shift_category" },
                                    { "data": "part_number" },
                                ]
                                        
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
  
                                           var unit_id = $('#unitdata').val();  //alert(unit_id);
                                           var from_date = $('#from-datepicker').val();  
                                           var jobrole = $('#jobrole').val();
                                           var shift_category = $('#shift_category').val(); 
                                           var part_of_number = $('#part_of_number').val();
                                           if(unit_id=='none')
                                           {
                                               swal("Please select a unit");
                                               event.preventDefault();

                                           }
                                           else if(from_date=="")
                                           {  
                                              swal("Please select a date");
                                              event.preventDefault();
                                           }
                                           else
                                           { 
                                            table.draw();
                                              //    $.ajax({
                                              //         type :'POST',
                                              //         dataType:'json',
                                              //         data : { unit_id:unit_id,from_date:from_date,jobrole:jobrole,shift_category:shift_category,part_of_number:part_of_number},
                                              //         url: baseURL+"admin/Reports/workingreport",
                                              //         success : function(result){  
                                              //          //$('#myTable').val(result); 
                                              //         // if(result=='') { 
                                              //         //         table.clear().draw();
                                              //         //        }
                                              //         //        else
                                              //         //        { 
                                              //         //         table.clear(); 
                                              //         //         table.rows.add(result).draw(); 
                                              //         //        }  


                                              //         }
                                              // }); 
                                            }

                                    });

                                           
                         });


  

  });
function formatDate(date){
  var date_array = date.split('/');
  var new_date = date_array[2]+"-"+date_array[1]+"-"+date_array[0];
  return new_date;
}
 