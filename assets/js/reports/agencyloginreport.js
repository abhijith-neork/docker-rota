$(function() {
                                
                                    $(document).ready(function() {

                                    var role = jobe_roles;
                                      $(".select2").select2();
                                      if(role.length > 0){
                                        $('.jobrole').select2().val(role).trigger("change") 
                                      }

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
                                    "order": [[ 6, "desc" ],[ 1, "desc" ]],
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
                                    $(document).on('click', '.search', function(e) {  
                                      
                                      var from_date = $('#start_date').val(); //alert(from_date);
                                      var to_date = $('#end_date').val(); //alert(to_date);
                                      if(from_date=='')
                                      {
                                          swal("Please select from date");
                                          e.preventDefault();
                                      }
                                      
                                      else if(to_date=='')
                                      {
                                          swal("Please select to date");
                                          e.preventDefault();
                                      }
                                       
                                       else if(new Date(formatDate(from_date)).getTime() > new Date(formatDate(to_date)).getTime())
                                       { 
                                          swal("From date should be smaller than to date"); 
                                          e.preventDefault();
                                       }                              
                                     });

                                    
                                    });


  

  });
 
  
  function formatDate(date){
    var date_array = date.split('/');
    var new_date = date_array[2]+"-"+date_array[1]+"-"+date_array[0];
    return new_date;
  }
  