$(function() {
  $(document).ready(function() {
    var table = $('#myTable').DataTable({
      "order": [[1, 'asc']],
      "dom": 'lBfrtip',
      "buttons": ['copy', 'excel', 'pdf', 'print'],
      "displayLength": 50, 
      "lengthMenu": [[50, 75, 100, -1], [50, 75, 100, "All"]],
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
    var table = $('#myTable1').DataTable({
      // "order": [[1, 'asc']],
      "dom": 'lBfrtip',
      "buttons": ['copy', 'excel', 'pdf', 'print'],
      "displayLength": 50, 
      "lengthMenu": [[50, 75, 100, -1], [50, 75, 100, "All"]],
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
  var year = $("#year").val();
  var month = $("#month").val();
  if(year=='none')
  {
      swal("Please select year");
      e.preventDefault();
  }
  else if(month=='none')
   {
       swal("Please select month");
       e.preventDefault();
   }
   else{
    $( "#frmViewOverstaffingReport" ).submit();
   }
});