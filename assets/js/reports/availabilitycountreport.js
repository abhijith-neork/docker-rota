$(function() {
                                
                                    $(document).ready(function() {
$("#img-loader-data").hide();
                                    var role = jobe_roles;
                                    $(".select2").select2();
                                    if(role.length > 0){
                                      $('.user_role').select2().val(role).trigger("change") 
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
                                    "order": [[ 0, "asc" ]],
                                    // "order": [
                                    // [1, 'asc']
                                    // ],
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
                                        } 
                                        
                                    });

                                    
 
                                    });




  

  });

$(document).on('click', '.search', function(e) { 
      jobrole=$('#job_role').val(); //alert(jobrole);
      start_date=$('#start_date').val();
      end_date=$('#end_date').val();
      var date1 = new Date(formatDate(start_date)); 
      var date2 = new Date(formatDate(end_date)); 

      if(jobrole=='')
      {
        swal("Please select a jobrole group");
        event.preventDefault();
      }
      
      else if(start_date=='')
      {
          swal("Please select from date");
      }
      
      else if(end_date=='')
      {
          swal("Please select to date");
      }
       
      else if(new Date(formatDate(start_date)).getTime() > new Date(formatDate(end_date)).getTime())
      {
        swal("Start date should be smaller than end date");
        event.preventDefault();
      }
      else
      {
           e.preventDefault();

       $("#img-loader-data").show();

          $( "#frmavailReport" ).submit();
       }
    });

function formatDate(date){
  var date_array = date.split('/');
  var new_date = date_array[2]+"-"+date_array[1]+"-"+date_array[0];
  return new_date;
}

 
  
  