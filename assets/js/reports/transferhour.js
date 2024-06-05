$(function() {
                                
                                    $(document).ready(function() {

                                    var role = jobe_roles;  
                                      $(".select2").select2();
                                      if(role.length > 0){
                                        $('.jobrole').select2().val(role).trigger("change") 
                                      }

                                    $("#start_date").datepicker({ 
                                        dateFormat: 'dd/mm/yy',
                                        beforeShowDay: function(date) {
                                    var day = date.getDay();
                                    return [(day != 1 && day != 2 && day != 3 && day != 4 && day != 5 && day !=6)];
                                    }
                                    });
                                    $("#start_date").on("change", function () {
                                        var fromdate = $(this).val();
                                        //alert(fromdate);
                                    }); 

                                    $("#end_date").datepicker({ 
                                        dateFormat: 'dd/mm/yy',
                                        beforeShowDay: function(date) {
                                      var day = date.getDay();
                                      return [(day != 0 && day != 1 && day != 2 && day != 3 && day != 4 && day != 5)];
                                      }
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
                                                columns: [0,1,2,3,4,5]
                                            }
                                           
                                       },
                                       {
                                           extend: 'excel', 
                                           footer: true,
                                           exportOptions: {
                                                columns: [0,1,2,3,4,5]
                                            }
                                       },
                                       {
                                           extend: 'print',
                                           footer: true,
                                           exportOptions: {
                                                columns: [0,1,2,3,4,5]
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

$(document).on('click', '.search', function(event) { 
      start_date=$('#start_date').val();
      end_date=$('#end_date').val();
      from_unit=$('#unitdata').val(); //alert(from_unit);
      at_unit=$('#at_unit').val();//alert(at_unit);
      var date1 = new Date(formatDate(start_date)); 
      var date2 = new Date(formatDate(end_date)); 
      if(from_unit==0)
      {  
         swal("Please select from unit");
         event.preventDefault();
      }
      else if(start_date=="")
      {  
         swal("Please select from date");
         event.preventDefault();
      }
      else if(end_date=="")
      {  
         swal("Please select to date");
         event.preventDefault();
      }
      else if(from_unit==at_unit && from_unit!=0)
      {
         swal("Please select different from unit and at unit");
         event.preventDefault();
      }
      else if(new Date(formatDate(start_date)).getTime() > new Date(formatDate(end_date)).getTime())
      {
        swal("Start date should be smaller than end date");
        event.preventDefault();
      }
      else if(new Date(formatDate(start_date)).getTime() == new Date(formatDate(end_date)).getTime())
      { 
        swal("Start date should be smaller than end date"); 
        event.preventDefault();
      }
      else
      {
      $( "#frmpayrollReport" ).submit();
       }
    });

function formatDate(date){
  var date_array = date.split('/');
  var new_date = date_array[2]+"-"+date_array[1]+"-"+date_array[0];
  return new_date;
}

  
  